<?php
    if ($role != '') {
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
        if ($role == 0) {
            $title = __('Personal');
        }
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
            __('Usuarios / '),
            array(
                'controller' => 'user',
                'action'     => 'index'
            )
        );
    ?>
    <?php
        echo $this->Html->link(
            __('Agregar ') . $title . '',
            array(
                'controller' => 'user',
                'action'     => 'add',
                $role
            )
        );
    ?>
</div>

<?php
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

<div class="row-fluid ">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-user"></i> Agregar <?php echo $title; ?></h4>
        </div>
        <div class="box-content">
            <?php
                echo $this->Form->create('',[
                    'type'  => 'file',
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
                                            'User.fname1',
                                            array(
                                                'type'                => 'text',
                                                'class'               => 'form-control',
                                                'id'                  => 'name',
                                                'div'                 => false,
                                                'label'               => __('Primer Nombre'),
                                                'maxlength'           => 50,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Name'
                                            )
                                        );
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('name');
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.name);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'User.fname2',
                                            array(
                                                'type'                => 'text',
                                                'class'               => 'form-control',
                                                'id'                  => 'name',
                                                'div'                 => false,
                                                'label'               => __('Segundo Nombre'),
                                                'maxlength'           => 50,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Name'
                                            )
                                        );
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'User.lname1',
                                            array(
                                                'type'                => 'text',
                                                'class'               => 'form-control',
                                                'id'                  => 'lname',
                                                'div'                 => false,
                                                'label'               => __('Primer Apellido'),
                                                'maxlength'           => 100,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Name'
                                            )
                                        );
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('lname');
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.lname);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'User.lname2',
                                            array(
                                                'type'                => 'text',
                                                'class'               => 'form-control',
                                                'id'                  => 'name',
                                                'div'                 => false,
                                                'label'               => __('Segundo Apellido'),
                                                'maxlength'           => 100,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Name'
                                            )
                                        );
                                    ?>
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <?php
                                    if ($role == 0) {
                                ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.user_type',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $roles,
                                                    'placeholder' => __('Selecciona un Perfil'),
                                                    'id'          => 'user_type',
                                                    'label'       => __('Perfil'),
                                                    'onchange'    => 'ShowField()'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.email',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'email',
                                                    'div'                 => false,
                                                    'label'               => __('Email'),
                                                    'maxlength'           => 50,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'Email'));
                                        ?>
                                        <script language="javascript" type="text/javascript">
                                            var f1 = new LiveValidation('email');
                                            f1.add( Validate.Presence);
                                            f1.add( Validate.Email);
                                        </script>
                                    </div>
                                </div>
                                
                                <?php
                                    } else {
                                ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.tax_id',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'tax_id',
                                                    'div'                 => false,
                                                    'label'               => __('Cédula'),
                                                    'maxlength'           => 100,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'tax_id'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.passport',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'passport',
                                                    'div'                 => false,
                                                    'label'               => __('Pasaporte'),
                                                    'maxlength'           => 100,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'passport'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.birthday', 
                                                array(
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                    'label' => __('Fecha de Nacimiento'),
                                                    'placeholder' => 'YYYY-MM-DD'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            $options = [1 => __('Masculino'), 2 => __('Femenino')];
                                            echo $this->Form->input(
                                                'User.gender',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $options,
                                                    'placeholder' => __('Selecciona un Género'),
                                                    'id'          => 'gender',
                                                    'label'       => __('Género'),
                                                    'onchange'    => 'ShowField()'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.born_country',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $countries,
                                                    'placeholder' => __('Selecciona un País'),
                                                    'id'          => 'country',
                                                    'label'       => __('Nacionalidad'),
                                                    'onchange'    => 'ShowField()',
                                                    'default'     => '232'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.profession',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'profession',
                                                    'div'                 => false,
                                                    'label'               => __('Profesión'),
                                                    'maxlength'           => 100,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'Profession'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.address',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'address',
                                                    'div'                 => false,
                                                    'label'               => __('Dirección'),
                                                    'maxlength'           => 100,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'Address'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.neighborhood',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'neighborhood',
                                                    'div'                 => false,
                                                    'label'               => __('Urbanización'),
                                                    'maxlength'           => 100,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'Neighborhood'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.town',
                                                array(
                                                    'type' => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'town',
                                                    'div'                 => false,
                                                    'label'               => __('Ciudad'),
                                                    'maxlength'           => 100,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'Town',
                                                    'default'             => 'Ciudad de Panamá'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.state',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'state',
                                                    'div'                 => false,
                                                    'label'               => __('Estado'),
                                                    'maxlength'           => 100,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'State',
                                                    'default'             => 'Panamá'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php 
                                            if ($role == 5) {
                                                echo $this->Form->input(
                                                    'User.country',
                                                    array(
                                                        'type'        => 'select',
                                                        'class'       => 'form-control',
                                                        'options'     => $countries,
                                                        'placeholder' => __('País'),
                                                        'id'          => 'country',
                                                        'label'       => __('País'),
                                                        'onchange'    => 'ShowField()',
                                                        'default'     => '170'
                                                    )
                                                );
                                            } else {
                                                echo $this->Form->input(
                                                    'User.country',
                                                    array(
                                                        'type'        => 'select',
                                                        'class'       => 'form-control',
                                                        'options'     => $countries,
                                                        'placeholder' => __('País'),
                                                        'id'          => 'country',
                                                        'label'       => __('País'),
                                                        'onchange'    => 'ShowField()',
                                                        'default'     => '232'
                                                    )
                                                );
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.home_phone',
                                                array(
                                                    'type'                => 'number',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'home_phone',
                                                    'div'                 => false,
                                                    'label'               => __('Teléfono Fijo'),
                                                    'maxlength'           => 15,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'Home Phone'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.mobile_phone',
                                                array(
                                                    'type'                => 'number',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'mobile_phone',
                                                    'div'                 => false,
                                                    'label'               => __('Teléfono Móvil'),
                                                    'maxlength'           => 15,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'Mobile Phone'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'User.email',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'email',
                                                    'div'                 => false,
                                                    'label'               => __('Email'),
                                                    'maxlength'           => 50,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'Email'));
                                        ?>
                                        <script language="javascript" type="text/javascript">
                                            var f1 = new LiveValidation('email');
                                            f1.add( Validate.Presence);
                                            f1.add( Validate.Email);
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            $options = [1 => __('Activo'), 2 => __('Inactivo')];
                                            echo $this->Form->input(
                                                'User.status',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $options,
                                                    'placeholder' => __('Selecciona un Estado'),
                                                    'id'          => 'status',
                                                    'label'       => 'Status',
                                                    'onchange'    => 'ShowField()'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <br>
                                    <span class="btn btn-file btn-xs">
                                        <?php 
                                            echo $this->Form->input(
                                                'User.photo', [
                                                    'type'     => 'file',
                                                    'required' => false, 
                                                    'class'    => 'fileinput',
                                                    'label'    =>__('Seleccionar Archivo')
                                                ]
                                            );
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                    }
                                ?>
                                <div class="col-md-12">
                                    <div class="form-actions">
                                        <?php 
                                            echo $this->Form->Submit(
                                                __('Agregar'),
                                                array(
                                                    'class' => 'btn btn-primary pull-right'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>