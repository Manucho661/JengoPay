<?php 
include '../../db/connect.php';

// ===========================
// BUILD FILTER CONDITIONS
// ===========================
$where = [];
$params = [];

// Date range filter
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $where[] = "je.entry_date BETWEEN :from AND :to";
    $params[':from'] = $_GET['from_date'];
    $params[':to']   = $_GET['to_date'];
}

// Account filter
if (!empty($_GET['account_id'])) {
    $where[] = "jl.account_id = :account_id";
    $params[':account_id'] = $_GET['account_id'];
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

// ===========================
// MAIN QUERY (UNION journals + expense_items.item_total)
// ===========================
$where = [];
$params = [];

// Date range filter for both journal_entries.entry_date and expense_items.created_at
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $where[] = "(je.entry_date BETWEEN :from AND :to OR ei.created_at BETWEEN :from AND :to)";
    $params[':from'] = $_GET['from_date'];
    $params[':to']   = $_GET['to_date'];
}

// Optional account filter (apply to journal_lines.account_id OR expense_items.item_account_code)
if (!empty($_GET['account_id'])) {
    $where[] = "(jl.account_id = :account_id OR ei.item_account_code = :account_id)";
    $params[':account_id'] = $_GET['account_id'];
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

/*
  Two-part UNION:
  - Part A: aggregates journal_lines (same as before)
  - Part B: aggregates expense_items using item_total (explicitly)
*/
$sql = "
    -- PART A: Journal lines
    SELECT 
        a.account_code,
        a.account_name,
        a.account_type,
        a.financial_statement,
        a.debit_credit,
        COALESCE(SUM(jl.debit), 0) AS total_debit,
        COALESCE(SUM(jl.credit), 0) AS total_credit,
        'journal' AS source_type
    FROM chart_of_accounts a
    LEFT JOIN journal_lines jl ON a.account_code = jl.account_id
    LEFT JOIN journal_entries je ON jl.journal_entry_id = je.id
    LEFT JOIN expense_items ei ON 1=0  -- keep same column namespace for union; no effect
    $whereSql
    GROUP BY a.account_code, a.account_name, a.account_type, a.financial_statement, a.debit_credit

    UNION ALL

    -- PART B: Expenses (use item_total explicitly)
    SELECT
        ei.item_account_code AS account_code,
        COALESCE(a.account_name, CONCAT('Expense (code ', ei.item_account_code, ')')) AS account_name,
        COALESCE(a.account_type, 'Expense') AS account_type,
        COALESCE(a.financial_statement, 'P&L') AS financial_statement,
        'DEBIT' AS debit_credit,
        COALESCE(SUM(CAST(ei.item_total AS DECIMAL(20,2))), 0) AS total_debit,
        0 AS total_credit,
        'expense_items' AS source_type
    FROM expense_items ei
    LEFT JOIN chart_of_accounts a ON ei.item_account_code = a.account_code
    LEFT JOIN journal_lines jl ON 1=0
    LEFT JOIN journal_entries je ON 1=0
    $whereSql
    GROUP BY ei.item_account_code, a.account_name, a.account_type, a.financial_statement

    ORDER BY account_code
";


$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rawRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===========================
// POST-PROCESSING: DETERMINE DEBIT / CREDIT POSITION
// ===========================
$rows = [];
foreach ($rawRows as $r) {
    $accountName = strtolower(trim($r['account_name']));
    $normalSide  = strtoupper(trim($r['debit_credit'])); // 'DEBIT' or 'CREDIT'
    $net = $r['total_debit'] - $r['total_credit'];

    // Default logic based on normal side
    if ($normalSide === 'DEBIT') {
        $debit = $net >= 0 ? $net : 0;
        $credit = $net < 0 ? abs($net) : 0;
    } else {
        $credit = $net <= 0 ? abs($net) : 0;
        $debit = $net > 0 ? $net : 0;
    }

    // ===========================
    // FORCE CREDIT for these cases
    // ===========================
    if (
        strpos($accountName, 'accounts payable..') !== false ||
        strpos($accountName, 'loan..') !== false ||
        strpos($accountName, 'capital..') !== false ||
        strpos($accountName, 'revenue..') !== false ||
        strpos($accountName, 'income..') !== false ||
        strpos($accountName, 'garbage collection fees..') !== false
    ) {
        // Ensure it shows under Credit only
        $credit = max($r['total_credit'], abs($net));
        $debit = 0;
    }

    // Skip zero balances
    if (abs($debit) < 0.01 && abs($credit) < 0.01) continue;

    $r['adjusted_debit'] = $debit;
    $r['adjusted_credit'] = $credit;

    $rows[] = $r;
}

// ===========================
// FETCH ACCOUNT LIST FOR FILTERS
// ===========================
$accounts = $pdo->query("
    SELECT account_code, account_name 
    FROM chart_of_accounts 
    ORDER BY account_name
")->fetchAll(PDO::FETCH_ASSOC);
?>



<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Trial Balance Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Styles -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="../../../../landlord/css/adminlte.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
  
  <style>
    body { font-size: 16px; }
    .table th { background-color: #343a40; color: white; }
    .balance-positive { color: #28a745; font-weight: bold; }
    .balance-negative { color: #dc3545; font-weight: bold; }
    .account-code { color: #6c757d; font-size: 0.9em; }
    .debug-info { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <!-- Header -->
    <nav class="app-header navbar navbar-expand bg-body">
      <div class="container-fluid">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <img src="17.jpg" class="user-image rounded-circle shadow" alt="User Image" />
              <span class="d-none d-md-inline"><b>JENGO PAY</b></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <li class="user-header text-bg-primary">
                <img src="../../dist/assets/img/user2-160x160.jpg" class="rounded-circle shadow" alt="User Image" />
                <p>Alexander Pierce - Web Developer<small>Member since Nov. 2023</small></p>
              </li>
              <li class="user-footer">
                <a href="#" class="btn btn-default btn-flat">Profile</a>
                <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Sidebar -->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <div class="sidebar-brand">
        <a href="./index.html" class="brand-link">
          <span class="brand-text font-weight-light">
            <b class="p-2" style="background-color:#FFC107; border:2px solid #FFC107; border-top-left-radius:5px; font-weight:bold; color:#00192D;">BT</b>
            <b class="p-2" style="border-bottom-right-radius:5px; font-weight:bold; border:2px solid #FFC107; color: #FFC107;">JENGOPAY</b>
          </span>
        </a>
      </div>
      <div><?php include_once '../../includes/sidebar.php'; ?></div>
    </aside>

    <!-- Main Content -->
    <?php 
include '../../db/connect.php';

// ===========================
// BUILD FILTER CONDITIONS
// ===========================
$where = [];
$params = [];

// Date range filter
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $where[] = "je.entry_date BETWEEN :from AND :to";
    $params[':from'] = $_GET['from_date'];
    $params[':to']   = $_GET['to_date'];
}

// ===========================
// MAIN QUERY
// ===========================
$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

$sql = "
    SELECT 
        a.account_code,
        a.account_name,
        a.account_type,
        a.financial_statement,
        a.debit_credit,
        COALESCE(SUM(jl.debit), 0) AS total_debit,
        COALESCE(SUM(jl.credit), 0) AS total_credit
    FROM chart_of_accounts a
    LEFT JOIN journal_lines jl ON a.account_code = jl.account_id 
    LEFT JOIN journal_entries je ON jl.journal_entry_id = je.id
    $whereSql
    GROUP BY a.account_code, a.account_name, a.account_type, a.financial_statement, a.debit_credit
    HAVING COALESCE(SUM(jl.debit), 0) != 0 OR COALESCE(SUM(jl.credit), 0) != 0
    ORDER BY a.account_code
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container">
  <h3 class="mb-4 text-center">Trial Balance</h3>

  <!-- FILTER FORM -->
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
      <label class="form-label">From Date</label>
      <input type="date" name="from_date" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>" class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label">To Date</label>
      <input type="date" name="to_date" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>" class="form-control">
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button type="submit" class="btn w-100" style="background-color:#00192D;color:#FFC107;">
        <i class="fas fa-filter me-2"></i>Filter
      </button>
    </div>
  </form>

  <div class="card shadow">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table id="trialBalance" class="table table-bordered table-striped mb-0">
          <thead class="table" style="color:#FFC107;">
            <tr>
              <th rowspan="2">Account</th>
              <th colspan="2">Initial Balance</th>
              <th colspan="2"><?= !empty($_GET['from_date']) && !empty($_GET['to_date']) ? date('M Y', strtotime($_GET['from_date'])) : 'Period' ?></th>
              <th colspan="2">End Balance</th>
            </tr>
            <tr>
              <th>Debit</th>
              <th>Credit</th>
              <th>Debit</th>
              <th>Credit</th>
              <th>Debit</th>
              <th>Credit</th>
            </tr>
          </thead>
          <tbody>
          <?php
$totalInitialDebit = $totalInitialCredit = 0;
$totalPeriodDebit = $totalPeriodCredit = 0;
$totalEndDebit = $totalEndCredit = 0;

foreach ($rows as $r):
    $accountName = strtolower(trim($r['account_name']));
    $net = $r['total_debit'] - $r['total_credit'];

    $debit = $credit = 0;

    if (strpos($accountName, 'vat payable..') !== false) {
        $debit = $r['total_debit'];
        $credit = $r['total_credit'];

    } elseif (strpos($accountName, 'accounts payable..') !== false) {
        $debit = $r['total_debit'];
        $credit = $r['total_credit'];

    } elseif (strpos($accountName, 'accounts receivable..') !== false) {
        // ✅ Keep Accounts Receivable.. visible even after payments
        $debit = $r['total_debit'];
        $credit = $r['total_credit'];

    } elseif (
        (strpos($accountName, 'owner..') !== false && strpos($accountName, 'capital..') !== false) ||
        strpos($accountName, 'revenue..') !== false ||
        strpos($accountName, 'income..') !== false ||
        strpos($accountName, 'garbage..') !== false ||
        strpos($accountName, 'late payment..') !== false ||
        strpos($accountName, 'commission..') !== false ||
        strpos($accountName, 'management fee..') !== false
    ) {
        $credit = abs($net);

    } elseif (
        strpos($accountName, 'expense..') !== false ||
        strpos($accountName, 'utilities..') !== false ||
        strpos($accountName, 'repair..') !== false ||
        strpos($accountName, 'maintenance..') !== false ||
        strpos($accountName, 'internet..') !== false ||
        strpos($accountName, 'cleaning..') !== false ||
        strpos($accountName, 'security..') !== false ||
        strpos($accountName, 'salary..') !== false
    ) {
        $debit = abs($net);

    } elseif (
        strpos($accountName, 'cash..') !== false ||
        strpos($accountName, 'mpesa..') !== false ||
        strpos($accountName, 'bank..') !== false
    ) {
        $credit = abs($net);

    } else {
        if ($net >= 0) $debit = $net;
        else $credit = abs($net);
    }

    $initialDebit  = $r['initial_debit']  ?? 0;
    $initialCredit = $r['initial_credit'] ?? 0;

    // End balances
    $endDebit = $initialDebit + $debit;
    $endCredit = $initialCredit + $credit;

    $totalInitialDebit += $initialDebit;
    $totalInitialCredit += $initialCredit;
    $totalPeriodDebit += $debit;
    $totalPeriodCredit += $credit;
    $totalEndDebit += $endDebit;
    $totalEndCredit += $endCredit;

?>

<tr data-account-id="<?= htmlspecialchars($r['account_code']) ?>" style="cursor:pointer;">
  <td>
  <div class="d-flex align-items-center justify-content-between">
  <div class="fw-bold">
    <?= htmlspecialchars($r['account_name']) ?>:
  </div>

  <!-- Dots dropdown -->
  <div class="dropdown">
    <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
      <i class="fas fa-ellipsis-v"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li>
        <a class="dropdown-item" href="/Jengopay/landlord/pages/financials/trialbalance/general_ledger.php?account=<?= urlencode($r['account_name']) ?>">
          View General Ledger
        </a>
      </li>
    </ul>
  </div>
</div>
    <small class="account-code">
      Code: <?= htmlspecialchars($r['account_code']) ?> | 
      Type: <?= htmlspecialchars($r['account_type'] ?? 'N/A') ?>
    </small>
  </td>
  <td class="text-end"><?= $initialDebit > 0 ? number_format($initialDebit,2) : '' ?></td>
  <td class="text-end"><?= $initialCredit > 0 ? number_format($initialCredit,2) : '' ?></td>
  <td class="text-end <?= $debit > 0 ? 'balance-positive' : '' ?>"><?= $debit > 0 ? number_format($debit,2) : '' ?></td>
  <td class="text-end <?= $credit > 0 ? 'balance-negative' : '' ?>"><?= $credit > 0 ? number_format($credit,2) : '' ?></td>
  <td class="text-end"><?= $endDebit > 0 ? number_format($endDebit,2) : '' ?></td>
  <td class="text-end"><?= $endCredit > 0 ? number_format($endCredit,2) : '' ?></td>
</tr>
<?php endforeach; ?>
          </tbody>
          <tfoot class="table-dark">
            <tr>
              <th>Total</th>
              <th class="text-end"><?= number_format($totalInitialDebit,2) ?></th>
              <th class="text-end"><?= number_format($totalInitialCredit,2) ?></th>
              <th class="text-end"><?= number_format($totalPeriodDebit,2) ?></th>
              <th class="text-end"><?= number_format($totalPeriodCredit,2) ?></th>
              <th class="text-end"><?= number_format($totalEndDebit,2) ?></th>
              <th class="text-end"><?= number_format($totalEndCredit,2) ?></th>
            </tr>
            <tr class="<?= abs($totalEndDebit - $totalEndCredit) < 0.01 ? 'table-success' : 'table-danger' ?>">
              <td colspan="7" class="text-center fw-bold">
                <?php if (abs($totalEndDebit - $totalEndCredit) < 0.01): ?>
                  <i class="fas fa-check-circle me-2"></i>Trial Balance is Balanced!
                <?php else: ?>
                  <i class="fas fa-exclamation-triangle me-2"></i>
                  Trial Balance is Out of Balance by: Ksh <?= number_format(abs($totalEndDebit - $totalEndCredit), 2) ?>
                <?php endif; ?>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <div class="card-footer text-muted d-flex justify-content-between">
      <small>Generated on: <?= date('Y-m-d H:i:s') ?></small>
      <small>
        Period: <?= !empty($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : 'All' ?> 
        to <?= !empty($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : 'All' ?>
      </small>
    </div>
  </div>
</main>


</div>

  <!-- Ledger Modal -->
  <div class="modal fade" id="ledgerModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">General Ledger - <span id="modalAccountName"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">Loading ledger details...</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</body>
</html>