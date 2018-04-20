<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;


/**
 * Class TopController
 * @package App\Controller
 * @author ocean <trangchauuit@gmail.com>
 *
 */
class TopController extends AppController
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
        // get user info
        $user = $this->getUser();
        $user_name = $user['first_name']. ' ' . $user['last_name'];
        $this->set('user_name', $user_name);
    }
}
