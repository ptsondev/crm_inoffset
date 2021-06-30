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

echo '<h3 id="page-title">Hàng Cần Giao</h3>';

        $sql = "SELECT * FROM projects WHERE status=4 ORDER BY PID ASC";

        $stmt = $dbh->prepare($sql);

        $stmt->execute();

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);       



//echo '<pre>';
//var_dump($projects);
$i=1;
echo '<table id="mobile-my-task">';
foreach($projects as $p){
    $class = $i%2==0? 'odd':'even';
    $class='';
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
    
    echo '<tr  class="item '.$class.' '.$p['PID'].'">';
        echo '<td rowspan="5"><b>PID:</b> '.$p['PID'].'</td>'; 
        
        echo '<td><b>Tên Khách:</b> '.$p['name'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['PID'].'">';
        echo '<td colspan="3"><b>SDT:</b> '.$p['phone'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['PID'].'">';
        echo '<td colspan="3"><b>Địa Chỉ:</b> '.$p['delivery_address'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['PID'].'">';
        echo '<td colspan="3"><b>Ghi Chú Riêng:</b> '.$p['delivery_note'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['PID'].'">';
        //echo '<td><div class="btn-finish-mobile"  project_id="'.$p['PID'].'">Hoàn Thành</div></td>';
        echo '<td>';
            echo '<span class="submit_finish chua_thu" project_id="'.$p['PID'].'" new_status="'.STT_DA_GIAO_CHUA_THU.'"><input type="button" value="Đã Giao & Chưa Thu Tiền" /></span>';
            echo '<span class="submit_finish" project_id="'.$p['PID'].'" new_status="'.STT_DA_GIAO_THU_ROI.'"><input type="button" value="Đã Giao & Đã Thu Tiền" /></span>';
        echo '</td>';

    echo '</tr>';
    $i++;
}



echo '</table>';


?>
<script>

       $(document).on('click', '.submit_finish',function(){

            var PID = $(this).attr('project_id');
      
            var NewStatus = $(this).attr('new_status');
            
             $.ajax({ url: "delivery.php",

                            async: false,

                            dataType: "JSON",

                            data:{PID:PID, NewStatus:NewStatus},

                            success: function (response) {                                

                            }

            });

            $('tr.'+PID).remove();

        });
</script>