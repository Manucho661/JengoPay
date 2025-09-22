<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$providerID = $_GET['provider_id'] ?? null;

if (!$providerID || !is_numeric($providerID)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing provider ID"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM providers WHERE id = ?");
    $stmt->execute([$providerID]);
    $details = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$details) {
        http_response_code(404);
        echo json_encode(['error' => 'Provider not found']);
        exit;
    }

    echo json_encode([
        "details" => $details
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
