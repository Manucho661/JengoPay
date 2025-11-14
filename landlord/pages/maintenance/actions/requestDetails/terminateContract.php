<?php
header('Content-Type: application/json'); // Always return JSON

require_once '../../../db/connect.php'; // Your PDO connection file

// Enable PDO exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Custom error handler (optional but helpful)
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    error_log("Error: [$errno] $errstr on line $errline in file $errfile");
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Make sure the request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method. POST required.');
    }

    // Get the response and assignment ID
    $assignment_id = $_POST['assignment_id'] ?? null;

    if (!$assignment_id) {
        throw new Exception('Missing required ID.');
    }

    // Prepare the update query
    $terminateValue =1;
    $status = 'Not Assigned';
    $stmt = $pdo->prepare("
        UPDATE request_assignments
        SET terminate = :value,
        status= :status
        WHERE id = :id
    ");

    $stmt->execute([
        ':value' => $terminateValue,
        ':status' => $status,
        ':id' => $assignment_id
    ]);

    // Return success JSON
    echo json_encode([
        "success" => true,
        "message" => "Contract terminated successfully.",
        "data" => [
            "assignment_id" => $assignment_id
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => "PDO Error: " . $e->getMessage()
    ]);
} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "error" => "Error: " . $e->getMessage()
    ]);
}
