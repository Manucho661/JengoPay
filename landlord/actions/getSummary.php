<?php
header('Content-Type: application/json');

require_once '../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {

    // Count buildings
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM buildings");
    $stmt->execute();

    $totalBuildings = $stmt->fetchColumn(); // returns the count

    echo json_encode([
        'success' => true,
        'totalBuildings' => $totalBuildings
    ]);

} catch (Throwable $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
