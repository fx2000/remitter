<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <?php echo $this->Html->meta('icon','',array('type' => 'icon'));?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=9"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
        <meta name="author" content="">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
        <?php echo $this->Html->charset(); ?>
        <title>HispanoRemesas | PAR</title>
    <?php
                //echo $this->Html->css('admin_style');
                //echo $this->Html->css('bootstrap-cerulean');
                //echo $this->Html->css('bootstrap-responsive');
                echo $this->Html->css('bootstrap.min.css');
                echo $this->Html->css('light-bootstrap-dashboard.css?v=2.0.1'); 
                // echo $this->Html->css('fullcalendar');
                // echo $this->Html->css('fullcalendar.print');
                // echo $this->Html->css('chosen');
                // echo $this->Html->css('uniform.default');
                // echo $this->Html->css('colorbox');
                // echo $this->Html->css('jquery.cleditor');
                // echo $this->Html->css('jquery-ui-1.8.21.custom');
                // echo $this->Html->css('jquery.noty');
                // echo $this->Html->css('noty_theme_default');
                // echo $this->Html->css('elfinder.min');
                // echo $this->Html->css('elfinder.theme');
                echo $this->Html->css('jquery.iphone.toggle');
                // echo $this->Html->css('opa-icons.css');
                // echo $this->Html->css('uploadify.css');
                echo $this->Html->script('livevalidation_standalone');
                echo $this->fetch('meta');
                echo $this->fetch('css');
                echo $this->fetch('script');     
    ?>
</head>
<body>
        <div class="wrapper">
                <?php echo $this->element('adminLeftMenu');?>
                <div class="main-panel">
                        <?php echo $this->element('adminheader');?>
                <div class="content">
                        <?php echo $this->fetch('content'); 
                        ?>
                </div>
    </div>
    <?php //echo $this->element('sql_dump'); ?>
<?php   
echo $this->Html->script('jquery-1.7.2.min');
echo $this->Html->script('jquery-ui-1.8.21.custom.min');
echo $this->Html->script('bootstrap-datetimepicker');
echo $this->Html->script('bootstrap-transition');
echo $this->Html->script('bootstrap-alert');
echo $this->Html->script('bootstrap-modal');
echo $this->Html->script('bootstrap-dropdown');
echo $this->Html->script('bootstrap-scrollspy');
echo $this->Html->script('bootstrap-tab');
echo $this->Html->script('bootstrap-tooltip');
echo $this->Html->script('bootstrap-popover');
echo $this->Html->script('bootstrap-button');
echo $this->Html->script('bootstrap-collapse');
echo $this->Html->script('bootstrap-carousel');
echo $this->Html->script('bootstrap-typeahead');
echo $this->Html->script('bootstrap-tour');
echo $this->Html->script('jquery.cookie');
echo $this->Html->script('fullcalendar.min');   
// //   <!-- data table plugin -->
echo $this->Html->script('jquery.dataTables.min');

// //   <!-- chart libraries start -->
echo $this->Html->script('excanvas');
echo $this->Html->script('jquery.flot.min');
echo $this->Html->script('jquery.flot.pie.min');
echo $this->Html->script('jquery.flot.stack');
echo $this->Html->script('jquery.chosen.min');
echo $this->Html->script('jquery.flot.resize.min');
// //   <!-- chart libraries end -->
// //   <!-- select or dropdown enhancer -->
echo $this->Html->script('jquery.uniform.min');
// //   <!-- checkbox, radio, and file input styler -->
echo $this->Html->script('jquery.colorbox.min');
// //   <!-- rich text editor library -->
echo $this->Html->script('jquery.cleditor.min');
// //   <!-- notification plugin -->
echo $this->Html->script('jquery.noty');
// //   <!-- file manager library -->
echo $this->Html->script('jquery.elfinder.min');
// //   <!-- star rating plugin -->
echo $this->Html->script('jquery.raty.min');
// //   <!-- for iOS style toggle switch -->
echo $this->Html->script('jquery.iphone.toggle');
// //   <!-- autogrowing textarea plugin -->
echo $this->Html->script('jquery.autogrow-textarea');
// //   <!-- multiple file upload plugin -->
echo $this->Html->script('jquery.uploadify-3.1.min');
// //   <!-- history.js for cross-browser state change on ajax -->
echo $this->Html->script('jquery.history');
// //   <!-- application script for Charisma demo -->
echo $this->Html->script('popper.min.js');
echo $this->Html->script('bootstrap.min.js');
echo $this->Html->script('travel_script');
echo $this->Html->script('light-bootstrap-dashboard.js?v=2.0.1');
?>
<script>
function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
    //alert(document.cookie);
}
deleteAllCookies();
</script>
</body>
</html>
