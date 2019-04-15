x<?php 
    $user_id = $this->request->session()->read('user_id');
    $user_type = $this->request->session()->read('user_type');
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
            'Remesas Compradas',
            array(
                'controller' => 'remittance',
                'action'     => 'indexInvestor',
                base64_encode($user_id)
            )
        );
    ?>
</div>

<div class="box-content">
    <div>
        <p><?= $this->request->session()->read('fname1') ?>, en el Paso 1 a la izquierda puedes ver los datos específicos de la transferencia que debes realizar para finalizar esta transacción, una vez que hayas realizado la transferencia, puedes colocar la información relevante en el Paso 2 a la derecha.</p>
        <p>Deberás colocar el número de confirmación de la transferencia dado por el banco y una captura de pantalla en JPG, o PNG del resultado de la transacción.</p>
    </div>
</div>

<div class="row-fluid ">

    <div class="box well span12">
        <div class="box-header well" data-original-title>
            <div class="row">

                <!-- ONE -->
                <div class="col-sm-6 py-2">
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
                                    <h5 class="title"><?php echo __('Envía la Transferencia'); ?></h5>
                                </div>
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <?php
                                    foreach($remittance AS $r){
                                        echo ('<tr><td align="right">' . __('ID ' . '</td><td align="left">') .
                                            str_pad($r->id,6,'0',STR_PAD_LEFT) . '</td></tr>'); 
                                    }
                                    foreach($recipient AS $re){
                                        echo ('<tr><td align="right">' . __('Beneficiario ') . '</td><td align="left">' . $re->fname1 . ' ' . $re->fname2 . ' ' . $re->lname1 . ' ' . $re->lname2 . '</td></tr>');
                                        echo ('<tr><td align="right">' . __('Cédula o RIF ') . '</td><td align="left">' . $re->tax_id . '</td></tr>');
                                        //echo ('<tr><td align="right">' . __('Pasaporte') . '</td><td align="left">' . $re->passport . '</td></tr>');
                                    }
                                    foreach($country AS $c){ 
                                        //echo ('<tr><td align="right">' . __('País') . '</td><td align="left">' . $c->name . '</td></tr>');
                                    }
                                    foreach($bank AS $b){ 
                                        echo ('<tr><td align="right">' . __('Banco ') . '</td><td align="left">' . $b->name . '</td></tr>');
                                    }
                                    foreach($type AS $t){ 
                                        echo ('<tr><td align="right">' . __('Tipo de Cuenta ') . '</td><td align="left">' . $t->name . '</td></tr>');
                                    }
                                    foreach($recipient AS $re){ 
                                        echo ('<tr><td align="right">' . __('Número de Cuenta ') . '</td><td align="left">' . $re->bank_account_number . '</td></tr>');
                                    }
                                    foreach($remittance AS $r){
                                        echo ('<tr><td align="right"><b>' . __('Monto a Transferir ' . '</b></td><td align="left"><b>') .
                                            'BsS.' . number_format($r->amount_delivered,2,',','.') . '</b></td></tr>'); 
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>


                <!-- TWO -->
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
                                <h5 class="title"><?php echo __('Confirma la Transacción'); ?></h5>
                            </a>
                        </div>

                        <!-- FORM -->
                        <div class="card-body">
                            <?php
                                echo $this->Form->create('',[
                                    'type'  => 'file',
                                    'class' => 'form-horizontal'
                                ]);
                            ?>
                            <table class="table">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <?php
                                                echo $this->Form->textarea(
                                                    'Remittance.descriptions',
                                                    array(
                                                        'class'       => 'form-control',
                                                        'style'       => 'width:400px;',
                                                        'placeholder' => __('Número de Confirmación'),
                                                        'id'          => 'descriptions',
                                                        'required'    => true
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <br>
                                            <span class="btn btn-file btn-xs">
                                                <?php 
                                                    echo $this->Form->input(
                                                        'Remittance.photo', [
                                                            'type'     => 'file',
                                                            'class'    => 'btn btn-file',
                                                            'label'    => __('Seleccionar Archivo')
                                                        ]
                                                    );
                                                ?>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-actions">
                                            <?php
                                                echo $this->Form->Submit(
                                                    __('Confirmar'),
                                                    array(
                                                        'id'    => 'applyBtn',
                                                        'class' => 'btn btn-primary pull-right'
                                                    )
                                                );
                                            ?>
                                            <div id="countdown_text" style="font-size: 16px;"></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>