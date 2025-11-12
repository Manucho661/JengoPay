<?php
header('Content-Type: application/json');
require_once '../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Default IDs (you can later make them dynamic)
    $sender_id = 3;
    $receiver_id = 2;

    // Fetch all chat messages between the two users
    $stmt = $pdo->prepare("
    SELECT id, sender_id, receiver_id, message, created_at
    FROM chats
    WHERE (sender_id = ? AND receiver_id = ?)
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY created_at ASC
");

   $stmt->execute([$sender_id, $receiver_id, $receiver_id, $sender_id]);

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'count' => count($messages),
        'messages' => $messages
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
