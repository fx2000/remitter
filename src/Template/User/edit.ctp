<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    function ShowField()
    {
        var role = $("#role").val();
        if(role==4 || role==5)
        {
            $("#showdiv").show();
        }
    }

    $( function() {
        $( "#datepicker" ).datepicker();
    } );
</script>

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
            __('Usuarios'),
            array(
                'controller' => 'user',
                'action'     => 'index'
            )
        );
    ?>
</div>

<?php
    if ($this->request->session()->read('alert') != '') {
?>

<div class="alert <?php echo ($this->request->session()->read('success')==1)?'alert-success':'alert-danger'?>">
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
<?php
    echo $this->Form->create('',[
        'type'  => 'file',
        'class' => 'form-horizontal'
    ]);
?>
    <div class="section-image" data-image="../../assets/img/bg5.jpg" ;data-color="blue">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-6">
                    <div class="card ">
                        <div class="card-header ">
                            <h4>
                                <i class="fas fa-user"></i>
                                <?php echo __(' Ver ') . $title; ?>
                            </h4>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-3 pr-1">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Primer Nombre'); ?>
                                            <star class="star">*</star>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.fname1',
                                                array(
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                    'id' => 'name',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 50,
                                                    'data-rel' => 'tooltip',
                                                    'data-original-title' => 'Name',
                                                    'requerid' => true
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3 px-1">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Segundo Nombre'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.fname2',
                                                array(
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                    'id' => 'name',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 50,
                                                    'data-rel'=> 'tooltip',
                                                    'data-original-title' => 'Name'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3 pl-1">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Primer Apellido'); ?>
                                            <star class="star">*</star>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.lname1',
                                                array(
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                    'id' => 'name',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 100,
                                                    'data-rel' => 'tooltip',
                                                    'data-original-title' => 'Name',
                                                    'requerid' => true
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3 pl-1">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Segundo Apellido'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.lname2',
                                                array(
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                    'id' => 'name',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 100,
                                                    'data-rel' => 'tooltip',
                                                    'data-original-title' => 'Name'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                                if ($role == '1' || $role == '2' || $role == '3' || $role == '6' || $role == '7') {
                                    if ($this->request->session()->read('user_type')!=3) {
                            ?>
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Perfil'); ?>
                                            <star class="star">*</star>
                                        </label>
                                        <?php 
                                            echo $this->Form->input(
                                                'User.user_type',
                                                array(
                                                    'type'  => 'select',
                                                    'class'  => 'form-control',
                                                    'options' => $roles,
                                                    'placeholder' => __('Selecciona un Perfil'),
                                                    'id' => 'gender',
                                                    'label' => false,
                                                    'onchange' => 'ShowField()',
                                                    'requerid' => true
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6 pl-1">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Email'); ?>
                                            <star class="star">*</star>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.email',
                                                array(
                                                    'type' => 'email',
                                                    'class' => 'form-control',
                                                    'id' => 'email',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 50,
                                                    'data-rel' => 'tooltip',
                                                    'data-original-title' => 'Email',
                                                    'requerid' => true
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                                    }
                                } else {
                            ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Cédula'); ?>
                                            <star class="star">*</star>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.tax_id',
                                                array(
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                    'id' => 'tax_id',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 100,
                                                    'data-rel' => 'tooltip',
                                                    'data-original-title' => 'tax_id',
                                                    'requerid' => true
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Pasaporte'); ?>
                                            <star class="star">*</star>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.passport',
                                                array(
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                    'id' => 'tax_id',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 100,
                                                    'data-rel' => 'tooltip',
                                                    'data-original-title' => 'passport',
                                                    'requerid' => true
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Nacimiento'); ?>
                                            <star class="star">*</star>
                                        </label>
                                        <?php 
                                            echo $this->Form->input(
                                               'User.birthday',
                                               array(
                                                    'type' => 'date',
                                                    'class'=> 'form-control',
                                                    'id'   => 'birthday',
                                                    'div'  => false,
                                                    'label' => false,
                                                    'maxlength' => 100,
                                                    'data-rel' => 'tooltip',
                                                    'data-original-title' => 'birthday',
                                                    'requerid' => true
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Género'); ?>
                                        </label>
                                        <?php
                                            $options = [1 => __('Masculino'), 2 => __('Femenino')];
                                            echo $this->Form->input(
                                                'User.gender',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $options,
                                                    'placeholder' => __('Selecciona un género'),
                                                    'id'          => 'gender',
                                                    'label'       => false,
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
                                        <label>
                                            <?php echo __('Nacionalidad'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.born_country',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $countries,
                                                    'placeholder' => __('Selecciona un país'),
                                                    'id'          => 'country',
                                                    'label'       => false,
                                                    'onchange'    => 'ShowField()'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Profesión'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.profession',
                                                array(
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                    'id' => 'profession',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 100,
                                                    'data-rel' => 'tooltip',
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
                                        <label>
                                            <?php echo __('Dirección'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.address',
                                                array(
                                                    'type' => 'text',
                                                    'class' => 'form-control',
                                                    'id' => 'address',
                                                    'div'  => false,
                                                    'label'  => false,
                                                    'maxlength' => 100,
                                                    'data-rel'  => 'tooltip',
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
                                        <label>
                                            <?php echo __('Urbanización'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.neighborhood',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'neighborhood',
                                                    'div'                 => false,
                                                    'label'               => false,
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
                                        <label>
                                            <?php echo __('Ciudad'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.town',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'town',
                                                    'div'                 => false,
                                                    'label'               => false,
                                                    'maxlength'           => 100,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'Town'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Provincia'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.state',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'state',
                                                    'div'                 => false,
                                                    'label'               => false,
                                                    'maxlength'           => 100,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'State'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('País'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.country',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $countries,
                                                    'placeholder' => __('País'),
                                                    'id'          => 'country',
                                                    'label'       => false,
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
                                        <label>
                                            <?php echo __('Teléfono Fijo'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.home_phone',
                                                array(
                                                    'type' => 'number',
                                                    'class' => 'form-control',
                                                    'id' => 'home_phone',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 15,
                                                    'data-rel' => 'tooltip',
                                                    'data-original-title' => 'Home Phone'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Teléfono Movil'); ?>
                                        </label>
                                            <?php
                                            echo $this->Form->input(
                                                'User.mobile_phone',
                                                array(
                                                    'type' => 'number',
                                                    'class' => 'form-control',
                                                    'id' => 'mobile_phone',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 15,
                                                    'data-rel' => 'tooltip',
                                                    'data-original-title' => 'Mobile Phone'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Email'); ?>
                                        </label>
                                        <?php
                                            echo $this->Form->input(
                                                'User.email',
                                                array(
                                                    'type' => 'email',
                                                    'class' => 'form-control',
                                                    'id' => 'email',
                                                    'div' => false,
                                                    'label' => false,
                                                    'maxlength' => 30,
                                                    'data-rel' => 'tooltip',
                                                    'data-original-title' => 'Email',
                                                    'requerid' => true
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Estado'); ?>
                                        </label>
                                        <?php 
                                            $status = [1 => __('Activo'), 2 => __('Inactivo')];
                                            echo $this->Form->input(
                                                'User.status',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $status,
                                                    'placeholder' => __('Estado'),
                                                    'id'          => 'country',
                                                    'label'       => false,
                                                    'onchange'    => 'ShowField()'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-user">
                        <div class="card-header no-padding">
                            <div class="card-image">
                                <label>
                                    <?php echo __('Documento de Identidad'); ?>
                                </label>
                            </div>
                        </div>
                        <div class="card-body ">
                            <div class="author">
                                <?php 
                                    foreach ($user_detail as $user):
                                        if ($user->photo_dir != null || $user->photo != null) {
                                            $imgUrl = "\"" . '/webroot/img/users/photo/' . $user->photo_dir . '/' . $user->photo . "\"";
                                ?>
                                            <a href=<?php echo $imgUrl; ?>
                                                onclick="return !window.open(this.href, 'width=500,height=500')"
                                                target="_blank"
                                            >
                                            <img src=<?php echo $imgUrl; ?> width="250"/>
                                            </a>
                                <?php
                                        } else {
                                            $imgUrl = '/webroot/img/id_card_placeholder.png';
                                ?>
                                            <a href=<?php echo $imgUrl; ?>
                                                onclick="return !window.open(this.href, 'width=500,height=500')"
                                                target="_blank"
                                            >
                                            <img src=<?php echo $imgUrl; ?> width="250"/>
                                            </a>
                                <?php
                                        }
                                    endforeach;
                                ?>
                                <p class="card-description"></p>
                            </div>
                            <p class="card-description text-center">
                            </p>
                        </div>
                        <span class="btn btn-file btn-xs">
                                            <?php 
                                                echo $this->Form->input(
                                                    'User.photo', [
                                                        'type'     => 'file',
                                                        'required' => false, 
                                                        'class'    => 'fileinput',
                                                        'label'    =>__('Cambiar Imagen')
                                                    ]
                                                );
                                            ?>
                                        </span>
                        <?php
                            }
                        ?>
                        <div class="card-footer ">
                            <hr>
                            <div class="col-md-6">
                                <?php
                                    echo $this->Form->Submit(
                                        __('Guardar'),
                                        array(
                                            'class'=>'btn btn-primary'
                                        )
                                    );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="card card-user">
                        <?php
                            if ($user_detail[0]['user_type'] == 4) {
                        ?>
                                <div class="card-header no-padding">
                                    <label>
                                        <?php echo __('Saldo'); ?>
                                    </label>
                                </div>
                                <div class="card-body ">
                                        Saldo Disponible: $<?php echo number_format($balance['balance'], 2, '.', ','); ?>
                                        <br/>
                                        Fondos en Tránsito: $<?php echo number_format($balance['tmp_balance'], 2, '.', ','); ?>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>