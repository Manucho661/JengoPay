<?php
require_once '../../db/connect.php'; // PDO connection in $pdo

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

function redirect_with_message(string $url, string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,      // success | danger | warning | info
        'message' => $message
    ];
    header("Location: $url");
    exit;
}

// Only accept POST submit
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['submitRequest'])) {
    return; // do nothing on GET
}


try {
    // Read POST fields safely
    $category    = isset($_POST['category']) ? trim($_POST['category']) : '';
    $title       = isset($_POST['title']) ? trim($_POST['title']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;

    // IMPORTANT: your form uses name="building" and name="unit"
    $buildingId  = isset($_POST['building']) ? (int)$_POST['building'] : 0;
    $unitId      = isset($_POST['unit']) ? trim($_POST['unit']) : null;

    // Basic validation
    if ($category === '' || $buildingId <= 0) {
        redirect_with_message('../requests/index.php', 'danger', 'Please select a category and building.');
    }

    // Use real session user in production
    $userId = $_SESSION['user']['id'] ?? 1;
    $role   = $_SESSION['user']['role'] ?? 'landlord';

    // Insert maintenance request
    $stmt = $pdo->prepare("
        INSERT INTO maintenance_requests
            (created_by_user_id, requester_role, building_id, title, description, category, created_at)
        VALUES
            (:created_by_user_id, :requester_role, :building_id, :title, :description, :category, NOW())
    ");

    $stmt->execute([
        ':created_by_user_id' => $userId,
        ':requester_role'     => $role,
        ':building_id'        => $buildingId,
        ':title'              => $title,
        ':description'        => $description,
        ':category'           => $category,
    ]);

    $maintenance_request_id = (int)$pdo->lastInsertId();

    // Handle photo uploads
    $uploadDir = __DIR__ . '/../../../maintenance/uploads/'; // FIXED path
    $uploadUrl = 'uploads/'; // stored in DB

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!empty($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
        foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {
            if (!isset($_FILES['photos']['error'][$index]) || $_FILES['photos']['error'][$index] !== UPLOAD_ERR_OK) {
                continue;
            }

            $originalName = basename($_FILES['photos']['name'][$index]);
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // Optional: allow only image extensions
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if ($ext && !in_array($ext, $allowed, true)) {
                continue;
            }

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
                    ':photo_url'  => $relativePath
                ]);
            }
        }
    }

    $_SESSION['success'] =
        "Maintenance request submitted successfully.";

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;

    exit;
} catch (Throwable $e) {
    $_SESSION['error'] =
        'Failed to submit maintenance request: ' . $e->getMessage();

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
