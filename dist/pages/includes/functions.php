<?php
require_once '../db/connect.php'; // This should define and expose $pdo as a PDO instance

function get_all_buildings(PDO $pdo) {
    $sql = "SELECT * FROM buildings ORDER BY building_name ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_rent_summary(PDO $pdo, $month, $year) {
    $summary = [
        'collected' => 0,
        'penalties' => 0,
        'arrears' => 0,
        'overpayment' => 0
    ];

    $sql = "SELECT
                SUM(collected_amount) AS total_collected,
                SUM(penalties_amount) AS total_penalties,
                SUM(arrears_amount) AS total_arrears,
                SUM(overpayment_amount) AS total_overpayment
            FROM rent_entries
            WHERE month = :month AND year = :year";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':month' => $month,
        ':year' => (int)$year
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $summary['collected'] = $row['total_collected'] ?? 0;
        $summary['penalties'] = $row['total_penalties'] ?? 0;
        $summary['arrears'] = $row['total_arrears'] ?? 0;
        $summary['overpayment'] = $row['total_overpayment'] ?? 0;
    }

    return $summary;
}

function get_rent_details(PDO $pdo, $building_id = null, $month, $year) {
    $params = [
        ':month' => $month,
        ':year' => (int)$year
    ];

    $sql = "SELECT b.building_name AS building_name, re.*
            FROM rent_entries re
            JOIN buildings b ON re.building_id = b.building_id
            WHERE re.month = :month AND re.year = :year";

    if ($building_id !== null && $building_id !== 'all') {
        $sql .= " AND b.id = :building_id";
        $params[':building_id'] = (int)$building_id;
    }

    $sql .= " ORDER BY b.building_name ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_distinct_years(PDO $pdo) {
    $sql = "SELECT DISTINCT year FROM rent_entries ORDER BY year DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function get_distinct_months(PDO $pdo, $year = null) {
    $params = [];
    $sql = "SELECT DISTINCT month FROM rent_entries";

    if ($year !== null) {
        $sql .= " WHERE year = :year";
        $params[':year'] = (int)$year;
    }

    $sql .= " ORDER BY FIELD(month,
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December')";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>
