<?php
header('Content-Type: application/json');
include '../../../db/connect.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'error' => 'Only POST method is allowed']);
    exit;
}

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Fallback to regular POST if JSON decode fails
if (json_last_error() !== JSON_ERROR_NONE) {
    $data = $_POST;
}

// Validate the ID
$id = isset($data['id']) ? (int)$data['id'] : 0;

if ($id <= 0) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => 'Invalid message ID',
        'received_id' => $id
    ]);
    exit;
}

try {
    // First check if the announcement exists
    $checkStmt = $pdo->prepare("SELECT id FROM announcements WHERE id = ?");
    $checkStmt->execute([$id]);

    if ($checkStmt->rowCount() === 0) {
        http_response_code(404); // Not Found
        echo json_encode(['success' => false, 'error' => 'Message not found']);
        exit;
    }

    // Delete the announcement
    $deleteStmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
    $deleteStmt->execute([$id]);

    if ($deleteStmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        // This should theoretically never happen since we checked existence first
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to delete message']);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
}
?>