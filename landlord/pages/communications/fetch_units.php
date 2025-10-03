<?php
include '../db/connect.php';

if (!isset($_GET['building_id'])) {
    exit;
}

$buildingId = intval($_GET['building_id']);

// Get building name to match units
$stmt = $pdo->prepare("SELECT building_name FROM buildings WHERE id = ?");
$stmt->execute([$buildingId]);
$building = $stmt->fetch();

if (!$building) {
    exit("<option value=''>No Units Found</option>");
}

$buildingName = $building['building_name'];

// Fetch from all unit tables
$units = [];

// bed_seaters
$stmt = $pdo->prepare("SELECT id, unit_number FROM bed_seaters WHERE building_link = ?");
$stmt->execute([$buildingName]);
$units = array_merge($units, $stmt->fetchAll(PDO::FETCH_ASSOC));

// single_units
$stmt = $pdo->prepare("SELECT id, unit_number FROM single_units WHERE building_link = ?");
$stmt->execute([$buildingName]);
$units = array_merge($units, $stmt->fetchAll(PDO::FETCH_ASSOC));

// multi_rooms
$stmt = $pdo->prepare("SELECT id, unit_number FROM multi_rooms WHERE building_link = ?");
$stmt->execute([$buildingName]);
$units = array_merge($units, $stmt->fetchAll(PDO::FETCH_ASSOC));

// Generate <option>
if ($units) {
    foreach ($units as $u) {
        echo "<option value='".htmlspecialchars($u['id'])."'>".htmlspecialchars($u['unit_number'])."</option>";
    }
} else {
    echo "<option value=''>No Units Found</option>";
}
