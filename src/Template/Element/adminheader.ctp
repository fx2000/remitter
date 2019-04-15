<?php 
    $user_type = $this->request->session()->read('user_type');
?>

<style>
    .dropbtn {
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
    }
    .dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 10px 10px 10px 10px;
    }
    .dropdown-content a {
        color: dark-gray;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
    .dropdown-content a:hover {background-color: #ddd}
    .dropdown:hover .dropdown-content {
        display: block;
    }
    .dropdown:hover .dropbtn {
    }
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg " color-on-scroll="500" style="min-height: 75px; padding: 15px 55px">
    <div class=" container-fluid  ">
        <?php 
            // $str = '';
            // if($user_detail['role'] != '') $str = 'Role : '.$user_detail['role']; 
            // if($user_detail['assigned_to'] != '') $str .= ' <b>|</b> Assigned To : '.$user_detail['assigned_to'];
        ?>
        <a class="navbar-brand"><?php //echo $str; ?></a>
        <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar burger-lines"></span>
            <span class="navbar-toggler-bar burger-lines"></span>
            <span class="navbar-toggler-bar burger-lines"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="no-icon">
                                <i class="fas fa-user"></i>
                                <?php
                                    $fullName = $this->request->session()->read('fname1') . ' ' . $this->request->session()->read('lname1');
                                    echo $fullName;
                                ?>
                            </span>
                        </a>
                        <div class="dropdown-content">
                            <?php
                                echo $this->Html->link(
                                    __('Mi Perfil'),
                                    array(
                                        'controller' => 'user',
                                        'action'     => 'edit',base64_encode($this->request->session()->read('user_id')),$this->request->session()->read('user_type')
                                    )
                                );
                            ?>
                            <?php
                                echo $this->Html->link(
                                    __('Cambiar Contraseña'),
                                    array(
                                        'controller' => 'cpanel',
                                        'action'     => 'change_pwd'
                                    )
                                );
                            ?>
                            <?php
                                echo $this->Html->link(
                                    __('Salir'),
                                    array(
                                        'controller' => 'cpanel',
                                        'action'     => 'logout'
                                    )
                                );
                            ?>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
