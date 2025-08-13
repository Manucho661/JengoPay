<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

try {
    // Get JSON input from fetch
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'], $data['status'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing ID or status'
        ]);
        exit;
    }

    $requestId = (int)$data['id'];
    $newStatus = $data['status']; // expected 'available' or 'unavailable'

    // Update the availability in maintenance_requests
    $stmt = $pdo->prepare("
        UPDATE maintenance_requests
        SET availability = :status
        WHERE id = :id
    ");
    $stmt->execute([
        ':status' => $newStatus,
        ':id' => $requestId
    ]);

    // Check if update affected any row
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Availability updated successfully',
            'newStatus' => $newStatus
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No rows updated. ID may not exist or status unchanged.'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
