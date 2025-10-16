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
    // Correct query with quotes around 'available'
     $stmt = $pdo->prepare("
        SELECT 
            mr.id,
            mr.request_date,
            mr.request,
            mr.residence,
            mr.unit,
            mr.category,
            mr.description,
            mr.provider_id,
            mr.budget,
            mr.duration,
            mp.photo_url
        FROM maintenance_requests AS mr
        LEFT JOIN maintenance_photos AS mp
            ON mr.id = mp.maintenance_request_id
        WHERE mr.availability = 'available'
    ");
    $stmt->execute();

    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return a success response
    echo json_encode([
        "success" => true,
        "data" => $requests
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
