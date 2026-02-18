<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../../db/connect.php';

try {
    $building_id = (int)($_GET['building_id'] ?? 0);

    if ($building_id <= 0) {
        echo json_encode([
            'success' => true,
            'units' => []
        ]);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id, unit_number
        FROM building_units
        WHERE building_id = ?
        ORDER BY unit_number ASC
    ");

    $stmt->execute([$building_id]);

    echo json_encode([
        'success' => true,
        'units' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
    exit;

} catch (Throwable $e) {
    // Log server-side if you have logging set up
    // error_log($e->getMessage());

    echo json_encode([
        'success' => false,
        'error' => 'Unable to fetch units at this time.'
    ]);
    exit;
}
