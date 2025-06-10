<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once '../../db/connect.php';

$table = $_GET['table'] ?? 'inspections'; // default to 'inspections'

try {
    // Step 1: Auto-update inspections if we're fetching inspections
    if ($table === 'inspections') {
        $today = date('Y-m-d');
        $updateStmt = $pdo->prepare("
            UPDATE inspections 
            SET status = 'incomplete'
            WHERE date < :today AND status NOT IN ('completed', 'incomplete')
        ");
        $updateStmt->execute(['today' => $today]);

        // Fetch inspections
        $stmt = $pdo->prepare("SELECT * FROM inspections ORDER BY date DESC");
    } elseif ($table === 'inspection_items') {
        // Fetch inspection items
         if (isset($_GET['inspection_id'])) {
        $inspectionId = $_GET['inspection_id'];
        $stmt = $pdo->prepare("SELECT * FROM inspection_items WHERE inspection_id = :id ORDER BY id DESC");
        $stmt->execute(['id' => $inspectionId]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM inspection_items ORDER BY id DESC");
        $stmt->execute();
    }
    } else {
        throw new Exception("Invalid table requested.");
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $results
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
