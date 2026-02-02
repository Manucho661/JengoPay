<?php
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$error = '';

try {
    // Original query to fetch all the application details
    $stmt = $pdo->prepare("
    SELECT 
        mrp.proposed_budget,
        mrp.proposed_duration,
        mrp.created_at,
        mr.id,
        mr.created_at AS request_created_at,
        mr.category,
        mr.description,
        mr.budget,
        mr.duration,
        mrp.status,
        mp.photo_path
    FROM maintenance_request_proposals AS mrp
    LEFT JOIN maintenance_requests AS mr
        ON mrp.maintenance_request_id = mr.id 
    LEFT JOIN maintenance_request_photos AS mp
        ON mr.id = mp.maintenance_request_id
    WHERE mr.availability = 'available'
    ");
    $stmt->execute();

    // Fetch all application details
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Now calculate the counts from the $applications array
    $totalApplications = count($applications);
    $pending = 0;
    $accepted = 0;
    $declined = 0;

    // Loop through the applications to calculate pending, accepted, and declined counts
    foreach ($applications as $application) {
        if ($application['status'] === 'pending') {
            $pending++;
        } elseif ($application['status'] === 'accepted') {
            $accepted++;
        } elseif ($application['status'] === 'declined') {
            $declined++;
        }
    }

    // Now you can use $totalApplications, $pending, $accepted, and $declined
    // For example, you can return these counts or use them in your stats container

} catch (Throwable $e) {
    // Handle errors that are not related to PDO
    $error = $e->getMessage();
    // For debugging: echo $error;
}
