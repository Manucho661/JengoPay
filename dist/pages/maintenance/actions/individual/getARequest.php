<?php
include '../db/connect.php';

if (isset($_GET['id'])) {
    $requestId = intval($_GET['id']); // Convert to integer for safety
    // echo "The request ID is: " . $requestId;
    try {
        $stm = $pdo->prepare("
    SELECT * 
    FROM maintenance_requests
    LEFT JOIN maintenance_photos 
        ON maintenance_requests.id = maintenance_photos.maintenance_request_id
    LEFT JOIN maintenance_request_proposals 
        ON maintenance_requests.id = maintenance_request_proposals.maintenance_request_id
    LEFT JOIN maintenance_payments 
        ON maintenance_requests.id = maintenance_payments.maintenance_request_id
    WHERE maintenance_requests.id = ?
    ");

    $stm->execute([$requestId]);
    $request = $stm->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
       echo $e->getMessage();
    }
} else {
    echo "No ID provided.";
}
