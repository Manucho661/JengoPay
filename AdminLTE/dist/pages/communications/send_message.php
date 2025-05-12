<?php
include '../db/connect.php'; // Ensure $pdo is defined

$message = $_POST['message'] ?? '';
$sender = $_POST['sender'] ?? '';
$thread_id = $_POST['thread_id'] ?? 0;

if ($message && $sender && $thread_id) {
    // Insert the message
    $stmt = $pdo->prepare("INSERT INTO messages (thread_id, sender, content) VALUES (:thread_id, :sender, :content)");
    $stmt->execute([
        'thread_id' => $thread_id,
        'sender' => $sender,
        'content' => $message
    ]);

    // Handle file uploads (if any)
    if (!empty($_FILES['attachment']['name'][0])) {
        foreach ($_FILES['attachment']['tmp_name'] as $index => $tmpName) {
            $originalName = basename($_FILES['attachment']['name'][$index]);
            $uniqueName = uniqid() . '-' . preg_replace('/[^A-Za-z0-9.\-_]/', '_', $originalName); // Sanitize filename
            $target = 'uploads/' . $uniqueName;

            // Move file and insert file reference into DB
            if (move_uploaded_file($tmpName, $target)) {
                $stmt = $pdo->prepare("INSERT INTO message_files (thread_id, file_path) VALUES (:thread_id, :file_path)");
                $stmt->execute([
                    'thread_id' => $thread_id,
                    'file_path' => $target
                ]);
            }
        }
    }

    echo "Message sent";
} else {
    echo "Invalid data";
}
