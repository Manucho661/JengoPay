<?php
require_once '../../db/connect.php';

$tenantId = $_GET['tenant'] ?? '';
if (empty($tenantId)) {
    die('<div class="alert alert-danger">No tenant specified</div>');
}

// Get tenant details
$sql = "
  SELECT 
    ii.tenant,
    u.first_name,
    u.middle_name,
    t.unit,
    t.phone_number
  FROM invoice_items ii
  LEFT JOIN tenants t ON ii.tenant = t.id
  LEFT JOIN users u ON t.user_id = u.id
  WHERE ii.tenant = :tenant
  LIMIT 1
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['tenant' => $tenantId]);
$tenantDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Get tenant invoices
$sql = "
  SELECT 
    ii.id, 
    ii.invoice_number, 
    ii.description, 
    ii.created_at, 
    ii.total
  FROM invoice_items ii
  WHERE ii.tenant = :tenant
  ORDER BY ii.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['tenant' => $tenantId]);
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

function daysOverdue($date) {
    $today = new DateTime();
    $invoiceDate = new DateTime($date);
    return $invoiceDate->diff($today)->days;
}

if (empty($invoices)) {
    echo '<div class="alert alert-info">No invoices found for this tenant</div>';
    return;
}

// Build tenant name
$firstName = $tenantDetails['first_name'] ?? '';
$middleName = $tenantDetails['middle_name'] ?? '';
$tenantName = trim($firstName . ' ' . $middleName);

if (empty($tenantName) || $tenantName == ' ') {
    $tenantName = 'Tenant ' . $tenantId;
}

// Calculate summary
$summary = [
    '0-30' => 0,
    '31-60' => 0,
    '61-90' => 0,
    '90+' => 0,
    'total' => 0
];

foreach ($invoices as $inv) {
    $days = daysOverdue($inv['created_at']);
    $amount = $inv['total'];
    
    if ($days <= 30) $summary['0-30'] += $amount;
    elseif ($days <= 60) $summary['31-60'] += $amount;
    elseif ($days <= 90) $summary['61-90'] += $amount;
    else $summary['90+'] += $amount;
    
    $summary['total'] += $amount;
}
?>

<!-- Tenant Information -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0">Tenant Information</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <strong>First Name:</strong> Emmanuel<br>
                <strong>Middle Name:</strong> Wafula<br>
            </div>
            <div class="col-md-6">
                <?php if (!empty($tenantDetails['unit'])): ?>
                    <strong>Unit:</strong> <?= htmlspecialchars($tenantDetails['unit']) ?><br>
                <?php endif; ?>
                <?php if (!empty($tenantDetails['phone_number'])): ?>
                    <strong>Phone:</strong> <?= htmlspecialchars($tenantDetails['phone_number']) ?><br>
                <?php endif; ?>
                <strong>Total Invoices:</strong> <?= count($invoices) ?><br>
            </div>
        </div>
    </div>
</div>

<!-- Summary -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Aging Summary</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 border-end">
                        <small class="text-muted">0-30 Days</small>
                        <h5><?= number_format($summary['0-30'], 2) ?></h5>
                    </div>
                    <div class="col-md-3 border-end">
                        <small class="text-muted">31-60 Days</small>
                        <h5><?= number_format($summary['31-60'], 2) ?></h5>
                    </div>
                    <div class="col-md-3 border-end">
                        <small class="text-muted">61-90 Days</small>
                        <h5><?= number_format($summary['61-90'], 2) ?></h5>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">90+ Days</small>
                        <h5><?= number_format($summary['90+'], 2) ?></h5>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-center">
                        <strong class="fs-5">Total Due: <?= number_format($summary['total'], 2) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoices Table -->
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Invoice #</th>
            <th>Description</th>
            <th>Date</th>
            <th class="text-end">Amount</th>
            <th class="text-end">Days Overdue</th>
            <th>Aging Bucket</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invoices as $row): 
            $days = daysOverdue($row['created_at']);
            if ($days <= 30) $bucket = '0-30';
            elseif ($days <= 60) $bucket = '31-60';
            elseif ($days <= 90) $bucket = '61-90';
            else $bucket = '90+';
        ?>
        <tr>
            <td><?= htmlspecialchars($row['invoice_number']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
            <td class="text-end"><?= number_format($row['total'], 2) ?></td>
            <td class="text-end"><?= $days ?></td>
            <td><span class="badge bg-<?= $bucket === '90+' ? 'danger' : ($bucket === '61-90' ? 'warning' : ($bucket === '31-60' ? 'info' : 'success')) ?>"><?= $bucket ?></span></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>