<?php
header('Content-Type: application/json');
require_once '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_date = $_POST['request_date'] ?? null;
    $category = $_POST['category'] ?? null;
    $request = $_POST['request'] === 'Other' ? $_POST['custom_request'] : $_POST['request'];
    $description = $_POST['description'] ?? null;

    try {
        // 1. Insert maintenance request
        $stmt = $pdo->prepare("INSERT INTO maintenance_requests 
            (request_date, category, request, description, created_at) 
            VALUES (?, ?, ?, ?, NOW())");

        $stmt->execute([
            $request_date,
            $category,
            $request,       // ✅ Corrected order: request comes before description
            $description
        ]);

        $maintenance_request_id = $pdo->lastInsertId();

        // 2. Handle photo uploads
        $uploadDir = __DIR__ . '/../maintenance/uploads/'; // server path
        $uploadUrl = 'maintenance/uploads/';               // for database reference

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!empty($_FILES['photos']['name'][0])) {
            foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {
                if ($_FILES['photos']['error'][$index] === 0) {
                    $originalName = basename($_FILES['photos']['name'][$index]);
                    $fileName = time() . '_' . $originalName;
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $relativePath = $uploadUrl . $fileName;

                        $photoStmt = $pdo->prepare("INSERT INTO maintenance_photos (maintenance_request_id, photo_url) VALUES (?, ?)");
                        $photoStmt->execute([$maintenance_request_id, $relativePath]);
                    }
                }
            }
        }

        echo json_encode(['success' => true]);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
