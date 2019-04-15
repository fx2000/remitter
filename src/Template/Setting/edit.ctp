<?php 
    $user_type = $this->request->session()->read('user_type');
    if ($this->request->session()->read('alert') != '') {
?>
<div class="alert <?php echo ($this->request->session()->read('success')==1)?'alert-success':'alert-error'?>">
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
            __('Configuración /'),
            array(
                'controller' => 'setting',
                'action'     => 'index'
            )
        );
    ?>
</div>
<div class="row-fluid ">
    <div class="box span12">
        <div class="box-content">
            <?php
                echo $this->Form->create(
                    '',[
                        'class'=>'form-horizontal'
                    ]
                );
            ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <?php
                            echo "<h4><i class='fas fa-money-bill-alt'></i>" . __(' Editar Impuestos, Tasas y Tarifas') . "</h4>";
                        ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'Setting.country_id',
                                            array(
                                                'type'        => 'select',
                                                'class'       => 'form-control',
                                                'id'          => 'country',
                                                'options'     => $countries,
                                                'label'       => 'País',
                                                'placeholder' => 'Selecciona un país'
                                            )
                                        );
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'Setting.tax',
                                            array(
                                                'type'                => 'float',
                                                'class'               => 'form-control',
                                                'id'                  => 'tax',
                                                'div'                 => false,
                                                'label'               => 'ITBMS',
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'ITBMS'
                                            )
                                        );
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'Setting.sale_rate',
                                            array(
                                                'type'=>'float',
                                                'class'=>'form-control',
                                                'id'=>'rate',
                                                'div'                 => false,
                                                'label'               => 'Tasa para Inversionistas',
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Tasa para Inversionistas'
                                            )
                                        );
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'Setting.purchase_rate',
                                            array(
                                                'type'                => 'float',
                                                'class'               => 'form-control',
                                                'id'                  => 'rate',
                                                'div'                 => false,
                                                'label'               => 'Tasa para Clientes',
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Tasa para Clientes'
                                            )
                                        );
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <?php
                                        echo $this->Form->input(
                                            'Setting.fee',
                                            array(
                                                'type'                => 'float',
                                                'class'               => 'form-control',
                                                'id'                  => 'fee',
                                                'div'                 => false,
                                                'label'               => 'Tarifa de envío',
                                                'data-rel'            => 'tooltip',
                                                'data-original-title' => 'Tarifa de envío'
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
                                                'class'=>'btn btn-primary pull-right'
                                            )
                                        );
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
                    