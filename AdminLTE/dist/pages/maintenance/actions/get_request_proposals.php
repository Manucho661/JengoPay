<?php 

require_once '../../db/connect.php';

if (isset($_GET['request_id'])) {
    $requestId = $_GET['request_id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM maintenance_request_proposals WHERE maintenance_request_id = ?");
        $stmt->execute([$requestId]);

        $proposals = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($proposals);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }

} else {
    echo json_encode([
        "success" => false,
        "message" => "No request_id provided"
    ]);
}