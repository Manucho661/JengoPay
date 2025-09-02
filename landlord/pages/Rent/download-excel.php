<?php
// Set headers to tell the browser this is an Excel file
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rent-report.xls");

// Sample data (replace with actual logic or include your rent table loop here)
$tenants = [
    ['tenant_name' => 'John Doe', 'unit_code' => 'A101', 'amount_paid' => 5000, 'penalty' => 100, 'arrears' => 200, 'overpayment' => 0],
    ['tenant_name' => 'Jane Smith', 'unit_code' => 'B202', 'amount_paid' => 6000, 'penalty' => 0, 'arrears' => 0, 'overpayment' => 300],
];
?>

<table border="1">
    <thead>
        <tr>
            <th>Tenant + Unit</th>
            <th>Paid</th>
            <th>Penalty</th>
            <th>Arrears</th>
            <th>Overpayment</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tenants as $tenant): ?>
            <tr>
                <td><?= htmlspecialchars($tenant['tenant_name']) ?> (<?= $tenant['unit_code'] ?>)</td>
                <td>KSH <?= number_format($tenant['amount_paid'], 2) ?></td>
                <td>KSH <?= number_format($tenant['penalty'], 2) ?></td>
                <td>KSH <?= number_format($tenant['arrears'], 2) ?></td>
                <td>KSH <?= number_format($tenant['overpayment'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
