<?php
header('Content-Type: application/json');
require '../../../db/connect.php';

// Get ID from POST request
$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid or missing message ID'
    ]);
    exit;
}

try {
    // Check if message is "Sent" or "Archived"
    $check = $pdo->prepare("SELECT status FROM announcements WHERE id = ?");
    $check->execute([$id]);
    $row = $check->fetch();

    if (!$row) {
        echo json_encode([
            'success' => false,
            'error' => 'No message found with that ID',
            'id' => $id
        ]);
        exit;
    }

    if (!in_array($row['status'], ['Sent', 'Archived'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Only "Sent" or "Archived" messages can be deleted',
            'status' => $row['status']
        ]);
        exit;
    }

    // Perform delete
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Delete failed unexpectedly']);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
