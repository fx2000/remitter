<?php 
    $user_id = $this->request->session()->read('user_id');
    $user_type = $this->request->session()->read('user_type');
    if ($user_type == '4') {
        $title = __(' Mis Compras');
    } else {
        $title = __(' Remesas');
    }
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
            __('Remesas Compradas'),
            array(
                'controller' => 'remittance',
                'action'     => 'indexInvestor',
                base64_encode($user_id)
            )
        );
    ?>
</div>

<div class="row-fluid">        
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-paper-plane"></i><?php echo __(' Remesas Compradas'); ?></h4>
        </div>
        <?php 
            if ($user_type == '4') { 
                echo(
                    '<div class="box-content">
                        <table class="table table-striped table-bordered bootstrap-datatable Retailerdatatable">
                            <thead>
                                <tr>
                                    <th>ID Transacción</th>
                                    <th>Fecha & Hora</th>
                                    <th>Beneficiario</th>
                                    <th>Banco</th>
                                    <th>Tipo de cuenta</th>
                                    <th>Número de cuenta</th>
                                    <th>Monto a Transferir</th>
                                    <th>Tasa</th>
                                    <th>Monto a Recibir</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>'
                );
                foreach($remittances AS $r) {
                    echo (
                                '<tr>
                                    <td>'
                    );
                    echo str_pad($r->id,6,'0',STR_PAD_LEFT); 
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $r->reserved_dt;
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
                    echo $r->number;
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $this->Number->currency($r->amount_delivered,'BsF');
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $this->Number->currency($r->sale_rate,'BsF');
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $this->Number->currency($r->amount_sold);
                    echo (
                                    '</td>
                                    <td style="align-text: center;">'
                    );
                    if ($r->status == 2) {
                        echo "<i class='fa fa-circle text-danger'></i> " . __('Reservada');
                    } elseif ($r->status == 3) {
                        echo "<i class='fa fa-circle text-warning'></i> " . __('En Verificación');
                    } elseif ($r->status == 4) {
                        echo "<i class='fa fa-circle text-success'></i> " . __('Completada');
                    } else {
                        echo __("Otro");
                    }
                    echo (
                                    '</td>
                                    <td>'
                    );
                    //if ($r->status == 2) {
                        echo $this->Html->link(
                            "<i class='fas fa-pencil-alt'></i> " . __('Ver'), 
                            array(
                                'controller' => 'remittance',
                                'action'     => 'confirm',
                                base64_encode($r->id)
                            ), 
                            array(
                                'class'      => 'btn btn-primary btn-round btn-sm',
                                'rel'        => 'tooltip',
                                'escape'     => false
                            )
                        );
                    //} 
                    echo (
                                    '</td>
                                </tr>'
                    );
                }
                echo (
                            '</tbody>
                        </table>
                    </div>'
                );
            } else {
                echo(
                    '<div class="box-content">
                        <table class="table table-striped table-bordered bootstrap-datatable transactiondatatable">
                            <thead>
                                <tr>
                                    <th>Id Transacción</th>
                                    <th>Fecha & Hora</th>
                                    <th>Inversionista</th>
                                    <th>Cliente</th>
                                    <th>Beneficiario</th>
                                    <th>Monto por Transferir</th>
                                    <th>Banco</th>
                                    <th>Tipo de cuenta</th>
                                    <th>Número de cuenta</th>
                                    <th>Monto por Recibir</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>'
                );
                
                foreach($remittances AS $r) {
                    echo (
                                '<tr>
                                    <td>'
                    );
                    echo str_pad($r->id,6,'0',STR_PAD_LEFT); 
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $r->reserved_dt;
                    echo (
                                    '</td>
                                    <td>'
                    );
                    echo $r->investor;
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
                    echo $this->Number->currency($r->amount_delivered,'BsF');
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
                    echo $r->number;
                    echo (
                                    '</td>
                                    <td>'
                    );
                    if ($r->status==1) {
                        echo $this->Number->currency($r->amount_delivered/$settings[0]->sale_rate);
                    } else {
                        echo $this->Number->currency($r->amount_sold);
                    }
                    echo (
                                    '</td>
                                    <td style="align-text: center;">'
                    );
                    if ($r->status == 1) {
                        echo "<i class='fa fa-circle text-info'></i> " . __('Disponible');
                    } elseif ($r->status == 2) {
                        echo "<i class='fa fa-circle text-danger'></i> " . __('Reservada');
                    } elseif ($r->status == 3) {
                        echo "<i class='fa fa-circle text-warning'></i> " . __('En Verificación');
                    } elseif ($r->status == 4) {
                        echo "<i class='fa fa-circle text-success'></i> " . __('Completada');
                    } else {
                        echo __("Otro");
                    }
                    echo (
                                    '</td>
                                    <td>'
                    );
                    if ($r->status == 2) {
                        echo $this->Html->link(
                            "<i class='fas fa-pencil-alt'></i> " . __('Ver'), 
                            array(
                                'controller' => 'remittance',
                                'action'     => 'confirm',
                                base64_encode($r->id)
                            ), 
                            array(
                                'class'      => 'btn btn-primary btn-round btn-sm',
                                'rel'        => 'tooltip',
                                'escape'     => false
                            )
                        );
                    } 
                    echo (
                                    '</td>
                                </tr>'
                    );
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
