<?php

require_once '../db/connect.php';

/**
 * Convert PHP warnings/notices into exceptions
 */
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [$errno]: $errstr in $errfile on line $errline");
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {

    /* ===============================
       1. Authenticated USER ID
    =============================== */
    if (!isset($_SESSION['user']['id'])) {
        throw new Exception('User not authenticated');
    }

    $userId = $_SESSION['user']['id'];

    /* ===============================
       2. Resolve SERVICE PROVIDER ID
    =============================== */
    $stmt = $pdo->prepare("
        SELECT id
        FROM service_providers
        WHERE user_id = ?
        LIMIT 1
    ");
    $stmt->execute([$userId]);

    $provider = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$provider) {
        throw new Exception('Service provider account not found for this user');
    }

    $serviceProviderId = $provider['id'];

    /* ===============================
       3. Fetch available requests
    =============================== */
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
            u.unit_number
        FROM maintenance_requests mr
        LEFT JOIN buildings b ON mr.building_id = b.id
        LEFT JOIN building_units u ON mr.building_unit_id = u.id
        WHERE mr.availability = 'available'
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($requests)) {
        $requests = [];
        return;
    }

    /* ===============================
       4. Collect request IDs
    =============================== */
    $requestIds = array_column($requests, 'id');
    $placeholders = implode(',', array_fill(0, count($requestIds), '?'));

    /* ===============================
       5. Fetch photos
    =============================== */
    $stmt = $pdo->prepare("
        SELECT maintenance_request_id, photo_path
        FROM maintenance_request_photos
        WHERE maintenance_request_id IN ($placeholders)
    ");
    $stmt->execute($requestIds);

    $photosByRequest = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $photo) {
        $photosByRequest[$photo['maintenance_request_id']][] = $photo['photo_path'];
    }

    /* ===============================
       6. Fetch THIS provider’s proposals
    =============================== */
    $params = array_merge([$serviceProviderId], $requestIds);

    $stmt = $pdo->prepare("
        SELECT 
            maintenance_request_id,
            proposed_budget,
            status
        FROM maintenance_request_proposals
        WHERE service_provider_id = ?
          AND maintenance_request_id IN ($placeholders)
    ");
    $stmt->execute($params);

    $providerProposals = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $proposal) {
        $providerProposals[$proposal['maintenance_request_id']] = $proposal;
    }

    /* ===============================
       7. Attach everything to requests
    =============================== */
    foreach ($requests as &$request) {
        $id = $request['id'];

        $request['photos'] = $photosByRequest[$id] ?? [];

        if (isset($providerProposals[$id])) {
            $request['has_applied'] = true;
            $request['my_proposal'] = $providerProposals[$id];
        } else {
            $request['has_applied'] = false;
            $request['my_proposal'] = null;
        }
    }
    unset($request);

    // ✅ $requests is now fully prepared for the view

} catch (Throwable $e) {

    error_log(
        "Exception: {$e->getMessage()} |
         File: {$e->getFile()} |
         Line: {$e->getLine()}"
    );

    header("Location: /Jengopay/errorMessages/errorMessage1.php");
    exit;
}
