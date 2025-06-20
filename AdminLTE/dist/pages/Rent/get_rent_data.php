<?php
header('Content-Type: application/json');

// Include your database connection
include '../db/connect.php';

try {
    // Get filters from POST data
    $building = $_POST['building'] ?? '';
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';

    // Base query
    $sql = "SELECT
              building_name,
              SUM(amount_collected) as amount_collected,
              SUM(balances) as balances,
              SUM(penalties) as penalties,
              SUM(arrears) as arrears,
              SUM(overpayment) as overpayment
            FROM building_rent_summary
            WHERE 1=1";

    // Add filters if provided
    $params = [];

    if (!empty($building)) {
        $sql .= " AND building_name = :building";
        $params[':building'] = $building;
    }

    if (!empty($startDate)) {
        $sql .= " AND payment_date >= :start_date";
        $params[':start_date'] = $startDate;
    }

    if (!empty($endDate)) {
        $sql .= " AND payment_date <= :end_date";
        $params[':end_date'] = $endDate;
    }

    $sql .= " GROUP BY building_name";

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>