<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <!-- <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
        <link rel="icon" type="image/png" href="../assets/img/favicon.ico"> -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title><?php echo __('HispanoRemesas | PAR'); ?></title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" /> -->
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" /> -->
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <!-- CSS Files -->
        <?php echo $this->Html->css('bootstrap.min.css'); ?>
        <?php echo $this->Html->css('login.css'); ?>
        <?php echo $this->Html->css('light-bootstrap-dashboard.css');?>
    </head>
    <body>
        <div class="wrapper">
            <div class="container">
                <!-- <div class="card"> -->
                <?php
                    echo $this->Html->image(
                        'logoColorNoShadow.png',
                        array(
                            'class' => 'img-fluid',
                            'width' => '100'
                        )
                    );
                ?>
                <h3>
                    <?php
                        echo __('<b>Hispano</b>Remesas');
                    ?>
                </h3>
                <?php
                    echo $this->Form->create('',[
                        'class' => 'form-group'
                    ]);
                ?>
                <?php
                    echo $this->Form->input(
                        'username',
                        array(
                            'type'        => 'text',
                            'placeholder' => __('Email'),
                            'class'       => 'form-control',
                            'id'          => 'text',
                            'div'         => false,
                            'label'       => false,
                            'required'
                        )
                    );
                ?>
                <?php
                    echo $this->Form->input(
                        'password',
                        array(
                            'type'        => 'password',
                            'placeholder' => __('Contraseña'),
                            'class'       => 'form-control',
                            'id'          => 'password',
                            'div'         => false,
                            'label'       => false,
                            'required'
                        )
                    );
                ?>
                <?php
                    echo $this->Form->Submit(
                        __('Iniciar Sesión'),
                        array(
                            'class' => 'button'
                        )
                    );
                ?>
                <div class="row-fluid" style="margin:10px 10px 10px 10px;color:white;">
                    <?php
                        echo $this->Flash->render();
                    ?>
                </div>
                <!-- </div> -->
            </div>
            <!--
            <ul class="bg-bubbles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
            -->
        </div>
        <!-- <script src="js/index.js"></script> -->
    </body>
    <?php
        //echo $this->Html->script('login.js');
        // echo $this->Html->script('jquery-1.7.2.min');    
        // echo $this->Html->script('jquery-ui-1.8.21.custom.min');
        // echo $this->Html->script('bootstrap-transition');
        // echo $this->Html->script('bootstrap-alert');
        // echo $this->Html->script('bootstrap-modal');
        // echo $this->Html->script('bootstrap-dropdown');
        // echo $this->Html->script('bootstrap-scrollspy');
        // echo $this->Html->script('bootstrap-tab');
        // echo $this->Html->script('bootstrap-tooltip');
        // echo $this->Html->script('bootstrap-popover');
        // echo $this->Html->script('bootstrap-button');
        // echo $this->Html->script('bootstrap-collapse');
        // echo $this->Html->script('bootstrap-carousel');
        // echo $this->Html->script('bootstrap-typeahead');
        // echo $this->Html->script('bootstrap-tour');
        // echo $this->Html->script('jquery.cookie');
        // echo $this->Html->script('fullcalendar.min');    
        // //   <!-- data table plugin -->
        // echo $this->Html->script('jquery.dataTables.min');
        // //   <!-- chart libraries start -->
        // echo $this->Html->script('excanvas');
        // echo $this->Html->script('jquery.flot.min');
        // echo $this->Html->script('jquery.flot.pie.min');
        // echo $this->Html->script('jquery.flot.stack');
        // echo $this->Html->script('jquery.chosen.min');
        // echo $this->Html->script('jquery.flot.resize.min');
        // //   <!-- chart libraries end -->
        // //   <!-- select or dropdown enhancer -->
        // echo $this->Html->script('jquery.uniform.min');
        // //   <!-- checkbox, radio, and file input styler -->
        // echo $this->Html->script('jquery.colorbox.min');
        // //   <!-- rich text editor library -->
        // echo $this->Html->script('jquery.cleditor.min');
        // //   <!-- notification plugin -->
        // echo $this->Html->script('jquery.noty');
        // //   <!-- file manager library -->
        // echo $this->Html->script('jquery.elfinder.min');
        // //   <!-- star rating plugin -->
        // echo $this->Html->script('jquery.raty.min');
        // //   <!-- for iOS style toggle switch -->
        // echo $this->Html->script('jquery.iphone.toggle');
        // //   <!-- autogrowing textarea plugin -->
        // echo $this->Html->script('jquery.autogrow-textarea');
        // //   <!-- multiple file upload plugin -->
        // echo $this->Html->script('jquery.uploadify-3.1.min');
        // //   <!-- history.js for cross-browser state change on ajax -->
        // echo $this->Html->script('jquery.history');
        // //   <!-- application script for Charisma demo -->
        // echo $this->Html->script('travel_script');
    ?>
    </body>
</html>
