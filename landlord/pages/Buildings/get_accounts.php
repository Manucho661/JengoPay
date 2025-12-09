<?php
require_once "../db/connect.php"; // adjust path if needed

header('Content-Type: application/json');

try {
    // Fetch Chart of Accounts
    $stmt = $pdo->prepare("
        SELECT id, account_name, account_code 
        FROM chart_of_accounts 
        ORDER BY account_code ASC
    ");
    $stmt->execute();

    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $accounts
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
