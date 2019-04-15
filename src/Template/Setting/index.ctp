<?php 
    $user_type = $this->request->session()->read('user_type');
    if ($this->request->session()->read('alert') != '') {
?>

<div class="alert <?php echo ($this->request->session()->read('success') == 1) ? 'alert-success':'alert-danger'; ?>">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>
        <?php 
            echo $this->request->session()->read('alert');
            $_SESSION['alert']='';
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
            __('Configuración'),
            array(
                'controller' => 'setting',
                'action'     => 'index'
            )
        );
    ?>
</div>
<div>

<?php
    // echo $this->Html->link(
    //     "<i class='fas fa-plus'></i> " . __('Agregar Configuración'), 
    //     array(
    //         'controller' => 'setting',
    //         'action'     => 'add'
    //     ), 
    //     array(
    //         'class'      => 'btn btn-success btn-simple btn-link pull-right',
    //         'rel'        =>'tooltip',
    //         'escape'     => false
    //     )
    // ); 
?>

</div>
<div class="row-fluid ">        
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-money-bill-alt"></i><?php echo __(' Impuestos y Tasas'); ?></h4>
        </div>
        <div class="box-content">
            <table class="table table-striped table-bordered bootstrap-datatable">
                <thead>
                    <tr>
                        <th class="hidden-phone"><?php echo __('Id'); ?></th>
                        <th class="hidden-phone"><?php echo __('País'); ?></th>
                        <th><?php echo __('ITBMS'); ?></th>
                        <th><?php echo __('Tasa Inversionistas'); ?></th>
                        <th><?php echo __('Tasa Clientes'); ?></th>
                        <th><?php echo __('Tarifa'); ?></th>
                        <th class="hidden-phone"><?php echo __('Estado'); ?></th>
                        <th class="hidden-phone"><?php echo __('Acciones'); ?></th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($settings AS $setting){ ?>
                            <tr>
                                <td>
                                    <?php echo ($setting->id); ?>
                                </td>
                                <td>
                                    <?php echo ($setting->country); ?>
                                </td>
                                <td>
                                    <?php echo number_format($setting->tax,2) . '%'; ?>
                                </td>
                                <td>
                                    <?php echo 'BsF.' . number_format($setting->sale_rate,2); ?>
                                </td>
                                <td>
                                    <?php echo 'BsF.' . number_format($setting->purchase_rate,2); ?>
                                </td>
                                <td>
                                    <?php echo '$' . number_format($setting->fee,2); ?>
                                </td>
                                <td style="align-item: center;">
                                    <?php
                                        echo '',(
                                            $setting->status == 1 ? "<i class='fas fa-check'></i>" : "<i class='fas fa-times'></i>"
                                        );
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        echo $this->Html->link(
                                            "<i class='fas fa-pencil-alt'></i> " . __('Modificar'), 
                                            array(
                                                'controller' => 'setting',
                                                'action'     => 'edit', 
                                                base64_encode($setting->id)
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
                                                'controller' => 'setting',
                                                'action'     => 'delete', 
                                                base64_encode($setting->id)
                                            ), 
                                            array(
                                                'class'      => 'btn btn-danger btn-round btn-sm', 
                                                'rel'        => 'tooltip',
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