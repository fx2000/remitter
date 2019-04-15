<?php 
    $user_type = $this->request->session()->read('user_type');
    if ($this->request->session()->read('alert') != '') {
?>

<div class="alert <?php echo ($this->request->session()->read('success')==1)?'alert-success':'alert-danger'?>">
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
            __('Pagos'),
            array(
                'controller' => 'payment',
                'action'     => 'index'
            )
        );
    ?>
</div>

<div class="row-fluid ">        
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-money-bill-alt"></i><?php echo __(' Pagos'); ?></h4>
        </div>
        <div class="box-content">
            <table class="table table-striped table-bordered bootstrap-datatable inventorymovementdatatable">
                <thead>
                    <tr>
                        <th><?php echo __('ID Transacción'); ?></th>
                        <th class="hidden-phone"><?php echo __('Fecha & Hora'); ?></th>
                        <th class="hidden-phone"><?php echo __('Inversionista'); ?></th>
                        <th class="hidden-phone"><?php echo __('Monto'); ?></th>
                        <th class="hidden-phone"><?php echo __('Banco'); ?></th>
                        <th class="hidden-phone"><?php echo __('Tipo de Cuenta'); ?></th>
                        <th class="hidden-phone"><?php echo __('Número de Cuenta'); ?></th>
                        <th class="hidden-phone"><?php echo __('Estado'); ?></th>
                        <th class="hidden-phone"><?php echo __('Acciones'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($payments AS $p){ ?>
                            <tr>
                                <td>
                                    <?php
                                        echo $this->Html->link(
                                            str_pad($p->id,6,'0',STR_PAD_LEFT), 
                                            array(
                                                'controller' => 'payment',
                                                'action'     => 'edit', 
                                                base64_encode($p->id)
                                            )
                                        ); 
                                    ?>
                                </td>
                                <td><?php echo date_format($p->trans_dt, 'Y-m-d H:i:s'); ?></td>
                                <td><?php echo $p->investor; ?></td>
                                <td><?php echo '$' . number_format($p->amount,2,'.',','); ?></td>
                                <td><?php echo $p->bank; ?></td>
                                <td><?php echo $p->bank_account_type; ?></td>
                                <td><?php echo $p->bank_account_number; ?></td>
                                <td style="align-text: center;">
                                <?php 
                                    if ($p->status == 1) {
                                        echo "<i class='fa fa-circle text-warning'></i> " . __('Pendiente');
                                    } elseif ($p->status == 2) {
                                        echo "<i class='fa fa-circle text-info'></i> " . __('En Verificación');
                                    } elseif ($p->status == 3) {
                                        echo "<i class='fa fa-circle text-success'></i> " . __('Aprobado');
                                    } else {
                                        echo "<i class='fa fa-circle text-danger'></i> " . __('Rechazado');
                                    }
                                ?>
                                </td>
                                <td>
                                    <?php
                                        echo $this->Html->link(
                                            "<i class='fas fa-pencil-alt'></i> " . __('Ver'), 
                                            array(
                                                'controller' => 'payment',
                                                'action'     => 'edit',
                                                base64_encode($p->id)
                                            ), 
                                            array(
                                                'class'      => 'btn btn-primary btn-round btn-sm',
                                                'rel'        => 'tooltip',
                                                'escape'     => false
                                            )
                                        );
                                    ?>
                                    <?php
                                        echo $this->Html->link(
                                            "<i class='fas fa-trash'></i> " . __('Eliminar'),
                                            array(
                                                'controller' => 'payment',
                                                'action'     => 'delete',
                                                base64_encode($p->id)
                                            ), 
                                            array(
                                                'class'      => 'btn btn-danger btn-round btn-sm',
                                                'rel'        =>'tooltip',
                                                'escape'     => false
                                            )
                                        );
                                    ?>
                                </td>
                            </tr>
                    <?php   
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>