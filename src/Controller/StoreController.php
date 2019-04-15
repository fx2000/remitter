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
 * Store Controller
 *
 * Handles retailer stores
 *
 */
class StoreController extends AppController
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
     * ¿Qué hace esta función?
     */
    public function add()
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $cities = $this->CprCities->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'city'
            ]
        )->toArray();
        $countries = $this->CprCountries->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'country'
            ]
        )->toArray();
        $provinces = $this->CprProvinces->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'provinces'
            ]
        )->toArray();
       
        $this->set('cities', $cities);
        $this->set('countries', $countries);
        $this->set('provinces', $provinces);
        if ($user_type == 4) {
            $retailers = $this->CprRetailers->find(
                'list', [
                    'conditions' => array(
                        'status'        => 1,
                        'id'            => $this->Session->read('assigned_to'),
                        'delete_status' => 0
                    ),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
        } else if ($user_type == 6) {
            $store_detail = $this->Store->findById($session->read('assigned_to'))->toArray();
            $retailers = $this->CprRetailers->find(
                'list', [
                    'conditions' => array(
                        'status'        => 1,
                        'id'            => $store_detail['Store']['retailer_id'],
                        'delete_status' => 0
                    ),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
        } else {
           $retailers = $this->CprRetailers->find(
                'list', [
                    'conditions' => array(
                        'status'=>1,
                        'delete_status'=>0
                    ),
                    'keyField' => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
        }
        $this->set('retailers', $retailers);
        if (!empty($this->request->data)) {
            $data = $this->request->data['Store'];
            if ($session->read('user_type') == 6) {
                $data['level'] = 2;
            } else {
                $data['level'] = 1;
            }
            $retailer = $this->CprRetailers->findById($data['retailer_id'])->toArray(); 
            $data['operation_model'] = $retailer['0']->operation_model;
            $data['status'] = 1;
            $user = $this->CprStores->newEntity();
            $this->CprStores->patchEntity($user, $data);
            if ($newStoreId = $this->CprStores->save($user)) {
                $retailer_accounts = $this->CprAccounts->find(
                    'all', [
                        'conditions'=>array(
                            'retailer_id'   => $data['retailer_id'],
                            'store_id'      => '0',
                            'delete_status' => 0
                        )
                    ]
                )->toArray(); 
                if (!empty($retailer_accounts)) {
                    if ($retailer[0]->operation_model == 2) {
                        foreach ($retailer_accounts As $retailer_account) {
                            $accountdata['Account']['id'] ='';
                            $accountdata['Account']['account_id'] = $retailer_account->account_id;
                            $accountdata['Account']['operation_model'] = $data['operation_model'];
                            $accountdata['Account']['retailer_id'] = $data['retailer_id'];
                            $accountdata['Account']['store_id'] = $newStoreId;
                            $accountdata['Account']['amount'] = '0';
                            $accountdata['Account']['account_type'] = $retailer_account->account_type;
                            $accountdata['Account']['credit_limit'] = $retailer_account->credit_limit;
                            $user = $this->CprAccounts->newEntity();
                            $this->CprAccounts->patchEntity($user, $accountdata);
                            $newaccountId = $this->CprAccounts->save($user);
                            $retailer_account_operators = $this->CprAccountOperators->find(
                                'all', [
                                    'conditions' => array(
                                        'account_id' => $retailer_account->id)
                                ]
                            )->toArray();
                            foreach ($retailer_account_operators As $retailer_account_operator) {
                                $operatordata['AccountOperator']['id'] = '';
                                $operatordata['AccountOperator']['operator_id'] = $retailer_account_operator->operator_id;
                                $operatordata['AccountOperator']['account_no'] = $retailer_account->account_id;
                                $operatordata['AccountOperator']['account_id'] = $newaccountId;
                                $user = $this->CprAccountOperators->newEntity();
                                $this->CprAccountOperators->patchEntity($user, $operatordata);
                                $this->CprAccountOperators->save($user);
                            }
                        }
                    }
                }
                $session->write('success', "1");
                $session->write('alert', __('Store saved successfully'));
                $this->redirect($this->referer());
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Error occured while saving store'));
                $this->redirect($this->referer()); 
            }
        }
        
    }

    /*
     * ¿Qué hace esta función?
     */
    public function view($retailerId)
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $cities = $this->CprCities->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'city'
            ]
        )->toArray();
        $countries = $this->CprCountries->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'country'
            ]
        )->toArray();
        $provinces = $this->CprProvinces->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'provinces'
            ]
        )->toArray();
        $retailers = $this->CprRetailers->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $MyRetailer = $this->CprStores->findById($session->read('assigned_to'))->toArray();
        $retailerId = base64_decode($retailerId);
        if ($retailerId == -1) {
            if ($user_type == 4) {
                $stores = $this->CprStores->find(
                    'all', [
                        'conditions' => array(
                            'retailer_id'   => $session->read('assigned_to'),
                            'delete_status' => 0
                        ),
                        'order'      => array('id DESC')
                    ]
                )->toArray();
            }
            else if ($user_type == 6) {
                $stores = $this->CprStores->find(
                    'all', [
                        'conditions' => array(
                            'retailer_id'   => $MyRetailer['Store']['retailer_id'],
                            'delete_status' => 0
                        ),
                        'order'      => array('id DESC')
                    ]
                )->toArray();
            } else {
                $stores = $this->CprStores->find(
                    'all', [
                        'conditions' => array('delete_status' => 0),
                        'order'      => array('id DESC')
                    ]
                )->toArray();
            }
        } else {
            $stores = $this->CprStores->find(
                'all', [
                    'conditions' => array(
                        'retailer_id'   => $retailerId,
                        'delete_status' => 0
                    ),
                    'order'      => array('id DESC')
                ]
            )->toArray();
        }
        $i = 0;
        $operation_model = array(
            '0' => __('N/A'),
            '1' => __('Shared'),
            '2' => __('Individual')
        );
        $permission = array(
            '1' => __('Web'),
            '2' => __('Mobile'),
            '3' => __('Both')
        );
        $storeds = array();
        foreach ($stores As $store) {
            $storeds[$i]['Store']['id'] = $store->id;
            $storeds[$i]['Store']['name'] = $store->name;
            $storeds[$i]['Store']['phone_no'] = $store->phone_no ;
            $storeds[$i]['Store']['status'] = $store->status  ;
            $storeds[$i]['Store']['address'] = $store->address   ;
            $storeds[$i]['Store']['operation_model'] = $store->operation_model    ;
            $storeds[$i]['Store']['city_id'] = $cities[$store->city_id];
            $storeds[$i]['Store']['country_id'] = $countries[$store->country_id];
            $storeds[$i]['Store']['province_id'] = $provinces[$store->province_id];
            $storeds[$i]['Store']['retailer_id'] = $retailers[$store->retailer_id] != '' ? $retailers[$store->retailer_id] : 'N/A';
            $storeds[$i]['Store']['permission'] = @$permission[@$store->permission];
            $i++;
        }
        $this->set('stores' , $storeds);
    }

    /*
     * ¿Qué hace esta función?
     */
    public function edit($id, $retailerId)
    {
        $session = $this->request->session();
         $user_type = $session->read('user_type');
         $cities = $this->CprCities->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'city'
            ]
        )->toArray();
        $countries = $this->CprCountries->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'country'
            ]
        )->toArray();
        $provinces = $this->CprProvinces->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'provinces'
            ]
        )->toArray();
        $this->set('cities', $cities);
        $this->set('countries', $countries);
        $this->set('provinces', $provinces);
        if ($user_type == 4) {
            $retailers = $this->CprRetailers->find(
                'list', [
                    'conditions' => array(
                        'status'        => 1,
                        'id'            => $session->read('assigned_to'),
                        'delete_status' => 0
                    ),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
        } else if ($user_type == 6) {
            $store_detail = $this->CprStores->findById($session->read('assigned_to'))->toArray();
            $retailers = $this->CprRetailers->find(
                'list', [
                    'conditions' => array(
                        'status'        => 1,
                        'id'            => $store_detail['Store']['retailer_id'],
                        'delete_status' => 0
                    ),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
        } else {
           $retailers = $this->CprRetailers->find(
                'list', [
                    'conditions' => array(
                        'status'        => 1,
                        'delete_status' => 0
                    ),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
        }
        $this->set('retailers', $retailers);
        if (is_numeric(base64_decode($id))) {
            $id = base64_decode($id);
            if (!empty($this->request->data)) {
                $data = $this->request->data['Store'];
                if ($session->read('user_type') == 6) {
                    $data['level'] = 2;
                } else {
                    $data['level'] = 1;
                }
                $data['id'] = $id;
                $retailer = $this->CprRetailers->findById($data['retailer_id'])->toArray();
                $store_detail = $this->CprStores->findById($id)->toArray();
                $data['operation_model'] = $retailer[0]->operation_model;
                $user = $this->CprStores->newEntity();
                $this->CprStores->patchEntity($user, $data);
                if ($this->CprStores->save($user)) {
                    $this->CprAccounts->deleteAll([
                        'retailer_id' => $store_detail[0]->retailer_id,
                        'store_id'    => $id
                    ]);
                    $retailer_accounts = $this->CprAccounts->find(
                        'all', [
                            'conditions' => array(
                                'retailer_id'   => $data['retailer_id'],
                                'store_id'      => '0',
                                'delete_status' => 0
                            )
                        ]
                    )->toArray();
                    if (!empty($retailer_accounts)) {
                        if ($retailer[0]->operation_model == 2) {
                            foreach ($retailer_accounts As $retailer_account) {
                                $accountdata['Account']['id'] ='';
                                $accountdata['Account']['account_id'] = $retailer_account->account_id;
                                $accountdata['Account']['operation_model'] = $data['operation_model'];
                                $accountdata['Account']['retailer_id'] = $data['retailer_id'];
                                $accountdata['Account']['store_id'] = $id;
                                $accountdata['Account']['amount'] = '0';
                                $accountdata['Account']['account_type'] = $retailer_account->account_type;
                                $accountdata['Account']['credit_limit'] = $retailer_account->credit_limit;
                                $user = $this->CprAccounts->newEntity();
                                $this->CprAccounts->patchEntity($user, $accountdata);
                                $newaccountId = $this->CprAccounts->save($user);
                                $retailer_account_operators = $this->CprAccountOperators->find(
                                    'all', [
                                        'conditions' => array('account_id' => $retailer_account->id)
                                    ]
                                )->toArray();
                                foreach ($retailer_account_operators As $retailer_account_operator) {
                                    $operatordata['AccountOperator']['id'] = '';
                                    $operatordata['AccountOperator']['operator_id'] = $retailer_account_operator->operator_id;
                                    $operatordata['AccountOperator']['account_no'] = $retailer_account->account_id;
                                    $operatordata['AccountOperator']['account_id'] = $newaccountId;
                                    $user = $this->CprAccountOperators->newEntity();
                                    $this->CprAccountOperators->patchEntity($user, $operatordata);
                                    $this->CprAccountOperators->save($user);
                                }
                            }
                        }
                    }
                    if ($data['status'] == 0) {
                        $updateData['status'] = 0;
                        $this->CprUsers->query()->update()
                            ->set(['status' => 0])
                            ->where(['assigned_to' => $id])
                            ->execute();
                    } else {
                        $updateData['status'] = 1;
                        $this->CprUsers->query()->update()
                            ->set(['status' => 1])
                            ->where(['assigned_to' => $id])
                            ->execute();
                    }
                    $session->write('success', "1");
                    $session->write('alert', __('Store updated successfully'));
                    $this->redirect(
                        array(
                            'controller' => 'Store',
                            'action'     => 'view',
                            $retailerId
                        )
                    );
                } else {
                    $session->write('success', "0");
                    $session->write('alert', __('An error occured while updating store'));
                    $this->redirect($this->referer()); 
                }
            } else {
                $storeData = $this->CprStores->findById($id)->toArray();
                $this->request->data['Store'] = $storeData[0];
            }
        }
    }

    /*
     * ¿Qué hace esta función?
     */
    public function delete($id , $retailerId)
    {
        $session = $this->request->session();   
       if (is_numeric(base64_decode($id))) {
            $store_accounts = $this->CprAccounts->find(
                'list', [
                    'conditions' => array(
                        'store_id'      => base64_decode($id),
                        'delete_status' => 0
                    ),
                    'keyField'   => 'id',
                    'valueField' => 'id'
                ]
            )->toArray();
            if (!empty($store_accounts)) {
                $conn = ConnectionManager::get('default');
                $stmt = $conn->execute('delete from cpr_account_operators where account_id IN (' . implode(",", $store_accounts) . ')');
                $stmt = $conn->execute('Update cpr_accounts Set delete_status=1 where id IN (' . implode(",", $store_accounts) . ')');
            }
            $delete['delete_status'] = 1;
            $delete['id'] = base64_decode($id);
            $user = $this->CprStores->newEntity();
            $this->CprStores->patchEntity($user, $delete);
            if ($this->CprStores->save($user)) {
                $delete1['delete_status'] = 1;
                $this->CprAccounts->query()->update()
                    ->set(['delete_status' => 1])
                    ->where(['store_id' => base64_decode($id)])
                    ->execute();
                $this->CprUsers->query()->update()
                    ->set(['delete_status' => 1])
                    ->where(['assigned_to' => base64_decode($id), 'user_type IN' => [6,7]])
                    ->execute();
        $session->write('success', "1");
        $session->write('alert', __('Store deleted successfully'));
        $this->redirect(
            array(
                'controller' => 'Store',
                'action'     => 'view',
                $retailerId));
            }
        }
    }

    /*
     * ¿Qué hace esta función?
     */
    public function viewAccounts($store_id)
    {
        if (is_numeric(base64_decode($store_id))) {
            $store_id = base64_decode($store_id);
            $store = $this->CprStores->findById($store_id)->toArray();
            $this->set('store', $store);
            $storesaccts = $this->CprAccounts->find(
                'all', [
                    'conditions' => array(
                        'store_id'      => $store_id,
                        'delete_status' => 0
                    )
                ]
            )->toArray();
            $account_type = array(
                '1' => __('Postpaid'),
                '2' => __('Prepaid')
            );
            $i = 0;
            $operators = $this->CprOperators->find(
                'list', [
                    'conditions' => array('status' => 1),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
            $storesacctds = array();
            foreach ($storesaccts As $storeacct) {
                $j = 0;
                $store_op = array();
                $store_operator = $this->CprAccountOperators->find(
                    'all', [
                        'conditions' => array('account_id' => $storeacct->id)
                    ]
                )->toArray();
                foreach ($store_operator As $op) {
                    $store_op[$j] = $operators[$op->operator_id];
                    $j++;
                }
                $storesacctds[$i]['Account']['operators'] = $store_op; 
                $storesacctds[$i]['Account']['account_type'] = $account_type[$storeacct->account_type];
                $storesacctds[$i]['Account']['amount'] = $storeacct->amount;
                $storesacctds[$i]['Account']['id'] = $storeacct->id;
                $storesacctds[$i]['Account']['credit_limit'] = $storeacct->account_type == 1 ? $storeacct->credit_limit : 'N/A';
                $i++;
            }
            $this->set('storesacct', $storesacctds);
        }
    }
}
