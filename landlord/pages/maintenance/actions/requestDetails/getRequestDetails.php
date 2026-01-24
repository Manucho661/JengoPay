<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$requestId = $_GET['id'] ?? null;

if (!$requestId || !is_numeric($requestId)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid or missing ID"]);
    exit;
}

try {
    // Step 1: Get the main request along with provider name
    $stmt = $pdo->prepare("
    SELECT 
        mr.*,
        pr.name AS provider_name,
        pr.id AS provider_id,
        ra.id AS assignment_id,
        ra.provider_response,
        ra.status
    FROM maintenance_requests AS mr
    LEFT JOIN maintenance_request_assignments AS ra 
        ON ra.maintenance_request_id = mr.id 
        AND ra.terminated IS NULL  -- condition moved to JOIN
    LEFT JOIN service_providers AS pr 
        ON ra.provider_id = pr.id
    WHERE mr.id = :id
");

    $stmt->execute(['id' => $requestId]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        http_response_code(404);
        echo json_encode(['error' => 'Request not found']);
        exit;
    }

    // Step 2: Get photos
    $stmt = $pdo->prepare("SELECT * FROM maintenance_request_photos WHERE maintenance_request_id = :id");
    $stmt->execute(['id' => $requestId]);
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 3: Get proposals with provider details using JOIN
    $stmt = $pdo->prepare("
        SELECT 
            p.*, 
            pr.name, 
            pr.phone,
            pr.ratings,
            pr.location
        FROM 
            maintenance_request_proposals p
        JOIN 
            service_providers pr ON p.service_provider_id = pr.id
        WHERE 
            p.maintenance_request_id = :id
    ");
    $stmt->execute(['id' => $requestId]);
    $proposals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 4: Get payments
    $stmt = $pdo->prepare("SELECT * FROM maintenance_request_payments WHERE maintenance_request_id = :id");
    $stmt->execute(['id' => $requestId]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 5: Combine into one structured response
    $response = [
        'request'   => $request,
        'photos'    => $photos,
        'proposals' => $proposals,
        'payments'  => $payments
    ];

    echo json_encode($response);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
