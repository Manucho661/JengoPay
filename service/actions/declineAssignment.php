<?php
require_once '../db/connect.php'; // $pdo is PDO

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decline_assignment'])) {

    $proposalId = (int) ($_POST['proposal_id'] ?? 0);

    if ($proposalId <= 0) {
        $error = 'Invalid proposal ID.';
    } elseif (empty($_SESSION['user']['id'])) {
        $error = 'You must be logged in to decline an assignment.';
    } else {
        try {
            $pdo->beginTransaction();

            $userId = (int) $_SESSION['user']['id'];

            // 1) Resolve provider id for this user (so random users can’t decline others’ assignments)
            $stmtProvider = $pdo->prepare("
                SELECT id
                FROM service_providers
                WHERE user_id = :user_id
                LIMIT 1
            ");
            $stmtProvider->execute([':user_id' => $userId]);
            $provider = $stmtProvider->fetch(PDO::FETCH_ASSOC);

            if (!$provider) {
                throw new Exception('No service provider account found for this user.');
            }

            $providerId = (int) $provider['id'];
            var_dump($proposalId);
            exit;

            // 2) Get maintenance_request_id from proposal row (and verify it belongs to this provider, if you store that)
            // NOTE: Change `service_provider_id` below if your proposals table uses a different column name (e.g. provider_id)
            $stmtGet = $pdo->prepare("
                SELECT id, maintenance_request_id
                FROM maintenance_request_proposals
                WHERE id = :proposal_id
                  AND service_provider_id = :provider_id
                LIMIT 1
            ");
            $stmtGet->execute([
                ':proposal_id' => $proposalId,
                ':provider_id' => $providerId
            ]);
            $proposal = $stmtGet->fetch(PDO::FETCH_ASSOC);

            if (!$proposal) {
                throw new Exception('Proposal not found (or not owned by you).');
            }

            $requestId = (int) $proposal['maintenance_request_id'];

            // 3) Update proposal status to "Declined Assignment"
            $stmt1 = $pdo->prepare("
                UPDATE maintenance_request_proposals
                SET status = 'Declined Assignment'
                WHERE id = :id
                LIMIT 1
            ");
            $stmt1->execute([':id' => $proposalId]);

            // 4) Null assigned_to_provider_id on the maintenance request
            // Optional safety: only null if currently assigned to THIS provider
            $stmt2 = $pdo->prepare("
                UPDATE maintenance_requests
                SET assigned_to_provider_id = NULL
                WHERE id = :request_id
                  AND assigned_to_provider_id = :provider_id
                LIMIT 1
            ");
            $stmt2->execute([
                ':request_id' => $requestId,
                ':provider_id' => $providerId
            ]);

            // 5) Update assignment status to declined where status is assigned (for that request)
            $stmt3 = $pdo->prepare("
                UPDATE maintenance_request_assignment
                SET status = 'declined'
                WHERE maintenance_request_id = :request_id
                  AND status = 'assigned'
            ");
            $stmt3->execute([':request_id' => $requestId]);

            $pdo->commit();

            $_SESSION['success'] = 'Assignment declined successfully.';
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = 'Something went wrong while declining the assignment.';
            error_log("Decline assignment error: " . $e->getMessage());
            // Debug only:
            echo $error = $e->getMessage();
            exit;
        }
    }
}
