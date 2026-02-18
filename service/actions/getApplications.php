<?php
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$error = '';


try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['user']['id'])) {
        throw new Exception('User not authenticated.');
    }

    $userId = (int) $_SESSION['user']['id'];

    /* -----------------------------------------
       0) Resolve service_provider_id for user
    ----------------------------------------- */
    $stmtP = $pdo->prepare("
        SELECT id
        FROM service_providers
        WHERE user_id = ?
        LIMIT 1
    ");
    $stmtP->execute([$userId]);
    $provider = $stmtP->fetch(PDO::FETCH_ASSOC);

    if (!$provider) {
        throw new Exception('Service provider account not found for this user.');
    }

    $providerId = (int) $provider['id'];

    /* -----------------------------------------
       1) MAIN QUERY (only this provider)
    ----------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT 
            mrp.id AS proposal_id,
            mrp.proposed_budget,
            mrp.proposed_duration,
            mrp.created_at,
            mr.id,
            mr.created_at AS request_created_at,
            mr.category,
            mr.title,
            mr.description,
            mr.budget,
            mr.duration,
            mrp.status,
            mp.photo_path,
            mr.building_id,
            mr.building_unit_id
        FROM maintenance_request_proposals AS mrp
        LEFT JOIN maintenance_requests AS mr
            ON mrp.maintenance_request_id = mr.id 
        LEFT JOIN maintenance_request_photos AS mp
            ON mr.id = mp.maintenance_request_id
        WHERE mrp.service_provider_id = :provider_id
          AND mr.availability = 'available'
          AND (mrp.status IS NULL OR LOWER(TRIM(mrp.status)) <> 'Withdrawn')
        ORDER BY mrp.created_at DESC, mrp.id DESC
    ");

    $stmt->execute([':provider_id' => $providerId]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* -----------------------------------------
       2) COUNTS
    ----------------------------------------- */
    $totalApplications = count($applications);
    $pending = 0;
    $accepted = 0;
    $declined = 0;

    foreach ($applications as $app) {
        $status = strtolower(trim((string)($app['status'] ?? '')));

        if ($status === 'pending') {
            $pending++;
        } elseif ($status === 'accepted') {
            $accepted++;
        } elseif ($status === 'rejected') {
            $declined++;
        }
    }

    /* -----------------------------------------
       3) Collect building_id & unit_id
    ----------------------------------------- */
    $buildingIds = [];
    $unitIds = [];

    foreach ($applications as $app) {
        if (!empty($app['building_id'])) {
            $buildingIds[(int)$app['building_id']] = true;
        }
        if (!empty($app['building_unit_id'])) {
            $unitIds[(int)$app['building_unit_id']] = true;
        }
    }

    $buildingIds = array_keys($buildingIds);
    $unitIds = array_keys($unitIds);

    /* -----------------------------------------
       4) Fetch buildings
    ----------------------------------------- */
    $buildingsMap = [];
    if (!empty($buildingIds)) {
        $placeholders = implode(',', array_fill(0, count($buildingIds), '?'));
        $stmtB = $pdo->prepare("
            SELECT id, building_name 
            FROM buildings 
            WHERE id IN ($placeholders)
        ");
        $stmtB->execute($buildingIds);
        $buildingsMap = $stmtB->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /* -----------------------------------------
       5) Fetch units
    ----------------------------------------- */
    $unitsMap = [];
    if (!empty($unitIds)) {
        $placeholders = implode(',', array_fill(0, count($unitIds), '?'));
        $stmtU = $pdo->prepare("
            SELECT id, unit_number 
            FROM building_units 
            WHERE id IN ($placeholders)
        ");
        $stmtU->execute($unitIds);
        $unitsMap = $stmtU->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /* -----------------------------------------
       6) Merge names
    ----------------------------------------- */
    foreach ($applications as &$app) {
        $app['building_name'] = $buildingsMap[(int)$app['building_id']] ?? null;
        $app['unit_number']   = $unitsMap[(int)$app['building_unit_id']] ?? null;
    }
    unset($app);

} catch (Throwable $e) {
    $error = $e->getMessage();
    exit;
}
