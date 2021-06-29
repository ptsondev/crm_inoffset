<?php

    require_once '../mylib.php';

    require_once '../include.php';
show_header_include('Quản Lý Đơn Hàng');
    if(!is_login()){        

        header("Location: /");

        die;

    }

    $user = $_SESSION['user'];

    if($user['role']!=ROLE_ADMIN){

        header("Location: /");

        die;    

    }



    display_site_header();

?>

<h3 id="page-title">Nhân Sự</h3>
<div class="container">
<?php 

    $users = getAllUsers();

    foreach ($users as $u) {

        echo '<li><a href="/manager/staff/detail.php?UID='.$u['ID'].'">'.$u['fullname'].'</a></li>';

    }

?>
</div>