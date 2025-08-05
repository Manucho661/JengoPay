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
        $building_id = $_POST['building_id'] ?? null;
        $supplier = $_POST['supplier'] ?? null;
        $untaxedAmount = $_POST['untaxedAmount'];
        $totalTax = $_POST['totalTax'] ?? 0.00;
        $total = $_POST['total'] ?? 0.00;

        $stmt = $pdo->prepare("INSERT INTO expenses (supplier_id, building_id, expense_date, expense_no, supplier,untaxed_amount, total_taxes, total) VALUES (?,?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$supplier, $building_id, $expense_date, $expense_no, $supplier, $untaxedAmount, $totalTax, $total]);

        $expense_id = $pdo->lastInsertId();

        $item_account_codes = $_POST['item_account_code'] ?? [];
        $descriptions = $_POST['description'] ?? [];
        $quantities = $_POST['qty'] ?? [];
        $unit_prices = $_POST['unit_price'] ?? [];
        $taxes = $_POST['taxes'] ?? [];
        $item_totals = $_POST['item_total'] ?? [];
        $discounts = $_POST['discount'] ?? [];
        $stmtItem = $pdo->prepare("INSERT INTO expense_items (item_account_code, expense_id, description, qty, unit_price, item_untaxed_amount, taxes, item_total, discount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        for ($i = 0; $i < count($item_account_codes); $i++) {
            $item_untaxed_amount= $quantities[$i] *$unit_prices[$i];
        $stmtItem->execute([
            $item_account_codes[$i],
            $expense_id,
            $descriptions[$i],
            $quantities[$i],
            $unit_prices[$i],
            $item_untaxed_amount,
            $taxes[$i],
            $item_totals[$i],
            $discounts[$i]
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