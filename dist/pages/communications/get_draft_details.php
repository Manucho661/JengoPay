<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once '../db/connect.php';

header('Content-Type: application/json');

try {
    // Validate input
    if (!isset($_GET['id'])) {
        throw new Exception('Missing draft ID parameter');
    }

    $draftId = (int)$_GET['id'];
    if ($draftId <= 0) {
        throw new Exception('Invalid draft ID');
    }

    // Debug: Log the received ID
    error_log("Fetching draft with ID: $draftId");

    // Prepare and execute query
    $stmt = $pdo->prepare("SELECT id, priority, recipient, message
                          FROM announcements
                          WHERE id = ? AND status = 'draft'");
    $stmt->execute([$draftId]);
    $draft = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$draft) {
        // Check if the record exists at all
        $stmt = $pdo->prepare("SELECT id FROM announcements WHERE id = ?");
        $stmt->execute([$draftId]);
        if (!$stmt->fetch()) {
            throw new Exception('Draft does not exist');
        }
        throw new Exception('Record exists but is not a draft');
    }

    // Return the draft data
    echo json_encode([
        'success' => true,
        'data' => $draft
    ]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Database error',
        'debug' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>