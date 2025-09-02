<?php
// adjust the path to your PDO connection file
include '../../db/connect.php';

header('Content-Type: application/json');

try {
    // Make sure PDO throws exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional filters
    $invoiceId   = isset($_GET['invoice_id'])   ? (int)$_GET['invoice_id']   : null;
    $buildingId  = isset($_GET['building_id'])  ? (int)$_GET['building_id']  : null;
    $dateFrom    = isset($_GET['date_from'])    ? $_GET['date_from']         : null; // YYYY-MM-DD
    $dateTo      = isset($_GET['date_to'])      ? $_GET['date_to']           : null; // YYYY-MM-DD

    // Base query: ONLY completed payments
    $sql = "
        SELECT
            p.id                AS payment_id,
            p.invoice_id,
            i.invoice_number,
            i.tenant            AS tenant_id,
            p.tenant            AS tenant_name,
            i.building_id,
            i.invoice_date,
            i.due_date,
            i.total             AS invoice_total,
            p.amount,
            p.payment_method,
            p.payment_date,
            p.reference_number,
            p.status            AS status  -- will be 'completed'
        FROM payments p
        INNER JOIN invoice i ON i.id = p.invoice_id
        WHERE p.status = 'completed'
          -- if you want to ALSO enforce that the invoice is marked paid, keep this line:
          AND i.payment_status = 'paid'
    ";

    $params = [];

    if (!empty($invoiceId)) {
        $sql .= " AND p.invoice_id = :invoice_id";
        $params[':invoice_id'] = $invoiceId;
    }

    if (!empty($buildingId)) {
        $sql .= " AND i.building_id = :building_id";
        $params[':building_id'] = $buildingId;
    }

    if (!empty($dateFrom)) {
        $sql .= " AND p.payment_date >= :date_from";
        $params[':date_from'] = $dateFrom;
    }

    if (!empty($dateTo)) {
        $sql .= " AND p.payment_date <= :date_to";
        $params[':date_to'] = $dateTo;
    }

    $sql .= " ORDER BY p.payment_date DESC, p.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // total of the completed payments returned
    $totalPaid = 0.0;
    foreach ($rows as $r) {
        $totalPaid += (float)$r['amount'];
    }

    echo json_encode([
        'success'     => true,
        'filters'     => [
            'invoice_id'  => $invoiceId,
            'building_id' => $buildingId,
            'date_from'   => $dateFrom,
            'date_to'     => $dateTo
        ],
        'total_paid'  => $totalPaid,
        'count'       => count($rows),
        'data'        => $rows
    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => $e->getMessage()
    ]);
}
