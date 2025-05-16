<?php
include '../db/connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$message = $_POST['message'] ?? '';
$threadId = (int)($_POST['thread_id'] ?? 0);
$sender = $_POST['sender'] ?? 'unknown';

if (!$threadId) {
    echo json_encode(['success' => false, 'error' => 'Invalid thread ID']);
    exit;
}

$filePath = null;
if (!empty($_FILES['file']['name'])) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filename = time() . '_' . basename($_FILES['file']['name']);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        $filePath = 'uploads/' . $filename;
    } else {
        echo json_encode(['success' => false, 'error' => 'File upload failed']);
        exit;
    }
}

$content = htmlspecialchars($message);
if ($filePath) {
    $fileLink = "<a href='$filePath' target='_blank'>Download Attachment</a>";
    $content .= ($content ? '<br>' : '') . $fileLink;
}

try {
    $stmt = $pdo->prepare("INSERT INTO messages (thread_id, sender, content, timestamp, file_path) VALUES (:thread_id, :sender, :content, NOW(), :file_path)");
    $stmt->execute([
        'thread_id' => $threadId,
        'sender' => $sender,
        'content' => $content,
        'file_path' => $filePath
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB error: ' . $e->getMessage()]);
}
?>
