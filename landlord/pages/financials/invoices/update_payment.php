<?php
include "../../db/connect.php";
header("Content-Type: application/json");

if (!isset($_POST['id'], $_POST['amount'])) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

$id = (int) $_POST['id'];
$amount = (float) $_POST['amount'];

$stmt = $pdo->prepare("UPDATE payments SET amount = ? WHERE id = ?");
if ($stmt->execute([$amount, $id])) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}
