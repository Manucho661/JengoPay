<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once '../db/connect.php';

header('Content-Type: application/json');

try {
    // Validate required fields
    $required = ['id', 'priority', 'recipient', 'message'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    $id = (int)$_POST['id'];
    $priority = trim($_POST['priority']);
    $recipient = trim($_POST['recipient']);
    $message = trim($_POST['message']);

    // Validate ID
    if ($id <= 0) {
        throw new Exception('Invalid draft ID');
    }

    // Validate priority
    $allowedPriorities = ['Normal', 'High', 'Urgent'];
    if (!in_array($priority, $allowedPriorities)) {
        throw new Exception('Invalid priority value');
    }

    // Validate recipient
    $allowedRecipients = ['All Tenants', 'Specific Building', 'Individual Tenant'];
    if (!in_array($recipient, $allowedRecipients)) {
        throw new Exception('Invalid recipient value');
    }

    // Prepare update statement
    $stmt = $pdo->prepare("
        UPDATE announcements
        SET priority = ?, recipient = ?, message = ?, updated_at = NOW()
        WHERE id = ? AND status = 'draft'
    ");

    $success = $stmt->execute([$priority, $recipient, $message, $id]);

    if (!$success) {
        throw new Exception('Failed to update draft in database');
    }

    // Check if any rows were affected
    if ($stmt->rowCount() === 0) {
        throw new Exception('No draft found with that ID or it may have been published');
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Draft updated successfully'
    ]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>