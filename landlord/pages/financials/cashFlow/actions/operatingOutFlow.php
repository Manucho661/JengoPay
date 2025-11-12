<?php

require_once '../../db/connect.php'; // include your PDO connection

// Convert all PHP warnings/notices into exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    $sql = "
        SELECT 
            c.account_name AS item_type,
            SUM(ei.item_total) AS total_amount
        FROM 
            expenses e
        JOIN 
            expense_items ei ON e.id = ei.expense_id
        JOIN 
            chart_of_accounts c ON ei.item_account_code = c.account_code
        WHERE 
            e.status = 'paid'
        GROUP BY 
            c.account_name
        ORDER BY 
            total_amount DESC;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $operatingOutflows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate cumulative total in PHP -
    $cumulativeOutflow = 0;
    foreach ($operatingOutflows as &$row) {
        $cumulativeOutflow += $row['total_amount'];
        $row['cumulative_total'] = $cumulativeOutflow;
    }

    // echo json_encode([
    //     'success' => true,
    //     'operatingOutflows' => $operatingOutflows,
    // ]);
} catch (Throwable $e) {
    // Return proper JSON error message
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
