<?php
include '../../db/connect.php';

// Capture filters
$from_date  = $_GET['from_date'] ?? '';
$to_date    = $_GET['to_date'] ?? '';
$account_id = $_GET['account_id'] ?? '';

$where  = [];
$params = [];

// Date filter
if (!empty($from_date) && !empty($to_date)) {
  $where[] = "DATE(je.created_at) BETWEEN :from AND :to";
  $params[':from'] = $from_date;
  $params[':to']   = $to_date;
}

// Account filter
if (!empty($account_id)) {
  $where[] = "jl.account_id = :account_id";
  $params[':account_id'] = $account_id;
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

// General Ledger query
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
    ORDER BY je.created_at, je.id
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ledgerRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Accounts for dropdown filter
$accounts = $pdo->query("
    SELECT account_code, account_name 
    FROM chart_of_accounts 
    ORDER BY account_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

$runningBalance = 0;
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>JengoPay | General Ledger</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="JengoPay | General Ledger" />
  <meta name="author" content="JengoPay" />
  <meta
    name="description"
    content="JengoPay General Ledger - Track and manage all financial transactions." />
  <meta
    name="keywords"
    content="jengopay, finance, accounting, general ledger, transactions" />
  <!--end::Primary Meta Tags-->
  <!--begin::Fonts-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
    crossorigin="anonymous" />
  <!--end::Fonts-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
    integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
    crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="/jengopay/landlord/css/adminlte.css" />
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <!-- scripts for data_table -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

  <!-- Include XLSX and FileSaver.js for Excel export -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

  <!-- Include jsPDF library (latest version) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <!-- Include jsPDF autoTable plugin (latest compatible version with jsPDF 2.5.1) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

  <style>
    :root {
      --primary-color: #00192D;
      --accent-color: #FFC107;
      --light-bg: #f8f9fa;
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    body {
      font-size: 16px;
      background-color: var(--light-bg);
    }
    
    .app-wrapper {
      background-color: var(--light-bg);
    }
    
    .app-content-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, #003053 100%);
      color: white;
      padding: 1.5rem 0;
      margin-bottom: 1.5rem;
      border-radius: 0 0 10px 10px;
    }
    
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: var(--card-shadow);
      margin-bottom: 1.5rem;
    }
    
    .card-header {
      background-color: white;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      padding: 1rem 1.5rem;
      border-radius: 10px 10px 0 0 !important;
      font-weight: 600;
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
      background-color: #00243d;
      border-color: #00243d;
    }
    
    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .table thead th {
      background-color: var(--primary-color);
      color: white;
      border: none;
      padding: 1rem;
    }
    
    .table tbody td {
      padding: 0.75rem 1rem;
      vertical-align: middle;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0, 25, 45, 0.03);
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(0, 25, 45, 0.08);
    }
    
    .filter-card {
      background-color: white;
      border-radius: 10px;
      padding: 1.5rem;
      box-shadow: var(--card-shadow);
      margin-bottom: 1.5rem;
    }
    
    .stats-card {
      text-align: center;
      padding: 1.5rem;
      border-radius: 10px;
      color: white;
      margin-bottom: 1.5rem;
    }
    
    .stats-card.primary {
      background: linear-gradient(135deg, var(--primary-color) 0%, #003053 100%);
    }
    
    .stats-card.success {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    .stats-card.warning {
      background: linear-gradient(135deg, var(--accent-color) 0%, #ffcd39 100%);
    }
    
    .stats-card.info {
      background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
    }
    
    .stats-card .stats-value {
      font-size: 2rem;
      font-weight: 700;
      margin: 0.5rem 0;
    }
    
    .stats-card .stats-label {
      font-size: 0.9rem;
      opacity: 0.9;
    }
    
    .form-control, .form-select {
      border-radius: 6px;
      padding: 0.6rem 0.75rem;
      border: 1px solid #dee2e6;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(0, 25, 45, 0.25);
    }
    
    .export-buttons {
      display: flex;
      gap: 0.5rem;
      justify-content: flex-end;
      margin-bottom: 1rem;
    }
    
    .running-balance-positive {
      color: #28a745;
      font-weight: 600;
    }
    
    .running-balance-negative {
      color: #dc3545;
      font-weight: 600;
    }
    
    .badge-account {
      background-color: rgba(0, 25, 45, 0.1);
      color: var(--primary-color);
      font-weight: 500;
      padding: 0.3rem 0.6rem;
      border-radius: 4px;
    }
    
    .page-title {
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
      opacity: 0.8;
      margin-bottom: 0;
    }
    
    .filter-actions {
      display: flex;
      gap: 0.5rem;
      justify-content: flex-end;
    }
    
    @media (max-width: 768px) {
      .export-buttons {
        justify-content: flex-start;
      }
      
      .stats-card .stats-value {
        font-size: 1.5rem;
      }
      
      .filter-actions {
        flex-direction: column;
      }
      
      .filter-actions .btn {
        width: 100%;
      }
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <!--begin::Header-->
    <nav class="app-header navbar navbar-expand bg-body">
      <!--begin::Container-->
      <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
          <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
          <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
        </ul>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
          <!--begin::Navbar Search-->
          <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
              <i class="bi bi-search"></i>
            </a>
          </li>
          <!--end::Navbar Search-->
          <!--begin::Messages Dropdown Menu-->
          <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#">
              <i class="bi bi-chat-text"></i>
              <span class="navbar-badge badge text-bg-danger">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <a href="#" class="dropdown-item">
                <!--begin::Message-->
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <img
                      src="../../../dist/assets/img/user1-128x128.jpg"
                      alt="User Avatar"
                      class="img-size-50 rounded-circle me-3" />
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="dropdown-item-title">
                      Brad Diesel
                      <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span>
                    </h3>
                    <p class="fs-7">Call me whenever you can...</p>
                    <p class="fs-7 text-secondary">
                      <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                    </p>
                  </div>
                </div>
                <!--end::Message-->
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <!--begin::Message-->
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <img
                      src="../../../dist/assets/img/user8-128x128.jpg"
                      alt="User Avatar"
                      class="img-size-50 rounded-circle me-3" />
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="dropdown-item-title">
                      John Pierce
                      <span class="float-end fs-7 text-secondary">
                        <i class="bi bi-star-fill"></i>
                      </span>
                    </h3>
                    <p class="fs-7">I got your message bro</p>
                    <p class="fs-7 text-secondary">
                      <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                    </p>
                  </div>
                </div>
                <!--end::Message-->
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <!--begin::Message-->
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <img
                      src="../../../dist/assets/img/user3-128x128.jpg"
                      alt="User Avatar"
                      class="img-size-50 rounded-circle me-3" />
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="dropdown-item-title">
                      Nora Silvester
                      <span class="float-end fs-7 text-warning">
                        <i class="bi bi-star-fill"></i>
                      </span>
                    </h3>
                    <p class="fs-7">The subject goes here</p>
                    <p class="fs-7 text-secondary">
                      <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                    </p>
                  </div>
                </div>
                <!--end::Message-->
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
          </li>
          <!--end::Messages Dropdown Menu-->
          <!--begin::Notifications Dropdown Menu-->
          <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#">
              <i class="bi bi-bell-fill"></i>
              <span class="navbar-badge badge text-bg-warning">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <span class="dropdown-item dropdown-header">15 Notifications</span>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="bi bi-envelope me-2"></i> 4 new messages
                <span class="float-end text-secondary fs-7">3 mins</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="bi bi-people-fill me-2"></i> 8 friend requests
                <span class="float-end text-secondary fs-7">12 hours</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                <span class="float-end text-secondary fs-7">2 days</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
            </div>
          </li>
          <!--end::Notifications Dropdown Menu-->
          <!--begin::Fullscreen Toggle-->
          <li class="nav-item">
            <a class="nav-link" href="#" data-lte-toggle="fullscreen">
              <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
              <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
            </a>
          </li>
          <!--end::Fullscreen Toggle-->
          <!--begin::User Menu Dropdown-->
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <img
                src="17.jpg"
                class="user-image rounded-circle shadow"
                alt="User Image" />
              <span class="d-none d-md-inline"> <b>JENGO PAY</b> </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <!--begin::User Image-->
              <li class="user-header text-bg-primary">
                <img
                  src="../../dist/assets/img/user2-160x160.jpg"
                  class="rounded-circle shadow"
                  alt="User Image" />
                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2023</small>
                </p>
              </li>
              <!--end::User Image-->
              <!--begin::Menu Body-->
              <li class="user-body">
                <!--begin::Row-->
                <div class="row">
                  <div class="col-4 text-center"><a href="#">Followers</a></div>
                  <div class="col-4 text-center"><a href="#">Sales</a></div>
                  <div class="col-4 text-center"><a href="#">Friends</a></div>
                </div>
                <!--end::Row-->
              </li>
              <!--end::Menu Body-->
              <!--begin::Menu Footer-->
              <li class="user-footer">
                <a href="#" class="btn btn-default btn-flat">Profile</a>
                <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
              </li>
              <!--end::Menu Footer-->
            </ul>
          </li>
          <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
      </div>
      <!--end::Container-->
    </nav>
    <!--end::Header-->
    <!--begin::Sidebar-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
          <!--begin::Brand Text-->
          <span class="brand-text font-weight-light"><b class="p-2"
              style="background-color:#FFC107; border:2px solid #FFC107; border-top-left-radius:5px; font-weight:bold; color:#00192D;">BT</b><b
              class="p-2"
              style=" border-bottom-right-radius:5px; font-weight:bold; border:2px solid #FFC107; color: #FFC107;">JENGOPAY</b></span>
        </a>
        </span>
        <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
      </div>
      <!--end::Sidebar Brand-->
      <!--begin::Sidebar Wrapper-->
      <div> <?php include_once '../../includes/sidebar.php'; ?> </div> <!-- This is where the sidebar is inserted -->
      <!--end::Sidebar Wrapper-->
    </aside>
    <!--end::Sidebar-->
    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6">
              <h1 class="page-title">General Ledger</h1>
              <p class="page-subtitle">Track and manage all financial transactions</p>
            </div>
            <div class="col-sm-6">
              <div class="export-buttons">
                <button class="btn btn-light" onclick="exportToExcel()">
                  <i class="fas fa-file-excel me-2"></i> Export Excel
                </button>
                <button class="btn btn-light" onclick="exportToPDF()">
                  <i class="fas fa-file-pdf me-2"></i> Export PDF
                </button>
              </div>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content Header-->
      
      <!--begin::App Content-->
      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          
          <!-- Stats Cards -->
          <div class="row mb-4">
            <div class="col-md-3">
              <div class="stats-card primary">
                <i class="fas fa-exchange-alt fa-2x"></i>
                <div class="stats-value"><?= count($ledgerRows) ?></div>
                <div class="stats-label">Total Transactions</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stats-card success">
                <i class="fas fa-arrow-down fa-2x"></i>
                <div class="stats-value">
                  <?php
                  $totalDebit = 0;
                  foreach ($ledgerRows as $row) {
                    $totalDebit += $row['debit'];
                  }
                  echo 'KSH ' . number_format($totalDebit, 2);
                  ?>
                </div>
                <div class="stats-label">Total Debit</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stats-card warning">
                <i class="fas fa-arrow-up fa-2x"></i>
                <div class="stats-value">
                  <?php
                  $totalCredit = 0;
                  foreach ($ledgerRows as $row) {
                    $totalCredit += $row['credit'];
                  }
                  echo 'KSH ' . number_format($totalCredit, 2);
                  ?>
                </div>
                <div class="stats-label">Total Credit</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stats-card info">
                <i class="fas fa-balance-scale fa-2x"></i>
                <div class="stats-value">
                  <?php
                  $netBalance = $totalDebit - $totalCredit;
                  echo 'KSH ' . number_format(abs($netBalance), 2);
                  ?>
                </div>
                <div class="stats-label">Net Balance</div>
              </div>
            </div>
          </div>
          
          <!-- Filters Card -->
          <div class="filter-card">
            <h5 class="mb-3">Filter Transactions</h5>
            <form method="get" class="row g-3">
              <div class="col-md-3">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" id="from_date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" class="form-control">
              </div>
              <div class="col-md-3">
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" id="to_date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" class="form-control">
              </div>
              <div class="col-md-4">
                <label for="account_id" class="form-label">Account</label>
                <select id="account_id" name="account_id" class="form-select">
                  <option value="">-- All Accounts --</option>
                  <?php foreach ($accounts as $acc): ?>
                    <option value="<?= $acc['account_code'] ?>" <?= $account_id == $acc['account_code'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($acc['account_name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2 d-flex align-items-end">
                <div class="filter-actions w-100">
                  <button type="submit" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-filter me-2"></i> Filter
                  </button>
                  <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="fas fa-redo me-2"></i> Reset
                  </button>
                </div>
              </div>
            </form>
          </div>
          
          <!-- Ledger Table -->
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">General Ledger Entries</h5>
              <span class="badge bg-primary"><?= count($ledgerRows) ?> entries</span>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Reference</th>
                      <th>Description</th>
                      <th>Account</th>
                      <th class="text-end">Debit (KSH)</th>
                      <th class="text-end">Credit (KSH)</th>
                      <th class="text-end">Running Balance</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $runningBalance = 0;
                    foreach ($ledgerRows as $row): 
                      $runningBalance += $row['debit'] - $row['credit'];
                      $balanceClass = $runningBalance >= 0 ? 'running-balance-positive' : 'running-balance-negative';
                    ?>
                      <tr>
                        <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))) ?></td>
                        <td>
                          <span class="badge-account"><?= htmlspecialchars($row['reference']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                          <div><?= htmlspecialchars($row['account_name']) ?></div>
                          <small class="text-muted"><?= htmlspecialchars($row['account_code']) ?></small>
                        </td>
                        <td class="text-end"><?= number_format($row['debit'], 2) ?></td>
                        <td class="text-end"><?= number_format($row['credit'], 2) ?></td>
                        <td class="text-end <?= $balanceClass ?>"><?= number_format($runningBalance, 2) ?></td>
                      </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($ledgerRows)): ?>
                      <tr>
                        <td colspan="7" class="text-center py-4">
                          <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                          <p class="text-muted">No transactions found for the selected filters</p>
                        </td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content-->
    </main>
    <!--end::App Main-->
  </div>
  <!--end::App Wrapper-->

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
  <script src="../../../../landlord/js/adminlte.js"></script>

  <script>
    // Initialize DataTable
    $(document).ready(function() {
      $('table').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
          search: "Search transactions:",
          lengthMenu: "Show _MENU_ entries",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
        }
      });
    });

    function exportToExcel() {
      const table = document.querySelector('table');
      const wb = XLSX.utils.table_to_book(table, {
        sheet: "General Ledger"
      });
      XLSX.writeFile(wb, 'General_Ledger_' + new Date().toISOString().split('T')[0] + '.xlsx');
    }

    function exportToPDF() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      
      // Add title
      doc.setFontSize(16);
      doc.text('General Ledger Report', 14, 15);
      
      // Add date range if available
      const fromDate = document.getElementById('from_date').value;
      const toDate = document.getElementById('to_date').value;
      if (fromDate && toDate) {
        doc.setFontSize(10);
        doc.text(`Date Range: ${fromDate} to ${toDate}`, 14, 22);
      }
      
      // Add table
      doc.autoTable({
        html: 'table',
        startY: 30,
        theme: 'grid',
        headStyles: {
          fillColor: [0, 25, 45]
        },
        styles: {
          fontSize: 8,
          cellPadding: 2
        }
      });
      
      doc.save('General_Ledger_' + new Date().toISOString().split('T')[0] + '.pdf');
    }

    // Reset filters function
    function resetFilters() {
      // Clear form inputs
      document.getElementById('from_date').value = '';
      document.getElementById('to_date').value = '';
      document.getElementById('account_id').value = '';
      
      // Submit the form to reload the page without filters
      window.location.href = window.location.pathname;
    }
  </script>
</body>
<!--end::Body-->

</html>