<?php
require_once '../../../db/connect.php'; // $pdo
include_once '../processes/encrypt_decrypt_function.php';


set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$buildingId = $_GET['id'] ?? null;

if (isset($_GET['id'])) {
    $buildingId = encryptor('decrypt', $_GET['id']);
    $_SESSION['building_id'] = $buildingId;
}

try {

    /* =========================
       1) BUILDING (by id)
          + county_name + constituency_name
    ========================= */
    $stmt = $pdo->prepare("
        SELECT
            b.*,
            c.name AS county_name,
            co.name AS constituency_name
        FROM buildings b
        LEFT JOIN counties c
            ON c.id = b.county
        LEFT JOIN constituencies co
            ON co.id = b.constituency
        WHERE b.id = :id
        LIMIT 1
    ");
    $stmt->execute(['id' => (int)$buildingId]);
    $building = $stmt->fetch(PDO::FETCH_ASSOC);

    // if (!$building) {
    //     die("Building not found");
    // }

    /* =========================
       2) UNITS (with category, status, tenant)
    ========================= */
    $stmt = $pdo->prepare("
        SELECT
            bu.id AS unit_id,
            bu.unit_number,
            bu.monthly_rent,
            uc.category_name,

            CASE
                WHEN tn.id IS NULL THEN 'Vacant'
                ELSE 'Occupied'
            END AS unit_status,

            te.first_name AS tenant_first_name

        FROM building_units bu
        LEFT JOIN unit_categories uc
            ON uc.id = bu.unit_category_id

        -- latest ACTIVE tenancy per unit (if any)
        LEFT JOIN tenancies tn
            ON tn.id = (
                SELECT t2.id
                FROM tenancies t2
                WHERE t2.unit_id = bu.id
                  AND t2.status = 'Active'
                ORDER BY t2.created_at DESC, t2.id DESC
                LIMIT 1
            )

        LEFT JOIN tenants te
            ON te.id = tn.tenant_id

        WHERE bu.building_id = :bid
        ORDER BY bu.unit_number ASC, bu.id ASC
    ");
    $stmt->execute(['bid' => (int)$buildingId]);
    $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalUnits= count($units);


    /* =========================
       3) MAINTENANCE REQUESTS (for building)
          + assigned provider name
    ========================= */
    $stmt = $pdo->prepare("
        SELECT
            mr.*,
            sp.name AS assigned_provider_name
        FROM maintenance_requests mr
        LEFT JOIN service_providers sp
            ON sp.id = mr.assigned_to_provider_id
        WHERE mr.building_id = :bid
        ORDER BY mr.created_at DESC, mr.id DESC
    ");
    $stmt->execute(['bid' => (int)$buildingId]);
    $maintenance_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {
    echo $e->getMessage();
}
