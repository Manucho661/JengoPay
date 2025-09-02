<?php
include '../db/connect.php'; // Database connection

// Get invoice ID from query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "<p style='color:red;'>Invalid invoice ID.</p>";
    exit;
}

try {
    // Fetch invoice with tenant name
    $stmt = $pdo->prepare("
        SELECT
            i.invoice_number,
            i.created_at,
            i.status,
            i.total_amount,
            CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name) AS tenant_name
        FROM invoice i
        LEFT JOIN users u ON i.tenant_id = u.id
        WHERE i.id = :id
    ");
    $stmt->execute(['id' => $id]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        echo "<p style='color:red;'>Invoice not found.</p>";
        exit;
    }

    // Fetch invoice items
    $itemsStmt = $pdo->prepare("
        SELECT description, quantity, unit_price, sub_total
        FROM invoice_items
        WHERE invoice_id = :id
    ");
    $itemsStmt->execute(['id' => $id]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Output HTML
    echo "<h4>Invoice #{$invoice['invoice_number']}</h4>";
    echo "<p><strong>Tenant:</strong> {$invoice['tenant_name']}</p>";
    echo "<p><strong>Date:</strong> {$invoice['created_at']}</p>";
    echo "<p><strong>Status:</strong> {$invoice['status']}</p>";

    echo "<table style='width:100%; border-collapse:collapse; margin-top:10px;' border='1'>
            <thead>
                <tr>
                    <th style='padding:5px;'>Description</th>
                    <th style='padding:5px;'>Qty</th>
                    <th style='padding:5px;'>Unit Price</th>
                    <th style='padding:5px;'>Subtotal</th>
                </tr>
            </thead>
            <tbody>";
    foreach ($items as $item) {
        echo "<tr>
                <td style='padding:5px;'>{$item['description']}</td>
                <td style='padding:5px;'>{$item['quantity']}</td>
                <td style='padding:5px;'>{$item['unit_price']}</td>
                <td style='padding:5px;'>{$item['sub_total']}</td>
              </tr>";
    }
    echo "</tbody></table>";

    echo "<p style='margin-top:10px;'><strong>Total Amount:</strong> {$invoice['total_amount']}</p>";

} catch (PDOException $e) {
    echo "<p style='color:red;'>Database error: {$e->getMessage()}</p>";
}
?>
