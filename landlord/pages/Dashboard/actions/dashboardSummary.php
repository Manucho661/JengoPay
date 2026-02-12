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

    $userId = (int) $_SESSION['user']['id'];

    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception("Landlord account not found for this user.");
    }

    $landlord_id = (int) $landlord['id'];

    // 2) Get all counts in a single query
    $stmt = $pdo->prepare("
        SELECT
            (SELECT COUNT(*) FROM buildings WHERE landlord_id = ?) AS buildingCount,
            (SELECT COUNT(*) FROM tenants WHERE landlord_id = ? AND status = 'active') AS tenantCount,
            (SELECT COUNT(*) FROM maintenance_requests WHERE landlord_id = ? AND status = 'submitted') AS requestCount
    ");
    $stmt->execute([$landlord_id, $landlord_id, $landlord_id]);
    $counts = $stmt->fetch(PDO::FETCH_ASSOC);

    // Extract variables
    $buildingCount = (int) ($counts['buildingCount'] ?? 0);
    $tenantCount   = (int) ($counts['tenantCount'] ?? 0);
    $requestCount  = (int) ($counts['requestCount'] ?? 0);

    /* ---------------------------------------
       3) Last 4 buildings for this landlord
    ---------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT
            id,
            building_name,
            created_at
        FROM buildings
        WHERE landlord_id = ?
        ORDER BY created_at DESC, id DESC
        LIMIT 4
    ");
    $stmt->execute([$landlord_id]);
    $lastBuildings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* ---------------------------------------
        4) Last 4 maintenance requests created (WITH building + unit)
        ---------------------------------------- */
        $stmt = $pdo->prepare("
        SELECT
            mr.id,
            mr.title,
            mr.status,
            mr.created_at,
            mr.building_id,
            mr.building_unit_id,
            b.building_name,
            u.unit_number
        FROM maintenance_requests mr
        LEFT JOIN buildings b
            ON b.id = mr.building_id
        LEFT JOIN building_units u
            ON u.id = mr.building_unit_id
        WHERE mr.landlord_id = ?
        ORDER BY mr.created_at DESC, mr.id DESC
        LIMIT 4
    ");
        $stmt->execute([$landlord_id]);
        $lastMaintenanceRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Now available:
    // $buildingCount, $tenantCount, $requestCount
    // $lastBuildings, $lastMaintenanceRequests

} catch (Throwable $e) {
    echo "Error: " . $e->getMessage();
}
