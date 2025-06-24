<?php
header('Content-Type: application/json');
require_once '../db/connect.php';

$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['success' => false, 'error' => 'Invalid announcement ID']);
    exit;
}

try {
    // Check if message exists
    $checkStmt = $pdo->prepare("SELECT status FROM announcements WHERE id = ?");
    $checkStmt->execute([$id]);
    $message = $checkStmt->fetch();

    if (!$message) {
        echo json_encode(['success' => false, 'error' => 'No message found with that ID']);
        exit;
    }

    if ($message['status'] !== 'Sent') {
        echo json_encode([
            'success' => false,
            'error' => 'Message is not a sent announcement (status: ' . $message['status'] . ')'
        ]);
        exit;
    }

    // Perform deletion
    $deleteStmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
    $deleteStmt->execute([$id]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

