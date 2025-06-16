<?php
require_once '../db/connect.php'; // Your database connection file

$filters = json_decode(file_get_contents('php://input'), true);

$query = "SELECT * FROM tenant_rent_summary WHERE 1=1";
$params = [];

if (!empty($filters['building'])) {
    $query .= " AND building_name = ?";
    $params[] = $filters['building'];
}

if (!empty($filters['unitType'])) {
    $query .= " AND unit_type = ?";
    $params[] = $filters['unitType'];
}

if (!empty($filters['year'])) {
    $query .= " AND year = ?";
    $params[] = $filters['year'];
}

if (!empty($filters['month'])) {
    $query .= " AND month = ?";
    $params[] = $filters['month'];
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate the table HTML (similar to your existing code but without the building headers)
$currentBuilding = '';
foreach ($tenants as $tenant) {
    $building = $tenant['building_name'] ?? '';

    if ($building !== $currentBuilding) {
        $currentBuilding = $building;
        echo '<tr class="table-group-header bg-light">';
        echo '<td colspan="6" style="font-weight: bold; color: #007bff;">';
        echo htmlspecialchars($currentBuilding);
        echo '</td></tr>';
    }

    // Output the tenant row as before
    // ...
}
?>