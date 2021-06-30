<?php



require_once '../mylib.php';

$dbh = getDBH();

  $sql = "Select ID, fullname From users";

        $stmt = $dbh->prepare($sql);

        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);     



        echo json_encode($users);