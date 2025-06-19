<?php
header('Content-Type: application/json');
require_once '../db/connect.php'; // This sets up $pdo

try {
    // Use $pdo to fetch announcements
    // $stmt = $pdo->prepare("SELECT recipient, priority, message, created_at FROM announcements ORDER BY created_at DESC");
    $stmt = $pdo->query("SELECT id, recipient, priority, message, status, created_at FROM announcements ORDER BY created_at DESC");

    $stmt->execute();

    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $announcements
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
