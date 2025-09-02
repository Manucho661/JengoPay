<?php
require_once '../db/connect.php';

header('Content-Type: application/json');

try {
    // Fetch all buildings
    $query = "SELECT building_id, building_name FROM buildings ORDER BY building_name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($buildings);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>