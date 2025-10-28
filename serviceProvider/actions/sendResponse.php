<?php
header('Content-Type: application/json'); // Ensure the content type is JSON

require_once '../../db/connect.php'; // include your PDO connection

// Set PDO to throw exceptions on error
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    // Log errors to a file or console if needed, for debugging
    error_log("Error: [$errno] $errstr on line $errline in file $errfile");
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    
    

    // Return a success response
    echo json_encode([
        "success" => true,
        "data" => $response
    ]);
} catch (PDOException $e) {
    // Handle PDO exceptions (e.g., invalid queries, connection issues)
    echo json_encode([
        "success" => false,
        "error" => "PDO Error: " . $e->getMessage()
    ]);
} catch (Throwable $e) {
    // Handle other errors that are not related to PDO
    echo json_encode([
        "success" => false,
        "error" => "Error: " . $e->getMessage()
    ]);
}
