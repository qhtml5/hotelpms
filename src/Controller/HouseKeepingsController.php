<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Model\Value\ConfigsValue;


/**
 * Class TopController
 * @package App\Controller
 * @author ocean <trangchauuit@gmail.com>
 *
 */
class HouseKeepingsController extends InternalAppController
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

        $this->loadModel('EquipmentTypes');
        $equipment_types = $this->EquipmentTypes->find()->select(['id','name'])->where(['EquipmentTypes.operator_code' => $this->getOperatorCode(),'EquipmentTypes.branch_code' => $this->getBranchCode(),'EquipmentTypes.facility_code' => $this->getFacilityCode()])->all();
        $this->set(compact('equipment_types'));
        $this->loadModel('EquipmentInfos');
        $query = $this->EquipmentInfos->find()->contain(['EquipmentStates'])->where(['EquipmentInfos.sale_enable' => 1,'EquipmentStates.sale_enable' => 1, 'EquipmentInfos.operator_code' => $this->getOperatorCode(),'EquipmentInfos.branch_code' => $this->getBranchCode(),'EquipmentInfos.facility_code' => $this->getFacilityCode()]);
        if (!empty(($this->request->query('type')))) {
            $type = $this->request->query('type');
            $query = $query->where([
                'EquipmentInfos.equipment_type_id' => $type,
            ]);
            $this->set('type', $type);
        }
        if (!empty(($this->request->query('room')))) {
            $room = $this->request->query('room');
            $query = $query->where(['EquipmentInfos.equipment_code like' => '%'.$room. '%']);
            $this->set('room', $room);
        }
        $equipment_infos = $query->all();
        //echo json_encode($equipment_infos); die();
        $this->set(compact('equipment_infos'));
    }

    public function updateStatus()
    {
        $this->set(compact('equipment_types'));
        if ( !empty($this->request->data()) ) {
            $id = $this->request->data['id'];
            $this->loadModel('EquipmentStates');
            $equipment_state = $this->EquipmentStates->find()->where(['equipment_info_id' => $id])->first();
            $equipment_state['clean_state'] = $this->request->data['status'];
            if ($this->EquipmentStates->save($equipment_state)){
                 die();
            }
        }
    }
}
