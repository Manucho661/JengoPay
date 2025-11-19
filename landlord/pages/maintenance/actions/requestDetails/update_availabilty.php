<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    if (!isset($_GET['id']) || !isset($_GET['status'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing parameters"
        ]);
        exit;
    }

    $requestId = (int) $_GET['id'];
    $status = $_GET['status'] === "available" ? "available" : "unavailable"; // sanitize

    $sql = "UPDATE maintenance_requests SET availability = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":status" => $status,
        ":id" => $requestId
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Availability updated",
            "new_status" => $status
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No update made (maybe same status or invalid ID)"
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
