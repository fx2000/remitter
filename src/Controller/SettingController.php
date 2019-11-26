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
 * Settings Controller
 *
 * Handles system settings
 *
 */
class SettingController extends AppController
{
    var $uses = array(
        'Country',
        'Setting'
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
        $this->loadModel('CprCountries');
        $this->loadModel('CprSettings');
        $this->viewBuilder()->layout('admin_layout');
        $this->set('URL',Configure::read('Server.URL'));
    }

    /*
     * View Taxes and Rates
     */
    public function index()
    {
        $settings = $this->CprSettings->find('all')->toArray();
        $countries = $this->CprCountries->find(
            'list',[
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $i = 0;
        foreach ($settings As $setting) {
            $settings[$i]['country'] = $countries[$setting['country_id']];
            $i++;
        }
        $this->set('settings', $settings);
    }

    /*
     * Edit Settings
     */
    function edit($id)
    { 
        $session = $this->request->session();
        $user_type = $session->read('user_type');
        $setting_detail = $this->CprSettings->find(
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
        if (is_numeric(base64_decode($id))) {
            if (!empty($this->request->data)) {
                $data = $this->request->data;
                $data['Setting']['id'] = base64_decode($id);
                $setting = $this->CprSettings->newEntity();
                $this->CprSettings->patchEntity($setting, $data['Setting']);
                if ($this->CprSettings->save($setting)) {
                    $session->write('success', "1");
                    $session->write('alert', __('Configuración actualizada correctamente'));
                    $this->redirect(
                        array(
                            'controller' => 'Setting',
                            'action'     => 'index',
                        )
                    );
                }
            } else {
                $this->request->data['Setting'] = $setting_detail[0];
            }
        }
    }

    /*
     * ¿Qué hace esta función?
     */
    public function operator() {
        $this->request->data = $this->CprOperators->find('all');
        
    }

    /*
     * ¿Qué hace esta función?
     */
    public function operatorChange($id, $status)
    {
        $session = $this->request->session();
        $operator['id'] = base64_decode($id);
        $operator['status'] = $status;
        $user = $this->CprOperators->newEntity();
        $this->CprOperators->patchEntity($user, $operator);
        if ($this->CprOperators->save($user)) {
            $session->write('success', "1");
            $session->write('alert', __('Estatus de operadora actualizado correctamente'));
        } else {
            $session->write('success', "0");
            $session->write('alert', __('La operadora no pudo ser modificada'));
        }
        $this->redirect(
            array(
                'controller' => 'setting',
                'action'     => 'operator'
            )
        );
    }

    /*
     * ¿Qué hace esta función?
     */     
    public function viewPlatform()
    {
        $operators_credentials = $this->CprOperators->find('all')
            ->hydrate(false)
            ->join([
                'table'      => 'cpr_operator_credentials',
                'alias'      => 'OperatorCredential',
                'type'       => 'LEFT',
                'conditions' => 'OperatorCredential.operator_id = CprOperators.id',
            ])
            ->select([
                'OperatorCredential.product_id',
                'OperatorCredential.ip_address',
                'OperatorCredential.port',
                'OperatorCredential.username',
                'CprOperators.id',
                'CprOperators.name'
            ])
            ->toArray();
        $this->set('operators_credentials', $operators_credentials);
    }

    /*
     * Add a new bank
     */
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
            $exbank = $this->CprUsers->find(
                'all', [
                    'conditions' => array('name' => $data['Bank']['name'], 'country_id' => $data['Bank']['country_id'])
                ]
            )->toArray();
            if (count($exbank) >= 1) {
                $session->write('success', "0");
                $session->write('alert', __('El banco ya existe'));
                $this->render();
            } else {
                $user = $this->CprUsers->newEntity();
                $this->CprUsers->patchEntity($user, $data['User']);
                if ($this->CprUsers->save($user)) {
                    $session->write('success', "1");
                    $session->write('alert', __('Usuario agregado correctamente'));
                    $this->redirect(
                        array(
                            'controller' => 'user',
                            'action'     => 'add'
                            )
                    );
                }
            }
        }
    }

    

    

    /*
     * ¿Qué hace esta función?
     */
    public function editPlatform($id)
    {
        $session = $this->request->session();
        if (is_numeric(base64_decode($id))) {
            if (!empty($this->request->data)) {
                $data = $this->request->data['OperatorCredential'];
                $exstoperator = $this->CprOperatorCredentials->find(
                    'all', [
                        'conditions' => array('operator_id' => $data['operator_id'])
                    ]
                )->toArray();
                if (empty($exstoperator)) {
                    $user = $this->CprOperatorCredentials->newEntity();
                    $this->CprOperatorCredentials->patchEntity($user, $data);
                    if ($this->CprOperatorCredentials->save($user)) {
                        $session->write('success', "1");
                        $session->write('alert', __('Platafrma actualizada correctamente'));
                        $this->redirect(
                            array(
                                'controller' => 'setting',
                                'action'     => 'view_platform'
                            )
                        );
                    } else {
                        $session->write('success',"0");
                        $session->write('alert', __('Ha ocurrido un error al actualizar la plataforma'));
                        $this->redirect($this->referer());
                    }
                } else {
                    $updateData['ip_address'] = "'" . $data['ip_address'] . "'";
                    $updateData['username'] = "'" . $data['username'] . "'";
                    $updateData['port'] = "'" . $data['port'] . "'";
                    $updateData['product_id'] = "'" . $data['product_id'] . "'";
                    $storeUpd = $this->CprOperatorCredentials->query()->update()
                        ->set([
                            'ip_address' => $data['ip_address'],
                            'username'   => $data['username'],
                            'port'       => $data['port'],
                            'product_id' => $data['product_id']
                        ])
                        ->where(['operator_id' => $data['operator_id']])
                        ->execute();
                    if ($storeUpd) {
                        $session->write('success', "1");
                        $session->write('alert', __('Plataforma actualizada correctamente'));
                        $this->redirect(
                            array(
                                'controller' => 'setting',
                                'action'     => 'view_platform'
                            )
                        );
                    } else {
                        $session->write('success', "0");
                        $session->write('alert', __('Ha ocurrido un error al actualizar la plataforma'));
                        $this->redirect($this->referer());
                    }
                }
            } else {
                    $operators_credentials = $this->CprOperators->find('all')
                        ->hydrate(false)
                        ->join([
                            'table'      => 'cpr_operator_credentials',
                            'alias'      => 'OperatorCredential',
                            'type'       => 'LEFT',
                            'conditions' => array(
                                'OperatorCredential.operator_id = CprOperators.id',
                                'OperatorCredential.operator_id' => base64_decode($id)
                            )
                        ])
                        ->select([
                            'OperatorCredential.product_id',
                            'OperatorCredential.ip_address',
                            'OperatorCredential.port',
                            'OperatorCredential.username',
                            'CprOperators.id',
                            'CprOperators.name'
                        ])
                        ->toArray();
                    $this->request->data['Operator'] = $operators_credentials[0];
                    $this->request->data['OperatorCredential'] = $operators_credentials[0]['OperatorCredential'];
                    if (empty($this->request->data)) {
                        $opr = $this->CprOperators->findById(base64_decode($id))->toArray();
                        $this->request->data['Operator'] = $opr[0];
                    }
                }
            }
        }

    /*
     * Change password
     */
    public function changePassword($id)
    {
        $session = $this->request->session();
        if (!empty($this->request->data)) {
            $id = base64_decode($id);
            $data = $this->request->data['OperatorCredential'];
            if ($data['password'] == $data['confirm_password']) {
                $data['id'] = $id;
                $storeUpd = $this->CprOperatorCredentials->query()->update()
                    ->set(['password' => $data['password']])
                    ->where(['operator_id' => $id])
                    ->execute();
                if ($storeUpd) {
                    $session->write('success', "1");
                    $session->write('alert', __('Contraseña cambiada correctamente'));
                    $this->redirect(
                        array(
                            'controller' => 'setting',
                            'action'     => 'viewPlatform'
                        )
                    );
                } else {
                    $session->write('success', "0");
                    $session->write('alert', __('Ha ocurrido un error al cambiar la contraseña'));
                    $this->redirect($this->referer());
                }
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Las contraseñas no coinciden'));
            }
        }
    }

    /*
     * ¿Qué hace esta función?
     */
    public function notification()
    {
        $session = $this->request->session();
        if (!empty($this->request->data)) {
            $data = $this->request->data['SmtpSetting'];
            $user = $this->CprSmtpSettings->newEntity();
            $this->CprSmtpSettings->patchEntity($user, $data);
            if ($this->CprSmtpSettings->save($user)) {
                $session->write('success', "1");
                $session->write('alert', __('Detalles de notificación modificados correctamente'));
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Ha ocurrido un error al guardar los detalles de notificación'));
            }
        }
        $smtp = $this->CprSmtpSettings->find('all')->toArray();
        $this->request->data['SmtpSetting'] = $smtp[0];
    }

    /*
     * ¿Qué hace esta función?
     */
    public function reportSetting($id = null)
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
        )->toArray();
        $this->set('retailers' , $retailers);
        if (!empty($this->request->data)) {
            $data = $this->request->data['RetailerReportSetting'];
            $existing = $this->CprRetailerReportSettings->find(
                'all', [
                    'conditions' => array(
                        'retailer_id' => $this->request->data['RetailerReportSetting']['retailer_id']
                    )
                ]
            )->toArray();
            if (!empty($existing)) {
                $data['id'] = $existing[0]['id'];
            }
            $user = $this->CprRetailerReportSettings->newEntity();
            $this->CprRetailerReportSettings->patchEntity($user, $data);
            if ($this->CprRetailerReportSettings->save($user)) {
                $session->write('success', "1");
                $session->write('alert', __('Configuración guardada correctamente'));
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Ha ocurrido un error al guardar la configuración'));
            }
        }
        $seldata = $this->CprRetailerReportSettings->find(
            'all', [
                'conditions' => array('id' => base64_decode($id)
            )]
        )->toArray();
        if (!empty($seldata)) {
            $this->request->data['RetailerReportSetting'] = $seldata[0];
        }
        $settings = $this->CprRetailerReportSettings->find('all')
            ->hydrate(false)
            ->join([
                    'table'      => 'cpr_retailers',
                    'alias'      => 'Retailer',
                    'type'       => 'INNER',
                    'conditions' => array('Retailer.id=CprRetailerReportSettings.retailer_id')
            ])
            ->select([
                'Retailer.name',
                'CprRetailerReportSettings.trans_report',
                'CprRetailerReportSettings.sale_report',
                'CprRetailerReportSettings.retailer_sale_report',
                'CprRetailerReportSettings.store_sale_report',
                'CprRetailerReportSettings.user_sale_report',
                'CprRetailerReportSettings.account_inventory_report',
                'CprRetailerReportSettings.account_movement_report',
                'CprRetailerReportSettings.account_deposit_report',
                'CprRetailerReportSettings.time',
                'CprRetailerReportSettings.id'
            ])
            ->group(['CprRetailerReportSettings.id'])
            ->toArray();
        $this->set('settings',$settings);
    }
        
    /*
     * ¿Qué hace esta función?
     */
    public function retailerDeveloper()
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
        )->toArray();
        $this->set('retailers' , $retailers);
        if (!empty($this->request->data)) {
            $data = $this->request->data['Developer'];
            $existing = $this->CprDevelopers->find(
                'all', [
                    'conditions' => array(
                        'retailer_id' => $this->request->data['Developer']['retailer_id'],
                        'store_id'    => $this->request->data['Developer']['store_id'])
                ]
            )->toArray();
            if (!empty($existing)) {
                $data ['id'] = $existing['Developer']['id'];
            } else {
                $data ['developer_key'] = md5(time());
                $data ['secret_key'] = md5(
                    $this->request->data['Developer']['retailer_id'] . "-" . $this->request->data['Developer']['store_id']
                );
            }
            $user = $this->CprDevelopers->newEntity();
            $this->CprDevelopers->patchEntity($user, $data);
            if ($this->CprDevelopers->save($user)) {
                $this->request->data = array();
                $session->write('success', "1");
                $session->write('alert', __('Detalles del desarrollados actualizados correctamente'));
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Ha ocurrido un error al actualizar los datos del desarrollador'));
            }
        }
        $settings = $this->CprDevelopers->find('all')
            ->hydrate(false)
            ->join([
                    'table'      => 'cpr_retailers',
                    'alias'      => 'Retailer',
                    'type'       => 'INNER',
                    'conditions' => array('Retailer.id=CprDevelopers.retailer_id')
            ])
            ->join([
                    'table'      => 'cpr_stores',
                    'alias'      => 'Store',
                    'type'       => 'INNER',
                    'conditions' => array('Store.id=CprDevelopers.store_id')
            ])
            ->select([
                'Retailer.name',
                'Store.name',
                'CprDevelopers.developer_key',
                'CprDevelopers.secret_key',
                'CprDevelopers.status',
                'CprDevelopers.id'
            ])
            ->group(['CprDevelopers.id'])
            ->toArray();
        $this->set('settings', $settings);
    }

    /*
     * ¿Qué hace esta función?
     */
    public function deleteDeveloper($id) {
        $session = $this->request->session();
        if (is_numeric(base64_decode($id))) {
            $delete['Developer']['id'] = base64_decode($id);
            if ($this->CprDevelopers->deleteAll(['id' => $delete['Developer']['id']])) {
                $session->write('success', "1");
                $session->write('alert', __('Desarrollador eliminado correctamente'));
                
            }
        }
        $this->redirect(array('controller'=>'Setting','action'=>'retailerDeveloper'));
    }

    /*
     * ¿Qué hace esta función?
     */
    public function deleteReportSetting($id)
    {
        $session = $this->request->session();
        if (is_numeric(base64_decode($id))) {
            $delete['id'] = base64_decode($id);
            if ($this->CprRetailerReportSettings->deleteAll(['id' => $delete['id']])) {
                $session->write('success', "1");
                $session->write('alert', __('Correo electrónico eliminado correctamente'));
            }
        }
        $this->redirect(
            array(
                'controller' => 'Setting',
                'action'     => 'report_setting'
            )
        );
    }
}