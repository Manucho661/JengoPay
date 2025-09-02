<?php

include '../../../db/connect.php';
header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    echo json_encode(['error' => 'user_id not provided']);
    exit;
}

$user_id = $_GET['user_id'];

// Fetch tenant, user, and income source details
$stmt = $pdo->prepare("
    SELECT
        tenants.id AS tenant_id,
        tenants.building_id, tenants.unit_id, tenants.status, tenants.id_no, tenants.phone_number,
        users.first_name, users.middle_name, users.email,
        income_source.income_type AS income_source,
        income_source.place_of_work AS work_place,
        income_source.job_title,
        income_source.employer_name
    FROM tenants
    JOIN users ON tenants.user_id = users.id
    LEFT JOIN income_source ON tenants.id = income_source.tenant_id
    WHERE tenants.user_id = ?
");


$stmt->execute([$user_id]);
$tenant = $stmt->fetch(PDO::FETCH_ASSOC);

if ($tenant === false) {
    echo json_encode(['message' => 'No tenant found for that user_id']);
    exit;
}

// Fetch associated files
$stmt2 = $pdo->prepare("SELECT file_name, file_path FROM files WHERE tenant_id = ?");
$stmt2->execute([$tenant['tenant_id']]);
$files = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Respond with combined data
$response = [
    'tenant' => $tenant,
    'files' => $files
];

echo json_encode($response);
?>
