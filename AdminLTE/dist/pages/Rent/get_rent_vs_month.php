<?php
include '../db/connect.php'; // Use shared PDO connection

$buildingId = isset($_GET['building_id']) ? intval($_GET['building_id']) : 0;

if ($buildingId === 0) {
    echo json_encode(['error' => 'Invalid building ID']);
    exit;
}

// Step 1: Get the building name from ID
$stmt = $pdo->prepare("SELECT building_name FROM tenant_rent_summary WHERE id = ?");
$stmt->execute([$buildingId]);
$row = $stmt->fetch();

if (!$row) {
    echo json_encode(['error' => 'Building not found']);
    exit;
}

$buildingName = $row['building_name'];

// Step 2: Get all tenants from that building
$query = "
    SELECT tenant_name, month, SUM(amount_paid) AS total_paid
    FROM tenant_rent_summary
    WHERE building_name = ?
    GROUP BY tenant_name, month
    ORDER BY tenant_name, month
";
$stmt = $pdo->prepare($query);
$stmt->execute([$buildingName]);

$data = [];
$months = [];
$tenants = [];

while ($row = $stmt->fetch()) {
    $tenant = $row['tenant_name'];
    $month = $row['month'];
    $amount = (float)$row['total_paid'];

    if (!in_array($tenant, $tenants)) {
        $tenants[] = $tenant;
    }
    if (!in_array($month, $months)) {
        $months[] = $month;
    }

    $data[$tenant][$month] = $amount;
}

sort($months);

$response = [];
foreach ($tenants as $tenant) {
    $tenantData = [];
    foreach ($months as $month) {
        $tenantData[] = $data[$tenant][$month] ?? 0;
    }
    $response[] = [
        'label' => $tenant,
        'data' => $tenantData
    ];
}

echo json_encode([
    'labels' => $months,
    'datasets' => $response
]);
?>
