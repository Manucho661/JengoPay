<?php

require_once '../db/connect.php'; // $pdo is PDO

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reapplication'])) {

    // Collect data from POST
    $maintenance_request_id = (int) ($_POST['job_request_id'] ?? 0);
    $proposed_budget = $_POST['proposed_budget'] ?? '';
    $proposed_duration = $_POST['proposed_duration'] ?? '';
    $cover_letter = $_POST['cover_letter'] ?? '';
    $provider_availability = $_POST['provider_availability'] ?? '';

    // Get the logged-in user's ID from session
    if (!isset($_SESSION['user']['id'])) {
        $error = 'You must be logged in to submit a proposal.';
    } else {
        $user_id = $_SESSION['user']['id'];

        // Lookup service provider ID based on user_id
        $stmtProvider = $pdo->prepare("SELECT id FROM service_providers WHERE user_id = :user_id");
        $stmtProvider->execute([':user_id' => $user_id]);
        $provider = $stmtProvider->fetch(PDO::FETCH_ASSOC);

        if (!$provider) {
            $error = 'No service provider found for this user.';
        } else {
            $provider_id = (int)$provider['id'];

            // Validation
            if (empty($maintenance_request_id) || $proposed_budget === '' || $proposed_duration === '') {
                $error = 'All fields are required.';
            } else {
                try {

                    // ✅ Ensure a proposal exists to update (optional but clearer messaging)
                    $checkStmt = $pdo->prepare("
                        SELECT id 
                        FROM maintenance_request_proposals
                        WHERE maintenance_request_id = :maintenance_request_id
                          AND service_provider_id = :service_provider_id
                        LIMIT 1
                    ");
                    $checkStmt->execute([
                        ':maintenance_request_id' => $maintenance_request_id,
                        ':service_provider_id'    => $provider_id
                    ]);

                    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

                    if (!$existing) {
                        $error = 'No existing proposal found to update. Please apply first.';
                    } else {
                        // ✅ UPDATE instead of INSERT
                        $sql = "UPDATE maintenance_request_proposals
                                SET proposed_budget = :proposed_budget,
                                    proposed_duration = :proposed_duration,
                                    cover_letter = :cover_letter,
                                    provider_availability = :provider_availability,
                                    updated_at = NOW()
                                WHERE maintenance_request_id = :maintenance_request_id
                                  AND service_provider_id = :service_provider_id
                                LIMIT 1";

                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':proposed_budget'         => $proposed_budget,
                            ':proposed_duration'       => $proposed_duration,
                            ':cover_letter'            => $cover_letter,
                            ':provider_availability'   => $provider_availability,
                            ':maintenance_request_id'  => $maintenance_request_id,
                            ':service_provider_id'     => $provider_id
                        ]);

                        $success = 'Your proposal has been updated successfully.';
                    }

                } catch (PDOException $e) {
                    $error = 'Something went wrong. Please try again.';
                    // For debugging only: echo $e->getMessage(); exit;

                    echo $e->getMessage();
                    exit;
                }
            }
        }
    }
}
