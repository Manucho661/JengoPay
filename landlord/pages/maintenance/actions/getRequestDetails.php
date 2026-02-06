<?php
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
    // Step 1: Get the main request along with provider name (no building/unit joins)
    $stmt = $pdo->prepare("
        SELECT 
            mr.*,
            pr.name AS provider_name,
            pr.id AS provider_id,
            ra.id AS assignment_id,
            ra.provider_response
        FROM maintenance_requests AS mr
        LEFT JOIN maintenance_request_assignments AS ra 
            ON ra.maintenance_request_id = mr.id 
            AND ra.terminated IS NULL
        LEFT JOIN service_providers AS pr 
            ON ra.service_provider_id = pr.id
        WHERE mr.id = :id
        LIMIT 1
    ");
    $stmt->execute(['id' => $requestId]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        http_response_code(404);
        echo json_encode(['error' => 'Request not found']);
        exit;
    }

    // Step 1b: Fetch building_name separately
    $buildingName = null;
    if (!empty($request['building_id'])) {
        $stmt = $pdo->prepare("SELECT building_name FROM buildings WHERE id = :bid LIMIT 1");
        $stmt->execute(['bid' => $request['building_id']]);
        $buildingName = $stmt->fetchColumn() ?: null;
    }

    // Step 1c: Fetch unit_number separately
    $unitNumber = null;
    if (!empty($request['building_unit_id'])) {
        $stmt = $pdo->prepare("SELECT unit_number FROM building_units WHERE id = :uid LIMIT 1");
        $stmt->execute(['uid' => $request['building_unit_id']]);
        $unitNumber = $stmt->fetchColumn() ?: null;
    }

    // Attach to request details
    $request['building_name'] = $buildingName;
    $request['unit_number'] = $unitNumber;

    // Step 2: Get photos
    $stmt = $pdo->prepare("SELECT * FROM maintenance_request_photos WHERE maintenance_request_id = :id");
    $stmt->execute(['id' => $requestId]);
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Step 2: Get photos || provider details
    $proposals = [];
    $assignedProvider = null;

    // Check if already assigned
    if (!empty($request['provider_id']) && $request['provider_id'] != 0) {

        // Fetch assigned provider details
        $stmt = $pdo->prepare("
        SELECT id, name, phone, email
        FROM service_providers
        WHERE id = :pid
        LIMIT 1
    ");
        $stmt->execute(['pid' => $request['provider_id']]);
        $assignedProvider = $stmt->fetch(PDO::FETCH_ASSOC);

    } else {

        // Fetch proposals only if NOT assigned
        $stmt = $pdo->prepare("
        SELECT 
            sp.name AS service_provider_name,
            sp.id AS service_provider_id,
            mrp.proposed_budget,
            mrp.proposed_duration,
            mrp.provider_availability
        FROM maintenance_request_proposals mrp
        JOIN service_providers sp 
            ON mrp.service_provider_id = sp.id
        WHERE mrp.maintenance_request_id = :id
    ");
        $stmt->execute(['id' => $requestId]);
        $proposals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Step 4: Get payments
    $stmt = $pdo->prepare("SELECT * FROM maintenance_request_payments WHERE maintenance_request_id = :id");
    $stmt->execute(['id' => $requestId]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 5: Response
    $response = [
        'request'   => $request,
        'photos'    => $photos,
        'proposals' => $proposals,
        'payments'  => $payments
    ];
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
