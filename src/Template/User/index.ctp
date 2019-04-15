<?php
    if ($role != 0) {
        $role = $role[0]['id'];
        switch ($role) {
            case 1:
                $title = __('Administrador');
            break;
            case 2:
                $title = __('Supervisor');
            break;
            case 3:
                $title = __('Operador');
            break;
            case 4:
                $title = __('Inversionista');
            break;
            case 5:
                $title = __('Cliente');
            break;
            case 6:
                $title = __('Tesorero');
            break;
            case 7:
                $title = __('Atención al Cliente');
            break;
            default:
                $title = __('Otro');
            break;
        } 
    } else {
        $title = __('Personal');
    }
    //echo ('DEBUGGER -> Rol: '.$role);
?>

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
            $title,
            array(
                'controller' => 'user',
                'action'     => 'index',
                $role
            )
        );
    ?>
</div>

<div>
    <?php
        echo $this->Html->link(
            "<i class='fas fa-plus'></i> " . __('Agregar ') . $title, 
            array(
                'controller' => 'user',
                'action'     => 'add',
                $role), 
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
        <h4><i class="fas fa-users"></i> <?php echo $title; ?></h4>
        </div>
        <div class="box-content">
            <table class="table table-striped table-bordered bootstrap-datatable salesretailerdatatable">
                <thead>
                    <tr>
                        <th><?php echo __('Nombre'); ?></th>
                        <th class="hidden-phone"><?php echo __('Cédula o Pasaporte'); ?></th>
                        <th class="hidden-phone"><?php echo '',($role!=0 ? __('País') : __('Perfil')); ?></th>
                        <th class="hidden-phone"><?php echo __('Estado'); ?></th>
                        <th class="hidden-phone"><?php echo __('Acciones'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($users AS $user) {
                    ?>
                        <tr>
                            <td>
                                <?php
                                    echo $this->Html->link(
                                        $user->fname1 . ' ' . $user->lname1, 
                                        array(
                                            'controller' => 'user',
                                            'action'     => 'edit',
                                            base64_encode($user->id), 
                                            $user->user_type
                                        )
                                    ); 
                                ?>
                            </td>
                            <td>
                                <?php
                                    $cedula = is_numeric($user->tax_id) ? number_format($user->tax_id) : $user->tax_id;
                                    echo '', ($user->tax_id == null ? $user->passport : $cedula);
                                ?>
                            </td>
                            <td><?php echo '',($role!=0 ? $user->country : $user->role); ?></td>
                            <td style="align-item: center;">
                                <?php echo '',($user->status == 1 ? "<i class='fas fa-check'></i>" : "<i class='fas fa-times'></i>"); ?>
                            </td>
                            <td>
                                <?php
                                    echo $this->Html->link(
                                        "<i class='fas fa-pencil-alt'></i> " . __('Ver'), 
                                        array(
                                            'controller' => 'user',
                                            'action'     => 'edit', 
                                            base64_encode($user->id),
                                            $user->user_type
                                        ), 
                                        array(
                                            'class'      => 'btn btn-primary btn-round btn-sm', 
                                            'rel'        => 'tooltip',
                                            'escape'     => false
                                        )
                                    ); 
                                ?>
                                <?php
                                    if($user_type == 1 || $user_type == 2){ //Admin y Supervisor
                                        echo $this->Html->link(
                                            "<i class='fas fa-trash'></i> " . __('Eliminar'), 
                                            array(
                                                'controller' => 'user',
                                                'action'     => 'delete', 
                                                base64_encode($user->id)
                                            ), 
                                            array(
                                                'class'      => 'btn btn-danger btn-round btn-sm', 
                                                'rel'        => 'tooltip',
                                                'escape'     => false, 
                                                'confirm'    => __('¿Estás seguro de que deseas eliminar este ') . $title . '?'
                                            )
                                        );
                                    } 
                                ?>
                                <?php 
                                    if ($role == 5) {
                                        echo $this->Html->link(
                                            "<i class='icon-user icon-white'></i> " . __('Beneficiarios'), 
                                            array(
                                                'controller' => 'recipient',
                                                'action'     => 'index', 
                                                base64_encode($user->id)
                                            ), 
                                            array(
                                                'class'      => 'btn btn-success btn-round btn-sm', 
                                                'rel'        => 'tooltip',
                                                'escape'     => false
                                            )
                                        );
                                    }
                                ?>
                                <?php
                                    if ($role == 4) {
                                        echo $this->Html->link(
                                            "<i class='icon-envelope icon-white'></i> " . __('Pagos'), 
                                            array(
                                                'controller' => 'payment',
                                                'action'     => 'index', 
                                                base64_encode($user->id)
                                            ), 
                                            array(
                                                'class'      => 'btn btn-success btn-round btn-sm', 
                                                'rel'        => 'tooltip',
                                                'escape'     => false
                                            )
                                        );
                                    }
                                ?>
                                <?php
                                    if ($role == 4) {
                                        echo $this->Html->link(
                                            "<i class='icon-envelope icon-white'></i> " . __('Cuentas'), 
                                            array(
                                                'controller' => 'recipient',
                                                'action'     => 'index', 
                                                base64_encode($user->id)
                                            ), 
                                            array(
                                                'class'      => 'btn btn-info btn-round btn-sm', 
                                                'rel'        => 'tooltip',
                                                'escape'     => false
                                            )
                                        );
                                    }
                                ?>
                                <?php
                                    if ($role == 4) {
                                        echo $this->Html->link(
                                            "<i class='icon-envelope icon-white'></i> " . __('Remesas'), 
                                            array(
                                                'controller' => 'remittance',
                                                'action'     => 'index-investor', 
                                                base64_encode($user->id)
                                            ), 
                                            array(
                                                'class'      => 'btn btn-warning btn-round btn-sm', 
                                                'rel'        => 'tooltip',
                                                'escape'     => false
                                            )
                                        );
                                    } elseif ($role == 5 && ($user_type == 1 || $user_type == 2 || $user_type == 6)){
                                        echo $this->Html->link(
                                            "<i class='icon-envelope icon-white'></i> " . __('Remesas'), 
                                            array(
                                                'controller' => 'remittance',
                                                'action'     => 'index', 
                                                base64_encode($user->id)
                                            ), 
                                            array(
                                                'class'      => 'btn btn-warning btn-round btn-sm', 
                                                'rel'        => 'tooltip',
                                                'escape'     => false
                                            )
                                        );
                                    }
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