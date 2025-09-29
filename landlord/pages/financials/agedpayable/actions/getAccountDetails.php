<?php 
header('Content-Type: application/json');

require_once '../../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$supplier = $_GET['supplier'] ?? '';

if (empty($supplier)) {
    echo json_encode([
        'error' => true,
        'message' => 'Supplier parameter is required'
    ]);
    exit;
}

try {
    $sql = "
        SELECT 
            id, 
            expense_no, 
            created_at, 
            total,
            CASE
                WHEN DATEDIFF(CURDATE(), created_at) BETWEEN 0 AND 30 THEN '0-30 Days'
                WHEN DATEDIFF(CURDATE(), created_at) BETWEEN 31 AND 60 THEN '31-60 Days'
                WHEN DATEDIFF(CURDATE(), created_at) BETWEEN 61 AND 90 THEN '61-90 Days'
                ELSE '90+ Days'
            END AS age_bucket
        FROM expenses
        WHERE supplier = :supplier
        ORDER BY created_at DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['supplier' => $supplier]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'error' => false,
        'data' => $result
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
