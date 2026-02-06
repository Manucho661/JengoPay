<?php

require_once '../db/connect.php'; // $pdo is PDO

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {

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
            $provider_id = $provider['id'];

            // Validation
            if (empty($maintenance_request_id) || empty($proposed_budget) || empty($proposed_duration)) {
                $error = 'All fields are required.';
            } else {
                try {
                    $sql = "INSERT INTO maintenance_request_proposals
                            (maintenance_request_id, service_provider_id, proposed_budget, proposed_duration, cover_letter, provider_availability)
                            VALUES (:maintenance_request_id, :service_provider_id, :proposed_budget, :proposed_duration, :cover_letter, :provider_availability)";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':maintenance_request_id' => $maintenance_request_id,
                        ':service_provider_id'    => $provider_id,
                        ':proposed_budget'        => $proposed_budget,
                        ':proposed_duration'      => $proposed_duration,
                        ':cover_letter'           => $cover_letter,
                        ':provider_availability'   => $provider_availability
                    ]);

                    $success = 'Your proposal has been submitted successfully.';
                } catch (PDOException $e) {
                    $error = 'Something went wrong. Please try again.';
                    // For debugging only: $error = $e->getMessage();

                    echo $e->getMessage();
                    exit;
                }
            }
        }
    }
}
