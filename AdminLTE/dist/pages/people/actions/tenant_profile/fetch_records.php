<?php

include '../../../db/connect.php';
header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    echo json_encode(['error' => 'user_id not provided']);
    exit;
}

$user_id = $_GET['user_id'];

// Fetch tenant and user details
$stmt = $pdo->prepare("
    SELECT tenants.id AS tenant_id, tenants.income_source, tenants.work_place, tenants.job_title,
           tenants.residence, tenants.unit, tenants.status, tenants.id_no, tenants.phone_number,
           users.first_name, users.middle_name, users.email
    FROM tenants
    JOIN users ON tenants.user_id = users.id
    WHERE tenants.user_id = ?
");
$stmt->execute([$user_id]);
$tenant = $stmt->fetch(PDO::FETCH_ASSOC);

if ($tenant === false) {
    echo json_encode(['message' => 'No tenant found for that user_id']);
    exit;
}

// Fetch associated files
$stmt2 = $pdo->prepare("SELECT file_name,file_path FROM files WHERE tenant_id = ?");
$stmt2->execute([$tenant['tenant_id']]);
$files = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Respond with combined data
$response = [
    'tenant' => $tenant,
    'files' => $files
];

echo json_encode($response);
?>
