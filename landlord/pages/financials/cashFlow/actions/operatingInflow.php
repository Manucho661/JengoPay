<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    $sql = "
        SELECT 
                a.account_name, 
                SUM(jl.credit) AS total_credit
            FROM 
                journal_lines jl
            INNER JOIN 
                chart_of_accounts a ON jl.account_id = a.account_code
            WHERE 
                jl.source_table = 'invoice'
            GROUP BY 
                a.account_name;
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $operatingInflows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'operatingInflows' => $operatingInflows,
    ]);
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage();
}
