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
        mrp.bid_amount,
        mrp.estimated_time,
        mrp.submitted_at,
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
    FROM maintenance_request_proposals AS mrp
    LEFT JOIN maintenance_requests AS mr
        ON mrp.maintenance_request_id = mr.id 
    LEFT JOIN maintenance_photos AS mp
        ON mr.id = mp.maintenance_request_id
    WHERE mr.availability = 'available'
    ");
    $stmt->execute();

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
