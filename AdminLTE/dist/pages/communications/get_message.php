<?php
include '../db/connect.php';

if (isset($_GET['id'])) {
    // Fetch the main message and replies (assuming you have replies stored separately)
    $stmt = $pdo->prepare("
        SELECT message, sender_type, created_at
        FROM communication_replies
        WHERE thread_id = ?
        ORDER BY created_at ASC
    ");
    $stmt->execute([$_GET['id']]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($messages);
}
