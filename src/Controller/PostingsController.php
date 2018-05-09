<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Model\Value\ConfigsValue;
use Cake\I18n\Date;
use Cake\I18n\Time;


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
        // Get List Minibar
        $descriptions = $this->getListDescriptonMerchandise();
        $this->set(compact('descriptions'));  

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

    public function postRoom()
    {
        if ($this->request->is('ajax')) {
            $reservation_detail_id = $_POST['reservation_detail_id'];
            $salesInfo = TableRegistry::get('SalesInfos')->commandGetOrCreate(['reservation_detail_id' =>$reservation_detail_id]);
            $sale_info_id = $salesInfo['id'];
            $post_id = $_POST['post_id'];
            $number = $_POST['number'];
            if ( !empty($post_id) && !empty($number) ) {
                $salesDetailsTable = TableRegistry::get('SalesDetails');
                foreach ($post_id as $key => $post) {
                    $sale_detail_data['operator_code'] =  $this->getOperatorCode();
                    $sale_detail_data['sales_info_id'] = $sale_info_id;
                    $sale_detail_data['sales_datetime'] = new Time();
                    $sale_detail_data['sales_date'] = $business_date = $this->getBusinessDate();
                    $sale_detail_data['quantity'] = $number[$key];
                    $sale_detail_data['description_id'] = $post;
                    $sale_detail = $salesDetailsTable->newEntity();
                    $sale_detail = $salesDetailsTable->patchEntity($sale_detail, $sale_detail_data);
                    $sale_detail = $salesDetailsTable->save($sale_detail);                       
                }
            }
        }
        die();
    }

    public function getListDescriptonMerchandise()
    {
        // Data extraction from DB
        $descriptionsTable = TableRegistry::get('Descriptions');
        $description_datas = $descriptionsTable->find('all')
            ->select(['id','name','price'])
            ->contain(['BranchInfos','FacilityInfos', 'DescriptionCategories'])
            ->order(['Descriptions.id' => 'asc'])
            ->where([
                'OR' =>
                    [['Descriptions.branch_code' => $this->getBranchCode()], ['Descriptions.branch_code IS NULL']],
                'OR' =>
                    [['Descriptions.facility_code' => $this->getFacilityCode()], ['Descriptions.facility_code IS NULL']],
                'Descriptions.operator_code ' => $this->getOperatorCode(),
                'Descriptions.description_kind in ' => [ConfigsValue::DRK_MERCHANDISE] ])
            ->all();
        return $description_datas;
    }
}