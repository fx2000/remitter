<?php 
    $user_id = $this->request->session()->read('user_id');
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
            __('Mis Retiros'),
            array(
                'controller' => 'payment',
                'action'     => 'index'
            )
        );
    ?>
</div>
<div>
    
<?php
    echo $this->Html->link(
        __("<i class='fas fa-plus'></i> Solicitar Retiro"),
            array(
                'controller' => 'payment',
                'action'     => 'add', base64_encode($user_id)
            ), 
            array(
                'class'      => 'btn btn-success btn-simple btn-link pull-right',
                'rel'        => 'tooltip',
                'escape'     => false
            )
        ); 
?>
</div>
<div class="row-fluid ">        
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-money-bill-alt"></i><?php echo __(' Mis Retiros'); ?></h4>
        </div>
        <div class="box-content">
            <table class="table table-striped table-bordered bootstrap-datatable Productdatatable">
                <thead>
                    <tr>
                        <th><?php echo __('Id Transacción'); ?></th>
                        <th class="hidden-phone"><?php echo __('Fecha & Hora'); ?></th>
                        <th class="hidden-phone"><?php echo __('Monto'); ?></th>
                        <th class="hidden-phone"><?php echo __('Banco'); ?></th>
                        <th class="hidden-phone"><?php echo __('Tipo de Cuenta'); ?></th>
                        <th class="hidden-phone"><?php echo __('Estado de la Transacción'); ?></th>
                        <!-- <th class="hidden-phone">Options</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($payments AS $p){ ?>
                            <tr>
                                <td><?php echo str_pad($p->id,6,'0',STR_PAD_LEFT); ?></td>
                                <td><?php echo $p->trans_dt; ?></td>
                                <td><?php echo '$'.$p->amount; ?></td>
                                <td><?php echo $p->bank; ?></td>
                                <td><?php echo $p->bank_account_type; ?></td>
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
                                <!-- <td>
                                    <?php //echo $this->Html->link("<i class='fas fa-pencil-alt'></i> Edit", 
                                        //array('controller' => 'payment','action'=> 'edit', base64_encode($p->id)), 
                                        //array('class' => 'btn btn-primary btn-round btn-sm', 'rel'=>'tooltip', 'escape' => false)) 
                                    ?>
                                </td> -->
                            </tr>
                    <?php   
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>