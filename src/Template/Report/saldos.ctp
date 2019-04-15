<style>
    .totales {
        text-align:left;
        font-size:15px;
        font-weight:300;
    }
</style>

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
            __('Usuarios'),
            array(
                'controller' => 'user',
                'action'     => 'index'
            )
        );
    ?>
</div>

<div class="row-fluid">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-paper-plane"></i><?php echo __(' Saldos por Inversionista'); ?></h4>
        </div>
        <div class="box-content">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>País</th>
                        <th>Saldo Diferido</th>
                        <th>Saldo Acumulado</th>
                        <th>Saldo Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    	$cant = 0;
                    	$balance = 0;
                    	$diferido = 0;
                        $total = 0;
                        foreach($users AS $u) {
                            echo (
                                        '<tr>
                                            <td>');
                            echo str_pad($u->id, 6, "0", STR_PAD_LEFT);
                            echo (
                                            '</td>
                                            <td>'
                            );
                            echo $u->fname1 . ' ' . $u->lname1;
                            echo (
                                            '</td>
                                            <td>'
                            );
                            echo $u->country;
                            echo (
                                            '</td>
                                            <td>'
                            );
                            echo '$' . number_format($u->balanceTmp,2);
                            echo (
                                '</td>
                                <td>'
                            );
                            echo '$' . number_format($u->balance,2);
                            echo (
                                '</td>
                                <td>'
                            );
                            echo '$' . number_format(($u->balance + $u->balanceTmp),2);
                            echo (
                                '</td>
                                <td>'
                            ); 
                            echo (
                                            '</td>
                                        </tr>');
                            $cant++;
                            $balance = $balance + $u->balance;
                            $diferido = $diferido + $u->balanceTmp;
                            $total= $total + ($u->balance + $u->balanceTmp);
                        }
                    ?>
                </tbody>
            </table>
            <?php 
                echo ('<div class="totales">'.__('Inversionistas').': <b>'.$cant.'</b>');
                echo ('<br>');
                echo (__('Saldo Diferido').': <b>$'.number_format($diferido,2).'</b>');
                echo ('<br>');
                echo (__('Saldo Acumulado').': <b>$'.number_format($balance,2).'</b>');
                echo ('<br>');
                echo (__('Saldo Total').': <b>$'.number_format($total,2).'</b></div>');
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
            <h4><i class="fas fa-paper-plane"></i><?php echo __(' Saldos por Inversionista'); ?></h4>
        </div>
        <div class="box-content">
            <table style="font-family:Calibri;font-size:8pt;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>País</th>
                        <th>Saldo Diferido</th>
                        <th>Saldo Acumulado</th>
                        <th>Saldo Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    	$cant = 0;
                    	$balance = 0;
                    	$diferido = 0;
                        $total = 0;
                        foreach($users AS $u) {
                            echo (
                                        '<tr>
                                            <td>');
                            echo str_pad($u->id, 6, "0", STR_PAD_LEFT);
                            echo (
                                            '</td>
                                            <td>'
                            );
                            echo $u->fname1 . ' ' . $u->lname1;
                            echo (
                                            '</td>
                                            <td>'
                            );
                            echo $u->country;
                            echo (
                                            '</td>
                                            <td>'
                            );
                            echo '$' . number_format($u->balanceTmp,2);
                            echo (
                                '</td>
                                <td>'
                            );
                            echo '$' . number_format($u->balance,2);
                            echo (
                                '</td>
                                <td>'
                            );
                            echo '$' . number_format(($u->balance + $u->balanceTmp),2);
                            echo (
                                '</td>
                                <td>'
                            ); 
                            echo (
                                            '</td>
                                        </tr>');
                            $cant++;
                            $balance = $balance + $u->balance;
                            $diferido = $diferido + $u->balanceTmp;
                            $total= $total + ($u->balance + $u->balanceTmp);
                        }
                    ?>
                </tbody>
            </table>
            <div style="font-family:Calibri;font-size:9pt;">
                <?php 
                echo ('<div class="totales">'.__('Inversionistas').': <b>'.$cant.'</b>');
                echo ('<br>');
                echo (__('Saldo Diferido').': <b>$'.number_format($diferido,2).'</b>');
                echo ('<br>');
                echo (__('Saldo Acumulado').': <b>$'.number_format($balance,2).'</b>');
                echo ('<br>');
                echo (__('Saldo Total').': <b>$'.number_format($total,2).'</b></div>');
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
