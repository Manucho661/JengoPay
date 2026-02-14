<?php
include '../db/connect.php';

$buildingId = isset($_GET['building_id']) ? intval($_GET['building_id']) : 0;
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

if ($buildingId === 0) {
    echo json_encode(['error' => 'Invalid building ID']);
    exit;
}

// Step 1: Get building name
$stmt = $pdo->prepare("SELECT building_name FROM tenant_rent_summary WHERE id = ?");
$stmt->execute([$buildingId]);
$row = $stmt->fetch();

if (!$row) {
    echo json_encode(['error' => 'Building not found']);
    exit;
}

$buildingName = $row['building_name'];

// Step 2: Define all months with their numeric values
$allMonths = [
    'Jan' => 1, 'Feb' => 2, 'Mar' => 3, 'Apr' => 4,
    'May' => 5, 'Jun' => 6, 'Jul' => 7, 'Aug' => 8,
    'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12
];

// Initialize data for all months
$monthData = [];
foreach ($allMonths as $monthName => $monthNum) {
    $monthData[$monthName] = [
        'month' => $monthName,
        'total_collected' => 0.00,
        'year' => $year
    ];
}

// Step 3: Fetch actual collected data per month using payment_date
$query = "
    SELECT
        DATE_FORMAT(payment_date, '%b') AS month_short,
        MONTH(payment_date) AS month_num,
        SUM(amount_paid) AS total_collected
    FROM tenant_rent_summary
    WHERE building_name = ?
    AND YEAR(payment_date) = ?
    GROUP BY month_num, month_short
    ORDER BY month_num
";
$stmt = $pdo->prepare($query);
$stmt->execute([$buildingName, $year]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $month = $row['month_short'];
    $amount = (float)$row['total_collected'];

    if (isset($monthData[$month])) {
        $monthData[$month]['total_collected'] = $amount;
    }
}

// Convert to sequential array for response
$response = array_values($monthData);

echo json_encode($response);
?>