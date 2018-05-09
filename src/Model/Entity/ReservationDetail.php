<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Entity;
use Cake\Collection\Collection;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use App\Model\Value\ConfigsValue;
use App\Model\Value\DescriptionKindValue;
use App\Model\Value\PriceKindValue;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * Class _ReservationDetail
 * @package App\Model\Entity
 * @author chau.vo <vo.thi.trang.chau@suzukishouten.co.jp>
 */
class ReservationDetail extends Entity
{

    protected $_virtual = ['amount', 'flag_check_out'];
    /**
     * Get currency conversion when process exchange amount
     *
     * @author vo.chau
     * @date 2017-10-12
     * @param int $currencyId
     * @param string|null $branchCode
     * @param string|null $facilityCode
     * @param string|null $operatorCode
     * @return \App\Model\Entity\CurrencyConversion|mixed|null
     */
    public function getCurrencyConversion($currencyId = 0, $branchCode = null, $facilityCode = null, $operatorCode = null, $user = null)
    {
        $loginUser = $this->getContext()->getLoginUser();
        $operatorCode = $loginUser['profile']['operator_code'];
        $currency = null;
        $table = TableRegistry::get('CurrencyConversions');
        if ($currencyId) {
            $currency = $table->find()->where(['id' => $currencyId])->first();
        } 
        if ($currency) {
            return $currency;
        }
        // if null then get default currency setting
        $currency = $table->find()->where(['operator_code' => $operatorCode,
            'branch_code'   => $branchCode,
            'facility_code' => $facilityCode,
            'is_base' => ConfigsValue::FLV_TRUE])->first();
        if ($currency) {
            return $currency;
        }

        // get currency by operator code and branch code & is base
        $currency = $table->find()->where(['operator_code' => $operatorCode,
            'branch_code'   => $branchCode,
            'is_base' => ConfigsValue::FLV_TRUE])->first();
        if ($currency) {
            return $currency;
        }
        // get currency by operator code & is base
        $currency = $table->find()->where(['operator_code' => $operatorCode,
            'is_base' => ConfigsValue::FLV_TRUE])->first();
        return $currency;
    }

    public function _getAmount()
    {
        if ( empty($this->sales_infos) ) {
            return null;
        }
        if ( !empty($this->client_info) ) {
            $currency = $this->getCurrencyConversion(
                $this->client_info->currency_conversion_id,
                $this->reservation_info->branch_code,
                $this->reservation_info->facility_code
            );
        } else {
            $currency = $this->getCurrencyConversion(
                0,
                $this->reservation_info->branch_code,
                $this->reservation_info->facility_code
            );
        }
        $this->formatDisplaySalesInfo();
        $this->addCalculationInfoToSalesInfo($this->sales_info, $currency); 
        $total =  $this->sales_info->balance;
        $reservationDetail['total'] = $total;
        return $total;
    }


    /**
     * Perform calculation of list sales details
     *
     * - Remove paid sales detail
     * - Ignore sales detail has description with tax_attribution > 0
     * - Calculate the discount
     * - Calculate the deposit
     * - Calculate subtotal += sales price * quantity
     * - Calculate service fee list: apply if tax_attribution of service & tax_application  = 1
     * - Calculate tax list: apply if tax_attribution of tax & tax_application = 1
     * - Calculate tax for service fee
     * - Calculate total = subtotal - discount + tax + service fee
     * - Calculate balance = total - deposit
     * - Calculate refund = deposit - total
     * - Filter tax and service = 0
     *
     * @author vo.chau
     * @date 2017-Feb-09
     * @param array $salesDetails list of item need to be calculated
     * @return array
     */
    public function calculateSalesAmount($salesDetails)
    {
        // Prevent null variable
        if ($salesDetails == null) {
            $salesDetails = [];
        }
        // Allow calculate one SalesDetail
        if (!is_array($salesDetails)) {
            $salesDetails = array($salesDetails);
        }
        $subTotal = 0.0;
        $discount = 0.0;
        $deposit = 0.0;
        $taxes = $this->getListApplications(ConfigsValue::DRK_TAX);
        $serviceFees = $this->getListApplications(ConfigsValue::DRK_ADDITIONAL);

        $discountItems = [];

        /** @var \App\Model\Entity\SalesDetail $salesDetail */
        foreach ($salesDetails as $salesDetail) {
            // If salesDetail is add by vat or service then ignore it (ignore description has tax_attribution)
            if ($salesDetail && $salesDetail->description
                && $salesDetail->description->tax_attribution > 0) {
                continue;
            }
            // Remove paid sales detail
            if ($salesDetail->payment_detail == null) {

                // Calculate discount
                if ($salesDetail->description && $salesDetail->description->description_kind == DescriptionKindValue::DRK_DISCOUNT) {
                    $discountItems[] = $salesDetail;
                    continue;
                }

                // Calculate deposit
                if ($salesDetail->description && $salesDetail->description->description_kind == DescriptionKindValue::DRK_DEPOSIT) {
                    $deposit += $salesDetail->sales_price;
                    continue;
                }

                $subTotal += $salesDetail->sales_price * $salesDetail->quantity;
                // Calculate service fee
                foreach ($serviceFees as $serviceFee) {
                    if (!isset($serviceFee->amount)) {
                        $serviceFee->amount = 0.0;
                    }
                    // Check if this sales detail apply service fee
                    $applyFee = $serviceFee->tax_attribution & $salesDetail->description->tax_application;

                    if ($applyFee) {
                        $serviceFee->amount += $salesDetail->sales_price * $salesDetail->quantity;
                    }
                }
                // Calculate tax
                foreach ($taxes as $tax) {
                    if (!isset($tax->amount)) {
                        $tax->amount = 0.0;
                    }
                    // Check if this sales detail apply tax
                    $applyTax = $tax->tax_attribution & $salesDetail->description->tax_application;

                    if ($applyTax) {
                        $tax->amount += $salesDetail->sales_price * $salesDetail->quantity;
                    }
                }
            }
        }

        // Calculate discount after have subTotal
        foreach($discountItems as $item) {
            /** @var \App\Model\Entity\SalesDetail $item */
            if ($item->description->price_kind == PriceKindValue::PRK_DISC_AMOUNT) {
                $discount += $item->sales_price;
            } else {
                $discount += ($item->sales_price * $subTotal / 100);
            }
        }

        // Calculate total
        $total = $subTotal - $discount;

        // Calculate % service fee and add to total
        foreach ($serviceFees as $serviceFee) {
            $serviceFee->amount = $serviceFee->amount * $serviceFee->price / 100;
            $total += $serviceFee->amount;
        }

        // Calculate % tax and add to total
        foreach ($taxes as $tax) {
            foreach ($serviceFees as $serviceFee) {
                // Check if this service fee apply tax
                $applyTax = $tax->tax_attribution & $serviceFee->tax_application;

                if ($applyTax) {
                    $tax->amount += $serviceFee->amount;
                }
            }
            $tax->amount = $tax->amount * $tax->price / 100;

            $total += $tax->amount;
        }

        // Remove 0.0 amount from tax or service fee
        $serviceFees = array_values(array_filter($serviceFees, function($item) {
            return $item->amount > 0.0;
        }));
        $taxes = array_values(array_filter($taxes, function($item) {
            return $item->amount > 0.0;
        }));

        // Fixme: balance and refund maybe conflict together
        // Calculate balance and refund
        $balance = ($total >= $deposit) ? $deposit - $total : 0;
        // refund = 0 if not enough deposit
        $refund = ($total >= $deposit) ? 0 : $deposit - $total;

        $result = array(
            'sub_total' => $subTotal,
            'discount' => $discount,
            'service_fee' => $serviceFees,
            'tax' => $taxes,
            'deposit' => $deposit,
            'refund' => $refund,
            'balance' => $balance,
            'total' => $total
        );

        return $result;
    }

    /**
     * Add calculation information to sales info
     *
     * @author vo.chau
     * @date 2017-Feb-13
     * @param \App\Model\Entity\SalesInfo $salesInfo
     * @param null|\App\Model\Entity\CurrencyConversion $currency
     */
    public function addCalculationInfoToSalesInfo(&$salesInfo, $currency = null)
    {
        if ($salesInfo === null) {
            return;
        }
        $salesAmounts = $this->calculateSalesAmount($salesInfo->sales_details);
        $salesInfo->sub_total = $salesAmounts['sub_total'];
        $salesInfo->discount = $salesAmounts['discount'];
        $salesInfo->service_fee = $salesAmounts['service_fee'];
        $salesInfo->tax = $salesAmounts['tax'];
        $salesInfo->deposit = $salesAmounts['deposit'];
        $salesInfo->refund = $salesAmounts['refund'];
        $salesInfo->balance = $salesAmounts['balance'];
        $salesInfo->total = $salesAmounts['total'];
        // Add exchange rate for client can process and show price in correct currency
        if ($currency != null) {
            $salesInfo->currency_conversion = [
                'id' => $currency->id,
                'currency_code' => $currency->currency_code,
                'exchange_rate' => $currency->exchange_rate,
                'after_dec_point' => $currency->after_dec_point
            ];
        }
    }

    /**
     *
     * Get list of tax and service fee applications in system
     *
     * - description_kind = 3 (tax) = 4 (additional)
     * - tax_attribution != 0
     *
     * @author vo.chau
     * @date 2017-Feb-10
     * @param int $descriptionKind
     * @see \App\Model\Value\ConfigsValue::DRK_*
     * @return \Cake\Collection\Collection|\Cake\Collection\CollectionInterface|\Cake\Collection\Iterator\FilterIterator|null
     */
    public function getListApplications($descriptionKind = 0)
    {
        $applications = TableRegistry::get('Descriptions')
            ->find()
            ->select(['id', 'description_kind', 'tax_attribution', 'tax_application', 'name', 'price', 'price_kind', 'valid_sale_period_start', 'valid_sale_period_end', 'description_category_id'])
            ->distinct(['id', 'description_kind', 'tax_attribution', 'tax_application', 'name', 'price', 'price_kind', 'valid_sale_period_start', 'valid_sale_period_end', 'description_category_id'])
            ->find('AvailableByKind', ['description_kind' => $descriptionKind])
            ->where(['tax_attribution >' => 0])
            ->order(['description_kind' => 'DESC', 'tax_attribution' => 'ASC', 'valid_sale_period_start' => 'DESC'])
            ->all();

        $taxAttribute = 0;

        $applications = $applications->filter(
            function($item) use(&$taxAttribute) {
                if ($item->tax_attribution != $taxAttribute) {
                    $taxAttribute = $item->tax_attribution;
                    return true;
                } else {
                    return false;
                }
            })->toArray();

        return $applications;
    }

    /**
     * explore the list of reservation equipment stayed
     *
     * @author vo.chau
     * @date 2017-10-09
     * @return \Cake\Collection\Collection|null
     */
    public function getStayedEquipmentReservation()
    {
        // Filter and get current equipment
        $reservationEquipments = $this->reservation_equipments;
        if (empty($reservationEquipments)) {
            return null;
        }
        $currentEquipments = (new Collection($reservationEquipments))->filter(function ($item) {
            return $item['last_used_date'] != null;
        });
        if ($currentEquipments && $currentEquipments->isEmpty()) {
            return null;
        }
        // fixme: have to check order of last_used_date or by use_start_date
        return $currentEquipments;
    }

    /**
     * explore the last use of reservation equipment
     *
     * @author vo.chau
     * @date 2017-10-09
     * @return \App\Model\Entity\ReservationEquipment|null
     */
    public function getCurrentEquipmentReservation()
    {
        $listStayedResEquips = $this->getStayedEquipmentReservation();
        if ($listStayedResEquips != null) {
            Log::info("Found last used reservation equipment");
            return $listStayedResEquips->last();
        }
        Log::info("NOT Found last used reservation equipment");
        return null;
    }

    /**
     * explore the first use of reservation equipment
     *
     * @author vo.chau
     * @date 2017-10-09
     * @return \App\Model\Entity\ReservationEquipment|null
     */
    public function getFirstEquipmentReservation()
    {
        $listStayedResEquips = $this->getStayedEquipmentReservation();
        if ($listStayedResEquips != null) {
            Log::info("Found first used reservation equipment");
            return $listStayedResEquips->first();
        }
        Log::info("NOT Found first used reservation equipment");
        return null;
    }

    /**
     * format display sales info
     * @author vo.chau
     * @date 2017-10-10
     * @return $this
     */
    public function formatDisplaySalesInfo()
    {
        if (!empty($this->sales_infos)) {
            $this->sales_info = $this->sales_infos[0];
        } else {
            $this->sales_info = null;
        }
        unset($this->sales_infos);
        return $this;
    }

    /**
     * Check if this reservation detail can be paid?
     *
     * @author vo.chau
     * @date 2017-10-11
     * @return bool
     */
    public function canBePaid()
    {
        return $this->inhouse && !$this->canceled && ($this->check_out_date == null);
    }

    /**
     * Check this reservation details was paid all sales infos
     *
     * @return bool
     */
    public function isPaid()
    {
        return TableRegistry::get('SalesInfos')->isPaidByReservationDetail([
            'reservation_detail_id' => $this->id
        ]);
    }
}