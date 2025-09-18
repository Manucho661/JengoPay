<?php
header('Content-Type: application/json');

// 1. Validate Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['unit_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// 2. Include DB Connection (PDO)
include '../db/connect.php'; // Ensure this defines $pdo for PDO

// 3. Sanitize Input
$unitId = (int)$_POST['unit_id'];

try {
    // 4. Prepare & Execute Delete Query
    $stmt = $pdo->prepare("DELETE FROM units WHERE unit_id = ?");
    $stmt->execute([$unitId]);

    // 5. Check if deletion succeeded
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Unit not found or already deleted']);
    }
} catch (PDOException $e) {
    // 6. Handle DB Errors
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}