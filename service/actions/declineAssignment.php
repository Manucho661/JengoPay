<?php

require_once '../db/connect.php'; // $pdo is PDO

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decline_assignment'])) {

    // Proposal ID from POST
    $maintenance_proposal_id = (int) ($_POST['proposal_id'] ?? 0);

    if (!$maintenance_proposal_id) {
        $error = 'Invalid proposal ID.';
    } elseif (!isset($_SESSION['user']['id'])) {
        $error = 'You must be logged in to withdraw a proposal.';
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE maintenance_request_proposals
                SET status = 'Withdrawn'
                WHERE id = :id
            ");

            $stmt->execute([
                ':id' => $maintenance_proposal_id
            ]);

            $success = 'Your proposal has been withdrawn successfully.';

            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        } catch (PDOException $e) {
            $error = 'Something went wrong while withdrawing the proposal.';
            // Debug only:
            // $error = $e->getMessage();
        }
    }
}
