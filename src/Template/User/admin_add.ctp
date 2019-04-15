<?php
    $URL = Configure::read('Server.URL');
?>
<script>
    window.onload =function ()
    {
        loadStores();   
    };
    function ShowField()
    {
        var usertype = $("#usertype").val();
        if (usertype == 4 || usertype == 5) {
            $("#uiddiv").hide();
            $("#retailerdiv").show();
            $("#storediv").hide();
            $('#stores').empty();
        } else if (usertype == 6 || usertype == 7) {
            $("#uiddiv").hide();
            $("#retailerdiv").show();
            $("#storediv").show();
            if (usertype == 7) {
                $("#uiddiv").show();
            }
        } else {
            $("#retailerdiv").hide();
            $("#storediv").hide();
            $('#stores').empty();
        }
    }

    function loadStores()
    {
        var path = '<?php echo $URL?>/inventory/get_stores/'+$('#retailer').val();
        var request = $.ajax({
                url: path
                
            });
            request.done(function (response, textStatus, jqXHR){ //alert(response);
                        $('#stores').empty();
                        $('#stores').append(response);
                        $("#stores").val("<?php echo $this->request->data['Recharge']['store_id']?>");
            });
            request.fail(function (jqXHR, textStatus, errorThrown){
                console.log(jqXHR);
            });
    }
</script>

<div>
    <ul class="breadcrumb">
        <li>
            <?php
                echo $this->Html->link(
                    __('Inicio'),
                    array(
                        'controller' => 'cpanel',
                        'action'     => 'home'
                    )
                );
            ?>
        </li>
        <li>/</li>
        <li>
            <?php
                echo $this->Html->link(
                    __('Agregar Usuario'),
                    array(
                        'controller' => 'user',
                        'action'     => 'add'
                    )
                );
            ?>
        </li>
    </ul>
</div>

<?php
    if ($this->request->session()->read('alert') != '') {
?>

<div class="alert <?php echo ($this->request->session()->read('success') == 1) ? 'alert-success':'alert-error'; ?>">
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

<div class="row-fluid ">    
    <div class="box span12">
       <div class="box-header well" data-original-title>
            <h2><i class="icon-list-alt"></i><?php echo __(' Agregar Usuario'); ?></h2>
        </div>
        <div class="box-content">
             <?php
                echo $this->Form->create(
                    '',
                    array(
                        'url'   => array(
                            'controller' => 'user',
                            'action'     => 'add'
                        ),
                        'class' => 'form-horizontal'
                    )
                );
            ?>
            <fieldset>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Nombre'); ?></label>
                    <div class="controls">
                        <?php
                            echo $this->Form->input(
                                'User.name',
                                array(
                                    'type'                => 'text',
                                    'class'               => 'input-large',
                                    'id'                  => 'name',
                                    'div'                 => false,
                                    'label'               => false,
                                    'maxlength'           => 100,
                                    'data-rel'            => 'tooltip',
                                    'data-original-title' => 'Name'
                                )
                            );
                        ?>
                        <script language="javascript" type="text/javascript">
                            var f1 = new LiveValidation('name');
                            f1.add( Validate.Presence);
                        </script>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Email'); ?></label>
                    <div class="controls">
                        <?php
                            echo $this->Form->input(
                                'User.email',
                                array(
                                    'type'                => 'text',
                                    'class'               => 'input-large',
                                    'id'                  => 'email',
                                    'div'                 => false,
                                    'label'               => false,
                                    'maxlength'           => 100,
                                    'data-rel'            => 'tooltip',
                                    'data-original-title' => 'Email'
                                )
                            );
                        ?>
                        <script language="javascript" type="text/javascript">
                            var f1 = new LiveValidation('email');
                            f1.add( Validate.Presence);
                            f1.add( Validate.Email);
                        </script>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Usuario'); ?></label>
                    <div class="controls">
                        <?php
                            echo $this->Form->input(
                                'User.username',
                                array(
                                    'type'                => 'text',
                                    'class'               => 'input-large',
                                    'id'                  => 'username',
                                    'div'                 => false,
                                    'label'               => false,
                                    'maxlength'           => 100,
                                    'data-rel'            => 'tooltip',
                                    'data-original-title' => 'Username'
                                )
                            );
                        ?>
                        <script language="javascript" type="text/javascript">
                            var f1 = new LiveValidation('username');
                            f1.add( Validate.Presence);
                        </script>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Contraseña'); ?></label>
                    <div class="controls">
                        <?php
                            echo $this->Form->input(
                                'User.password',
                                array(
                                    'type'                => 'password',
                                    'class'               => 'input-large',
                                    'id'                  => 'password',
                                    'div'                 => false,
                                    'label'               => false,
                                    'maxlength'           => 100,
                                    'data-rel'            => 'tooltip',
                                    'data-original-title' => 'Password'
                                )
                            );
                        ?>
                        <script language="javascript" type="text/javascript">
                            var f1 = new LiveValidation('password');
                            f1.add( Validate.Presence);
                            f1.add( Validate.len_password);
                        </script>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Confirmar Contraseña'); ?></label>
                    <div class="controls">
                        <?php
                            echo $this->Form->input(
                                'User.confirm_password',
                                array(
                                    'type'                => 'password',
                                    'class'               => 'input-large',
                                    'id'                  => 'confirm_password',
                                    'div'                 => false,
                                    'label'               => false,
                                    'maxlength'           => 100,
                                    'data-rel'            => 'tooltip',
                                    'data-original-title' => 'Confirmar Contraseña'
                                )
                            );
                        ?>
                        <script language="javascript" type="text/javascript">
                            var f1 = new LiveValidation('confirm_password');
                            f1.add( Validate.Presence);
                            f1.add( Validate.password);
                        </script>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Perfil'); ?></label>
                    <div class="controls">
                        <?php
                            echo $this->Form->input(
                                'User.user_type',
                                array(
                                    'type'     => 'select',
                                    'options'  => $usertype,
                                    'empty'    => 'Seleccionar Perfil',
                                    'id'       => "usertype",
                                    'label'    => false,
                                    'onchange' => 'ShowField()'
                                )
                            );
                        ?>
                        <script language="javascript" type="text/javascript">
                            var f1 = new LiveValidation('usertype');
                            f1.add( Validate.Presence);
                        </script>
                    </div>
                </div>
                <?php
                    $isVisibleRetailer = 'none';
                    $isVisibleStore = 'none';
                    $isVisibleUID = 'none';
                    if ($this->request->data['User']['user_type'] == 4 || $this->request->data['User']['user_type'] == 5) {
                        $isVisibleRetailer = 'block';

                    } else if ($this->request->data['User']['user_type'] == 6 || $this->request->data['User']['user_type'] == 7) {
                        $isVisibleStore = 'block';
                        $isVisibleRetailer = 'block';
                        $isVisibleUID = 'block';
                    }
                ?>
                <div class="control-group" id="retailerdiv" style="display:<?php echo $isVisibleRetailer?>">
                    <label class="control-label"><?php echo __('Retailer'); ?></label>
                    <div class="controls">
                        <?php
                            echo $this->Form->input(
                                'User.retailer',
                                array(
                                    'type'     => 'select',
                                    'options'  => $retailer,
                                    'empty'    => 'Seleccionar Retailer',
                                    'id'       => "retailer",
                                    'label'    => false,
                                    'onchange' => 'loadStores()'
                                )
                            );
                        ?>
                    </div>
                </div>
                <div class="control-group" id="storediv" style="display:<?php echo $isVisibleStore?>">
                    <label class="control-label"><?php echo __('Tienda'); ?></label>
                    <div class="controls">
                        <?php
                            echo $this->Form->input(
                                'User.store',
                                array(
                                    'type'    => 'select',
                                    'options' => $stores,
                                    'empty'   => 'Seleccionar Tienda',
                                    'id'      => "stores",
                                    'label'   => false
                                )
                            );
                        ?>
                    </div>
                </div>
                <div class="control-group" id="uiddiv" style="display:<?php echo $isVisibleUID?>">
                    <label class="control-label"><?php echo __('UID : '); ?></label>
                    <div class="controls">
                        <?php
                            echo $this->Form->input(
                                'User.uid',
                                array(
                                    'type'  => 'text',
                                    'id'    => "uid",
                                    'label' => false
                                )
                            );
                        ?>
                    </div>
                </div>
                <div class="form-actions">
                    <?php
                        echo $this->Form->Submit(
                            __('Guardar'),
                            array(
                                'class' => 'btn btn-primary'
                            )
                        );
                    ?>
                </div>
            </fieldset>
        </div>
    </div>
</div>