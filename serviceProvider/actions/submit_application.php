<?php
header('Content-Type: application/json');

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

require_once '../../db/connect.php'; // $conn is PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['requestId'] ?? '';
    $your_price = $_POST['your_price'] ?? '';
    $duration = ($_POST['duration'] ?? '') === 'other' ? ($_POST['custom_duration'] ?? '') : $_POST['duration'];
    $cover_letter = $_POST['cover_letter'] ?? '';
    $provider_id = 2; // Change this if you want to use dynamic provider_id from POST

    //  ✅ Input validation
    if (empty($your_price) || empty($request_id) || empty($duration) || empty($cover_letter)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required.']);
        exit;
    }

    try {
        $sql = "INSERT INTO maintenance_request_proposals (maintenance_request_id, provider_id, cover_letter, bid_amount, estimated_time)
                VALUES (:maintenance_request_id, :provider_id, :cover_letter, :bid_amount, :estimated_time)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':maintenance_request_id' => $request_id,
            ':bid_amount' => $your_price,
            ':estimated_time' => $duration,
            ':cover_letter' => $cover_letter,
            ':provider_id' => $provider_id
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'DB Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
