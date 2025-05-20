<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../../db/connect.php';

/**
 * Processes and inserts inspection items based on POST data.
 *
 * @param PDO $pdo
 * @param int $inspectionId
 * @param array $items
 * @throws Exception if required data is missing or insert fails
 */
function processInspectionItems(PDO $pdo, int $inspectionId, array $items): void {
    foreach ($items as $item) {
        $statusKey = "{$item}_condition";
        $descKey = "{$item}_description";

        if (!isset($_POST[$statusKey])) {
            throw new Exception("Missing required field: {$statusKey}");
        }

        $status = trim($_POST[$statusKey]);
        $description = isset($_POST[$descKey]) ? trim($_POST[$descKey]) : null;

        $stmt = $pdo->prepare("
            INSERT INTO inspection_items (inspection_id, category, status, description)
            VALUES (:inspection_id, :category, :status, :description)
        ");

        $stmt->execute([
            'inspection_id' => $inspectionId,
            'category'      => ucfirst($item),
            'status'        => $status,
            'description'   => $status === 'Needs Repair' ? $description : null
        ]);
    }
}

// Define your inspection items
$inspectionItems = ['window', 'floor', 'socket'];

// Hardcoded inspection ID
$inspection_id = 20;

// Try to process and insert items
try {
    processInspectionItems($pdo, $inspection_id, $inspectionItems);
    echo "Inspection items saved successfully.";
} catch (Exception $e) {
   error_log("Error processing inspection items: " . $e->getMessage());
    echo "An error occurred: " . $e->getMessage(); // For debugging only

}
?>
