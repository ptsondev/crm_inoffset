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



       $sql = "Select * From projects WHERE PID>? GROUP BY PID ORDER BY PID DESC";


        $stmt = $dbh->prepare($sql);

        $stmt->execute(array($maxPID-1000));

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);          



//echo '<pre>';
//var_dump($projects);
$i=1;
echo '<table id="mobile-my-task">';
foreach($projects as $p){
    $class = ($i%2==0)? 'odd':'even';
    
    
    echo '<tr  class=" item '.$class.' '.$p['PID'].'">';
        echo '<td class="split" rowspan="5"><b>PID:</b> '.$p['PID'].' <br/> '.displayStatusBySTTID($p['status']) . '</td>'; 
        
        echo '<td><b>Tên Khách:</b> '.$p['name'].' </td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['PID'].'">';
        echo '<td><b>SDT:</b> '.$p['phone'].' - <b>Email:</b> '.$p['email'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['PID'].'">';
        echo '<td><b>Mô Tả & Quy Cách:</b></br> '.nl2br($p['summary']).'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['PID'].'">';
        echo '<td><b>Ghi Chú Chung:</b></br> '.nl2br($p['steps']).'</td>';
    echo '</tr>';
    echo '<tr  class="split item '.$class.' '.$p['PID'].'">';
        echo '<td><b>Gia công & Giao Hàng:</b><br/> '.nl2br($p['delivery_note']).'</td>';
    echo '</tr>';
   
    $i++;
}



echo '</table>';