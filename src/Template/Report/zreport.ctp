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
            <h4><i class="fas fa-paper-plane"></i><?php echo __(' Imprimir Reporte Z'); ?></h4>
        </div>
        <div class="box-content">
            <p>El Reporte Z genera un cierre de todas las operaciones del día calendario, ¿estás seguro de que deseas emitirlo?</p>
        </div>
    </div>
</div>
<div class="text-center" onclick="window.open('http://200.75.249.86/zprint.php', '_blank', 'width=400,height=200')">
    <?php 
        echo $this->Html->link(
            __('Imprimir'),
            array(
                'controller' => 'cpanel',
                'action'     => 'home'
            ),
            array(
                'class'      => 'btn btn-primary'
            )
        );
    ?>
</div>