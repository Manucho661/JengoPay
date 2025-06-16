<?php
include '../../db/connect.php'; // Ensure $pdo is properly initialized

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['request_id'] ?? '';

    if (!empty($id)) {
        $stmt = $pdo->prepare("DELETE FROM maintenance_requests WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing ID']);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Invalid request method']);
