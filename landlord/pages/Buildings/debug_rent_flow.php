<?php
session_start();
require_once "../db/connect.php";

echo "<h3>Debug: Rent Amount Flow Check</h3>";

// Get a sample tenant
$stmt = $pdo->query("SELECT id, first_name, last_name, unit_id FROM tenants LIMIT 5");
$tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h4>Tenants and Their Units:</h4>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Tenant ID</th><th>Name</th><th>Unit ID</th><th>Unit Number</th><th>Monthly Rent</th></tr>";

foreach ($tenants as $tenant) {
    $unit_id = $tenant['unit_id'];
    $monthly_rent = 0;
    $unit_number = 'N/A';
    
    if ($unit_id) {
        $unit_stmt = $pdo->prepare("SELECT unit_number, monthly_rent FROM building_units WHERE id = ?");
        $unit_stmt->execute([$unit_id]);
        $unit = $unit_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($unit) {
            $unit_number = $unit['unit_number'];
            $monthly_rent = $unit['monthly_rent'];
        }
    }
    
    echo "<tr>";
    echo "<td>{$tenant['id']}</td>";
    echo "<td>{$tenant['first_name']} {$tenant['last_name']}</td>";
    echo "<td>{$unit_id}</td>";
    echo "<td>{$unit_number}</td>";
    echo "<td>KES " . number_format($monthly_rent, 2) . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h4>All Units with Monthly Rent:</h4>";
$units_stmt = $pdo->query("SELECT id, unit_number, monthly_rent FROM building_units WHERE monthly_rent > 0");
$units = $units_stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Unit ID</th><th>Unit Number</th><th>Monthly Rent</th></tr>";

foreach ($units as $unit) {
    echo "<tr>";
    echo "<td>{$unit['id']}</td>";
    echo "<td>{$unit['unit_number']}</td>";
    echo "<td>KES " . number_format($unit['monthly_rent'], 2) . "</td>";
    echo "</tr>";
}

echo "</table>";
?>