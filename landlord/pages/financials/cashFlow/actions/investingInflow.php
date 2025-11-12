<?php
require_once '../../db/connect.php'; // include your PDO connection

// Convert all PHP warnings/notices into exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Fetch investing activities with inflows
    $sql = "
        SELECT 
            id,
            description,
            cash_inflows,
            cash_outflows,
            created_at,
            updated_at
        FROM 
            investing_activities
        WHERE 
            cash_inflows IS NOT NULL AND cash_inflows > 0
        ORDER BY 
            created_at DESC;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $investingInflows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total inflows
    $totalInvestingInflows = 0;
    foreach ($investingInflows as $row) {
        $totalInflows += $row['cash_inflows'];
    }

    // echo json_encode([
    //     'success' => true,
    //     'investingInflows' => $investingInflows,
    //     'totalInflows' => $totalInflows,
    // ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ]);
}
