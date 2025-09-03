<?php
include "../../db/connect.php";  // adjust path

header("Content-Type: application/json");

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["payment_id"], $data["amount"])) {
        echo json_encode(["success" => false, "message" => "Missing fields"]);
        exit;
    }

    $paymentId = (int)$data["payment_id"];
    $amount = (float)$data["amount"];

    $stmt = $pdo->prepare("UPDATE payments SET amount = ? WHERE id = ?");
    $stmt->execute([$amount, $paymentId]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
