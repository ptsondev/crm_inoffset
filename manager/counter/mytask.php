<?php



require_once '../mylib.php';

$dbh = getDBH();



//update single record in db.

function updateSingle($pdo, $TID, $PID){

    $dbh = getDBH();


    //mylog('xxxx');
    //$discontinued = boolToInt($r['Discontinued']);

    $now = time();

    $sql = "UPDATE timeline SET finish=1, created=? WHERE TID=?";
    
    $stmt = $dbh->prepare($sql);

    $stmt->execute(array($now, $TID));
    
    
    
    // asign lai cho nhan vien in an
    reAssignProject($PID);



    if($result == false) {

        throw new Exception(print_r($stmt->errorInfo(),1).PHP_EOL.$sql);

    }

}





if( isset($_REQUEST['TID'] )){

    $TID = $_REQUEST['TID'];
    $PID = $_REQUEST['PID'];

    updateSingle($dbh, $TID, $PID);

    echo "1";

    

}else{

    //session_start();

   // if (!isset($_SESSION["Projects"]))

    //{ 

        //add in session["Projects"];

        $user = $_SESSION['user'];

        $sql = "Select p.PID, p.email, p.name, p.summary, p.steps,p.created,p.deadline,t.note, t.task,t.TID From projects p
         RIGHT JOIN timeline t ON t.PID = p.PID
         Where p.assigned = ? AND t.UID = ? AND t.finish=0 ORDER BY p.deadline ASC";

        $stmt = $dbh->prepare($sql);

        $stmt->execute(array($user['ID'], $user['ID']));

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);       

        //$_SESSION["Projects"]= json_encode($projects);            

    //} 

    

    //$projects = json_decode($_SESSION["Projects"], true);

    

    

    $sb = "{\"data\":".json_encode($projects)."}";

    echo $sb;

}













