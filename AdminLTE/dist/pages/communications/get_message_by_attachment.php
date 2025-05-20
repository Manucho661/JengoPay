<?php
include '../db/connect.php';
header('Content-Type: application/json');

// Validate input
if (!isset($_GET['thread_id'], $_GET['attachment_id'])) {
    echo json_encode(['error' => 'Missing thread_id or attachment_id']);
    exit;
}

$threadId = (int) $_GET['thread_id'];
$attachmentId = (int) $_GET['attachment_id'];

$sql = "
    SELECT
        m.message_id,
        m.thread_id,
        m.sender,
        m.content,
        m.timestamp,
        f.file_id,
        f.file_path
    FROM messages m
    JOIN message_files f ON m.message_id = f.message_id
    WHERE m.thread_id = :thread_id AND f.file_id = :attachment_id
    LIMIT 1
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':thread_id' => $threadId,
    ':attachment_id' => $attachmentId
]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    echo json_encode(['error' => 'No message found with that attachment']);
    exit;
}

// Optional: Add base64 preview
if (file_exists($result['file_path']) && is_readable($result['file_path'])) {
    $mime = mime_content_type($result['file_path']);
    $data = base64_encode(file_get_contents($result['file_path']));
    $result['file_preview'] = "data:$mime;base64,$data";
} else {
    $result['file_preview'] = null;
}

echo json_encode($result);
exit;
?>
