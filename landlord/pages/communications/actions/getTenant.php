<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../db/connect.php';

try {
    $unit_id = (int)($_GET['unit_id'] ?? 0);

    if ($unit_id <= 0) {
        echo json_encode([
            'success' => true,
            'tenant' => null
        ]);
        exit;
    }

    // Get active tenant for the selected unit
    // tenancies.tenant_id  -> tenants.id
    // tenants.user_id      -> users.id (the id you use in conversations)
    $stmt = $pdo->prepare("
    SELECT
        te.user_id AS tenant_user_id,
        CONCAT(TRIM(te.first_name), ' ', TRIM(te.middle_name)) AS tenant_name,
        te.id AS tenant_id
    FROM tenancies tn
    INNER JOIN tenants te
        ON te.id = tn.tenant_id
    WHERE tn.unit_id = ?
      AND tn.status = 'Active'
    ORDER BY tn.leasing_start_date DESC, tn.id DESC
    LIMIT 1
");


    $stmt->execute([$unit_id]);

    $tenant = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'tenant' => $tenant ?: null
    ]);
    exit;
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Unable to fetch tenant at this time.',
        'erro message' => $e->getMessage(),
    ]);
    exit;
}
