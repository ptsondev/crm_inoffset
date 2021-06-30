<?php



require_once '../mylib.php';

$dbh = getDBH();



//update single record in db.

function updateSingle($pdo, $r){

    $dbh = getDBH();

    $sql = "UPDATE projects SET phone=?, delivery_address=?, delivery_note=? WHERE PID=?";
    
    $stmt = $dbh->prepare($sql);

    $stmt->execute(array($r->phone, $r->delivery_address, $r->delivery_note, $r->PID));
    
    


    if($result == false) {

        throw new Exception(print_r($stmt->errorInfo(),1).PHP_EOL.$sql);

    }

}





function updateList($updateList){    

    $dbh = getDBH();

    foreach ($updateList as $r)

    {

        updateSingle($dbh, $r);

    }

    return $updateList;

}

if( isset($_REQUEST['list']) || isset($_REQUEST['PID'])){

    if(isset($_REQUEST['PID'])){
        $dbh = getDBH();
        $sql = "UPDATE projects SET status=? WHERE PID=?";
    
         $stmt = $dbh->prepare($sql);

        $stmt->execute(array($_REQUEST['NewStatus'], $_REQUEST['PID']));
        mylog($sql);

    }else{

        //mylog($_REQUEST);

        $dlist = json_decode($_REQUEST['list']);  

        //echo json_encode($dlist);die;

        if(isset($dlist->updateList) && !empty($dlist->updateList)){

            $dlist->updateList = updateList($dlist->updateList);

        }  

        

        if(isset($dlist->addList) && !empty($dlist->addList)){

            // check PID roi moi add de khoi trung

            $dlist->addList = addList($dlist->addList);        

        }    

        echo json_encode($dlist);die;
    }
    

    

}else{

    //session_start();

   // if (!isset($_SESSION["Projects"]))

    //{ 

        //add in session["Projects"];

        $user = $_SESSION['user'];

        $sql = "SELECT * FROM projects WHERE status=4 ORDER BY PID DESC";

        $stmt = $dbh->prepare($sql);

        $stmt->execute();

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);       

        //$_SESSION["Projects"]= json_encode($projects);            

    //} 

    

    //$projects = json_decode($_SESSION["Projects"], true);

    

    

    $sb = "{\"data\":".json_encode($projects)."}";

    echo $sb;

}













