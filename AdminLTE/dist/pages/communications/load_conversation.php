<?php
include '../db/connect.php';

if (isset($_GET['thread_id']) && $_GET['thread_id'] > 0) {
    $threadId = (int)$_GET['thread_id'];

    // Mark messages as read
    $pdo->prepare("UPDATE messages SET is_read = 1 WHERE thread_id = :thread_id AND is_read = 0")
        ->execute(['thread_id' => $threadId]);

    // Fetch messages and their individual attachments
    $stmt = $pdo->prepare("
        SELECT m.id AS message_id, m.sender, m.content, m.timestamp, f.file_path
        FROM messages m
        LEFT JOIN message_files f ON m.id = f.message_id
        WHERE m.thread_id = :thread_id
        ORDER BY m.timestamp ASC
    ");
    $stmt->execute(['thread_id' => $threadId]);

    // Group messages with their corresponding files
    $messages = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['message_id'];
        if (!isset($messages[$id])) {
            $messages[$id] = [
                'sender' => htmlspecialchars($row['sender']),
                'content' => htmlspecialchars($row['content']),
                'files' => [],
                'timestamp' => date('M d, H:i', strtotime($row['timestamp']))
            ];
        }

        if (!empty($row['file_path'])) {
            $messages[$id]['files'][] = htmlspecialchars($row['file_path']);
        }
    }

    // Display messages with attachments inside the message bubble
    if (empty($messages)) {
        echo "<div class='text-muted'>No messages in this thread yet.</div>";
    } else {
        foreach ($messages as $msg) {
            $class = ($msg['sender'] === 'landlord') ? 'outgoing' : 'incoming';

            echo "<div class='message $class'>";

            // Start message bubble
            echo "<div class='bubble'>";
            echo "<div class='text'>{$msg['content']}</div>";

            // Display attachments inside the bubble
            if (!empty($msg['files'])) {
                echo "<div class='attachments' style='margin-top: 8px;'>";
                foreach ($msg['files'] as $filePath) {
                    $fileName = basename($filePath);
                    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                    switch ($ext) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                            echo "<div style='margin-top: 6px;'><strong>$fileName</strong><br>
                                  <img src='$filePath' style='max-width:200px; max-height:200px;'></div>";
                            break;
                        case 'pdf':
                            echo "<div style='margin-top: 6px;'><strong>$fileName</strong><br>
                                  <embed src='$filePath' type='application/pdf' width='100%' height='400px'></div>";
                            break;
                        default:
                            echo "<div><a href='$filePath' target='_blank'>📎 $fileName</a></div>";
                    }
                }
                echo "</div>";
            }

            echo "</div>"; // end bubble

            // Timestamp outside bubble
            echo "<div class='timestamp'>{$msg['timestamp']}</div>";

            echo "</div>"; // end message
        }
    }
}
?>
