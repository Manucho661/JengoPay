<?php
include '../db/connect.php';
header('Content-Type: application/json');

// Show errors (for debugging)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit;
}

$message = trim($_POST['message'] ?? '');
$threadId = (int)($_POST['thread_id'] ?? 0);
$sender = trim($_POST['sender'] ?? 'unknown');

// Validate input
if ($threadId <= 0 || ($message === '' && empty($_FILES['file']['name']))) {
    echo json_encode(['success' => false, 'error' => 'Message or file is required.']);
    exit;
}

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Insert message
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

    // File upload directory
    $upload_dir = "uploads/";
    $uploaded_files = [];

    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Handle file upload(s)
    if (!empty($_FILES['file']['name'][0])) {
        foreach ($_FILES['file']['name'] as $index => $name) {
            if ($_FILES['file']['error'][$index] === UPLOAD_ERR_OK) {
                $fileExtension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx', 'txt'];

                if (!in_array($fileExtension, $validExtensions)) {
                    throw new Exception("Invalid file type: " . $fileExtension);
                }

                if ($_FILES['file']['size'][$index] > 10 * 1024 * 1024) {
                    throw new Exception("File size exceeds 10MB limit");
                }

                $filename = uniqid() . '_' . basename($name);
                $targetFile = $upload_dir . $filename;

                if (move_uploaded_file($_FILES['file']['tmp_name'][$index], $targetFile)) {
                    $uploaded_files[] = $targetFile;

                    // Insert into message_files
                    $stmtFile = $pdo->prepare("
                        INSERT INTO message_files (message_id, thread_id, file_path)
                        VALUES (:message_id, :thread_id, :file_path)
                    ");
                    $stmtFile->execute([
                        'message_id' => $messageId,
                        'thread_id' => $threadId,
                        'file_path' => $targetFile
                    ]);

                    // Optional: set the first file path in the messages table
                    $stmtUpdate = $pdo->prepare("
                        UPDATE messages SET file_path = :file_path
                        WHERE message_id = :message_id
                    ");
                    $stmtUpdate->execute([
                        'file_path' => $targetFile,
                        'message_id' => $messageId
                    ]);
                } else {
                    throw new Exception("Failed to move uploaded file");
                }
            }
        }
    }

    // Commit the transaction
    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
