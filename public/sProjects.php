<?php

use Illuminate\Support\Facades\Log;

require_once('mylib.php');
$dbh = getDBH();
function addSingle($pdo, $r)
{
    $sql = 'INSERT INTO projects (name, source, status, description, assigned, sale_id) VALUES("", 1, 1, "", 1,1)';
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    if ($result == false) {
        throw new Exception(print_r($stmt->errorInfo(), 1) . PHP_EOL . $sql);
    }
    return $pdo->lastInsertId();
}

function updateSingle($pdo, $r)
{
    /* Update theo data hiện tại */
    $sql = 'UPDATE projects SET name=?, phone=?, email=?, source=?, status=?, description=? WHERE id=?';
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute(array($r->name, $r->phone, $r->email, $r->source, $r->status, $r->description, $r->id));
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
    if (isset($dlist->updateList) && !empty($dlist->updateList)) {
        $dlist->updateList = updateList($dlist->updateList);
    }
    if (isset($dlist->addList) && !empty($dlist->addList)) {
        $dlist->addList = addList($dlist->addList);
    }

    echo json_encode($dlist);
    die;
} else {
    $sql = "SELECT MAX(ID) as mm FROM projects";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $maxPID = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $maxPID = $maxPID[0]['mm'];
    //add in session["Projects"];

    /*$sql = "SELECT id, name, phone, email,  source, status, description, assigned FROM projects
    WHERE ID>? GROUP BY ID ORDER BY priority DESC, deadline ASC, ID DESC";*/

    $sql = "SELECT id, name, phone, email,  source, status, description, assigned FROM projects
WHERE ID>? GROUP BY ID ORDER BY ID DESC";


    $stmt = $dbh->prepare($sql);

    $stmt->execute(array($maxPID - 500));

    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sb = "{\"data\":" . json_encode($projects) . "}";
    echo $sb;
}
