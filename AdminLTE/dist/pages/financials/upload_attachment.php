<?php
// upload_attachment.php

// Database connection
require_once 'db_connection.php';

// Check if file was uploaded
if (!isset($_FILES['file']) || !isset($_POST['invoice_number'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$invoiceNumber = $_POST['invoice_number'];
$file = $_FILES['file'];

// Validate file
$allowedTypes = [
    'application/pdf' => 'pdf',
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'application/msword' => 'doc',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx'
];

if (!array_key_exists($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    exit;
}

// Check file size (5MB limit)
if ($file['size'] > 5242880) {
    echo json_encode(['success' => false, 'message' => 'File size exceeds 5MB limit']);
    exit;
}

// Create upload directory if it doesn't exist
$uploadDir = 'uploads/invoice_attachments/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate unique filename
$extension = $allowedTypes[$file['type']];
$filename = 'invoice_' . $invoiceNumber . '_' . time() . '.' . $extension;
$filepath = $uploadDir . $filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    // Save to database
    try {
        $stmt = $pdo->prepare("
            INSERT INTO invoice_attachments
            (invoice_number, file_name, file_path)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$invoiceNumber, $file['name'], $filepath]);

        echo json_encode([
            'success' => true,
            'message' => 'File uploaded successfully',
            'filename' => $file['name']
        ]);
    } catch (PDOException $e) {
        // Delete the uploaded file if DB insert fails
        unlink($filepath);
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'File upload failed']);
}