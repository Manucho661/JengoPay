<?php
require_once '../../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    return;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            sp.name AS service_provider_name,
            mrp.proposed_budget,
            mrp.proposed_duration,
            mrp.provider_availability
        FROM maintenance_request_proposals mrp
        JOIN service_providers sp 
            ON mrp.service_provider_id = sp.id
        WHERE mrp.id = ?
    ");

    $stmt->execute([$id]);

    $proposals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // no output on success

} catch (Throwable $e) {
    echo "Error loading proposals: " . $e->getMessage();
}
