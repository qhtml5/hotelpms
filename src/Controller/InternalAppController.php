<?php
namespace App\Controller;

use App\Context\ContextTrait;
use App\Controller\Component\QueryCRUDTrait;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Utility\Hash;
use Suzukishouten\Standard\Controller\InternalAppControllerTrait;
use Suzukishouten\Standard\Controller\RequestParamParserTrait;
use Suzukishouten\Standard\Model\Entity\LoginUser;
use Suzukishouten\Standard\Utility\ThrowErrorTrait;
use Cake\ORM\TableRegistry;
use App\Model\Value\ConfigsValue;


/**
 * @package App\Controller
 * @author ocean <chau.vo>
 */
class InternalAppController extends AppController
{
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

}
