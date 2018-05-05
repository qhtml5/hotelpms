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
         $this->viewBuilder()->setLayout(false);
        //print_r((new DefaultPasswordHasher)->hash(12345)); die();
        if ($this->request->is('post')) {
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
            } else {
                $this->Flash->set('Wrong username or password.', [
                    'element' => '/Flash/error'
                ]);
            }
            
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
