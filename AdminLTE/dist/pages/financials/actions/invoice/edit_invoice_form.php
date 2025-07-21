<?php
// Make sure $inv and $id are available from the parent file

// Fetch line items
$lineItems = $pdo->prepare("SELECT id, description, quantity, unit_price FROM invoice WHERE id = ?");
$lineItems->execute([$id]);
$rows = $lineItems->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h4>Edit Draft Invoice #<?= htmlspecialchars($inv['invoice_number']) ?></h4>

    <form action="update_invoice.php" method="POST">
        <input type="hidden" name="invoice_id" value="<?= $inv['id'] ?>">

        <!-- Invoice Meta -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Invoice Date</label>
                <input type="date" name="invoice_date" class="form-control" value="<?= htmlspecialchars($inv['invoice_date']) ?>">
            </div>
            <div class="col-md-4">
                <label>Due Date</label>
                <input type="date" name="due_date" class="form-control" value="<?= htmlspecialchars($inv['due_date']) ?>">
            </div>
        </div>

        <!-- Line Items Table -->
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody id="line-items-body">
                <?php foreach ($rows as $i => $line): ?>
                <tr>
                    <td>
                        <input type="hidden" name="items[<?= $i ?>][id]" value="<?= $line['id'] ?>">
                        <input type="text" name="items[<?= $i ?>][description]" class="form-control" value="<?= htmlspecialchars($line['description']) ?>">
                    </td>
                    <td><input type="number" name="items[<?= $i ?>][quantity]" class="form-control" value="<?= $line['quantity'] ?>" step="1" min="1"></td>
                    <td><input type="number" name="items[<?= $i ?>][unit_price]" class="form-control" value="<?= $line['unit_price'] ?>" step="0.01"></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();">×</button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="button" class="btn btn-secondary" onclick="addLineItem()">Add Line Item</button>
        <br><br>
        <button type="submit" class="btn btn-success">Save Invoice</button>
    </form>
</div>

<script>
    let itemCount = <?= count($rows) ?>;

    function addLineItem() {
        const tbody = document.getElementById('line-items-body');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="items[${itemCount}][description]" class="form-control"></td>
            <td><input type="number" name="items[${itemCount}][quantity]" class="form-control" value="1" min="1"></td>
            <td><input type="number" name="items[${itemCount}][unit_price]" class="form-control" value="0.00" step="0.01"></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();">×</button></td>
        `;
        tbody.appendChild(row);
        itemCount++;
    }
</script>
