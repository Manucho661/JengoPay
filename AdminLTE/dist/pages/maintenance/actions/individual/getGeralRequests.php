<?php
include '../db/connect.php';

try{
    $stm= $pdo->prepare("SELECT * FROM maintenance_requests");
    $stm->execute();
    $requests= $stm->fetch(PDO::FETCH_ASSOC);
    var_dump($requests);
}
catch(PDOException $e){
    $e->getMessage();
}
?>