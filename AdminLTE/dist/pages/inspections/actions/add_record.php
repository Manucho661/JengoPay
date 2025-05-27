<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../db/connect.php';

/**
 * Processes and inserts inspection items and their photos based on POST and FILES data.
 *
 * @param PDO $pdo
 * @param int $inspectionId
 * @param array $items
 * @throws Exception if required data is missing or insert fails
 */
function processInspectionItems(PDO $pdo, int $inspectionId, array $items): void {
    foreach ($items as $item) {
        $statusKey = "{$item}_condition";
        $descKey = "{$item}_state";
        $photoKey = "{$item}_photo";

        if (!isset($_POST[$statusKey])) {
            throw new Exception("Missing required field: {$statusKey}");
        }

        $status = trim($_POST[$statusKey]);
        $description = isset($_POST[$descKey]) ? trim($_POST[$descKey]) : null;

        // Insert into inspection_items
        $stmt = $pdo->prepare("
            INSERT INTO inspection_items (inspection_id, category, status, description)
            VALUES (:inspection_id, :category, :status, :description)
        ");
        $stmt->execute([
            'inspection_id' => $inspectionId,
            'category'      => ucfirst($item),
            'status'        => $status,
            'description'   => $status === 'Needs Repair' ? $description : null
        ]);

        // Get the inserted inspection_item ID
        $inspectionItemId = $pdo->lastInsertId();

        // Handle file if uploaded
        if (isset($_FILES[$photoKey]) && $_FILES[$photoKey]['error'] === UPLOAD_ERR_OK) {
            $photoData = handleFileUpload($_FILES[$photoKey]);

            // Insert photo path into inspection_photos table
            $photoStmt = $pdo->prepare("
                INSERT INTO inspection_photos (inspection_item_id, photo_path)
                VALUES (:inspection_item_id, :photo_path)
            ");
            $photoStmt->execute([
                'inspection_item_id' => $inspectionItemId,
                'photo_path'         => $photoData['path']
            ]);
        }

        // Update Inspection Status to Completed.
        $sql = "UPDATE inspections SET status = :status WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $status = "Completed";
        $id = $inspectionId;
        $stmt->execute([
            'status' =>$status,
            'id' => $id
        ]);

    }
}

// Handles a single file upload
function handleFileUpload(array $file): array {
    $relativePath = 'originaltwo/AdminLTE/dist/pages/inspections/uploads/';
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/' . $relativePath;

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $originalName = $file['name'];
    $tempPath = $file['tmp_name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];

    if (!in_array($extension, $allowed)) {
        throw new Exception("File type not allowed: $extension");
    }

    $uniqueName = uniqid(pathinfo($originalName, PATHINFO_FILENAME) . '_') . '.' . $extension;
    $destination = $uploadDir . $uniqueName;
    $browserPath = '/' . $relativePath . $uniqueName;

    if (!move_uploaded_file($tempPath, $destination)) {
        throw new Exception("Failed to move uploaded file: $originalName");
    }

    return ['path' => $browserPath];
}

// Get inspection ID
if (!isset($_POST['inspection_id'])) {
    throw new Exception("Missing inspection ID.");
}
$inspection_id = (int) $_POST['inspection_id'];

// Define inspection items (could be dynamic too)
$inspectionItems = ['window', 'floor', 'socket'];

try {
    processInspectionItems($pdo, $inspection_id, $inspectionItems);
    echo "Inspection items saved successfully.";
} catch (Exception $e) {
    error_log("Error processing inspection items: " . $e->getMessage());
    echo "An error occurred: " . $e->getMessage(); // Remove or limit in production
}
?>
