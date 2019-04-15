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

<div class="row-fluid">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-paper-plane"></i><?php echo __(' Remesas por Mes - ').$dates; ?></h4>
        </div>
        <div class="box-content">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Fecha & Hora</th>
                        <th>Cliente</th>
                        <th>Beneficiario</th>
                        <th>Tasa Efectiva</th>
                        <th>Monto Remesa</th>
                        <th>Tarifa de Envío</th>
                        <th>ITBMS</th>
                        <th>Total Recibido</th>
                        <th>Monto Transferido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $cant=0;
                        $total=0;
                        $neto=0;
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
                            echo 'BsS.' . number_format($r->purchase_rate,2);
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
                            echo '$' . number_format($r->tax + $r->fee + $r->amount,2);
                            echo (
                                '</td>
                                <td>'
                            );
                            echo 'BsS.' . number_format($r->amount_delivered,2);  
                            echo (
                                            '</td>
                                        </tr>');
                            $cant++;            
                            $total=$total+($r->amount+$r->fee+$r->tax);
                            $neto=$neto+($r->amount);
                            $tarifa=$tarifa+($r->fee);
                            $itbms=$itbms+($r->tax);
                        }
                    ?>
                </tbody>
            </table>
            <?php 
                echo ('<div class="totales">'.__('Cantidad de Remesas').': <b>'.$cant.'</b>');
                echo ('<br>');
                echo (__('Total en Remesas').': <b>$'.number_format($neto,2).'</b>');
                echo ('<br>');
                echo (__('Total Neto Facturado').': <b>$'.number_format($tarifa,2).'</b>');
                echo ('<br>');
                echo (__('Total ITBMS').': <b>$'.number_format($itbms,2).'</b></div>');
                echo ('<br>');
                echo (__('Total Recibido').': <b>$'.number_format($total,2).'</b>');
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


<div class="row-fluid" id="divToPrint" style="visibility:hidden">
    <style type="text/css">
        <?php echo file_get_contents(WWW_ROOT . 'css' . DS . 'home.css'); ?>
    </style>
    <div class="box span12">
        <div class="box-header well" data-original-title style="font-family:Calibri">
            <h4><i class="fas fa-paper-plane"></i><?php echo __(' Remesas por Mes - ') . $dates; ?></h4>
        </div>
        <div class="box-content">
            <table style="font-family:Calibri;font-size:8pt;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Fecha & Hora</th>
                        <th>Cliente</th>
                        <th>Beneficiario</th>
                        <th>Tasa Efectiva</th>
                        <th>Monto Remesa</th>
                        <th>Tarifa de Envío</th>
                        <th>ITBMS</th>
                        <th>Total Recibido</th>
                        <th>Monto Transferido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $cant=0;
                        $total=0;
                        $neto=0;
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
                            echo 'BsS.' . number_format($r->purchase_rate,2);
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
                            echo '$' . number_format($r->tax + $r->fee + $r->amount,2);
                            echo (
                                '</td>
                                <td>'
                            );
                            echo 'BsS.' . number_format($r->amount_delivered,2);  
                            echo (
                                            '</td>
                                        </tr>');
                            $cant++;            
                            $total=$total+($r->amount+$r->fee+$r->tax);
                            $neto=$neto+($r->amount);
                            $tarifa=$tarifa+($r->fee);
                            $itbms=$itbms+($r->tax);
                        }
                    ?>
                </tbody>
            </table>
            <div style="font-family:Calibri;font-size:9pt;">
                <?php
                    echo ('<br>');
                    echo ('<div class="totales">'.__('Cantidad de Remesas').': <b>'.$cant.'</b>');
                    echo ('<br>');
                    echo (__('Total en Remesas').': <b>$'.number_format($neto,2).'</b>');
                    echo ('<br>');
                    echo (__('Total Neto Facturado').': <b>$'.number_format($tarifa,2).'</b>');
                    echo ('<br>');
                    echo (__('Total ITBMS').': <b>$'.number_format($itbms,2).'</b></div>');
                    echo ('<br>');
                    echo (__('Total Recibido').': <b>$'.number_format($total,2).'</b>');
                    echo ('<br>');
                ?>
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
