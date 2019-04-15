<?php //debug($crt_control);
    $user_type = $this->request->session()->read('user_type');
?>

<style>
    .card {
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
        width: 40%;
        margin: 0 auto;
        float: none;
        margin-bottom: 20px;
        align-items: center;
        justify-content: center;
        text-align:center;
        display: inline-block;
        vertical-align: middle;
    }
    .card .fas {
        margin-top: 10px;
    }
    .card .title {
        margin-top: 10px;
    }
</style>

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

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <?php
                if ($user_type == 1 || $user_type == 2 || $user_type == 3 || $user_type == 6 || $user_type == 7) {
                    echo ("
                    <div class='card col-md-2'>
                        ");
                    echo $this->Html->link(
                        "<i class='fas fa-paper-plane fa-2x'></i><h5 class='title'>" . __('Crear Remesa') . "</h5>",
                        array(
                            'controller' => 'Remittance',
                            'action'     => 'add'
                        ),
                        array(
                            'data-rel'   => 'tooltip',
                            'title'      => __('Crear Remesa'),
                            'escape'     => false
                        )
                    );
                    echo ("
                    </div>
                    <div class='card col-md-2'>");
                    echo $this->Html->link(
                        "<i class='fas fa-user fa-2x'></i><h5 class='title'>" . __('Crear Cliente') . "</h5>",
                        array(
                            'controller' => 'User',
                            'action'     => 'add',
                            5
                        ),
                        array(
                            'data-rel'   => 'tooltip',
                            'title'      => __('Crear Cliente'),
                            'escape'     => false
                        )
                    );
                    echo ("
                    </div>
                    <div class='card col-md-2'>");
                    echo $this->Html->link(
                        "<i class='fas fa-briefcase fa-2x'></i><h5 class='title'>" . __('Crear Inversionista') . "</h5>",
                        array(
                            'controller' => 'User',
                            'action'     => 'add',
                            4
                        ),
                        array(
                            'data-rel'   => 'tooltip',
                            'title'      => __('Crear Inversionista'),
                            'escape'     => false
                        )
                    );
                    echo ("
                    </div>
                    <div class='card col-md-2'>");
                    echo $this->Html->link(
                        "<i class='fas fa-money-bill-alt fa-2x'></i><h5 class='title'>" . __('Aplicar Pago') . "</h5>",
                        array(
                            'controller' => 'Payment',
                            'action'     => 'index'
                        ),
                        array(
                            'data-rel'   => 'tooltip',
                            'title'      => __('Aplicar Pago'),
                            'escape'     => false
                        )
                    );
                    echo ("
                    </div>
                    ");
                } else if ($user_type == 4) {
                    echo ("
                        <div class='card col-md-5'>
                            <div class='card-header'>");
                                foreach ($accounBalance as $ab) {
                                    echo ("<h4 class='card-title'>" . $this->Number->currency($ab->tmp_balance) . "</h4>");
                                }
                                //echo ("<i class='fas fa-money-bill-alt fa-2x'></i>");
                                echo "<p class='card-category'>" . __('Fondos en Tránsito') . "</p><br>";
                    echo ("</div>
                        </div>
                        <div class='card col-md-5'>
                            <div class='card-header'>");
                                foreach ($accounBalance as $ab) {
                                    echo ("<h4 class='card-title'>" . $this->Number->currency($ab->balance) . "</h4>");
                                }
                                //echo ("<i class='fas fa-money-bill-alt fa-2x'></i>");
                                echo "<p class='card-category'>" . __('Saldo Disponible') . "</p><br>";
                    echo ("</div>
                        </div>");
                }
            ?>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
        <?php
            if ($user_type == 4) {
        ?>
                <div class='card col-md-5'>
                    <div class='card-header'>
                        <h5 class='card-title'>Remesas</h5>
                        <p class='card-category'>Operaciones del día</p>
                    </div>
                    <div class='card-body'>
                        <div id='chartPreferences' class='ct-chart ct-perfect-fourth' style="text-align:left">
                            <p><i class='fa fa-circle text-danger'></i> Disponibles: <?php echo $dispRem; ?></p>
                            <p><i class='fa fa-circle text-warning'></i> Reservadas: <?php echo $reseRemInv; ?></p></p>
                            <p><i class='fa fa-circle text-info'></i> En Verificación: <?php echo $veriRemInv; ?></p></p>
                            <p><i class='fa fa-circle text-success'></i> Completadas: <?php echo $comRemInv; ?></p></p>
                        </div>
                    </div>
                    <div class='card-footer'>
                        <div class="legend" style="font-size: 11px;"><!--
                            <i class="fa fa-circle text-danger"></i><?php echo __(' Disponible'); ?>
                            <i class="fa fa-circle text-warning"></i><?php echo __(' Reservada'); ?>
                            <i class="fa fa-circle text-info"></i><?php echo __(' En Verificación'); ?>
                            <i class="fa fa-circle text-success"></i><?php echo __(' Completada'); ?>-->
                        </div>
                        <hr>
                        <div class='stats'>
                            <i class='fa fa-clock-o'></i> <?php echo date('Y-m-d h:i:s A'); ?>
                        </div>
                    </div>
                </div>
        <?php
            } else {
        ?>
                <div class='card col-md-5'>
                    <div class='card-header'>
                        <h5 class='card-title'>Operaciones del día</h5>
                    </div>
                    <div class='stats'>
                            <i class='fa fa-clock-o'></i> <?php echo date('Y-m-d h:i:s A'); ?>
                        </div>
                    <div class='card-body'>
                        <div id='chartPreferences' class='ct-chart ct-perfect-fourth' style="text-align:left">
                            <p><i class='fa fa-circle text'></i> Total Transacciones: <?php echo $totalRem; ?></p>
                            <p><i class='fa fa-circle text'></i> Monto en Remesas: <?php echo '$' . number_format($montoRem[0]['sum'], 2); ?></p>
                            <p><i class='fa fa-circle text'></i> Monto en Tarifas: <?php echo '$' . number_format($montoTot[0]['sum'] - $montoRem[0]['sum'], 2); ?></p>
                            <p><i class='fa fa-circle text'></i> Total Efectivo: <?php echo '$' . number_format($montoCash[0]['sum'], 2); ?></p>
                            <p><i class='fa fa-circle text'></i> Total ACH: <?php echo '$' . number_format($montoAch[0]['sum'], 2); ?></p>
                            <p><i class='fa fa-circle text'></i> Total Punto Pago: <?php echo '$' . number_format($montoPuntopago[0]['sum'], 2); ?></p>
                            <p><i class='fa fa-circle text'></i><b> Total Captado: <?php echo '$' . number_format($montoTot[0]['sum'], 2); ?></b></p>
                            <p><i class='fa fa-circle text-danger'></i> Disponibles: <?php echo $dispRem; ?></p>
                            <p><i class='fa fa-circle text-warning'></i> Reservadas: <?php echo $reseRem; ?></p></p>
                            <p><i class='fa fa-circle text-info'></i> En Verificación: <?php echo $veriRem; ?></p></p>
                            <p><i class='fa fa-circle text-success'></i> Completadas: <?php echo $comRem; ?></p></p>
                        </div>
                    </div>
                </div>
        <?php
            }
        ?>
            <div class="card col-md-5">
                <div class="card-header ">
                    <?php
                        if ($user_type == 4) {
                            echo '<h5 class="card-title">' . __('Mis Retiros') . '</h5>';
                        } else {
                            echo __('<h5 class="card-title">Retiros</h5>');
                        }
                    ?>
                    <p class="card-category"><?php echo __('Últimos retiros'); ?></p>
                </div>
                <div class="card-body ">
                    <div id="chartHours" class="ct-chart"></div>
                </div>
                <div class="card-footer">
                    <div class="legend" style="font-size: 11px;">
                        <i class="fa fa-circle text-info"></i><?php echo __(' Pendiente'); ?>
                        <i class="fa fa-circle text-success"></i><?php echo __(' Aprobado'); ?>
                        <i class="fa fa-circle text-danger"></i><?php echo __(' Rechazado'); ?>
                    </div>
                    <hr>
                    <div class="stats">
                        <i class="fa fa-history"></i><?php echo __(' Actualizado hace 3 minutos'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
