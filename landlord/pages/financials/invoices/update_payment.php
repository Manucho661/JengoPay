<?php
include '../../db/connect.php';
header('Content-Type: application/json');

// Decode raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

$paymentId = $data['payment_id'] ?? null;
$amount    = $data['amount'] ?? null;

if ($paymentId && $amount) {
    try {
        $stmt = $pdo->prepare("UPDATE payments SET amount = ? WHERE id = ?");
        $stmt->execute([$amount, $paymentId]);

        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
