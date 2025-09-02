<?php
require_once '../../db/connect.php';

try{
        // Limit as a positional parameter
    $limit = 4;
    $stmt = $pdo->prepare("SELECT id, name, category FROM providers LIMIT ?");
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);

    $stmt->execute();
    $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($providers);
}
catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    http_response_code(500);
}

?>