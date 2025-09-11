<?php
include '../db/connect.php';

$buildingId = isset($_GET['building_id']) ? intval($_GET['building_id']) : 0;

if ($buildingId === 0) {
    echo json_encode(['error' => 'Invalid building ID']);
    exit;
}

$stmt = $pdo->prepare("SELECT DISTINCT building_name FROM tenant_rent_summary WHERE id = ? LIMIT 1");
$stmt->execute([$buildingId]);
$building = $stmt->fetch();

if (!$building) {
    echo json_encode(['error' => 'Building not found']);
    exit;
}

echo json_encode(['building_name' => $building['building_name']]);
?>