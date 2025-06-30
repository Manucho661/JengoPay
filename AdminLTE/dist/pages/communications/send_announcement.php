<?php
header('Content-Type: application/json');
include '../db/connect.php'; // Make sure this sets up $pdo correctly

$recipient = $_POST['recipient'] ?? '';
$priority = $_POST['priority'] ?? '';
$message = $_POST['message'] ?? '';
$created_at = 'created_at';
$status = 'Sent'; // default status

// Validation
if (empty($recipient) || empty($priority) || empty($message)) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required.'
    ]);
    exit;
}

try {
    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO announcements (recipient, priority, message, created_at, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$recipient, $priority, $message, $created_at, $status]);

    echo json_encode([
        'success' => true,
        'message' => 'Announcement sent successfully'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
