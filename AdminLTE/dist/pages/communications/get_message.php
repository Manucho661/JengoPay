<?php
include '../db/connect.php';
header('Content-Type: application/json');

if (isset($_GET['message_id']) && is_numeric($_GET['message_id'])) {
    $messageId = (int)$_GET['message_id'];

    // Fetch the message from the database
    $stmt = $pdo->prepare("SELECT sender, content, timestamp, file_path FROM messages WHERE id = :message_id");
    $stmt->execute(['message_id' => $messageId]);

    $message = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($message) {
        // Format message content and include file link if available
        $content = htmlspecialchars($message['content']);
        $timestamp = date('H:i', strtotime($message['timestamp']));
        $fileLink = $message['file_path'] ? "<div class='attachment'><a href='{$message['file_path']}' target='_blank'>Download Attachment</a></div>" : '';

        // Combine content and file link (if any)
        $messageContent = "<div class='message'>
                            <div class='bubble'>$content</div>
                            $fileLink
                            <div class='timestamp'>$timestamp</div>
                          </div>";

        // Return the response in JSON format
        echo json_encode(['success' => true, 'message' => $messageContent]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Message not found']);
    }

    exit;
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid message ID']);
}
?>

