<style>
    #fileupload {
        position: relative;
        overflow: hidden;
        border: 1px solid #cccccc;
        padding: 5px;
    }
    #fileupload-label {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        opacity: 0.001
    }
</style>

<?php
    if ($this->request->session()->read('alert')!='') {
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
            __('Bancos'),
            array(
                'controller' => 'bank',
                'action'     => 'index'
            )
        );
    ?>
</div>
<div class="row-fluid ">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-bank"></i><?php echo __(' Agregar Banco'); ?></h4>
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
                                                'Bank.name',
                                                array(
                                                    'type'                => 'text',
                                                    'class'               => 'form-control',
                                                    'id'                  => 'name',
                                                    'label'               => __('Nombre'),
                                                    'maxlength'           => 50,
                                                    'data-rel'            => 'tooltip',
                                                    'data-original-title' => 'Bank Name'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'Bank.country_id',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $countries,
                                                    'placeholder' => __('Selecciona un País'),
                                                    'id'          => 'country',
                                                    'label'       => 'Country',
                                                    'onchange'    => 'ShowField()',
                                                    'default'     => '232'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                            $options = [1 => __('Activo'), 2 => __('Inactivo')];
                                            echo $this->Form->input(
                                                'Bank.status',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $options,
                                                    'placeholder' => __('Selecciona un Estado'),
                                                    'id'          => 'status',
                                                    'label'       => 'Status'
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