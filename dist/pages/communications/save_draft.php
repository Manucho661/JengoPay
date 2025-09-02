<?php
require_once '../db/connect.php'; // adjust path

$data = json_decode(file_get_contents('php://input'), true);

$recipient = $data['recipient'] ?? '';
$priority = $data['priority'] ?? 'Normal';
$message = $data['message'] ?? '';
$now = date('Y-m-d H:i:s');

if ($recipient || $message) {
    $stmt = $pdo->prepare("INSERT INTO announcements (recipient, priority, message, status, created_at)
                           VALUES (?, ?, ?, 'Draft', ?)");
    $stmt->execute([$recipient, $priority, $message, $now]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
