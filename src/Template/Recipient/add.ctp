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
            __('Clientes '),
            array(
                'controller' => 'user',
                'action'     => 'index',
                5
            )
        );
    ?>
</div>

<?php
    if ($this->request->session()->read('alert') != '') {
?>

<div class="alert <?php echo ($this->request->session()->read('success')==1) ? 'alert-success':'alert-error'; ?>">
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
            <h4><i class="fas fa-users"></i><?php echo __(' Agregar Beneficiario'); ?></h4>
        </div>
        <div class="box-content">
            <?php
                echo $this->Form->create(
                    '',
                    ['class' => 'form-horizontal']
                );
            ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php 
                                        echo $this->Form->input(
                                            'Recipient.fname1',
                                            array(
                                                'type'                => 'text',
                                                'class'               => 'form-control',
                                                'id'                  => 'fname1',
                                                'div'                 => false,
                                                'label'               => __('Primer Nombre'),
                                                'maxlength'           => 100,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Name'
                                        ));
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('fname1'); 
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.fname1);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php 
                                        echo $this->Form->input(
                                            'Recipient.fname2',
                                            array(
                                                'type'                => 'text',
                                                'class'               => 'form-control',
                                                'id'                  => 'name',
                                                'div'                 => false,
                                                'label'               => __('Segundo Nombre'),
                                                'maxlength'           => 50,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Name'
                                        ));
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php 
                                        echo $this->Form->input(
                                            'Recipient.lname1',
                                            array(
                                                'type'                => 'text',
                                                'class'               => 'form-control',
                                                'id'                  => 'lname1',
                                                'div'                 => false,
                                                'label'               => __('Primer Apellido'),
                                                'maxlength'           => 100,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Name'
                                        ));
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('lname1'); 
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.lname1);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php 
                                        echo $this->Form->input(
                                            'Recipient.lname2',
                                            array(
                                                'type'                => 'text',
                                                'class'               => 'form-control',
                                                'id'                  => 'name',
                                                'div'                 => false,
                                                'label'               => __('Segundo Apellido'),
                                                'maxlength'           => 100,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Name'
                                        ));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php 
                                        echo $this->Form->input(
                                            'Recipient.tax_id',
                                            array(
                                            'type'                => 'text',
                                            'class'               => 'form-control',
                                            'id'                  => 'tax_id',
                                            'div'                 => false,
                                            'label'               => __('Cédula'),
                                            'maxlength'           => 100,
                                            'data-rel'            => 'tooltip',
                                            'data-original-title' => 'tax_id'
                                        ));
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('tax_id'); 
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.tax_id);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php 
                                        echo $this->Form->input(
                                            'Recipient.passport',
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
                                            'User.phone_number',
                                            array(
                                                'type'                => 'number',
                                                'class'               => 'form-control',
                                                'id'                  => 'phone_number',
                                                'div'                 => false,
                                                'label'               => __('Teléfono Celular'),
                                                'maxlength'           => 15,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Mobile Phone'
                                            )
                                        );
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('phone_number'); 
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.phone_number);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'Recipient.email',
                                            array(
                                                'type'                => 'text',
                                                'class'               => 'form-control',
                                                'id'                  => 'email',
                                                'div'                 => false,
                                                'label'               => __('Email'),
                                                'maxlength'           => 50,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Email'
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
                                            'Recipient.country_id',
                                            array(
                                                'type'        => 'select',
                                                'class'       => 'form-control',
                                                'options'     => $countries,
                                                'placeholder' => __('Selecciona un País'),
                                                'id'          => 'country',
                                                'label'       => __('País'),
                                                'onchange'    => 'ShowField()',
                                                'default'     => '232'
                                        ));
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('country'); 
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.country);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php 
                                        echo $this->Form->input(
                                            'Recipient.bank_id',
                                            array(
                                                'type'        => 'select',
                                                'class'       => 'form-control',
                                                'options'     => $banks,
                                                'placeholder' => __('Selecciona un Banco'),
                                                'id'          => 'bank_id',
                                                'label'       => __('Banco')
                                        ));
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('bank_id'); 
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.bank_id);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        $types = [1 => __('Ahorros'), 2 => __('Corriente')];
                                        echo $this->Form->input(
                                            'Recipient.bank_account_type',
                                            array(
                                                'type'        => 'select',
                                                'class'       => 'form-control',
                                                'options'     => $types,
                                                'placeholder' => __('Selecciona un tipo de cuenta'),
                                                'id'          => 'bank_account_type',
                                                'label'       => __('Tipo de Cuenta'),
                                                'default'     => 2
                                        ));
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('bank_account_type'); 
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.bank_account_type);
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php 
                                        echo $this->Form->input(
                                            'Recipient.bank_account_number',
                                            array(
                                                'type'                => 'text',
                                                'class'               => 'form-control',
                                                'id'                  => 'bank_account_number',
                                                'div'                 => false,
                                                'label'               => __('Número de Cuenta'),
                                                'minlength'           => 20,
                                                'maxlength'           => 20,
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'tax_id'
                                        ));
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('bank_account_number'); 
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.bank_account_number);
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        $status = [1 => 'Activo', 2 => 'Inactivo'];
                                        echo $this->Form->input(
                                            'Recipient.status',
                                            array(
                                                'type'        => 'select',
                                                'class'       => 'form-control',
                                                'options'     => $status,
                                                'placeholder' => 'Status',
                                                'id'          => 'status',
                                                'label'       => __('Estado'),
                                                'onchange'    => 'ShowField()'
                                        ));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-actions">
                                    <?php 
                                        echo $this->Form->Submit(
                                            __('Aceptar'),
                                            array(
                                                'class'=>'btn btn-primary pull-right'
                                        ));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>