<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Table;
use App\Model\Value\ConfigsValue;
use App\Model\Value\DescriptionKindValue;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Cake\I18n\Date;
use Cake\ORM\TableRegistry;

/**
 * Class DescriptionsTable
 * @package App\Model\Table
 * @author chau.vo <vo.thi.trang.chau@suzukishouten.co.jp>
 */
class DescriptionsTable extends BaseTable
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
         * configure mapping for Descriptions
         * 1. has one equipment charge infos
         * 2. has many sales details
         * 3. belongs to description categories
         * @author ty.huynh
         */
        $this->addAssociations([
            'hasOne' => [
                'EquipmentChargeInfos' => [
                    'foreignKey' => 'description_id',
                    'className' => 'App\Model\Table\EquipmentChargeInfosTable'
                ]
            ],
            'hasMany' => [
                'SalesDetails' => [
                    'foreignKey' => 'description_id',
                    'className' => 'App\Model\Table\SalesDetailsTable'
                ],
                'CurrencyConversions' => [
                    'bindingKey' => ['operator_code'],
                    'foreignKey' => ['operator_code'],
                    'className' => 'App\Model\Table\CurrencyConversionsTable'
                ]
            ],
            'belongsTo' => [
                'FacilityInfoRever' => [
                    'foreignKey' => 'transfer_origin_facility_id',
                    'className' => 'App\Model\Table\FacilityInfosTable'
                ]
            ]
        ]);
        $this->belongsTo('BranchInfos', [
            'bindingKey' => ['branch_code'],
            'foreignKey' => ['branch_code']
        ]);
        $this->belongsTo('DescriptionCategories');
        $this->belongsTo('FacilityInfos', [
            'bindingKey' => ['facility_code','branch_code'],
            'foreignKey' => ['facility_code','branch_code']
        ]);

    }
}