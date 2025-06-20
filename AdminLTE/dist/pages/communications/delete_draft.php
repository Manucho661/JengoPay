<?php
header('Content-Type: application/json');

require_once '../db/connect.php'; // This must define $pdo

$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or missing ID']);
    exit;
}

try {
    // Optional: Ensure you're deleting only if status is 'Draft'
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = :id AND status = 'Draft'");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Draft not found or already deleted'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
