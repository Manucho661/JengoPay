<?php
include '../../db/connect.php';
header('Content-Type: application/json');

if (isset($_GET['message_id']) && is_numeric($_GET['message_id'])) {
    $messageId = (int)$_GET['message_id'];

    // Fetch the message
    $stmt = $pdo->prepare("SELECT sender, content, timestamp FROM messages WHERE message_id = :message_id");
    $stmt->execute(['message_id' => $messageId]);
    $message = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($message) {
        $content = nl2br(htmlspecialchars($message['content']));
        $timestamp = date('H:i', strtotime($message['timestamp']));
        $attachmentHtml = '';

        // Fetch attachments from message_files table
        $stmt_files = $pdo->prepare("SELECT file_path FROM message_files WHERE message_id = :message_id");
        $stmt_files->execute(['message_id' => $messageId]);
        $files = $stmt_files->fetchAll(PDO::FETCH_ASSOC);

        foreach ($files as $file) {
            $path = '../' . ltrim($file['file_path'], '/'); // Ensure relative path for security
            if (!empty($path) && file_exists($path)) {
                $basename = basename($path);
                $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
                $mimeType = mime_content_type($path);
                $base64 = base64_encode(file_get_contents($path));

                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
                    $attachmentHtml .= "
                        <div class='attachment-image mt-2'>
                            <img src='data:$mimeType;base64,$base64' alt='$basename' class='img-thumbnail' style='max-width:200px; max-height:150px;'>
                            <div class='file-name small'>$basename</div>
                        </div>";
                } elseif ($ext === 'pdf') {
                    $attachmentHtml .= "
                        <div class='attachment-file mt-2'>
                            <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                                <i class='fas fa-file-pdf'></i> $basename
                            </a>
                        </div>";
                } else {
                    $attachmentHtml .= "
                        <div class='attachment-file mt-2'>
                            <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                                <i class='fas fa-download'></i> $basename
                            </a>
                        </div>";
                }
            }
        }

        // Combine message + attachments
        $messageContent = "
            <div class='message'>
                <div class='bubble'>
                    $content
                    $attachmentHtml
                </div>
                <div class='timestamp text-muted small'>$timestamp</div>
            </div>";

        echo json_encode(['success' => true, 'message' => $messageContent]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Message not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid message ID']);
}
?>
