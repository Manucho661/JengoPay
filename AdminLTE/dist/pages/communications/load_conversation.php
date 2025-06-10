<?php
include '../db/connect.php';
header('Content-Type: application/json');

// Input
$threadId = isset($_GET['thread_id']) ? (int)$_GET['thread_id'] : null;
if (!$threadId) {
    echo json_encode(['error' => 'Missing thread_id']);
    exit;
}

// Fetch thread title
$stmtTitle = $pdo->prepare("SELECT title FROM communication WHERE thread_id = :thread_id");
$stmtTitle->execute(['thread_id' => $threadId]);
$titleRow = $stmtTitle->fetch(PDO::FETCH_ASSOC);
$title = $titleRow ? $titleRow['title'] : 'Not Found';

// Fetch messages and attachments
$stmt = $pdo->prepare("
    SELECT
        m.message_id,
        m.sender,
        m.content,
        m.timestamp,
        m.viewed,
        m.file_path AS single_file_path,  -- NEW: directly stored file
        GROUP_CONCAT(mf.file_path SEPARATOR '|||') AS file_paths,
        GROUP_CONCAT(mf.file_id SEPARATOR '|||') AS file_ids
    FROM messages m
    LEFT JOIN message_files mf ON m.message_id = mf.message_id
    WHERE m.thread_id = :thread_id
    GROUP BY m.message_id
    ORDER BY m.timestamp ASC
");
$stmt->execute(['thread_id' => $threadId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$messagesHtml = '';

foreach ($messages as $msg) {
    $class = ($msg['sender'] === 'landlord') ? 'outgoing' : 'incoming';
    $content = nl2br(htmlspecialchars($msg['content']));
    $timestamp = date('H:i', strtotime($msg['timestamp']));

    // Merge file paths from message_files and messages.file_path
    $file_paths = [];
    $file_ids = [];

    if (!empty($msg['file_paths'])) {
        $file_paths = explode('|||', $msg['file_paths']);
        $file_ids = explode('|||', $msg['file_ids']);
    }

    if (!empty($msg['single_file_path'])) {
        $file_paths[] = $msg['single_file_path']; // Add direct file_path
        $file_ids[] = null;
    }

    $messagesHtml .= "<div class='message $class' data-message-id='{$msg['message_id']}'>";

    // Add three dots menu only for "outgoing" messages (optional condition)
    $messagesHtml .= "
    <div class='message-options'>
        <button class='options-btn' onclick='toggleOptionsMenu(this)'>⋮</button>
        <div class='options-menu' style='display: none;'>
            <button onclick='editMessage(this, {$msg['message_id']})'>
                <i class='fas fa-edit'></i> Edit
            </button>
            <button onclick='deleteMessage({$msg['message_id']})'>
                <i class='fas fa-trash-alt'></i> Delete
            </button>
        </div>
    </div>
";

    $messagesHtml .= "<div class='bubble'>$content</div>";

    if (!empty($file_paths)) {
        $messagesHtml .= "<div class='attachments mt-2'>";
        foreach ($file_paths as $index => $file_path) {
            if (empty($file_path)) continue;

            $base_upload_dir = '/originalTwo/AdminLTE/dist/pages/communications/uploads/';
            $full_path = $_SERVER['DOCUMENT_ROOT'] . $base_upload_dir . basename($file_path);
            error_log("FULL PATH: " . $full_path);

            $basename = basename($file_path);
            $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
            $file_id = $file_ids[$index] ?? '';

            if (file_exists($full_path) && is_readable($full_path)) {
                $fileData = file_get_contents($full_path);
                $base64 = base64_encode($fileData);
                $mimeType = mime_content_type($full_path);

                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
                    $messagesHtml .= "
                    <div class='attachment-image whatsapp-style'>
                        <div class='image-container'>
                            <a href='data:$mimeType;base64,$base64' download='$basename' class='download-icon' title='Download'>
                                <i class='fas fa-download'></i>
                            </a>
                            <img src='data:$mimeType;base64,$base64' alt='$basename' class='media-image'>
                        </div>
                        <div class='file-name'>$basename</div>
                    </div>";
                } elseif ($ext === 'pdf') {
                    $messagesHtml .= "<div class='attachment-file whatsapp-style-file' data-filename='$basename'>
                        <div class='file-container'>
                            <embed src='data:$mimeType;base64,$base64' type='$mimeType' class='file-preview' />
                            <a href='data:$mimeType;base64,$base64' download='$basename' class='download-icon' title='Download'>
                                <i class='fas fa-download'></i>
                            </a>
                            <button class='delete-icon' title='Delete' onclick='deleteAttachment(this, \"$basename\")'>
                                <i class='fas fa-trash-alt'></i>
                            </button>
                        </div>
                        <a href='data:$mimeType;base64,$base64' download='$basename' class='file-download-link'>
                            <i class='fas fa-file-pdf file-icon'></i>
                            <span class='file-name'>$basename</span>
                        </a>
                    </div>";
                } else {
                    $messagesHtml .= "<div class='attachment-file mb-2'>
                        <a href='data:$mimeType;base64,$base64' download='$basename' class='btn btn-sm btn-outline-secondary'>
                            <i class='fas fa-download'></i> $basename
                        </a>
                    </div>";
                }
            } else {
                $messagesHtml .= "<div class='attachment-error text-danger mb-2'>
                    <i class='fas fa-exclamation-triangle'></i> File not found: $basename
                </div>";
            }
        }
        $messagesHtml .= "</div>"; // end attachments
    }

    // Timestamp + tick logic (only for landlord sender)
    $messagesHtml .= "<div class='timestamp small text-muted d-flex align-items-center'>$timestamp";

    if ($msg['sender'] === 'landlord') {
        if ($msg['viewed']) {
            // Seen → double blue ticks
            $messagesHtml .= "<i class='fas fa-check-double text-primary ms-2' title='Seen'></i>";
        } else {
            // Sent but not seen → single grey tick
            $messagesHtml .= "<i class='fas fa-check text-muted ms-2' title='Sent, not seen'></i>";
        }
    }

    $messagesHtml .= "</div>"; // timestamp

    $messagesHtml .= "</div>"; // end message
}

echo json_encode([
    'title' => $title,
    'messages' => $messagesHtml
]);
exit;
