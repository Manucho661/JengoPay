<?php
include '../../../db/connect.php';

header('Content-Type: application/json');

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    if (!isset($_GET['field']) || !isset($_GET['value'])) {
        echo json_encode(["exists" => false, "error" => "Missing parameters"]);
        exit;
    }

    $field = $_GET['field'];
    $value = $_GET['value'];

    // ✅ allow only specific fields to prevent SQL injection
    $allowedFields = ["supplierName", "supplierKra"];
    if (!in_array($field, $allowedFields)) {
        echo json_encode(["exists" => false, "error" => "Invalid field"]);
        exit;
    }

    // ✅ map UI field to DB field
    $dataBaseField = ($field === "supplierName") ? "supplier_name" : "kra_pin";
    $frontMessage = ($field === "supplierName") ? "Supplier name already exists" : "Kra number already exists";

    // ✅ build query dynamically but bind value securely
    $sql = "SELECT $dataBaseField FROM suppliers WHERE $dataBaseField = :value LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":value" => $value]);

    $exists = $stmt->fetchColumn() ? true : false;

    echo json_encode([
        "exists" => $exists,
        "error" => null,
        "Message" => $frontMessage,
    ]);
} catch (Throwable $e) {
    // ✅ catch real DB errors, pass them to frontend
    echo json_encode(["exists" => false, "error" => $e->getMessage()]);
}
