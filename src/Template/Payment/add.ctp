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
            __('Mis Retiros'),
            array(
                'controller' => 'payment',
                'action'     => 'index'
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

<div class="box-content">
    <div>
        <p><?= $this->request->session()->read('fname1') ?>, en el Paso 1 a la izquierda puedes especificar el monto del retiro que quieres realizar, una vez que hayas definido un monto, puedes colocar la información relevante a la transferencia en el Paso 2 a la derecha.</p>
        <p>No olvides que el monto de tu retiro nunca puede ser mayor a tu saldo disponible. Todos los retiros pasan por un proceso de aprobación y transferencia que puede tomar hasta 48 horas luego de la solcitud.</p>
    </div>
</div>

<div class="row-fluid ">

    <div class="box well span12">
        <div class="box-header well" data-original-title>
            <div class="row">
                <div class="col-sm-4 py-2">
                    <div class="card card-user h-100">
                        <div class="card-image" style="height:55px;">
                            <?php
                                echo $this->Html->image('currency.jpg', ['alt' => 'Panamá']);
                            ?>
                        </div>
                        <div class="author" style="margin-top:-25px;size:155px;">
                            <a href="#">
                                <div>
                                    <?php 
                                        echo $this->Html->image('number1.png', 
                                            [
                                                'alt' => __('Panamá'), 'class'=>'avatar border-gray',
                                            'style' => 'height:85px;width:85px'
                                            ]
                                        );
                                    ?>
                                    <h5 class="title"><?php echo __('Confirma el Saldo a Retirar'); ?></h5>
                                    <h6 class="title">
                                        <?php
                                            $balance = $this->Number->currency($accountInvestor[0]->balance);
                                            echo (' <div class="ml-auto"><i class="fas fa-money-bill-alt"></i> ' . __('Saldo disponible: ') . $balance . '</div>');
                                        ?>
                                    </h6>
                                </div>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table">
                                <table>
                                    <?php 
                                        echo $this->Form->create();
                                        echo $this->Form->input(
                                            'Payment.amount',
                                            array(
                                                'type'        => 'text',
                                                'class'       => 'form-control',
                                                'placeholder' => 'Ingresa el Monto a Retirar',
                                                'id'          => 'amount',
                                                'style'       => 'text-align:center;',
                                                'label'       => false
                                            )
                                        );
                                    ?>
                                    <script language="javascript" type="text/javascript">
                                        var f1 = new LiveValidation('amount');
                                        f1.add(Validate.Presence); 
                                        f1.add(Validate.NumberValidFloat);
                                    </script>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 py-2">
                    <div class="card card-user h-100">
                        <div class="card-image" style="height:55px;">
                            <?php
                                echo $this->Html->image('currency.jpg', ['alt' => 'Panamá']);
                            ?>
                        </div>
                        <div class="author" style="margin-top:-25px;size:155px;">
                            <a href="#">
                                <?php 
                                    echo $this->Html->image('number2.png', 
                                        [
                                            'alt' => __('Panamá'), 'class'=>'avatar border-gray',
                                        'style' => 'height:85px;width:85px'
                                        ]
                                    );
                                ?>
                                <h5 class="title"><?php echo __('Confirma los Datos de La Transferencia'); ?></h5>
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <?php
                                                echo $this->Form->input(
                                                    'Payment.titular',
                                                    array(
                                                        'type'                => 'text',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'titular',
                                                        'div'                 => false,
                                                        'label'               => __('Nombre del Beneficiario'),
                                                        'maxlength'           => 100,
                                                        'data-rel'            => 'tooltip',
                                                        'data-original-title' => 'titular'
                                                    )
                                                );
                                            ?>
                                            <script language="javascript" type="text/javascript">
                                                var f1 = new LiveValidation('titular');
                                                f1.add(Validate.Presence);
                                            </script>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <?php
                                                echo $this->Form->input(
                                                    'Payment.bank_account_number',
                                                    array(
                                                        'type'                => 'text',
                                                        'class'               => 'form-control',
                                                        'id'                  => 'bank_account_number',
                                                        'div'                 => false,
                                                        'label'               => __('Número de Cuenta'),
                                                        'maxlength'           => 100,
                                                        'data-rel'            => 'tooltip',
                                                        'data-original-title' => 'bank_account_number'
                                                    )
                                                );
                                            ?>
                                            <script language="javascript" type="text/javascript">
                                                var f1 = new LiveValidation('bank_account_number');
                                                f1.add(Validate.Presence);
                                            </script>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <?php
                                                echo $this->Form->input(
                                                    'Payment.bank_account_type',
                                                    array(
                                                        'type'        => 'select',
                                                        'class'       => 'form-control',
                                                        'options'     => $bank_account_type,
                                                        'placeholder' => __('Seleccionar'),
                                                        'id'          => 'bank_account_type',
                                                        'label'       => __('Tipo de Cuenta'),
                                                        'onchange'    => 'ShowField()'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <?php
                                                echo $this->Form->input(
                                                    'Payment.bank_id',
                                                    array(
                                                        'type'        => 'select',
                                                        'class'       => 'form-control',
                                                        'options'     => $banks,
                                                        'placeholder' => __('Seleccionar'),
                                                        'id'          => 'bank_id',
                                                        'label'       => __('Banco'),
                                                        'onchange'    => 'ShowField()'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="form-actions">
                                <div class="card-footer">
                                    <?php
                                        echo $this->Form->Submit(
                                            __('Solicitar'),
                                            array(
                                                'class' => 'btn btn-primary pull-right'
                                            )
                                        );
                                    ?>
                                </div>
                                <div id="countdown_text" style="font-size: 16px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>