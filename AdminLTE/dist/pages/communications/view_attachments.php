<?php
require 'db.php';  // Include your database connection file

// Get message_id from URL query parameter, sanitize as integer
$message_id = isset($_GET['message_id']) ? intval($_GET['message_id']) : 0;

if ($message_id > 0) {
    // Prepare and execute SQL to fetch file name and type for this message
    $stmt = $pdo->prepare("SELECT file_name, file_type FROM attachments WHERE message_id = ?");
    $stmt->execute([$message_id]);
    $attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($attachments) {
        foreach ($attachments as $file) {
            // Build safe file path for the uploads folder
            $filePath = 'uploads/' . htmlspecialchars($file['file_name']);
            $fileType = $file['file_type'];

            // Display differently based on file type
            if (str_starts_with($fileType, 'image/')) {
                echo "<div><img src='$filePath' alt='Image' style='max-width:300px; margin:10px 0;'></div>";
            } elseif ($fileType === 'application/pdf') {
                echo "<div><a href='$filePath' target='_blank'>View PDF</a></div>";
            } else {
                echo "<div><a href='$filePath' download>Download " . htmlspecialchars($file['file_name']) . "</a></div>";
            }
        }
    } else {
        echo "<p>No attachments found for this message.</p>";
    }
} else {
    echo "<p>Invalid message ID.</p>";
}
