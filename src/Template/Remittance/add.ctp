<style>
    .form-control{
        font-size: 12px;
    }
    .card .description{
        font-size: 12px;
    }
    .form-group{
        flex: 1;
    }
</style>

<script>
    function getval1(sel)
    {
        loadCustomer();
    }

    function loadCustomer()
    {
        var path='<?php echo $baseUrl?>/remittance/get_customer/'+$('#customer').val();
        var request = $.ajax({
            url: path
        });
        request.done(function (response, textStatus, jqXHR){ //alert(response);
            $('#client_div').show();
            $('#recipient_div').hide();
            $('#recipients_div').hide();
            $('#amount_div').hide();
            var res = JSON.parse(response);
            $('#name').empty();
            $('#tax').empty();
            // $('#name').append(res.fname1+" "+res.fname2+" - "+res.tax_id); 
            $('#tax').append("<br><b>Documento: </b>"+res.tax_id);
            if (res.photo_dir != null || res.photo != null) {
                $('#img').empty();
                var imgPath='../webroot/img/users/photo/' + res.photo_dir + '/' + res.photo;
                $("#img").attr("src",imgPath);
                $("#cedula").attr("href",imgPath);
            } else {
                $('#img').empty();
                var imgPath='../webroot/img/id_card_placeholder.png';
                $("#img").attr("src",imgPath);
            }
            loadRecipients(res.id);
        });
        request.fail(function (jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
        });
    }

    function loadRecipients($id)
    {
        var path='<?php echo $baseUrl?>/remittance/get_recipients/'+$id;
        var request = $.ajax({
            url: path
        });
        request.done(function (response, textStatus, jqXHR){ //alert(response);
            $('#recipients_div').show();
            $('#sel1').empty();
            $('#sel1').append(response); 
        });
        request.fail(function (jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
        });
    }

    function getval2(sel)
    {
        loadRecipient();
    }

    function loadRecipient($id)
    {
        var path='<?php echo $baseUrl?>/remittance/get_recipient/'+$('#sel1').val();
        var request = $.ajax({
            url: path
        });
        request.done(function (response, textStatus, jqXHR){ //alert(response);
            $('#recipient_div').show();
            $('#amount_div').show();    
            var rec = JSON.parse(response);
            $('#tax_r').empty();
            $('#country_r').empty();
            $('#bank_r').empty();
            $('#account_type_r').empty();
            $('#account_r').empty();
            $('#tax_r').val('<b>Cédula: </b>'+rec.tax_id);
            $('#country_r').append('<b>País: </b>'+rec.country);
            $('#bank_r').append('<b>Banco: </b>'+rec.bank);
            $('#account_type_r').append('<b>Tipo de Cuenta: </b>'+rec.account_type);
            $('#account_r').append('<b>Número de Cuenta: </b>'+rec.bank_account_number);
        });
        request.fail(function (jqXHR, textStatus, errorThrown){
            console.log(jqXHR);
        });

        $(function getTotalAmount() {
            $('#amountBase').change(function() {
                var path='<?php echo $baseUrl?>/remittance/get_settings';
                var request = $.ajax({
                    url: path
                });
                request.done(function (response, textStatus, jqXHR){
                    var rer = JSON.parse(response); 
                    var amount = parseFloat($('#amountBase').val());
                    var fee = parseFloat(rer.fee);
                    var tax = (parseFloat(rer.tax)*fee)/100;
                    var total = amount+(fee-tax)+tax;
                    var purchase_rate = parseFloat(rer.purchase_rate);
                    var amounTransfer = amount*purchase_rate;
                    $('#fee').val((fee-tax).toFixed(2));
                    $('#itbms').val(tax);
                    $('#totalAmount').val(total);
                    $('#purchaseRate').val(purchase_rate);
                    $('#amountTransfer').val(amounTransfer);
                    $('#btn_div').show();  
                });
                request.fail(function (jqXHR, textStatus, errorThrown){
                    console.log(jqXHR);
                });
            });
        }); 
    }
</script>

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
            __('Remesas / '),
            array(
                'controller' => 'remittance',
                'action'     => 'index'
            )
        );
    ?>
    <?php
        echo $this->Html->link(
            __('Crear Remesa'),
            array(
                'controller' => 'remittance',
                'action'     => 'add'
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

<div class="pull-right">
    <?php
        $rate = $this->Number->currency($settings[0]->purchase_rate, 'BsF');
        echo ('<div class="pull-right"><h4><i class="fas fa-money-bill-alt"></i> ' . __('Tasa efectiva: ') . $rate . '</h4></div>');
    ?>
</div>

<div class="row-fluid ">        
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-paper-plane"></i><?php echo __(' Crear Remesa'); ?></h4>
        </div>
    </div>
    <div class="box well span12">
        <div class="box-header well" data-original-title>
            <?php
                echo $this->Form->create('',[
                    'type'  => 'file',
                    'class' => 'form-horizontal'
                ]);
            ?>
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
                                    <h5 class="title"><?php echo __('Cliente'); ?></h5>
                                </div>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <?php
                                    echo $this->Form->input(
                                        'Remittance.client_id',
                                        array(
                                            'type'        => 'select',
                                            'class'       => 'form-control',
                                            'options'     => $clients,
                                            'placeholder' => __('Clientes'),
                                            'label'       => false,
                                            'id'          => 'customer',
                                            'onchange'    => 'getval1(this)',
                                            'empty'       => __('Selecciona un cliente')
                                        )
                                    );
                                ?>
                            </div>
                            <div id="client_div" style="display:none;">
                                <!-- <p class="description" id="name"></p> -->
                                <p class="description" id="tax"></p>
                                <div style="align:center;">

                                    <a href="#" id="cedula"
                                        onclick="return !window.open(this.href, 'width=500,height=500')"
                                        target="_blank"
                                    >
                                    <img id="img" src="" width="260"/></a>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 py-2" id="recipients_div" style="display:none;">
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
                                <h5 class="title"><?php echo __('Beneficiario'); ?></h5>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <?php
                                    echo $this->Form->input(
                                        'Remittance.recipient_id',
                                        array(
                                            'type'        => 'select',
                                            'class'       => 'form-control',
                                            'placeholder' => __('Beneficiario'),
                                            'label'       => false,
                                            'id'          => 'sel1',
                                            'onchange'    => 'getval2(this)'
                                        )
                                    );
                                ?>
                            </div>
                            <div id="recipient_div" style="display:none; margin-bottom:40px;">
                                <a href="#">
                                    <h5 class="title" id="name_r"></h5>
                                </a>
                                <p class="description" id="tax_r"></p>
                                <p class="description" id="country_r"></p>
                                <p class="description" id="bank_r"></p>
                                <p class="description" id="account_type_r"></p>
                                <p class="description" id="account_r"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 py-2" id="amount_div" style="display:none;">
                    <div class="card card-user h-100">
                        <div class="card-image" style="height:55px;">
                            <?php
                                echo $this->Html->image('currency.jpg', ['alt' => 'Panamá']);
                            ?>
                        </div>
                        <div class="author" style="margin-top:-25px;size:155px;">
                            <a href="#">
                                <?php 
                                    echo $this->Html->image('number3.png', 
                                        [
                                            'alt' => __('Panamá'), 'class'=>'avatar border-gray',
                                        'style' => 'height:85px;width:85px'
                                        ]
                                    );
                                ?>
                                <h5 class="title"><?php echo __('Monto'); ?></h5>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'Remittance.amount',
                                                array(
                                                    'type'  => 'number',
                                                    'class' => 'form-control',
                                                    'id'    => 'amountBase',
                                                    'style' => 'text-align:center;',
                                                    'label' => __('Monto de la remesa')
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'Remittance.fee',
                                                array(
                                                    'type'  => 'number',
                                                    'class' => 'form-control',
                                                    'id'    => 'fee', 
                                                    'style' => 'text-align:center;color:#222',
                                                    'label' => __('Cargo por transacción'),
                                                    'readonly' => 'readonly'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'Remittance.tax',
                                                array(
                                                    'type'  => 'number',
                                                    'class' => 'form-control',
                                                    'id'    => 'itbms', 
                                                    'style' => 'text-align:center;color:#222',
                                                    'label' => __('ITBMS'),
                                                    'readonly' => 'readonly'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'Remittance.amount_payed',
                                                array(
                                                    'type'  => 'number',
                                                    'class' => 'form-control',
                                                    'id'    => 'totalAmount', 
                                                    'style' => 'text-align:center;color:#222',
                                                    'label' => __('Monto a pagar'),
                                                    'readonly' => 'readonly'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'Remittance.purchase_rate',
                                                array(
                                                    'type'  => 'number',
                                                    'class' => 'form-control',
                                                    'id'    => 'purchaseRate', 
                                                    'style' => 'text-align:center;color:#222',
                                                    'label' => __('Tasa efectiva'),
                                                    'readonly' => 'readonly'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                            echo $this->Form->input(
                                                'Remittance.amount_delivered',
                                                array(
                                                    'type'  => 'number',
                                                    'class' => 'form-control',
                                                    'id'    => 'amountTransfer', 
                                                    'style' => 'text-align:center; color:#222',
                                                    'label' => __('Monto a recibir'),
                                                    'readonly' => 'readonly'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php 
                                            $type = [1 => __('Efectivo'), 2 => __('ACH'), 4 => __('Otro')];
                                            echo $this->Form->input(
                                                'Remittance.type',
                                                array(
                                                    'type'        => 'select',
                                                    'class'       => 'form-control',
                                                    'options'     => $type,
                                                    'id'          => 'type',
                                                    'label'       => __('Método de Pago'),
                                                    'onchange'    => 'ShowField()'
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <br>
                                        <span class="btn btn-file btn-xs">
                                            <?php 
                                                echo $this->Form->input(
                                                    'Remittance.ach', [
                                                        'type'     => 'file',
                                                        'class'    => 'btn btn-file',
                                                        'label'    => __('Comprobante ACH')
                                                    ]
                                                );
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mr-auto ml-auto">
                                    <div class="form-group">
                                        <div id="btn_div" class="form-actions" style="display:none;">
                                            <?php
                                                echo $this->Form->Submit(
                                                    __('Aceptar'),
                                                    array(
                                                        'class' => 'btn btn-primary'
                                                    )
                                                );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
