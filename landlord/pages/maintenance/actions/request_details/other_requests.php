<?php
header('Content-Type: application/json');
require_once '../../../db/connect.php'; // include your PDO connection
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});
try {
    $stmt = $pdo->prepare("SELECT id, request_date, residence, unit, category, request, description, priority, availability, status, payment_status FROM maintenance_requests");
    $stmt->execute();
    $maintenance_requets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["data" => $maintenance_requets]);
} catch (Throwable $e) {
        echo json_encode(["error" => $e->getMessage()]);
}
