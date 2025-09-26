<?php 
include '../../db/connect.php';

// Build filters
$where = [];
$params = [];

// Date range
if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $where[] = "jl.created_at BETWEEN :from AND :to";
    $params[':from'] = $_GET['from_date'];
    $params[':to']   = $_GET['to_date'];
}

// Account filter
if (!empty($_GET['account_id'])) {
    $where[] = "jl.account_id = :account_id";
    $params[':account_id'] = $_GET['account_id'];
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

// Fetch ALL accounts from chart_of_accounts, even those with no transactions
$sql = "
    SELECT 
        a.account_code,
        a.account_name,
        a.account_type,
        COALESCE(SUM(jl.debit), 0) AS total_debit,
        COALESCE(SUM(jl.credit), 0) AS total_credit
    FROM chart_of_accounts a
    LEFT JOIN journal_lines jl ON a.account_code = jl.account_id 
    " . ($whereSql ? "AND " . str_replace("WHERE", "", $whereSql) : "") . "
    GROUP BY a.account_code, a.account_name, a.account_type
    HAVING COALESCE(SUM(jl.debit), 0) != 0 OR COALESCE(SUM(jl.credit), 0) != 0
    ORDER BY a.account_code
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalDebit = 0;
$totalCredit = 0;

// Fetch account list for dropdown
$accounts = $pdo->query("SELECT account_code, account_name FROM chart_of_accounts ORDER BY account_name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Trial Balance Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Stylesheets -->
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
          <!-- User menu items -->
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
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h2 class="mb-3">Trial Balance Report</h2>
              
              <!-- Filters -->
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title mb-0">Filter Report</h5>
                </div>
                <div class="card-body">
                  <form method="get" class="row g-3">
                    <div class="col-md-3">
                      <label class="form-label">From Date</label>
                      <input type="date" name="from_date" class="form-control" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">To Date</label>
                      <input type="date" name="to_date" class="form-control" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                      <label class="form-label">Account</label>
                      <select name="account_id" class="form-select">
                        <option value="">-- All Accounts --</option>
                        <?php foreach ($accounts as $acc): ?>
                          <option value="<?= $acc['account_code'] ?>" <?= (!empty($_GET['account_id']) && $_GET['account_id'] == $acc['account_code']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($acc['account_name']) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                      <button type="submit" class="btn  w-100"  style= "background-color:#FFC107; color:#00192D;">Apply Filters</button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Trial Balance Table -->
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">Trial Balance</h5>
                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" onclick="exportToExcel()">
                      <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="exportToPDF()">
                      <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                      <i class="fas fa-print"></i> Print
                    </button>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="trialBalance" class="table table-bordered table-striped mb-0">
                      <thead class="table-dark">
                        <tr>
                          <th width="50%">Account</th>
                          <th width="25%">Debit (Ksh)</th>
                          <th width="25%">Credit (Ksh)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $totalDebit = 0;
                        $totalCredit = 0;
                        
                        foreach ($rows as $r): 
                          // Calculate net balance
                          $netBalance = $r['total_debit'] - $r['total_credit'];
                          
                          if ($netBalance > 0) {
                            $debit = $netBalance;
                            $credit = 0;
                          } else {
                            $debit = 0;
                            $credit = abs($netBalance);
                          }
                          
                          $totalDebit += $debit;
                          $totalCredit += $credit;
                          
                          // Skip accounts with zero balance
                          if ($debit == 0 && $credit == 0) continue;
                        ?>
                        <tr data-account-id="<?= $r['account_code'] ?>" style="cursor: pointer;">
                          <td>
                            <div class="fw-bold"><?= htmlspecialchars($r['account_name']) ?></div>
                            <small class="account-code">Code: <?= $r['account_code'] ?> | Type: <?= $r['account_type'] ?? 'N/A' ?></small>
                          </td>
                          <td class="text-end <?= $debit > 0 ? 'balance-positive' : '' ?>">
                            <?= number_format($debit, 2) ?>
                          </td>
                          <td class="text-end <?= $credit > 0 ? 'balance-negative' : '' ?>">
                            <?= number_format($credit, 2) ?>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($rows)): ?>
                        <tr>
                          <td colspan="3" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle me-2"></i>
                            No transactions found for the selected period
                          </td>
                        </tr>
                        <?php endif; ?>
                      </tbody>
                      <tfoot class="table-dark">
                        <tr>
                          <th class="text-end">Total</th>
                          <th class="text-end"><?= number_format($totalDebit, 2) ?></th>
                          <th class="text-end"><?= number_format($totalCredit, 2) ?></th>
                        </tr>
                        <tr class="<?= $totalDebit == $totalCredit ? 'table-success' : 'table-danger' ?>">
                          <td colspan="3" class="text-center fw-bold">
                            <?php if ($totalDebit == $totalCredit): ?>
                              <i class="fas fa-check-circle me-2"></i>Trial Balance is Balanced!
                            <?php else: ?>
                              <i class="fas fa-exclamation-triangle me-2"></i>
                              Trial Balance is Out of Balance by: Ksh <?= number_format(abs($totalDebit - $totalCredit), 2) ?>
                            <?php endif; ?>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
                <div class="card-footer text-muted">
                  <small>Generated on: <?= date('Y-m-d H:i:s') ?></small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal for General Ledger -->
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

  <script>
$('#trialBalance tbody').on('click', 'tr[data-account-id]', function () {
  var accountId = $(this).data('account-id');
  var accountName = $(this).find('td:first .fw-bold').text();
  if (!accountId) return;

  $('#modalAccountName').text(accountName);
  $('#ledgerModal .modal-body').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Loading ledger details...</div>');
  $('#ledgerModal').modal('show');

  // Fetch ledger data from backend
  $.ajax({
    url: '/Jengopay/landlord/pages/financials/generalledger/get_ledger.php',
    type: 'GET',
    data: { account_id: accountId },
    success: function (response) {
      let data;
      try {
        data = JSON.parse(response);
      } catch (e) {
        $('#ledgerModal .modal-body').html('<div class="alert alert-danger">Failed to load ledger data.</div>');
        return;
      }

      if (data.error) {
        $('#ledgerModal .modal-body').html('<div class="alert alert-warning">' + data.error + '</div>');
        return;
      }

      if (data.length === 0) {
        $('#ledgerModal .modal-body').html('<div class="alert alert-info">No transactions found for this account.</div>');
        return;
      }

      // Build table
      let tableHtml = `
        <table class="table table-striped table-bordered">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Reference</th>
              <th>Description</th>
              <th class="text-end">Debit</th>
              <th class="text-end">Credit</th>
              <th class="text-end">Running Balance</th>
            </tr>
          </thead>
          <tbody>
      `;

      let runningBalance = 0;
      data.forEach(row => {
        runningBalance += parseFloat(row.debit) - parseFloat(row.credit);
        tableHtml += `
          <tr>
            <td>${row.entry_date || '-'}</td>
            <td>${row.reference || '-'}</td>
            <td>${row.description || '-'}</td>
            <td class="text-end">${parseFloat(row.debit).toLocaleString()}</td>
            <td class="text-end">${parseFloat(row.credit).toLocaleString()}</td>
            <td class="text-end">${runningBalance.toLocaleString()}</td>
          </tr>
        `;
      });

      tableHtml += `</tbody></table>`;
      $('#ledgerModal .modal-body').html(tableHtml);
    },
    error: function () {
      $('#ledgerModal .modal-body').html('<div class="alert alert-danger">Error loading ledger data.</div>');
    }
  });
});


  function exportToExcel() {
    const table = document.getElementById('trialBalance');
    const wb = XLSX.utils.table_to_book(table, {sheet: "Trial Balance"});
    XLSX.writeFile(wb, 'Trial_Balance_' + new Date().toISOString().split('T')[0] + '.xlsx');
  }

  function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    doc.text('Trial Balance Report', 14, 15);
    doc.autoTable({
      html: '#trialBalance',
      startY: 25,
      theme: 'grid',
      headStyles: { fillColor: [52, 58, 64] }
    });
    
    doc.save('Trial_Balance_' + new Date().toISOString().split('T')[0] + '.pdf');
  }
  </script>
</body>
</html>