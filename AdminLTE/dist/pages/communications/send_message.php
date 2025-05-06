<?php
include '../db/connect.php'; // Ensure $pdo is defined

$message = $_POST['message'] ?? '';
$sender = $_POST['sender'] ?? '';
$thread_id = $_POST['thread_id'] ?? 0;

if ($message && $sender && $thread_id) {
    $stmt = $pdo->prepare("INSERT INTO messages (thread_id, sender, content) VALUES (:thread_id, :sender, :content)");
    $stmt->execute([
        'thread_id' => $thread_id,
        'sender' => $sender,
        'content' => $message
    ]);

    echo "Message sent";
} else {
    echo "Invalid data";
}