<?php
include '../../db/connect.php';

$buildingId = $_GET['id'] ?? null;
if (!$buildingId) {
    echo json_encode([]);
    exit;
}

$units = [];

// fetch bed seaters
$stmt = $pdo->prepare("SELECT id, entity_name, monthly_rent, occupancy_status, 'bed_seaters' AS unit_type 
                       FROM bed_seaters WHERE building_id = ?");
$stmt->execute([$buildingId]);
$units = array_merge($units, $stmt->fetchAll(PDO::FETCH_ASSOC));

// fetch single rooms
$stmt = $pdo->prepare("SELECT id, entity_name, monthly_rent, occupancy_status, 'single_rooms' AS unit_type 
                       FROM single_rooms WHERE building_id = ?");
$stmt->execute([$buildingId]);
$units = array_merge($units, $stmt->fetchAll(PDO::FETCH_ASSOC));

// fetch multi rooms
$stmt = $pdo->prepare("SELECT id, entity_name, monthly_rent, occupancy_status, 'multi_rooms' AS unit_type 
                       FROM multi_rooms WHERE building_id = ?");
$stmt->execute([$buildingId]);
$units = array_merge($units, $stmt->fetchAll(PDO::FETCH_ASSOC));

echo json_encode($units);
