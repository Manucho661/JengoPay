<?php
include '../db/connect.php';

$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;
$buildingId = $_GET['building_id'] ?? null;

try {
    // Initialize all totals
    $totals = [
        'rent' => 0,
        'water' => 0,
        'garbage' => 0,
        'maintenance' => 0,
        'salaries' => 0,
        // Add more categories here if needed
    ];

    // Build WHERE clause for date filtering
    $dateWhere = "";
    if ($startDate && $endDate) {
        $dateWhere = "AND i.invoice_date BETWEEN :start_date AND :end_date";
    }

    // Build WHERE clause for building filtering
    $buildingWhere = "";
    if ($buildingId && $buildingId !== 'all') {
        $buildingWhere = "AND i.building_id = :building_id";
    }

    // Define account items
    $accountItems = [
        'rent' => '500',
        'water' => '501',
        'garbage' => '502',
        'maintenance' => '601',
        'salaries' => '602',
    ];

    foreach ($accountItems as $key => $accountItem) {
        $stmt = $pdo->prepare("
            SELECT SUM(ii.sub_total) AS total
            FROM invoice_items ii
            JOIN invoice i ON ii.invoice_number = i.invoice_number
            WHERE ii.account_item = :account_item
            $dateWhere
            $buildingWhere
        ");

        $stmt->bindParam(':account_item', $accountItem);

        if ($startDate && $endDate) {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }
        if ($buildingId && $buildingId !== 'all') {
            $stmt->bindParam(':building_id', $buildingId);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $totals[$key] = $result['total'] ?? 0;
    }

    // Calculate summary
    $totalIncome = $totals['rent'] + $totals['water'] + $totals['garbage'];
    $totalExpenses = $totals['maintenance'] + $totals['salaries'];
    $netProfit = $totalIncome - $totalExpenses;

    $summary = [
        'total_income' => $totalIncome,
        'total_expenses' => $totalExpenses,
        'net_profit' => $netProfit
    ];

    // Format numbers
    $formatted = [];
    foreach ($totals as $key => $value) {
        $formatted[$key] = number_format($value, 2);
    }
    $formatted['total_income'] = number_format($totalIncome, 2);
    $formatted['total_expenses'] = number_format($totalExpenses, 2);
    $formatted['net_profit'] = number_format($netProfit, 2);

    // Return JSON
    header('Content-Type: application/json');
    echo json_encode([
        'totals' => $totals,
        'summary' => $summary,
        'formatted' => $formatted
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
