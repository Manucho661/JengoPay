<?php
include '../db/connect.php';

header('Content-Type: application/json');

if (isset($_GET['thread_id']) && is_numeric($_GET['thread_id'])) {
    $threadId = (int)$_GET['thread_id'];

    // Fetch thread title (Optional)
    $stmtTitle = $pdo->prepare("SELECT title FROM communication WHERE thread_id = :thread_id");
    $stmtTitle->execute(['thread_id' => $threadId]);
    $titleRow = $stmtTitle->fetch(PDO::FETCH_ASSOC);
    $title = $titleRow ? $titleRow['title'] : 'Not Found';

    // Fetch messages and associated files
    $stmt = $pdo->prepare("
        SELECT
            m.message_id,
            m.sender,
            m.content,
            m.timestamp,
            GROUP_CONCAT(DISTINCT mf.file_path SEPARATOR '|||') AS file_paths
        FROM messages m
        LEFT JOIN message_files mf ON mf.message_id = m.message_id
        WHERE m.thread_id = :thread_id
        GROUP BY m.message_id, m.sender, m.content, m.timestamp
        ORDER BY m.timestamp ASC
    ");
    $stmt->execute(['thread_id' => $threadId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $messagesHtml = '';

    foreach ($messages as $msg) {
        $class = ($msg['sender'] === 'landlord') ? 'outgoing' : 'incoming';
        $content = nl2br(htmlspecialchars($msg['content']));
        $timestamp = date('H:i', strtotime($msg['timestamp']));
        $file_paths = !empty($msg['file_paths']) ? array_filter(explode('|||', $msg['file_paths'])) : [];

        // DEBUG: Check if any attachments were fetched
        if (!empty($file_paths)) {
            echo "<pre>";
            print_r([
                'content' => $msg['content'],
                'attachments' => $file_paths
            ]);
            echo "</pre>";
            exit; // Stop further execution
        }

        $messagesHtml .= "<div class='message $class'>
                            <div class='bubble'>$content</div>";

        // Attachments
        if (!empty($file_paths)) {
            $messagesHtml .= "<div class='attachments mt-2'>";
            foreach ($file_paths as $file_path) {
                $full_path = htmlspecialchars($file_path);
                $basename = basename($full_path);
                $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));

                // Read file content safely
                if (file_exists($full_path)) {
                    $fileData = file_get_contents($full_path);
                    $base64 = base64_encode($fileData);
                    $mimeType = mime_content_type($full_path);

                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
                        $messagesHtml .= "<div class='attachment-image mb-2'>
                                            <img src='data:$mimeType;base64,$base64' alt='$basename' class='img-thumbnail' style='max-width:200px; max-height:150px;'>
                                            <div class='file-name'>$basename</div>
                                          </div>";
                    } elseif ($ext === 'pdf') {
                        $messagesHtml .= "<div class='attachment-file mb-2'>
                                            <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                                                <i class='fas fa-file-pdf'></i> $basename
                                            </a>
                                          </div>";
                    } else {
                        $messagesHtml .= "<div class='attachment-file mb-2'>
                                            <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                                                <i class='fas fa-download'></i> $basename
                                            </a>
                                          </div>";
                    }
                }
            }
            $messagesHtml .= "</div>"; // Close attachments
        }

        $messagesHtml .= "<div class='timestamp'>$timestamp</div></div>"; // Close message
    }

    echo json_encode([
        'title' => $title,
        'messages' => $messagesHtml
    ]);
    exit;
}
?>
