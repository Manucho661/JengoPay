<?php
require_once '../../db/connect.php'; // include your PDO connection

// Convert all PHP warnings/notices into exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// tenant deposits
// Prepare and execute query
// $stmt = $pdo->query("SELECT SUM(amount_paid) AS total_paid FROM tenants_deposits");
// $result = $stmt->fetch();

// echo "Total Amount Paid: " . $result['total_paid'];

try {
    $sql = "
        SELECT 
    c.account_name AS item_type,
    SUM(ii.total) AS total_amount
    FROM 
        invoice i
    JOIN 
        invoice_items ii ON i.invoice_number = ii.invoice_number
    JOIN 
        chart_of_accounts c ON ii.account_item = c.account_code
    WHERE 
        i.payment_status = 'paid'
    GROUP BY 
        c.account_name
    ORDER BY 
        total_amount DESC;

    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $operatingInflows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $cumulativeOperatingInflow = 0;
    foreach ($operatingInflows as &$row) {
        $cumulativeOperatingInflow += $row['total_amount'];
        $row['cumulative_total'] = $cumulativeOperatingInflow;
    }
    // tenant deposts
    $stmt1 = $pdo->prepare("SELECT SUM(amount_paid) AS total_amount FROM tenant_deposits");
    $stmt1->execute();

    $depostsResult = $stmt1->fetch(PDO::FETCH_ASSOC);
    $totalTenantDeposits = $depostsResult['total_amount'] ?? 0;

    // echo json_encode([
    //     'success' => true,
    //     'operatingInflows' => $operatingInflows,
    // ]);
} catch (Throwable $e) {
    // Return proper JSON error message
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
