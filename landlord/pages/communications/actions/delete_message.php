<?php
include '../../db/connect.php';
header('Content-Type: application/json');

$messageId = isset($_POST['message_id']) ? (int)$_POST['message_id'] : null;

if (!$messageId) {
    echo json_encode(['error' => 'Missing message ID']);
    exit;
}

// Fetch file paths from message_files
$stmt = $pdo->prepare("SELECT file_path FROM message_files WHERE message_id = ?");
$stmt->execute([$messageId]);
$attachedFiles = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch the single file (if stored directly in messages table)
$stmtSingle = $pdo->prepare("SELECT file_path FROM messages WHERE message_id = ?");
$stmtSingle->execute([$messageId]);
$singleFile = $stmtSingle->fetchColumn();

$allFiles = array_merge($attachedFiles, $singleFile ? [$singleFile] : []);

$baseUploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/communications/uploads/';

foreach ($allFiles as $file) {
    $filePath = $baseUploadDir . basename($file);
    if (file_exists($filePath)) {
        unlink($filePath); // delete file from disk
    }
}

// Delete related file records
$pdo->prepare("DELETE FROM message_files WHERE message_id = ?")->execute([$messageId]);

// Delete the message itself
$pdo->prepare("DELETE FROM messages WHERE message_id = ?")->execute([$messageId]);

echo json_encode(['success' => true]);
exit;
