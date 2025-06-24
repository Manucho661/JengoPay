<?php
header('Content-Type: application/json');

require_once '../db/connect.php'; // Adjust path if needed

// Get ID from POST request
$id = $_POST['id'] ?? null;

// Validate input
if (!$id || !is_numeric($id)) {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid message ID',
        'debug_post' => $_POST,
        'method' => $_SERVER['REQUEST_METHOD']
    ]);
    exit;
}

try {
    // Delete only if status is 'Sent'
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ? AND status = 'Sent'");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No sent message found with that ID']);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
