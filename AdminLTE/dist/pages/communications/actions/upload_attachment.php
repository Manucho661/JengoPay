<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;

    if ($message_id <= 0) {
        die("Invalid message ID.");
    }

    if (!isset($_FILES['attachment']) || $_FILES['attachment']['error'] !== UPLOAD_ERR_OK) {
        die("Error uploading file.");
    }

    $fileTmpPath = $_FILES['attachment']['tmp_name'];
    $fileName = basename($_FILES['attachment']['name']);
    $fileSize = $_FILES['attachment']['size'];
    $fileType = $_FILES['attachment']['type'];

    // Sanitize file name (remove unwanted characters)
    $fileNameClean = preg_replace("/[^a-zA-Z0-9\.\-\_]/", "", $fileName);

    // Create unique file name to avoid overwriting
    $newFileName = time() . "_" . $fileNameClean;

    $uploadDir = 'uploads/';
    $destPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        // Insert file info into DB
        $stmt = $pdo->prepare("INSERT INTO attachments (message_id, file_name, file_type) VALUES (?, ?, ?)");
        $stmt->execute([$message_id, $newFileName, $fileType]);

        echo "File uploaded and saved successfully.";
    } else {
        echo "Failed to move uploaded file.";
    }
} else {
    ?>
    <!-- Simple upload form -->
    <form method="post" enctype="multipart/form-data">
        <label>Message ID: <input type="number" name="message_id" required></label><br>
        <label>Choose file: <input type="file" name="attachment" required></label><br>
        <button type="submit">Upload</button>
    </form>
    <?php
}
?>
