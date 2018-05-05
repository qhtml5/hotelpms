<?php
namespace App\Controller;

use App\Controller\Component\QueryCRUDTrait;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;
use App\Model\Value\ConfigsValue;


/**
 * @package App\Controller
 * @author ocean <chau.vo>
 */
class InternalAppController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'Top',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'logout'
            ]
        ]);

        // get user info
        $user = $this->getUser();
        $user_name = $user['first_name']. ' ' . $user['last_name'];
        $this->set('user_name', $user_name);
    }

	 /**
     * Get Operator Code
     * @move from AppController by chau.vo
     */
    public function getOperatorCode()
    {
        $user = $this->Auth->user();
        $operator_code = $user['operator_code'];
        return $operator_code;
    }

	 /**
     * Get Branch Code
     * @move from AppController by chau.vo
     */
    public function getBranchCode()
    {
        $user = $this->Auth->user();
        $branch_code = $user['branch_code'];
        return $branch_code;
    }

    /**
     * Get Facility Code 
     * @move from AppController by chau.vo
     */
    public function getFacilityCode()
    {
    	$user = $this->Auth->user();
        $facility_code = $user['facility_code'];
        return $facility_code;        
    }

    /**
     * Get User 
     * @move from AppController by chau.vo
     */
    public function getUser()
    {
        $user = $this->Auth->user();
        return $user;        
    }

    /**
     * Get Business Date 
     * @move from AppController by chau.vo
     */
    public function getBusinessDate()
    {
        if ($this->businessDate == null && $this->getBranchCode()) {
            $businessDateEntity = TableRegistry::get('BusinessDates')
                ->find()
                ->where(['branch_code' => $this->getBranchCode()])
                ->first();
            if ($businessDateEntity) {
                $this->businessDate = $businessDateEntity->business_date;
            }
        }
        if ($this->businessDate == null) {
            $this->businessDate = new Date();
        }
        return $this->businessDate;
    }

    /**
     * Get Equipment Type
     * @move from AppController by chau.vo
     */
    public function getEquipmentTypes()
    {
        $this->loadModel('EquipmentTypes');
        $equipment_types = $this->EquipmentTypes->find()->select(['id','name'])->where(['EquipmentTypes.operator_code' => $this->getOperatorCode(),'EquipmentTypes.branch_code' => $this->getBranchCode(),'EquipmentTypes.facility_code' => $this->getFacilityCode()])->all();
        return $equipment_types;       
    }


}
