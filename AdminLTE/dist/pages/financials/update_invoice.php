<?php
include '../db/connect.php'; // Update with your correct path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceId     = $_POST['invoice_id'];
    $invoiceDate   = $_POST['invoice_date'];
    $dueDate       = $_POST['due_date'];
    $items         = $_POST['items'] ?? [];

    // Basic validation (you can expand this)
    if (!$invoiceId || !$invoiceDate || !$dueDate || empty($items)) {
        die("Missing invoice details.");
    }

    try {
        $pdo->beginTransaction();

        // 1. Update the invoice meta (date & due date)
        $stmt = $pdo->prepare("UPDATE invoice SET invoice_date = ?, due_date = ? WHERE id = ?");
        $stmt->execute([$invoiceDate, $dueDate, $invoiceId]);

        // 2. Update or insert invoice line items
        foreach ($items as $item) {
            $desc       = trim($item['description']);
            $qty        = (int) $item['quantity'];
            $unitPrice  = (float) $item['unit_price'];

            // Skip empty items
            if ($desc === '' || $qty <= 0 || $unitPrice < 0) continue;

            if (!empty($item['id'])) {
                // Update existing item
                $stmt = $pdo->prepare("UPDATE invoice SET description = ?, quantity = ?, unit_price = ? WHERE id = ? AND id = ?");
                $stmt->execute([$desc, $qty, $unitPrice, $item['id'], $invoiceId]);
            } else {
                // Insert new item
                $stmt = $pdo->prepare("INSERT INTO invoice (id, description, quantity, unit_price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$invoiceId, $desc, $qty, $unitPrice]);
            }
        }

        $pdo->commit();
        header("Location: invoice_details.php?id=$invoiceId");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error updating invoice: " . $e->getMessage();
    }

} else {
    echo "Invalid request.";
}
