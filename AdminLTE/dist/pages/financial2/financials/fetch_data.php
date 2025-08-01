<?php
require_once '../db/connect.php';

header('Content-Type: application/json');

try {
    // Get all buildings with their tenants
    $query = "SELECT
                t.unit_id AS building_name,
                t.user_id,
                CONCAT(u.first_name, ' ', u.middle_name) AS full_name,
                t.unit_id AS unit_id,
                t.rent_amount,
                t.phone_number,
                t.id_no
              FROM tenants t
              JOIN users u ON t.user_id = u.id
              WHERE t.unit_id IS NOT NULL
              AND t.status = 'active'
              ORDER BY t.unit_id, full_name";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group by building
    $buildings = [];
    foreach ($result as $row) {
        $buildingName = $row['building_name'];
        if (!isset($buildings[$buildingName])) {
            $buildings[$buildingName] = [
                'building_name' => $buildingName,
                'tenants' => []
            ];
        }
        unset($row['building_name']);
        $buildings[$buildingName]['tenants'][] = $row;
    }

    // Convert associative array to indexed array
    $buildings = array_values($buildings);

    echo json_encode($buildings);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>"