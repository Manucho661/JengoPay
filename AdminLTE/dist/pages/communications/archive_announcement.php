<?php
require_once '../db/connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? 0;

if ($id) {
    $stmt = $pdo->prepare("UPDATE announcements SET status = 'Archived' WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
}
?>
