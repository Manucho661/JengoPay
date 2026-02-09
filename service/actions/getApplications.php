<?php
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$error = '';

try {

    /**
     * 1) MAIN QUERY (no building/unit joins)
     */
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
        WHERE mr.availability = 'available'
    ");
    $stmt->execute();
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * 2) COUNTS (kept as standalone variables)
     */
    $totalApplications = count($applications);
    $pending = 0;
    $accepted = 0;
    $declined = 0;

    foreach ($applications as $app) {
        if ($app['status'] === 'Pending') {
            $pending++;
        } elseif ($app['status'] === 'Accepted') {
            $accepted++;
        } elseif ($app['status'] === 'Rejected') {
            $declined++;
        }
    }

    /**
     * 3) COLLECT UNIQUE building_id & unit_id
     */
    $buildingIds = [];
    $unitIds = [];

    foreach ($applications as $app) {
        if (!empty($app['building_id'])) {
            $buildingIds[$app['building_id']] = true;
        }
        if (!empty($app['building_unit_id'])) {
            $unitIds[$app['building_unit_id']] = true;
        }
    }

    $buildingIds = array_keys($buildingIds);
    $unitIds = array_keys($unitIds);

    /**
     * 4) FETCH BUILDINGS
     */
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

    /**
     * 5) FETCH UNITS
     */
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

    /**
     * 6) MERGE NAMES INTO APPLICATIONS
     */
    foreach ($applications as &$app) {
        $app['building_name'] = $buildingsMap[$app['building_id']] ?? null;
        $app['unit_number']   = $unitsMap[$app['building_unit_id']] ?? null;
    }
    unset($app);

} catch (Throwable $e) {
    $error = $e->getMessage();
}
