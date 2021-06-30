<?php



require_once '../mylib.php';

$dbh = getDBH();



//update single record in db.

function updateSingle($pdo, $PID){

    $dbh = getDBH();


    //mylog('xxxx');
    //$discontinued = boolToInt($r['Discontinued']);

    $user = $_SESSION['user'];
    $now = time();

    $sql = "UPDATE timeline SET finish=1, created=? WHERE PID=? AND UID=? AND task=?";
    
    $stmt = $dbh->prepare($sql);

    $stmt->execute(array($now, $PID, $user['ID'], TASK_DESIGN));

    // asign lai cho nhan vien in an

    reAssignProject($PID);



    if($result == false) {

        throw new Exception(print_r($stmt->errorInfo(),1).PHP_EOL.$sql);

    }

}





if( isset($_REQUEST['PID'] )){

    $PID = $_REQUEST['PID'];

    updateSingle($dbh, $PID);

    echo "1";

    

}else{

    //session_start();

   // if (!isset($_SESSION["Projects"]))

    //{ 

        //add in session["Projects"];

        $user = $_SESSION['user'];

        //var_dump($user);

        $sql = "Select p.PID, p.name, p.summary, p.steps,p.created,p.deadline,t.note From projects p
         RIGHT JOIN timeline t ON t.PID = p.PID
         Where p.assigned = ? AND t.UID = ? AND t.task=? AND t.finish=0 ORDER BY p.deadline ASC";

        $stmt = $dbh->prepare($sql);

        $stmt->execute(array($user['ID'],$user['ID'], TASK_DESIGN));

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);       

        //$_SESSION["Projects"]= json_encode($projects);            

    //} 

    

    //$projects = json_decode($_SESSION["Projects"], true);

    

    

    $sb = "{\"data\":".json_encode($projects)."}";

    echo $sb;

}













