<style>
    .totales {
        text-align:left;
        font-size:15px;
        font-weight:300;
    }
</style>
<script>
function alertBox() {
    alert(<?php echo __("No es posible actualizar esta remesa"); ?>);
}
</script>

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
            __('Remesas'),
            array(
                'controller' => 'remittance',
                'action'     => 'index'
            )
        );
    ?>
</div>

<div class="row-fluid ">        
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-paper-plane"></i><?php echo __(' Cierre de Caja - ').$user_name; ?></h4>
        </div>
        <div class="box-content">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Fecha & Hora</th>
                        <th>Cliente</th>
                        <th>Beneficiario</th>
                        <th>Monto Recibido</th>
                        <th>Tarifa de Envío</th>
                        <th>ITBMS</th>
                        <th>Tasa Efectiva</th>
                        <th>Método de Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $cant=0;
                        $cash=0;
                        $ach=0;
                        $total=0;
                        $neto=0;
                        $tarifaCash=0;
                        $tarifaAch=0;
                        $tarifa=0;
                        $itbms=0;
                        foreach($remittances AS $r) {
                            echo (
                                        '<tr>
                                            <td>');
                            echo str_pad($r->id, 6, "0", STR_PAD_LEFT);
                            echo (
                                            '</td>
                                            <td>'
                            );
                            echo date_format($r->trans_dt, 'Y-m-d H:i:s');
                            echo (
                                            '</td>
                                            <td>'
                            );
                            echo $r->client;
                            echo (
                                            '</td>
                                            <td>'
                            );
                            echo $r->recipient;
                            echo (
                                            '</td>
                                            <td>'
                            );
                            echo '$' . number_format($r->amount,2);
                            echo (
                                '</td>
                                <td>'
                            );
                            echo '$' . number_format($r->fee,2);
                            echo (
                                '</td>
                                <td>'
                            );
                            echo '$' . number_format($r->tax,2);
                            echo (
                                '</td>
                                <td>'
                            );
                            echo '$' . number_format($r->purchase_rate,2);
                            echo (
                                '</td>
                                <td>'
                            );
                            if ($r->payment_type == 1) {
                                echo 'Efectivo';
                                $cash = $cash + $r->amount;
                                $tarifaCash = $tarifaCash + $r->fee + $r->tax;
                            } else {
                                echo 'ACH';
                                $ach = $ach + $r->amount;
                                $tarifaAch = $tarifaAch + $r->fee + $r->tax;
                            }
                            echo (
                                            '</td>
                                        </tr>');
                            $cant=$cant+1;
                            $total=$total+($r->amount+$r->fee+$r->tax);
                            $neto=$neto+($r->amount);
                            $tarifa=$tarifaCash + $tarifaAch;
                            $itbms=$itbms+($r->tax);
                        }
                    ?>
                </tbody>
            </table>
            <?php 
                echo ('<div class="totales">'.__('Cantidad de Remesas').': <b>'.$cant.'</b>');
                echo ('<br>');
                echo (__('Monto en Remesas').': <b>$'.number_format($neto,2).'</b>');
                echo ('<br>');
                echo (__('Monto en Tarifas').': <b>$'.number_format($tarifa,2).'</b>');
                echo ('<br>');
                echo (__('Monto en Efectivo').': <b>$'.number_format(($cash + $tarifaCash),2).'</b>');
                echo ('<br>');
                echo (__('Monto en Transferencias').': <b>$'.number_format(($ach + $tarifaAch),2).'</b>');
                echo ('<br>');
                echo (__('Monto Total').': <b>$'.number_format($total,2).'</b>');
                echo ('<br>');
            ?>
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
    </div>
</div>
<div class="form-inline col-md-12" style="margin-top: 40px; display:none">
    <div class="col-md-2"></div>
        <div class="card card-user col-md-8" id="divToPrint">
            <div class="card-body">
                <div class="author">
                    <div class="content">
                        <div class="author">
                            <table class="table" align="center">
                                <td style="text-align: center;">
                                    <?php
                                        echo $this->Html->image(
                                            'logobw.jpg', [
                                                'alt'   => __('HispanoRemesas'),
                                                'class' => 'avatar border-gray',
                                                'style' => 'border-radius: 0px; border: 0px; height: 75px; margin-top:60px;'
                                            ]
                                        );
                                    ?>
                                </td>
                            </table>
                        </div>
                        <br>
                        <div>
                            <table class="table" align="center" style="font-family:Calibri;font-size:9pt;">
                                <tr><td>DuFer Holdings Group, Inc.</td></tr>
                                <tr><td>RUC 155663383-2-2018 DV76</td></tr>
                                <tr><td>Ave. Federico Boyd, PH DoubleTree Hilton, Local C11, Campo Alegre.</td></tr>
                            </table>
                        </div>
                        <div>
                            <table class="table" align="center">
                                <tr><td style="text-align:center;font-family:Calibri;font-size:14pt;"><strong>CIERRE DE CAJA</strong></td></tr>
                                <tr><td style="text-align:center;font-family:Calibri;font-size:12pt;"><?php echo date('Y-m-d')?></td></tr>
                                <hr/>
                            </table>
                        </div>
                        <div>
                            <table class="table" style="font-family:Calibri;font-size:9pt;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Hora</th>
                                        <th>Monto</th>
                                        <th>Tarifa</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $cant = 0;
                                        $cash = 0;
                                        $ach = 0;
                                        $total = 0;
                                        $neto = 0;
                                        $tarifa = 0;
                                        $tarifaCash = 0;
                                        $tarifaAch = 0;
                                        $itbms = 0;
                                        foreach($remittances AS $r) {
                                            if ($r->payment_type == 1) {
                                                $cash = $cash + $r->amount;
                                                $tarifaCash = $tarifaCash + $r->fee + $r->tax;
                                            } else {
                                                $ach = $ach + $r->amount;
                                                $tarifaAch = $tarifaAch + $r->fee + $r->tax;
                                            }
                                            echo (
                                                        '<tr>
                                                            <td>');
                                            echo str_pad($r->id, 6, "0", STR_PAD_LEFT); 
                                            echo (
                                                            '</td>
                                                            <td>'
                                            );
                                            echo date_format($r->trans_dt, 'h:i:s a');
                                            echo (
                                                            '</td>
                                                            <td>'
                                            );
                                            echo '$' . number_format($r->amount,2);
                                            echo (
                                                '</td>
                                                <td>'
                                            );
                                            echo '$' . number_format(($r->fee + $r->tax),2);
                                            echo (
                                                '</td>
                                                <td>'
                                            );
                                            echo '$' . number_format(($r->amount + $r->fee + $r->tax),2);
                                            echo (
                                                            '</td>
                                                        </tr>');
                                            $cant = $cant + 1;            
                                            $total = $total + ($r->amount + $r->fee + $r->tax);
                                            $neto = $neto + ($r->amount);
                                            $tarifa = $tarifaCash + $tarifaAch;
                                            $itbms = $itbms + ($r->tax);
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <table class="table" style="font-family:Calibri;font-size:9pt;">
                            <hr/>
                                <?php
                                    echo ('<tr><td align="right"><b>'.__('Operador').'</b></td><td td align="left">'.$user_name.'</td></tr>');
                                    echo ('<tr><td align="right"><b>'.__('Hora de Cierre').'</b></td><td align="left">'.date('Y-m-d h:i a').'</td></tr>');
                                    echo ('<tr><td align="right"><b>'.__('Cantidad de Remesas').'</b></td><td align="left">'.$cant.'</td></tr>');
                                    echo ('<tr><td align="right"><b>'.__('Total en Remesas').'</b></td><td align="left">'.$this->Number->currency($neto).'</td></tr>');
                                    echo ('<tr><td align="right"><b>'.__('Total en Tarifas').'</b></td><td align="left">'.$this->Number->currency($tarifa).'</td></tr>');
                                    echo ('<tr><td align="right"><b>'.__('Total en Efectivo').'</b></td><td align="left">'.$this->Number->currency($cash + $tarifaCash).'</td></tr>');
                                    echo ('<tr><td align="right"><b>'.__('Total en Transferencias').'</b></td><td align="left">'.$this->Number->currency($ach + $tarifaAch).'</td></tr>');
                                    echo ('<tr><td align="right"><b>'.__('Total General').'</b></td><td align="left">'.$this->Number->currency($total).'</td></tr>');
                                ?>
                            </table>
                        </div>
                    </div>
                    <hr>
                </div>
        </div>
    </div>
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