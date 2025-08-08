<?php
// fetch_invoice_data.php
require_once '../db/connect.php'; // Include your database connection file

header('Content-Type: application/json');

try {
    $startDate = $_POST['startDate'] . ' 00:00:00';
    $endDate = $_POST['endDate'] . ' 23:59:59';

    // Query to get invoice items within date range
    $stmt = $pdo->prepare("
        SELECT
            account_item,
            SUM(total) as amount
        FROM
            invoice_items
        WHERE
            created_at BETWEEN :startDate AND :endDate
        GROUP BY
            account_item
    ");

    $stmt->execute([
        ':startDate' => $startDate,
        ':endDate' => $endDate
    ]);

    $invoiceData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize all categories
    $data = [
        'rent' => 0,
        'water' => 0,
        'garbage' => 0,
        'maintenance' => 0,
        'salaries' => 0,
        'electricity' => 0,
        'waterExpense' => 0,
        'garbageExpense' => 0,
        'internet' => 0,
        'security' => 0,
        'software' => 0,
        'marketing' => 0,
        'legal' => 0,
        'loanInterest' => 0,
        'bankCharges' => 0,
        'otherExpenses' => 0
    ];

    // Map account items to categories
    foreach ($invoiceData as $item) {
        switch ($item['account_item']) {
            case '500': // Rent
                $data['rent'] += $item['amount'];
                break;
            case '510': // Water
                $data['water'] += $item['amount'];
                break;
            case '515': // Garbage
                $data['garbage'] += $item['amount'];
                break;
            // Add more cases for other account items as needed
        }
    }

    // Calculate totals
    $data['totalIncome'] = $data['rent'] + $data['water'] + $data['garbage'];
    $data['totalExpenses'] = $data['maintenance'] + $data['salaries'] + $data['electricity'] +
                            $data['waterExpense'] + $data['garbageExpense'] + $data['internet'] +
                            $data['security'] + $data['software'] + $data['marketing'] +
                            $data['legal'] + $data['loanInterest'] + $data['bankCharges'] +
                            $data['otherExpenses'];
    $data['netProfit'] = $data['totalIncome'] - $data['totalExpenses'];

    echo json_encode($data);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>