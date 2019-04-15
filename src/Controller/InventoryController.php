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
use Cake\Network\Response;
use Cake\Core\Configure;

/**
 * Inventory Controller
 *
 * Handles prepaid airtime inventory for resellers and distributors
 *
 */
class InventoryController extends AppController
{
    var $uses = array(
        'Setting',
        'Operator',
        'AirtimePurchaseHistory',
        'User',
        'Retailer',
        'Account',
        'RetailerAccountDeposit',
        'AirtimeMovement',
        'Store'
    );

    /*
     * ¿Qué hace esta función?
     */
    public function initialize()
    {
        $this->loadComponent('Validation');
        $this->loadModel('CprSettings');
        $this->loadModel('CprAirtimePurchaseHistories');
        $this->loadModel('CprRetailerAccountDeposits');
        $this->loadModel('CprAirtimeMovements');
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
        $this->set('URL', Configure::read('Server.URL'));

    }

    /*
     * ¿Qué hace esta función?
     */
    public function index() 
    {
        $session = $this->request->session();
        if (!empty($this->request->data)) {
            $qry = 
                'UPDATE cpr_operators SET balance = balance +' .
                    $this->request->data['Inventory']['amount'] .
                    ' WHERE id=\'' .
                    $this->request->data['Inventory']['operator'] .
                    '\'';
            $conn = ConnectionManager::get('default');
            $stmt = $conn->execute($qry);
            $History['AirtimePurchaseHistory']['amount'] = $this->request->data['Inventory']['amount'];
            $History['AirtimePurchaseHistory']['operator'] = $this->request->data['Inventory']['operator'];
            $History['AirtimePurchaseHistory']['document_no'] = $this->request->data['Inventory']['document_no'];
            $user = $this->CprAirtimePurchaseHistories->newEntity();
            $this->CprAirtimePurchaseHistories->patchEntity($user, $History['AirtimePurchaseHistory']);
            $this->CprAirtimePurchaseHistories->save($user);
            $session->write('success', "1");
            $session->write('alert', __('Cuenta agregada correctamente'));
            $this->redirect(
                array(
                    'controller'=>'inventory',
                    'action'=>'index'
                )
            );
        } 
        $data = $this->CprOperators->find('all')->toArray();
        $this->set('userdata', $data);
        $Operatordata = $this->CprOperators->find(
            'list', [
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('Operatordata', $Operatordata);
        $Admindata = $this->CprUsers->find(
            'all', [
                'conditions' => array(
                    'id'            => $session->read('user_id'),
                    'delete_status' => 0
                )
            ]
        );
        $this->set('Admindata', $Admindata);
    }

    /*
     * ¿Qué hace esta función?
     */
    public function admin_exportHistory() 
    {
        $this->autoRender=false;
        $data = $this->AirtimePurchaseHistory->find(
            'all',
            array(
                'fields' => array(
                    'AirtimePurchaseHistory.*',
                    'Operator.name'
                ),
                'order'  => 'datetime desc',
                'joins'  => array(
                    array(
                        'table'      => 'cpr_operators',
                        'alias'      => 'Operator',
                        'type'       => 'INNER',
                        'conditions' => array('AirtimePurchaseHistory.operator = Operator.id')
                    )
                )
            )
        );
        $content = '';
        if (!empty($data)) {
            $content .= "Operator,Amount,Document Number,DateTime" . "\n";
            foreach ($data As $recharge) { 
                $content .=
                    $recharge['Operator']['name'] . "," .
                    $recharge['AirtimePurchaseHistory']['amount'] . "," .
                    $recharge['AirtimePurchaseHistory']['document_no'] ."," .
                    date('d M Y h:i A', strtotime($recharge['AirtimePurchaseHistory']['datetime'])) .
                    "\n";
            }    
        }
        $path = realpath(EXPORT_PATH) . '/';
        $FileName = 'AirtimePurchases.csv';
        $NewFile = $path . $FileName;
        file_put_contents($NewFile, $content);
        header('Content-Type: application/csv'); 
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        readfile($NewFile);
        exit(); 
    }

    /*
     * Set minimum balance threshold for inventory warnings
     */
    public function limit()
    {
        $session = $this->request->session();
        if (!empty($this->request->data)) {
            $operators = $this->CprOperators->find('all')->toArray();
            foreach($operators AS $operator) {
                $var  = 'min_balance' . $operator->id;
                $value['Operator']['minimum_balance'] = $this->request->data['Operator'][$var];
                $value['Operator']['id'] = $operator->id;
                $user = $this->CprOperators->newEntity();
                $this->CprOperators->patchEntity($user, $value['Operator']);
                $this->CprOperators->save($user);
                $session->write('success', "1");
                $session->write('alert', __('Límite mínimo guardado correctamente'));
            }
            $this->redirect(
                array(
                    'controller' => 'inventory',
                    'action'     => 'limit'
                )
            );
        } else {
            $opr = $this->CprOperators->find('all')->toArray();
            $this->request->data = $opr;
        }
    }
    
    /*
     * Add or subtract amount from retailers account.
     */
    public function addsubAmount()
    {
        $session = $this->request->session();
        $retailers = $this->CprRetailers->find(
            'list', [
                'conditions' => array(
                    'status'        => 1,
                    'delete_status' => 0
                ),
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        );
        $this->set('retailers',$retailers);
        if (!empty($this->request->data)) {
            $data = $this->request->data['RetailerAccountDeposit'];
            $user = $this->CprRetailerAccountDeposits->newEntity();
            $this->CprRetailerAccountDeposits->patchEntity($user, $data);
            if ($this->CprRetailerAccountDeposits->save($user)) {
                $account_detail = $this->CprAccounts->findById($data['account_id'])->toArray();
                $retailer_detail = $this->CprRetailers->findById($data['retailer_id'])->toArray();
                if ($account_detail[0]->account_type == 1) {
                    if ($data['operation'] == 1) {
                        $updateData['amount'] = $account_detail[0]->amount - $data['amount'];
                    } else {
                        $updateData['amount'] = $account_detail[0]->amount + $data['amount'];
                    }
                } else if ($account_detail[0]->account_type == 2) {
                    if ($data['operation'] == 1) {
                        $updateData['amount'] = $account_detail[0]->amount + $data['amount'];
                    } else {
                        $updateData['amount'] = $account_detail[0]->amount - $data['amount'];
                    }
                }
                $storeUpd = $this->CprAccounts->query()->update()
                    ->set(['amount' => $updateData['amount']])
                    ->where(['id' => $data['account_id']])
                    ->execute();
                if ($storeUpd) {
                    $session->write('success', "1");
                    $session->write('alert', __('Cuenta actualizada correctamente'));
                    $this->redirect($this->referer());
                }
            } else {
                    $session->write('success', "0");
                    $session->write('alert', __('Ha ocurrido un error al actualizar la cuenta'));
                    $this->redirect($this->referer());
            }
        }    
    }
        
    /*
     * Get retailer account list
     */
    public function getRetailerAccount($id)
    {
        $this->autoRender = false;
        $acct_type = array(
            '1' => 'Postpaid',
            '2' => 'Prepaid'
        );
        if ($id != '') {
            $accounts = $this->CprAccounts->find(
                'all', [
                    'conditions' => array(
                        'retailer_id'   => $id,
                        'store_id'      => 0,
                        'delete_status' => 0
                    ),
                    'fields'     => array(
                        'id',
                        'account_id',
                        'account_type'
                    )
                ]
            )->toArray();
            $str = __('<option value=""> Seleccionar cuenta</option>');
            foreach($accounts as $account) {
                $remaining = 6-  strlen($account['Account']['id']);
                $accountId = '';
                for ($i = 0; $i < $remaining; $i++)
                $accountId .= '0';
                $accountId .= $account['id'];
                $str .=
                    '<option value=' . $account['id'] . '>' . $accountId .
                    ' ( ' . $acct_type[$account['account_type']] . ' ) ' . '</option>';
            }
            echo $str;
            exit;
        }
    }
    
    /*
     * Transfer inventory between accounts
     */
    public function airtimeMovement() {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $assigned_to = $session->read('assigned_to');
        if ($user_type == 4) {
            $retailers = $this->CprRetailers->find(
                'list',[
                    'conditions' => array(
                        'status'          => 1 ,
                        'operation_model' => '2',
                        'delete_status'   => 0,
                        'id'              => $assigned_to
                    )
                ]
            );
        } else {
            $retailers = $this->CprRetailers->find(
                'list', [
                    'conditions' => array(
                        'status'          => 1,
                        'operation_model' => '2',
                        'delete_status'   => 0
                    )
                ]
            );
        }
        
        $retailerstr = __('<option value="">Seleccionar origen</option>');
        foreach ($retailers As $k => $v) {
            $retailerstr .= '<option value=' . $k . '>' . $v . '</option>';
        }
        $this->set('retailers', $retailerstr);
        if ($user_type == 4) {   
            $stores = $this->CprStores->find(
                'list', [
                    'conditions' => array(
                        'status'          => 1 ,
                        'operation_model' => '2',
                        'delete_status'   => 0 ,
                        'retailer_id'     => $assigned_to
                    ),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            );
        } else {
            $stores = $this->CprStores->find(
                'list', [
                    'conditions' => array(
                        'status'          => 1,
                        'operation_model' => '2',
                        'delete_status'   => 0
                    ),
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            );
        }
        $storestr = __('<option value="">Seleccionar origen</option>');
        foreach ($stores As $k => $v) {
            $storestr .= '<option value=' . $k . '>' . $v . '</option>';
        }
        $this->set('stores', $storestr);
    }

    /*
     * ¿Qué hace esta función?
     */
    public function admin_airtime_movement_edit()
    {    
        $this->requestAction(
            array(
                'controller' => 'cpanel',
                'action'     => 'admin_checkSession'
            )
        );
        $this->layout = 'admin_layout';
        $retailers = $this->Retailer->find(
            'list',
            array(
                'conditions' => array(
                    'status'          => 1 ,
                    'operation_model' => '2',
                    'delete_status'   => 0
                )
            )
        );
        $retailerstr = __('<option value="">Seleccionar origen</option>');
        foreach ($retailers As $k => $v) {
            $retailerstr .= '<option value=' . $k . '>' . $v . '</option>';
        }
        $this->set('retailers', $retailerstr);
        $stores = $this->Store->find(
            'list',
            array(
                'conditions' => array(
                    'status'          => 1 ,
                    'operation_model' => '2',
                    'delete_status'   => 0
                ),
                'fields'     => array(
                    'id',
                    'name'
                )
            )
        );
        $storestr = __('<option value="">Seleccionar origen</option>');
        foreach ($stores As $k => $v) {
            $storestr .= '<option value=' . $k . '>' . $v . '</option>';
        }
        $this->set('stores', $storestr);
        if (!empty($this->request->data)) {
            $data = $this->request->data['AirtimeMovement'];
            $acct_type = array(
                '1' => 'Postpaid',
                '2' => 'Prepaid'
            );

            if ($data['movement_type'] ==1 ) {
                $source  = $this->Retailer->find(
                    'list',
                    array(
                        'conditions' => array(
                            'status'          => 1 ,
                            'operation_model' => '2',
                            'delete_status'   => 0
                        )
                    )
                );
                $source_accounts = $this->Account->find(
                    'all',
                    array(
                        'conditions' => array(
                            'retailer_id'   => $data['source_id'],
                            'store_id'      => 0,
                            'delete_status' => 0
                        ),
                        'fields'     => array(
                            'id',
                            'account_id',
                            'account_type'
                        )
                    )
                );
                $destinations = $this->Store->find(
                    'list',
                    array(
                        'conditions' => array(
                            'retailer_id'   => $data['source_id'],
                            'status !='     => 0,
                            'delete_status' => 0
                        ),
                        'fields' => array(
                            'id',
                            'name'
                        )
                    )
                );
                $destination_accounts = $this->Account->find(
                    'all',
                    array(
                        'conditions' => array(
                            'retailer_id'   => $data['source_id'],
                            'store_id'      => $data['destination_id'],
                            'delete_status' => 0
                        ),
                        'fields'     => array(
                            'id',
                            'account_id',
                            'account_type'
                        )
                    )
                );
            } else {
                $source = $this->Store->find(
                    'list',
                    array(
                        'conditions' => array(
                            'status'          => 1,
                            'operation_model' => '2',
                            'delete_status'   => 0
                        ),
                        'fields'     => array(
                            'id',
                            'name'
                        )
                    )
                );
                $source_accounts = $this->Account->find(
                    'all',
                    array(
                        'conditions' => array(
                            'retailer_id'   => $data['destination_id'],
                            'store_id'      => $data['source_id'],
                            'delete_status' => 0
                        ),
                        'fields'     => array(
                            'id',
                            'account_id',
                            'account_type'
                        )
                    )
                );
                $destinations = $this->Retailer->find(
                    'list',
                    array(
                        'conditions' => array(
                            'id'            => $data['destination_id'],
                            'delete_status' => 0
                        ),
                        'fields'     => array(
                            'id',
                            'name'
                        )
                    )
                );
                $destination_accounts = $this->Account->find(
                    'all',
                    array(
                        'conditions' => array(
                            'retailer_id'   => $data['destination_id'],
                            'store_id'      => 0,
                            'delete_status' => 0
                        ),
                        'fields'     => array(
                            'id',
                            'account_id',
                            'account_type'
                        )
                    )
                );
            }
            $source_acct_arr;
            foreach ($source_accounts As $source_account) {
                $remaining = 6 - strlen($source_account['Account']['id']);
                $saccount = '';
                for ($i = 0; $i < $remaining; $i++) {
                    $saccount .= '0';
                }
                $saccount .= $source_account['Account']['id'];
                $source_acct_arr[$source_account['Account']['id']] =
                    $saccount . " ( " . $acct_type[$source_account['Account']['account_type']] .
                    " ) ";
            }
            $destination_acct_arr;
            foreach ($destination_accounts As $destination_account) {
                $remaining = 6 - strlen($destination_account['Account']['id']);
                $saccount = '';
                for ($i = 0; $i < $remaining; $i++) {
                    $saccount .= '0';
                }
                $saccount .= $destination_account['Account']['id'];
                $destination_acct_arr[$destination_account['Account']['id']] =
                    $saccount . " ( " . $acct_type[$destination_account['Account']['account_type']] .
                    " ) ";
            }
            $this->set('source', $source);
            $this->set('source_acct', $source_acct_arr);
            $this->set('destination', $destinations);
            $this->set('destination_acct', $destination_acct_arr);
        }
    }
    
    /*
     * Confirm inventory movement
     */
     public function airtimeMovementConfirm()
     {
        if ($this->request->data['AirtimeMovement']['movement_type'] == 1) {
            $source_detail = $this->CprRetailers->findById(
                $this->request->data['AirtimeMovement']['source_id']
            )->toArray();
            $destination_detail = $this->CprStores->findById(
                $this->request->data['AirtimeMovement']['destination_id']
            )->toArray();
            $this->request->data['AirtimeMovement']['source'] = $source_detail[0]->name;
            $this->request->data['AirtimeMovement']['destination'] = $destination_detail[0]->name;
        } else {
            $source_detail = $this->CprStores->findById(
                $this->request->data['AirtimeMovement']['source_id']
            )->toArray();
            $destination_detail = $this->CprRetailers->findById(
                $this->request->data['AirtimeMovement']['destination_id']
            )->toArray();
            $this->request->data['AirtimeMovement']['source'] = $source_detail[0]->name;
            $this->request->data['AirtimeMovement']['destination'] = $destination_detail[0]->name;
        }
        $source_acct = $this->CprAccounts->findById(
            $this->request->data['AirtimeMovement']['source_account']
        )->toArray();
        $destination_acct = $this->CprAccounts->findById(
            $this->request->data['AirtimeMovement']['destination_account']
        )->toArray();
        $remaining = 6 - strlen($source_acct[0]->id);
        $saccountId = '';
        for ($i = 0; $i < $remaining; $i++) {
            $saccountId .= '0';
        }
        $saccountId .= $source_acct[0]->id;
        $remaining = 6 - strlen($destination_acct[0]->id);
        $daccountId = '';
        for ($i = 0; $i < $remaining; $i++) {
            $daccountId .= '0';
        }
        $daccountId .= $destination_acct[0]->id;
        $this->request->data['AirtimeMovement']['source_account_id'] = $saccountId;
        $this->request->data['AirtimeMovement']['destination_account_id'] = $daccountId;
     }

    /*
     * Confirm inventory movement
     */
    public function airtimeMovementDone()
    {
        $session = $this->request->session();
        $this->autoRender = false;
        if (!empty($this->request->data)) {
            $data = $this->request->data['AirtimeMovement'];
            if ($data['movement_type'] == 1) {
                $retailer = $data['source_id'];
                $data['retailer_id'] = $data['source_id'];
                $data['store_id'] = $data['destination_id'];
            } else {
                $retailer = $data['destination_id'];
                $data['retailer_id'] = $data['destination_id'];
                $data['store_id'] = $data['source_id'];
            }
            $retailer_detail = $this->CprRetailers->findById($retailer)->toArray();
            $source_acct = $this->CprAccounts->findById($data['source_account'])->toArray();
            $destination_acct = $this->CprAccounts->findById($data['destination_account'])->toArray();
            if ($source_acct[0]->amount >= $data['amount']) {
                $data['user_id'] = $session->read('user_id');
                $user = $this->CprAirtimeMovements->newEntity();
                $this->CprAirtimeMovements->patchEntity($user, $data);
                if ( $this->CprAirtimeMovements->save($user)) {
                    $AirtimeMovementId = $user->id;
                    $updateData['amount'] = $source_acct[0]->amount - $data['amount'];
                    $storeUpd = $this->CprAccounts->query()->update()
                                    ->set(['amount' => $updateData['amount']])
                                    ->where(['id' => $data['source_account']])
                                    ->execute();
                    if ($storeUpd) {
                        $updateData['amount'] = $destination_acct[0]->amount + $data['amount'];
                        $storeUpd = $this->CprAccounts->query()->update()
                                    ->set(['amount' => $updateData['amount']])
                                    ->where(['id' => $data['destination_account']])
                                    ->execute();
                        if ($storeUpd) {
                            $session->write('success',"1");
                            $session->write('alert', __('Movimiento realizado correctamente'));
                            $this->redirect(
                                array(
                                    'controller' => 'inventory',
                                    'action'     => 'airtime_movement_finish',
                                    $AirtimeMovementId
                                )
                            );
                        }
                    }
                } else {
                    $session->write('success', "0");
                    $session->write('alert', __('Ha ocurrido un error en el movimiento de inventario'));
                    $this->redirect(
                        array(
                            'controller' => 'inventory',
                            'action'     => 'airtime_movement_finish',
                            -1
                        )
                    );
                }
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Fondos insuficientes'));
                $this->redirect(
                    array(
                        'controller' => 'inventory',
                        'action'     => 'airtime_movement_finish',
                        -1
                    )
                );
            }
        }
     }

    /*
     * What does this function do?
     */
    public function airtimeMovementFinish($movement_id)
    {
        $session = $this->request->session();
        $movement_detail = array();
        if ($movement_id != -1) {
            $airtime_detail = $this->CprAirtimeMovements->findById($movement_id)->toArray();
            if ($airtime_detail[0]->movement_type == 1) {
                $source_detail = $this->CprRetailers->findById($airtime_detail[0]->source_id)->toArray();
                $destination_detail =  $this->CprStores->findById($airtime_detail[0]->destination_id)->toArray();
                $movement_detail['source'] = $source_detail[0]->name;
                $movement_detail['destination'] = $destination_detail[0]->name;
            } else {
                $source_detail = $this->CprStores>findById($airtime_detail[0]->source_id)->toArray();
                $destination_detail =  $this->CprRetailers->findById($airtime_detail[0]->destination_id)->toArray();
                $movement_detail['source'] = $source_detail[0]->name;
                $movement_detail['destination'] = $destination_detail[0]->name;
            }
            $source_acct = $this->CprAccounts->findById($airtime_detail[0]->source_account)->toArray();
            $destination_acct = $this->CprAccounts->findById($airtime_detail[0]->destination_account)->toArray();
            $remaining = 6 - strlen($source_acct[0]->id);
            $saccountId = '';
            for ($i = 0; $i < $remaining; $i++) {
                $saccountId .= '0';
            }
            $saccountId .= $source_acct[0]->id;
            $movement_detail['source_account'] = $saccountId;
            $remaining = 6-strlen($destination_acct[0]->id);
            $daccountId = '';
            for ($i = 0; $i < $remaining; $i++) {
                $daccountId .= '0';
            }
            $daccountId .= $destination_acct[0]->id;
            $movement_detail['destination_account'] = $daccountId;
            $movement_detail['amount'] = $airtime_detail[0]->amount;
            $movement_detail['document_no'] = $airtime_detail[0]->document_no;
            $movement_detail['notes'] = $airtime_detail[0]->notes;
            $movement_detail['movement_type'] = $airtime_detail[0]->movement_type;
        }
        $this->set('data', $movement_detail);
    }

    /*
     * Get a list of all stores belonging to a retailer
     */
    public function getStores($retailer_id)
    {
        $this->autoRender = false;
        if ($retailer_id != '') {
            $stores = $this->CprStores->find(
                'list',[
                    'conditions' => array(
                        'retailer_id'   => $retailer_id,
                        'status !='     => 0,
                        'delete_status' => 0
                    ),
                    'keyField'    => 'id',
                    'valueField'  => 'name'
                ]
            )->toArray();
            $str = __('<option value="">Seleccionar tienda</option>');
            foreach ($stores as $k => $v) {
                $str .= '<option value=' . $k . '>' . $v . '</option>';
            }  
        }
        echo $str;
        exit;
    }
    
    /*
     * Parameters : Retailer Id , Store Id
     * Description : This method is created to find all accounts of a store.
     */
    public function getStoreAccounts($retailer_id, $store_id)
    {
        $this->autoRender = false;
        $acct_type = array(
            '1' => 'Postpaid',
            '2' => 'Prepaid'
        );
        if ($retailer_id != '' && $store_id != '') {
            $accounts = $this->CprAccounts->find(
                'all',[
                    'conditions' => array(
                        'retailer_id'   => $retailer_id,
                        'store_id'      => $store_id,
                        'delete_status' => 0
                    )
                ]
            )->toArray();
            $str = __('<option value="">Seleccionar cuenta</option>');
            foreach ($accounts as $account) {
                $remaining = 6 - strlen($account->id);
                $accountId = '';
                for ($i = 0; $i < $remaining; $i++) {
                    $accountId .= '0';
                }
                $accountId .= $account->id;
                $str .= 
                    '<option value=' . $account->id . '>' . $accountId . ' ( ' .
                    $acct_type[$account->account_type] . ' ) ' . '</option>';
            }
            echo $str;
            exit;
        }
    }
    
    /*
     * Parameters : Store Id
     * Description : This method is created find retailer of a store.
     */
    public function get_retailer($store_id)
    {
        $this->autoRender = false;
        if ($store_id != '') {
            $store_detail = $this->Store->findById($store_id);
            $retailer = $this->Retailer->findById($store_detail['Store']['retailer_id']);
            $str = __('<option value="">Seleccionar destino</option>');
            $str .=
                '<option value=' . $retailer['Retailer']['id'] . '>' . $retailer['Retailer']['name'] . '</option>';
            return $str;
        }
    }
    
    /*
     * Parameters : Store Id
     * Description : This method is created find all account of a store.
     */
    public function get_store_accounts_by_store_id($store_id)
    {
        $this->autoRender = false;
        $acct_type = array(
            '1' => 'Postpaid',
            '2' => 'Prepaid'
        );
        if ($store_id != '') {
            $accounts = $this->Account->find(
                'all',
                array(
                    'conditions' => array(
                        'store_id'      => $store_id,
                        'delete_status' => 0
                    )
                )
            );
            $str = __('<option value="">Seleccionar cuenta</option>');
            foreach ($accounts as $account) {
                $remaining = 6 - strlen($account['Account']['id']);
                $accountId = '';
                for ($i = 0; $i < $remaining; $i++) {
                    $accountId .= '0';
                }
                $accountId .= $account['Account']['id'];
                $str .=
                    '<option value=' . $account['Account']['id'] . '>' . $accountId . ' ( ' .
                    $acct_type[$account['Account']['account_type']] . ' ) ' . '</option>';
            }
            return $str;
        }
    }
    
    /*
     * What does this function do?
     */
    public function getSourceAccountBalance($account_id)
    {
        $this->autoRender = false;
        if ($account_id != '') {
            $account_detail = $this->CprAccounts->findById($account_id)->toArray();
        }
        echo $account_detail['0']->amount;
        exit;
    }
    
    /*
     * What does this function do?
     */
    public function admin_send_airtime_amount_update_email(
        $source,
        $source_account,
        $destination,
        $destination_account,
        $amount,
        $document_no,
        $notes,
        $email,
        $from_email)
    {

        $subject = __('HispanoRemesas - Movimientos');
        $aMsg = __("El movimiento se ha realizado correctamente.<br/>Detalles :-<br/>");
        $aMsg .= __("<b>Origen<b> :- ") . $source . "<br/>";
        $aMsg .= __("<b>Cuenta origen<b> :- ") . $source_account . "<br/>";
        $aMsg .= __("<b>Destino<b> :- ") . $destination . "<br/>";
        $aMsg .= __("<b>Cuenta destino<b> :- ") . $destination_account . "<br/>";
        $aMsg .= __("<b>Monto<b> :- $") . $amount . "<br/>";
        $aMsg .= __("<b>Número de documento<b> :- ") . $document_no . "<br/>";
        if ($notes != '') {
            $aMsg .= __("<b>Notas<b> :- ") . $notes . "<br/>";
        }
        $Email = new CakeEmail('default');
        $Email->to($email);
        $Email->emailFormat('html');
        $Email->template('retailer_amount_update_email_template')->viewVars(
            array(
                'retailer' => $source,
                'aMsg'     => $aMsg
            )
        );
        $Email->subject($subject);
        $Email->replyTo(EMAIL_ADDRESS);
        $Email->from($from_email);
        $Email->send();
    }

    /*
     * What does this function do?
     */
    public function admin_send_amount_update_email(
        $retailer,
        $account,
        $amount,
        $operation,
        $email,
        $from_email)
    {
    
        if ($operation == 1) {
            $str = __('Monto acreditado');
        } else {
            $str = __('Monto debitado');
        }
        $remaining = 6 - strlen($account);
        $accountId = '';
        for ($i = 0; $i < $remaining; $i++) {
            $accountId .= '0';
        }
        $accountId .= $account;
        $subject = __('HispanoRemesas - ') . $str;
        $aMsg = $str . __(' Detalles :-<br/>');
        $aMsg .= __('<b>Comercio<b> :- ') . $retailer . "<br/>";
        $aMsg .= __('<b>Cuenta<b> :- ') . $accountId . "<br/>";
        $aMsg .= __('<b>Monto<b> :- $') . $amount . "<br/>";
        $Email = new CakeEmail('default');
        $Email->to($email);
        $Email->emailFormat('html');
        $Email->template('retailer_amount_update_email_template')->viewVars(
            array(
                'retailer' => $retailer,
                'aMsg'     => $aMsg
            )
        );
        $Email->subject($subject);
        $Email->replyTo(EMAIL_ADDRESS);
        $Email->from($from_email);
        $Email->send();
    }
}
