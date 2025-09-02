<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



header('Content-Type: application/json');

include '../../db/connect.php';

$unit_code = $_GET['unit_code'] ?? '';
$year = 2025;

// Create an array with 12 months (index 0 = January, 11 = December)
$monthlyRent = array_fill(0, 12, 0);

// Build WHERE clause
$where = ['`year` = ?'];
$params = [$year];

if (!empty($unit_code)) {
    $where[] = "unit_code = ?";
    $params[] = $unit_code;
}

$sql = "SELECT `month`, SUM(amount_paid) AS total 
        FROM tenant_rent_summary 
        WHERE " . implode(" AND ", $where) . "
        GROUP BY `month`";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $month = (int)$row['month'];
        if ($month >= 1 && $month <= 12) {
            $monthlyRent[$month - 1] = (float)$row['total']; // FIXED: adjust for 0-based index
        }
    }

    echo json_encode($monthlyRent); // returns [0, 0, ..., total] correctly for chart

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
