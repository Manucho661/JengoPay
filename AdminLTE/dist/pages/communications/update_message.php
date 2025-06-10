<?php
include '../db/connect.php';
header('Content-Type: application/json');

$messageId = $_POST['message_id'] ?? null;
$content = trim($_POST['content'] ?? '');

if (!$messageId || $content === '') {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$stmt = $pdo->prepare("UPDATE messages SET content = :content WHERE message_id = :message_id");
$success = $stmt->execute([
    'content' => $content,
    'message_id' => $messageId
]);

echo json_encode(['success' => $success]);
