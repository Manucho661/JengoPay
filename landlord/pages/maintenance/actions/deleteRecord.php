<?php
require_once '../../db/connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Run ONLY when delete button submitted
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['delete_request'])) {
    return; // included file: do nothing and let the rest of the page run
}

try {
    $requestID = isset($_POST['request_id']) ? (int) $_POST['request_id'] : 0;
    if ($requestID <= 0) {
        $_SESSION['error'] = "Missing or invalid request ID.";
        header('Location: ' . $_SERVER['REQUEST_URI']);
        return;
    }

    $stmt = $pdo->prepare("DELETE FROM maintenance_requests WHERE id = :id");
    $stmt->execute([':id' => $requestID]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Maintenance request deleted successfully.";
    } else {
        $_SESSION['error'] = "Request not found or already deleted.";
    }

    // Redirect back (prevents resubmission on refresh)
    header('Location: ' . $_SERVER['REQUEST_URI']);
    return;

} catch (PDOException $e) {
    $_SESSION['error'] = "Error deleting the request.";
    header('Location: ' . $_SERVER['REQUEST_URI']);
    return;
}
