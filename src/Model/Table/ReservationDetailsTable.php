<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Table;

use App\Model\Value\ConfigsValue;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use App\Model\Value\ChargeKindValue;

/**
 * Class ReservationDetailsTable
 * @package App\Model\Table
 * @author chau.vo <vo.thi.trang.chau@suzukishouten.co.jp>
 */
class ReservationDetailsTable extends BaseTable
{
    /**
     * 初期設定
     * @param array $config
     */
    public function initialize(array $config)

    {
        // apply the parent's configuration
        parent::initialize($config);
        $this->hasMany('ReservationEquipments', [
            'foreignKey' => 'reservation_detail_id',
            'className' => 'App\Model\Table\ReservationEquipmentsTable'
        ]);

        $this->hasOne('ClientOpinions', [
            'foreignKey' => 'reservation_detail_id',
            'className' => 'App\Model\Table\ClientOpinionsTable'
        ]);
        $this->belongsTo('ReservationInfos');
        $this->belongsTo('EquipmentTypes');
        $this->belongsTo('ClientInfos');

        /*
         * Reservation details has many sale infos
         * @ty.huynh
         */
        $this->hasMany('SalesInfos', [
            'foreignKey' => 'reservation_detail_id',
            'className' => 'App\Model\Table\SalesInfosTable'
        ]);
        /*
         * Reservation details has many reservation charges
         * @date 2017-Feb-16
         * @ty.huynh
         */
        $this->hasMany('ReservationCharges', [
            'foreignKey' => 'reservation_detail_id',
            'className' => 'App\Model\Table\ReservationChargesTable'
        ]);

        $this->hasMany('ReservationGuests', [
            'foreignKey' => 'reservation_detail_id',
            'className' => 'App\Model\Table\ReservationGuestsTable'
        ]);
    }
}