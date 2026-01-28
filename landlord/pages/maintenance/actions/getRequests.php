<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/connect.php';

try {
    // 1) Get landlord_id from the logged-in user
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }

    $userId = (int)$_SESSION['user']['id'];

    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception("Landlord account not found for this user.");
    }

    $landlord_id = (int)$landlord['id'];

    // 2) Fetch ONLY this landlord's maintenance requests along with the unit_number from the building_units table
    $stmt = $pdo->prepare("
    SELECT
        mr.*,
        mr.status,  -- Added to select status from maintenance_requests table
        b.building_name,
        bu.unit_number,  -- Added to fetch the unit number
        ra.id AS assignment_id,
        p.name  AS provider_name,
        p.email AS provider_email,
        p.phone AS provider_phone
    FROM maintenance_requests mr

    LEFT JOIN buildings b
        ON mr.building_id = b.id

    LEFT JOIN building_units bu  -- Join with building_units to get unit_number
        ON mr.building_unit_id = bu.id

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

    LEFT JOIN service_providers p
        ON ra.provider_id = p.id

    WHERE mr.landlord_id = :landlord_id
    ORDER BY mr.created_at DESC
");


    $stmt->execute(['landlord_id' => $landlord_id]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $requests = [];
    $requestsError = $e->getMessage();
}
