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
<?php } ?>
<div class="breadcrumb">
	<?php echo $this->Html->link('Inicio / ',array('controller'=>'cpanel','action'=>'home'));?>
	<?php echo $this->Html->link('Remesas',array('controller'=>'remittance','action'=>'index'));?>
</div>
<div class="box-header well" data-original-title>
    <h4><i class="fas fa-paper-plane"></i><?php echo __(' Solicitar Remesa'); ?></h4>
</div>
<div class="form-inline col-md-12" style="margin-top: 40px;">
    <div class="col-md-3">
    </div>
    <div class="card card-user col-md-6">
        <div class="card-body">
            <div class="author">
                <div class="content">
                    <div class="author">
                        <a href="#">
                            <?php
                                echo $this->Html->image(
                                    'currency.jpg', [
                                        'alt'   => __('Panamá'),
                                        'class' => 'avatar border-gray'
                                    ]
                                ); 
                            ?>
                        </a>
                    </div>
                </div>
                <div class="card-header">
                    <?php
                        echo $this->Form->create();
                        foreach ($remittance AS $r) {
                            $amount_sold = $r->amount_delivered / $settings[0]->sale_rate;
                            echo (
                                '<b><h3>' . __('Monto a Recibir') . '</h3></b><b><h3>' .
                                $this->Number->currency($amount_sold) .
                                '</h3></b>'
                            );
                        }
                            echo $this->Form->hidden(
                                'Remittance.amount_sold', 
                                ['value' => $amount_sold]
                            );
                    ?>
                </div>
                <div align="left">
                        <p><strong>IMPORTANTE:</strong></p>
                        <blockquote>
                            <p>Al presionar el botón SOLICITAR, esta remesa te será asignada y podrás ver los detalles completos de la transferencia que debes realizar para recibir este monto en tu cuenta. Tienes 60 minutos para realizar esta transferencia o de lo contrario la transacción será liberada y devuelta al pool de remesas disponibles.</p>
                            <p>Recuerda que debes especificar como Email del destinatario de la transferencia <strong>transferencias@hispanoremesas.com</strong> y tomar una captura de pantalla del resultado de la transacción, esto nos permitirá validar que la transferencia fue realizada correctamente y evitará demoras en la liberación de los fondos.</p>
                            <p>Recuerda que si necesitas ayuda en cualquier momento, puedes ponerte en contacto con nosotros a través de Whatsapp por el <strong>+507 6218-1809</strong> o por email a <strong>inversionistas@hispanoremesas.com</strong>.
                        </blockquote>
                    </div>                                    
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-actions">
                            <?php
                                echo $this->Form->Submit(
                                    __('Reservar'),
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
                    