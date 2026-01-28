<?php
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});


try {
    // Correct query with quotes around 'available'
    $stmt = $pdo->prepare("
    SELECT 
        mrp.bid_amount,
        mrp.estimated_time,
        mrp.created_at,
        mr.id,
        mr.request_date,
        mr.request,
        mr.residence,
        mr.unit,
        mr.category,
        mr.description,
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

   return $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Now, instead of echoing or returning JSON, return the $Applications array
    // return $applications; // This can be assigned to a variable in the calling script
} catch (Throwable $e) {
    // Handle errors that are not related to PDO
    return ["error" => "Error: " . $e->getMessage()];
}
?>
