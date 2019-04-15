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
use Cake\Network\Http\Client;

/**
 * Payments Controller
 *
 * Handles users
 *
 */
class PaymentController extends AppController
{

    // Activate Mailer module
    use MailerAwareTrait;
    
    var $uses = array(
        'Payment',
        'User',
        'UserType',
        'Bank',
        'AccountInvestor',
        'BankAccounts'
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
        $this->loadModel('CprPayments');
        $this->loadModel('CprRecipients');
        $this->loadModel('Users');
        $this->loadModel('CprBanks');
        $this->loadModel('CprBankAccountTypes');
        $this->loadModel('CprCountries');
        $this->loadModel('AccountInvestors');
        $this->loadModel('CprBankAccounts');
        $this->viewBuilder()->layout('admin_layout');
        $this->set('URL', Configure::read('Server.URL'));
    }

    public function index()
    {
        $payments = $this->CprPayments->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0
                )
            ]
        )->toArray();
        $investors = $this->Users->find(
            'list',[
                'conditions' => array(
                    'user_type' => 4
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $banks = $this->CprBanks->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $bank_account_type = $this->CprBankAccountTypes->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $i = 0;
        foreach ($payments As $payment) {
            $payments[$i]['investor'] = $investors[$payment['investor_id']];
            $i++;
        }
        $i = 0;
        foreach ($payments As $payment) {
            $payments[$i]['bank'] = $banks[$payment['bank_id']];
            $i++;
        }
        $i = 0;
        foreach ($payments As $payment) {
            $payments[$i]['bank_account_type'] = $bank_account_type[$payment['bank_account_type']];
            $i++;
        }
        
        $this->set("payments",$payments);
    }

    public function indexInvestor($id)
    {
        $payments = $this->CprPayments->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'investor_id' => base64_decode($id)
                )
            ]
        )->toArray();
        $investors = $this->Users->find(
            'list',[
                'conditions' => array(
                    'user_type' => 4
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $banks = $this->CprBanks->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $bank_account_type = $this->CprBankAccountTypes->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $i = 0;
        foreach ($payments As $payment) {
            $payments[$i]['investor'] = $investors[$payment['investor_id']];
            $i++;
        }
        $i = 0;
        foreach ($payments As $payment) {
            $payments[$i]['bank'] = $banks[$payment['bank_id']];
            $i++;
        }
        $i = 0;
        foreach ($payments As $payment) {
            $payments[$i]['bank_account_type'] = $bank_account_type[$payment['bank_account_type']];
            $i++;
        }
        $this->set("payments",$payments);
    }

    function add($id = null)
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $data = $this->request->data;

        $investors = $this->Users->find(
            'list',[
                'conditions' => array(
                    'user_type' => 4
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $investorInfo = $this->Users->find(
            'all',[
                'conditions' => array(
                    'id' => base64_decode($id)
                )
            ]
        )->toArray(); 
        $accountInvestor = $this->AccountInvestors->find(
            'all',[
                'conditions' => array(
                    'user_id' => base64_decode($id)
                )
            ]
        )->toArray();
        $banks = $this->CprBanks->find(
            'list',[
                'conditions' => array(
                    'country_id' => 170
                ),
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $bankAccount = $this->CprBankAccounts->find(
            'all',[
                'conditions' => array(
                    'user_id' => base64_decode($id)
                )
            ]
        )->toArray();
        $bank_account_type = $this->CprBankAccountTypes->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set("investors",$investors);
        $this->set("investorInfo",$investorInfo);
        $this->set("accountInvestor",$accountInvestor);
        $this->set("bankAccount",$bankAccount);
        $this->set("banks",$banks);
        $this->set("bank_account_type",$bank_account_type);

        if (!empty($data)) {
            if($data['Payment']['amount']<=round($accountInvestor[0]->balance, 2)){
                $data['Payment']['trans_dt']=date("Y-m-d H:i:s");
                $data['Payment']['status']=1;
                $data['Payment']['delete_status']=0;
                $data['Payment']['investor_id']=base64_decode($id);
                //$data['Payment']['bank_id']=$bankAccount[0]->bank_id;
                //$data['Payment']['account_number']=$bankAccount[0]->account_number;
                //$data['Payment']['bank_account_type']=$bankAccount[0]->account_type;

                $payment = $this->CprPayments->newEntity();
                $this->CprPayments->patchEntity($payment, $data['Payment']);
                if ($this->CprPayments->save($payment)) {

                    
                    // Send confirmation email
                    $this->getMailer('User')->send('payment_received', [$payment, $investorInfo[0], $accountInvestor[0]]);

                    // Send mobile notification
                    $http = new Client();
                    $response = $http->post('https://api.pushover.net/1/messages.json', [
                        'token'   => 'anx1ivsh6289s1dn8t8cboids6mxoh',
                        'user'    => 'uq6jg1j33et4s6w2paxjzhre31ug2w',
                        'message' => 'El usuario '. $investorInfo[0]->fname1 . ' ' . $investorInfo[0]->lname1 . ' ha emitido una solicitud de pago por ' . '$' . number_format($payment->amount, 2)
                    ]);

                    $session->write('success', "1");
                    $session->write('alert', __('Solicitud agregada correctamente '));
                    $this->redirect(
                        array(
                            'controller' => 'payment',
                            'action'     => 'index-investor',$id
                            )
                    );
                }
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Solicitud no puede ser mayor al balance actual'));
                $this->redirect(
                    array(
                        'controller' => 'payment',
                        'action'     => 'index-investor',$id
                        )
                );
            }
        }
    }

    /*
     * Edit Payment
     */
    function edit($id)
    { 
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $payment = $this->CprPayments->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'id' => base64_decode($id)
                )
            ]
        )->toArray();
        $investor = $this->Users->find(
            'all',[
                'conditions' => array(
                    'user_type' => 4,
                    'id' => $payment[0]->investor_id
                )
            ]
        )->toArray();
        $account = $this->AccountInvestors->find(
            'all',[
                'conditions' => array(
                    'user_id' => $payment[0]->investor_id
                )
            ]
        )->toArray();
        $bank = $this->CprBanks->find(
            'all', [
                'conditions' => array(
                    'id' => $payment[0]->bank_id
                )
            ]
        )->toArray();
        $country = $this->CprCountries->find(
            'all', [
                'conditions' => array(
                    'id' => $investor[0]->country_id
                )
            ]
        )->toArray();
        $type = $this->CprBankAccountTypes->find(
            'all', [
                'conditions' => array(
                    'id' => $payment[0]->bank_account_type
                )
            ]
        )->toArray();
        $this->set('payment',$payment);
        $this->set('investor',$investor);
        $this->set('bank',$bank);
        $this->set('country', $country);
        $this->set('type', $type);
        if (is_numeric(base64_decode($id))) {
            if (!empty($this->request->data)) {
                $data = $this->request->data;
                $data['Payment']['id'] = base64_decode($id);
                $pay = $this->CprPayments->newEntity();
                $this->CprPayments->patchEntity($pay, $data['Payment']);
                if ($this->CprPayments->save($pay)) {
                    if($this->request->data['Payment']['status'] == 3){
                        $data['AccountInvestor']['id'] = $account[0]->id;
                        $data['AccountInvestor']['user_id'] = $payment[0]->investor_id;
                        $data['AccountInvestor']['balance'] = $account[0]->balance-$payment[0]->amount;
                        //$data['AccountInvestor']['tmp_balance'] = $account[0]->tmp_balance-$payment[0]->amount;
                        $data['AccountInvestor']['modify_dt'] = date("Y-m-d H:i:s");
                        $accountInvestor = $this->AccountInvestors->newEntity();
                        $this->AccountInvestors->patchEntity($accountInvestor, $data['AccountInvestor']);
                        $this->AccountInvestors->save($accountInvestor);
                        $session->write('success', "1");
                        $session->write('alert', __('Pago adjudicado correctamente'));
                        $this->redirect(
                            array(
                                'controller' => 'Payment',
                                'action'     => 'index',
                            )
                        );
                    } else {
                        $session->write('success', "1");
                        $session->write('alert', __('Pago actualizado correctamente '.$data['Payment']['status']));
                        $this->redirect(
                        array(
                            'controller' => 'Payment',
                            'action'     => 'index',
                        )
                    );
                    }
                }
            } else {
                // $this->request->data['Remittance'] = $user_detail[0];
            }
        }
    }

    /*
     * Get a list of all investors
     */
    public function getInvestor($id)
    {   
        $this->autoRender = false;

        $investor = $this->Users->find(
            'all',[
                'conditions' => array(
                    'id' => $id,
                    'status' => 1
                )
            ]
        )->toArray();
        echo $investor[0];
        exit;
    }

    /*
     * Get Investor's balance
     */
    public function getBalance($id)
    {   
        $this->autoRender = false;
        $balance = $this->CprAccountInvestors->find(
            'all',[
                'conditions' => array(
                    'user_id' => $id
                )
            ]
        )->toArray();
        echo $balance[0];
        exit;
    }

    /*
     * Get Investor's banks
     */
    public function getBanks($id)
    {   
        $this->autoRender = false;
        $banksData = $this->CprBankAccounts->find(
            'all',[
                'conditions' => array(
                    'user_id' => $id
                )
            ]
        )->toArray();
        $banks = $this->CprBanks->find(
            'list',[
                'conditions' => array(
                    'id'   => $id
                ),
                'keyField'    => 'id',
                'valueField' => function ($row) {
                    return $row['name'];
                }
            ]
        )->toArray();
        $str = __('<option value="">Selecciona un banco</option>');
        foreach ($banks as $k => $v) {
            $str .= '<option value=' . $k . '>' . $v . '</option>';
        }  
        echo $str;
        exit;
    }

    /*
     * Get bank info
     */
    public function getBank2($id)
    {   
        $this->autoRender = false;
        $bank = $this->CprBanks->find(
            'all',[
                'conditions' => array(
                    'id' => $id
                )
            ]
        )->toArray();
        echo $bank[0];
        exit;
    }
}