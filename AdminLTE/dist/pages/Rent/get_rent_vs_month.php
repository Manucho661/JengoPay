<?php
// get_rent_vs_months.php
header('Content-Type: application/json');
include '../db/connect.php';

try {
    // Format payment_date to 'Month YYYY' (e.g., May 2025)
    $stmt = $pdo->query("
        SELECT DATE_FORMAT(payment_date, '%M %Y') AS period,
               SUM(amount_paid) AS total
        FROM tenant_rent_summary
        GROUP BY period
        ORDER BY MIN(payment_date)
    ");

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
