<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Get request_id and provider_id from query params
    $requestId  = $_GET['request_id'] ?? null;
    $providerId = $_GET['provider_id'] ?? null;

    if (!$requestId || !$providerId) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing request_id or provider_id"
        ]);
        exit;
    }

    // Prepare update statement
    $stmt = $pdo->prepare("
    INSERT INTO request_assignments (request_id, provider_id)
    VALUES (:request_id, :provider_id)
    ");


    $stmt->bindParam(':provider_id', $providerId, PDO::PARAM_INT);
    $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Provider assigned successfully",
            "request_id" => $requestId,
            "provider_id" => $providerId
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to assign provider"
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
