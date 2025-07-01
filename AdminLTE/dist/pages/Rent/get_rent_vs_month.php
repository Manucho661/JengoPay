<?php
header('Content-Type: application/json');
include '../db/connect.php';

$building_id = isset($_GET['building_id']) ? (int)$_GET['building_id'] : 0;

$months = [
  'January' => [],
  'February' => [],
  'March' => [],
  'April' => [],
  'May' => [],
  'June' => [],
  'July' => [],
  'August' => [],
  'September' => [],
  'October' => [],
  'November' => [],
  'December' => []
];

try {
  $stmt = $pdo->prepare("
    SELECT amount_collected, payment_date
    FROM building_rent_summary
    WHERE building_id = ? AND payment_date IS NOT NULL AND amount_collected IS NOT NULL
    ORDER BY payment_date
  ");
  $stmt->execute([$building_id]);

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $monthName = date('F', strtotime($row['payment_date']));
    $months[$monthName][] = (float)$row['amount_collected'];
  }

  // Prepare chart data
  $chartData = [];
  foreach ($months as $month => $values) {
    $chartData[] = [
      'month' => $month,
      'values' => $values // could be empty []
    ];
  }

  echo json_encode($chartData);
} catch (PDOException $e) {
  echo json_encode(['error' => $e->getMessage()]);
}
