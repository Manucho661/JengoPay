<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

include '../../../db/connect.php'; // make sure this sets up $pdo correctly

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_POST['file_name'], $_POST['tenant_id']) || !isset($_FILES['file_upload'])) {
    echo json_encode(['success' => false, 'message' => 'Missing file_name, tenant_id, or file_upload']);
    exit;
}

$fileName = $_POST['file_name'];
$tenantId = $_POST['tenant_id'];
$file = $_FILES['file_upload'];

$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$targetPath = $uploadDir . basename($file['name']);

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
    exit;
}




// Save to database
try {
    $stmt = $pdo->prepare("INSERT INTO files (file_name, file_path, tenant_id) VALUES (:file_name, :file_path, :tenant_id)");
    $stmt->execute([
        ':file_name' => $fileName,
        ':file_path' => $targetPath,
        ':tenant_id' => $tenantId
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]);
    exit;
}

echo json_encode([
    'success' => true,
    'file_name' => $fileName,
    'file_path' => $targetPath
]);
