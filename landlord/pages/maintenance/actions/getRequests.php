<?php
// actions/getRequestsData.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/connect.php';

try {
    $stmt = $pdo->prepare("
    SELECT
        mr.*,
        b.building_name,
        ra.id AS assignment_id,
        ra.status,
        p.name AS provider_name,
        p.email AS provider_email,
        p.phone AS provider_phone
    FROM maintenance_requests mr

    /* Join buildings to get building_name */
    LEFT JOIN buildings b
        ON mr.building_id = b.id

    /* Get latest assignment per request */
    LEFT JOIN (
        SELECT 
            ra.*, 
            ROW_NUMBER() OVER (
                PARTITION BY ra.maintenance_request_id 
                ORDER BY ra.created_at DESC
            ) AS row_num
        FROM maintenance_request_assignments ra
    ) ra 
        ON mr.id = ra.maintenance_request_id
       AND ra.row_num = 1

    /* Provider info */
    LEFT JOIN service_providers p 
        ON ra.provider_id = p.id

    ORDER BY mr.created_at DESC
");

    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $requests = [];
    $requestsError = $e->getMessage();
}
