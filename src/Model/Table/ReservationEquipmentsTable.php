<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Table;

use App\Model\Value\ConfigsValue;
use Cake\I18n\Date;
use Cake\Validation\Validator;

/**
 * Class ReservationEquipmentsTable
 * @package App\Model\Table
 * @author chau.vo <vo.thi.trang.chau@suzukishouten.co.jp>
 */
class ReservationEquipmentsTable extends BaseTable
{
    /**
     * 初期設定
     * @param array $config
     */
    public function initialize(array $config)
    {
        // apply the parent's configuration
        parent::initialize($config);
        $this->belongsTo('EquipmentInfos');
        $this->belongsTo('EquipmentTypes');
        $this->belongsTo('ReservationDetails');
        $this->belongsTo('ReservationInfos');
        $this->hasMany('ReservationCharges', [
            'foreignKey' => 'reservation_equipment_id',
            'className' => 'App\Model\Table\ReservationChargesTable'
        ]);
    }
}