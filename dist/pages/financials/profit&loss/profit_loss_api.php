<?php
header('Content-Type: application/json');
require_once '../db/connect.php';

// Get filter parameters
$buildingId = $_POST['building_id'] ?? null;
$startDate = $_POST['start_date'] ?? null;
$endDate = $_POST['end_date'] ?? null;

try {
    // Validate dates
    if ($startDate && $endDate && strtotime($startDate) > strtotime($endDate)) {
        throw new Exception("End date must be after start date");
    }

    // Initialize totals
    $result = [
        'income' => [
            'rent' => 0,
            'water' => 0,
            'garbage' => 0,
            'late_fees' => 0,
            'commissions' => 0,
            'other_income' => 0,
            'total' => 0
        ],
        'expenses' => [
            'maintenance' => 0,
            'salaries' => 0,
            'electricity' => 0,
            'water' => 0,
            'garbage' => 0,
            'internet' => 0,
            'security' => 0,
            'software' => 0,
            'marketing' => 0,
            'legal' => 0,
            'loan_interest' => 0,
            'bank_charges' => 0,
            'other_expenses' => 0,
            'total' => 0
        ],
        'net_profit' => 0
    ];

    // INCOME CALCULATION (using created_at)
    $incomeQuery = "SELECT
                    SUM(CASE WHEN account_item = '500' THEN sub_total ELSE 0 END) as rent,
                    SUM(CASE WHEN account_item = '510' THEN sub_total ELSE 0 END) as water,
                    SUM(CASE WHEN account_item = '515' THEN sub_total ELSE 0 END) as garbage,
                    SUM(CASE WHEN account_item = '505' THEN sub_total ELSE 0 END) as late_fees,
                    SUM(CASE WHEN account_item = '520' THEN sub_total ELSE 0 END) as commissions,
                    SUM(CASE WHEN account_item = '525' THEN sub_total ELSE 0 END) as other_income
                    FROM invoice_items WHERE 1=1";

    if ($buildingId && $buildingId !== 'all') {
        $incomeQuery .= " AND building_id = :building_id";
    }

    if ($startDate) {
        $incomeQuery .= " AND DATE(created_at) >= :start_date";
    }

    if ($endDate) {
        $incomeQuery .= " AND DATE(created_at) <= :end_date";
    }

    $stmt = $pdo->prepare($incomeQuery);

    if ($buildingId && $buildingId !== 'all') {
        $stmt->bindParam(':building_id', $buildingId);
    }

    if ($startDate) {
        $stmt->bindParam(':start_date', $startDate);
    }

    if ($endDate) {
        $stmt->bindParam(':end_date', $endDate);
    }

    $stmt->execute();
    $incomeData = $stmt->fetch(PDO::FETCH_ASSOC);

    $result['income']['rent'] = (float)($incomeData['rent'] ?? 0);
    $result['income']['water'] = (float)($incomeData['water'] ?? 0);
    $result['income']['garbage'] = (float)($incomeData['garbage'] ?? 0);
    $result['income']['late_fees'] = (float)($incomeData['late_fees'] ?? 0);
    $result['income']['commissions'] = (float)($incomeData['commissions'] ?? 0);
    $result['income']['other_income'] = (float)($incomeData['other_income'] ?? 0);
    $result['income']['total'] = array_sum($result['income']) - $result['income']['total'];

    // EXPENSES CALCULATION (using created_at)
    $expenseQuery = "SELECT
                    SUM(CASE WHEN item_account_code = '600' THEN item_untaxed_amount ELSE 0 END) as maintenance,
                    SUM(CASE WHEN item_account_code = '605' THEN item_untaxed_amount ELSE 0 END) as salaries,
                    SUM(CASE WHEN item_account_code = '610' THEN item_untaxed_amount ELSE 0 END) as electricity,
                    SUM(CASE WHEN item_account_code = '615' THEN item_untaxed_amount ELSE 0 END) as water,
                    SUM(CASE WHEN item_account_code = '620' THEN item_untaxed_amount ELSE 0 END) as garbage,
                    SUM(CASE WHEN item_account_code = '625' THEN item_untaxed_amount ELSE 0 END) as internet,
                    SUM(CASE WHEN item_account_code = '630' THEN item_untaxed_amount ELSE 0 END) as security,
                    SUM(CASE WHEN item_account_code = '635' THEN item_untaxed_amount ELSE 0 END) as software,
                    SUM(CASE WHEN item_account_code = '640' THEN item_untaxed_amount ELSE 0 END) as marketing,
                    SUM(CASE WHEN item_account_code = '645' THEN item_untaxed_amount ELSE 0 END) as legal,
                    SUM(CASE WHEN item_account_code = '655' THEN item_untaxed_amount ELSE 0 END) as loan_interest,
                    SUM(CASE WHEN item_account_code = '660' THEN item_untaxed_amount ELSE 0 END) as bank_charges,
                    SUM(CASE WHEN item_account_code = '665' THEN item_untaxed_amount ELSE 0 END) as other_expenses
                    FROM expense_items WHERE 1=1";

    if ($buildingId && $buildingId !== 'all') {
        $expenseQuery .= " AND building_id = :building_id";
    }

    if ($startDate) {
        $expenseQuery .= " AND DATE(created_at) >= :start_date";
    }

    if ($endDate) {
        $expenseQuery .= " AND DATE(created_at) <= :end_date";
    }

    $stmt = $pdo->prepare($expenseQuery);

    if ($buildingId && $buildingId !== 'all') {
        $stmt->bindParam(':building_id', $buildingId);
    }

    if ($startDate) {
        $stmt->bindParam(':start_date', $startDate);
    }

    if ($endDate) {
        $stmt->bindParam(':end_date', $endDate);
    }

    $stmt->execute();
    $expenseData = $stmt->fetch(PDO::FETCH_ASSOC);

    $result['expenses']['maintenance'] = (float)($expenseData['maintenance'] ?? 0);
    $result['expenses']['salaries'] = (float)($expenseData['salaries'] ?? 0);
    $result['expenses']['electricity'] = (float)($expenseData['electricity'] ?? 0);
    $result['expenses']['water'] = (float)($expenseData['water'] ?? 0);
    $result['expenses']['garbage'] = (float)($expenseData['garbage'] ?? 0);
    $result['expenses']['internet'] = (float)($expenseData['internet'] ?? 0);
    $result['expenses']['security'] = (float)($expenseData['security'] ?? 0);
    $result['expenses']['software'] = (float)($expenseData['software'] ?? 0);
    $result['expenses']['marketing'] = (float)($expenseData['marketing'] ?? 0);
    $result['expenses']['legal'] = (float)($expenseData['legal'] ?? 0);
    $result['expenses']['loan_interest'] = (float)($expenseData['loan_interest'] ?? 0);
    $result['expenses']['bank_charges'] = (float)($expenseData['bank_charges'] ?? 0);
    $result['expenses']['other_expenses'] = (float)($expenseData['other_expenses'] ?? 0);
    $result['expenses']['total'] = array_sum($result['expenses']) - $result['expenses']['total'];

    // Calculate net profit
    $result['net_profit'] = $result['income']['total'] - $result['expenses']['total'];

    echo json_encode([
        'success' => true,
        'data' => $result,
        'date_range' => [
            'start' => $startDate,
            'end' => $endDate
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}