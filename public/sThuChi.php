<?php



use Illuminate\Support\Facades\Log;

require_once('mylib.php');
$dbh = getDBH();



function addSingle($pdo, $r)
{
    $created = date('m/d/Y');
    $sql = 'INSERT INTO thuchi (amount, pom, title) VALUES(?, ?, "")';
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(array($r->amount, 0));
    if ($result == false) {
        throw new Exception(print_r($stmt->errorInfo(), 1) . PHP_EOL . $sql);
    }
    return $pdo->lastInsertId();
}

//update single record in db.

function updateSingle($pdo, $r)
{

    //$discontinued = boolToInt($r['Discontinued']);
    $sql = 'UPDATE thuchi SET title=?, pid=?, amount=?, pom=? WHERE id=?';
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(array($r->title, $r->pid, $r->amount, $r->pom, $r->id));

    /*
    $sql2 = 'UPDATE projects SET sum_in=(SELECT sum(amount) FROM thuchi WHERE PID=? AND pom=1) WHERE PID=?';
    $stmt = $pdo->prepare($sql2);
    $result = $stmt->execute(array($r->PID, $r->PID));

    $sql3 = 'UPDATE projects SET sum_out=(SELECT sum(amount) FROM thuchi WHERE PID=? AND pom=0) WHERE PID=?';
    $stmt = $pdo->prepare($sql3);
    $result = $stmt->execute(array($r->PID, $r->PID));

    */
    if ($result == false) {
        throw new Exception(print_r($stmt->errorInfo(), 1) . PHP_EOL . $sql);
    }
}

//delete single record from db.

function deleteSingle($pdo, $r)
{

    $sql = "delete from thuchi where TCID = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(array($r->TCID));
    if ($result == false) {
        throw new Exception(print_r($stmt->errorInfo(), 1) . PHP_EOL . $sql);
    }
}

function addList($addList)
{
    $dbh = getDBH();
    foreach ($addList as &$r) {
        $r->id = addSingle($dbh, $r);
    }
    return $addList;
}

function updateList($updateList)
{
    $dbh = getDBH();
    foreach ($updateList as $r) {
        updateSingle($dbh, $r);
    }
    return $updateList;
}


if (isset($_REQUEST['list'])) {
    $dlist = json_decode($_REQUEST['list']);
    //echo json_encode($dlist);die;
    if (isset($dlist->updateList) && !empty($dlist->updateList)) {
        $dlist->updateList = updateList($dlist->updateList);
    }

    if (isset($dlist->addList) && !empty($dlist->addList)) {
        // check PID roi moi add de khoi trung
        $dlist->addList = addList($dlist->addList);
    }
    //if(isset($dlist["deleteList"])){
    //    $dlist["deleteList"] = deleteList($dlist["deleteList"]);
    //}
    echo json_encode($dlist);
    die;
} else {
    $sql = "SELECT * FROM thuchi ORDER BY id DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $thuchis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sb = "{\"data\":" . json_encode($thuchis) . "}";
    echo $sb;
}
