<?php

    require_once '../mylib.php';

    require_once '../include.php';
    show_header_include('Quản Lý Đơn Hàng - Mobile');

    if(!is_login()){        

        header("Location: /");

        die;

    }
  


    $user = $_SESSION['user'];

    if($user['role']!=ROLE_ADMIN && $user['role']!=ROLE_SALE){

        header("Location: /");

        die;    

    }

    display_site_header();





$dbh = getDBH();


        $sql = "SELECT MAX(PID) as mm FROM projects";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $maxPID = $stmt->fetchAll(PDO::FETCH_ASSOC);       
        $maxPID = $maxPID[0]['mm'];



        $sql = "SELECT * FROM projects ORDER BY PID DESC";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(2));
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);          

// search: pid, name, phone, email
?>

<h3 class="mobile-title">Quản lý đơn hàng</h3>
<div id="frmSearchProject">
    <input type="text" id="txtSearchPID" placeholder="PID" value=""/>
    <input type="text" id="txtSearchName" placeholder="Tên Khách" value=""/>
    <input type="text" id="txtSearchPhone" placeholder="Điện Thoại" value=""/>
    <input type="text" id="txtSearchEmail" placeholder="Email" value=""/>
    <button class="crm_button" id="btnSearchProject">Tìm Đơn Hàng</button>
</div>


<div id="searchProjectResult">
    <?php echo renderProjectTableMobile($projects); ?>
</div>