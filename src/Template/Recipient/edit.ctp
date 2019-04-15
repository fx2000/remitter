<div class="breadcrumb">
    <?php
        echo $this->Html->link(
            'Inicio / ',
            array(
                'controller' => 'cpanel',
                'action'     => 'home'
            )
        );
    ?>
    <?php
        echo $this->Html->link(
            'Clientes / ',
            array(
                'controller' => 'user',
                'action'     => 'index',
                5
            )
        );
    ?>
    <?php
        echo $this->Html->link(
            'Modificar Beneficiario',
            array(
                'controller' => 'recipient',
                'action'     => 'edit',
                $this->request->params['pass'][0]
            )
        );
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
        <h4><i class="fas fa-users"></i><?php echo __(' Modificar Beneficiario'); ?></h4>
        </div>
        <div class="box-content">
            <?php echo $this->Form->create('',['class'=>'form-horizontal']);?>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo __('Primer Nombre'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.fname1',
                                                    array(
                                                        'type'                => 'text',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'name',
                                                        'div'                 => false,
                                                        'label'               => false,
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
                                            <label><?php echo __('Segundo Nombre'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.fname2',
                                                    array(
                                                        'type'                => 'text',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'name',
                                                        'div'                 => false,
                                                        'label'               => false,
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
                                            <label><?php echo __('Primer Apellido'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.lname1',
                                                    array(
                                                        'type'                => 'text',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'name',
                                                        'div'                 => false,
                                                        'label'               => false,
                                                        'maxlength'           => 100,
                                                        'data-rel'            => 'tooltip',
                                                        'data-original-title' => 'Name'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo __('Segundo Apellido'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.lname2',
                                                    array(
                                                        'type'                => 'text',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'name',
                                                        'div'                 => false,
                                                        'label'               => false,
                                                        'maxlength'           => 100,
                                                        'data-rel'            => 'tooltip',
                                                        'data-original-title' => 'Name'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo __('Cédula'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.tax_id',
                                                    array(
                                                        'type'                => 'text',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'tax_id',
                                                        'div'                 => false,
                                                        'label'               => false,
                                                        'maxlength'           => 100,
                                                        'data-rel'            => 'tooltip',
                                                        'data-original-title' => 'tax_id'
                                                    )
                                                );
                                            ?>
                                            <script language="javascript" type="text/javascript">
                                                var f1 = new LiveValidation('email');
                                                f1.add(Validate.Presence); 
                                                f1.add(Validate.tax_id);
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo __('Pasaporte'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.passport',
                                                    array(
                                                        'type'                => 'text',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'tax_id',
                                                        'div'                 => false,
                                                        'label'               => false,
                                                        'maxlength'           => 100,
                                                        'data-rel'            => 'tooltip',
                                                        'data-original-title' => 'passport'
                                                    )
                                                );
                                            ?>
                                            <script language="javascript" type="text/javascript">
                                                var f1 = new LiveValidation('email');
                                                f1.add(Validate.Presence); 
                                                f1.add(Validate.passport);
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo __('Email'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.email',
                                                    array(
                                                        'type'                => 'email',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'email',
                                                        'div'                 => false,
                                                        'label'               => false,
                                                        'maxlength'           => 30,
                                                        'data-rel'            => 'tooltip',
                                                        'data-original-title' => 'Email'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo __('Teléfono Celular'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.mobile_phone',
                                                    array(
                                                        'type'                => 'number',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'mobile_phone',
                                                        'div'                 => false,
                                                        'label'               => false,
                                                        'maxlength'           => 15,
                                                        'data-rel'            => 'tooltip',
                                                        'data-original-title' => 'Mobile Phone'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo __('País'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.country_id',
                                                    array(
                                                        'type'        => 'select',
                                                        'class'       => 'form-control',
                                                        'options'     => $countries,
                                                        'placeholder' => 'País',
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
                                            <label><?php echo __('Banco'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.bank_id',
                                                    array(
                                                        'type'        => 'select',
                                                        'class'       => 'form-control',
                                                        'options'     => $banks,
                                                        'placeholder' => 'Banks',
                                                        'id'          => 'bank_id',
                                                        'label'       => false,
                                                        'onchange'    => 'ShowField()'
                                                ));
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo __('Tipo de Cuenta'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.bank_account_type',
                                                    array(
                                                        'type'        => 'select',
                                                        'class'       => 'form-control',
                                                        'options'     => $account_types,
                                                        'placeholder' => 'Banks',
                                                        'id'          => 'bank_account_type',
                                                        'label'       => false,
                                                        'onchange'    => 'ShowField()'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo __('Número de Cuenta'); ?></label>
                                            <?php
                                                echo $this->Form->input(
                                                    'Recipient.bank_account_number',
                                                    array(
                                                        'type'                => 'number',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'home_phone',
                                                        'div'                 => false,
                                                        'label'               => false,
                                                        'maxlength'           => 15,
                                                        'data-rel'            => 'tooltip',
                                                        'data-original-title' => 'Home Phone'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <?php 
                                                $status = [1 => __('Activo'), 2 => __('Inactivo')];
                                                echo $this->Form->input(
                                                    'User.status',
                                                    array(
                                                        'type'        => 'select',
                                                        'class'       => 'form-control',
                                                        'options'     => $status,
                                                        'placeholder' => __('Estado'),
                                                        'id'          => 'estado',
                                                        'label'       => __('Estado'),
                                                        'onchange'    => 'ShowField()'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-actions">
                                            <?php
                                                echo $this->Form->Submit(
                                                    __('Guardar'),
                                                    array(
                                                        'class' => 'btn btn-primary pull-right'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>