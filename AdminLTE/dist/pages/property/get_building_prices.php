<?php
// get_building_prices.php
require_once '../db/connect.php'; // This should define a $pdo PDO object

header('Content-Type: application/json');

if (isset($_GET['building_id'])) {
    $buildingId = intval($_GET['building_id']);

    try {
        $query = "SELECT water_price, electricity_price FROM buildings WHERE building_id = :building_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':building_id', $buildingId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode([
                'success' => true,
                'water_price' => $row['water_price'],
                'electricity_price' => $row['electricity_price']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Building not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Building ID not provided']);
}
