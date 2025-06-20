<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // ✅ Ensure JSON header

include '../../db/connect.php';

$unit_code = $_GET['unit_code'] ?? '';
$year = $_GET['year'] ?? date('Y');

// Start with 12 months initialized to 0
$monthlyRent = array_fill(1, 12, 0);

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
        $monthlyRent[$month] = (float)$row['total'];
    }

    // Return JSON array starting with January at index 0
    echo json_encode(array_values($monthlyRent));

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
 