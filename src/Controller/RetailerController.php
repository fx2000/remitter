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

/**
 * Retailers Controller
 *
 * Handles retailers
 *
 */
class RetailerController extends AppController
{
    var $uses = array(
        'Retailer',
        'City',
        'Country',
        'Province',
        'User',
        'Store',
        'Account',
        'AccountOperator',
        'Operator'
    );

    /*
     * ¿Qué hace esta función?
     */
    public function initialize()
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
        $this->loadModel('CprRetailers');
        $this->loadModel('CprCities');
        $this->loadModel('CprCountries');
        $this->loadModel('CprProvinces');
        $this->loadModel('CprUsers');
        $this->loadModel('CprStores');
        $this->loadModel('CprAccounts');
        $this->loadModel('CprAccountOperators');
        $this->loadModel('CprOperators');
        $this->viewBuilder()->layout('admin_layout');
    }

    /*
     * Add a new retailer
     */
    public function add()
    {
        $cities = $this->CprCities->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'city'
            ]
        )->toArray();
        $countries = $this->CprCountries->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'country'
            ]
        )->toArray();
        $provinces = $this->CprProvinces->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'provinces'
            ]
        )->toArray();
        $this->set('cities', $cities);
        $this->set('countries', $countries);
        $this->set('provinces', $provinces);
        $session = $this->request->session();
        if (!empty($this->request->data)) {
            $data = $this->request->data['Retailer'];
            $isexistemail = $this->CprRetailers->find(
                'all', [
                    'conditions' => array(
                        'email'         => $data['email'],
                        'delete_status' => 0
                    )
                ]
            )->toArray();
            if (empty($isexistemail)) {
                $this->request->data['Retailer']['datetime'] = Date('Y-m-d H:i:s');
                $this->request->data['Retailer']['status'] = '1';
                $this->request->data['CprRetailers'] = $this->request->data['Retailer'];
                unset($this->request->data['Retailer']);
                $user = $this->CprRetailers->newEntity();
                $this->CprRetailers->patchEntity($user, $this->request->data);
                if ($this->CprRetailers->save($user)) {
                    $session->write('success',"1");
                    $session->write('alert', __('Retailer saved successfully'));
                } else {
                    $session->write('success',"0");
                    $session->write('alert', __('Error occured while saving retailer'));
                }
            } else {
                $session->write('success',"0");
                $session->write('alert',__('Email already exists'));
            }
        }
    }

    /*
     * View a retailer
     */
    public function view()
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $cities = $this->CprCities->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'city'
            ]
        )->toArray();
        $countries = $this->CprCountries->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'country'
            ]
        )->toArray();
        $provinces = $this->CprProvinces->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'provinces'
            ]
        )->toArray();
        if ($user_type == 4) {
            $retailers = $this->CprRetailers->find(
                'all',[
                    'order' => 'id desc',
                    'conditions' => array(
                        'id'            => $session->read('assigned_to'),
                        'delete_status' => 0
                    )
                ]
            )->toArray();
        } else {
            $retailers = $this->CprRetailers->find(
                'all',[
                    'order' => 'id desc',
                    'conditions' => array('delete_status' => 0)
                ]
            )->toArray();
        }
        $i = 0;
        $operation_model = array(
            '0' => 'N/A',
            '1' => 'Shared',
            '2' => 'Individual'
        );
        foreach ($retailers As $retailer) {
            $retailers[$i]['city_id'] = $cities[$retailer['city_id']];
            $retailers[$i]['country_id'] = $countries[$retailer['country_id']];
            $retailers[$i]['province_id'] = $provinces[$retailer['province_id']];
            $retailers[$i]['operation_model'] = $operation_model[$retailer['operation_model']];
            $i++;
        }
        $this->set('retailers', $retailers);
    }

    /*
     * Edit a retailer
     */
    public function edit($id)
    {
        $session = $this->request->session();
        $cities = $this->CprCities->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'city'
            ]
        )->toArray();
        $countries = $this->CprCountries->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'country'
            ]
        )->toArray();
        $provinces = $this->CprProvinces->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'provinces'
            ]
        )->toArray();
        $this->set('cities', $cities);
        $this->set('countries', $countries);
        $this->set('provinces', $provinces);
        if (is_numeric(base64_decode($id))) {
            $id = base64_decode($id);
            if (!empty($this->request->data)) {
                $data = $this->request->data['Retailer'];
                $this->request->data['Retailer']['datetime'] = Date('Y-m-d H:i:s');
                $this->request->data['Retailer']['id'] = $id;
                $this->request->data['CprRetailers'] = $this->request->data['Retailer'];
                unset($this->request->data['Retailer']);
                $user = $this->CprRetailers->newEntity();
                $this->CprRetailers->patchEntity($user, $this->request->data);
                if ($this->CprRetailers->save($user)) {
                    $storeUpd = $this->CprStores->query()->update()
                        ->set(['operation_model' => $data['operation_model']])
                        ->where(['retailer_id' => $id])
                        ->execute();
                    if ($storeUpd) {
                        $AccountUpd = $this->CprAccounts->query()->update()
                            ->set(['operation_model' => $data['operation_model']])
                            ->where(['retailer_id' => $id])
                            ->execute();
                        if ($AccountUpd) {
                            //IF OPERATION MODEL == 2 THEN CREATE ACCOUNTS FOR ALL STORES
                            if ($data['operation_model'] == 2) {
                                $retailer_accounts = $this->CprAccounts->find(
                                    'all', [
                                        'conditions' => array(
                                            'retailer_id' => $id,
                                            'store_id'    => '0'
                                        )
                                    ]
                                )->toArray();
                                if (!empty($retailer_accounts)) {
                                    $stores = $this->CprStores->find(
                                        'all', [
                                            'conditions' => array(
                                                'retailer_id' => $data['id'],
                                                'status'      => '1'
                                            )
                                        ]
                                    )->toArray();
                                    if (!empty($stores)) {
                                        foreach ($retailer_accounts As $retailer_account) {
                                            foreach ($stores As $store) {
                                                $storedata['Account']['id']='';
                                                $storedata['Account']['store_id'] = $store['Store']['id'];
                                                $storedata['Account']['retailer_id'] = $data['id'];
                                                $storedata['Account']['operation_model'] = $data['operation_model'];
                                                $storedata['Account']['amount'] = 0;
                                                $storedata['Account']['account_type'] = $retailer_account['Account']['account_type'];
                                                $storedata['Account']['account_id'] = $retailer_account['Account']['account_id'];
                                                $storedata['Account']['credit_limit'] = $retailer_account['Account']['credit_limit'];
                                                $storedata['CprAccounts'] = $storedata['Account'];
                                                unset($storedata['Account']);
                                                $user = $this->CprAccounts->newEntity();
                                                $this->CprAccounts->patchEntity($user, $storedata);
                                                $this->CprAccounts->save($user);
                                                $retailer_account_operators = $this->CprAccountOperators->find(
                                                    'all', [
                                                        'conditions' => array(
                                                            'account_id' => $retailer_account['Account']['id']
                                                        )
                                                    ]
                                                );
                                                foreach ($retailer_account_operators As $retailer_account_operator) {
                                                    $operatordata['AccountOperator']['id']='';
                                                    $operatordata['AccountOperator']['operator_id'] = $retailer_account_operator['AccountOperator']['operator_id'];
                                                    $operatordata['AccountOperator']['account_no'] = $retailer_account['Account']['account_id'];
                                                    $operatordata['AccountOperator']['account_id'] = $this->CprAccountOperators->id;
                                                    $operatordata['CprAccountOperators'] = $storedata['AccountOperator'];
                                                    unset($storedata['AccountOperator']);
                                                    $user = $this->CprAccountOperators->newEntity();
                                                    $this->CprAccountOperators->patchEntity($user, $storedata);
                                                    $this->CprAccountOperators->save($user);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                //IF OPERATION MODEL == 1 THEN DELETE ALL ACCOUNTS OF ITS ALL STORES
                                $delete1['delete_status'] = 1;
                                $this->CprAccounts->query()->update()
                                    ->set(['delete_status' => 1])
                                    ->where([
                                        'retailer_id' => $id,
                                        'store_id !=' => '0'
                                    ])
                                    ->execute();
                            }
                            $session->write('success', "1");
                            $session->write('alert', __('Retailer updated successfully'));
                            $this->redirect(
                                array(
                                    'controller' => 'Retailer',
                                    'action'     => 'view'
                                )
                            );
                        }
                    } else {
                        $session->write('success', "0");
                        $session->write('alert', __('Error updated'));
                        $this->redirect($this->referer());
                    }
                    
                } else {
                    $session->write('success', "0");
                    $session->write('alert', __('Error occured while updating retailer'));
                    $this->redirect($this->referer());
                }
            } else {
                $this->request->data = $this->CprRetailers->findById($id)->toArray();
                $this->request->data['Retailer'] = $this->request->data[0];
            }
        }
    }

    /*
     * Delete a retailer
     */
    public function delete($id)
    {
        if (is_numeric(base64_decode($id))) {
            $user = $this->CprRetailers->newEntity();
            $user->id = base64_decode($id);
            $user->delete_status = 1;
            if ($this->CprRetailers->save($user)) {
                $this->CprAccounts->query()->update()
                    ->set(['delete_status' => 1])
                    ->where(['retailer_id' => base64_decode($id)])
                    ->execute();
                $conn = ConnectionManager::get('default');
                $stmt = $conn->execute(
                    "Update cpr_users set delete_status = 1 where assigned_to In (Select id from cpr_stores where retailer_id = '" .
                    base64_decode($id) . "') And user_type In (7,6)"
                );
                $this->CprStores->query()->update()
                    ->set(['delete_status' => 1])
                    ->where(['retailer_id' => base64_decode($id)])
                    ->execute();
                $this->CprUsers->query()->update()
                    ->set(['delete_status' => 1])
                    ->where(['assigned_to' => base64_decode($id), 'user_type IN' => [4, 5]])
                    ->execute();
                $this->request->session()->write('success', "1");
                $this->request->session()->write('alert', __('Retailer deleted successfully'));
                $this->redirect(
                    array(
                        'controller' => 'Retailer',
                        'action'     => 'view'
                    )
                );
            }
        }
    }

    /*
     * Manage reseller accounts
     */
    public function manageAccounts($retailer_id)
    {
        $session = $this->request->session();
        if (is_numeric(base64_decode($retailer_id))) {
            $retailer_id = base64_decode($retailer_id);
            if (!empty($this->request->data)) {
                $data = $this->request->data['Account'];
                if ($data['account_type'] == 1 && $data['credit_limit'] == '') {
                        $session->write('success', "0");
                        $session->write('alert', __('A credit limit is required for postpaid accounts'));
                    } else {
                        $data['amount'] = 0;
                        $data['store_id'] = 0;
                        $data['retailer_id'] = $retailer_id;
                        $retailer = $this->CprRetailers->findById($retailer_id)->toArray();
                        $data['operation_model'] = $retailer[0]->operation_model;
                        $user = $this->CprAccounts->newEntity();
                        $this->CprAccounts->patchEntity($user, $data);                              
                        if ($accdataid = $this->CprAccounts->save($user)) {
                            $accountCreatedId = $accdataid->id;
                            $remaining = 4 - strlen($accountCreatedId);
                            $acctId = 'A';
                            for ($i = 0; $i < $remaining; $i++) {
                                $acctId .= '0';
                            }
                            $acctId .= $accountCreatedId;
                            $updateData['account_id'] = "'" . $acctId . "'";
                            $storeUpd = $this->CprAccounts->query()->update()
                                ->set(['account_id' => $acctId])
                                ->where(['id' => $accountCreatedId])
                                ->execute();
                            if ($data['operation_model'] == 2) {
                                $stores = $this->CprStores->find(
                                    'all', [
                                        'conditions' => array(
                                            'retailer_id'   => $retailer_id,
                                            'status'        => '1',
                                            'delete_status' => 0
                                        )
                                    ]
                                );
                                if (!empty($stores)) {
                                    foreach ($stores As $store) {
                                        $storedata['Account']['id'] = '';
                                        $storedata['Account']['store_id'] = $store['Store']['id'];
                                        $storedata['Account']['retailer_id'] = $retailer_id;
                                        $storedata['Account']['operation_model'] = $data['operation_model'];
                                        $storedata['Account']['amount'] = 0;
                                        $storedata['Account']['account_type'] = $data['account_type'];
                                        $storedata['Account']['account_id'] = $acctId;
                                        $storedata['Account']['credit_limit'] = $data['credit_limit'];
                                        $user = $this->CprAccounts->newEntity();
                                        $this->CprAccounts->patchEntity($user, $storedata);
                                        $this->CprAccounts->save($user);
                                    }
                                }
                            }
                            $session->write('success', "1");
                            $session->write('alert', __('Account added successfully'));
                            $this->request->data = array();
                        } else {
                            $session->write('success', "0");
                            $session->write('alert', __('An error occured while adding accounts'));
                        }
                    }
                }
                $accounts = $this->CprAccounts->find(
                    'all', [
                        'conditions' => array(
                            'retailer_id'   => $retailer_id,
                            'store_id'      => 0,
                            'delete_status' => 0
                        )
                    ]
                )->toArray();
            
            $i = 0;
            $account_type = array(
                '1' => __('Postpaid'),
                '2' => __('Prepaid')
            );
            $operation_model = array(
                '0' => __('N/A'),
                '1' => __('Shared'),
                '2' => __('Individual')
            );
            $accountds = array();
            foreach ($accounts As $account) {
                $accountds[$i]['Account']['id'] = $account->id;
                $accountds[$i]['Account']['amount'] = $account->amount;
                $accountds[$i]['Account']['account_type'] = $account_type[$account->account_type]; 
                $accountds[$i]['Account']['operation_model'] = $operation_model[$account->operation_model];
                $accountds[$i]['Account']['credit_limit'] = $account->account_type != 1 ? 'N/A' : '$' . $account->credit_limit;
                if ($account->account_type == 1) {
                    $accountds[$i]['Account']['amount'] = $account->credit_limit - $account->amount;
                }
                $i++;
            }
            $retailers = $this->CprRetailers->find(
                'all', [
                    'conditions' => array('id' => $retailer_id)
                ]
            )->toArray();
            $this->set('accounts', $accountds);
            $this->set('retailer', $retailers[0]);
        }
    }

    /*
     * Edit reseller accounts
     */
    public function editAccount($id)
    {
        $session = $this->request->session();
        if (is_numeric(base64_decode($id))) {
            $id = base64_decode($id);
            $account_detail = $this->CprAccounts->findById($id)->toArray();
            if (!empty($this->request->data)) {
                $data = $this->request->data['Account'];
                $account_id_exst = $this->CprAccounts->find(
                    'all', [
                        'conditions' => array(
                            'id'            => @$data['account_id'],
                            'id  !='        => $account_detail[0]->account_id,
                            'delete_status' => 0
                        )
                    ]
                )->toArray();
                if (count($account_id_exst) == 0) {
                    if ($data['account_type'] == 1 && $data['credit_limit'] == '') {
                        $session->write('success', "0");
                        $session->write('alert', __('A credit limit is required for postpaid accounts'));
                    } else {
                        $user = $this->CprAccounts->newEntity();
                        $this->CprAccounts->patchEntity($user,  $data);
                        if ($this->CprAccounts->save($user)) {
                            $updateData['account_type'] = $data['account_type'];
                            $updateData['credit_limit'] = $data['account_type'] == 1 ? $data['credit_limit'] : null;
                            $storeUpd = $this->CprAccounts->query()->update()
                                ->set([
                                    'account_type' => $data['account_type'],
                                    'credit_limit' =>  $updateData['credit_limit']
                                ])
                                ->where(['account_id' => $account_detail[0]->account_id])
                                ->execute();
                            $session->write('success', "1");
                            $session->write('alert', __('Account updated successfully'));
                            $this->redirect(
                                array(
                                    'controller' => 'Retailer',
                                    'action'     => 'manage_accounts',
                                    base64_encode($account_detail[0]->retailer_id)
                                )
                            );
                        } else {
                            $session->write('success', "0");
                            $session->write('alert', __('An error occured while updating account'));
                        }
                    }
                } else {
                    $session->write('success', "0");
                    $session->write('alert', __('Account ID already exists'));
                }
            } else {
                debug($account_detail);
                $this->request->data['Account'] = $account_detail[0];
            }
        }
        $retailer = $this->CprRetailers->findById($account_detail[0]->retailer_id)->toArray();
        $this->set('retailer', $retailer[0]);
    }

    /*
     * Delete reseller accounts
     */
    public function deleteAccount($id)
    {
        $session = $this->request->session();
        if (is_numeric(base64_decode($id))) {
            $account = $this->CprAccounts->findById(base64_decode($id))->toArray();
            $user = $this->CprAccounts->newEntity();
            $user->delete_status = 1;
            $user->id = base64_decode($id); 
            if ($this->CprAccounts->save($user)) {
                if ($account[0]->store_id == 0) {
                    $delete1['delete_status'] = 1;
                    $storeUpd = $this->CprAccounts->query()->update()
                        ->set(['delete_status' => 1])
                        ->where(['account_id' => $account[0]->account_id])
                        ->execute();
                }
                $session->write('success', "1");
                $session->write('alert', __('Account deleted successfully'));
                $this->redirect(
                    array(
                        'controller' => 'Retailer',
                        'action'     => 'manage_accounts',
                        base64_encode($account[0]->retailer_id)
                    )
                );
            } else {
                $session->write('success', "0");
                $session->write('alert', __('An error occured while deleting account'));
                $this->redirect(
                    array(
                        'controller' => 'Retailer',
                        'action'     => 'manage_accounts',
                        base64_encode($account[0]->retailer_id)
                    )
                );
            }
        }
    }

    /*
     * Assign mobile opreator to retailer account
     */
    public function assignOperator($acct_id)
    {
        if(is_numeric(base64_decode($acct_id))) {
            $acct_id = base64_decode($acct_id);
            if (!empty($this->request->data)) {
                $data = $this->request->data['AccountOperator'];
                $account_detail = $this->CprAccounts->findById($acct_id)->toArray();
                foreach ($data['operator_id'] As $operator) {
                    $stores_detail = $this->CprAccounts->find(
                        'all', [
                            'conditions' => array(
                                'account_id'    => $account_detail['0']->account_id,
                                'delete_status' => 0
                            )
                        ]
                    )->toArray();
                    foreach ($stores_detail As $store_detail) {
                        $stores_acct_detail = $this->CprAccounts->find(
                            'list', [
                                'conditions' => array(
                                    'retailer_id'   => $account_detail['0']->retailer_id,
                                    'store_id'      => $store_detail->store_id,
                                    'delete_status' => 0
                                ),
                                'keyField'   => 'id',
                                'valueField' => 'id'
                            ]
                        )->toArray();
                        foreach ($stores_acct_detail AS $s) {
                            $st[]= $s;
                        }
                        $isexists = $this->CprAccountOperators->find(
                            'all', [
                                'conditions' => array(
                                    'account_id IN' => $st,
                                    'operator_id'   => $operator
                                )
                            ]
                        )->toArray();
                        if (count($isexists) == 0) {
                            $account_operator['AccountOperator']['id'] = '';
                            $account_operator['AccountOperator']['account_id'] = $store_detail->id;
                            $account_operator['AccountOperator']['operator_id'] = $operator;
                            $account_operator['AccountOperator']['account_no'] = $store_detail->account_id;
                            $user = $this->CprAccountOperators->newEntity();
                            $this->CprAccountOperators->patchEntity($user, $account_operator['AccountOperator']);
                            $this->CprAccountOperators->save($user);
                        }
                    }
                }
            } else {
                $this->request->data = $this->CprAccountOperators->find(
                    'all', [
                        'conditions' => array('account_id'=>$acct_id)
                    ]
                )->toArray();
            }
            $conn = ConnectionManager::get('default');
            $operator_ids = $conn->execute(
                "SELECT operator_id FROM cpr_account_operators AS AccountOperator WHERE account_id IN (SELECT id FROM cpr_accounts AS Account WHERE retailer_id = (SELECT retailer_id FROM cpr_accounts AS Account WHERE id=" .
                $acct_id . ") AND store_id=0 AND delete_status = 0) ")->fetchAll('assoc');
            foreach ($operator_ids As $operator_id) {
                $operator_not_come[] = $operator_id['operator_id'];
            }
            $operators =array();
            if (!empty($operator_not_come)) {
                $operators = $this->CprOperators->find(
                    'all', [
                        'conditions' => array('id NOT IN'=>$operator_not_come),
                        'fields' => array(
                            'id',
                            'name',
                            'status'
                        )
                    ]
                )->toArray();
            } else {
                $operators = $this->CprOperators->find(
                    'all', [
                        'fields' => array(
                            'id',
                            'name',
                            'status'
                        )
                    ]
                )->toArray();
            }
            $this->set('operators', $operators);
            $operators_account = $this->CprAccountOperators->find(
                'all', [
                    'conditions' => array('account_id' => $acct_id)
                ]
            )->toArray();
            $this->set('account_operators', $operators_account);
            $all_operators = $this->CprOperators->find(
                'list', [
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
            $this->set('all_operators', $all_operators);
        }
    }

    /*
     * Delete a mobile operator from a retailer account
     */
    public function deleteAccountOperator($id)
    {
        $session = $this->request->session();
        if (is_numeric(base64_decode($id))) {
            $account_operator_detail = $this->CprAccountOperators->findById(base64_decode($id))->toArray();
            $delete = $this->CprAccountOperators->deleteAll([
                'account_no'  => $account_operator_detail[0]->account_no,
                'operator_id' => $account_operator_detail[0]->operator_id
            ]);
            if ($delete) {
                $session->write('success', "1");
                $session->write('alert', __('Operator unassignment successful'));
                $this->redirect(
                    array(
                        'controller' => 'Retailer',
                        'action'     => 'assign_operator',
                        base64_encode($account_operator_detail[0]->account_id)
                    )
                );
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Operator unassignment unsuccessful'));
                $this->redirect(
                    array(
                        'controller' => 'Retailer',
                        'action'     => 'assign_operator',
                        base64_encode($account_operator_detail[0]->account_id)
                    )
                );
            }
        }
    }
}
