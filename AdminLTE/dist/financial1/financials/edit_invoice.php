<?php
require_once '../db/connect.php';

// Show errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Get invoice ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid invoice ID.");
}
$invoiceId = (int) $_GET['id'];

// Fetch invoice (must be draft)
$stmt = $pdo->prepare("SELECT * FROM invoice WHERE id = ? AND status = 'draft'");
$stmt->execute([$invoiceId]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) {
    die("Invoice not found or not editable (only drafts allowed).");
}

// Dropdowns
$buildings = $pdo->query("SELECT building_id, building_name FROM buildings ORDER BY building_name")->fetchAll(PDO::FETCH_ASSOC);
// $accountItems = $pdo->query("SELECT account_code, account_name FROM account_items ORDER BY account_name")->fetchAll(PDO::FETCH_ASSOC);

// Form variables
$invoiceNumber = $invoice['invoice_number'];
$invoiceDate = $invoice['invoice_date'];
$dueDate = $invoice['due_date'];
$buildingId = $invoice['building_id'];
$tenant = $invoice['tenant'];
$notes = $invoice['notes'];

// Use one invoice item row (your schema has one row per draft)
$draftItem = [
    'account_item' => $invoice['account_item'],
    'description' => $invoice['description'],
    'quantity' => $invoice['quantity'],
    'unit_price' => $invoice['unit_price'],
    'taxes' => $invoice['taxes'],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Invoice - <?= htmlspecialchars($invoiceNumber) ?></title>
</head>
<body>

<div class="invoice-form-container">
    <form method="POST" action="submit_invoice.php">
        <input type="hidden" id="invoice-id" name="invoice_id" value="<?= $invoiceId ?>">

        <!-- Tenant Details -->
        <div class="form-section">
            <h3 class="section-title">Tenant Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="invoice-number">Invoice #</label>
                    <input type="text" id="invoice-number" value="<?= $invoiceNumber ?>" class="form-control" readonly>
                    <input type="hidden" name="invoice_number" value="<?= $invoiceNumber ?>">
                </div>

                <div class="form-group">
                    <label for="building">Building</label>
                    <select id="building" name="building_id" class="form-control" required>
                        <option value="">Select a Building</option>
                        <?php foreach ($buildings as $b): ?>
                            <option value="<?= $b['building_id'] ?>" <?= $b['building_id'] == $buildingId ? 'selected' : '' ?>>
                                <?= htmlspecialchars($b['building_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="customer">Tenant</label>
                    <select id="customer" name="tenant" class="form-control" required>
                        <option value="<?= $tenant ?>" selected>Tenant #<?= $tenant ?></option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="invoice-date">Invoice Date</label>
                    <input type="date" id="invoice-date" name="invoice_date" class="form-control" required value="<?= $invoiceDate ?>">
                </div>

                <div class="form-group">
                    <label for="due-date">Due Date</label>
                    <input type="date" id="due-date" name="due_date" class="form-control" required value="<?= $dueDate ?>">
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="form-section">
            <h3 class="section-title">Items</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item (Service)</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Taxes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="account_item[]" class="select-account searchable-select" required>
                                <option value="" disabled>Select Account Item</option>
                                <?php foreach ($accountItems as $item): ?>
                                    <option value="<?= htmlspecialchars($item['account_code']) ?>"
                                        <?= $item['account_code'] == $draftItem['account_item'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($item['account_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <textarea name="description[]" rows="1" required><?= htmlspecialchars($draftItem['description']) ?></textarea>
                        </td>
                        <td>
                            <input type="number" name="quantity[]" class="form-control quantity" required value="<?= $draftItem['quantity'] ?>">
                        </td>
                        <td>
                            <input type="number" name="unit_price[]" class="form-control unit-price" required value="<?= $draftItem['unit_price'] ?>">
                        </td>
                        <td>
                            <select name="taxes[]" class="form-select vat-option" required>
                                <option value="inclusive" <?= $draftItem['taxes'] === 'inclusive' ? 'selected' : '' ?>>VAT 16% Inclusive</option>
                                <option value="exclusive" <?= $draftItem['taxes'] === 'exclusive' ? 'selected' : '' ?>>VAT 16% Exclusive</option>
                                <option value="zero" <?= $draftItem['taxes'] === 'zero' ? 'selected' : '' ?>>Zero Rated</option>
                                <option value="exempted" <?= $draftItem['taxes'] === 'exempted' ? 'selected' : '' ?>>Exempted</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)">
                                <i class="fa fa-trash" style="font-size: 12px;"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="button" class="add-btn" onclick="addRow()">
                <i class="fa fa-plus"></i> ADD MORE
            </button>
        </div>

        <!-- Notes -->
        <div class="form-section">
            <div class="form-row">
                <div class="form-group">
                    <label for="notes">Notes(Optional)</label>
                    <textarea id="notes" name="notes" class="form-control" rows="3"><?= htmlspecialchars($notes) ?></textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Invoice</button>
    </form>
</div>

</body>
</html>
