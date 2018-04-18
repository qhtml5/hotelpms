<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;


/**
 * Class UsersController
 * @package App\Controller
 * @author ocean <trangchauuit@gmail.com>
 *
 */
class UsersController extends AppController
{

	/**
     *
     * ### Get List 
     * @author chau.vo <trangchauuit@gmail.com>
     * @date 2018-Mar-23
     * @return \Cake\Network\Response
     */

    function login(){
        //print_r((new DefaultPasswordHasher)->hash(12345)); die();
        if ($this->request->is('post')) {
            print_r($this->request->is('post')); die();
            $this->Auth->config('authenticate', [
                'Form' => [
                    'fields' => ['username' => 'employee_code']
                ]
            ]);
            $this->Auth->constructAuthenticate();
            $this->request->data['employee_code'] = $this->request->data['username'];
            $user = $this->Auth->identify();
            if ($user) {    
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
