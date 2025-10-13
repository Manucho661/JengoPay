<?php
require_once '../../db/connect.php';

// Initialize array
$transactions = [];

/* =======================
   FETCH INVOICES (CREDITS)
   ======================= */
$queryInvoices = "
    SELECT 
        invoice_number AS number, 
        created_at AS date, 
        account_item, 
        total AS amount
    FROM invoice_items
    ORDER BY created_at ASC
";
$stmt = $pdo->prepare($queryInvoices);
$stmt->execute();
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($invoices as $inv) {
    $transactions[] = [
        'number' => htmlspecialchars($inv['number']),
        'date' => $inv['date'],
        'debit' => 0,
        'credit' => (float)$inv['amount']
    ];
}

/* =======================
   FETCH EXPENSES (DEBITS)
   ======================= */
$queryExpenses = "
    SELECT 
        expense_id AS number, 
        created_at AS date, 
        item_account_code, 
        item_total AS amount
    FROM expense_items
    ORDER BY created_at ASC
";
$stmt = $pdo->prepare($queryExpenses);
$stmt->execute();
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($expenses as $exp) {
    $transactions[] = [
        'number' => 'EXP' . htmlspecialchars($exp['number']),
        'date' => $exp['date'],
        'debit' => (float)$exp['amount'],
        'credit' => 0
    ];
}

/* =======================
   SORT TRANSACTIONS BY DATE
   ======================= */
usort($transactions, function($a, $b) {
    return strtotime($a['date']) - strtotime($b['date']);
});

/* =======================
   CALCULATE RUNNING BALANCE
   ======================= */
$balance = 0;
$totalDebit = 0;
$totalCredit = 0;

foreach ($transactions as $key => $tr) {
    $balance += $tr['credit'] - $tr['debit'];
    $transactions[$key]['balance'] = $balance;
    $totalDebit += $tr['debit'];
    $totalCredit += $tr['credit'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>General Ledger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        h3 {
            color: #00192D;
        }
        .table th, .table td {
            vertical-align: middle;
            font-size: 15px;
        }
        .table tfoot td {
            font-weight: bold;
            background-color: #f1f1f1;
        }
    </style>
</head>
<body class="p-4">
    <div class="container-fluid">
        <h3 class="mb-4">📘 General Ledger</h3>

        <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Number</th>
                        <th>Date</th>
                        <th class="text-end">Debit (Ksh)</th>
                        <th class="text-end">Credit (Ksh)</th>
                        <th class="text-end">Balance (Ksh)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($transactions) > 0): ?>
                        <?php foreach ($transactions as $tr): ?>
                            <tr>
                                <td><?= htmlspecialchars($tr['number']) ?></td>
                                <td><?= date('Y-m-d', strtotime($tr['date'])) ?></td>
                                <td class="text-end"><?= $tr['debit'] > 0 ? number_format($tr['debit'], 2) : '-' ?></td>
                                <td class="text-end"><?= $tr['credit'] > 0 ? number_format($tr['credit'], 2) : '-' ?></td>
                                <td class="text-end"><?= number_format($tr['balance'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted">No transactions found.</td></tr>
                    <?php endif; ?>
                </tbody>
                <?php if (count($transactions) > 0): ?>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-end">Totals:</td>
                        <td class="text-end"><?= number_format($totalDebit, 2) ?></td>
                        <td class="text-end"><?= number_format($totalCredit, 2) ?></td>
                        <td class="text-end"><?= number_format($balance, 2) ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>
