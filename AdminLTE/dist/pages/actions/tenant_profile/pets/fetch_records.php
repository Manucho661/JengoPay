<?php
include '../../../db/connect.php';
header('Content-Type: application/json');

if (!isset($_GET['tenant_id'])) {
    echo json_encode([]);
    exit;
}

$tenant_id = $_GET['tenant_id'];

// Fetch pets
$stmt = $pdo->prepare("SELECT pet_name, weight, license_number FROM pets WHERE tenant_id = ?");
$stmt->execute([$tenant_id]);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch tenant details
$stmt = $pdo->prepare("
  SELECT tenants.id AS tenant_id, tenants.income_source, tenants.work_place, tenants.job_title, 
         tenants.residence, tenants.unit, tenants.status, tenants.id_no,
         users.first_name, users.middle_name, users.email
  FROM tenants
  JOIN users ON tenants.user_id = users.id
  WHERE tenants.id = ?
");
$stmt->execute([$tenant_id]);
$tenant = $stmt->fetch(PDO::FETCH_ASSOC);

// Combine and return as a single JSON response
$response = [
    'tenant' => $tenant ?: [],
    'pets' => $pets
];
echo json_encode($response);
?>
