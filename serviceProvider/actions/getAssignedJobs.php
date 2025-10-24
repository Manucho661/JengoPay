<?php
header('Content-Type: application/json');

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

require_once '../../db/connect.php'; // include your PDO connection

try {
    // Correct query with quotes around 'available'
    $stmt = $pdo->prepare("
    SELECT 
        ra.id AS assignment_id,
        mr.id AS request_id,
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
    FROM request_assignments AS ra
    LEFT JOIN maintenance_requests AS mr
        ON ra.request_id = mr.id
    LEFT JOIN maintenance_photos AS mp
        ON mr.id = mp.maintenance_request_id
    WHERE ra.provider_id = :provider_id
");

$stmt->execute(['provider_id' => 2]);


    $Applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return a success response
    echo json_encode([
        "success" => true,
        "data" => $Applications
    ]);
} catch (Throwable $e) {
    // Handle other errors that are not related to PDO
    echo json_encode([
        "success" => false,
        "error" => "Error: " . $e->getMessage()
    ]);
}
