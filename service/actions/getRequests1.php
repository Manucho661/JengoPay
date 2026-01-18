<?php

require_once '../db/connect.php';

/**
 * Convert PHP warnings/notices into exceptions
 * so they can be handled in try/catch.
 */
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [$errno]: $errstr in $errfile on line $errline");
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {

    $sql = "
        SELECT 
            mr.id,
            mr.title,
            mr.category,
            mr.description,
            mr.budget,
            mr.duration,
            mr.created_at,

            b.building_name,
            u.unit_number,

            GROUP_CONCAT(mp.photo_path) AS photo_paths,

            mrp.service_provider_id,
            mrp.proposed_budget

        FROM maintenance_requests AS mr

        LEFT JOIN buildings AS b
            ON mr.building_id = b.id

        LEFT JOIN building_units AS u
            ON mr.building_unit_id = u.id

        LEFT JOIN maintenance_request_photos AS mp
            ON mr.id = mp.maintenance_request_id

        LEFT JOIN maintenance_request_proposals AS mrp
            ON mr.id = mrp.maintenance_request_id

        WHERE mr.availability = 'available'

        GROUP BY mr.id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $requests is now ready for rendering in the view

} catch (Throwable $e) {

    // Log detailed error for developers
    error_log(
        "Exception: {$e->getMessage()} | 
         File: {$e->getFile()} | 
         Line: {$e->getLine()}"
    );

    // Display detailed error (developer view, uncomment if an error occurs)
    // echo "<h3>Developer Debug Info</h3>";
    // echo "<p><strong>Message:</strong> {$e->getMessage()}</p>";
    // echo "<p><strong>File:</strong> {$e->getFile()}</p>";
    // echo "<p><strong>Line:</strong> {$e->getLine()}</p>";

    // Display safe message to users
    // Redirect user to your error page
    $redirectUrl = '/Jengopay/errorMessages/errorMessage1.php';
    header("Location: $redirectUrl");
    exit;
}
