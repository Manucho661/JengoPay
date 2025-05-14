<?php
include '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message_content'])) {
        $messageContent = $_POST['message_content'];
        $threadId = 1; // For now, use a static thread ID or pass it in the request

        // Insert the message into the database
        $stmt = $pdo->prepare("INSERT INTO messages (thread_id, sender, content, timestamp) VALUES (:thread_id, 'landlord', :content, NOW())");
        $stmt->execute(['thread_id' => $threadId, 'content' => $messageContent]);

        // Get the last inserted message ID
        $messageId = $pdo->lastInsertId();

        // Handle file uploads
        if (isset($_FILES['files'])) {
            $uploadsDir = 'uploads/'; // Specify your upload directory
            foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
                $fileName = basename($_FILES['files']['name'][$key]);
                $filePath = $uploadsDir . $fileName;

                // Move file to upload directory
                if (move_uploaded_file($tmpName, $filePath)) {
                    // Save file path to the database
                    $stmt = $pdo->prepare("INSERT INTO message_files (message_id, file_path) VALUES (:message_id, :file_path)");
                    $stmt->execute(['message_id' => $messageId, 'file_path' => $filePath]);
                }
            }
        }

        // Send a success response
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
