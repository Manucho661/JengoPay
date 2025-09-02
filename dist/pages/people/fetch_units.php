<?php
require_once '../db/connect.php'; // Include your PDO connection file

header('Content-Type: application/json');

try {
    $building_id = $_POST['building_id'] ?? null;
    $unit_type = $_POST['unit_type'] ?? null;
    $unit_number = $_POST['unit_number'] ?? null;

    if (!$building_id || !$unit_type) {
        throw new Exception('Building ID and Unit Type are required');
    }

    // Proper SQL query without inline comments
    $query = "SELECT
                 u.unit_id,
                 u.unit_number,
                 u.building_id,
                 b.building_name,
                 u.unit_type,
                 u.rent_amount
              FROM units u
              JOIN buildings b ON u.building_id = b.building_id
              WHERE u.building_id = :building_id
              AND u.unit_type = :unit_type";

    $params = [
        ':building_id' => $building_id,
        ':unit_type' => $unit_type
    ];

    // Optional: Filter by unit_number if provided
    if (!empty($unit_number)) {
        $query .= " AND u.unit_number LIKE :unit_number";
        $params[':unit_number'] = "%$unit_number%";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($units);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>