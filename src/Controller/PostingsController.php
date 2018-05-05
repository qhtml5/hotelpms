<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Model\Value\ConfigsValue;


/**
 * Class PostsController
 * @package App\Controller
 * @author ocean <trangchauuit@gmail.com>
 *
 */
class PostingsController extends InternalAppController
{
    public function initialize()
    {
        parent::initialize();
    }

	/**
     *
     * ### Get List 
     * @author chau.vo <trangchauuit@gmail.com>
     * @date 2018-Mar-23
     * @return \Cake\Network\Response
     */


    public function index()
    {
        $business_date = $this->getBusinessDate();
        $this->loadModel('ReservationEquipments');
        $query = $this->ReservationEquipments->find('all')
            ->select(['id', 'reservation_detail_id'])
            ->contain([
                'EquipmentInfos' => [
                    'fields' => [
                        'id', 'equipment_code'
                    ]
                ],
                'EquipmentTypes' => [
                    'fields' => [
                        'id', 'name'
                    ]
                ],
                'ReservationDetails' => [
                    'fields' => [
                        'id', 'client_info_id','arrival_date', 'departure_date'
                    ]
                ],
                'ReservationDetails.ClientInfos' => [
                    'fields' => [
                        'id', 'first_name','last_name'
                    ]
                ],
                'ReservationDetails.ReservationInfos' => [
                    'fields' => [
                        'id', 'reservation_number'
                    ]
                ]
            ])
            ->where(['ReservationEquipments.inhouse' => ConfigsValue::FLV_TRUE, 'ReservationEquipments.use_start_date <=' => $business_date, 'ReservationEquipments.use_end_date >=' => $business_date ]);
        if (!empty(($this->request->query('room')))) {
            $room = $this->request->query('room');
            $query = $query->where(['EquipmentInfos.id' => $room]);
            $this->set('room', $room);
        }
        $reservation_equipment = $query->first();
        $this->set(compact('reservation_equipment'));    
    }

    public function searchRoom()
    {
        if ($this->request->is('ajax')) {
            $this->loadModel('EquipmentInfos');
            $query = $this->EquipmentInfos->find()->select(['id','equipment_code'])->contain(['EquipmentStates'])->where(['EquipmentInfos.sale_enable' => 1,'EquipmentStates.sale_enable' => 1, 'EquipmentInfos.operator_code' => $this->getOperatorCode(),'EquipmentInfos.branch_code' => $this->getBranchCode(),'EquipmentInfos.facility_code' => $this->getFacilityCode()]);
            if (!empty($_GET['q'])) {
                $query = $query->where([
                    'EquipmentInfos.equipment_code like' => '%'.$_GET['q']. '%',
                ]);
            }
            $json = [];
            $equipment_infos = $query->all();
            if($equipment_infos){
                foreach ($equipment_infos as $equipment_info) {
                    $json[] = ['id'=>$equipment_info['id'], 'text'=>$equipment_info['equipment_code']];
                }
            }                  
            echo json_encode($json); die();
        }
    }
}