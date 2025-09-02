<?php

require_once '../db/connect.php'; // ✅ Use your correct DB config path
global $requests;

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
            r.description,
            r.priority, 
            r.availability,
            r.status, 
            r.payment_status, 
            r.is_read,
            (
                SELECT photo_url 
                FROM maintenance_photos 
                WHERE maintenance_request_id = r.id 
                LIMIT 1
            ) AS photo
        FROM maintenance_requests r
        ORDER BY r.request_date DESC
    ");
    
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $requests;  // <--- return data here

} catch (Exception $e) {
    // echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
