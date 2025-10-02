<?php
include '../../../db/connect.php'; // adjust if path differs

header('Content-Type: application/json');

$buildingId = $_POST['id'] ?? null;

if (!$buildingId) {
    echo json_encode([]);
    exit;
}

$units = [];

// Bed Seaters
$stmt = $pdo->prepare("
    SELECT id AS unit_id, unit_number, monthly_rent, occupancy_status, 'Bed Seater' AS unit_type
    FROM bed_seaters
    WHERE building_link = ?
");
$stmt->execute([$buildingId]);
$units = array_merge($units, $stmt->fetchAll(PDO::FETCH_ASSOC));

// Single Units
$stmt = $pdo->prepare("
    SELECT id AS unit_id, unit_number, monthly_rent, occupancy_status, 'Single Unit' AS unit_type
    FROM single_units
    WHERE building_link = ?
");
$stmt->execute([$buildingId]);
$units = array_merge($units, $stmt->fetchAll(PDO::FETCH_ASSOC));

// Multi Rooms
$stmt = $pdo->prepare("
    SELECT id AS unit_id, unit_number, monthly_rent, occupancy_status, 'Multi Room' AS unit_type
    FROM multi_rooms
    WHERE building_link = ?
");
$stmt->execute([$buildingId]);
$units = array_merge($units, $stmt->fetchAll(PDO::FETCH_ASSOC));

// Return as JSON
echo json_encode($units);
