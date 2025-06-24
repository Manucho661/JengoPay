<?php
header('Content-Type: application/json');
require_once '../db/connect.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'No draft ID provided']);
    exit;
}

try {
    // Only delete if status is 'Draft'
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ? AND status = 'Draft'");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No draft found with that ID']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>