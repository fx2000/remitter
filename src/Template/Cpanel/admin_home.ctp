<?php //debug($crt_control);
 echo $this->Html->css('charisma-app');
 $user_type = $this->request->session()->read('user_type');
//debug($sales);
?>

<!--<div id="content" class="span12">-->
<!-- LONG UPPER LINE-->

<div>
    <ul class="breadcrumb">
        <li>
            <?php
                echo $this->Html->link(
                    __('Inicio'),
                    array(
                        'controller' => 'cpanel',
                        'action'     => 'home'
                    )
                );
            ?>
        </li>
    </ul>
</div>

<div class="row-fluid" style="margin:30px 0 10px 0;">
    <?php 
        if ($user_type == 1 || $user_type == 2 || $user_type == 3 || $user_type ==4 ) {
            echo $this->Html->link(
                '<span class="icon32 icon-blue icon-asterisk1"></span><div>' . __('Gestionar Retailers') . '</div>',
                array(
                    'controller' => 'Retailer',
                    'action'     => 'view'
                ),
                array(
                    'data-rel'   => 'tooltip',
                    'title'      => __('Gestionar Retailers'),
                    'class'      => 'well span3 top-block',
                    'escape'     => false
                )
            );
        }
    ?>

    <?php 
        if ($user_type == 1 || $user_type == 2 || $user_type == 3 || $user_type ==4 || $user_type == 6) {
            echo $this->Html->link(
                '<span class="icon32 icon-blue icon-briefcase"></span><div>' . __('Gestionar Tiendas') . '</div>',
                array(
                    'controller' => 'Store',
                    'action'     => 'view',
                    base64_encode(-1)
                ),
                array(
                    'data-rel' => 'tooltip',
                    'title'    => __('Gestionar Tiendas'),
                    'class'    => 'well span3 top-block',
                    'escape'   => false
                )
            );
        }
    ?>

    <?php 
        if ($user_type == 1 || $user_type == 2 || $user_type == 3 || $user_type ==4 || $user_type == 5 || $user_type == 6 || $user_type ==7) {
            echo $this->Html->link(
                '<span class="icon32 icon-blue icon-document"></span><div>' . __('Reportes') . '</div>',
                array(
                    'controller' => 'Report',
                    'action'     => 'transaction'
                ),
                array(
                    'data-rel'   => 'tooltip',
                    'title'      => __('Reportes'),
                    'class'      => 'well span3 top-block',
                    'escape'     => false
                )
            );
        }
    ?>

    <?php 
        if ($user_type == 1 || $user_type == 2 || $user_type == 3) {
            echo $this->Html->link(
                '<span class="icon32 icon-blue icon-star-off"></span><div>' . __('Inventario') . '</div>',
                array(
                    'controller' => 'Inventory',
                    'action'     => 'index'
                ),
                array(
                    'data-rel'   => 'tooltip',
                    'title'      => __('Inventario'),
                    'class'      => 'well span3 top-block',
                    'escape'     => false
                )
            );
        }
    ?>
</div>

<div class="row-fluid" style="margin:20px 0;">
    <?php 
        if ($user_type == 1 || $user_type == 2 || $user_type == 3 || $user_type ==4 || $user_type == 6) {
            echo $this->Html->link(
                '<span class="icon32 icon-blue icon-info"></span><div>' . __('Estado de Recargas') . '</div>',
                array(
                    'controller' => 'Recharge',
                    'action'     => 'status'
                ),
                array(
                    'data-rel'   => 'tooltip',
                    'title'      => __('Estado de Recargas'),
                    'class'      => 'well span3 top-block',
                    'escape'     => false
                )
            );
        }
    ?>

    <?php 
        if ($user_type == 1) {
            echo $this->Html->link(
                '<span class="icon32 icon-blue icon-gear"></span><div>' . __('Configuración') . '</div>',
                array(
                    'controller' => 'Setting',
                    'action'     => 'operator'
                ),
                array(
                    'data-rel'   => 'tooltip',
                    'title'      => __('Configuración'),
                    'class'      => 'well span3 top-block',
                    'escape'     => false
                )
            );
        }
    ?>

    <?php 
        if ($user_type == 1 || $user_type == 2 || $user_type == 3) {
            echo $this->Html->link(
                '<span class="icon32 icon-blue icon-gear"></span><div>' . __('Configuración de App') . '</div>',
                array(
                    'controller' => 'AppSetting',
                    'action'     => 'view_slideshow_img'
                ),
                array(
                    'data-rel'   => 'tooltip',
                    'title'      => __('Configuración de App'),
                    'class'      => 'well span3 top-block',
                    'escape'     => false
                )
            );
        }
    ?>
    
    <?php 
        if ($user_type == 1 || $user_type == 2 || $user_type == 3 || $user_type ==4 || $user_type == 6) {
            echo $this->Html->link(
                '<span class="icon32 icon-blue icon-user"></span><div>' . __('Gestionar Usuarios') . '</div>',
                array(
                    'controller' => 'User',
                    'action'     => 'view',
                    base64_encode(-1)
                ),
                array(
                    'data-rel'   => 'tooltip',
                    'title'      => __('Gestionar Usuarios'),
                    'class'      => 'well span3 top-block',
                    'escape'     => false
                )
            );
        }
    ?>
</div>
