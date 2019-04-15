<?php 
    $user_type = $this->request->session()->read('user_type');
    if ($this->request->session()->read('alert') != '') {
?>

    <div class="alert <?php echo ($this->request->session()->read('success')==1)?'alert-success':'alert-danger'?>">
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
            __('Pagos'),
            array(
                'controller' => 'payments',
                'action'     => 'index'
            )
        );
    ?>
</div>

<div class="row-fluid ">
    <div class="box well span12">
        <div class="box-header well" data-original-title>
            <div class="row">
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
	                                foreach($investor AS $i) {
	                                    echo ('<tr><td>' . __('Inversionista') . '</td><td>' . $i->fname1 . ' ' . $i->lname1 . '</td></tr>');
	                                    echo ('<tr><td>' . __('Cédula') . '</td><td>' . $i->tax_id . '</td></tr>');
	                                    echo ('<tr><td>' . __('Pasaporte') . '</td><td>' . $i->passport . '</td></tr>');
	                                }
	                                foreach($country AS $c) {
	                                    echo ('<tr><td>' . __('País') . '</td><td>' . $c->name . '</td></tr>');
	                                }
	                                foreach($bank AS $b) {
	                                    echo ('<tr><td>' . __('Banco') . '</td><td>' . $b->name . '</td></tr>');
	                                }
	                                foreach($type AS $t) {
	                                    echo ('<tr><td>' . __('Tipo de Cuenta') . '</td><td>' . $t->name . '</td></tr>');
	                                }
	                                foreach($payment AS $p) {
	                                    echo ('<tr><td>' . __('Número de Cuenta') . '</td><td>' . $p->bank_account_number . '</td></tr>');
	                                }
	                                foreach($payment AS $p) {
	                                    echo ('<tr><td>' . __('Monto') . '</td><td>' . '$' . number_format($p->amount, 2) . '</td></tr>');
	                                }
	                            ?>
                            </table>
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
                                <h5 class="title"><?php echo __('Confirma la Transacción'); ?></h5>
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table">
                            	<?php echo $this->Form->create();?>
	                            <?php
	                                echo $this->Form->input(
	                                    'Payment.descriptions',
	                                    array(
	                                        'type'        => 'text',
	                                        'class'       => 'form-control',
                                            'id'          => 'descriptions',
	                                        'maxlength'   => 200,
	                                        'placeholder' => __('Código de Transacción'),
	                                        'label'       => __('Código de Transacción'),
	                                        'onchange'    => 'ShowField()'
	                                    )
	                                );
	                            ?>
	                            <br>
	                            <?php
	                                $status = [1 => __('Pendiente'), 2 => __('En Verificación'), 3 => __('Finalizado')];
	                                echo $this->Form->input(
	                                    'Payment.status',
	                                    array(
	                                        'type'        => 'select',
	                                        'class'       => 'form-control',
	                                        'options'     => $status,
	                                        'placeholder' => __('Selecciona un Estado'),
	                                        'id'          => 'status',
	                                        'label'       => __('Estado'),
                                            'onchange'    => 'ShowField()'
	                                    )
	                                );
	                            ?>
                            </table>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>