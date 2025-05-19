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
        // Format content and timestamp
        $content = nl2br(htmlspecialchars($message['content']));
        $timestamp = date('H:i', strtotime($message['timestamp']));
        $attachmentHtml = '';

        // If file exists, embed it accordingly
        if (!empty($message['file_path']) && file_exists($message['file_path'])) {
            $path = $message['file_path'];
            $basename = basename($path);
            $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
            $fileData = file_get_contents($path);
            $base64 = base64_encode($fileData);
            $mimeType = mime_content_type($path);

            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
                $attachmentHtml = "<div class='attachment-image mt-2'>
                                    <img src='data:$mimeType;base64,$base64' alt='$basename' class='img-thumbnail' style='max-width:200px; max-height:150px;'>
                                    <div class='file-name small'>$basename</div>
                                  </div>";
            } elseif ($ext === 'pdf') {
                $attachmentHtml = "<div class='attachment-file mt-2'>
                                    <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                                        <i class='fas fa-file-pdf'></i> $basename
                                    </a>
                                  </div>";
            } else {
                $attachmentHtml = "<div class='attachment-file mt-2'>
                                    <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                                        <i class='fas fa-download'></i> $basename
                                    </a>
                                  </div>";
            }
        }

        // Render message with attachment inside bubble
        $messageContent = "<div class='message'>
                            <div class='bubble'>
                                $content
                                $attachmentHtml
                            </div>
                            <div class='timestamp'>$timestamp</div>
                          </div>";

        echo json_encode(['success' => true, 'message' => $messageContent]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Message not found']);
    }

    exit;
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid message ID']);
}
?>
