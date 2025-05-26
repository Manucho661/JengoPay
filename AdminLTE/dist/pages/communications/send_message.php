<?php
include '../db/connect.php';
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit;
}

$message = trim($_POST['message'] ?? '');
$threadId = (int)($_POST['thread_id'] ?? 0);
$sender = trim($_POST['sender'] ?? 'unknown');

if ($threadId <= 0 || ($message === '' && empty($_FILES['file']['name'][0]))) {
    echo json_encode(['success' => false, 'error' => 'Message or file is required.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Insert the message (with empty file_path initially)
    $stmt = $pdo->prepare("
        INSERT INTO messages (thread_id, sender, content, timestamp, file_path)
        VALUES (:thread_id, :sender, :content, NOW(), '')
    ");
    $stmt->execute([
        'thread_id' => $threadId,
        'sender' => $sender,
        'content' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8')
    ]);

    $messageId = $pdo->lastInsertId();

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedFiles = [];

    if (!empty($_FILES['file']['name'][0])) {
        foreach ($_FILES['file']['name'] as $index => $originalName) {
            if ($_FILES['file']['error'][$index] === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx', 'txt'];
                if (!in_array($extension, $validExtensions)) {
                    throw new Exception("Invalid file type: $extension");
                }

                if ($_FILES['file']['size'][$index] > 10 * 1024 * 1024) {
                    throw new Exception("File size exceeds 10MB: $originalName");
                }

                $uniqueName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
                $relativePath = $uploadDir . $uniqueName;
                $fullPath = __DIR__ . '/' . $relativePath;

                if (move_uploaded_file($_FILES['file']['tmp_name'][$index], $fullPath)) {
                    $uploadedFiles[] = $relativePath;

                    // Insert into message_files table
                    $stmtFile = $pdo->prepare("
                        INSERT INTO message_files (message_id, file_path)
                        VALUES (:message_id, :file_path)
                    ");
                    $stmtFile->execute([
                        'message_id' => $messageId,
                        'file_path' => $relativePath
                    ]);
                } else {
                    throw new Exception("Failed to upload: $originalName");
                }
            }
        }

        // // Update first uploaded file as primary file_path in messages table (optional)
        // if (!empty($uploadedFiles)) {
        //     $stmtUpdate = $pdo->prepare("
        //         UPDATE messages SET file_path = :file_path WHERE message_id = :message_id
        //     ");
        //     $stmtUpdate->execute([
        //         'file_path' => $uploadedFiles[0],
        //         'message_id' => $messageId
        //     ]);
        // }
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
