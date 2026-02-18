<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$supplier_id = (int)($_GET['supplier_id'] ?? 0);

if ($supplier_id <= 0) {
    echo json_encode([
        'error' => true,
        'message' => 'supplier_id parameter is required'
    ]);
    exit;
}

try {
    $sql = "
        SELECT 
            e.id,
            e.expense_no,
            e.created_at,
            e.total,
            s.supplier_name,
            CASE
                WHEN DATEDIFF(CURDATE(), e.created_at) BETWEEN 0 AND 30 THEN '0-30 Days'
                WHEN DATEDIFF(CURDATE(), e.created_at) BETWEEN 31 AND 60 THEN '31-60 Days'
                WHEN DATEDIFF(CURDATE(), e.created_at) BETWEEN 61 AND 90 THEN '61-90 Days'
                ELSE '90+ Days'
            END AS age_bucket
        FROM expenses e
        INNER JOIN suppliers s
            ON s.id = e.supplier_id
        WHERE e.supplier_id = :supplier_id
        ORDER BY e.created_at DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['supplier_id' => $supplier_id]);
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
