<?php    
$prefix = array('','đặt', 'chỗ', 'xưởng', 'nơi', 'địa chỉ', 'báo giá', 'bảng giá', 'công ty');
$dongtu = array('in', 'làm', 'sản xuất', 'thiết kế');
$danhtu = array('báo cáo thường niên');
$tinhtu = array('giá rẻ', 'chất lượng', 'uy tín', 'tại hcm');

foreach($prefix as $p){
    foreach($dongtu as $dong){
        foreach($danhtu as $danh){
            foreach($tinhtu as $t){
                echo '"';
                if(!empty($p)){
                    echo $p.' ';
                }
                echo $dong.' ';
                echo $danh;
                if(!empty($t)){
                    echo ' '.$t;
                }
                echo '"<br/>';
            }
        }    
    }
}