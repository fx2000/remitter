<?php 
    $user_type = $this->request->session()->read('user_type');
    $user_name = $this->request->session()->read('fname1') . ' ' . $this->request->session()->read('lname1');
    if ($this->request->session()->read('alert') != '') {
?>

<div class="alert <?php echo ($this->request->session()->read('success')==1)?'alert-success':'alert-error'?>">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>
        <?php 
            echo $this->request->session()->read('alert');
            $_SESSION['alert'] = '';
        ?>
    </strong>
</div>

<?php } ?>

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
            __('Pagos'),
            array(
                'controller' => 'remittance',
                'action'     => 'index'
            )
        );
    ?>
</div>

<div class="row-fluid">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-paper-plane"></i><?php echo __(' Pagos por Mes'); ?></h4>
        </div>
        <div class="box-content">
            <p>Por favor selecciona el mes y año del reporte a generar</p>
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
                                        $year = [
                                            2018 => __('2018'), 2019 => __('2019')
                                        ];
                                        echo $this->Form->input(
                                            'year',
                                            array(
                                                'type'        => 'select',
                                                'class'       => 'form-control',
                                                'options'     => $year,
                                                'placeholder' => __('Selecciona un Año'),
                                                'id'          => 'year',
                                                'label'       => 'Año',
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
                                        $month = [
                                            1  => __('Enero'),
                                            2  => __('Febrero'),
                                            3  => __('Marzo'),
                                            4  => __('Abril'),
                                            5  => __('Mayo'),
                                            6  => __('Junio'),
                                            7  => __('Julio'),
                                            8  => __('Agosto'),
                                            9  => __('Septiembre'),
                                            10 => __('Octubre'),
                                            11 => __('Noviembre'),
                                            12 => __('Diciembre'),
                                        ];
                                        echo $this->Form->input(
                                            'month',
                                            array(
                                                'type'        => 'select',
                                                'class'       => 'form-control',
                                                'options'     => $month,
                                                'placeholder' => __('Selecciona un Mes'),
                                                'id'          => 'month',
                                                'label'       => 'Mes',
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
                                            __('Aceptar'),
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

