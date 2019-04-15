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
 * AppSetting Controller
 *
 * Manage mobile application settings such as Slideshow and Barcodes
 *
 */
class AppSettingController extends AppController
{
    var $uses = array(
        'Slideshow',
        'Operator',
        'Product'
    );
    
    /*
     * ¿Qué hace esta función?
     */
    public function initialize()
    {
        $session = $this->request->session();
        if ($session->read('user_id')=='') {
            $this->redirect(
                array(
                    'controller' => 'Cpanel',
                    'action'     => 'index'
                )
            );
        }
        $this->loadModel('CprSlideshows');
        $this->loadModel('CprOperators');
        $this->loadModel('CprProducts');
        $this->viewBuilder()->layout('admin_layout');
        $this->set('URL', Configure::read('Server.URL')
        );
    }

    /*
     * Add a new image to Slideshow
     */
    public function addSlideshowImg()
    {
        $data = $this->request->data;
        $session = $this->request->session();
        if (!empty($data)) {
            $img = $data['Slideshow']['file'];
            if ($img['name'] != '' && $img['error'] == 0) {
                $name = $img['name'];
                $name_arr = explode(".", $name);
                $ext = $name_arr[count($name_arr) - 1];
                if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg') {
                    if ($img['size'] <= 5242880) {
                        unset($name_arr[count($name_arr) - 1]);
                        $name_pre = implode(".", $name_arr);
                        $dest = WWW_ROOT . UPLOAD_SLIDESHOW_PATH . $img['name'];
                        $src = $img['tmp_name'];
                        if (move_uploaded_file($src, $dest)) {
                            $item['Slideshow']['image_path'] = UPLOAD_SLIDESHOW_PATH . $img['name'];
                            $item['Slideshow']['time'] = $data['Slideshow']['time'];
                            $item['Slideshow']['status'] = $data['Slideshow']['status'];
                            $user = $this->CprSlideshows->newEntity();
                            $this->CprSlideshows->patchEntity($user, $item['Slideshow']);
                            if ($this->CprSlideshows->save($user)) {
                                $session->write('success', "1");
                                $session->write('alert', __('Imagen guardada correctamente'));
                                $this->redirect($this->referer());
                            }
                        }
                    } else {
                        $session->write('success', "0");
                        $session->write('alert', __('El tamaño máximo de la imagen es de 5mb'));
                        $this->render();
                    }
                } else {
                    $session->write('success', "0");
                    $session->write('alert', __('Solo se admiten archivos JPG, JPEG y PNG'));
                    $this->render();
                }
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Ha ocurrido un error al cargar la imagen'));
                $this->render();
            }
        }
    }

    /*
     * View a Slideshow image
     */
    public function viewSlideshowImg()
    {
        $images = $this->CprSlideshows->find(
            'all', [
                'conditions' => array('delete_status !=' => '1')
            ]
        )->toArray();
        $this->set('images', $images);
    }

    /*
     * Edit a Slideshow image
     */
    public function editSlideshowImg($id)
    {
        $session = $this->request->session();
        if (is_numeric(base64_decode($id))) {
            if (!empty($this->request->data)) {
                $data = $this->request->data;
                $data['Slideshow']['id'] = base64_decode($id);
                $user = $this->CprSlideshows->newEntity();
                $this->CprSlideshows->patchEntity($user, $data['Slideshow']);
                if ($this->CprSlideshows->save($user)) {
                    $session->write('success', "1");
                    $session->write('alert', __('La imagen ha sido cargada correctamente'));
                    $this->redirect(
                        array(
                            'controller' => 'AppSetting',
                            'action'     => 'view_slideshow_img'
                        )
                    );
                }
            } else {
                $slideShow = $this->CprSlideshows->findById(base64_decode($id))->toArray();
                $this->request->data['Slideshow'] = $slideShow[0];
            }
        }
    }

    /*
     * Delete a Slideshow image
     */
    public function deleteSlideshowImg($id)
    {
        $session = $this->request->session();
        $this->autoRender=false;
        $data['Slideshow']['id'] = base64_decode($id);
        $data['Slideshow']['delete_status'] = 1;
        if (is_numeric(base64_decode($id))) {
            $user = $this->CprSlideshows->newEntity();
            $this->CprSlideshows->patchEntity($user, $data['Slideshow']);
            if ($this->CprSlideshows->save($user)) {
                $session->write('success', "1");
                $session->write('alert', __('Imagen eliminada correctamente'));
                $this->redirect(
                    array(
                        'controller' => 'AppSetting',
                        'action'     => 'view_slideshow_img'
                    )
                );
            }
        }
    }

    /*
     * Add a new Product
     */
    public function addProduct()
    {
        $session = $this->request->session();
        $data = $this->request->data;
        $operators = $this->CprOperators->find(
            'list', [
                'conditions' => array('status' => '1'),
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('operators', $operators);
        if(!empty($data)) {
            $img = $data['Product']['file'];
            if ($img['name'] != '' && $img['error'] == 0) {
                $name = $img['name'];
                $name_arr = explode(".", $name);
                $ext = $name_arr[count($name_arr) - 1];
                if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg') {
                    if ($img['size'] <= 5242880) {
                        unset($name_arr[count($name_arr) - 1]);
                        $name_pre = implode(".", $name_arr);
                        $dest = WWW_ROOT . UPLOAD_BARCODE_PATH . $img['name'];
                        $src = $img['tmp_name'];
                        if (move_uploaded_file($src, $dest)) {
                            $item['Product']['barcode_image'] = UPLOAD_BARCODE_PATH . $img['name'];
                            $item['Product']['amount'] = $data['Product']['amount'];
                            $item['Product']['operator_id'] = $data['Product']['operator_id'];
                            $item['Product']['barcode_no'] = $data['Product']['barcode_no'];
                            $item['Product']['status'] = 1;
                            $user = $this->CprProducts->newEntity();
                            $this->CprProducts->patchEntity($user, $item['Product']);
                            if ($this->CprProducts->save($user)) {
                                $session->write('success', "1");
                                $session->write('alert', __('Producto guardado correctamente'));
                                $this->redirect($this->referer());
                            } 
                        }
                    } else {
                        $session->write('success', "0");
                        $session->write('alert', __('El tamaño máximo de la imagen es de 5mb'));
                        $this->render();
                    }
                } else {
                    $session->write('success', "0");
                    $session->write('alert',  __('Solo se admiten archivos JPG, JPEG y PNG'));
                    $this->render();
                }
            } else {
                $session->write('success', "0");
                $session->write('alert', __('Ha ocurrido un error durante la creación del producto'));
                $this->render();
            }
        }
    }

    /*
     * View a Product
     */
    public function viewProducts()
    {
        $session = $this->request->session();
        $operators = $this->CprOperators->find(
            'list', [
                'conditions' => array('status' => '1'),
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $products = $this->CprProducts->find('all',  ['order' => 'operator_id'])->toArray();
        $i = 0;
        foreach ($products as $product) {
            $products[$i]->operator_id = $operators[$product->operator_id];
            $i++;
        }
        $this->set('products', $products);
    }

    /*
     * Edit a Product
     */
    public function editProducts($id)
    {
        $session = $this->request->session();
        $operators = $this->CprOperators->find(
            'list', [
                'conditions' =>array('status' => '1'),
                'keyField'   => 'id',
                'valueField' => 'name'
            ]
        )->toArray();
        $this->set('operators',$operators);
        if (is_numeric(base64_decode($id))) {
            if (!empty($this->request->data)) {
                $data = $this->request->data;
                $img = @$data['Product']['file'];
                if ($img['name'] != '' && $img['error'] == 0) {
                    $name = $img['name'];
                    $name_arr = explode(".", $name);
                    $ext = $name_arr[count($name_arr) - 1];
                    if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg') {
                        if($img['size'] <= 5242880) {
                            unset($name_arr[count($name_arr) - 1]);
                            $name_pre = implode(".", $name_arr);
                            $dest = WWW_ROOT . UPLOAD_BARCODE_PATH . $img['name'];
                            $src = $img['tmp_name'];
                            if (move_uploaded_file($src, $dest)) {
                                $item['Product']['id'] = base64_decode($id);
                                $item['Product']['barcode_image'] = UPLOAD_BARCODE_PATH . $img['name'];
                                $item['Product']['amount'] = $data['Product']['amount'];
                                $item['Product']['barcode_no'] = $data['Product']['barcode_no'];
                                $item['Product']['operator_id'] = $data['Product']['operator_id'];
                                $item['Product']['status'] = 0;
                                $user = $this->CprProducts->newEntity();
                                $this->CprProducts->patchEntity($user,  $item['Product']);
                                if ($this->CprProducts->save($user)) {
                                    $session->write('success',"1");
                                    $session->write('alert', __('Producto actualizado correctamente'));
                                    $this->redirect(
                                        array(
                                            'controller' => 'AppSetting',
                                            'action'     => 'view_products'
                                        )
                                    );
                                }
                            }
                        } else {
                            $session->write('success', "0");
                            $session->write('alert', __('El tamaño máximo de la imagen es de 5mb'));
                            $this->render();
                        }
                    } else {
                    $session->write('success', "0");
                    $session->write('alert', __('Solo se admiten archivos JPG, JPEG y PNG'));
                    $this->render();
                    }
                } else {
                    unset($data['Product']['file']);
                    $data['Product']['id'] = base64_decode($id);
                    $user = $this->CprProducts->newEntity();
                    $this->CprProducts->patchEntity($user,  $data['Product']);
                    if ($this->CprProducts->save($user)) {
                        $session->write('success', "1");
                        $session->write('alert', __('Producto actualizado correctamente'));
                        $this->redirect($this->referer());
                    } 
                }
            } else {
                $product = $this->CprProducts->findById(base64_decode($id))->toArray();
                $this->request->data['Product'] = $product[0];
            }
        }    
    }

    /*
     * Delete a Product
     */
    public function deleteProducts($id)
    {
        $session = $this->request->session();
        $this->autoRender=false;
        if (is_numeric(base64_decode($id))) {
            if ($this->CprProducts->deleteAll(['id' => base64_decode($id)])) {
                $session->write('success', "1");
                $session->write('alert', __('Producto eliminado correctamente'));
                $this->redirect(
                    array(
                        'controller' => 'AppSetting',
                        'action'     => 'view_products'
                    )
                );
            }
        }
    }
}
