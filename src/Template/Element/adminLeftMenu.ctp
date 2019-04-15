<?php 
    $user_id = $this->request->session()->read('user_id');
    $user_type = $this->request->session()->read('user_type');
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $("#show-users").click(function(){
            $("#users").show(700);
        });
        $("#show-reports").click(function(){
            $("#reports").show(700);
        });
        $("#show-settings").click(function(){
            $("#settings").show(700);
        });
    });
</script>

<div class="sidebar" data-image=<?php echo $this->Url->image('currency.jpg'); ?> data-color="blue">
    <div class="sidebar-wrapper">
        <div class="logo">
            <table>
                <tr>
                    <td>
                        &nbsp;
                        <?php
                            echo $this->Html->image(
                                'logoColorNoShadow.png',
                                array(
                                    'width' => '40'
                                )
                            );
                        ?>
                    </td>
                    <td>
                        <br><h5><b>&nbsp;<?php echo __('Hispano</b>Remesas'); ?></h5>
                    </td>
                </tr>
            </table>  
        </div>
        <ul class="nav">
            <li>
                <?php
                    echo $this->Html->link(
                        "<i class='fas fa-tachometer-alt'></i><span> " . __('Inicio') . "</span>",
                        array(
                            'controller' => 'cpanel',
                            'action'     => 'home'
                        ),
                        array(
                            'class'      => 'nav-link',
                            'escape'     => false
                        )
                    );
                ?>
            </li>
            <li>
                <?php 
                    if ($user_type == 4) { //Inversionista
                        echo $this->Html->link(
                            "<i class='fas fa-paper-plane'></i><span>" . __('Remesas Disponibles') . "</span>",
                            array(
                                'controller' => 'remittance',
                                'action'     => 'index'
                            ),
                            array(
                                'class'      => 'nav-link',
                                'escape'     => false
                            )
                        );

                    } elseif($user_type == 1 || $user_type == 2 || $user_type == 6) { //Admin, Supervisor, Tesoreria
                        echo $this->Html->link(
                            "<i class='fas fa-paper-plane'></i><span>" . __('Remesas') . "</span>",
                            array(
                                'controller' => 'remittance',
                                'action'     => 'index'
                            ),
                            array(
                                'class'      => 'nav-link',
                                'escape'     => false
                            )
                        );
                    }
                ?>
            </li>
            <li>
                <?php 
                    if ($user_type == 4) { //Inversionista
                        echo $this->Html->link(
                            "<i class='fas fa-shopping-cart'></i><span>" . __('Remesas Compradas') . "</span>",
                            array(
                                'controller' => 'remittance',
                                'action'     => 'indexInvestor',
                                base64_encode($user_id)
                            ),
                            array(
                                'class'      => 'nav-link',
                                'escape'     => false
                            )
                        );
                    }
                ?>
            </li>
            <li>
                <?php 
                    if ($user_type == 4) { //Inversionista
                        echo $this->Html->link(
                            "<i class='fas fa-money-bill-alt'></i><span>" . __('Mis Retiros') . "</span>",
                            array(
                                'controller' => 'payment',
                                'action'     => 'indexInvestor',
                                base64_encode($user_id)
                            ),
                            array(
                                'class'  => 'nav-link',
                                'escape' => false
                            )
                        );
                    } elseif($user_type == 1 || $user_type == 2 || $user_type == 6) { //Admin, Supervisor, Tesoreria
                        echo $this->Html->link(
                            "<i class='fas fa-money-bill-alt'></i><span>" . __('Pagos') . "</span>",
                            array(
                                'controller' => 'payment',
                                'action'     => 'index'
                            ),
                            array(
                                'class'      => 'nav-link',
                                'escape'     => false
                            )
                        );
                    }
                ?>
            </li>
            
            <?php
                if ($user_type != 4 && $user_type != 5) { //Ni inversionista ni cliente
            ?>

            <li>
                <a class="nav-link" id="show-users">
                    <i class="fas fa-users"></i>
                    <p><?php echo __('Usuarios'); ?></p>
                </a>
            </li>
            <ul id ="users" style="margin-left: 15px; display: none;">
                <li>
                    <?php
                        echo $this->Html->link(
                            __("Clientes"),
                            array(
                                'controller' => 'user',
                                'action'     => 'index',
                                5
                            ),
                            array(
                                'class'      => 'nav-link',
                                'escape'     => false
                            )
                        );
                    ?> 
                </li>
                <?php
                    if($user_type == 1 || $user_type == 2 || $user_type == 6) { //Admin, Supervisor, Tesoreria
                ?>        
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __("Inversionistas"),
                                array(
                                    'controller' => 'user',
                                    'action'     => 'index',
                                    4
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <?php
                    }
                ?> 
            </ul>

            <li>
                <a class="nav-link" id="show-reports">
                    <i class="fas fa-chart-bar"></i>
                    <p><?php echo __('Reportes'); ?></p>
                </a>
            </li>
            <ul id="reports" style="margin-left: 15px; display: none;">
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Cierre de Caja'),
                                array(
                                    'controller' => 'remittance',
                                    'action'     => 'indexOpDaily',
                                    base64_encode($user_id)
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Reporte Z'),
                                array(
                                    'controller' => 'report',
                                    'action'     => 'zreport',
                                    base64_encode($user_id)
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <?php
                    if($user_type == 1 || $user_type == 2 || $user_type == 6) { //Admin, Supervisor, Tesoreria
                ?>
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Remesas por Mes'),
                                array(
                                    'controller' => 'report',
                                    'action'     => 'OpMonthQuery',
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Remesas por Trimestre'),
                                array(
                                    'controller' => 'report',
                                    'action'     => 'OpQuarterQuery',
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Remesas por Inversionista'),
                                array(
                                    'controller' => 'report',
                                    'action'     => 'OpMonthInvestorQuery',
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Ingresos por Remesa'),
                                array(
                                    'controller' => 'report',
                                    'action'     => 'revenueQuery',
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Pagos por Mes'),
                                array(
                                    'controller' => 'report',
                                    'action'     => 'payMonthQuery',
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Saldos por Inversionista'),
                                array(
                                    'controller' => 'report',
                                    'action'     => 'saldos',
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <?php
                    }
                ?> 
            </ul>
            
            <?php
                }
            ?>

            <?php
                if ($user_type == 1 || $user_type == 2) { //Admin y Supervisor
            ?>

            <li>
                <a id="show-settings" class="nav-link">
                    <i class="fas fa-cogs"></i>
                    <p><?php echo __('Configuración'); ?></p>
                </a>
            </li>
            <ul id="settings" style="margin-left: 15px; display: none;">
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Bancos'),
                                array(
                                    'controller' => 'bank',
                                    'action'     => 'index'
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Impuestos y Tasas'),
                                array(
                                    'controller' => 'setting',
                                    'action'     => 'index'
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
                <li>
                    <div>
                        <?php
                            echo $this->Html->link(
                                __('Personal'),
                                array(
                                    'controller' => 'user',
                                    'action'     => 'index',
                                    0
                                ),
                                array(
                                    'class'      => 'nav-link',
                                    'escape'     => false
                                )
                            );
                        ?>
                    <div>
                </li>
            </ul>
            <?php
                }
            ?>
        </ul>
    </div>
</div>
