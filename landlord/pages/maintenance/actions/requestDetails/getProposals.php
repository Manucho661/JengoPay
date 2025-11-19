<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing ID"]);
    exit;
}

// $id = (int) $id;

try {
    // For now, just return the id
    $stmt = $pdo->prepare("SELECT provider_id, cover_letter, bid_amount FROM maintenance_request_proposals WHERE id = ?");
    $stmt->execute([$id]);
    $proposals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["proposals" => $proposals]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
