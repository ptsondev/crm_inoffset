<?php

require_once '../mylib.php';
require_once '../include.php';
show_header_include('Gia Công');

$dbh = getDBH();




        $sql = "SELECT * FROM projects WHERE status=? ORDER BY PID ASC";

        $stmt = $dbh->prepare($sql);

        $stmt->execute(array(STT_DUYET_IN));

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);       



//echo '<pre>';
//var_dump($projects);
$i=1;
echo '<table id="mobile-my-task">';
foreach($projects as $p){
    $class = ($i%2==0)? 'odd':'even';
    
    
    echo '<tr  class=" item '.$class.' '.$p['PID'].'">';
        echo '<td class="split" rowspan="4"><b>PID:</b> '.$p['PID'].'</td>'; 
        
        echo '<td><b>Tên Khách:</b> '.$p['name'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['PID'].'">';
        echo '<td colspan="3"><b>SDT:</b> '.$p['phone'].'</td>';
    echo '</tr>';
    echo '<tr  class="item '.$class.' '.$p['PID'].'">';
        echo '<td colspan="3"><b>Hình ảnh:</b> '.showPictures($p['PID']).'</td>';
    echo '</tr>';
    echo '<tr  class="split item '.$class.' '.$p['PID'].'">';
        echo '<td colspan="3"><b>Gia công & Giao Hàng:</b><br/> '.nl2br($p['delivery_note']).'</td>';
    echo '</tr>';
   
    $i++;
}



echo '</table>';

function showPictures($PID){
    $arrPics = loadProjectPictures($PID);
    
	$i=1;
    $html= '<div id="project_pictures">';
foreach ($arrPics as $pic_id => $url){
    $html.= '<div class="pic pic-'.$pic_id.'">';
		$html.= '<label>'.$i++.'</label>';
        $html.= '<img src="/'.$url.'" class="picture"  />';
    $html.= '</div>';
}
$html.= '</div>';
    return $html;
}
?>