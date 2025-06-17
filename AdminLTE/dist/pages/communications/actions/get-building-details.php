<?php
// Display all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set response type to JSON
header('Content-Type: application/json');

include '../../db/connect.php'; // adjust path if needed


if (isset($_GET['building_id'])) {
    $buildingId = $_GET['building_id'];

    $stmt = $pdo->prepare("SELECT building_id, building_name, building_type FROM buildings WHERE building_id = ?");
    $stmt->execute([$buildingId]);
    $building = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($building) {
        echo json_encode(['success' => true, 'building' => $building]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Building not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No building_id provided']);
}
