<?php
header('Content-Type: application/json');
require_once '../db/connect.php'; // Make sure this file defines $pdo

try {
    // Validate building_id parameter
    if (!isset($_GET['building_id'])) {
        throw new Exception('Building ID parameter is missing');
    }

    $buildingId = $_GET['building_id'];

    // Validate it's a numeric value
    if (!is_numeric($buildingId)) {
        throw new Exception('Building ID must be a number');
    }

    $buildingId = (int)$buildingId;
    if ($buildingId <= 0) {
        throw new Exception('Invalid building ID value');
    }

    // Prepare and execute query using PDO
    $stmt = $pdo->prepare("
        SELECT
            t.id AS tenant_id,
            t.unit_id,
            t.building_id,
            u.first_name,
            u.middle_name
        FROM tenants t
        JOIN users u ON t.user_id = u.id
        WHERE t.building_id = :building_id
          AND t.status = 'active'
        ORDER BY u.first_name, u.middle_name
    ");

    $stmt->bindParam(':building_id', $buildingId, PDO::PARAM_INT);
    $stmt->execute();

    $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tenants);

} catch (PDOException $e) {
    error_log('PDO Error in get_tenants.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
} catch (Exception $e) {
    error_log('Error in get_tenants.php: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}