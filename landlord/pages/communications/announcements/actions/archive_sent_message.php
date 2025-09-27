<?php
header('Content-Type: application/json');
include '../../../db/connect.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid message ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE announcements SET status = 'Archived' WHERE id = ? AND status = 'Sent'");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        // Check if message exists
        $check = $pdo->prepare("SELECT id FROM announcements WHERE id = ?");
        $check->execute([$id]);

        if ($check->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Message not found']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Message is already archived or not sent']);
        }
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>