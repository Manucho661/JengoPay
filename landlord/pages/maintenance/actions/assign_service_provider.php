<?php
header('Content-Type: application/json');

require_once '../../db/connect.php';


// Get POST data
$requestID = $_POST['maintenance_request_id'] ?? null;
$providerID = $_POST['service_provider_id'] ?? null;

if ($requestID && $providerID) {
    try {
        $stmt = $pdo->prepare("UPDATE maintenance_requests SET provider_id = ? WHERE id = ?");
        $stmt->execute([$providerID, $requestID]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        http_response_code(500);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing request_id or provider_id']);
    http_response_code(400);
}
