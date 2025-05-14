<?php

include '../../../db/connect.php';
header('Content-Type: application/json');

// Check the correct parameter
if (!isset($_GET['user_id'])) {
    echo json_encode(['error' => 'user_id not provided']);
    exit;
}

$user_id = $_GET['user_id'];

// Debug: Output the ID
// echo "User ID: $user_id";

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
    echo json_encode(['message' => 'No tenant found for that user_id ' ]);
} else {
    echo json_encode($tenant);
}
?>
