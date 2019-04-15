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
 * Handles users
 *
 */
class WebSaleController extends AppController
{
    var $uses = array(
        'Operator',
        'Recharge',
        'Account',
        'Store'
    );
    
    /*
     * ¿Qué hace esta función?
     */
    public function initialize()
    {
        $this->loadModel('CprSmtpSettings');
        $this->loadModel('CprUsers');
        $this->loadModel('CprRecharges');
        $this->loadModel('CprOperatorCredentials');
        $this->loadModel('CprOperators');
        $this->loadModel('CprRetailerReportSettings');
        $this->loadModel('CprAirtimePurchaseHistories');
        $this->loadModel('CprRetailerAccountDeposits');
        $this->loadModel('CprAirtimeMovements');
        $this->loadModel('CprRetailers');
        $this->loadModel('CprStores');      
        $this->loadModel('CprAccounts');
        $this->viewBuilder()->layout('admin_layout');
    }

    /*
     * Parameters : None
     * Description : This method is created to recharge.
     */
    public function admin_recharge()
    {
        $this->requestAction(
            array(
                'controller' => 'cpanel',
                'action'     => 'admin_checkSession'
            )
        );
        $this->layout = 'admin_layout';
        $user_id = $this->Session->read('user_id');
        $assigned_to = $this->Session->read('assigned_to');
        $user_operators = $this->Account->query(
            "SELECT Account.*,AccountOperator.operator_id FROM cpr_accounts AS Account, cpr_account_operators AS AccountOperator WHERE Account.id=AccountOperator.account_id AND ((store_id=\"" .
            $assigned_to . "\" AND account_type=2)  || (retailer_id=(SELECT retailer_id FROM cpr_stores WHERE id=\"" .
            $assigned_to . "\" AND operation_model = 1) And store_id = 0 )) AND Account.delete_status=0"
        );
        foreach ($user_operators As $operator) {
            if ($operator['Account']['account_type'] == 1) {
                $balancecheck = $operator['Account']['credit_limit'] - $operator['Account']['amount'];
                if ($balancecheck < 0) {
                    $balancecheck = 0;
                }
                $stores_balance[$operator['AccountOperator']['operator_id']] = ($balancecheck != '') ? $balancecheck : 0;
            } else {
                $stores_balance[$operator['AccountOperator']['operator_id']] = ($operator['Account']['amount'] != '') ? $operator['Account']['amount'] : 0;
            } 
            $operatorInfo = $this->Operator->findById($operator['AccountOperator']['operator_id']);
            $operators[$operator['AccountOperator']['operator_id']] = $operatorInfo['Operator']['name'];
        }
        $this->set('stores_balance', $stores_balance);
        $this->set('operators',$operators);
        $user_store = $this->Store->query(
            'SELECT * FROM cpr_stores AS Store WHERE id = (SELECT assigned_to FROM cpr_users WHERE id = ' .
            $user_id . ') AND delete_status =0'
        );
        $this->set('user_operation_model', $user_store[0]['Store']['operation_model']);
        if (!empty($this->request->data)) {
            $this->request->data['Recharge']['cphone_no'] = $this->request->data['Recharge']['phone_no'];
        }
    }

    /*
     * ¿Qué hace esta función?
     */
    public function admin_recharge_confirmation()
    { 
        $this->requestAction(
            array(
                'controller' => 'cpanel',
                'action'     => 'admin_checkSession'
            )
        );
        $this->layout = 'admin_layout';
        $operators = $this->Operator->find(
            'list',
            array(
                'conditions' => array('status' => 1)
            )
        );
        $this->set('operators', $operators);
        $this->request->data['Recharge']['operator'] = $operators[$this->request->data['Recharge']['operator_id']];
    }

    /*
     * ¿Qué hace esta función?
     */
    public function admin_do_recharge()
    {
        $this->requestAction(
            array(
                'controller' => 'cpanel',
                'action'     => 'admin_checkSession'
            )
        );
        $this->autoRender = false;
        $operators = $this->Operator->find(
            'list',
            array(
                'conditions' => array('status' => 1)
            )
        );
        if (!empty($this->request->data)) {
            $user_id = $this->Session->read('user_id');
            $data = $this->request->data['Recharge'];
            $api_data['user_id'] = $user_id;
            $api_data['operator_id'] = $data['operator_id'];
            $api_data['amount'] = $data['amount'];
            $api_data['mobile_no'] = $data['phone_no'];
            $info = $this->httpsPost($api_data);
            if ($info['status'] != 0) {
                $this->redirect(
                    array(
                        'controller' => 'WebSale',
                        'action'     => 'recharge_status',
                        $info['data'][0]['transaction_id'],
                        $info['data'][0]['newamount']
                    )
                );
            } else {
                $result['Recharge']['operator_id'] = $operators[$data['operator_id']];
                $result['Recharge']['amount'] = $data['amount'];
                $result['Recharge']['phone_no'] = $data['phone_no'];
                $this->Session->write('success', "0");
                $this->Session->write('alert', $info['message']);
            }
        }
    }

    /*
     * ¿Qué hace esta función?
     */
    public function admin_recharge_status($transaction_id, $new_amt)
    {
        $this->requestAction(
            array(
                'controller' => 'cpanel',
                'action'     => 'admin_checkSession'
            )
        );
        $this->layout = 'admin_layout';
        $recharge_detail = $this->Recharge->find(
            'first',
            array(
                'conditions' => array('reference_no' => $transaction_id)
            )
        );
        $operators = $this->Operator->find(
            'list',
            array(
                'conditions' => array('status' => 1)
            )
        );
        if ($recharge_detail['Recharge']['status'] == 1) {
            $result['Recharge']['operator_id'] = $operators[$recharge_detail['Recharge']['operator_id']];
            $result['Recharge']['amount'] = $recharge_detail['Recharge']['amount'];
            $result['Recharge']['phone_no'] = $recharge_detail['Recharge']['mobile_no'];
            $result['Recharge']['transaction_id'] = $transaction_id;
            $result['Recharge']['recharge_status'] = $recharge_detail['Recharge']['response'];
             $result['Recharge']['new_amt'] = $new_amt;
            $result['Recharge']['datetime'] = date('Y-m-d h:i:s A', strtotime($recharge_detail['Recharge']['datetime']));
            $this->Session->write('success', "1");
            $this->Session->write('alert', __('Recharge successfull'));
        } else {
            $result['Recharge']['operator_id'] = $operators[$recharge_detail['Recharge']['operator_id']];
            $result['Recharge']['amount'] = $recharge_detail['Recharge']['amount'];
            $result['Recharge']['phone_no'] = $recharge_detail['Recharge']['mobile_no'];
            $result['Recharge']['transaction_id'] = $transaction_id;
            $result['Recharge']['recharge_status'] = $recharge_detail['Recharge']['response'];
            $result['Recharge']['datetime'] = date('Y-m-d h:i:s A',strtotime($recharge_detail['Recharge']['datetime']));
            $this->Session->write('success',"0");
            $this->Session->write('alert', __('Recharge failed'));
        }
        $this->request->data = $result;
    }

    /*
     * ¿Qué hace esta función?
     */
    public function admin_recharge_print($transaction_id)
    {
        $this->autoRender = true;
        $this->layout = null;
        $recharge_detail = $this->Recharge->find(
            'first',
            array(
                'conditions' => array('reference_no' => $transaction_id)
            )
        );
        $operators = $this->Operator->find(
            'list',
            array(
                'conditions' => array('status' => 1)
            )
        );
        if ($recharge_detail['Recharge']['status'] == 1) {
            $result['operator_id'] = $operators[$recharge_detail['Recharge']['operator_id']];
            $result['amount'] = $recharge_detail['Recharge']['amount'];
            $result['phone_no'] = $recharge_detail['Recharge']['mobile_no'];
            $result['transaction_id'] = $transaction_id;
            $result['recharge_status'] = $recharge_detail['Recharge']['response'];
        } else {
            $result['operator_id'] = $operators[$recharge_detail['Recharge']['operator_id']];
            $result['amount'] = $recharge_detail['Recharge']['amount'];
            $result['phone_no'] = $recharge_detail['Recharge']['mobile_no'];
            $result['transaction_id'] = $transaction_id;
            $result['recharge_status'] = $recharge_detail['Recharge']['response'];
        }
        $this->set('result', $result);
    }

    /*
     * ¿Qué hace esta función?
     */
    public function inventory_balance($operator_id)
    {
        $this->autoRender = false;
        $user_id = 1;
        $operator_id = 1;
        if ($user_id != '' && $operator_id != '') {
            $stores_acct = $this->Account->query(
                "SELECT * FROM cpr_accounts AS Account WHERE id = (SELECT account_id FROM cpr_account_operators AS AccountOperator
                    WHERE account_id IN (SELECT id FROM cpr_accounts AS Account WHERE store_id =
                    (SELECT id FROM cpr_stores AS Store WHERE id = (SELECT assigned_to FROM cpr_users AS User WHERE id = " .
                    $user_id . "))) And operator_id =" . $operator_id . ") "
            );
        }
    }

    /*
     * ¿Qué hace esta función?
     */
    function httpsPost($postData)
    {
        $URL = Configure::Read('Server.URL');
        $curlObj = curl_init();
        curl_setopt($curlObj, CURLOPT_URL, $URL . 'API/WebRecharge/recharge.php');
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $postData);    
        $response = curl_exec($curlObj);    
        $json = json_decode($response , true);
        curl_close($curlObj); 
        return $json;
    }
}
