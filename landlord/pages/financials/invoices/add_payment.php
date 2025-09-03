<?php
include '../../db/connect.php';
header('Content-Type: application/json');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);

    $invoiceId = $data['invoice_id'] ?? null;
    $amount    = $data['amount'] ?? null;
    $method    = $data['method'] ?? null;

    if (!$invoiceId || !$amount || !$method) {
        echo json_encode(["success" => false, "message" => "Missing fields"]);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO payments (invoice_id, amount, payment_method, payment_date, status)
        VALUES (:invoice_id, :amount, :method, NOW(), 'completed')
    ");
    $stmt->execute([
        ':invoice_id' => $invoiceId,
        ':amount'     => $amount,
        ':method'     => $method,
    ]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
