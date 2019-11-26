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
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;
use Cake\Mailer\MailerAwareTrait;
use Cake\Network\Http\Client;

/**
 * Remittance Controller
 *
 * Handles remittances
 *
 */
class RemittanceController extends AppController
{

    // Activate Mailer module
    use MailerAwareTrait;

    var $uses = array(
        'User',
        'UserType',
        'Bank',
        'Recipient',
        'BankAccountTypes',
        'Settings',
        'Invoice',
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
        $this->loadModel('Remittances');
        $this->loadModel('CprRecipients');
        $this->loadModel('Users');
        $this->loadModel('CprInvestors');
        $this->loadModel('CprBanks');
        $this->loadModel('CprCountries');
        $this->loadModel('CprBankAccountTypes');
        $this->loadModel('CprSettings');
        $this->loadModel('Invoices');
        $this->loadModel('AccountInvestors');
        $this->viewBuilder()->layout('admin_layout');
        $baseUrl = Router::url('/', true);
        $this->set('baseUrl', $baseUrl);
    }

    public function index($id = null)
    {
        if ($id != null) {
            $remittances = $this->Remittances->find(
                'all', [
                    'conditions' => array(
                        'delete_status' => 0,
                        'client_id'     => base64_decode($id)
                    )
                ]
            )->toArray();
        } else {
            $remittances = $this->Remittances->find(
                'all', [
                    'conditions' => array(
                        'delete_status' => 0
                    )
                ]
            )->toArray();
        }
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
        $clients = $this->Users->find(
            'list',[
                'conditions' => array(
                    'user_type' => 5
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $recipients = $this->CprRecipients->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status'     => 1
                )
            ]
        )->toArray();
        $recipients_names = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['fname2'] . ' ' . $row['lname1'] . ' ' . $row['lname2'];
                }
            ]
        )->toArray();
        $recipients_banks = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'bank_id'
            ]
        )->toArray();
        $recipients_accounts = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'bank_account_type'
            ]
        )->toArray();
        $recipients_numbers = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'bank_account_number'
            ]
        )->toArray();
        $banks = $this->CprBanks->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $account_types = $this->CprBankAccountTypes->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $settings = $this->CprSettings->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status' => 1
                )
            ]
        )->toArray();
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['client'] = $clients[$remittance['client_id']];
            $remittances[$i]['recipient'] = $recipients_names[$remittance['recipient_id']];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            if($remittance['investor_id'] != NULL){
                $remittances[$i]['investor'] = $investors[$remittance['investor_id']];
            } else {
                $remittances[$i]['investor'] = '';
            }
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['recipient'] = $recipients_names[$remittance['recipient_id']];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['bank'] = $banks[$recipients_banks[$remittance['recipient_id']]];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['account'] = $account_types[$recipients_accounts[$remittance['recipient_id']]];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['number'] = $recipients_numbers[$remittance['recipient_id']];
            $i++;
        }
        $this->set("settings", $settings);
        $this->set("remittances", $remittances);
    }

    public function indexInvestor($id = null)
    {
        if ($id != null) {
            $remittances = $this->Remittances->find(
                'all', [
                    'conditions' => array(
                        'delete_status'  => 0,
                        'status NOT IN'  => [1],
                        'investor_id'    => base64_decode($id)
                    )
                ]
            )->toArray();
        } else {
            $remittances = $this->Remittances->find(
                'all', [
                    'conditions' => array(
                        'delete_status' => 0,
                        'status IN'     => [1,2,3]
                    )
                ]
            )->toArray();
        }
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
        $clients = $this->Users->find(
            'list',[
                'conditions' => array(
                    'user_type' => 5
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $recipients = $this->CprRecipients->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status'     => 1
                )
            ]
        )->toArray();
        $recipients_names = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['fname2'] . ' ' . $row['lname1'] . ' ' . $row['lname2'];
                }
            ]
        )->toArray();
        $recipients_banks = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'bank_id'
            ]
        )->toArray();
        $recipients_accounts = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'bank_account_type'
            ]
        )->toArray();
        $recipients_numbers = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'bank_account_number'
            ]
        )->toArray();
        $banks = $this->CprBanks->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $account_types = $this->CprBankAccountTypes->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $settings = $this->CprSettings->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status' => 1
                )
            ]
        )->toArray();
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['client'] = $clients[$remittance['client_id']];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['investor'] = $investors[$remittance['investor_id']];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['recipient'] = $recipients_names[$remittance['recipient_id']];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['bank'] = $banks[$recipients_banks[$remittance['recipient_id']]];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['account'] = $account_types[$recipients_accounts[$remittance['recipient_id']]];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['number'] = $recipients_numbers[$remittance['recipient_id']];
            $i++;
        }
        $this->set("settings", $settings);
        $this->set("remittances",$remittances);
    }

    public function indexOpDaily($id = null)
    {
        $remittances = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'operator_id' => base64_decode($id),
                    'trans_dt >=' => Date::today(),
                    'status !=' => 5
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
        $clients = $this->Users->find(
            'list',[
                'conditions' => array(
                    'user_type' => 5
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $recipients = $this->CprRecipients->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status'     => 1
                )
            ]
        )->toArray();
        $recipients_names = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['fname2'] . ' ' . $row['lname1'] . ' ' . $row['lname2'];
                }
            ]
        )->toArray();
        $recipients_banks = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'bank_id'
            ]
        )->toArray();
        $recipients_accounts = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'bank_account_type'
            ]
        )->toArray();
        $recipients_numbers = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'bank_account_number'
            ]
        )->toArray();
        $banks = $this->CprBanks->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $account_types = $this->CprBankAccountTypes->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $settings = $this->CprSettings->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status' => 1
                )
            ]
        )->toArray();
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['client'] = $clients[$remittance['client_id']];
            $remittances[$i]['recipient'] = $recipients_names[$remittance['recipient_id']];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            if($remittance['investor_id'] != null){
                $remittances[$i]['investor'] = $investors[$remittance['investor_id']];
                $i++;
            } else {
                $remittances[$i]['investor'] = '';
            }
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['recipient'] = $recipients_names[$remittance['recipient_id']];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['bank'] = $banks[$recipients_banks[$remittance['recipient_id']]];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['account'] = $account_types[$recipients_accounts[$remittance['recipient_id']]];
            $i++;
        }
        $i = 0;
        foreach ($remittances As $remittance) {
            $remittances[$i]['number'] = $recipients_numbers[$remittance['recipient_id']];
            $i++;
        }
        $this->set("settings", $settings);
        $this->set("remittances",$remittances);
    }

    function add()
    {
        $session = $this->request->session();
        $operator_id = $session->read('user_id');
        $data = $this->request->data;
        $clients = $this->Users->find(
            'list',[
                'conditions' => array(
                    'user_type'     => 5,
                    'delete_status' => 0
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $recipients = $this->CprRecipients->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['fname2'] . ' ' . $row['lname1'] . ' ' . $row['lname2'];
                }
            ]
        )->toArray();
        $settings = $this->CprSettings->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status'        => 1
                )
            ]
        )->toArray();
        $this->set('clients', $clients);
        $this->set('recipients', $recipients);
        $this->set("settings", $settings);
        
        if (!empty($data)) {

            $client_info = $this->Users->find(
                'all', [
                    'conditions' => array(
                        'delete_status' => 0,
                        'user_type' => 5,
                        'id'     => $data['Remittance']['client_id']
                    )
                ]
            )->toArray();
            $recipient_info = $this->CprRecipients->find(
                'all', [
                    'conditions' => array(
                        'delete_status' => 0,
                        'id'     => $data['Remittance']['recipient_id']
                    )
                ]
            )->toArray();
            $account_info = $this->CprBankAccountTypes->find(
                'all', [
                    'conditions' => array(
                        'id'     => $recipient_info['0']['bank_account_type']
                    )
                ]
            )->toArray();
            $bank_info = $this->CprBanks->find(
                'all', [
                    'conditions' => array(
                        'delete_status' => 0,
                        'id'     => $recipient_info['0']['bank_id']
                    )
                ]
            )->toArray();

            // Add remittance data
            $data['Remittance']['trans_dt'] = date("Y-m-d H:i:s");
            $data['Remittance']['operator_id'] = $operator_id;
            $data['Remittance']['status'] = 1;
            $data['Remittance']['payment_type'] = intval($data['Remittance']['type']);
            $data['Remittance']['delete_status'] = 0;

            // Empty ACH file field if payment type is cash (This is ugly, I know. DD)
            if ($data['Remittance']['payment_type'] == 1 && isset($data['Remittance']['ach'])) {
            	unset($data['Remittance']['ach']);
            } else if ($data['Remittance']['payment_type'] == 2 && $data['Remittance']['ach']['error'] == 4) {
                $session->write('success', "0");
                $session->write('alert', __('Las remesas ACH deben llevar un comprobante de transferencia'));
                return $this->redirect(
                    array(
                        'controller' => 'remittance',
                        'action'     => 'add'
                        )
                );
            }

            // Add invoice data
            $data['Invoice']['nombre'] = $client_info['0']['fname1'] . ' ' . $client_info['0']['lname1'];

            if ($client_info['0']['tax_id'] != '') {
                $data['Invoice']['cedula'] = $client_info['0']['tax_id'];
            } elseif ($client_info['0']['passport'] != '') {
                $data['Invoice']['cedula'] = $client_info['0']['passport'];
            } else {
                $data['Invoice']['cedula'] = ' ';
            }

            if (isset($client_info['0']['address'])) {
                $data['Invoice']['direccion'] = $client_info['0']['address'] . '. ' . $client_info['0']['town'] . '.';
            } else {
                $data['Invoice']['direccion'] = ' ';
            }
            
            $data['Invoice']['total_pagos'] = $data['Remittance']['fee'] + $data['Remittance']['tax'];
            $data['Invoice']['total_final'] = $data['Invoice']['total_pagos'];
            if ($data['Remittance']['payment_type'] == 1) {
                $data['Invoice']['efectivo'] = $data['Invoice']['total_pagos'];
            } else {
                $data['Invoice']['otro_pago'] = $data['Invoice']['total_pagos'];
            }
            
            $data['Invoice']['codigo'] = 'REM';
            $data['Invoice']['unidad'] = 'TRX';
            $data['Invoice']['precio_neto'] = $data['Remittance']['fee'];
            $data['Invoice']['alicuota'] = $settings[0]['tax'];
            
            $remittance = $this->Remittances->newEntity();
            $this->Remittances->patchEntity($remittance, $data['Remittance']);
            

            if ($this->Remittances->save($remittance)) {

                // Send confirmation email
                $this->getMailer('User')->send('remittance_received', [$remittance, $recipient_info[0], $client_info[0]]);

                // Uncomment this to send mobile notifications through Pushover
                $http = new Client();
                /* $response = $http->post('https://api.pushover.net/1/messages.json', [
                    'token'   => '',
                    'user'    => '',
                    'sound'   => 'cashregister',
                    'message' => 'El cliente '. $data['Invoice']['nombre'] . ' ha enviado la remesa ' . $remittance->id . ' por $' . number_format($remittance->amount, 2) . ' a ' . $bank_info[0]['name']
                ]); */

                $data['Invoice']['id_remesa'] = $remittance->id;
                $data['Invoice']['nombre_articulo'] = 'Remesa Panamá->Venezuela' . str_pad(($data['Invoice']['id_remesa']), 6, "0", STR_PAD_LEFT);

                $invoice = $this->Invoices->newEntity();
                $this->Invoices->patchEntity($invoice, $data['Invoice']);

                if ($this->Invoices->save($invoice)) {

                    // Generate csv files for fiscal printer
                    $fiscalPrint = $this->invoice($invoice->id);

                    $session->write('success', "1");
                    $session->write('alert', __('Remesa generada correctamente'));
                    $this->redirect(
                        array(
                            'controller' => 'remittance',
                            'action'     => 'print'
                            )
                    );
                }
            }
        }
    }

    /*
     * Edit Remittance
     */
    function edit($id)
    { 
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $remittance_detail = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'id'            => base64_decode($id)
                )
            ]
        )->toArray();
        $account = $this->AccountInvestors->find(
            'all', [
                'conditions' => array(
                    'user_id' => $remittance_detail[0]->investor_id
                )
            ]
        )->toArray();
        $client = $this->Users->find(
            'list',[
                'conditions' => array(
                    'id' => $remittance_detail[0]['client_id']
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $recipient = $this->CprRecipients->find(
            'list',[
                'conditions' => array(
                    'id' => $remittance_detail[0]['recipient_id']
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['fname2'] . ' ' . $row['lname1'] . ' ' . $row['lname2'];
                }
            ]
        )->toArray();
        $investor = $this->Users->find(
            'list',[
                'conditions' => array(
                    'id' => $remittance_detail[0]['investor_id']
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $client_info = $this->Users->find(
            'all', [
                'conditions' => array(
                    'id'     => $remittance_detail[0]['client_id']
                )
            ]
        )->toArray();
        $recipient_info = $this->CprRecipients->find(
            'all', [
                'conditions' => array(
                    'id'     => $remittance_detail[0]['recipient_id']
                )
            ]
        )->toArray();
        $investor_info = $this->Users->find(
            'all', [
                'conditions' => array(
                    'id'     => $remittance_detail[0]['investor_id']
                )
            ]
        )->toArray();
        $bank_info = $this->CprBanks->find(
            'all',[
                'conditions' => array(
                    'id' => $recipient_info[0]['bank_id']
                ),
            ]
        )->toArray();
        $operator_info = $this->Users->find(
            'all',[
                'conditions' => array(
                    'id' => $remittance_detail[0]['operator_id']
                ),
            ]
        )->toArray();
        $this->set('client', $client);
        $this->set('recipient', $recipient);
        $this->set('investor', $investor);
        $this->set('client', $client);
        $this->set('recipient_info', $recipient_info);
        $this->set('investor_info', $investor_info);
        $this->set('client_info', $client_info);
        $this->set('bank_info', $bank_info);
        $this->set('operator_info', $operator_info);
        $this->set('remittance', $remittance_detail);

        if (is_numeric(base64_decode($id))) {

            if (!empty($this->request->data)) {
                $data = $this->request->data;
                $data['Remittance']['id'] = base64_decode($id);
                $remittance = $this->Remittances->newEntity();
                $this->Remittances->patchEntity($remittance, $data['Remittance']);

                if ($this->Remittances->save($remittance)) {

                    if ($this->request->data['Remittance']['status'] == 4) {
                        
                        // Save delivered date/time CHECK THIS
                        $data['Remittance']['delivered_dt'] = date("Y-m-d H:i:s");
                        $remittance = $this->Remittances->newEntity();                      // ADDED
                        $this->Remittances->patchEntity($remittance, $data['Remittance']);  // ADDED
                        $this->Remittances->save($remittance);

                        // Send confirmation email for client
                        $this->getMailer('User')->send('remittance_sent', [$remittance_detail[0], $recipient_info[0], $client_info[0]]);

                        $data['AccountInvestor']['id'] = $account[0]->id;
                        $data['AccountInvestor']['user_id'] = $remittance_detail[0]->investor_id;
                        $data['AccountInvestor']['balance'] = $account[0]->balance + $remittance_detail[0]->amount_sold;
                        $data['AccountInvestor']['tmp_balance'] = $account[0]->tmp_balance - $remittance_detail[0]->amount_sold;
                        $data['AccountInvestor']['modify_dt'] = date("Y-m-d H:i:s");
                        $accountInvestor = $this->AccountInvestors->newEntity();
                        $this->AccountInvestors->patchEntity($accountInvestor, $data['AccountInvestor']);
                        $this->AccountInvestors->save($accountInvestor);

                        // Send confirmation email for investor
                        // $this->getMailer('User')->send('remittance_completed', [$remittance_detail[0], $recipient_info[0], $investor_info[0], $accountInvestor]);

                        $session->write('success', "1");
                        $session->write('alert', __('Remesa completada correctamente'));
                        $this->redirect(
                            array(
                                'controller' => 'Remittance',
                                'action'     => 'index',
                            )
                        );
                    } else {
                        $session->write('success', "1");
                        $session->write('alert', __('Remesa actualizada correctamente'));
                        $this->redirect(
                            array(
                                'controller' => 'Remittance',
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
     * Print last Remittance
     */
    function print($id = null)
    { 
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        if ($id == null) {
            $remittance_detail = $this->Remittances->find(
                'all', 
                    array(
                        'order' => array(
                            'id' => 'DESC'
                        ),
                        'limit' => '1'
                    )
            )->toArray();
        } else {
            $remittance_detail = $this->Remittances->find(
                'all', [
                    'conditions' => array(
                        'delete_status' => 0,
                        'id'     => $id
                    )
                ]
            )->toArray();
        }
        $account = $this->AccountInvestors->find(
            'all', [
                'conditions' => array(
                    'user_id' => $remittance_detail[0]->investor_id
                )
            ]
        )->toArray();
        $client = $this->Users->find(
            'all',[
                'conditions' => array(
                    'id' => $remittance_detail[0]['client_id']
                ),
            ]
        )->toArray();
        $recipient = $this->CprRecipients->find(
            'all',[
                'conditions' => array(
                    'id' => $remittance_detail[0]['recipient_id']
                ),
            ]
        )->toArray();
        $investor = $this->Users->find(
            'list',[
                'conditions' => array(
                    'id' => $remittance_detail[0]['investor_id']
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $bank = $this->CprBanks->find(
            'all',[
                'conditions' => array(
                    'id' => $recipient[0]['bank_id']
                ),
            ]
        )->toArray();
        $operator_info = $this->Users->find(
            'all',[
                'conditions' => array(
                    'id' => $remittance_detail[0]['operator_id']
                ),
            ]
        )->toArray();
        $this->set('client',$client);
        $this->set('recipient',$recipient);
        $this->set('investor',$investor);
        $this->set('remittance',$remittance_detail);
        $this->set('bank',$bank);
        $this->set('operator',$operator_info);
    }

    /*
     * Apply Remittance
     */
    function apply($id)
    { 
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $remittance = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'id' => base64_decode($id)
                )
            ]
        )->toArray();
        $this->set('remittance', $remittance);

        $client = $this->Users->find(
            'list',[
                'conditions' => array(
                    'id'   => $remittance['0']->client_id
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $this->set('client', $client);

        $investor = $this->Users->find(
            'all',[
                'conditions' => array(
                    'id'   => $session->read('user_id')
                ),
            ]
        )->toArray();
        $this->set('investor', $investor);

        $recipient = $this->CprRecipients->find(
            'all', [
                'conditions' => array(
                    'id' => $remittance['0']->recipient_id
                )
            ]
        )->toArray();
        $this->set('recipient', $recipient);

        $bank = $this->CprBanks->find(
            'all', [
                'conditions' => array(
                    'id' => $recipient['0']->bank_id
                )
            ]
        )->toArray();
        $this->set('bank', $bank);

        $country = $this->CprCountries->find(
            'all', [
                'conditions' => array(
                    'id' => $recipient['0']->country_id
                )
            ]
        )->toArray();
        $this->set('country', $country);

        $type = $this->CprBankAccountTypes->find(
            'all', [
                'conditions' => array(
                    'id' => $recipient['0']->bank_account_type
                )
            ]
        )->toArray();
        $this->set('type', $type);

        $settings = $this->CprSettings->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status' => 1
                )
            ]
        )->toArray();
        $this->set("settings",$settings);
        if (is_numeric(base64_decode($id))) {
            if (!empty($this->request->data)) {
                $data = $this->request->data;
                $data['Remittance']['id'] = base64_decode($id);
                $data['Remittance']['investor_id'] = $session->read('user_id');
                $data['Remittance']['reserved_dt'] = date("Y-m-d H:i:s");
                $data['Remittance']['status'] = 2;
                $data['Remittance']['sale_rate'] = $settings[0]['sale_rate'];  // ADDED
                $remittance = $this->Remittances->newEntity();
                $this->Remittances->patchEntity($remittance, $data['Remittance']);

                if ($this->Remittances->save($remittance)) {

                    // Send mobile notification
                    $http = new Client();
                    $response = $http->post('https://api.pushover.net/1/messages.json', [
                        'token'   => 'anx1ivsh6289s1dn8t8cboids6mxoh',
                        'user'    => 'uq6jg1j33et4s6w2paxjzhre31ug2w',
                        'message' => 'El inversionista '. $investor[0]['fname1'] . ' ' . $investor[0]['lname1'] . ' ha reservado la remesa ' . $data['Remittance']['id'] . ' a ' . $bank[0]['name']
                    ]);

                    $session->write('success', "1");
                    $session->write('alert', __('Remesa reservada correctamente '));
                    $this->redirect(
                        array(
                            'controller' => 'Remittance',
                            'action'     => 'index',
                        )
                    );
                }
            } else {
                // $this->request->data['Remittance'] = $user_detail[0];
            }
        }
    }

    /*
     * Get a list of all customers
     */
    public function getCustomer($id)
    {   
        $this->autoRender = false;
            $customer = $this->Users->find(
                'all',[
                    'conditions' => array(
                        'id' => $id,
                        'delete_status' => '0'
                    )
                ]
            )->toArray();
        echo $customer[0];
        exit;
    }

    /*
     * Get a list of all recipients from a customer
     */
    public function getRecipients($id)
    {
        $this->autoRender = false;
            $recipients = $this->CprRecipients->find(
                'list',[
                    'conditions' => array(
                        'client_id'     => $id,
                        'status !='     => 0,
                        'delete_status' => 0
                    ),
                    'keyField'    => 'id',
                    'valueField' => function ($row) {
                        return $row['fname1'] . ' ' . $row['fname2'] . ' ' . $row['lname1'] . ' ' . $row['lname2'];
                    }
                ]
            )->toArray();
            $str = __('<option value="">Selecciona un beneficiario</option>');
            foreach ($recipients as $k => $v) {
                $str .= '<option value=' . $k . '>' . $v . '</option>';
            }  
        echo $str;
        exit;
    }

    /*
     * Get recipient info
     */
    public function getRecipient($id)
    {
        $this->autoRender = false;
            $recipient = $this->CprRecipients->find(
                'all',[
                    'conditions' => array(
                        'id' => $id,
                        'delete_status' => 0
                    )
                ]
            )->toArray();
            $countries = $this->CprCountries->find(
                'list',[
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
            $i = 0;
            foreach ($recipient As $r) {
                $recipient[$i]['country'] = $countries[$r['country_id']];
                $i++;
            }
            $banks = $this->CprBanks->find(
                'list',[
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
            $i = 0;
            foreach ($recipient As $r) {
                $recipient[$i]['bank'] = $banks[$r['bank_id']];
                $i++;
            }
            $account_types = $this->CprBankAccountTypes->find(
                'list',[
                    'keyField'   => 'id',
                    'valueField' => 'name'
                ]
            )->toArray();
            $i = 0;
            foreach ($recipient As $r) {
                $recipient[$i]['account_type'] = $account_types[$r['bank_account_type']];
                $i++;
            }
        echo $recipient[0];
        exit;
    }

    /*
     * Get Amounts info
     */
    public function getSettings()
    {
        $this->autoRender = false;
            $s = $this->CprSettings->find(
                'all',[
                    'conditions' => array(
                        'status' => 1
                    )
                ]
            )->toArray();
        echo $s[0];
        exit;
    }

    /*
     * Reserve Remittance
     */
    public function reserve($id,$inv,$amount)
    {
        $session = $this->request->session();
        if (is_numeric($id)) {
            $data['Remittance']['id'] = $id;
            $data['Remittance']['investor_id'] = $inv;
            $data['Remittance']['reserved_dt'] = date("Y-m-d H:i:s");
            $data['Remittance']['status'] = 2;
            $data['Remittance']['amount_sold'] = $amount;
            $remittance = $this->Remittances->newEntity();
            $this->Remittances->patchEntity($remittance, $data['Remittance']);
            if ($this->Remittances->save($remittance)) {
                $session->write('success', "1");
                $session->write('alert', __('Remesa reservada correctamente'));
                $this->redirect(
                    array(
                        'controller' => 'Remittance',
                        'action'     => 'index',
                    )
                );
            }
        }
        exit;
    }

    /*
     * Edit Remittance
     */
    function confirm($id)
    { 
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $remittance = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'id' => base64_decode($id)
                )
            ]
        )->toArray();
        $this->set('remittance', $remittance);
        $client = $this->Users->find(
            'list',[
                'conditions' => array(
                    'id'   => $remittance['0']->client_id
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
            ]
        )->toArray();
        $this->set('client', $client);
        $recipient = $this->CprRecipients->find(
            'list',[
                'conditions' => array(
                    'id'   => $remittance['0']->recipient_id
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['fname2'] . ' ' . $row['lname1'] . ' ' . $row['lname2'];
                }
            ]
        )->toArray();
        $this->set('recipient', $recipient);
        $recipient = $this->CprRecipients->find(
            'all', [
                'conditions' => array(
                    'id' => $remittance['0']->recipient_id
                )
            ]
        )->toArray();
        $this->set('recipient', $recipient);
        $bank = $this->CprBanks->find(
            'all', [
                'conditions' => array(
                    'id' => $recipient['0']->bank_id
                )
            ]
        )->toArray();
        $this->set('bank', $bank);
        $country = $this->CprCountries->find(
            'all', [
                'conditions' => array(
                    'id' => $recipient['0']->country_id
                )
            ]
        )->toArray();
        $this->set('country', $country);
        $type = $this->CprBankAccountTypes->find(
            'all', [
                'conditions' => array(
                    'id' => $recipient['0']->bank_account_type
                )
            ]
        )->toArray();
        $this->set('type', $type);
        $settings = $this->CprSettings->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status'        => 1
                )
            ]
        )->toArray();
        $this->set("settings",$settings);
        $accountInvestor = $this->AccountInvestors->find(
            'all', [
                'conditions' => array(
                    'user_id' => $remittance['0']->investor_id
                )
            ]
        )->toArray();
        $investor = $this->Users->find(
            'all',[
                'conditions' => array(
                    'id'   => $remittance['0']->investor_id
                ),
            ]
        )->toArray();
        if (is_numeric(base64_decode($id))) {
            if (!empty($this->request->data)) {
                $data = $this->request->data;
                $data['Remittance']['id'] = base64_decode($id);
                $data['Remittance']['status'] = 3;
                $data['Remittance']['applyed_dt'] = date("Y-m-d H:i:s");
                $remitt = $this->Remittances->newEntity();
                $this->Remittances->patchEntity($remitt, $data['Remittance']);
                
                if ($this->Remittances->save($remitt)) {
                    $data['AccountInvestor']['id'] = $accountInvestor[0]->id;
                    $data['AccountInvestor']['user_id'] = $remittance[0]->investor_id;
                    $data['AccountInvestor']['tmp_balance'] = $accountInvestor[0]->tmp_balance + $remittance[0]->amount_sold;
                    $data['AccountInvestor']['modify_dt'] = date("Y-m-d H:i:s");
                    $account = $this->AccountInvestors->newEntity();
                    $this->AccountInvestors->patchEntity($account, $data['AccountInvestor']);
                    $this->AccountInvestors->save($account);

                    // Send email notification
                    // $this->getMailer('User')->send('remittance_verification', [$remittance[0], $recipient[0], $investor[0]]);

                    // Send mobile notification
                    $http = new Client();
                    $response = $http->post('https://api.pushover.net/1/messages.json', [
                        'token'   => 'anx1ivsh6289s1dn8t8cboids6mxoh',
                        'user'    => 'uq6jg1j33et4s6w2paxjzhre31ug2w',
                        'message' => 'La remesa '. $data['Remittance']['id'] . ' ha sido ejecutada por el inversionista y está pendiente por verificación '
                    ]);

                    $session->write('success', "1");
                    $session->write('alert', __('La operación está en proceso'));
                    $this->redirect(
                        array(
                            'controller' => 'Remittance',
                            'action'     => 'index',
                        )
                    );
                }
            } else {
                // $this->request->data['Remittance'] = $user_detail[0];
            }
        }
    }

    public function delete($id)
    {
        $session = $this->request->session();
        if (is_numeric(base64_decode($id))) {
            $remittance_detail = $this->Remittances->find(
                'all', [
                    'conditions' => array('id'=>base64_decode($id))
                ]
            )->toArray();
            $remittance = $this->Remittances->newEntity();
            $remittance->id = base64_decode($id);
            $remittance->delete_status = 1;
            if ($this->Remittances->save($remittance)) {          
                $session->write('success', "1");
                $session->write('alert', __('Remesa eliminada correctamente'));
                $this->redirect(
                    array(
                        'controller' => 'remittance',
                        'action'     => 'index'
                    )
                ); 
            }
        }
    }

    public function invoice($id)
    {
        $this->autoRender = false;

        // Initialize variablEs
        $contentTi = '';
        $contentMv = '';

        // Get data from invoices table
        $data = $this->Invoices->get($id);

        // If there is data to write to the invoice files
        if (!empty($data)) {

            // Generate document number
            $documento = str_pad($data['id_remesa'], 7, "0", STR_PAD_LEFT);

            // Generate file contents
            $contentTi =
                'FACTI' . $documento . "\t" .
                $data['nombre'] . "\t" .
                $data['cedula'] . "\t" .
                $data['direccion'] . "\t" .
                number_format($data['descuento'], 2, '.', '') . "\t" .
                number_format($data['total_pagos'], 2, '.', '') . "\t" .
                number_format($data['total_final'], 2, '.', '') . "\t" .
                number_format($data['recargos'], 2, '.', '') . "\t" .
                number_format($data['porcentaje_recargo'], 2, '.', '') . "\t" .
                number_format($data['efectivo'], 2, '.', '') . "\t" .
                number_format($data['cheque'], 2, '.', '') . "\t" .
                number_format($data['tarjeta_credito'], 2, '.', '') . "\t" .
                number_format($data['tarjeta_debito'], 2, '.', '') . "\t" .
                number_format($data['nota_credito'], 2, '.', '') . "\t" .
                number_format($data['otro_pago'], 2, '.', '');
            $contentMv =
                'FACTI' . $documento . "\t" .
                $data['codigo'] . "\t" .
                $data['nombre_articulo'] . "\t" .
                $data['unidad'] . "\t" .
                $data['cantidad'] . "\t" .
                number_format($data['precio_neto'], 2, '.', '') . "\t" .
                number_format($data['alicuota'], 2, '.', '') . "\t" .
                $data['agrupado'] . "\t" .
                number_format($data['isc'], 2, '.', '');
        }
        // Generate invoice header file
        $path = ROOT . '/invoices/IN/';
        $fileNameTi = 'FACTI' . $documento . '.TXT';
        $newFile = $path . $fileNameTi;
        file_put_contents($newFile, $contentTi);
        header('Content-type: text/plain');

        // Generate invoice movement file
        $path = ROOT . '/invoices/IN/';
        $fileNameMv = 'FACMV' . $documento . '.TXT';
        $newFile = $path . $fileNameMv;
        file_put_contents($newFile, $contentMv);
        header('Content-type: text/plain');

        return;
    }  
}