<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Class DescriptionsTable
 * @package App\Model\Table
 * @author ty.huynh <huynh.hong.ty@suzutek.vn>
 */
class DescriptionCategoriesTable extends BaseTable
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
         * configure mapping for Description Categories
         * 1. has many descriptions
         *
         * @author ty.huynh
         */
        $this->addAssociations([
            'hasMany' => [
                'Descriptions' => [
                    'foreignKey' => 'description_category_id',
                    'className' => 'App\Model\Table\DescriptionsTable'
                ]
            ]
        ]);

        $this->belongsTo('BranchInfos', [
            'bindingKey' => ['branch_code'],
            'foreignKey' => ['branch_code']
        ]);
        $this->belongsTo('FacilityInfos', [
            'bindingKey' => ['facility_code','branch_code'],
            'foreignKey' => ['facility_code','branch_code']
        ]);
    }
}