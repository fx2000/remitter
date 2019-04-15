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

/**
 * Recharges Controller
 *
 * Handles sending recharges to third party servers
 *
 */
class RechargeController extends AppController
{
    var $uses = array(
        'User',
        'Recharge',
        'Operator',
        'OperatorCredential'
    );

    /*
     * ¿Qué hace esta función?
     */
    public function initialize()
    {
        // load layout
        $this->loadModel('CprUsers');
        $this->loadModel('CprRecharges');
        $this->loadModel('CprOperatorCredentials');
        $this->loadModel('CprOperators');
        $this->viewBuilder()->layout('admin_layout');
        $this->set('URL', Configure::read('Server.URL'));
    }

    /*
     * ¿Qué hace esta función?
     */
    function status()
    {
        $Operators = $this->CprOperators->find(
            'all', [
                'conditions' => array('status' => 1),
                'fields'     => array(
                    'productId',
                    'name'
                )
            ]
        )->toArray();
        foreach ($Operators AS $operator) {
            $Operatordata[$operator->productId] = $operator->name;
        }
        $this->set('Operatordata', $Operatordata);
    }
    
    /*
     * ¿Qué hace esta función?
     */
    function viewStatus()
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        if ($this->request->data) {
            $flag = 0;
            $Userdata = $this->CprUsers->find(
                'all',[
                    'conditions'=>array('id'=>$session->read('user_id'))
                ]
            )->toArray();
            $this->set('UserData', $Userdata);
            $recharge_detail = $this->CprRecharges->find(
                'all', [
                    'conditions' => array('reference_no' => $this->request->data['Recharge']['transcation_id'])
                ]
            )->toArray();
            if ($user_type == 4) {
                if ($recharge_detail['0']->retailer_id == $session->read('assigned_to')) {
                    $flag = 1;
                }
            } else if ($user_type == 6 || $user_type == 7) {
                if ($recharge_detail['Recharge']['store_id'] == $this->Session->read('assigned_to')) {
                    $flag = 1;
                }
            } else if ($user_type == 1 || $user_type == 2 || $user_type == 3 ) {
                $flag = 1;
            }
            if ($flag == 1) {
                $header[] = "Host: 69.175.66.98:5501";
                $header[] = "Content-type: text/xml";
                $NewXml = '<methodCall>
                    <methodName>roms.eposrtxnpr</methodName>
                    <params>
                    <param><value><string>' . TRX_USERNAME . '</string></value></param>
                    <param><value><string>' . TRX_PASSWORD . '</string></value></param>
                    <param><value><string>' . $this->request->data['Recharge']['transcation_id'] . '</string></value></param>
                    <param><value><string>' . $this->request->data['Recharge']['operator'] . '</string></value></param>
                    </params>
                    </methodCall>';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, TRX_URL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST' );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $NewXml);
                $result = curl_exec($ch);
                curl_close($ch); 
                $resultArr = explode(':', $result);
                $rechageStaus = $this->CprRecharges->find('all')->hydrate(false)->join([
                    'table'      => 'cpr_operators',
                    'alias'      => 'Operator',
                    'type'       => 'INNER',
                    'conditions' => array('CprRecharges.operator_id=Operator.id')
                ])->join([
                    'table'      => 'cpr_users',
                    'alias'      => 'User',
                    'type'       => 'INNER',
                    'conditions' => array('CprRecharges.user_id=User.id')
                ])->select([
                    'User.name',
                    'Operator.name',
                    'CprRecharges.reference_no',
                    'CprRecharges.mobile_no',
                    'CprRecharges.datetime',
                    'CprRecharges.status',
                    'CprRecharges.response_msg',
                    'CprRecharges.reference_no'
                ])->where([
                    'reference_no' => $this->request->data['Recharge']['transcation_id'],
                    'operator_id'  => $this->request->data['Recharge']['operator']
                ])->toArray();
                if (!empty($rechageStaus)) {
                    $this->set('rechageStaus', $rechageStaus[0]);
                } else {
                    $session->write('success', "0");
                    $session->write('alert', __('No match Found'));
                    $this->redirect(
                        array(
                            'controller' => 'recharge',
                            'action'     => 'status'
                        )
                    );
                }
            } else {
                $session->write('success',"0");
                $session->write('alert', __('No match Found'));
                $this->redirect(
                    array(
                        'controller' => 'recharge',
                        'action'     => 'status'
                    )
                );
            }
        } else {
            $session->write('success',"0");
            $session->write('alert', __('Please enter transaction id and operator'));
            $this->redirect(
                array(
                    'controller' => 'recharge',
                    'action'     => 'status'
                )
            );
        }
            
    }

    /*
     * ¿Qué hace esta función?
     */
    public function admin_generateNewRecharge()
    {
        $this->autoRender = false;
        $rechagedata = $this->Recharge->find(
            'first', array('conditions' => array('id' => $this->request->data['Recharge']['id']))
        );
        $operatordata = $this->Operator->find(
            'first', array('conditions' => array('id' => $rechagedata['Recharge']['operator']))
        );
        $header[] = TRX_URL;
        $header[] = "Content-type: text/xml";
        echo $NewXml = '<methodCall>
            <methodName>roms.esinglextrapr</methodName>
            <params>
            <param><value><string>' . Configure::read('Server.ROMSMERCHANTID') . '</string></value></param>
            <param><value><string>' . Configure::read('Server.ROMSMERCHANTPIN') . '</string></value></param>
            <param><value><string>' . $rechagedata['Recharge']['mobile_no'] . '</string></value></param>
            <param><value><string>' . $rechagedata['Recharge']['total_amount'] . '</string></value></param>
            <param><value><string>' . $rechagedata['Recharge']['MerchantTxnId'] . '</string></value></param>
            <param><value><string></string></value></param>
            <param><value><string></string></value></param>
            <param><value><string>' . time() . '</string></value></param>
            <param><value><string>' . $operatordata['Operator']['productId'] . '</string></value></param>
            </params>
            </methodCall>';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TRX_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $NewXml);
        echo $result = curl_exec($ch);
        curl_close($ch); 
        $result1 = simplexml_load_string($result);
        $result2 = $result1->params->param->value;
        $result3 =  $result2->string;
        $ArrRechargeStatus = explode(':', $result3);
        $status = $ArrRechargeStatus[1];
        $rewardPoint = 0;
        $txnId = '';
        if ($status == '00') {
            $RechargeDone = 1;
            $message = __('Recharge has been successful');
            $txnId = $ArrRechargeStatus[2];
            $OperatorBalance = $ArrRechargeStatus[4];
            if ($rechagedata['Recharge']['recharge_from'] != 3) {
                $Settings =  $this->Recharge->query(
                    "SELECT recharge_reward_point FROM cp_settings"
                );
                $rewardPoint = $Settings[0]['cp_settings']['recharge_reward_point'];
            }
            $UpdOperatorBal = $this->Recharge->query(
                "UPDATE cp_operators SET `balance`= \"" . $OperatorBalance .
                "\" WHERE id=\"" . $rechagedata['Recharge']['operator'] .
                "\" "
            );
        } else {
            $RechargeDone = 0;
            switch ($status) {
                case 1: $messageCode = '564';
                    $message = __('Improper MerchantID');
                    break;
                case 2: $messageCode = '565';
                    $message = __('Improper CustomerPhoneNo');
                    break;
                case 3: $messageCode = '566';
                    $message = __('Improper MerchantPIN');
                    break;
                case 4: $messageCode = '567';
                    $message = __('The minimum amount should be ') . $ArrRechargeStatus[2];
                    break;
                case 5: $messageCode = '568';
                    $message = __('The maximum amount should be ') . $ArrRechargeStatus[2];
                    break;
                case 6: $messageCode = '569';
                    $message = __('Operation not supported or data inconsistency');
                    break;
                case 7: $messageCode = '570';
                    $message = __('Remote system unavailable');
                    break;
                case 8: $messageCode = '571';
                    $message = __('Insufficient funds');
                    break;
                case 9: $messageCode = '572';
                    $message = __('Duplicate Transaction');
                    break;
                case 10: $messageCode = '573';
                    $message = __('Missing MerchantID, CustomerPhoneNo, MerchantPIN or TopupAmt');
                    break;
                case 11: $messageCode = '574';
                    $message = __('Improper ProductID');
                    break;
                case 12: $messageCode = '575';
                    $message = __('Merchant account has been disabled');
                    break;
                case 13: $messageCode = '576';
                    $message = __('Improper Terminal');
                    break;
                default: $messageCode = '577';
                    $message = __('Something went wrong');
                    break;
            } 
        }
        $updRecharge = $this->Recharge->query(
            "UPDATE cp_recharges SET `status` = \"" . $RechargeDone . "\" , `transaction_id`=\"" .
            $txnId . "\",`response_code`=\"" . $status . "\",`response_message`=\"" . $message .
            "\",redeem_points=\"" . $rewardPoint . "\" WHERE id=\"" . $rechagedata['Recharge']['id'] .
            "\" "
        );
        $updUserAccount = $this->Recharge->query(
            "UPDATE cp_users SET `points` = points + \"" . $rewardPoint . "\" WHERE id=\"" .
            $rechagedata['Recharge']['user_id'] . "\" "
        );
        if ($rechagedata['Recharge']['recharge_from'] == 3 && $RechargeDone == 1) {
            $InsRedemtion = $this->Recharge->query(
                "INSERT INTO `cp_redemptions` (`id`, `user_id`, `point`, `redeem_for`, `reward_id`, `redemption_code`) VALUES ('', \"" .
                $rechagedata['Recharge']['user_id'] . "\", \"" . $rechagedata['Recharge']['amount'] .
                "\", '1',\"" . $rechagedata['Recharge']['id'] . "\", '')"
            );
            $updUserAccount = $this->Recharge->query(
                "UPDATE cp_users SET `points` = points - \"" . $rechagedata['Recharge']['amount'] .
                "\" WHERE id=\"" . $rechagedata['Recharge']['user_id'] . "\" "
            );
        }
        if ($RechargeDone == 1) {
            $this->Session->write('success', "1");
        } else {
            $this->Session->write('success', "0");
        }
        $this->Session->write('alert', $message);
        $this->redirect(
            array(
                'controller' => 'recharge',
                'action'     => 'status'
            )
        );
    }

    /*
     * ¿Qué hace esta función?
     */
    public function admin_getRechargeDetailById($id){
        $this->layout = '';
        if (!empty($id)) {
            $recharge = $this->Recharge->find(
                'first',
                array('conditions' => array('id' => $id))
            );
            return $recharge;
        }
    }
}
