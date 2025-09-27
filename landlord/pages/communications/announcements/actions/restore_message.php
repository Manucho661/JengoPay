<?php
header('Content-Type: application/json');
require_once '../../../db/connect.php';

$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['success' => false, 'error' => 'Invalid message ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE announcements SET status = 'Sent' WHERE id = ? AND status = 'Archived'");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Message not found or not restorable']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
