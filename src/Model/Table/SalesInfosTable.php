<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Table;
use App\Model\Entity\SalesInfo;
use App\Model\Value\ConfigsValue;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

/**
 * Class SalesInfosTable
 * @package App\Model\Table
 * @author vo.chau
 * @date 2017-Jan-18
 */
class SalesInfosTable extends BaseTable
{
    /**
     * initialize repository
     *
     * @param array $config override configurations
     */
    public function initialize(array $config)
    {
        // initialize for parent
        parent::initialize($config);

        /*
         * configure mapping for Sales Infos
         * 1. has many sale details
         * 2. has many payment details (none or one)
         * 3. belongs to reservation infos
         * 4. belongs to reservation details
         * 5. belongs to equipment infos
         *
         * @author vo.chau
         */
        $this->addAssociations([
            'hasMany' => [
                'SalesDetails' => [
                    'foreignKey' => 'sales_info_id',
                    'className' => 'App\Model\Table\SalesDetailsTable'
                ],
                'PaymentDetails' => [
                    'foreignKey' => 'sales_info_id',
                    'className' => 'App\Model\Table\PaymentDetailsTable'
                ]
            ],
            'belongsTo' => [
                'ReservationInfos' => [
                    'foreignKey' => 'reservation_info_id',
                    'className' => 'App\Model\Table\ReservationInfosTable'
                ],
                'ReservationDetails' => [
                    'foreignKey' => 'reservation_detail_id',
                    'className' => 'App\Model\Table\ReservationDetailsTable'
                ],
                'EquipmentInfos' => [
                    'foreignKey' => 'equipment_info_id',
                    'className' => 'App\Model\Table\EquipmentInfosTable'
                ]
            ]
        ]);
    }

    //==== Command ====
    /**
     * Find the sales info by reservation detail, or create a new one if don't have sale info yet.
     * Using in room charge/cashier related to room
     *
     * ```
     * $options = [
     *      'sales_info_id' => 1,
     *      'reload_detail' => true/1 // reload with sales details
     * ]
     * $options = [
     *      'reservation_detail_id' => 1,
     *      'disable_auto_create' => true/1 // disable auto create by reservation detail
     * ]
     *
     * ```
     * @author vo.chau
     * @date 2017-10-06
     * @param array $options
     * @return \App\Model\Entity\SalesInfo|null|bool
     */
    public function commandGetOrCreate($options)
    {
        $salesInfo = null;
        $disableAutoCreate = !empty($options['disable_auto_create']) ? $options['disable_auto_create'] : false;
        // If not exist sales info id then add new/get one
        if (!empty($options['sales_info_id'])) {
            $query = $this->findById($options['sales_info_id']);
            if (!empty($options['reload_detail'])) {
                $query->find('Detail', []);
            }
            return $query->firstOrFail();
        } elseif (!empty($options['reservation_detail_id'])) {
            $salesInfo = $this->getByReservationDetail($options['reservation_detail_id']);
            if ($salesInfo == null && !$disableAutoCreate) {
                $salesInfo = $this->createNewEntityByReservationDetail($options['reservation_detail_id']);
            }
        } else {
            $salesInfo = $this->createNewEntity();
        }
        // @memo: reload detail of this sales info
        if (!empty($options['reload_detail']) && $salesInfo) {
            $salesInfo = $this
                ->findById($salesInfo->id)
                ->find('Detail', [])
                ->firstOrFail();
        }
        return $salesInfo;
    }

    /**
     * @author vo.chau
     * @date 2017-10-09
     * @param $reservationDetailId
     * @return SalesInfo
     */
    private function getByReservationDetail($reservationDetailId)
    {
        return $this
            ->find()
            ->select([
                'id',
                'operator_code',
                'reservation_info_id',
                'reservation_detail_id',
                'equipment_info_id',
                'folio_language',
                'paid',
                'modified_employee_id'
            ])
            ->contain([
                'SalesDetails' => [
                    'fields' => ['id', 'operator_code', 'sales_info_id', 'description_id', 'sales_date', 'sales_datetime',
                        'nominal_price', 'sales_price', 'quantity','equipment_info_id'],
                    'queryBuilder' => function(Query $q) {
                        return $q->order(['sales_datetime' => 'asc']);
                    }
                ],
                'SalesDetails.PaymentDetails' => [
                    'fields' => ['id', 'operator_code', 'sales_info_id', 'sales_detail_id', 'payment_amount']
                ],
                'SalesDetails.Descriptions' => [
                    'fields' => ['id', 'description_code', 'description_kind', 'name', 'abbreviation',
                        'valid_sale_period_start', 'valid_sale_period_end', 'price_kind', 'price',
                        'tax_application', 'tax_attribution', 'sale_enable', 'description_category_id']
                ],
                'SalesDetails.EquipmentInfos' => [
                    'fields' => ['id', 'equipment_code']
                ],
            ])
            ->find('ByReservationDetail', ['reservation_detail_id' => $reservationDetailId])
            ->find('NotPaid', [])
            ->first();
    }

    /**
     * @author vo.chau
     * @date 2017-10-09
     * @param $reservationDetailId
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function createNewEntityByReservationDetail($reservationDetailId)
    {
        $reservationDetail = $this->getReservationDetailAndEquipments($reservationDetailId);
        // todo: do we allow get checkout reservation?
        if ($reservationDetail->check_out_date != null) {
            Log::info("This reservation detail [{$reservationDetailId}] is already check out!");
            return false;
        }
        $resEquip = $reservationDetail->getCurrentEquipmentReservation();
        // Get folio_language from system_language of client_infos
        // If null then get from system_language of locale_infos
        $folioLanguage = $this->getFolioLanguage(
            $reservationDetail->client_info_id,
            $reservationDetail->reservation_info_id);

        $salesInfo = new SalesInfo();
        // Patch data to sales info entity
        $this->patchEntity($salesInfo, [
            'operator_code'         => $reservationDetail->operator_code,
            'branch_code'         =>   $reservationDetail->reservation_info->branch_code,
            'facility_code'         => $reservationDetail->reservation_info->facility_code,
            'reservation_info_id'   => $reservationDetail->reservation_info_id,
            'reservation_detail_id' => $reservationDetail->id,
            'equipment_info_id'     => $resEquip->equipment_info_id,
            'folio_language'        => $folioLanguage,
            'paid'                  => ConfigsValue::FLV_FALSE
        ]);
        return $this->save($salesInfo);
    }

    /**
     * @author vo.chau
     * @date 2017-10-09
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function createNewEntity()
    {
        $salesInfo = new SalesInfo();
        $salesInfo->operator_code = $this->getContext()->getOperatorCode();
        $salesInfo->folio_language = ConfigsValue::FOLIO_LANG_DEFAULT;
        $salesInfo->paid = ConfigsValue::FLV_FALSE;
        return $this->save($salesInfo);
    }

    //==== Finder ====
    /**
     * @author vo.chau
     * @date 2017-10-09
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findByReservationDetail(Query $query, $options = [])
    {
        if (!empty($options['reservation_detail_id'])) {
            $query->where(['reservation_detail_id' => $options['reservation_detail_id']]);
        }
        return $query;
    }

    /**
     * Finder only not paid sales infos
     *
     * @author vo.chau
     * @date 2017-10-11
     * @param Query $query
     * @param array $options
     * @return Query
     */
    public function findNotPaid(Query $query, $options = [])
    {
        $query
            ->where([
                'OR' => [
                    ['paid' => ConfigsValue::FLV_FALSE],
                    ['paid IS NULL'],
                ]
            ]);
        return $query;
    }

    /**
     * @author vo.chau
     * @date 2017-10-09
     * @param $reservationDetailId
     * @return mixed
     */
    public function getReservationDetailAndEquipments($reservationDetailId)
    {
        return TableRegistry::get('ReservationDetails')
            ->find()
            ->where([
                'ReservationDetails.id' => $reservationDetailId,
                'ReservationDetails.canceled' => ConfigsValue::FLV_FALSE
            ])
            ->contain(['ReservationEquipments' => function($q) {
                return $q->order(['use_start_date' => 'asc']);
            }])
            ->contain(['ReservationInfos'])
            ->firstOrFail();
    }

    /**
     * Get folio language (invoice printing language)
     * Find from client table by client info id
     * If null then find from locale info table
     * If null then get default constant
     *
     * @author vo.chau
     * @date 2017-10-09
     * @param int $clientInfoId
     * @param int $reservationInfoId
     * @return string
     */
    protected function getFolioLanguage($clientInfoId = 0, $reservationInfoId = 0)
    {
        // Get from table client_infos
        /** @var \App\Model\Table\ClientInfosTable $table */
        $table = TableRegistry::get("ClientInfos");

        $client = $table->find()
            ->where(['id' => $clientInfoId])
            ->first();
        if ($client && $client->system_language) {
            return $client->system_language;
        }
        // Get reservation info to find the operator code and branch code to finding locale info
        $reservationInfo = TableRegistry::get("ReservationInfos")->find()
            ->where(['id' => $reservationInfoId])
            ->first();
        if ($reservationInfo) {
            // Get from table locale_infos
            $locale = TableRegistry::get("LocaleInfos")->find()
                ->where([
                    'operator_code' => $reservationInfo->operator_code,
                    'branch_code' => $reservationInfo->branch_code
                ])
                ->first();
            if ($locale) {
                return $locale->system_language;
            }
        }
        // Get default configuration
        return ConfigsValue::FOLIO_LANG_DEFAULT;
    }
}