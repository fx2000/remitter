<?php 
    $user_type = $this->request->session()->read('user_type');
    if ($this->request->session()->read('alert') != '') {
?>

<div class="alert <?php echo ($this->request->session()->read('success') == 1) ? 'alert-success':'alert-danger'; ?>">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>
        <?php 
            echo $this->request->session()->read('alert');
            $_SESSION['alert'] = '';
        ?>
    </strong>
</div>

<?php
    }
?>

<div class="breadcrumb">
    <?php
        echo $this->Html->link(
            __('Inicio / '),
            array(
                'controller' => 'cpanel',
                'action'     => 'home'
            )
        );
    ?>
    <?php
        echo $this->Html->link(
            'Remesas / ',
            array(
                'controller' => 'remittance',
                'action'     => 'index'
            )
        );
    ?>
    <?php
        echo $this->Html->link(
            __('Actualizar Remesa'),
            array(
                'controller' => 'remittance',
                'action'     => 'edit',
                $this->request->params['pass'][0]
            )
        );
    ?>
</div>
<div class="box-header well" data-original-title>
    <h4><i class="fas fa-paper-plane"></i><?php echo __(' Ver Remesa'); ?></h4>
</div>

<div class="row-fluid ">
    <div class="box well span12">
        <div class="box-header well" data-original-title>
            <div class="row">
                <div class="form-inline col-sm-6 py-2" style="margin-top: 40px;">
                    <div class="card card-user col-md-8">
                        <div class="card-body">
                            <div class="author">
                                <div class="content">
                                    <table class="table" align="center">
                                        <td style="text-align: center;">
                                            <?php
                                                echo $this->Html->image(
                                                    'logobw.jpg', [
                                                        'alt'   => __('remitter'),
                                                        'class' => 'avatar border-gray',
                                                        'style' => 'border-radius: 0px; border: 0px; height: 75px; margin-top:60px;'
                                                    ]
                                                );
                                            ?>
                                        </td>
                                    </table>
                                    <table class="table">
                                        <?php 
                                            foreach($remittance AS $re) {
                                                $st = $re->status;
                                                $id = $re->id;
                                                echo ('<tr><td align="right"><b>' . __('Número de Operación') . '</b></td><td>' . str_pad($re->id, 6, '0', STR_PAD_LEFT) . '</td></tr>');
                                                echo ('<tr><td align="right"><b>' . __('Fecha & Hora Transacción') . '</b></td><td>' . $re->trans_dt . '</td></tr>');
                                                echo ('<tr><td align="right"><b>' . __('Fecha & Hora Reserva') . '</b></td><td>' . $re->reserved_dt . '</td></tr>');
                                                echo ('<tr><td align="right"><b>' . __('Fecha & Hora Aplicación') . '</b></td><td>' . $re->applyed_dt . '</td></tr>');
                                                echo ('<tr><td align="right"><b>' . __('Monto') . '</b></td><td>' . $this->Number->currency($re->amount) . '</td></tr>');
                                                echo ('<tr><td align="right"><b>' . __('Tarifa Pagada') . '</b></td><td>' . $this->Number->currency($re->fee + $re->tax) . '</td></tr>');
                                                if ($re->payment_type == 1) {
                                                    echo ('<tr><td align="right"><b>' . __('Método de Pago') . '</b></td><td>' . 'Efectivo' . '</td></tr>');
                                                } else if ($re->payment_type == 2) {
                                                    echo ('<tr><td align="right"><b>' . __('Método de Pago') . '</b></td><td>' . 'ACH' . '</td></tr>');
                                                } else if ($re->payment_type == 3) {
                                                    echo ('<tr><td align="right"><b>' . __('Método de Pago') . '</b></td><td>' . 'Punto Pago' . '</td></tr>');
                                                } else {
                                                    echo ('<tr><td align="right"><b>' . __('Método de Pago') . '</b></td><td>' . 'Otro' . '</td></tr>');
                                                }
                                                echo ('<tr><td align="right"><b>' . __('Tasa de Compra') . '</b></td><td>' . 'BsS.' . number_format($re->purchase_rate, 2, '.', ',') . '</td></tr>');
                                                echo ('<tr><td align="right"><b>' . __('Tasa de Venta') . '</b></td><td>' . 'BsS.' . number_format($re->sale_rate, 2, '.', ',') . '</td></tr>');
                                                echo ('<tr><td align="right"><b>' . __('Monto Transferido') . '</b></td><td>' . 'BsS.' . number_format($re->amount_delivered, 2, '.', ',') . '</td></tr>');
                                                echo ('<tr><td align="right"><b>' . __('Operador') . '</b></td><td>'); foreach($operator_info as $o){ echo $o->fname1 . ' ' . $o->lname1; } echo('</td></tr>');
                                                if ($re->payment_type == 3) {
                                                    echo ('<tr><td align="right"><b>' . __('Sucursal') . '</b></td><td>' . 'Punto Pago' . '</td></tr>');
                                                } else {
                                                    echo ('<tr><td align="right"><b>' . __('Sucursal') . '</b></td><td>' . 'Principal' . '</td></tr>');
                                                }
                                                
                                            } 
                                            foreach($client_info AS $c) {
                                                echo ('<tr><td align="right"><b>' . __('Remitente') . '</b></td><td>' . $c->fname1 . ' ' . $c->lname1 . '</td></tr>');
                                                if ($c->tax_id != '') {
                                                    echo ('<tr><td align="right"><b>' . __('Cédula') . '</b></td><td>' . $c->tax_id . '</td></tr>');
                                                } else {
                                                    echo ('<tr><td align="right"><b>' . __('Pasaporte') . '</b></td><td>' . $c->passport . '</td></tr>');
                                                }
                                                echo ('<tr><td align="right"><b>' . __('País de Origen') . '</b></td><td>' . "Panamá" . '</td></tr>');
                                            }
                                            foreach($recipient_info AS $r) {
                                                echo ('<tr><td align="right"><b>' . __('Beneficiario') . '</b></td><td>' . $r->fname1 . ' ' . $r->lname1 . '</td></tr>');
                                                if (isset($r->tax_id)) {
                                                    echo ('<tr><td align="right"><b>' . __('Cédula') . '</b></td><td>' . $r->tax_id . '</td></tr>');
                                                } else {
                                                    echo ('<tr><td align="right"><b>' . __('Pasaporte') . '</b></td><td>' . $r->passport . '</td></tr>');
                                                }
                                                echo ('<tr><td align="right"><b>' . __('País Destino') . '</b></td><td>' . "Venezuela" . '</td></tr>');
                                                echo ('<tr><td align="right"><b>' . __('Número de Cuenta') . '</b></td><td>' . $r->bank_account_number . '</td></tr>');
                                                if ($r->bank_account_type == 1) {
                                                    $type = 'Ahorros';
                                                } else if ($r->bank_account_type == 2) {
                                                    $type = 'Corriente';
                                                } else{
                                                    $type = 'Otro';
                                                }
                                                echo ('<tr><td align="right"><b>' . __('Tipo de Cuenta') . '</b></td><td>' . $type . '</td></tr>');
                                            }
                                            foreach($bank_info AS $b) {
                                                echo ('<tr><td align="right"><b>' . __('Banco') . '</b></td><td>' . $b->name . '</td></tr>');
                                            }
                                        ?>
                                    </table>
                                    <?php
                                        /* Status:
                                            1:Disponible, 2:Reservada, 3:En Verificación, 4:Completada, 5:Cancelada
                                        */
                                        if ($st == 2){
                                            $status = [2 => __('Reservada'), 5 => __('Cancelada')];
                                        } elseif ($st == 2) {
                                            $status = [1 => __('Disponible'), 2 => __('Reservada'), 5 => __('Cancelada')];
                                        } elseif ($st == 3) {
                                            $status = [1 => __('Disponible'), 3 => __('En Verificación'), 4 => __('Completada'), 5 => __('Cancelada')];
                                        } else {
                                            $status = [];
                                        }
                                        if ($st == 2 || $st == 3) {
                                            echo $this->Form->create();
                                            echo $this->Form->input(
                                                'Remittance.status',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $status,
                                                    'placeholder' => __('Estado'),
                                                    'id'          => 'status',
                                                    'label'       => __('Estado'),
                                                    'onchange'    => 'ShowField()'
                                                )
                                            );
                                            echo ('<br>');
                                            /*echo $this->Form->input(
                                                'Remittance.descriptions',
                                                array(
                                                    'type'        => 'textarea',
                                                    'class'       => 'form-control',
                                                    'placeholder' => __('Escriba aquí...'),
                                                    'label'       => __('Comentarios'),
                                                    'escape' => false
                                                )
                                            );*/
                                        }
                                    ?>
                                </div>
                                <hr>
                                <div class="card-footer">
                                    <?php 
                                        if ($st == 2 || $st == 3) {
                                            echo $this->Html->link(
                                                __('Volver'),
                                                array(
                                                    'controller' => 'remittance',
                                                    'action' => 'index'
                                                ),
                                                array(
                                                    'class' => 'btn btn-primary'
                                                )
                                            );
                                            echo (' ');
                                            echo $this->Form->Submit(
                                                __('Guardar'),
                                                array(
                                                    'class' => 'btn btn-primary'
                                                )
                                            );
                                        } else {
                                            echo $this->Html->link(
                                                __('Volver'),
                                                array(
                                                    'controller' => 'remittance',
                                                    'action' => 'index'
                                                ),
                                                array(
                                                    'class' => 'btn btn-primary'
                                                )
                                            );
                                        }
                                        echo $this->Html->link(
                                            __('Reimprimir'),
                                            array(
                                                'controller' => 'remittance',
                                                'action' => 'print', $id
                                            ),
                                            array(
                                                'class' => 'btn btn-primary',
                                                'style' => 'margin-left:10px'
                                            )
                                        );
                                    ?>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 py-2" style="margin-top: 40px;">
                    <div class="row">
                        <div class="card card-user col-md-8">
                            <div class='card-header'>
                                <h5 class='card-title'>Comprobante ACH en Panamá</h5>
                            </div>
                            <div class="card-body ">
                                <div class="author" style="margin-top: 40px;">
                                    <?php 
                                        foreach ($remittance as $remitt):
                                            if ($remitt->ach_dir != null || $remitt->ach != null) {
                                                $imgUrl = "\"" . '/webroot/img/remittances/ach/' . $remitt->ach_dir . '/' . $remitt->ach . "\"";

                                                if (strpos($remitt->ach, '.pdf') !== false) {
                                                    $thumb = "\"" . '/webroot/img/pdficon.png' . "\"";
                                                } else {
                                                    $thumb = $imgUrl;
                                                }
                                    ?>
                                                <a href=<?php echo $imgUrl; ?>
                                                    onclick="return !window.open(this.href, 'width=800,height=800')"
                                                    target="_blank"
                                                >
                                                <img src=<?php echo $thumb; ?> width="150"/>
                                                </a>
                                    <?php
                                            } else {
                                                $thumb = '/webroot/img/id_card_placeholder.png';
                                    ?>
                                                <a href=<?php echo $thumb; ?>
                                                    onclick="return !window.open(this.href, 'width=800,height=800')"
                                                    target="_blank"
                                                >
                                                <img src=<?php echo $thumb; ?> width="250"/>
                                                </a>
                                    <?php
                                            }
                                        endforeach;
                                    ?>
                                    <p class="card-description"></p>
                                </div>
                                <p class="card-description text-center">
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card card-user col-md-8">
                            <div class='card-header'>
                                <h5 class='card-title'>Comprobante de Transferencia en Bolívares</h5>
                            </div>
                            <div class="card-body ">
                                <div class="author" style="margin-top: 40px;">
                                    <?php 
                                        foreach ($remittance as $remitt):
                                            if ($remitt->photo_dir != null || $remitt->photo != null) {
                                                $imgUrl = "\"" . '/webroot/img/remittances/photo/' . $remitt->photo_dir . '/' . $remitt->photo . "\"";

                                                if (strpos($remitt->photo, '.pdf') !== false) {
                                                    $thumb = "\"" . '/webroot/img/pdficon.png' . "\"";
                                                } else {
                                                    $thumb = $imgUrl;
                                                }
                                    ?>
                                                <a href=<?php echo $imgUrl; ?>
                                                    onclick="return !window.open(this.href, 'width=800,height=800')"
                                                    target="_blank"
                                                >
                                                <img src=<?php echo $thumb; ?> width="150"/>
                                                </a>
                                    <?php
                                            } else {
                                                $thumb = '/webroot/img/id_card_placeholder.png';
                                    ?>
                                                <a href=<?php echo $thumb; ?>
                                                    onclick="return !window.open(this.href, 'width=800,height=800')"
                                                    target="_blank"
                                                >
                                                <img src=<?php echo $thumb; ?> width="250"/>
                                                </a>
                                    <?php
                                            }
                                        endforeach;
                                    ?>
                                    <p class="card-description"></p>
                                </div>
                                <p class="card-description text-center">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
