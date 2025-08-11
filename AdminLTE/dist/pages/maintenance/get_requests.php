<?php
header('Content-Type: application/json');
require_once '../db/connect.php'; // ✅ Use your correct DB config path

try {
    // Fetch requests and join with one photo from maintenance_photos (if available)
    $stmt = $pdo->prepare("
        SELECT 
            r.id, 
            r.request_date, 
            r.residence, 
            r.unit, 
            r.category, 
            r.request, 
            r.request, 
            r.priority, 
            r.availability,
            r.status, 
            r.payment_status, 
            r.is_read,
            p.photo_url AS photo
        FROM maintenance_requests r
        LEFT JOIN maintenance_photos p ON r.id = p.maintenance_request_id
        GROUP BY r.id
        ORDER BY r.request_date DESC
    ");

    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($requests);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
