<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Important for frontend

require_once '../../db/connect.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM inspections");
    $stmt->execute(); // You forgot this!
    $inspections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $inspections
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
