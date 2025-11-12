<?php
require_once '../../db/connect.php'; // include your PDO connection

// Convert all PHP warnings/notices into exceptions
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Fetch investing activities with outflows
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
            cash_outflows IS NOT NULL AND cash_outflows > 0
        ORDER BY 
            created_at DESC;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $investingOutflows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total outflows
    $totalInvestingOutflows = 0;
    foreach ($investingOutflows as $row) {
        $totalOutflows += $row['cash_outflows'];
    }

    // echo json_encode([
    //     'success' => true,
    //     'investingOutflows' => $investingOutflows,
    //     'totalOutflows' => $totalOutflows,
    // ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ]);
}

?>