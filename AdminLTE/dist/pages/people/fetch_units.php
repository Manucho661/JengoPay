<?php
include '../db/connect.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get POST data
$buildingId = isset($_POST['building_id']) ? (int)$_POST['building_id'] : 0;
$unitType = isset($_POST['unit_type']) ? trim($_POST['unit_type']) : '';
$unitNumber = isset($_POST['unit_number']) ? trim($_POST['unit_number']) : '';

// Validate inputs
if ($buildingId <= 0) {
    die(json_encode(['error' => 'Valid Building ID is required']));
}
if (empty($unitType)) {
    die(json_encode(['error' => 'Unit Type is required']));
}

try {
    // Base query
    $sql = "
        SELECT unit_id, unit_number, building_id, unit_type
        FROM units
        WHERE building_id = :building_id
        AND unit_type = :unit_type
    ";

    // Add unit_number filter if provided
    if (!empty($unitNumber)) {
        $sql .= " AND unit_number = :unit_number";
    }

    $sql .= " ORDER BY unit_number";

    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $params = [
        ':building_id' => $buildingId,
        ':unit_type' => $unitType
    ];

    if (!empty($unitNumber)) {
        $params[':unit_number'] = $unitNumber;
    }

    $stmt->execute($params);

    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return results
    header('Content-Type: application/json');
    echo json_encode($units);

} catch (PDOException $e) {
    die(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
}