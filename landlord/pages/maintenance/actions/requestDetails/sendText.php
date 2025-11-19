<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

// Turn PHP warnings into exceptions (good for debugging)
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Read raw POST data
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    // Check if JSON decoding worked
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON received");
    }

    // Validate message content
    if (empty($data['text'])) {
        throw new Exception("Message text is missing");
    }

    // Default sender and receiver IDs
    $sender_id = 2;
    $receiver_id = 3;
    $message = trim($data['text']);

    // Prepare SQL query
    $stmt = $pdo->prepare("
        INSERT INTO chats (sender_id, receiver_id, message, created_at, updated_at)
        VALUES (:sender_id, :receiver_id, :message, NOW(), NOW())
    ");

    // Execute insert
    $stmt->execute([
        ':sender_id' => $sender_id,
        ':receiver_id' => $receiver_id,
        ':message' => $message
    ]);

    // Get the inserted chat ID
    $chat_id = $pdo->lastInsertId();

    // Respond with success JSON
    echo json_encode([
        'status' => 'success',
        'chat_id' => $chat_id,
        'message' => $message,
        'sender_id' => $sender_id,
        'receiver_id' => $receiver_id,
        'created_at' => date('Y-m-d H:i:s')
    ]);
} catch (Throwable $e) {
    // Handle all errors gracefully
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
