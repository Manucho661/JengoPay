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

        $expense_date = $_POST['date'] ?? null;
        $expense_no = $_POST['expense_no'] ?? null;
        $supplier = $_POST['supplier'] ?? null;
        $totalTax = $_POST['totalTax'] ?? 0.00;
        $total = $_POST['total'] ?? 0.00;

        $stmt = $pdo->prepare("INSERT INTO expenses (expense_date, expense_no, supplier,total_taxes, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$expense_date, $expense_no, $supplier, $totalTax, $total]);

        $expense_id = $pdo->lastInsertId();

        $item_names = $_POST['item_name'] ?? [];
        $descriptions = $_POST['description'] ?? [];
        $quantities = $_POST['qty'] ?? [];
        $unit_prices = $_POST['unit_price'] ?? [];
        $taxes = $_POST['taxes'] ?? [];
        $totals = $_POST['item_total'] ?? [];

        $stmtItem = $pdo->prepare("INSERT INTO expense_items (item_name, expense_id, description, qty, unit_price, taxes, total) VALUES (?, ?, ?,?, ?, ?, ?)");

        for ($i = 0; $i < count($item_names); $i++) {
        $stmtItem->execute([
            $item_names[$i],
            $expense_id,
            $descriptions[$i],
            $quantities[$i],
            $unit_prices[$i],
            $taxes[$i],
            $totals[$i]
        ]);
        }

        $pdo->commit();
        echo "Inserted successfully with ID: $expense_id";
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

?>