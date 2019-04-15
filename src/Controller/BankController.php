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
 * Settings Controller
 *
 * Handles system settings
 *
 */
class BankController extends AppController
{
    var $uses = array(
        'Bank',
        'Country',
        'User'
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
        $this->loadModel('CprUsers');
        $this->loadModel('CprBanks');
        $this->loadModel('CprCountries');
        $this->loadModel('CprBankAccountTypes');
        $this->viewBuilder()->layout('admin_layout');
        $this->set('URL', Configure::read('Server.URL'));
    }

    /*
     * View Banks
     */
    public function index()
    {
        $banks = $this->CprBanks->find('all')->toArray();
        $countries = $this->CprCountries->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $i = 0;
        foreach ($banks As $bank) {
            $banks[$i]['country'] = $countries[$bank['country_id']];
            $i++;
        }
        $this->set('banks', $banks);
    }

    function add()
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $data = $this->request->data;
        $countries = $this->CprCountries->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('countries',$countries);
        if (!empty($data)) {
            $data['Bank']['delete_status']=0;
            $bank = $this->CprBanks->newEntity();
            $this->CprBanks->patchEntity($bank, $data['Bank']);
            if ($this->CprBanks->save($bank)) {
                $session->write('success', "1");
                $session->write('alert', __('Banco agregado correctamente'));
                $this->redirect(
                    array(
                        'controller' => 'bank',
                        'action'     => 'index'
                        )
                );
            }
        }
    }

    public function edit($id)
    {
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $bank_detail = $this->CprBanks->find(
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
        $this->set('countries',$countries);
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
                $data['Bank']['id'] = base64_decode($id);
                $bank = $this->CprBanks->newEntity();
                $this->CprBanks->patchEntity($bank, $data['Bank']);
                if ($this->CprBanks->save($bank)) {
                    $session->write('success', "1");
                    $session->write('alert', __('Banco actualizado correctamente'));
                    $this->redirect(
                        array(
                            'controller' => 'bank',
                            'action'     => 'index'
                        )
                    );
                }
            } else {
                $this->request->data['Bank'] = $bank_detail[0];
            }
        }
    }
}
