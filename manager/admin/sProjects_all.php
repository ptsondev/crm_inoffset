<?php



require_once '../mylib.php';

$dbh = getDBH();



function addSingle($pdo, $r){

    //mylog($r);

    $created = time();

    /*$sql = 'INSERT INTO projects (name,phone,address,status,price_out,price_in,steps,summary,created) VALUES (?,?,?,?,?,?,?,?,?)';

    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute(array($r->name, $r->phone, 

    $r->address, $r->status, $r->price_out, $r->price_in, $r->steps, $r->summary, $created));

    */

    $sql = 'INSERT INTO projects (name,status,created) VALUES("x", 1, "'.$created.'")';

    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute();

    

    if($result == false) {

        throw new Exception(print_r($stmt->errorInfo(),1).PHP_EOL.$sql);

    }

    return $pdo->lastInsertId();

}

//update single record in db.

function updateSingle($pdo, $r){

    //$discontinued = boolToInt($r['Discontinued']);



    /* Lấy status cũ trước */

    $sql = 'SELECT status FROM projects WHERE PID=?';

    $stmt = $pdo->prepare($sql);

    $stmt->execute(array($r->PID));

    $oldStatus = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    $oldStatus = $oldStatus[0]['status'];



    /* Update theo data hiện tại */

    $sql = 'UPDATE projects SET name=?,source=?,phone=?,email=?,deadline=?,status=?,price_out=?,steps=?,summary=?,summary_design=?, delivery_note=?, sum_out=?,sum_in=?,saleID=? WHERE PID=?';

    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute(array($r->name,$r->source,$r->phone,$r->email,$r->deadline,$r->status,$r->price_out,$r->steps,$r->summary,$r->summary_design,$r->delivery_note,

                                   $r->sum_out,$r->sum_in, $r->saleID, $r->PID));

 

    if($result == false) {

        throw new Exception(print_r($stmt->errorInfo(),1).PHP_EOL.$sql);

    }





    /* neu vua moi ky => tao timeline ghi nhan sale */

    if($oldStatus!=STT_DA_KY && ($r->status == STT_DA_KY || $r->status==STT_DUYET_IN)){

        $user = $_SESSION['user'];

        //error_log(print_r($user, true));die;

        $cur = time();

        $sql = 'INSERT INTO timeline(PID, UID, task, finish, created) VALUES(?,?,?,?,?)';

        $stmt = $pdo->prepare($sql);

        $tmp = $stmt->execute(array($r->PID, $user['ID'], TASK_SALE, 1, $cur));

        //error_log(print_r($tmp, true));die;



        $sql = 'INSERT INTO timeline(PID, UID, task, finish) VALUES(?,?,?,?)';

        $stmt = $pdo->prepare($sql);

        $tmp = $stmt->execute(array($r->PID, 0, TASK_DESIGN, 0));



        $sql = 'INSERT INTO timeline(PID, UID, task, finish) VALUES(?,?,?,?)';

        $stmt = $pdo->prepare($sql);

        $tmp = $stmt->execute(array($r->PID, 0, TASK_PRINT, 0));



        $sql = 'INSERT INTO timeline(PID, UID, task, finish) VALUES(?,?,?,?)';

        $stmt = $pdo->prepare($sql);

        $tmp = $stmt->execute(array($r->PID, 0, TASK_PROCESS, 0));



        $sql = 'INSERT INTO timeline(PID, UID, task, finish) VALUES(?,?,?,?)';

        $stmt = $pdo->prepare($sql);

        $tmp = $stmt->execute(array($r->PID, 0, TASK_DELIVERY, 0));





        $sql = 'UPDATE projects SET assigned=? WHERE PID=?';

        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute(array($user['ID'],$r->PID));

    }

}

//delete single record from db.

function deleteSingle($pdo, $r)

{

    $sql = "delete from products where ProductID = ?";

 

    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute(array( $r['ProductID']));

 

    if($result == false) {

        throw new Exception(print_r($stmt->errorInfo(),1).PHP_EOL.$sql);

    }

}



function addList($addList)    

{

    $dbh = getDBH();

    foreach ($addList as &$r)

    {

        $r->PID = addSingle($dbh, $r);

    }

    return $addList;

}

function copyProject(&$sProject, $uProject){    

    $sProject["PID"] = $uProject["PID"];

    $sProject["name"] = $uProject["name"];

    $sProject["phone"] = $uProject["phone"];

    $sProject["status"] = $uProject["status"];

    $sProject["price_out"] = $uProject["price_out"];

    $sProject["steps"] = $uProject["steps"]; 

    $sProject["summary"] = $uProject["summary"]; 

    

    //....

}

function updateList($updateList)

{    

    $dbh = getDBH();

    foreach ($updateList as $r)

    {

        updateSingle($dbh, $r);

    }

    return $updateList;

}

function deleteList($deleteList)

{

    $projects = json_decode($_SESSION["Projects"], true);         



    foreach ($deleteList as $project)

    {

        $projectID = $project["PID"];

        foreach($projects as $i => $project2){

            if($project2["PID"] == $projectID){            

                unset($projects[$i]);

                break;

            }        

        }

    }

    $_SESSION["Projects"]= json_encode($projects);        

    return $deleteList;

}



if( isset($_REQUEST['list'])){

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

    

    

}else{


       

        $sql = "SELECT PID, name, source, phone, email, status, last_process, created FROM projects ORDER BY PID DESC";

        

        $stmt = $dbh->prepare($sql);

        $stmt->execute();

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);       



    

    $sb = "{\"data\":".json_encode($projects)."}";

    echo $sb;

}













