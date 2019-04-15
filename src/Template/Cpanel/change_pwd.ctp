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
            __('Mi Perfil / '),
            array(
                'controller' => 'cpanel',
                'action'     => 'change_pwd'
            )
        );
    ?>
    <?php
        echo __('Cambiar Contraseña');
    ?>
</div>

<?php
    if ($this->request->session()->read('alert') != '') {
?>

<div class="alert <?php echo ($this->request->session()->read('success') == 1) ? 'alert-success':'alert-error'; ?>">
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

<div class="row-fluid ">        
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-key"></i><?php echo __(' Cambiar Contraseña'); ?></h4>
        </div>
        <div class="box-content">
            <?php
                echo $this->Form->create('',[
                    'class' => 'form-horizontal'
                ]);
            ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'User.old_pwd',
                                            array(
                                                'type'        => 'password',
                                                'class'       => 'form-control',
                                                'id'          => 'old_pwd',
                                                'div'         => false,
                                                'label'       => __('Contraseña Actual'),
                                                'placeholder' => __('Contraseña Actual')
                                            )
                                        );
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('old_pwd');
                                        f1.add( Validate.Presence);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'User.new_pwd',
                                            array(
                                                'type'        => 'password',
                                                'class'       => 'form-control',
                                                'id'          => 'new_pwd',
                                                'div'         => false,
                                                'label'       => __('Nueva Contraseña'),
                                                'placeholder' => __('Nueva Contraseña')
                                            )
                                        );
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('new_pwd');
                                        f1.add( Validate.Presence);
                                        f1.add( Validate.len_password);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'User.confirm_pwd',
                                            array(
                                                'type'        => 'password',
                                                'class'       => 'form-control',
                                                'id'          => 'confirm_pwd',
                                                'div'         => false,
                                                'label'       => __('Confirmar Contraseña'),
                                                'placeholder' => __('Confirmar Contraseña')
                                            )
                                        );
                                    ?>
                            <script language="javascript" type="text/javascript">
                                var f1 = new LiveValidation('confirm_pwd');
                                f1.add( Validate.Presence);
                                f1.add( Validate.passwordchange);
                            </script>
                                </div>
                            </div>
                            <div class="form-actions" style="margin-top: 24px;">
                                <?php
                                    echo $this->Form->Submit(
                                        __('Aceptar'),
                                        array(
                                            'class' => 'btn btn-primary'
                                        )
                                    );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>