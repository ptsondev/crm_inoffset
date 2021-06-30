<?php



    require_once '../conf.php';



    $dbh = getDatabaseHandle();

                

    $sql = "Select * From projects order by PID DESC limit 0,100";

    

    $stmt = $dbh->query($sql);    

    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);



    echo json_encode($projects);







