<?php
include '../db/connect.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$fileId = $input['file_id'] ?? null;
$filename = $input['filename'] ?? null;

// Validate user permissions here (e.g., check if user owns the file)
// ...

try {
    // Delete from database
    if ($fileId) {
        $stmt = $pdo->prepare("DELETE FROM message_files WHERE file_id = ?");
        $stmt->execute([$fileId]);
    }

    // Delete physical file
    $uploadDir = '/originalTwo/AdminLTE/dist/pages/communications/uploads/';
    $filePath = $_SERVER['DOCUMENT_ROOT'] . $uploadDir . $filename;

    if (file_exists($filePath)) {
        unlink($filePath); // Delete the file
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}