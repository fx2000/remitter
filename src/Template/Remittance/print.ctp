<?php 
    $user_type = $this->request->session()->read('user_type');
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
            'Remesas',
            array(
                'controller' => 'remittance',
                'action'     => 'index'
            )
        );
    ?>
</div>

<div class="box-header well" data-original-title>
    <h4><i class="fas fa-paper-plane"></i><?php echo __(' Comprobante de Remesa'); ?></h4>
</div>
<div class="form-inline col-md-12" style="margin-top: 40px;">
    <div class="col-md-2"></div>
        <div class="card card-user col-md-8" id="divToPrint">
            <STYLE TYPE="text/css">
                TD{font-family: Calibri; font-size: 9pt;}
            </STYLE>
            <div class="card-body">
                <div class="author">
                    <div class="content">
                        <div class="author">
                            <table class="table" align="center">
                                <td style="text-align: center;">
                                    <?php
                                        echo $this->Html->image(
                                            'logobw.jpg', [
                                                'alt'   => __('remitter'),
                                                'class' => 'avatar border-gray',
                                                'style' => 'border-radius: 0px; border: 0px; height: 100px; width: auto; margin-top:60px;'
                                            ]
                                        );
                                    ?>
                                </td>
                                <tr><td><h1>remitter<h1></td></tr>
                                <tr><td><strong>COMPROBANTE DE REMESA</strong></td></tr>
                                <hr/>
                            </table>
                        </div>
                        <div>
                            <table class="table">
                            <hr/>
                                <?php
                                    foreach($remittance AS $re) {

                                    	$date = strtotime($re->trans_dt);

                                        $st = $re->status;
                                        echo ('<tr><td align="right"><b>' . __('Número de Operación') . '</b></td><td>' . str_pad($re->id, 6, '0', STR_PAD_LEFT) . '</td></tr>');
                                        echo ('<tr><td align="right"><b>' . __('Fecha y Hora') . '</b></td><td>' . date("Y-m-d h:i A",$date) . '</td></tr>');
                                        echo ('<tr><td align="right"><b>' . __('Monto') . '</b></td><td>' . $this->Number->currency($re->amount) . '</td></tr>');
                                        echo ('<tr><td align="right"><b>' . __('Tarifa Pagada') . '</b></td><td>' . $this->Number->currency($re->fee + $re->tax) . '</td></tr>');
                                        if ($re->payment_type == 1) {
                                            $ptype = 'Efectivo';
                                        } else if ($re->payment_type == 2) {
                                            $ptype = 'ACH';
                                        } else{
                                            $ptype = 'Otro';
                                        }
                                        echo ('<tr><td align="right"><b>' . __('Método de Pago') . '</b></td><td>' . $ptype . '</td></tr>');
                                        echo ('<tr><td align="right"><b>' . __('Tasa Efectiva') . '</b></td><td>' . 'BsS.' . number_format($re->purchase_rate, 2, '.', ',') . '</td></tr>');
                                        echo ('<tr><td align="right"><b>' . __('Monto Transferido') . '</b></td><td>' . 'BsS.' . number_format($re->amount_delivered, 2, '.', ',') . '</td></tr>');
                                    }
                                    foreach($operator AS $op) {
                                        echo ('<tr><td align="right"><b>' . __('Cajero') . '</b></td><td>' . $op->fname1 . ' ' . $op->lname1 . '</td></tr>');
                                    }
                                    foreach($remittance AS $re) {    
                                        echo ('<tr><td align="right"><b>' . __('Sucursal') . '</b></td><td>' . 'El Carmen' . '</td></tr>');
                                    }
                                     
                                    foreach($client AS $c) {
                                        echo ('<tr><td align="right"><b>' . __('Remitente') . '</b></td><td>' . $c->fname1 . ' ' . $c->lname1 . '</td></tr>');
                                        if ($c->tax_id != '') {
                                            echo ('<tr><td align="right"><b>' . __('Cédula') . '</b></td><td>' . $c->tax_id . '</td></tr>');
                                        } else {
                                            echo ('<tr><td align="right"><b>' . __('Pasaporte') . '</b></td><td>' . $c->passport . '</td></tr>');
                                        }
                                        echo ('<tr><td align="right"><b>' . __('País de Origen') . '</b></td><td>' . "Panamá" . '</td></tr>');
                                    }
                                    foreach($recipient AS $r) {
                                        echo ('<tr><td align="right"><b>' . __('Beneficiario') . '</b></td><td>' . $r->fname1 . ' ' . $r->fname2 . ' ' . $r->lname1 . ' ' . $r->lname2 . '</td></tr>');
                                        if ($r->tax_id != '') {
                                            echo ('<tr><td align="right"><b>' . __('Cédula') . '</b></td><td>' . $r->tax_id . '</td></tr>');
                                        } else {
                                            echo ('<tr><td align="right"><b>' . __('Pasaporte') . '</b></td><td>' . $r->passport . '</td></tr>');
                                        }
                                        echo ('<tr><td align="right"><b>' . __('País Destino') . '</b></td><td>' . "Venezuela" . '</td></tr>');
                                        echo ('<tr><td align="right"><b>' . __('Número de Cuenta') . '</b></td><td>' . $r->bank_account_number . '</td></tr>');
                                        if ($r->bank_account_type == 1) {
                                            $type = 'Ahorros';
                                        } else if ($r->bank_account_type == 2) {
                                            $type = 'Corriente';
                                        } else{
                                            $type = 'Otro';
                                        }
                                        echo ('<tr><td align="right"><b>' . __('Tipo de Cuenta') . '</b></td><td>' . $type . '</td></tr>');
                                    }
                                    foreach($bank AS $b) {
                                        echo ('<tr><td align="right"><b>' . __('Banco') . '</b></td><td>' . $b->name . '</td></tr>');
                                    } 
                                ?>
                            </table>
                        </div>
                        <div>
                            <table class="table">
                                <tr><td align="justify"><p>Nos esforzamos en procesar todas las remesas en menos de 48 horas luego de la fecha y hora especificada en este comprobante. No nos hacemos responsables por demoras ocasionadas por el sistema financiero del país destino.</p></td></tr>
                                <tr><td align="justify"><p>Si tienes alguna duda o reclamo, llámanos al +XXX XXXX-XXXX, escríbenos por Whatsapp al +XXX XXXX-XXXX o por email a clientes@remitter.appstic.net, no olvides mencionar el número de operación en la parte superior de este documento.</p></td></tr>
                                <tr><td align="justify"><p>Ocasionalmente remitter enviará correos electrónicos a sus clientes para proporcionar información de tasas, noticias y otros servicios relacionados.</p></td></tr>
                                <tr><td align="justify"><p>Con tu firma aceptas las condiciones reflejadas en este documento y las políticas establecidas por remitter en su página web (https://remitter.appstic.net/terms.html).</p></td></tr>
                                <tr><td align="justify"><p>  </p></td></tr>
                                <tr><td align="justify"><p>Firma: __________________________________</p></td></tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                </div>
        </div>
    </div>
</div>
<div class="text-center" onclick="PrintDiv();">
    <?php 
        echo $this->Html->link(
            __('Imprimir'),
            array(
                'controller' => 'cpanel',
                'action' => 'home'
            ),
            array(
                'class' => 'btn btn-primary'
            )
        );
    ?>    
</div>
<script type="text/javascript">     
    function PrintDiv() {    
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=400,height=600');
        popupWin.document.open();
        popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
    }
</script>
