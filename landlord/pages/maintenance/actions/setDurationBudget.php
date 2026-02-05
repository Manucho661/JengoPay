<?php
require_once '../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['setBudgetDuration'])) {
    return; // do nothing on GET
}

try {

    // ✅ Check request_id
    if (!isset($_POST['request_id'])) {
        throw new Exception("No request ID provided.");
    }

    if (!isset($_POST['budget'], $_POST['durationOption'])) {
        throw new Exception("Missing budget or duration.");
    }

    $requestId = $_POST['request_id'];
    $budget = $_POST['budget'];
    $duration = $_POST['durationOption'];

    // Update database
    $stmt = $pdo->prepare("
        UPDATE maintenance_requests
        SET budget = :budget, duration = :duration
        WHERE id = :request_id
    ");

    $stmt->execute([
        ':budget' => $budget,
        ':duration' => $duration,
        ':request_id' => $requestId
    ]);

    // ✅ Redirect on success
    $_SESSION['success'] =
        "Budget and duration have been set successfully.";

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;

} catch (Throwable $e) {

    $_SESSION['error'] =
        'Failed to set budget and duration: ' . $e->getMessage();

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
