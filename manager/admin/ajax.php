<?php



require_once '../mylib.php';

$dbh = getDBH();





if(isset($_REQUEST['action']) && $_REQUEST['action']=='updateTimeLine' ){

    $pid = $_REQUEST['pid'];

    

    $sql = "UPDATE timeline SET UID=?, note=? WHERE TID=?";

    $stmt = $dbh->prepare($sql);

    $stmt->execute(array($_REQUEST['saleUID'], $_REQUEST['noteSale'], $_REQUEST['saleTID']));





    $sql = "UPDATE timeline SET UID=?, note=? WHERE TID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($_REQUEST['xuLyFileUID'], $_REQUEST['noteDesign'], $_REQUEST['xuLyFileTID']));

    $sql = "UPDATE timeline SET UID=?, note=? WHERE TID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($_REQUEST['inUID'], $_REQUEST['notePrint'], $_REQUEST['inTID']));
    $sql = "UPDATE timeline SET UID=?, note=? WHERE TID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($_REQUEST['giaCongUID'], $_REQUEST['noteProcess'], $_REQUEST['giaCongTID']));
    $sql = "UPDATE timeline SET UID=?, note=? WHERE TID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($_REQUEST['giaoHangUID'], $_REQUEST['noteDelivery'], $_REQUEST['giaoHangTID']));
    // set lại assign project đó cho row nào chưa hoàn thành (đang thực hiện)
    reAssignProject($pid);
    
    // update project: set lại xử lý cuối cùng của đơn đó
    $timeline = getTimelineByPID($pid);
    $last_process='';
    foreach ($timeline as $t) {
        if(!empty($t['note'])){
            $last_process=$t['note'];
        }
    }
    $sql = "UPDATE projects SET last_process=? WHERE PID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($last_process, $pid));

    
    echo "1";

}else if(isset($_REQUEST['action']) && $_REQUEST['action']=='addCounter' ){
    $filename = $_REQUEST['filename'];
    $vtid = intval($_REQUEST['vtid']);
    $PID = intval($_REQUEST['pid']);
    $num = intval($_REQUEST['num']);
    $matin = intval($_REQUEST['matin']);
    $note = $_REQUEST['note'];
    $color = $_REQUEST['color'];
    
    // tính phí giấy
     $donGiaTo = 0;
    $sql = "SELECT don_gia_to FROM papers WHERE VTID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($vtid));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    $donGiaTo = $result[0]['don_gia_to'];
    $phiGiay = $donGiaTo * $num;
    

    // tính phí in
    $counter = tinhCounter($vtid, $num, $matin);
    $phiIn = $counter *600;
    if($color!='4M'){
        $phiIn = $counter *220;
    }
    
    // thêm
    if($color=='4M'){
        $sql = "INSERT INTO counter (filename, PID, VTID, num, matin, note, click_colors, phi_giay, phi_in, tong_phi, created) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
    }else{
        $sql = "INSERT INTO counter (filename, PID, VTID, num, matin, note, click_bw,  phi_giay, phi_in, tong_phi, created) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
    }
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($filename, $PID, $vtid, $num, $matin,$note, $counter, $phiGiay, $phiIn, ($phiGiay+$phiIn), time()));
    $newCID = $dbh->lastInsertId();
    
    // trừ bên vật tư
    $rest = 0;
    $sql = "SELECT quantity FROM papers WHERE VTID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($vtid));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    $rest = $result[0]['quantity'];
    $rest -= $num;
    
    $sql = "UPDATE papers SET quantity=? WHERE VTID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($rest, $vtid));
    
    // thêm phần chi cho đơn hàng có mã PID
    if(is_numeric($PID) && $PID!=0){
        $sql = "INSERT INTO thuchi (PID, des, amount, pom, post_date) VALUES (?,?,?,?,?)";
        $stmt = $dbh->prepare($sql);
        $des = $filename. ' - phí giấy & in';
        $stmt->execute(array($PID, $des, ($phiGiay+$phiIn), 0 ,date('m/d/Y')));    
        $newTCID = $dbh->lastInsertId();
        

        //update lại TCID của counter (chỗ này chuối quá)
        $sql = "UPDATE counter SET TCID=? WHERE CID=?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array($newTCID, $newCID));



        // update lại tổng chi của đơn hàng
        $sql3 = 'UPDATE projects SET sum_out=(SELECT sum(amount) FROM thuchi WHERE PID=? AND pom=0) WHERE PID=?';
        $stmt = $dbh->prepare($sql3);
        $stmt->execute(array($PID, $PID));

        updateLoiLo($PID);

    }

    echo renderTableCounter();
}else if(isset($_REQUEST['action']) && $_REQUEST['action']=='removeCounter' ){
    $CID = intval($_REQUEST['CID']);
    $sql = "DELETE FROM counter WHERE CID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($CID));

    // xoá luôn thu chi tham chiếu đến counter đó
    $TCID = intval($_REQUEST['TCID']);
    $sql = "DELETE FROM thuchi WHERE TCID=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($TCID));

    echo "1";
}else if(isset($_REQUEST['action']) && $_REQUEST['action']=='removePicture' ){
     $picture_id = intval($_REQUEST['picture_id']);
    $sql = "DELETE FROM pictures WHERE picture_id=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array($picture_id));
}