<?php
header('Content-Type: application/json');
include_once '../../db/connect.php';

if (!isset($_GET['building_id']) || empty($_GET['building_id'])) {
    echo json_encode([]);
    exit;
}

$buildingId = $_GET['building_id'];

// Fetch building name (to match with building_link in tenant tables)
$stmt = $conn->prepare("SELECT building_name FROM buildings WHERE id = ?");
$stmt->execute([$buildingId]);
$building = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$building) {
    echo json_encode([]);
    exit;
}

$buildingName = $building['building_name'];

try {
    // Fetch occupied tenants from all property types
    $queries = [
        "SELECT 'single_units' AS source, id, structure_type, first_name, last_name, unit_number
         FROM single_units
         WHERE building_link = ? AND occupancy_status = 'Occupied'",
        
        "SELECT 'multi_rooms' AS source, id, structure_type, first_name, last_name, unit_number
         FROM multi_rooms
         WHERE building_link = ? AND occupancy_status = 'Occupied'",
        
        "SELECT 'bed_seaters' AS source, id, structure_type, first_name, last_name, unit_number
         FROM bed_seaters
         WHERE building_link = ? AND occupancy_status = 'Occupied'"
    ];

    $tenants = [];
    foreach ($queries as $sql) {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$buildingName]);
        $tenants = array_merge($tenants, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    echo json_encode($tenants);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
