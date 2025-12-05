<?php
header('Content-Type: application/json'); // Always return JSON

require_once '../../db/connect.php'; // Your PDO connection file

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
    $provider_response = $_POST['provider_response'] ?? null;
    $assignment_id = $_POST['assignment_id'] ?? null;

    if (!$provider_response || !$assignment_id) {
        throw new Exception('Missing required fields.');
    }

    // Prepare the update query
    $updateQuery = "UPDATE request_assignments SET provider_response = :provider_response";

    // If the response is 'Accepted', update the status as well
    if ($provider_response === 'Accepted') {
        $updateQuery .= ", status = 'In progress'";
    }

    $updateQuery .= " WHERE id = :id";

    // Prepare and execute the query
    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute([
        ':provider_response' => $provider_response,
        ':id' => $assignment_id
    ]);


    // Return success JSON
    echo json_encode([
        "success" => true,
        "message" => "Response updated successfully.",
        "data" => [
            "assignment_id" => $assignment_id,
            "provider_response" => $provider_response
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
