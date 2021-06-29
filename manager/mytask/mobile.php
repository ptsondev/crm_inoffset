<?php

require_once '../mylib.php';
require_once '../include.php';
show_header_include('Việc Cần Làm');

$dbh = getDBH();

if(!is_login()){        
    header("Location: /");
    die;
}
$user = $_SESSION['user'];


echo '<h3 id="page-title">Task Của '.$user['fullname'].'</h3>';

    $sql = "Select p.PID, p.name, p.summary, p.steps,p.created,p.deadline,t.note, t.task,t.TID From projects p
         RIGHT JOIN timeline t ON t.PID = p.PID
         Where p.assigned = ? AND t.UID = ? AND t.finish=0 ORDER BY p.deadline ASC";

        $stmt = $dbh->prepare($sql);

        $stmt->execute(array($user['ID'], $user['ID']));

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);    


//echo '<pre>';
//var_dump($projects);
$i=1;
echo '<table id="mobile-my-task">';
foreach($projects as $p){
    $class = $i%2==0? 'odd':'even';
   /* echo '<div class="item '.$class.'">';
        echo '<div class="row">';
            echo '<div class="col-sm-2"><b>PID: </b>'.$p['PID'].'</div>';
            echo '<div class="col-sm-3"><b>Tên Khách: </b>'.$p['name'].'</div>';
            echo '<div class="col-sm-3"><b>Task: </b>'.$p['task'].'</div>';
            echo '<div class="col-sm-4"><b>Deadline: </b>'.$p['deadline'].'</div>';
        echo '</div>';
        echo '<div class="row"><div class="col-sm-12"><b>Mô Tả: </b>'.$p['summary'].'</div></div>';
    echo '</div>';
    */
    
    echo '<tr  class="item '.$class.' '.$p['TID'].'">';
        echo '<td rowspan="5"><b>PID:</b> '.$p['PID'].'</td>'; 
        echo '<td>'.$p['task'].'</td>';
        echo '<td><b>Tên Khách:</b> '.$p['name'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['TID'].'">';
        echo '<td colspan="3"><b>Mô Tả:</b> '.$p['summary'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['TID'].'">';
        echo '<td colspan="3"><b>Ghi Chú:</b> '.$p['steps'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['TID'].'">';
        echo '<td colspan="3"><b>Ghi Chú Riêng:</b> '.$p['note'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['TID'].'">';
        echo '<td><b>Deadline:</b> '.$p['deadline'].'</td>';
        echo '<td><div class="btn-finish-mobile" tid="'.$p['TID'].'" project_id="'.$p['PID'].'">Hoàn Thành</div></td>';
    echo '</tr>';
    $i++;
}



echo '</table>';