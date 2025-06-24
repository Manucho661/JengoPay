<?php
header('Content-Type: application/json');

// Include your database connection
include '../../db/connect.php';


// Get the message ID from POST data
$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'No announcement ID provided']);
    exit;
}

try {
    // Update the announcement status to 'Archived' in your database
    $stmt = $pdo->prepare("UPDATE announcements SET status = 'Archived' WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No announcement found with that ID']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>