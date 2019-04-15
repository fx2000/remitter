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
                'controller' => 'payment',
                'action'     => 'index'
            )
        );
    ?>
</div>

<div class="row-fluid ">        
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h4><i class="fas fa-money-bill-alt"></i></i><?php echo __(' Pagos por Mes - ').$dates; ?></h4>
        </div>
        <div class="box-content">
            <table class="table table-striped table-bordered bootstrap-datatable">
                <thead>
                    <tr>
                        <th><?php echo __('ID Transacción'); ?></th>
                        <th><?php echo __('Fecha & Hora'); ?></th>
                        <th><?php echo __('Inversionista'); ?></th>
                        <th><?php echo __('Monto'); ?></th>
                        <th><?php echo __('Banco'); ?></th>
                        <th><?php echo __('Tipo de Cuenta'); ?></th>
                        <th><?php echo __('Número de Cuenta'); ?></th>
                        <th><?php echo __('Estado'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $cant=0;
                        $total=0;
                        foreach($payments AS $p){ ?>
                            <tr>
                                <td>
                                    <?php echo str_pad($p->id,6,'0',STR_PAD_LEFT); ?>
                                </td>
                                <td><?php echo $p->trans_dt; ?></td>
                                <td><?php echo $p->investor; ?></td>
                                <td><?php echo $p->amount; ?></td>
                                <td><?php echo $p->bank; ?></td>
                                <td><?php echo $p->bank_account_type; ?></td>
                                <td><?php echo $p->bank_account_number; ?></td>
                                <td style="align-text: center;">
                                <?php 
                                    if ($p->status == 1) {
                                        echo __('Pendiente');
                                    } elseif ($p->status == 2) {
                                        echo __('En Verificación');
                                    } elseif ($p->status == 3) {
                                        echo __('Aprobado');
                                    } else {
                                        echo __('Rechazado');
                                    }
                                ?>
                                </td>
                            </tr>
                    <?php
                            $cant++;
                            $total = $total + $p->amount;
                        }
                    ?>
                </tbody>
            </table>
            <?php
                echo ('<br>');
                echo ('<div class="totales">'.__('Cantidad de Pagos Efectuados').': <b>'.$cant.'</b>');
                echo ('<br>');
                echo (__('Total Pagado').': <b>$'.number_format($total,2).'</b>');
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
            <h4><i class="fas fa-paper-plane"></i><?php echo __(' Pagos por Mes - ').$dates; ?></h4>
        </div>
        <div class="box-content">
            <table style="font-family:Calibri;font-size:10pt;">
                <thead>
                    <tr>
                        <th><?php echo __('ID Transacción'); ?></th>
                        <th><?php echo __('Fecha & Hora'); ?></th>
                        <th><?php echo __('Inversionista'); ?></th>
                        <th><?php echo __('Monto'); ?></th>
                        <th><?php echo __('Banco'); ?></th>
                        <th><?php echo __('Tipo de Cuenta'); ?></th>
                        <th><?php echo __('Número de Cuenta'); ?></th>
                        <th><?php echo __('Estado'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $cant = 0;
                        $total = 0;
                        foreach($payments AS $p){ ?>
                            <tr>
                                <td>
                                    <?php echo str_pad($p->id,6,'0',STR_PAD_LEFT); ?>
                                </td>
                                <td><?php echo $p->trans_dt; ?></td>
                                <td><?php echo $p->investor; ?></td>
                                <td><?php echo $p->amount; ?></td>
                                <td><?php echo $p->bank; ?></td>
                                <td><?php echo $p->bank_account_type; ?></td>
                                <td><?php echo $p->bank_account_number; ?></td>
                                <td style="align-text: center;">
                                <?php 
                                    if ($p->status == 1) {
                                        echo __('Pendiente');
                                    } elseif ($p->status == 2) {
                                        echo __('En Verificación');
                                    } elseif ($p->status == 3) {
                                        echo __('Aprobado');
                                    } else {
                                        echo __('Rechazado');
                                    }
                                ?>
                                </td>
                            </tr>
                    <?php
                            $cant++;
                            $total = $total + $p->amount;
                        }
                    ?>
                </tbody>
            </table>
            <div style="font-family:Calibri;font-size:12pt;">
                <?php
                    echo ('<br>');
                    echo ('<div class="totales">'.__('Cantidad de Pagos Efectuados').': <b>'.$cant.'</b>');
                    echo ('<br>');
                    echo (__('Total Pagado').': <b>$'.number_format($total,2).'</b>');
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