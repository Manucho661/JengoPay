<?php
// Get filter parameters
$buildingFilter = $_GET['building_name'] ?? '';
$unitTypeFilter = $_GET['unit_type'] ?? '';
$yearFilter = $_GET['year'] ?? '';
$monthFilter = $_GET['month'] ?? '';

// Build the query with filters
$query = "SELECT * FROM tenant_rent_summary WHERE 1=1";

if (!empty($buildingFilter)) {
    $query .= " AND building_name = :building_name";
}
if (!empty($unitTypeFilter)) {
    $query .= " AND unit_type = :unit_type";
}
if (!empty($yearFilter)) {
    $query .= " AND year = :year";
}
if (!empty($monthFilter)) {
    $query .= " AND month = :month";
}

$query .= " ORDER BY building_name, unit_code";

$stmt = $pdo->prepare($query);

if (!empty($buildingFilter)) {
    $stmt->bindParam(':building_name', $buildingFilter);
}
if (!empty($unitTypeFilter)) {
    $stmt->bindParam(':unit_type', $unitTypeFilter);
}
if (!empty($yearFilter)) {
    $stmt->bindParam(':year', $yearFilter);
}
if (!empty($monthFilter)) {
    $stmt->bindParam(':month', $monthFilter);
}

$stmt->execute();
$tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Your table HTML goes here (the same as before) -->
<table id="rent" class="tableRent" style="font-size: small; width: 100%;">
    <!-- Table content -->
</table>