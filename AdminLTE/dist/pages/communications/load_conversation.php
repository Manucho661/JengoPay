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
            GROUP_CONCAT(mf.file_path SEPARATOR '|||') AS file_paths
        FROM messages m
        LEFT JOIN message_files mf ON mf.message_id = m.message_id
        WHERE m.thread_id = :thread_id
        GROUP BY m.message_id
        ORDER BY m.timestamp ASC
    ");
    $stmt->execute(['thread_id' => $threadId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Build response with messages and file paths
    $messagesHtml = '';
    foreach ($messages as $msg) {
        $class = ($msg['sender'] === 'landlord') ? 'outgoing' : 'incoming';
        $content = nl2br(htmlspecialchars($msg['content']));
        $timestamp = date('H:i', strtotime($msg['timestamp']));
        $file_paths = !empty($msg['file_paths']) ? explode('|||', $msg['file_paths']) : [];

        $messagesHtml .= "<div class='message $class'>
                            <div class='bubble'>$content</div>";

        // Display attachments if any
        if (!empty($file_paths)) {
            $messagesHtml .= "<div class='attachments mt-2'>";
            foreach ($file_paths as $file) {
                if (empty($file)) continue;

                $file = htmlspecialchars($file);
                $basename = htmlspecialchars(basename($file));
                $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
                $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

                if (in_array($ext, $image_extensions)) {
                    // Display image with lightbox functionality
                    $messagesHtml .= "<div class='attachment-image mb-2'>
                                        <a href='$file' data-lightbox='message-$threadId' data-title='$basename'>
                                            <img src='$file' alt='$basename' class='img-thumbnail' style='max-width:200px; max-height:150px;'>
                                        </a>
                                        <div class='file-name'>$basename</div>
                                      </div>";
                } else {
                    // Display download link for non-image files
                    $messagesHtml .= "<div class='attachment-file mb-2'>
                                        <a href='$file' target='_blank' class='btn btn-sm btn-outline-secondary'>
                                            <i class='fas fa-download'></i> $basename
                                        </a>
                                      </div>";
                }
            }
            $messagesHtml .= "</div>";  // Close attachments div
        }

        $messagesHtml .= "<div class='timestamp'>$timestamp</div></div>"; // Close message div
    }

    // Send back the title and messages HTML as a JSON response
    echo json_encode([
        'title' => $title,
        'messages' => $messagesHtml
    ]);
    exit;
}
?>
