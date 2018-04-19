<?php
/**
 * Hotel PMS Project
 */
namespace App\Model\Table;

use Cake\Validation\Validator;

/**
 * Class EquipmentStatesTable
 * @package App\Model\Table
 * @author chau.vo 
 */
class EquipmentStatesTable extends BaseTable
{
    public function initialize(array $config)
    {
        // initialize for parent
        parent::initialize($config);

        /*
         * configure mapping for Equipments States
         * 1. belongs to equipment states
         *
         * @author ty.huynh
         */
        $this->addAssociations([
            'belongsTo' => [
                'EquipmentInfos' => ['className' => 'App\Model\Table\EquipmentInfosTable']
            ],
        ]);
    }
}