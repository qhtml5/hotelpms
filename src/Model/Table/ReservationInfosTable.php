<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Table;

use Cake\Validation\Validator;
use Cake\I18n\Date;
use Cake\ORM\TableRegistry;

/**
 * Class ReservationInfosTable
 * @package App\Model\Table
 * @author chau.vo <vo.thi.trang.chau@suzukishouten.co.jp>
 */
class ReservationInfosTable extends BaseTable
{
    /**
     * 初期設定
     * @param array $config
     */
    public function initialize(array $config)
    {
        // apply the parent's configuration
        parent::initialize($config);
        $this->hasMany('ReservationDetails', [
            'foreignKey' => 'reservation_info_id',
            'className' => 'App\Model\Table\ReservationDetailsTable'
        ]);
        $this->hasOne('ReservationEquipments', [
            'foreignKey' => 'reservation_info_id',
            'className' => 'App\Model\Table\ReservationEquipmentsTable'
        ]);
        $this->belongsTo('ClientInfoRever', [
            'foreignKey' => 'client_info_id',
            'className' => 'App\Model\Table\ClientInfosTable'
        ]);
        $this->belongsTo('AgentInfos');
        /*
         * Reservation infos has many sale infos
         * @ty.huynh
         */
        $this->hasMany('SalesInfos', [
            'foreignKey' => 'reservation_info_id',
            'className' => 'App\Model\Table\SalesInfosTable'
        ]);
        $this->belongsTo('ClientInfos', [
            'foreignKey' => 'client_info_id',
            'className' => 'App\Model\Table\ClientInfosTable'
        ]);
        /*
         * Reservation infos has many reservation charges
         * @date 2017-Feb-16
         * @ty.huynh
         */
        $this->hasMany('ReservationCharges', [
            'foreignKey' => 'reservation_info_id',
            'className' => 'App\Model\Table\ReservationChargesTable'
        ]);

        $this->belongsTo('FacilityInfos', [
            'bindingKey' => ['facility_code','branch_code'],
            'foreignKey' => ['facility_code','branch_code']
        ]);

        $this->hasMany('GuestInfos', [
            'foreignKey' => 'reservation_info_id',
            'className' => 'App\Model\Table\GuestInfosTable'
        ]);
    }


    /**
     * Validate data add new reservation
     *
     * @author chau.vo <vo.thi.trang.chau@suzukishouten.co.jp>
     * @return \Cake\Validation\Validator
     */

    public function getValidationCreateReservationInfoStandard()
    {

        $validator = new Validator();
        $validator
            /** client_info_id */
            // requied
            ->requirePresence('client_info_id', true, '')
            //  not null
            ->notEmpty('client_info_id', 'Client Info must enter')
            //  must number
            ->integer('client_info_id', 'Client Info must number')
            /** first_arival_date */
            // requied
            ->requirePresence('first_arrival_date', true, '')
            //  not null
            ->notEmpty('first_arrival_date', 'First Arrival Date must enter')
            // Check valid date
            ->date('first_arrival_date', ['ymd'], 'Date is not valid format.')
            /** first_arival_date */
            // requied
            ->requirePresence('last_departure_date', true, '')
            //  not null
            ->notEmpty('last_departure_date', 'Last Departure Date must enter')
            // Check valid date
            ->date('last_departure_date', ['ymd'], 'Date is not valid format.')
            // compare last_departure_date
            ->add('last_departure_date', 'custom', [
                'rule' => function ($value, $context) {
                    $first_arrival_date = new Date($context['data']['first_arrival_date']);
                    $last_departure_date = new Date($value);
                    return $last_departure_date >= $first_arrival_date;
                },
                'message' => "Last departure date must be greater than or equal to first arrival date"
            ])

            // Check does exist?
            ->add('first_arrival_date', array(
                'custom' => [
                    'rule' => [$this, 'checkBusinessDate'],
                    'message' => "First Arrival date must be greater than or equal to Bussiness Date"
                ]
            ))

            /** canceled */
            // requied
            ->requirePresence('canceled', true, '')
            //  not null
            ->notEmpty('canceled', 'Canceled must enter')
            //  must integer
            ->integer('canceled', 'Canceled must number')
            /** canceled */
            // requied
            ->requirePresence('no_show', true, '')
            //  not null
            ->notEmpty('no_show', 'No Show must enter')
            //  must integer
            ->integer('no_show', 'No Show must number')
            /** path_of_reservation */
            // requied
            ->requirePresence('path_of_reservation', true, '')
            //  not null
            ->notEmpty('path_of_reservation', 'path_of_reservationmust enter')
            //  must integer
            ->integer('path_of_reservation', 'path_of_reservation must number')
            /** path_of_reservation */
            // requied
            ->requirePresence('path_of_reservation', true, '')
            //  not null
            ->notEmpty('path_of_reservation', 'Path reservation must enter')
            //  must integer
            ->integer('path_of_reservation', 'Path reservation must number')
            /** agent_info_id */
            // requied
            ->notEmpty('agent_info_id', 'Agent info id is a required field', function ($context) {
                return ($context['data']['path_of_reservation'] == 1);
            })
        //  must integer
            /** created_date */
            // requied
            ->requirePresence('created_date', true, '')
            //  not null
            ->notEmpty('created_date', 'Created Date must enter');


        return $validator;
    }

    /**
     * Getting the current business date
     *
     * @author chau.vo <vo.thi.trang.chau@suzutek.vn>
     * @date 2017-Apr-24
     * @return \Cake\I18n\Date
     */
    public function checkBusinessDate( $value, $context )
    {

        // Get Branch Code
        $data = getallheaders();
        if ( isset( $data['x-pms-branch-code'] ) ) {
            $branch_code = $data['x-pms-branch-code'];
            if ( $branch_code )  {
                $branch_code = $data['x-pms-branch-code'];
            } else {
               $branch_code =   null;
            }
        } else {
            $branch_code = null;
        }

        $businessDate = null;
        
        // Get Branch Code
        if ($branch_code != null) {
            $businessDate = TableRegistry::get('BusinessDates')
                ->find()
                ->where(['branch_code' => $branch_code])
                ->first();
        }

        if ($businessDate == null) {
             $business_date = new Date();
        } else {
            $business_date = $businessDate->business_date;
        }

         $business_date = new Date($business_date);

         // Get Business Date
        $current_date = $business_date->i18nFormat("yyyy-MM-dd");
        $temp = strtotime($value) >= strtotime($current_date);
        return $temp;
    }
}