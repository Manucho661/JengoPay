<?php
include '../db/connect.php'; // Ensure $pdo is defined

header('Content-Type: application/json');

try {
    $building_id = isset($_REQUEST['building_id']) ? (int)$_REQUEST['building_id'] : null;

    if ($building_id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid building_id']);
        exit;
    }

    // Optional: Check if building exists
    $check = $pdo->prepare("SELECT 1 FROM buildings WHERE building_id = :id");
    $check->bindParam(':id', $building_id, PDO::PARAM_INT);
    $check->execute();
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Building not found']);
        exit;
    }

    // Delete the building
    $stmt = $pdo->prepare("DELETE FROM buildings WHERE building_id = :id");
    $stmt->bindParam(':id', $building_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Building deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Delete operation failed']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>