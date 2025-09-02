<?php
require_once '../../db/connect.php';

try{
    $requestID = $_GET['request_id'] ?? null;
    $status = 'completed';

        if ($requestID) {
        // Prepare and execute the update query
        $stmt = $pdo->prepare("UPDATE maintenance_requests SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $status,
            ':id' => $requestID
        ]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing item_id or status']);
    }
}
catch(PDOException $e){

}
?>