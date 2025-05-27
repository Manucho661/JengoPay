<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../../db/connect.php';

try {
    // Step 1: Automatically update outdated inspections
    $today = date('Y-m-d');
    $updateStmt = $pdo->prepare("
        UPDATE inspections 
        SET status = 'incomplete'
        WHERE date < :today AND status NOT IN ('completed', 'incomplete')
    ");
    $updateStmt->execute(['today' => $today]);

    // Step 2: Now fetch the inspections
    $stmt = $pdo->prepare("SELECT * FROM inspections ORDER BY date DESC");
    $stmt->execute();
    $inspections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 3: Send JSON response
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
