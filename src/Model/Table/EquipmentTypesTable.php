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
 * Class EquipmentTypesTable
 * @package App\Model\Table
 * @author chau.vo
 */
class EquipmentTypesTable extends BaseTable
{
    /**
     * 初期設定
     * @param array $config
     */
    public function initialize(array $config)
    {
        // apply the parent's configuration
        parent::initialize($config);
    }
}