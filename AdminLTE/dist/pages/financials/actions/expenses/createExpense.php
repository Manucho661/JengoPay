<?php
 include '../../../db/connect.php';

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Show errors during development
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        // Dump post data
        // var_dump($_POST); exit;

        $pdo->beginTransaction();

        $year = $_POST['year'] ?? null;
        $month = $_POST['month'] ?? null;
        $supplier = $_POST['supplier'] ?? null;
        $total = $_POST['total'] ?? 0.00;

        $stmt = $pdo->prepare("INSERT INTO expenses (year, month, supplier, total) VALUES (?, ?, ?, ?)");
        $stmt->execute([$year, $month, $supplier, $total]);

        $expense_id = $pdo->lastInsertId();

        $pdo->commit();
        echo "Inserted successfully with ID: $expense_id";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

?>