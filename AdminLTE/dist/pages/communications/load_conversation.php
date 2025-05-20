<?php
include '../db/connect.php';
header('Content-Type: application/json');

// Input
$threadId = isset($_GET['thread_id']) ? (int)$_GET['thread_id'] : null;
$attachmentId = isset($_GET['attachment_id']) ? (int)$_GET['attachment_id'] : null;

if (!$threadId) {
    echo json_encode(['error' => 'Missing thread_id']);
    exit;
}

// Fetch thread title
$stmtTitle = $pdo->prepare("SELECT title FROM communication WHERE thread_id = :thread_id");
$stmtTitle->execute(['thread_id' => $threadId]);
$titleRow = $stmtTitle->fetch(PDO::FETCH_ASSOC);
$title = $titleRow ? $titleRow['title'] : 'Not Found';

if ($attachmentId) {
    // ✅ Fetch specific message that has this attachment
    $sql = "
        SELECT
            m.message_id,
            m.thread_id,
            m.sender,
            m.content,
            m.timestamp,
            mf.file_id,
            mf.file_path
        FROM messages m
        JOIN message_files mf ON m.message_id = mf.message_id
        WHERE m.thread_id = :thread_id
        AND mf.file_id = :attachment_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'thread_id' => $threadId,
        'attachment_id' => $attachmentId
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // ✅ Fetch all messages in thread as usual
    $stmt = $pdo->prepare("
        SELECT
            m.message_id,
            m.sender,
            m.content,
            m.timestamp,
            GROUP_CONCAT(DISTINCT mf.file_path SEPARATOR '|||') AS file_paths,
            GROUP_CONCAT(DISTINCT mf.file_id SEPARATOR '|||') AS file_ids
        FROM messages m
        LEFT JOIN message_files mf ON mf.message_id = m.message_id
        WHERE m.thread_id = :thread_id
        GROUP BY m.message_id, m.sender, m.content, m.timestamp
        ORDER BY m.timestamp ASC
    ");
    $stmt->execute(['thread_id' => $threadId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$messagesHtml = '';

foreach ($messages as $msg) {
    $class = ($msg['sender'] === 'landlord') ? 'outgoing' : 'incoming';
    $content = nl2br(htmlspecialchars($msg['content']));
    $timestamp = date('H:i', strtotime($msg['timestamp']));

    $file_paths = [];
    $file_ids = [];

    if (!empty($msg['file_paths'])) {
        $file_paths = array_filter(explode('|||', $msg['file_paths'] ?? $msg['file_path']));
        $file_ids = !empty($msg['file_ids']) ? array_filter(explode('|||', $msg['file_ids'])) : [$msg['file_id'] ?? null];
    }

    $messagesHtml .= "<div class='message $class'>
                        <div class='bubble'>$content</div>";

    if (!empty($file_paths)) {
        $messagesHtml .= "<div class='attachments mt-2'>";
        foreach ($file_paths as $index => $file_path) {
            $full_path = htmlspecialchars($file_path);
            $basename = basename($full_path);
            $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
            $file_id = $file_ids[$index] ?? '';

            if (file_exists($full_path) && is_readable($full_path)) {
                $fileData = file_get_contents($full_path);
                $base64 = base64_encode($fileData);
                $mimeType = mime_content_type($full_path);

                if (strpos($mimeType, 'image/') === 0) {
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
            } else {
                $messagesHtml .= "<div class='attachment-error mb-2 text-danger'>
                    <i class='fas fa-exclamation-triangle'></i> File not found: $basename
                </div>";
            }
        }
        $messagesHtml .= "</div>";
    }

    $messagesHtml .= "<div class='timestamp'>$timestamp</div></div>";
}

echo json_encode([
    'title' => $title,
    'messages' => $messagesHtml
]);
exit;
?>
