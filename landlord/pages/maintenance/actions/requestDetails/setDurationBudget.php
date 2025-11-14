<?php
header('Content-Type: application/json');
session_start(); // Start session to access $_SESSION['id']

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {

    // 1️⃣ Check if the session ID exists
    if (!isset($_SESSION['id'])) {
        throw new Exception("No session ID found.");
    }

    $sessionId = $_SESSION['id'];

    if (isset($_REQUEST['budget']) && isset($_REQUEST['durationOption'])) {

        $budget = $_REQUEST['budget'];
        $duration = $_REQUEST['durationOption'];

        // 3️⃣ Update database using the session ID
        $stmt = $pdo->prepare("
            UPDATE maintenance_requests
            SET budget = :budget, duration = :duration
            WHERE id = :id
        ");

        $stmt->execute([
            ':budget' => $budget,
            ':duration' => $duration,
            ':id' => $sessionId
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
