<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Table;

use App\Model\Entity\Description;
use App\Model\Entity\SalesDetail;
use App\Model\Value\ConfigsValue;
use App\Model\Value\DescriptionKindValue;
use App\Model\Value\PriceKindValue;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Class SalesDetailsTable
 * @package App\Model\Table
 * @author ty.huynh <huynh.hong.ty@suzutek.vn>
 * @date 2017-Jan-18
 */
class SalesDetailsTable extends BaseTable
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
         * configure mapping for Sales details
         * 1. has one payment details or not
         * 2. belongs to sales infos
         * 3. belongs to descriptions
         *
         * @author ty.huynh
         */
        $this->addAssociations([
            'hasOne' => [
                'PaymentDetails' => ['className' => 'App\Model\Table\PaymentDetailsTable']
            ],
            'belongsTo' => [
                'SalesInfos' => [
                    'foreignKey' => 'sales_info_id',
                    'className' => 'App\Model\Table\SalesInfosTable'
                ],
                'Descriptions' => [
                    'foreignKey' => 'description_id',
                    'className' => 'App\Model\Table\DescriptionsTable'
                ],
                'EquipmentInfos' => [
                    'foreignKey' => 'equipment_info_id',
                    'className' => 'App\Model\Table\EquipmentInfosTable'
                ]
            ]
        ]);
    }
}