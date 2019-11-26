<?php
/**
 * remitter
 *
 * @link      https://github.com/fx2000/remitter
 * @since     0.1
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Network\Request;

/**
 * Cpanel Controller
 *
 * Manage sign in and user functions like change password or profile information
 *
 */
class CpanelController extends AppController
{
    var $uses = array(
        'Users',
        'Store',
        'Retailer',
        'UserType',
        'AccountInvestor'
    );

    /*
     * Sign in screen
     */
    function index()
    {
	$this->viewBuilder()->layout('');
        $this->loadModel('Users');
	if ($this->request->is('post')) {
            $user = $this->Users->find()->where([
                'email'         => $this->request->data['username'],
                //'password'      => md5($this->request->data['password']),
                'password'      => sha1($this->request->data['password']),
                'status'        => 1,
                'delete_status' => 0
            ])->toArray();
            if (!empty($user[0])) {
                $session = $this->request->session();
                $session->write('user_id', $user[0]->id);
                $session->write('user_type', $user[0]->user_type);
                $session->write('fname1', $user[0]->fname1);
                $session->write('lname1', $user[0]->lname1);
                $session->write('user_name', $user[0]->username);
                $session->write('assigned_to', $user[0]->assigned_to);
                $session->write('login_status', $user[0]->login_status);
                //$session->write('Config.language', 'spa');
                // if ($user[0]->user_type == 7) {
                //     $store_detail = $this->Store->findById($res['User']['assigned_to']);
                //     $this->Session->write(
                //         'permission',
                //         $store_detail['Store']['permission']
                //     );
                // }
                // if ($user[0]->login_status == 1) {
                return $this->redirect([
                    'controller' => 'cpanel',
                    'action'     => 'home'
                ]);
                // } else {
                //     return $this->redirect([
                //         'controller'=>'cpanel',
                //         'action' => 'change_password'
                //     ]);
                // }
            } else {
                $this->Flash->error(__('Nombre de usuario y contraseña inválido'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /*
     * Go to the dashboard
     */
    function home()
    {
	$session = $this->request->session();
        if ($session->read('user_id') == '') {
            $this->redirect(
                array(
                    'controller' => 'Cpanel',
                    'action'     => 'index'
                )
            );
        }
        $user_id = $session->read('user_id');

        $this->loadModel('AccountInvestors');

        $accounBalance = $this->AccountInvestors->find(
            'all', [
                'conditions' => array(
                    'user_id' => $user_id
                )
            ]
        )->toArray();
        $this->set("accounBalance",$accounBalance);

        $this->loadModel('Remittances');

        $date = date('Y-m-d');

        $query = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status !=' => 5
                )
            ]
        );

        $queryCash = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status !=' => 5,
                    'payment_type' => 1
                )
            ]
        );

        $queryAch = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status !=' => 5,
                    'payment_type' => 2
                )
            ]
        );

        $queryPuntopago = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status !=' => 5,
                    'payment_type' => 3
                )
            ]
        );

        $totalRem = $query->count();
        $this->set("totalRem", $totalRem);

        $montoRem = $query->select(['sum' => $query->func()->sum('amount')])->toArray();
        $this->set("montoRem", $montoRem);

        $montoCash = $queryCash->select(['sum' => $queryCash->func()->sum('amount_payed')])->toArray();
        $this->set("montoCash", $montoCash);

        $montoAch = $queryAch->select(['sum' => $queryAch->func()->sum('amount_payed')])->toArray();
        $this->set("montoAch", $montoAch);

        $montoPuntopago = $queryPuntopago->select(['sum' => $queryPuntopago->func()->sum('amount_payed')])->toArray();
        $this->set("montoPuntopago", $montoPuntopago);

        $montoTot = $query->select(['sum' => $query->func()->sum('amount_payed')])->toArray();
        $this->set("montoTot", $montoTot);

        $query = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status' => 1
                )
            ]
        );
        $dispRem = $query->count();
        $this->set("dispRem", $dispRem);

        $query = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status' => 2
                )
            ]
        );
        $reseRem = $query->count();
        $this->set("reseRem", $reseRem);

        $query = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status' => 3
                )
            ]
        );
        $veriRem = $query->count();
        $this->set("veriRem", $veriRem);

        $query = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status' => 4
                )
            ]
        );
        $comRem = $query->count();
        $this->set("comRem", $comRem);

        // Investor transactions

        $query = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status' => 2,
                    'investor_id' => $user_id
                )
            ]
        );
        $reseRemInv = $query->count();
        $this->set("reseRemInv", $reseRemInv);

        $query = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status' => 3,
                    'investor_id' => $user_id
                )
            ]
        );
        $veriRemInv = $query->count();
        $this->set("veriRemInv", $veriRemInv);

        $query = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'trans_dt >=' => $date,
                    'delete_status' => 0,
                    'status' => 4,
                    'investor_id' => $user_id
                )
            ]
        );
        $comRemInv = $query->count();
        $this->set("comRemInv", $comRemInv);

        $this->viewBuilder()->layout('admin_layout');
    }

    /*
     * View user profile
     */
    function profile()
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
        $this->viewBuilder()->layout('admin_layout');
        $session = $this->request->session();
        $id = $session->read('user_id');
        $this->loadModel('Users');
        if ($this->request->is('post')) {
            $user = $this->Users->newEntity();
            $user->id = $id;
            $user->username = $this->request->data['User']['username'];
            $user->email =  $this->request->data['User']['email'];
            $this->Users->save($user);
            $session->write('admin_name', $this->request->data['User']['username']);
            $session->write('success', "1");
            $session->write('alert', __('Perfil actualizado correctamente'));
            $this->redirect(
                array(
                    'controller' => 'cpanel',
                    'action'     => 'profile'
                )
            );
        } else {
            $data = $this->Users->find()->where(['id' => $id])->toArray();
            $this->request->data['User'] = $data[0];
        }
    }

    /*
     * Change password
     */
    function changePwd()
    {
        $session = $this->request->session();
        if($session->read('user_id') == '')
            $this->redirect(
                array(
                    'controller' => 'Cpanel',
                    'action'     => 'index'
                )
            );
            $this->viewBuilder()->layout('admin_layout');
            $this->loadModel('Users');
            if ($this->request->is('post')) {
                $session = $this->request->session();
                $id = $session->read('user_id');
                $pwd_exists = $this->Users->find()->where([
                    'id'       => $id,
                    'password' => sha1($this->request->data['User']['old_pwd']
                )])->toArray();
                if (empty($pwd_exists)) {
                    $session->write('success', "0");
                    $session->write('alert', __('Error al validar la contraseña'));
                } else {
                    if ($this->request->data['User']['new_pwd'] == $this->request->data['User']['confirm_pwd']) {
                        $photo = $this->Users->newEntity();
                        $photo->id = $id;
                        $photo->password = sha1($this->request->data['User']['new_pwd']);
                        $this->Users->save($photo);     
                        $session->write('success', "1");
                        $session->write('alert', __('Contraseña cambiada correctamente'));
                        $this->redirect(
                            array(
                                'controller' => 'cpanel',
                                'action'     => 'change_pwd'
                            )
                        );
                    } else {
                        $session->write('success', "0");
                        $session->write('alert', __('Las contraseñas no coinciden'));
                    }
                }
            }
    }

    /*
     * Sign out
     */
    function logout()
    {
        $this->autoRender = false;
        $session = $this->request->session();
        $session->destroy();
        $this->Flash->error(__('Cierre de sesión exitoso'));
        return $this->redirect([
            'controller' => 'cpanel',
            'action'     => 'index'
        ]);
    }

    /*
     * Check if session is active
     */
    function admin_checkSession()
    {
        $user_id=$this->Session->read('user_id');
        if ($user_id == null) {
            $this->Session->write(
                'alert',
                __("<span style='color:red;'>Tu sesión ha expirado, inicia de nuevo para continuar</span>")
            );
            $this->redirect(
                array(
                    'controller' => 'cpanel',
                    'action'     => 'admin_index',
                    'admin'      => true
                )
            );
        }
    }

    /*
     * ¿Qué hace esta función?
     */     
    function admin_user_basics()
    {   
        $this->requestAction(
            array(
                'controller' => 'cpanel',
                'action'     => 'admin_checkSession'
            )
        );
        $this->layout='admin_layout';
        $user_type = $this->Session->read('user_type');
        $user_types = $this->UserType->find(
            'list', array(
                'fields' => array(
                    'id',
                    'type'
                )
            )
        );
        $basics = array();
        switch ($user_type) {
            case 1:
            case 2:
            case 3:
                $basics['role'] = $user_types[$user_type];
            break;
            case 4 :
            case 5:
                $basics['role'] = $user_types[$user_type];
                $retailer_detail = $this->Retailer->find(
                    'first',
                    array(
                        'conditions' => array(
                            'id'            => $this->Session->read('assigned_to'),
                            'delete_status' => 0
                        )
                    )
                );
                $basics['assigned_to'] = $retailer_detail['Retailer']['name'];
            break;
            case 6 :
            case 7:
                $basics['role'] = $user_types[$user_type];
                $store_detail = $this->Store->find(
                    'first',
                    array(
                        'conditions' => array(
                            'id'            => $this->Session->read('assigned_to'),
                            'delete_status' => 0
                        )
                    )
                );
                $basics['assigned_to'] = $store_detail['Store']['name'];
            break;
        }
        return $basics;
    }

    /*
     * ¿Qué hace esta función?
     */
    public function admin_change_password()
    {
        $this->layout = 'admin_layout';
        $this->requestAction(
            array(
                'controller' => 'cpanel',
                'action'     => 'admin_checkSession'
            )
        );
        if (!empty($this->request->data)) {
            $data = $this->request->data['User'];
            $user_detail['User']['password'] = sha1($data['new_password']);
            $user_detail['User']['id'] = $this->Session->read('user_id');
            $user_detail['User']['login_status'] = 1;
            if ($this->User->save($user_detail)) {
                $this->Session->write('login_status', 1);
                $this->redirect(
                    array(
                        'controller' => 'cpanel',
                        'action'     => 'home'
                    )
                );
            }
        }
    }
}
