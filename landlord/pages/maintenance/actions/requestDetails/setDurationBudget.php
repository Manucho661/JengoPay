<?php
header('Content-Type: application/json');
session_start();

require_once '../../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errfile, $errline);
});

try {

    // ✅ Check if request_id exists from the form
    if (!isset($_REQUEST['request_id'])) {
        throw new Exception("No request ID provided.");
    }

    $requestId = $_REQUEST['request_id'];

    if (isset($_REQUEST['budget']) && isset($_REQUEST['durationOption'])) {

        $budget = $_REQUEST['budget'];
        $duration = $_REQUEST['durationOption'];

        // ✅ Update using request_id (not session id)
        $stmt = $pdo->prepare("
            UPDATE maintenance_requests
            SET budget = :budget, duration = :duration
            WHERE id = :request_id
        ");

        $stmt->execute([
            ':budget' => $budget,
            ':duration' => $duration,
            ':request_id' => $requestId
        ]);

        echo json_encode([
            "status" => "success",
            "message" => "Maintenance request updated successfully."
        ]);

    } else {
        throw new Exception("Missing budget or duration.");
    }

} catch (Throwable $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
