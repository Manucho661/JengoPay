<?php
require_once '../../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$maintenanceRequestId = $_GET['id'] ?? null;

if (!$maintenanceRequestId || !is_numeric($maintenanceRequestId)) {
    return;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            mra.id AS assignment_id,
            mra.maintenance_request_id,
            mra.service_provider_id,
            mra.status,
            mra.assigned_at,

            sp.name,
            sp.phone,
            sp.email

        FROM maintenance_request_assignments mra
        JOIN service_providers sp 
            ON mra.service_provider_id = sp.id

        WHERE mra.maintenance_request_id = ?
          AND mra.status = 'active'
        LIMIT 1
    ");

    $stmt->execute([$maintenanceRequestId]);

    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    // no output here, just $assignment available for use

} catch (Throwable $e) {
    echo "Error loading assigned service provider: " . $e->getMessage();
}
