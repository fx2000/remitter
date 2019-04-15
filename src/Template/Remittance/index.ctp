<script>
function alertBox() {
    alert(<?php echo __("No es posible actualizar esta remesa"); ?>);
}
</script>

<?php 
    $user_type = $this->request->session()->read('user_type');
    if ($this->request->session()->read('alert') != '') {
?>

<div class="alert <?php echo ($this->request->session()->read('success')==1)?'alert-success':'alert-error'?>">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>
        <?php 
            echo $this->request->session()->read('alert');
            $_SESSION['alert'] = '';
        ?>
    </strong>
</div>

<?php } ?>

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
        if ($user_type == '4') {
            echo $this->Html->link(
                __('Remesas Disponibles'),
                array(
                    'controller' => 'remittance',
                    'action'     => 'index'
                )
            );
        } else {
            echo $this->Html->link(
                __('Remesas'),
                array(
                    'controller' => 'remittance',
                    'action'     => 'index'
                )
            );  
        }
    ?>
</div>

<div>
    <?php 
        if ($user_type == '4') {
            $rate = 'BsS.' . number_format($settings[0]->sale_rate, 2);
            echo ('<div class="pull-right"><h4> ' . __('Tasa del Día') . ': ' . $rate . '</h4></div>');
        } else {
            echo $this->Html->link(
                "<i class='fas fa-plus'></i> " . __('Crear Remesa'), 
                array(
                    'controller' => 'remittance',
                    'action'     => 'add'
                ), 
                array(
                    'class'      => 'btn btn-success btn-simple btn-link pull-right',
                    'rel'        => 'tooltip',
                    'escape'     => false
                )
            );
        }
    ?>
</div>

<div class="row-fluid ">        
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-paper-plane"></i>
                <?php
                    if ($user_type == '4') {
                        echo __(' Remesas Disponibles');
                    } else {
                        echo __(' Remesas');
                    }
                ?>
            </h4>
        </div>
        <?php 
            if ($user_type == '4') { // Vista del Inversionista
                echo(
                    '<div class="box-content">
                        <div>
                            <p>' . $this->request->session()->read('fname1') . ', este es un listado de las remesas que hemos seleccionado para tí, esta lista es actualizada de manera automática basado en las remesas más antíguas pendientes por procesar, continua visitando esta página durante el día para mantenerte al tanto de nuestra disponibilidad.</p>
                            <p>Una vez que hayas conseguido una remesa de tu interés, presiona el botón SOLICITAR y sigue las instrucciones en la pantalla.</p>
                        </div>
                        <table class="table table-striped table-bordered Productdatatable">
                            <thead>
                                <tr>
                                    <th>Id Transacción</th>
                                    <th>Monto a Transferir</th>
                                    <th>Monto a Recibir</th>
                                    <th>Banco</th>
                                    <th>Tipo de Cuenta</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>'
                );
                $i = 0;
                foreach($remittances AS $r) {
                    if ($r->status == 1) {
                        if ($i < 50) {
                            echo(
                                '<tr>
                                    <td>'
                            );
                            echo str_pad($r->id,6,'0',STR_PAD_LEFT);
                            echo (
                                        '</td>
                                        <td>'
                            );
                            echo 'BsS.' . number_format($r->amount_delivered,2);
                            echo(
                                        '</td>
                                        <td>'
                            );
                            echo $this->Number->currency($r->amount_delivered/$settings[0]->sale_rate);
                            echo(
                                        '</td>
                                        <td>'
                            );
                            echo $r->bank;
                            echo(
                                        '</td>
                                        <td>'
                            );
                            echo $r->account;
                            echo(
                                        '</td>
                                        <td>'
                            );
                            echo $this->Html->link(
                                "<i class='fas fa-pencil-alt'></i> " . __('Solicitar'), 
                                array(
                                    'controller' => 'remittance',
                                    'action'     => 'apply',
                                    base64_encode($r->id)
                                ), 
                                array(
                                    'class'  => 'btn btn-primary btn-round btn-sm',
                                    'rel'    => 'tooltip',
                                    'escape' => false
                                )
                            ); 
                            echo (
                                        '</td>
                                    </tr>'
                            );
                            $i++;
                        }     
                    }
                }
                echo (
                            '</tbody>
                        </table>
                    </div>'
                );
            } else {
                echo (
                    '<div class="box-content">
                        <table class="table table-striped table-bordered bootstrap-datatable Retailerdatatable">
                            <thead>
                                <tr>
                                    <th>Id Transacción</th>
                                    <th>Fecha & Hora</th>
                                    <th>Cliente</th>
                                    <th>Beneficiario</th>
                                    <th>Banco</th>
                                    <th>Tipo de Cuenta</th>
                                    <th>Inversionista</th>
                                    <th>Monto $</th>
                                    <th>Monto BsS.</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>'
                );
                foreach($remittances AS $r) {
                    echo (
                                '<tr>
                                    <td>');
                    echo $this->Html->link(
                        str_pad($r->id,6,'0',STR_PAD_LEFT),
                        array(
                            'controller' => 'remittance',
                            'action'     => 'edit', 
                            base64_encode($r->id)
                        )
                    ); 
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo date_format($r->trans_dt, 'Y-m-d H:i:s');
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $r->client;
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $r->recipient;
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $r->bank;
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $r->account;
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $r->investor;
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo '$' . number_format($r->amount,2);
                    echo (
                                    '</td>
                                    <td style="align-text: center;">'
                    );
                    echo 'BsS.' . number_format($r->amount*$r->purchase_rate,2);
                    echo (
                                    '</td>
                                    <td style="align-text: center;">'
                    );
                    /* Status:
                        1:Disponible, 2:Reservada, 3:En Verificación, 4:Completada, 5:Cancelada
                    */
                    if ($r->status == 1) {
                        echo "<i class='fa fa-circle text-danger'></i> " . __('Disponible');
                    } elseif ($r->status == 2) {
                        echo "<i class='fa fa-circle text-warning'></i> " . __('Reservada');
                    } elseif ($r->status == 3) {
                        echo "<i class='fa fa-circle text-info'></i> " . __('En Verificación');
                    } elseif ($r->status == 4) {
                        echo "<i class='fa fa-circle text-success'></i> " . __('Completada');
                    } elseif ($r->status == 5) {
                        echo "<i class='fa fa-circle text'></i> " . __('Cancelada');
                    } else {
                        echo __("Otro");
                    }
                    echo ('
                                    </td>
                                    <td>'
                    );
                    echo $this->Html->link(
                        "<i class='fas fa-pencil-alt'></i> " . __('Ver'), 
                        array(
                            'controller' => 'remittance',
                            'action'     => 'edit',
                            base64_encode($r->id)
                        ), 
                        array(
                            'class'      => 'btn btn-primary btn-round btn-sm',
                            'rel'        => 'tooltip',
                            'escape'     => false
                        )
                    );    
                    echo $this->Html->Link(
                        "<i class='fas fa-trash'></i> " . __('Eliminar'), 
                        array(
                            'controller' => 'remittance',
                            'action'     => 'delete',
                            base64_encode($r->id)
                        ), 
                        array(
                            'class'   => 'btn btn-danger btn-round btn-sm',
                            'rel'     => 'tooltip',
                            'escape'  => false,
                            'confirm' => __('¿Estás seguro de que deseas eliminar esta remesa?')
                        )
                    );
                    echo (
                                    '</td>
                                </tr>');
                }
                echo (
                            '</tbody>
                        </table>
                    </div>'
                );
            }
        ?>
    </div>
</div>
