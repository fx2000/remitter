<?php 
    $user_type = $this->request->session()->read('user_type');

    if ($this->request->session()->read('alert') != '') {
?>

<div class="alert <?php echo ($this->request->session()->read('success')==1) ? 'alert-success':'alert-danger'; ?>">
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
            __('Clientes / '),
            array(
                'controller' => 'user',
                'action'     => 'index',
                5
            )
        );
    ?>
    <?php
        echo __('Beneficiarios');
    ?>
</div>

<div>
    <?php
        echo $this->Html->link(
            "<i class='fas fa-plus'></i> " . __('Agregar Beneficiario'),
            array(
                'controller' => 'recipient',
                'action'     => 'add',
                $user_id
            ), 
            array(
                'class'      => 'btn btn-success btn-simple btn-link pull-right',
                'rel'        => 'tooltip',
                'escape'     => false
            )
        ); 
    ?>
</div>
<div class="row-fluid ">        
    <div class="box span12">
        <div class="box-header well" data-original-title>
        <h4><i class="fas fa-users"></i><?php echo __(' Beneficiarios'); ?></h4>
        </div>
        <div class="box-content">
            <table class="table table-striped table-bordered bootstrap-datatable AccountDataTable">
                <thead>
                    <tr>
                    <th><?php echo __('Nombre'); ?></th>
                    <th><?php echo __('Cédula'); ?></th>
                    <th><?php echo __('Banco'); ?></th>
                    <th><?php echo __('Tipo de Cuenta'); ?></th>
                    <th><?php echo __('País'); ?></th>
                    <th><?php echo __('Estado'); ?></th>
                    <th><?php echo __('Acciones'); ?></th>
                </thead>
                <tbody>
                    <?php
                        foreach ($recipients AS $r) {
                    ?>
                        <tr>
                            <td>
                                <?php
                                    echo $this->Html->link(
                                        $r->fname1 . ' ' . $r->lname1, 
                                        array(
                                            'controller' => 'recipient',
                                            'action'     => 'edit',
                                            base64_encode($r->id)
                                        )
                                    ); 
                                ?>
                            </td>
                            <td>
                                <?php
                                    $cedula = is_numeric($r->tax_id) ? number_format($r->tax_id) : $r->tax_id;
                                    echo '', ($r->tax_id == null ? $r->passport : $cedula);
                                ?>
                            </td>
                            <td><?php echo $r->bank; ?></td>
                            <td><?php echo $r->bank_account_type; ?></td>
                            <td><?php echo $r->country; ?></td>
                            <td style="align-item: center;">
                                <?php
                                    echo '', ($r->status == 1 ? "<i class='fas fa-check'></i>" : "<i class='fas fa-times'></i>");
                                ?>
                            </td>
                            <td>
                                <?php
                                    echo $this->Html->link(
                                        "<i class='fas fa-pencil-alt'></i> " . __('Ver'), 
                                        array(
                                            'controller' => 'recipient',
                                            'action'     => 'edit',
                                            base64_encode($r->id)
                                        ), 
                                        array(
                                            'class'      => 'btn btn-primary btn-round btn-sm',
                                            'rel'        => 'tooltip',
                                            'escape'     => false
                                        )
                                    );
                                ?>
                                <?php
                                    echo $this->Html->link(
                                        "<i class='fas fa-trash'></i> " . __('Eliminar'),
                                        array(
                                            'controller' => 'recipient',
                                            'action'     => 'delete',
                                            base64_encode($r->id)
                                        ), 
                                        array(
                                            'class'      => 'btn btn-danger btn-round btn-sm',
                                            'rel'        => 'tooltip',
                                            'escape'     => false,
                                            'confirm'    => __('¿Estás seguro de que deseas eliminar este beneficiario?')
                                        )
                                    );
                                ?>
                            </td>
                        </tr>
                    <?php   
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>