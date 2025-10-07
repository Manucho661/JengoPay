<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    $sql = "
    SELECT *
    FROM (
        SELECT
            COALESCE(supplier, 'TOTAL') AS supplier,
            SUM(CASE WHEN DATEDIFF(CURDATE(), created_at) BETWEEN 0 AND 30 THEN total ELSE 0 END) AS `0-30 Days`,
            SUM(CASE WHEN DATEDIFF(CURDATE(), created_at) BETWEEN 31 AND 60 THEN total ELSE 0 END) AS `31-60 Days`,
            SUM(CASE WHEN DATEDIFF(CURDATE(), created_at) BETWEEN 61 AND 90 THEN total ELSE 0 END) AS `61-90 Days`,
            SUM(CASE WHEN DATEDIFF(CURDATE(), created_at) > 90 THEN total ELSE 0 END) AS `90+ Days`,
            SUM(total) AS `Total Payable`
        FROM
            expenses
            WHERE status IN ('unpaid','partially_paid')
        GROUP BY
            supplier WITH ROLLUP
    ) AS t
    ORDER BY 
        CASE WHEN supplier = 'TOTAL' THEN 2 ELSE 1 END, 
        supplier;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'data' => $result
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
