<?php
header('Content-Type: application/json');
session_start();

require_once '../../db/connect.php'; // PDO connection in $pdo

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Invalid request method. Use POST.']);
        exit;
    }

    // Read POST fields safely (trim to avoid accidental whitespace)
    $category = isset($_POST['category']) ? trim($_POST['category']) : null;
    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $request  = isset($_POST['request']) ? trim($_POST['request']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;

    // Basic validation
    if (empty($category) || empty($request)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing required fields: category or request.']);
        exit;
    }


    // Insert maintenance request
    // created by id
    // $userId = $_SESSION['user']['id'];
    $userId = 1;
    // $role = $_SESSION['user']['role'];
    $role = "landlord";
    $buildingId=1;
    $stmt = $pdo->prepare("
        INSERT INTO maintenance_requests 
            (created_by_user_id, requester_role, building_id, title, description, category, created_at) 
        VALUES (:created_by_user_id, :requester_role, :building_id, :title, :description, :category, NOW())
    ");

    $stmt->execute([
        ':created_by_user_id' => $userId,
        ':requester_role' => $role,
        ':building_id' => $buildingId,
        ':title' => $title,
        ':description' => $description,
        ':category' => $category
    ]);

    $maintenance_request_id = $pdo->lastInsertId();

    // Handle photo uploads
    $uploadDir = __DIR__ . '../../../maintenance/uploads/'; // absolute server path
    $uploadUrl = 'uploads/'; // relative path stored in DB

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Expecting input name "photos[]" in the form so PHP puts them in $_FILES['photos']
    if (!empty($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
        // Normalize and loop through files
        foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {
            if (!isset($_FILES['photos']['error'][$index]) || $_FILES['photos']['error'][$index] !== UPLOAD_ERR_OK) {
                continue; // skip errored file
            }

            $originalName = basename($_FILES['photos']['name'][$index]);
            $ext = pathinfo($originalName, PATHINFO_EXTENSION);
            // Create safer filename
            $fileName = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $relativePath = $uploadUrl . $fileName;

                $photoStmt = $pdo->prepare("
                    INSERT INTO maintenance_request_photos (maintenance_request_id, photo_path)
                    VALUES (:request_id, :photo_url)
                ");
                $photoStmt->execute([
                    ':request_id' => $maintenance_request_id,
                    ':photo_url' => $relativePath
                ]);
            }
        }
    }

    echo json_encode(['success' => true, 'id' => $maintenance_request_id]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'user_id' => $userId,
        'message' => $e->getMessage()
    ]);
}
