<?php
require_once '../db/connect.php';
header('Content-Type: application/json');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $invoiceId = (int) $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT
            id,
            invoice_number,
            invoice_date,
            due_date,
            payment_date,
            building_id,
            tenant,
            account_item,
            description,
            quantity,
            unit_price,
            taxes,
            sub_total,
            total,
            notes,
            terms_conditions,
            created_at,
            status,
            payment_status
        FROM invoice WHERE id = ?");
        $stmt->execute([$invoiceId]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($invoice) {
            echo json_encode($invoice);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Invoice not found']);
        }

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
    }

} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing invoice ID']);
}