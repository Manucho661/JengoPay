<?php 
require_once '../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['assignProvider'])) {
    return;
}

try {
    $requestId  = $_POST['request_id'] ?? null;
    $providerId = $_POST['provider_id'] ?? null;
    $proposalId = $_POST['proposal_id'] ?? null;

    if (!$requestId || !$providerId || !$proposalId) {
        echo "Missing request_id, provider_id, or proposal_id";
        exit;
    }

    $pdo->beginTransaction();

    // 1️⃣ Insert assignment
    $stmt = $pdo->prepare("
        INSERT INTO maintenance_request_assignments 
        (maintenance_request_id, service_provider_id)
        VALUES (:maintenance_request_id, :service_provider_id)
    ");
    $stmt->execute([
        ':maintenance_request_id' => (int)$requestId,
        ':service_provider_id'    => (int)$providerId,
    ]);

    // 2️⃣ Update maintenance request (ADDED availability)
    $stmt2 = $pdo->prepare("
        UPDATE maintenance_requests
        SET assigned_to_provider_id = :provider_id,
            status = 'Assigned',
            availability = 'Unavailable',
            assigned_at = NOW()
        WHERE id = :request_id
    ");
    $stmt2->execute([
        ':provider_id' => (int)$providerId,
        ':request_id'  => (int)$requestId,
    ]);

    // 3️⃣ Accept proposal
    $stmt3 = $pdo->prepare("
        UPDATE maintenance_request_proposals
        SET status = 'Accepted'
        WHERE id = :proposal_id
    ");
    $stmt3->execute([
        ':proposal_id' => (int)$proposalId,
    ]);

    $pdo->commit();

    $_SESSION['success'] = "Maintenance request assigned and proposal accepted successfully.";
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;

} catch (Throwable $e) {
    $pdo->rollBack();

    $_SESSION['error'] = 'Failed to assign the provider: ' . $e->getMessage();
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
