<?php
header('Content-Type: application/json');
include '../db/connect.php'; // or adjust path

if (!isset($_GET['building_id'])) {
    echo json_encode(['success' => false, 'message' => 'No property ID provided']);
    exit;
}

$propertyId = intval($_GET['building_id']);

try {
    $stmt = $pdo->prepare("SELECT county, ownership_info, units_number FROM buildings WHERE building_id = ?");
    $stmt->execute([$propertyId]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Property not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
