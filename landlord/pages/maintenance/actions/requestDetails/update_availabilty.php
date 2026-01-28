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

    // Check if the maintenance request exists before updating
    $stmt = $pdo->prepare("SELECT * FROM maintenance_requests WHERE id = :id");
    $stmt->execute([":id" => $requestId]);
    $existingRequest = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existingRequest) {
        echo json_encode([
            "status" => "error",
            "id" => $requestId,
            "message" => "Request ID does not exist"
        ]);
        exit;
    }

    // Check if the status is already the same
    if ($existingRequest['availability'] === $status) {
        echo json_encode([
            "status" => "error",
            "message" => "No update made (status already set to '$status')"
        ]);
        exit;
    }

    // Update availability status
    $sql = "UPDATE maintenance_requests SET availability = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":status" => $status,
        ":id" => $requestId
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Availability updated successfully",
            "new_status" => $status
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No update made (possibly due to database issues)"
        ]);
    }

} catch (Throwable $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
