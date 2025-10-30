<?php
include '../../db/connect.php';

// ==========================
//  FILTERS
// ==========================
$from_date   = $_GET['from_date'] ?? '';
$to_date     = $_GET['to_date'] ?? '';
$account_code = $_GET['account_code'] ?? '';
$account_name = $_GET['account_name'] ?? '';
$building_id  = $_GET['building_id'] ?? '';

// ==========================
//  WHERE CLAUSE
// ==========================
$where  = [];
$params = [];

if (!empty($from_date) && !empty($to_date)) {
    $where[] = "DATE(je.created_at) BETWEEN :from AND :to";
    $params[':from'] = $from_date;
    $params[':to']   = $to_date;
}

if (!empty($account_code)) {
    $where[] = "a.account_code = :account_code";
    $params[':account_code'] = $account_code;
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

// ==========================
//  MAIN QUERY
// ==========================
$sql = "
    SELECT 
        je.created_at,
        je.reference,
        je.description,
        a.account_code,
        a.account_name,
        jl.debit,
        jl.credit
    FROM journal_entries je
    INNER JOIN journal_lines jl ON je.id = jl.journal_entry_id
    INNER JOIN chart_of_accounts a ON jl.account_id = a.account_code
    $whereSql
    ORDER BY je.created_at ASC, je.id ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ledgerRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================
//  DROPDOWN DATA
// ==========================
$accounts = $pdo->query("
    SELECT account_code, account_name 
    FROM chart_of_accounts 
    ORDER BY account_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

$buildings = $pdo->query("
    SELECT id, building_name 
    FROM buildings 
    ORDER BY building_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

// ==========================
//  RUNNING BALANCE
// ==========================
$runningBalance = 0;
$totalDebit = 0;
$totalCredit = 0;
$pdfData = [];

foreach ($ledgerRows as $row) {
    $runningBalance += ($row['debit'] - $row['credit']);
    $totalDebit += $row['debit'];
    $totalCredit += $row['credit'];

    $pdfData[] = [
        'date' => date('Y-m-d', strtotime($row['created_at'])),
        'reference' => $row['reference'],
        'description' => $row['description'],
        'debit' => number_format($row['debit'], 2),
        'credit' => number_format($row['credit'], 2),
        'balance' => number_format($runningBalance, 2)
    ];
}

$pdfDataJson = json_encode($pdfData);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>General Ledger</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    body { font-size: 16px; }
    .badge-income { background-color: #28a745; color: white; }
    .badge-expense { background-color: #dc3545; color: white; }
  </style>
</head>

<body class="bg-light">
<div class="container py-4">
  <h2 style="color:#FFC107;"><i class="fas fa-file-invoice-dollar"></i> General Ledger</h2>
  <p class="text-muted"><?= htmlspecialchars($account_name ?: 'All Accounts') ?></p>

  <!-- FILTER FORM -->
  <form method="get" class="row g-3 mb-4 p-3 rounded bg-white shadow-sm">
    <div class="col-md-3">
      <label class="form-label">From Date</label>
      <input type="date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">To Date</label>
      <input type="date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">Account</label>
      <select name="account_code" class="form-select">
        <option value="">-- All Accounts --</option>
        <?php foreach ($accounts as $acc): ?>
          <option value="<?= $acc['account_code'] ?>" <?= $account_code == $acc['account_code'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($acc['account_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button type="submit" class="btn w-100" style="color:#FFC107; background-color:#00192D;">
        <i class="fas fa-filter"></i> Filter
      </button>
    </div>
  </form>

  <!-- SUMMARY CARDS -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h6>Total Debit</h6>
          <h4>Ksh <?= number_format($totalDebit, 2) ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-danger text-white">
        <div class="card-body">
          <h6>Total Credit</h6>
          <h4>Ksh <?= number_format($totalCredit, 2) ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-warning text-dark">
        <div class="card-body">
          <h6>Net Balance</h6>
          <h4>Ksh <?= number_format($runningBalance, 2) ?></h4>
        </div>
      </div>
    </div>
  </div>

  <!-- LEDGER TABLE -->
  <div class="table-responsive bg-white shadow-sm p-3 rounded">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Date</th>
          <th>Reference</th>
          <th>Description</th>
          <th>Debit (Ksh)</th>
          <th>Credit (Ksh)</th>
          <th>Running Balance (Ksh)</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($ledgerRows)): ?>
          <tr>
            <td colspan="6" class="text-center py-4">
              <i class="fas fa-info-circle text-muted"></i> No transactions found.
            </td>
          </tr>
        <?php else: ?>
          <?php 
            $displayBalance = 0;
            foreach ($ledgerRows as $r):
              $displayBalance += ($r['debit'] - $r['credit']);
          ?>
          <tr>
            <td><?= htmlspecialchars(date('Y-m-d', strtotime($r['created_at']))) ?></td>
            <td><?= htmlspecialchars($r['reference']) ?></td>
            <td><?= htmlspecialchars($r['description']) ?></td>
            <td class="text-end"><?= number_format($r['debit'], 2) ?></td>
            <td class="text-end"><?= number_format($r['credit'], 2) ?></td>
            <td class="text-end fw-bold"><?= number_format($displayBalance, 2) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
