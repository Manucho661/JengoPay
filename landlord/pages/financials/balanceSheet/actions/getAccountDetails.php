<?php
header('Content-Type: application/json');

require_once '../../../db/connect.php'; // include your PDO connection

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$account_id = $_GET['account'] ?? '';  // Ensure you're using the correct query parameter name

if (empty($account_id)) {  // Check if account_id is missing
    echo json_encode([
        'error' => true,
        'message' => 'account parameter is required'
    ]);
    exit;
}

try {
    // Prepare the SQL query to get details based on account_id
    $smth = $pdo->prepare("SELECT * FROM Journal_lines WHERE account_id = ?");
    $smth->execute([$account_id]);
    $details = $smth->fetchAll(PDO::FETCH_ASSOC);  // Use $smth, not $stmt

    echo json_encode([
        'error' => false,
        'data' => $details  // Return the fetched details as 'data'
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
