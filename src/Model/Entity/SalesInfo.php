<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Entity;
use App\Model\Value\ConfigsValue;
use App\Model\Value\DescriptionKindValue;
use App\Model\Value\PriceKindValue;
use Cake\I18n\Time;
use Cake\ORM\Entity;

/**
 * Class SalesInfo
 * @package App\Model\Entity
 * @author ty.huynh <huynh.hong.ty@suzutek.vn>
 * @date 2017-Jan-18
 * @property int id
 * @property string operator_code
 * @property int reservation_info_id
 * @property int reservation_detail_id
 * @property int equipment_info_id
 * @property string folio_language
 * @property int is_paid
 * @property \Cake\I18n\Time sales_datetime
 * @property int modified_employee_id
 * @property \Cake\I18n\Time created_date
 * @property \Cake\I18n\Time modified_date
 * @property \Cake\I18n\Time deleted_date
 * @property array payment_details
 * @property array sales_details
 */
class SalesInfo extends Entity
{

    protected $_hidden = ['paid'];
    protected $_virtual = ['is_paid'];

    /**
     * @author ty.huynh <huynh.hong.ty@suzutek.vn>
     * @return int
     */
    public function _getIsPaid()
    {
//        $paid = 0;
//        if ($this->payment_details != null && count($this->payment_details) > 0) {
//            // Using array_values to reset keys after filter
//            $paymentDetails = array_values(array_filter($this->payment_details, function($item) {
//                return $item->sales_detail_id == null;
//            }));
//            // Exist one payment detail with sales_detail_id = null
//            if ($paymentDetails != null && count($paymentDetails) > 0) {
//                $paid = 1;
//            }
//        }
        return !empty($this->paid) ? 1 : 0;
    }

    /**
     * Remove paid up sales details of sales info
     *
     * @author ty.huynh <huynh.hong.ty@suzutek.vn>
     * @date 2017-10-09
     * @param bool $showDetailPaidSalesInfo want to show sales detail of paid sales info
     * @return $this
     */
    public function removePaidUpSalesDetails($showDetailPaidSalesInfo = false)
    {
        if (empty($this->sales_details)) {
            return $this;
        }
        if (!$showDetailPaidSalesInfo && $this->is_paid) {
            $this->sales_details = [];
            return $this;
        }
        // Using array_values to reset keys after filter
        // Get not paid and not attribution
        $this->sales_details = array_values(array_filter($this->sales_details, function($item) {
            /** @var \App\Model\Entity\SalesDetail $item */
            return $item->payment_detail == null && $item->description->tax_attribution == 0;
        }));
        return $this;
    }

    /**
     * Remove not transfer description kind like tax, additional, deposit, discount.
     * Using in transfer to filter list sales detail can not transfer
     *
     * @author ty.huynh <huynh.hong.ty@suzutek.vn>
     * @date 2017-10-10
     * @return $this
     */
    public function removeNotTransferDescriptionKind()
    {
        if (empty($this->sales_details)) {
            return $this;
        }
        // Using array_values to reset keys after filter
        $this->sales_details = array_values(array_filter($this->sales_details, function($item) {
            /** @var \App\Model\Entity\SalesDetail $item */
            return !$item->not_show_cashier;
        }));
    }

    /**
     * Calculate total discount rate of one sales info
     * Use to check discount rate can exceed 100 or not
     *
     * @author ty.huynh <huynh.hong.ty@suzutek.vn>
     * @date 2017-10-13
     * @return float
     */
    public function getTotalDiscountRateOfSalesInfo()
    {
        $discountRate = 0.0;
        if ($this->sales_details) {
            foreach ($this->sales_details as $salesDetail) {
                if ($salesDetail->description
                    && $salesDetail->description->description_kind == DescriptionKindValue::DRK_DISCOUNT
                    && $salesDetail->description->price_kind == PriceKindValue::PRK_DISC_RATE) {
                    $discountRate += $salesDetail->sales_price;
                }
            }
        }
        return $discountRate;
    }

    /**
     * Clone a sales info
     * ```
     * $options = [
     *      'sales_detail_ids' : [1,2,3],
     *      'paid' : 1/0 (true/false)
     *      'reset_sales_detail' : 1/0 (true/false)
     * ]
     * ```
     * @author ty.huynh <huynh.hong.ty@suzutek.vn>
     * @date 2017-10-10
     * @param array $options
     * @return SalesInfo
     */
    public function _clone($options = [])
    {
        $entity = new SalesInfo();
        $entity->id = null;
        $entity->operator_code = $this->operator_code;
        $entity->folio_language = $this->folio_language;
        $entity->reservation_info_id = $this->reservation_info_id;
        $entity->reservation_detail_id = $this->reservation_detail_id;
        $entity->equipment_info_id = $this->equipment_info_id;
        $entity->sales_datetime = $this->getContext()->getLocalDateTime();
        if (!empty($options['paid'])) {
            $entity->paid = $entity->is_paid = $options['paid'];
        } else {
            $entity->paid = $entity->is_paid = ConfigsValue::FLV_FALSE;
        }
        $entity->sales_details = [];
        if (!empty($options['sales_detail_ids']) && !empty($this->sales_details)) {
            foreach($this->sales_details as $salesDetail) {
                if (in_array($salesDetail->id, $options['sales_detail_ids'])) {
                    if (!empty($options['reset_sales_detail'])) {
                        $entity->sales_details[] = $salesDetail->_clone();
                    } else {
                        $entity->sales_details[] = $salesDetail;
                    }
                }
            }
        }
        return $entity;
    }

}