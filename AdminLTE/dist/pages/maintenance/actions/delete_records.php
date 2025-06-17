<?php
require_once '../../db/connect.php';

try{
    $requestID = $_GET['request_id'] ?? null;

        if ($requestID) {
        // Prepare and execute the update query
        $stmt = $pdo->prepare("DELETE FROM maintenance_requests WHERE id = :id");
        $stmt->execute([
            ':id' => $requestID
        ]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing item_id']);
    }
}
catch(PDOException $e){

}

?>