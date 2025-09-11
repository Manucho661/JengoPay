<?php
// get_rent_data.php
header('Content-Type: application/json');
include '../db/connect.php';

try {
    $stmt = $pdo->query("SELECT building_name, amount_collected FROM building_rent_summary WHERE amount_collected IS NOT NULL ORDER BY id ASC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
