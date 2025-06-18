<?php
header('Content-Type: application/json');
include '../db/connect.php';

try {
    $stmt = $conn->query("SELECT recipient, priority, message, created_at FROM announcements ORDER BY created_at DESC LIMIT 50");
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $announcements]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch announcements: ' . $e->getMessage()]);
}
