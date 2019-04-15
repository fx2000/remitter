<?php
/**
 * PLATAFORMA DE ADMINISTRACIÓN DE REMESAS
 * Copyright (c) Fundación Duque de La Gomera
 *
 * @copyright Copyright (c) Fundación Duque de La Gomera (www.duquedelagomera.com)
 * @link      https://github.com/appstic/PAR
 * @since     0.1
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Network\Request;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Mailer\MailerAwareTrait;

/**
 * User Controller
 *
 * Handles users
 *
 */
class UserController extends AppController
{

    // Activate Mailer module
    use MailerAwareTrait;

    var $uses = array(
        'User',
        'UserType',
        'Country',
        'AccountInvestors'
    );
    
    /*
     * ¿Qué hace esta función?
     */
    public function initialize()
    {
        $session = $this->request->session();
        if($session->read('user_id') == '') {
            $this->redirect(
                array(
                    'controller' => 'Cpanel',
                    'action'     => 'index'
                )
            );
        }
        $this->loadComponent('Validation');
        $this->loadModel('Users');
        $this->loadModel('CprUserTypes');
        $this->loadModel('CprCountries');
        $this->loadModel('AccountInvestors');
        $this->viewBuilder()->layout('admin_layout');
        $this->set('URL', Configure::read('Server.URL'));
    }

    public function index($user_role)
    {
        if ($user_role!=0) {
            $users = $this->Users->find(
                'all', [
                    'conditions' => array(
                        'delete_status' => 0,
                        'user_type'     => $user_role,
                    ),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
            $role = $this->CprUserTypes->find(
                'all', [
                    'conditions' => array('id'=>$user_role)
                ]
            )->toArray();
            $countries = $this->CprCountries->find(
                'list',[
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
            $i = 0;
            foreach ($users As $user) {
                $users[$i]['country'] = $countries[$user['country']];
                $i++;
            }
            $this->set('role',$role);
        } else {
            $users = $this->Users->find(
                'all', [
                    'conditions' => array(
                        'delete_status' => 0,
                        'user_type NOT IN'  => [4,5]
                    ),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
            $roles = $this->CprUserTypes->find(
                'list',[
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
            $i = 0;
            foreach ($users As $user) {
                $users[$i]['role'] = $roles[$user['user_type']];
                $i++;
            }
            $this->set('role',0);
        }
        $this->set("users",$users);
    }

    /*
     * Add a new user
     */
    function add($user_role)
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $data = $this->request->data;

        if ($user_role==0) { //Staff
            $roles = $this->CprUserTypes->find(
                'list',[
                    'conditions' => array('id NOT IN'  => [4,5]),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
            $this->set('roles',$roles);
            $this->set('role',0);
        } else {
            $role = $this->CprUserTypes->find(
                'all', [
                    'conditions' => array('id'=>$user_role)
                ]
            )->toArray();
            $this->set('role',$role);
        }
        $countries = $this->CprCountries->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('countries',$countries);

        if (!empty($data)) {

            // Generate and hash a random password // TODO: This is BAD, upgrade security.
            $password = $this->generatePassword();
            $data['User']['password'] = sha1($password);

            // Generate a PIN
            $pin = $this->generatePin();
            $data['User']['pin'] = $pin;

            $data['User']['delete_status'] = 0;
            $data['User']['register_dt'] = date("Y-m-d H:i:s");

            if ($user_role == 4 || $user_role == 5) {
                $data['User']['user_type'] = $user_role;
            }

            $exuser = $this->Users->find(
                'all', [
                    'conditions' => array('email' => $data['User']['email'])
                ]
            )->toArray();

            if (count($exuser) >= 1) {
                $session->write('success', "0");
                $session->write('alert', __('El usuario ya existe'));
                $this->render();

            } else {

                $user = $this->Users->newEntity();
                $this->Users->patchEntity($user, $data['User']);
                $idUser = $this->Users->save($user);
                if ($idUser) {
                    
                    // Send welcome email
                    $this->getMailer('User')->send('welcome', [$user, $password, $pin]);

                    if ($user_role == 4) { //Investor
                        $data['AccountInvestor']['user_id'] = $idUser->id;
                        $data['AccountInvestor']['balance'] = 0;
                        $data['AccountInvestor']['modify_dt'] = date("Y-m-d H:i:s");
                        $account = $this->AccountInvestors->newEntity();
                        $this->AccountInvestors->patchEntity($account, $data['AccountInvestor']);
                        $this->AccountInvestors->save($account);

                        $session->write('success', "1");
                        $session->write('alert', __('Inversionista agregado correctamente'.$idUser->id));
                        $this->redirect(
                            array(
                                'controller' => 'user',
                                'action'     => 'index',$user_role
                                )
                        );

                    } elseif ($user_role==5) { //Clients
                        // Display success message and redirect to list
                        $session->write('success', "1");
                        $session->write('alert', __('Cliente agregado correctamente'));
                        $this->redirect(
                            array(
                                'controller' => 'recipient',
                                'action'     => 'add', base64_encode($idUser->id)
                                )
                        );

                    } else { //STAFF
                        $session->write('success', "1");
                        $session->write('alert', __('Usuario agregado correctamente'));
                        $this->redirect(
                            array(
                                'controller' => 'user',
                                'action'     => 'index',0
                                )
                        );
                    }
                }
            }
        }
    }

    /*
     * Editar Usuario
     */
    function edit($id, $user_role)
    { 
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $user_detail = $this->Users->find(
            'all', [
                'conditions' => array('id'=>base64_decode($id))
            ]
        )->toArray();
        $this->set('user_detail',$user_detail);
        $role = $this->CprUserTypes->find(
            'all', [
                'conditions' => array('id'=>$user_role)
            ]
        )->toArray();
        $this->set('role',$role);
        $countries = $this->CprCountries->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('countries',$countries);
        $roles = $this->CprUserTypes->find(
            'list',[
                'conditions' => array(
                    'id NOT IN'  => [4,5]
                ),
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('roles',$roles);

        if ($user_role == '4') {
            $balance = $this->AccountInvestors->find(
                'all',[
                    'conditions' => array(
                        'user_id'  => base64_decode($id)
                    ),
                    'valueField' => 'balance'
                ]
            )->toArray();
            $this->set('balance',$balance[0]);
        }   
        
        if (is_numeric(base64_decode($id))) {
            if (!empty($this->request->data)) {
                $data = $this->request->data;
                $data['User']['id'] = base64_decode($id);
                $user = $this->Users->newEntity();
                $this->Users->patchEntity($user, $data['User']);
                if ($this->Users->save($user)) {
                    $session->write('success', "1");
                    $session->write('alert', __('Usuario actualizado correctamente'));
                    
                    if ($user_role == '4') {
                        $this->redirect(
                            array(
                                'controller' => 'User',
                                'action'     => 'index',4
                            )
                        );
                    } elseif ($user_role == '5') {
                        $this->redirect(
                            array(
                                'controller' => 'User',
                                'action'     => 'index',5
                            )
                        );
                    } else {
                        $this->redirect(
                            array(
                                'controller' => 'User',
                                'action'     => 'index',0
                            )
                        );
                    }
                }
            } else {
                $this->request->data['User'] = $user_detail[0];
            }
        }
    }

    /*
     * Change a user's password
     */
    public function changePassword($id, $assigned_id, $type)
    {
        $session = $this->request->session();
        if ($this->request->data) {
            $id = base64_decode($id);
            if ($this->request->data['User']['new_password'] == $this->request->data['User']['confirm_password']) {
                $user = $this->Users->newEntity();
                $user->id = $id;
                $user->password = sha1($this->request->data['User']['new_password']);
                $this->Users->save($user);
                $session->write('success', "1");
                $session->write('alert', __('Contraseña cambiada correctamente'));
                $this->redirect(
                    array(
                        'controller' => 'user',
                        'action'     => 'view',
                        $assigned_id,
                        $type
                    )
                );
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Las contraseñas no coinciden'));
            }
        }
    }

    /*
     * Borar Usuario
     */
    public function delete($id)
    {
        $session = $this->request->session();
        if (is_numeric(base64_decode($id))) {
            $user_detail = $this->Users->find(
                'all', [
                    'conditions' => array('id'=>base64_decode($id))
                ]
            )->toArray();
            $user = $this->Users->newEntity();
            $user->id = base64_decode($id);
            $user->delete_status = 1;
            if ($this->Users->save($user)) {          
                $session->write('success', "1");
                $session->write('alert', __('Usuario eliminado correctamente'));
                if ($user_detail['0']->user_type == 5) {
                    $this->redirect(
                        array(
                            'controller' => 'user',
                            'action'     => 'index',5
                        )
                    );
                } elseif ($user_detail['0']->user_type == 4) {
                    $this->redirect(
                        array(
                            'controller' => 'user',
                            'action'     => 'index',4
                        )
                    );
                } else {
                    $this->redirect(
                        array(
                            'controller' => 'user',
                            'action'     => 'index',0
                        )
                    );
                }
            }
        }
    }

    /*
     * Generate a new password
     */
    public function generatePassword()
    {

        // Set the random id length
        $random_id_length = 8;

        // Generate a random id, encrypt it, and store it in $rnd_id
        $rnd_id = crypt(uniqid(rand(), 1));

        // Remove any slashes that might have come
        $rnd_id = strip_tags(stripslashes($rnd_id));

        // Remove any . or / and reverse the string
        $rnd_id = str_replace(".", "", $rnd_id);
        $rnd_id = strrev(str_replace("/", "", $rnd_id));

        // Take the first 10 characters from the $rnd_id
        $rnd_id = substr($rnd_id, 0, $random_id_length);

        // Shuffle characters
        $rnd_id = str_shuffle($rnd_id);

        // Remove caps
        $rnd_id = strtolower($rnd_id);

        // Return generated password
        return $rnd_id;
    }

    /*
     * Generate a random PIN
     */
    public function generatePin()
    {
        $pin = random_int(1000,9999);
        return $pin;
    }
}