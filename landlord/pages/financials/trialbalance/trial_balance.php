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
    :root {
      --primary-dark: #00192D;
      --primary-gold: #FFC107;
      --success-light: #e8f5e8;
      --danger-light: #fde8e8;
    }
    
    body { 
      font-size: 16px; 
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
    }
    
    .app-wrapper {
      background: transparent;
    }
    
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    
    .card-header {
      background: linear-gradient(135deg, var(--primary-dark) 0%, #002c4d 100%);
      color: white;
      padding: 1.25rem 1.5rem;
      border-bottom: 3px solid var(--primary-gold);
    }
    
    .table th { 
      background: linear-gradient(135deg, var(--primary-dark) 0%, #002c4d 100%);
      color: var(--primary-gold);
      font-weight: 600;
      border: none;
      padding: 1rem 0.75rem;
      text-align: center;
    }
    
    .table td {
      padding: 0.85rem 0.75rem;
      vertical-align: middle;
      border-color: #e9ecef;
    }
    
    .table tbody tr {
      transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
      background-color: #f8f9fa;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .balance-positive { 
      color: #28a745; 
      font-weight: 600;
      background-color: var(--success-light);
      border-radius: 4px;
      padding: 0.25rem 0.5rem;
    }
    
    .balance-negative { 
      color: #dc3545; 
      font-weight: 600;
      background-color: var(--danger-light);
      border-radius: 4px;
      padding: 0.25rem 0.5rem;
    }
    
    .account-code { 
      color: #6c757d; 
      font-size: 0.85em;
      display: block;
      margin-top: 0.25rem;
    }
    
    .filter-section {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      margin-bottom: 2rem;
    }
    
    .btn-primary-custom {
      background: linear-gradient(135deg, var(--primary-dark) 0%, #002c4d 100%);
      color: var(--primary-gold);
      border: none;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,25,45,0.3);
      color: var(--primary-gold);
    }
    
    .form-control {
      border-radius: 8px;
      border: 1px solid #dee2e6;
      padding: 0.75rem;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      border-color: var(--primary-dark);
      box-shadow: 0 0 0 0.2rem rgba(0,25,45,0.1);
    }
    
    .table-responsive {
      border-radius: 12px;
      overflow: hidden;
    }
    
    .status-balanced {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
    }
    
    .status-unbalanced {
      background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
      color: white;
    }
    
    .dropdown-menu {
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      border: none;
    }
    
    .dropdown-item {
      padding: 0.5rem 1rem;
      transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
      background-color: #f8f9fa;
      color: var(--primary-dark);
    }
    
    .main-title {
      color: var(--primary-dark);
      font-weight: 700;
      text-align: center;
      margin-bottom: 2rem;
      position: relative;
      padding-bottom: 1rem;
    }
    
    .main-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-dark) 0%, var(--primary-gold) 100%);
      border-radius: 2px;
    }
    
    .total-row {
      font-weight: 700;
      background-color: #f8f9fa !important;
    }
    
    .footer-info {
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
      padding: 1rem 1.5rem;
      font-size: 0.9em;
    }
    
    .nav-tabs .nav-link.active {
      border-bottom: 3px solid var(--primary-gold);
      font-weight: 600;
    }
    
    .loading-spinner {
      display: none;
      text-align: center;
      padding: 2rem;
    }
    
    .date-validation {
      font-size: 0.875em;
      margin-top: 0.25rem;
    }
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

<main class="container py-4">
  <h1 class="main-title">Trial Balance Report</h1>

  <!-- FILTER FORM -->
  <div class="filter-section">
    <form method="GET" class="row g-3 align-items-end" id="filterForm">
      <div class="col-md-4">
        <label class="form-label fw-semibold">From Date</label>
        <input type="date" name="from_date" id="from_date" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>" class="form-control">
        <div class="date-validation text-danger" id="from_date_error"></div>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">To Date</label>
        <input type="date" name="to_date" id="to_date" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>" class="form-control">
        <div class="date-validation text-danger" id="to_date_error"></div>
      </div>
      <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-primary-custom" id="filterBtn">
          <i class="fas fa-filter me-2"></i>Apply Filters
        </button>
      </div>
      <div class="col-md-2 d-grid">
        <button type="button" class="btn btn-outline-secondary" id="resetBtn">
          <i class="fas fa-redo me-2"></i>Reset
        </button>
      </div>
    </form>
  </div>

  <!-- Loading Spinner -->
  <div class="loading-spinner" id="loadingSpinner">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mt-2">Loading trial balance data...</p>
  </div>

  <div class="card" id="resultsCard">
    <div class="card-header">
      <h5 class="card-title mb-0"><i class="fas fa-balance-scale me-2"></i>Trial Balance Summary</h5>
      <?php if (!empty($_GET['from_date']) && !empty($_GET['to_date'])): ?>
        <small class="text-white-50">Showing data from <?= htmlspecialchars($_GET['from_date']) ?> to <?= htmlspecialchars($_GET['to_date']) ?></small>
      <?php endif; ?>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table id="trialBalance" class="table table-hover mb-0">
          <thead>
            <tr>
              <th rowspan="2" class="align-middle">Account</th>
              <th colspan="2" class="text-center">Initial Balance</th>
              <th colspan="2" class="text-center"><?= !empty($_GET['from_date']) && !empty($_GET['to_date']) ? date('M Y', strtotime($_GET['from_date'])) : 'Period' ?></th>
              <th colspan="2" class="text-center">End Balance</th>
            </tr>
            <tr>
              <th class="text-center">Debit</th>
              <th class="text-center">Credit</th>
              <th class="text-center">Debit</th>
              <th class="text-center">Credit</th>
              <th class="text-center">Debit</th>
              <th class="text-center">Credit</th>
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
    <?= htmlspecialchars($r['account_name']) ?>
  </div>

  <!-- Dots dropdown -->
  <div class="dropdown">
    <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
      <i class="fas fa-ellipsis-v"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li>
        <a class="dropdown-item" href="/Jengopay/landlord/pages/financials/trialbalance/general_ledger.php?account=<?= urlencode($r['account_name']) ?>&from_date=<?= htmlspecialchars($_GET['from_date'] ?? '') ?>&to_date=<?= htmlspecialchars($_GET['to_date'] ?? '') ?>">
          <i class="fas fa-book me-2"></i>View General Ledger
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
          <tfoot>
            <tr class="total-row">
              <th>Total</th>
              <th class="text-end"><?= number_format($totalInitialDebit,2) ?></th>
              <th class="text-end"><?= number_format($totalInitialCredit,2) ?></th>
              <th class="text-end"><?= number_format($totalPeriodDebit,2) ?></th>
              <th class="text-end"><?= number_format($totalPeriodCredit,2) ?></th>
              <th class="text-end"><?= number_format($totalEndDebit,2) ?></th>
              <th class="text-end"><?= number_format($totalEndCredit,2) ?></th>
            </tr>
            <tr class="<?= abs($totalEndDebit - $totalEndCredit) < 0.01 ? 'status-balanced' : 'status-unbalanced' ?>">
              <td colspan="7" class="text-center fw-bold py-3">
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

    <div class="footer-info d-flex justify-content-between">
      <small><i class="fas fa-calendar me-1"></i>Generated on: <?= date('Y-m-d H:i:s') ?></small>
      <small>
        <i class="fas fa-clock me-1"></i>
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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const filterForm = document.getElementById('filterForm');
      const fromDateInput = document.getElementById('from_date');
      const toDateInput = document.getElementById('to_date');
      const filterBtn = document.getElementById('filterBtn');
      const resetBtn = document.getElementById('resetBtn');
      const loadingSpinner = document.getElementById('loadingSpinner');
      const resultsCard = document.getElementById('resultsCard');
      
      // Set default dates if not set
      if (!fromDateInput.value) {
        const firstDay = new Date();
        firstDay.setDate(1);
        fromDateInput.valueAsDate = firstDay;
      }
      
      if (!toDateInput.value) {
        toDateInput.valueAsDate = new Date();
      }
      
      // Form validation
      filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const fromDate = fromDateInput.value;
        const toDate = toDateInput.value;
        let isValid = true;
        
        // Clear previous errors
        document.getElementById('from_date_error').textContent = '';
        document.getElementById('to_date_error').textContent = '';
        
        // Validate dates
        if (fromDate && toDate) {
          if (new Date(fromDate) > new Date(toDate)) {
            document.getElementById('from_date_error').textContent = 'From date cannot be after To date';
            isValid = false;
          }
        }
        
        if (!fromDate && toDate) {
          document.getElementById('from_date_error').textContent = 'From date is required when To date is set';
          isValid = false;
        }
        
        if (fromDate && !toDate) {
          document.getElementById('to_date_error').textContent = 'To date is required when From date is set';
          isValid = false;
        }
        
        if (isValid) {
          // Show loading spinner
          loadingSpinner.style.display = 'block';
          resultsCard.style.display = 'none';
          filterBtn.disabled = true;
          
          // Submit the form
          setTimeout(() => {
            filterForm.submit();
          }, 500);
        }
      });
      
      // Reset button functionality
      resetBtn.addEventListener('click', function() {
        fromDateInput.value = '';
        toDateInput.value = '';
        document.getElementById('from_date_error').textContent = '';
        document.getElementById('to_date_error').textContent = '';
        
        // Show loading spinner
        loadingSpinner.style.display = 'block';
        resultsCard.style.display = 'none';
        filterBtn.disabled = true;
        
        // Submit the form to show all data
        setTimeout(() => {
          filterForm.submit();
        }, 500);
      });
      
      // Auto-submit when both dates are selected
      fromDateInput.addEventListener('change', function() {
        if (fromDateInput.value && toDateInput.value) {
          filterForm.dispatchEvent(new Event('submit'));
        }
      });
      
      toDateInput.addEventListener('change', function() {
        if (fromDateInput.value && toDateInput.value) {
          filterForm.dispatchEvent(new Event('submit'));
        }
      });
      
      // Initialize DataTable if there are rows
      const table = document.getElementById('trialBalance');
      if (table && table.rows.length > 2) {
        $('#trialBalance').DataTable({
          paging: false,
          searching: true,
          ordering: true,
          info: false,
          dom: '<"row"<"col-sm-12"f>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12"i>>',
          language: {
            search: "Search accounts:",
            zeroRecords: "No matching accounts found"
          }
        });
      }
    });
  </script>
</body>
</html>