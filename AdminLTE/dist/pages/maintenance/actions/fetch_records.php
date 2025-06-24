<?php
header('Content-Type: application/json');

require_once '../../db/connect.php';

try {
    // Fetch maintenance requests along with provider details
    $stmt = $pdo->prepare("
        SELECT 
        mr.*, 
        p.name AS provider_name, 
        p.email AS provider_email,
        p.phone AS provider_phone,
        mp.photo_url
    FROM 
        maintenance_requests mr
    LEFT JOIN 
        providers p ON mr.provider_id = p.id
    LEFT JOIN 
        maintenance_photos mp ON mp.maintenance_request_id = mr.id

    ");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $requests
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
