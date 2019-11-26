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

/**
 * Recipient Controller
 *
 * Handles users
 *
 */
class RecipientController extends AppController
{
    var $uses = array(
        'User',
        'UserType',
        'BankAccountType',
        'Countries',
        'Recipient'
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
        $this->loadModel('CprRecipients');
        $this->loadModel('CprBanks');
        $this->loadModel('CprUsers');
        $this->loadModel('CprUserTypes');
        $this->loadModel('CprCountries');
        $this->loadModel('CprBankAccountTypes');
        $this->viewBuilder()->layout('admin_layout');
        $this->set('URL', Configure::read('Server.URL'));
    }

    public function index($id)
    {
        $this->set("user_id",$id);
        $recipients = $this->CprRecipients->find(
            'all', [
                'conditions' => array(
                    'delete_status' => 0,
                    'client_id' => base64_decode($id)
                ),
                'keyField'   => 'client_id',
                'valueField' => 'lname'
            ]
        )->toArray();
        $countries = $this->CprCountries->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
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
        $i = 0;
        foreach ($recipients As $recipient) {
            $recipients[$i]['country'] = $countries[$recipient['country_id']];
            $i++;
        }
        $i = 0;
        foreach ($recipients As $recipient) {
            $recipients[$i]['bank'] = $banks[$recipient['bank_id']];
            $i++;
        }
        $i = 0;
        foreach ($recipients As $recipient) {
            $recipients[$i]['bank_account_type'] = $account_types[$recipient['bank_account_type']];
            $i++;
        }
        $this->set("recipients",$recipients);
    }

    /*
     * Add a new recipient
     */
    function add($client_id)
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $data = $this->request->data;

        $countries = $this->CprCountries->find(
            'list',[
                'conditions' => array(
                    'id' => 232, //Venezuela
                ),
                'keyField'   => 'id',
                'valueField' => 'name',
            ]
        )->toArray();
        $this->set('countries',$countries);

        $banks = $this->CprBanks->find(
            'list',[
                'conditions' => array(
                    'country_id' => 232, //Venezuela
                    'status' => 1,
                    'delete_status' => 0
                ),
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('banks',$banks);

        if (!empty($data)) {
            //$exstusernamr = $this->CprRecipients->find(
            //    'all', [
            //        'conditions' => array('bank_account_number' => $data['Recipient']['bank_account_number'])
            //    ]
            //)->toArray();
            $data['Recipient']['client_id']=base64_decode($client_id);
            $recipient = $this->CprRecipients->newEntity();
            $this->CprRecipients->patchEntity($recipient, $data['Recipient']);
            if ($this->CprRecipients->save($recipient)) {
                $session->write('success', "1");
                $session->write('alert', __('Beneficiario agregado correctamente'));
                $this->redirect(
                    array(
                        'controller' => 'Recipient',
                        'action'     => 'index',$client_id
                        )
                );
            }
        }
    }

    public function edit($id)
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $recipient_detail = $this->CprRecipients->find(
            'all', [
                'conditions' => array('id'=>base64_decode($id))
            ]
        )->toArray();
        $countries = $this->CprCountries->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('countries', $countries);
        $banks = $this->CprBanks->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('banks', $banks);
        $account_types = $this->CprBankAccountTypes->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('account_types', $account_types);
        if (is_numeric(base64_decode($id))) {
            if (!empty($this->request->data)) {
                $data = $this->request->data;
                $data['Recipient']['id'] = base64_decode($id);
                $recipient = $this->CprRecipients->newEntity();
                $this->CprRecipients->patchEntity($recipient, $data['Recipient']);
                if ($this->CprRecipients->save($recipient)) {
                    $session->write('success', "1");
                    $session->write('alert', __('Beneficiario actualizado correctamente'));
                    $this->redirect(
                        array(
                            'controller' => 'recipient',
                            'action'     => 'index',$id
                        )
                    );
                }
            } else {
                $this->request->data['Recipient'] = $recipient_detail[0];
            }
        }
    }

    public function delete($id)
    {
        $session = $this->request->session();
        if (is_numeric(base64_decode($id))) {
            $recipient_detail = $this->CprRecipients->find(
                'all', [
                    'conditions' => array('id'=>base64_decode($id))
                ]
            )->toArray();
            $recipient = $this->CprRecipients->newEntity();
            $recipient->id = base64_decode($id);
            $recipient->delete_status = 1;
            if ($this->CprRecipients->save($recipient)) {          
                $session->write('success', "1");
                $session->write('alert', __('Beneficiario eliminado correctamente'));
                $this->redirect(
                    array(
                        'controller' => 'recipient',
                        'action'     => 'index',base64_encode($recipient_detail[0]['client_id'])
                    )
                ); 
            }
        }
    }
}