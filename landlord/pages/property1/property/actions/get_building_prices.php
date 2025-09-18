<?php
header('Content-Type: application/json');

// ✅ Use PDO and include the correct database connection path
$connectPath = __DIR__ . '/../../db/connect.php'; // Corrected path
if (!file_exists($connectPath)) {
    echo json_encode(['success' => false, 'error' => '❌ file not found']);
    exit;
}
require_once $connectPath; // This should define $pdo

// ✅ Validate input
$building_id = $_GET['building_id'] ?? null;
if (!$building_id || !is_numeric($building_id)) {
    echo json_encode(['success' => false, 'error' => '❌ Invalid or missing building_id']);
    exit;
}

try {
    // ✅ Prepare and execute PDO query
    $stmt = $pdo->prepare("SELECT electricity_price, water_price FROM buildings WHERE building_id = :building_id");
    $stmt->execute(['building_id' => $building_id]);
    $row = $stmt->fetch();

    // ✅ Respond with result
    if ($row) {
        echo json_encode([
            'success' => true,
            'electricity_price' => $row['electricity_price'],
            'water_price' => $row['water_price']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => '❌ Building not found']);
    }

} catch (PDOException $e) {
    // ❌ Handle any database error
    echo json_encode(['success' => false, 'error' => '❌ Database error: ' . $e->getMessage()]);
}
