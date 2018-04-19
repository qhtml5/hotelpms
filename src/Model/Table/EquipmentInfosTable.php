<?php
/**
 * Hotel PMS Project
 */
namespace App\Model\Table;

use App\Model\Value\ConfigsValue;
use Cake\Database\Query;
use Cake\I18n\Date;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Class EquipmentInfosTable
 * @package App\Model\Table
 * @author chau.vo <chau.vo>
 */
class EquipmentInfosTable extends BaseTable
{
    /**
     * 初期設定
     * @param array $config
     */
    public function initialize(array $config)
    {
        // apply the parent's configuration
        parent::initialize($config);
        $this->hasOne('EquipmentStates', [
            'foreignKey' => 'equipment_info_id',
            'className' => 'App\Model\Table\EquipmentStatesTable'
        ]);
    }
}