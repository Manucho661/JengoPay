<?php
require_once '../../db/connect.php'; // include your PDO connection

// Convert all PHP warnings/notices into exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Fetch all financing activities where inflows exist
    $sql = "
        SELECT 
            id,
            description,
            cash_inflows,
            cash_outflows,
            created_at,
            updated_at
        FROM 
            financing_activities
        WHERE 
            cash_inflows IS NOT NULL AND cash_outflows > 0
        ORDER BY 
            created_at DESC;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $financingOutflows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate the total inflows
    $totalFinancingOutflows = 0;
    foreach ($financingOutflows as $row) {
        $totalOutflows += $row['cash_inflows'];
    }

    // echo json_encode([
    //     'success' => true,
    //     'financingInflows' => $financingOutflows,
    //     'totalInflows' => $totaOutflows,
    // ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ]);
}
?>