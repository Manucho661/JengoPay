<?php
require_once '../db/connect.php'; // adjust if your path differs

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("UPDATE announcements SET status = 'Archived' WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing ID.']);
}
