<?php
include '../db/connect.php';

// Get filter values from the request
$building = $_POST['building'] ?? '';
$unitType = $_POST['unit_type'] ?? '';
$year = $_POST['year'] ?? '';
$month = $_POST['month'] ?? '';

// Build dynamic query
$query = "SELECT * FROM tenant_rent_summary WHERE 1=1";
$params = [];

// Filter by building name
if (!empty($building)) {
    $query .= " AND building_name = ?";
    $params[] = $building;
}

// Filter by unit type
if (!empty($unitType)) {
    $query .= " AND unit_type = ?";
    $params[] = $unitType;
}

// Filter by year
if (!empty($year)) {
    $query .= " AND YEAR(payment_date) = ?";
    $params[] = $year;
}

// Filter by month (name or number)
if (!empty($month)) {
    // Convert month name to number if needed
    if (!is_numeric($month)) {
        $monthNumber = date('m', strtotime("1 $month"));
    } else {
        $monthNumber = str_pad($month, 2, '0', STR_PAD_LEFT);
    }

    $query .= " AND MONTH(payment_date) = ?";
    $params[] = $monthNumber;
}

// Prepare and execute
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render results
if ($results) {
    foreach ($results as $row) {
        echo "<tr>
            <td>{$row['building_name']}</td>
            <td>{$row['tenant_name']}</td>
            <td>{$row['unit_code']}</td>
            <td>{$row['unit_type']}</td>
            <td>{$row['amount_paid']}</td>
            <td>{$row['balances']}</td>
            <td>{$row['penalty']}</td>
            <td>{$row['penalty_days']}</td>
            <td>{$row['arrears']}</td>
            <td>{$row['overpayment']}</td>
            <td>{$row['payment_date']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='11'>No records found.</td></tr>";
}
?>
