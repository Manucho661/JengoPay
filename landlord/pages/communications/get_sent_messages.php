<?php
header('Content-Type: application/json');
include '../../db/connect.php';

try {
    $query = "SELECT * FROM announcements
              WHERE status IN ('Sent', 'Archived')
              ORDER BY
                CASE WHEN status = 'Sent' THEN 0 ELSE 1 END,
                created_at DESC";

    $stmt = $pdo->query($query);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>