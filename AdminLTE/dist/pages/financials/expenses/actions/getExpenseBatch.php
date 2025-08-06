<?php
include '../../../db/connect.php';

try {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("Missing or empty 'id' parameter.");
    }

    $expenseId = $_GET['id'];

    $stm = $pdo->prepare("
        SELECT * FROM expenses
        LEFT JOIN expense_items 
            ON expenses.id = expense_items.expense_id
        LEFT JOIN expense_payments 
            ON expenses.id = expense_payments.expense_id
        WHERE expenses.id = ?
    ");
    $stm->execute([$expenseId]);

    $results = $stm->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($results);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
