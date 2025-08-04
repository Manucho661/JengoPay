<?php
include '../db/connect.php'; // adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['attachment'])) {
    $invoiceNumber = $_POST['invoice_number'];
    $file = $_FILES['attachment'];

    // Validate
    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/invoice_attachments/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = basename($file['name']);
        $targetPath = $uploadDir . time() . '_' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $stmt = $pdo->prepare("INSERT INTO invoice_attachments (invoice_number, file_name, file_path) VALUES (?, ?, ?)");
            $stmt->execute([$invoiceNumber, $fileName, $targetPath]);

            header("Location: view_invoice.php?invoice_number=" . urlencode($invoiceNumber));
            exit;
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "Upload error: " . $file['error'];
    }
}
?>
