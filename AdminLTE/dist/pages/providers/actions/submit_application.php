<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once '../../db/connect.php'; // $conn is PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $your_price = $_POST['your_price'] ?? '';
    $duration = ($_POST['duration'] ?? '') === 'other' ? ($_POST['custom_duration'] ?? '') : $_POST['duration'];
    $cover_letter = $_POST['cover_letter'] ?? '';
    $provider_id = 6;

    try {
        $sql = "INSERT INTO maintenance_request_proposals (bid_amount, estimated_time, cover_letter, provider_id)
                VALUES (:bid_amount, :estimated_time, :cover_letter, :provider_id)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':bid_amount' => $your_price,
            ':estimated_time' => $duration,
            ':cover_letter' => $cover_letter,
            ':provider_id' => $provider_id
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
