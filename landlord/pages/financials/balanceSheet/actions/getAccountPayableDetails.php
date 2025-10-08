<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // SQL query as a string
    $sql = "
        SELECT 
            e.supplier_id,
            s.supplier_name AS supplier_name,
            SUM(e.total) AS total_amount
        FROM expenses e
        JOIN suppliers s ON e.supplier_id = s.id
        WHERE e.status IN ('partially paid', 'unpaid')
        GROUP BY e.supplier_id, s.supplier_name
    ";

    // Option 1: Use query() since no parameters are being bound
    $stmt = $pdo->query($sql);
    $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'error' => false,
        'data' => $details
    ]);


} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
