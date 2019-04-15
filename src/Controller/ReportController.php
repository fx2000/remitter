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
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;
use Cake\Mailer\MailerAwareTrait;
use Cake\Network\Http\Client;

/**
 * Remittance Controller
 *
 * Handles users
 *
 */
class ReportController extends AppController
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
        'AccountInvestor',
        'Payment',
        'BankAccounts'
    );
    
    /*
     * Initialize Controller
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
        $this->loadComponent('Validation');
        $this->loadModel('Remittances');
        $this->loadModel('CprPayments');
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

    /*
     * Z Report (handled by billing server)
     */
    public function zreport()
    {

    }

    /*
     * Query monthly remittances
     */
    public function opMonthQuery()
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $data = $this->request->data;

        if (!empty($data)) {
            $this->redirect(
                array(
                    'controller' => 'report',
                    'action'     => 'opMonth',
                    $data['year'],
                    $data['month']
                )
            );
        }
    }

    /*
     * Monthly remittances
     */
    public function opMonth($year, $month)
    {
        $remittances = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status' => 4,
                    'trans_dt >=' => ($year . '-' . $month . '-01'),
                    'trans_dt <' => ($year . '-' . ($month + 1) . '-01')
                )
            ]
        )->toArray();
        //setlocale(LC_TIME, "es_PA");
        $dates = strftime('%B %Y', strtotime($year . '-' . $month));
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
        $this->set("dates",$dates);
    }

    /*
     * Query monthly payments
     */
    public function payMonthQuery()
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $data = $this->request->data;

        if (!empty($data)) {
            var_dump($data);
            $this->redirect(
                array(
                    'controller' => 'report',
                    'action'     => 'payMonth',
                    $data['year'],
                    $data['month']
                )
            );
        }
    }

    /*
     * Monthly payments
     */
    public function payMonth($year, $month)
    {
        $payments = $this->CprPayments->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'trans_dt >=' => ($year . '-' . $month . '-01'),
                    'trans_dt <' => ($year . '-' . ($month + 1) . '-01')
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
        $dates = strftime('%B %Y', strtotime($year . '-' . $month));
        $this->set("dates",$dates);
        $this->set("payments",$payments);
    }

    /*
     * Query revenue by remittances
     */
    public function revenueQuery()
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $data = $this->request->data;

        if (!empty($data)) {
            $this->redirect(
                array(
                    'controller' => 'report',
                    'action'     => 'revenue',
                    $data['year'],
                    $data['month']
                )
            );
        }
    }

    /*
     * Revenue by remittances
     */
    public function revenue($year, $month)
    {
        $remittances = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status' => 4,
                    'trans_dt >=' => ($year . '-' . $month . '-01'),
                    'trans_dt <' => ($year . '-' . ($month + 1) . '-01')
                )
            ]
        )->toArray();
        //setlocale(LC_TIME, "es_PA");
        $dates = strftime('%B %Y', strtotime($year . '-' . $month));
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
        $this->set("dates",$dates);
    }

        /*
     * Revenue by remittances
     */
    public function saldos()
    {
        $users = $this->Users->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'user_type'     => 4,
                ),
                'keyField'   => 'id',
            ]
        )->toArray();
        $balance = $this->AccountInvestors->find(
            'list', [
                'keyField'   => 'user_id',
                'valueField' => 'balance'
            ]
        )->toArray();
        $balanceTmp = $this->AccountInvestors->find(
            'list', [
                'keyField'   => 'user_id',
                'valueField' => 'tmp_balance'
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
            $users[$i]['balance'] = $balance[$user['id']];
            $users[$i]['balanceTmp'] = $balanceTmp[$user['id']];
            $i++;
        }
        $this->set("users", $users);
    }

        /*
     * Query monthly remittances
     */
    public function opMonthInvestorQuery()
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $data = $this->request->data;

        if (!empty($data)) {
            $this->redirect(
                array(
                    'controller' => 'report',
                    'action'     => 'opMonthInvestor',
                    $data['year'],
                    $data['month']
                )
            );
        }
    }

    /*
     * Monthly remittances by Investor
     */
    public function opMonthInvestor($year, $month)
    {
        $remittances = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status' => 4,
                    'trans_dt >=' => ($year . '-' . $month . '-01'),
                    'trans_dt <' => ($year . '-' . ($month + 1) . '-01')
                )
            ]
        )->toArray();

        $dates = strftime('%B %Y', strtotime($year . '-' . $month));
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
        $this->set("dates",$dates);
    }

    /*
     * Query quarterly remittances
     */
    public function opQuarterQuery()
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $data = $this->request->data;

        if (!empty($data)) {
            $this->redirect(
                array(
                    'controller' => 'report',
                    'action'     => 'opQuarter',
                    $data['year'],
                    $data['quarter']
                )
            );
        }
    }

    /*
     * Quarterly remittances
     */
    public function opQuarter($year, $quarter)
    {

        // Define quarters
        switch ($quarter) {
            case 1:
                $monthStart = 1;
                $monthEnd = 3;
                break;
            case 2:
                $monthStart = 4;
                $monthEnd = 6;
                break;
            case 3:
                $monthStart = 7;
                $monthEnd = 9;
                break;
            case 4:
                $monthStart = 10;
                $monthEnd = 12;
                break;
        }


        $remittances = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'status' => 4,
                    'trans_dt >=' => ($year . '-' . $monthStart . '-01'),
                    'trans_dt <' => ($year . '-' . ($monthEnd + 1) . '-01')
                )
            ]
        )->toArray();
        //setlocale(LC_TIME, "es_PA");
        $quarter = 'Q' . $quarter;
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
        $this->set("quarter",$quarter);
    }

    /*
     * Query daily remittances
     */
    public function opDailyQuery()
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $data = $this->request->data;

        if (!empty($data)) {
            $this->redirect(
                array(
                    'controller' => 'report',
                    'action'     => 'opDaily',
                    $data['year'],
                    $data['month'],
                    $data['day'],
                    $data['id']
                )
            );
        }
    }

    /*
     * Daily remittances
     */
    public function opDaily($year, $month, $day, $id)
    {
        $remittances = $this->Remittances->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'operator_id' => $id,
                    'DATE(trans_dt) >=' => $year . '-' . $month . '-' . $day,
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
        $staff = $this->Users->find(
            'list',[
                'conditions' => array(
                    'id' => $id
                ),
                'keyField'   => 'id',
                'valueField' => function ($row) {
                    return $row['fname1'] . ' ' . $row['lname1'];
                }
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
        $this->set("staff", $staff[$id]);
        $this->set("date", $year . '-' . $month . '-' . $day);
        $this->set("settings", $settings);
        $this->set("remittances",$remittances);
    }

}