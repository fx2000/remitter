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
                'action'     => 'profile'
            )
        );
    ?>
    <?php
        echo __('Actualizar Perfil');
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
            <h4><i class="fas fa-user"></i><?php echo __(' Actualizar Perfil'); ?></h4>
        </div>
        <div class="box-content">
            <?php
                echo $this->Form->create('',[
                    'class'=>'form-horizontal'
                ]);
            ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'User.username',
                                            array(
                                                'type'        => 'text',
                                                'class'       => 'form-control',
                                                'id'          => 'username',
                                                'div'         => false,
                                                'label'       => __('Usuario'),
                                                'placeholder' => __('Usuario')
                                            )
                                        );
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('username');
                                        f1.add(Validate.Presence);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'User.email',
                                            array(
                                                'type'        => 'text',
                                                'class'       => 'form-control',
                                                'id'          => 'email',
                                                'div'         => false,
                                                'label'       => __('Email'),
                                                'placeholder' => __('Email')
                                            )
                                        );
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('email');
                                        f1.add( Validate.Presence);
                                        f1.add(Validate.Presence);
                                        f1.add( Validate.Email);
                                    </script>
                                </div>
                            </div>
                            <div class="form-actions" style="margin-top: 22px;">
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
