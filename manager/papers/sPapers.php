<?php



require_once '../mylib.php';

$dbh = getDBH();



function addSingle($pdo, $r){

 //   mylog($r);

    $created = time();


    $sql = 'INSERT INTO papers (name) VALUES ("x")';

    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute();

    
    if($result == false) {

        throw new Exception(print_r($stmt->errorInfo(),1).PHP_EOL.$sql);

    }

    return $pdo->lastInsertId();

}

//update single record in db.

function updateSingle($pdo, $r){


   //mylog($r);
    /* Update theo data hiện tại */

    $sql = 'UPDATE papers SET name=?,quantity=?,don_gia_to=?, click_num=? WHERE VTID=?';

    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute(array($r->name,$r->quantity,$r->don_gia_to, $r->click_num, $r->VTID));

 
    if($result == false) {

        throw new Exception(print_r($stmt->errorInfo(),1).PHP_EOL.$sql);

    }





}

//delete single record from db.

function deleteSingle($pdo, $r)

{

    $sql = "delete from papers where VTID = ?";

 

    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute(array( $r['VTID']));

 

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

    $projects = json_decode($_SESSION["papers"], true);         



    foreach ($deleteList as $project)

    {

        $projectID = $project["VTID"];

        foreach($projects as $i => $project2){

            if($project2["VTID"] == $projectID){            

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

  
       

        $sql = "Select * From papers ORDER BY name ASC";

        

        $stmt = $dbh->prepare($sql);

        $stmt->execute();

        $papers = $stmt->fetchAll(PDO::FETCH_ASSOC);       

    

    $sb = "{\"data\":".json_encode($papers)."}";

    echo $sb;

}













