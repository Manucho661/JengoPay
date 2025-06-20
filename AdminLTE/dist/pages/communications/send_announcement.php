<?php
header('Content-Type: application/json');
require_once '../db/connect.php'; // $pdo from your earlier config

$recipient = $_POST['recipient'] ?? '';
$priority = $_POST['priority'] ?? '';
$message = $_POST['message'] ?? '';
$status = 'Sent'; // default

if (empty($recipient) || empty($priority) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO announcements (recipient, priority, message, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$recipient, $priority, $message, $status]);

    echo json_encode(['status' => 'success', 'message' => 'Announcement sent']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
