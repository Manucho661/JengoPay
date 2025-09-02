<?php
header('Content-Type: application/json');

// Database connection
require_once '../db/connect.php';

try {
    // Get parameters from GET request
    $building = $_GET['building'] ?? '';
    $startDate = $_GET['start_date'] ?? '';
    $endDate = $_GET['end_date'] ?? '';

    // Basic validation
    if (!empty($startDate) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate)) {
        throw new Exception('Invalid start date format');
    }

    if (!empty($endDate) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
        throw new Exception('Invalid end date format');
    }

    // Build query
    $sql = "SELECT
                building_name,
                COALESCE(SUM(amount_collected), 0) as amount_collected,
                COALESCE(SUM(balances), 0) as balances,
                COALESCE(SUM(penalties), 0) as penalties,
                COALESCE(SUM(arrears), 0) as arrears,
                COALESCE(SUM(overpayment), 0) as overpayment
            FROM building_rent_summary
            WHERE 1=1";

    $params = [];
    $types = '';

    if (!empty($building)) {
        $sql .= " AND building_name = ?";
        $params[] = $building;
        $types .= 's';
    }

    if (!empty($startDate)) {
        $sql .= " AND payment_date >= ?";
        $params[] = $startDate;
        $types .= 's';
    }

    if (!empty($endDate)) {
        $sql .= " AND payment_date <= ?";
        $params[] = $endDate;
        $types .= 's';
    }

    $sql .= " GROUP BY building_name
              ORDER BY building_name";

    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format numeric values as floats
    foreach ($results as &$row) {
        $row['amount_collected'] = (float)$row['amount_collected'];
        $row['balances'] = (float)$row['balances'];
        $row['penalties'] = (float)$row['penalties'];
        $row['arrears'] = (float)$row['arrears'];
        $row['overpayment'] = (float)$row['overpayment'];
    }

    echo json_encode($results);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}