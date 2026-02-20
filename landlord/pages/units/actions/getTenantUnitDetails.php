<?php
header('Content-Type: application/json');

require_once '../../db/connect.php'; // PDO connection in $pdo

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Optional: filter by unit_id if provided
    $unitId = isset($_GET['unit_id']) ? (int)$_GET['unit_id'] : 0;

    $sql = "
        SELECT
            tn.id AS tenancy_id,
            b.building_name,
            bu.unit_number,
            t.first_name,
            t.middle_name,
            tn.move_in_date
        FROM tenancies tn
        INNER JOIN building_units bu
            ON bu.id = tn.unit_id
        INNER JOIN buildings b
            ON b.id = bu.building_id
        INNER JOIN tenants t
            ON t.id = tn.tenant_id
        WHERE LOWER(TRIM(tn.status)) = 'active'
    ";

    $params = [];

    if ($unitId > 0) {
        $sql .= " AND tn.unit_id = :unit_id";
        $params[':unit_id'] = $unitId;
    }

    // Latest active tenancy first
    $sql .= " ORDER BY tn.move_in_date DESC, tn.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    if ($unitId > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data'    => $row ?: null
        ]);
        exit;
    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data'    => $rows
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
