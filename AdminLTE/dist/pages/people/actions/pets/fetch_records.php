<?php
include '../../../db/connect.php';
header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_GET['user_id'];

// Get tenant ID
$stmt1 = $pdo->prepare("SELECT id FROM tenants WHERE user_id = ?");
$stmt1->execute([$user_id]);
$tenantRow = $stmt1->fetch(PDO::FETCH_ASSOC); // fetch single row instead of fetchAll

if (!$tenantRow) {
    echo json_encode([]); // No tenant found for this user
    exit;
}
$tenant_id = $tenantRow['id'];

// Fetch pets for that tenant
$stmt = $pdo->prepare("SELECT pet_name, weight, license_number FROM pets WHERE tenant_id = ?");
$stmt->execute([$tenant_id]);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($pets);
?>
