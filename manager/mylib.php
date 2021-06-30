<?php    
require_once('conf.php');
session_start();
$dbh = getDBH();
$sql = "Select * From variables";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $variables = $stmt->fetchAll(PDO::FETCH_ASSOC);  
        //echo '<pre>';var_dump($variables);die;

$tmp = array();        
$tmp['gia_1_ram_C300']=$variables[3]['value'];
$tmp['gia_1_ram_C250']=$variables[4]['value'];
$tmp['gia_1_ram_C200']=$variables[5]['value'];
$tmp['gia_1_ram_C150']=$variables[6]['value'];
$GLOBALS['variables']=$tmp;
//var_dump($GLOBALS['variables']);

?>

<?php 
/* functions */
function getAllUsers(){
    $dbh = getDBH();
    $sql = 'SELECT * FROM users ORDER BY fullname';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
}

function display_select_users($selected=0){
    echo '<option value="0">-- Giao cho --</option>';
    $users = getAllUsers();
    foreach($users as $u){
        if($u['ID']==$selected){
            echo '<option selected="selected" value="'.$u['ID'].'">'.$u['fullname'].'</option>';
        }else{
            echo '<option value="'.$u['ID'].'">'.$u['fullname'].'</option>';
        }
    }
}

function getTimelineByPID($pid){
    //error_log(print_r($pid, true));die;
    $dbh = getDBH();
    $sql = 'SELECT * FROM timeline WHERE PID=? ORDER BY PID ASC';
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($pid));
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
}

function display_number($num, $so0cuoi=0){
    return number_format($num, $so0cuoi, '.', '.');
}

function is_login(){
    if(isset($_SESSION['user'])){
        return TRUE;
    }
    return FALSE;
}

function user_login($name, $pass){
    $dbh = getDBH();
     $sql = "Select * From users WHERE name=? AND password=?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array($name, md5($pass)));
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);  

    if(!empty($users)){
        $users=$users[0];
        return $users;
    }
    return FALSE;
}

function user_load($uid){
    $dbh = getDBH();
     $sql = "Select * From users WHERE ID=?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array($uid));
        $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);  

    if(!empty($staff)){
       return $staff[0];
    }
    return FALSE;
}

function getCounterTable(){
    $dbh = getDBH();   
     $sql = "SELECT * FROM counter ORDER BY CID DESC LIMIT 200";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  

    return $result;
}

function getPaperType(){
    $dbh = getDBH();
     $sql = "SELECT * FROM papers ORDER BY name ASC";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array('giay'));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  

    return $result;        
}

function getArrayPaper(){
    $papers = getPaperType();    
    $result = array();
    foreach($papers as $p){
        $result[$p['VTID']]=$p['name'];
    }
    return $result;
}

function tinhCounter($vtid=1, $num=1, $mat=1){
    $banIn = 1;
    $dbh = getDBH();
    $sql = "SELECT click_num FROM papers WHERE VTID=?";
     $stmt = $dbh->prepare($sql);
        $stmt->execute(array($vtid));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    $banIn = $result[0]['click_num'];
    return $banIn * $num * $mat;
}

function getListPaper($id){
    $papers = getPaperType();        
    $html = '<select id="'.$id.'">';
    foreach($papers as $p){
        $html.= '<option value="'.$p['VTID'].'">'.$p['name'].'</option>';
    }
    $html.='</select>';
    return $html;
}

function renderTableCounter(){
    $counters = getCounterTable();
    $arrPaper = getArrayPaper();
    $html = '';
    foreach ($counters as $c){
        $html.= '<tr class="item">';
            //$html.= '<td class="CID">'.$c['CID'].'</td>';
            $html.= '<td class="PID">'.$c['PID'].'</td>';
            $html.= '<td class="filename">'.$c['filename'].'</td>';
            $html.= '<td class="vtid">'.$arrPaper[$c['VTID']].'</td>';
            $html.= '<td class="num">'.$c['num'].'</td>';
            $html.= '<td class="matin">'.$c['matin'].'</td>';            
            $html.= '<td class="click_colors">'.$c['click_colors'].'</td>';            
            $html.= '<td class="click_bw">'.$c['click_bw'].'</td>';            
            $html.= '<td class="note">'.$c['note'].'</td>';
            $html.= '<td class="created">'.date('h:i:s - d/m/Y',$c['created']).'</td>';
			if(TRUE){ // nếu là admin mới hiện 3 cột này
				$html.='<td class="phi_giay">'.number_format ($c['phi_giay']).'</td>';
				$html.='<td class="phi_in">'.number_format ($c['phi_in']).'</td>';
				$html.='<td class="tong_phi">'.number_format ($c['tong_phi']).'</td>';
			}
            $html.= '<td class="action"><button TCID="'.$c['TCID'].'" CID="'.$c['CID'].'" class="btnRemove">Xóa</button</td>';
        $html.= '</tr>';
    }       
    return $html;
}


function tinhGiaGiay($soTo, $loai, $size='65x86'){
    if($loai == 'mythuat'){
        return 15000*$soTo;
    }
     $dbh = getDBH();
     $sql = "SELECT * FROM variables WHERE name=?";
     $stmt = $dbh->prepare($sql);
     $stmt->execute(array($loai.'_'.$size));
     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  
     if($result){         
         return $result[0]['value']/500*$soTo;
     }
     return FALSE;     
}

// https://docs.google.com/spreadsheets/d/1xnfDQw6xw2ib4ER29Edjl28dlvCqzLHF_Q1axVfmcBc/edit#gid=1079513478
function tinhGiaInOffset($size, $soBanIn, $mau=4){
    if($size=='32x43'){
        if($mau==4){
            if($soBanIn <= 3000){
                return 600000;
            }else{
                return 700000;
            }
        }else{
            return 300000;
        }
    }else if($size=='43x65'){
        if($mau==4){
            if($soBanIn <= 3000){
                return 820000;
            }else if($soBanIn  > 3000 && $soBanIn <=5000){
                return 910000;
            }else if($soBanIn  > 5000){
                return ($soBanIn*40*4)+220000;
            }
        }else{
            if($soBanIn <= 3000){
                return 400000;
            }else if($soBanIn  > 3000 && $soBanIn <=5000){
                return 450000;
            }else if($soBanIn  > 5000){
                return ($soBanIn*40)+220000;
            }
        }
    }else if($size=='65x86'){
        if($mau==4){
            if($soBanIn <= 3000){
                return 1250000;
            }else if($soBanIn  > 3000 && $soBanIn <=5000){
                return 1350000;
            }else if($soBanIn  > 5000 && $soBanIn <=20000){
                return ($soBanIn*60*4)+380000;
            }else if($soBanIn  > 20000){
                return ($soBanIn*55*4)+380000;
            }
        }else{
            if($soBanIn <= 3000){
                return 500000;
            }else if($soBanIn  > 3000 && $soBanIn <=5000){
                return 600000;
            }else if($soBanIn  > 5000 && $soBanIn <=20000){
                return ($soBanIn*60)+380000;
            }else if($soBanIn  > 20000){
                return ($soBanIn*55)+380000;
            }
        }
    }else if($size=='54x79'){
        if($mau==4){
            if($soBanIn <= 3000){
                return 910000;
            }else if($soBanIn  > 3000 && $soBanIn <=5000){
                return 1010000;
            }else if($soBanIn  > 5000){
                return ($soBanIn*45*4)+260000;
            }
        }else{
            if($soBanIn <= 3000){
                return 400000;
            }else if($soBanIn  > 3000 && $soBanIn <=5000){
                return 450000;
            }else if($soBanIn  > 5000){
                return ($soBanIn*45)+260000;
            }
        }
    }
}     

// hoàn thành bước A và tự assign bước B lại cho người tiếp theo
function reAssignProject($pid){
    $dbh = getDBH();

    $timeline = getTimelineByPID($pid);
    //error_log(print_r($timeline, true));die;
    foreach ($timeline as $t) {
        if($t['finish']==0){
            $sql = "UPDATE projects SET assigned=? WHERE PID=?";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array($t['UID'],$pid));
            break;
        }
    }
 }   

function display_site_header(){    
    if(is_login()){
        $user = $_SESSION['user'];
        echo '<div id="header-right">';
            echo '<span>Welcome, '.$user['fullname'].'</span>';
            echo '<a href="/logout.php">Thoát</a>';
        echo '</div>';

        if($user['role']==ROLE_ADMIN){
            echo '<div id="header-left">';
                echo '<a href="/manager/admin">Đơn Hàng</a>';
                echo '<a href="/manager/papers">Giấy</a>';                
				echo '<a href="/manager/counter">Counter | In Nhanh</a>';
                echo '<a href="/manager/delivery">Giao Hàng</a>';
                echo '<a href="/manager/thuchi">Thu Chi</a>';
				//echo '<a href="/manager/staff">Nhân Sự</a>';
                
            echo '</div>';
        }
    }
}

 function mylog($var){
    error_log(print_r($var, true));die;
 }

function is_mobile(){
    $useragent=$_SERVER['HTTP_USER_AGENT'];

    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
        return true;
    }
    return false;

}


function updateTimeStampToString(){
    $dbh = getDBH();
    $sql = "SELECT created FROM thuchi";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $values= $stmt->fetchAll(PDO::FETCH_ASSOC);  
    foreach($values as $v){
        $date = date('m/d/Y', $v['created']);
        //var_dump($date);die;
        $sql = "UPDATE thuchi SET post_date=? WHERE created=?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array($date, $v['created']));
    }
    die;

}
//updateTimeStampToString();


function updateLoiLo($PID){
    $dbh = getDBH();
    $sql = "SELECT sum_in, sum_out FROM projects WHERE PID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($PID));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    
    $ll = $result[0]['sum_in']-$result[0]['sum_out'];
    $sql = "UPDATE projects SET loilo =? WHERE PID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($ll, $PID));    
}


function loadProjectPictures($PID){
    $dbh = getDBH();
    $sql = "SELECT * FROM pictures WHERE PID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($PID));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  

    $arrPics = array();
    if(is_array($result)){
        foreach ($result as $item) {
            $arrPics[$item['picture_id']]=$item['url'];
        }
    }
    return $arrPics;
}

function sher_debug($var){
    $file = dirname(__FILE__).'\debug.txt';
    file_put_contents($file, print_r($var, true), FILE_APPEND);
    die;
}


