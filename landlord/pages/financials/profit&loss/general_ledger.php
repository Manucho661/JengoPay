<?php
include '../../db/connect.php';

// Get account code and name from URL parameters
$account_code = $_GET['account_code'] ?? '';
$account_name = $_GET['account_name'] ?? 'General Ledger';

// Capture filters
$from_date  = $_GET['from_date'] ?? '';
$to_date    = $_GET['to_date'] ?? '';
$building_id = $_GET['building_id'] ?? '';

$ledgerRows = [];

// Determine if it's an income account (500-599) or expense account (600-699)
if (!empty($account_code)) {
    if (substr($account_code, 0, 1) == '5') {
        // Income account - query invoice_items
        $query = "
            SELECT 
                created_at,
                invoice_number as reference,
                description,
                sub_total as amount,
                'income' as type
            FROM invoice_items 
            WHERE account_item = ?
        ";
        $params = [$account_code];
        
        // Date filter
        if (!empty($from_date) && !empty($to_date)) {
            $query .= " AND DATE(created_at) BETWEEN ? AND ?";
            $params[] = $from_date;
            $params[] = $to_date;
        }
        
        // Building filter
        if (!empty($building_id) && $building_id != 'all') {
            $query .= " AND building_id = ?";
            $params[] = $building_id;
        }
        
        $query .= " ORDER BY created_at DESC";
        
    } else {
        // Expense account - query expense_items JOINED with expenses
        $query = "
            SELECT 
                ei.created_at,
                e.expense_no as reference,
                ei.description,
                ei.item_untaxed_amount as amount,
                'expense' as type,
                e.supplier
            FROM expense_items ei
            INNER JOIN expenses e ON ei.expense_id = e.id
            WHERE ei.item_account_code = ?
        ";
        $params = [$account_code];
        
        // Date filter
        if (!empty($from_date) && !empty($to_date)) {
            $query .= " AND DATE(ei.created_at) BETWEEN ? AND ?";
            $params[] = $from_date;
            $params[] = $to_date;
        }
        
        // Building filter
        if (!empty($building_id) && $building_id != 'all') {
            $query .= " AND ei.building_id = ?";
            $params[] = $building_id;
        }
        
        $query .= " ORDER BY ei.created_at DESC";
    }

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $ledgerRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Debug: Check if we're getting data
        error_log("Account Code: " . $account_code);
        error_log("Query: " . $query);
        error_log("Params: " . implode(", ", $params));
        error_log("Rows Found: " . count($ledgerRows));
        
    } catch (PDOException $e) {
        $ledgerRows = [];
        $error = "Database error: " . $e->getMessage();
        error_log("General Ledger Error: " . $e->getMessage());
    }
}

// Buildings for dropdown filter
$buildings = $pdo->query("SELECT id, building_name FROM buildings ORDER BY building_name")->fetchAll(PDO::FETCH_ASSOC);

// Calculate running balance and totals
$runningBalance = 0;
$totalDebit = 0;
$totalCredit = 0;
$pdfData = [];

foreach ($ledgerRows as $row) {
    if ($row['type'] == 'income') {
        $runningBalance += $row['amount'];
        $totalDebit += $row['amount'];
    } else {
        $runningBalance -= $row['amount'];
        $totalCredit += $row['amount'];
    }
    
    // Prepare data for PDF export
    $pdfData[] = [
        'date' => date('Y-m-d', strtotime($row['created_at'])),
        'reference' => $row['reference'],
        'description' => $row['description'],
        'debit' => $row['type'] == 'income' ? number_format($row['amount'], 2) : '0.00',
        'credit' => $row['type'] == 'expense' ? number_format($row['amount'], 2) : '0.00',
        'balance' => number_format($runningBalance, 2)
    ];
}

// Prepare PDF data as JSON for JavaScript
$pdfDataJson = json_encode($pdfData);

// Account code to name mapping
$accountNames = [
    '500' => 'Rental Income',
    '510' => 'Water Charges (Revenue)',
    '515' => 'Garbage Charges (Revenue)',
    '505' => 'Late Payment Fees',
    '520' => 'Commissions and Management Fees',
    '525' => 'Other Income (Advertising, Penalties)',
    '600' => 'Maintenance and Repair Costs',
    '605' => 'Staff Salaries and Wages',
    '610' => 'Electricity Expense',
    '615' => 'Water Expense',
    '620' => 'Garbage Collection Expense',
    '625' => 'Internet Expense',
    '630' => 'Security Expense',
    '635' => 'Property Management Software Subscription',
    '640' => 'Marketing and Advertising Costs',
    '645' => 'Legal and Compliance Fees',
    '655' => 'Loan Interest Payments',
    '660' => 'Bank/Mpesa Charges',
    '665' => 'Other Expenses (Office, Supplies, Travel)'
];

if (empty($account_name)) {
    $account_name = $accountNames[$account_code] ?? 'General Ledger';
}
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE | Dashboard v2</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE | Dashboard v2" />
  <meta name="author" content="ColorlibHQ" />
  <meta
    name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <meta
    name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
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
  <link rel="stylesheet" href="../../../../landlord/css/adminlte.css" />
  <!-- <link rel="stylesheet" href="text.css" /> -->
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="profit&loss.css">

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

  <!-- Include jsPDF library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <!-- Include jsPDF autoTable plugin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
  <style>
    body {
      font-size: 16px;
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
      <div > <?php include_once '../../includes/sidebar.php'; ?>  </div> <!-- This is where the sidebar is inserted -->
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
            <h2 style="color:#FFC107;">General Ledger</h2>
            
  <!-- Date Filter Form -->
  <!-- Filters -->
  <form method="get" class="row g-3 mb-3">
    <div class="col-md-3">
      <label for="from_date" class="form-label">From Date</label>
      <input type="date" id="from_date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" class="form-control">
    </div>
    <div class="col-md-3">
      <label for="to_date" class="form-label">To Date</label>
      <input type="date" id="to_date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" class="form-control">
    </div>
    <div class="col-md-3">
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
    <div class="col-md-3 d-flex align-items-end">
      <button type="submit" class="btn w-100"  style="color: #FFC107; background-color: #00192D;">Filter</button>
    </div>
  </form>

  <!-- Header and Sidebar -->
   
    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-8">
              <h3 class="mb-0">
                <i class="fas fa-file-invoice-dollar" style="color:#FFC107;"></i> 
                General Ledger - <?= htmlspecialchars($account_name) ?>
              </h3>
              <p class="text-muted">Account Code: <?= htmlspecialchars($account_code) ?></p>
            </div>
            <div class="col-sm-4">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#" style="color: #00192D;"><i class="bi bi-house"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="../profit_loss.php" style="color: #00192D;">Profit & Loss</a></li>
                <li class="breadcrumb-item active">General Ledger</li>
              </ol>
            </div>
          </div>

          <!-- Filters -->
          <form method="get" class="row g-3 mb-4 mt-3 p-3 rounded" style="background-color: #f8f9fa;">
            <input type="hidden" name="account_code" value="<?= htmlspecialchars($account_code) ?>">
            <input type="hidden" name="account_name" value="<?= htmlspecialchars($account_name) ?>">
            
            <div class="col-md-3">
              <label for="from_date" class="form-label">From Date</label>
              <input type="date" id="from_date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" class="form-control">
            </div>
            
            <div class="col-md-3">
              <label for="to_date" class="form-label">To Date</label>
              <input type="date" id="to_date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" class="form-control">
            </div>
            
            <div class="col-md-3">
              <label for="building_id" class="form-label">Property</label>
              <select id="building_id" name="building_id" class="form-select">
                <option value="all">All Properties</option>
                <?php foreach ($buildings as $b): ?>
                  <option value="<?= $b['id'] ?>" <?= $building_id == $b['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['building_name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            
            <div class="col-md-3 d-flex align-items-end">
              <button type="submit" class="btn w-100 me-2" style="color: #FFC107; background-color: #00192D;">
                <i class="fas fa-filter"></i> Filter
              </button>
              <button type="button" onclick="exportToPDF()" class="btn btn-outline-secondary">
                <i class="fas fa-file-pdf"></i>
              </button>
            </div>
          </form>

          <!-- Summary Cards -->
          <div class="row mb-4">
            <div class="col-md-4">
              <div class="card bg-success text-white">
                <div class="card-body">
                  <h6 class="card-title">Total Amount</h6>
                  <h4>Ksh<?= number_format(array_sum(array_column($ledgerRows, 'amount')), 2) ?></h4>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card bg-info text-white">
                <div class="card-body">
                  <h6 class="card-title">Total Transactions</h6>
                  <h4><?= count($ledgerRows) ?></h4>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card bg-warning text-dark">
                <div class="card-body">
                  <h6 class="card-title">Date Range</h6>
                  <h6><?= $from_date ? htmlspecialchars($from_date) . ' to ' . htmlspecialchars($to_date) : 'All Dates' ?></h6>
                </div>
              </div>
            </div>
          </div>

          <!-- Ledger Table -->
          <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th>Description</th>
                <th>Debit (KSH)</th>
                <th>Credit (KSH)</th>
                <th>Type</th>
                <th>Running Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ledgerRows)): ?>
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-info-circle fa-2x text-muted mb-3"></i><br>
                        No transactions found for the selected filters.
                    </td>
                </tr>
            <?php else: ?>
                <?php 
                $displayRunningBalance = 0;
                foreach ($ledgerRows as $row): 
                    if ($row['type'] == 'income') {
                        $displayRunningBalance += $row['amount'];
                    } else {
                        $displayRunningBalance -= $row['amount'];
                    }
                ?>
                    <tr>
                        <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))) ?></td>
                        <td><?= htmlspecialchars($row['reference']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td class="text-end"><?= $row['type'] == 'income' ? number_format($row['amount'], 2) : '0.00' ?></td>
                        <td class="text-end"><?= $row['type'] == 'expense' ? number_format($row['amount'], 2) : '0.00' ?></td>
                        <td>
                            <span class="badge <?= $row['type'] == 'income' ? 'badge-income' : 'badge-expense' ?>">
                                <?= ucfirst($row['type']) ?>
                            </span>
                        </td>
                        <td class="text-end fw-bold"><?= number_format($displayRunningBalance, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot class="table-secondary">
            <tr>
                <td colspan="3" class="text-end fw-bold">Totals:</td>
                <td class="text-end fw-bold">Ksh<?= number_format($totalDebit, 2) ?></td>
                <td class="text-end fw-bold">Ksh<?= number_format($totalCredit, 2) ?></td>
                <td class="text-end fw-bold">Net:</td>
                <td class="text-end fw-bold">Ksh<?= number_format($runningBalance, 2) ?></td>
            </tr>
        </tfoot>
    </table>
</div>
        </div>
      </div>
    </main>
  </div>
  <!-- End view announcement -->
  <!-- javascript codes begin here  -->
  <!--begin::Script-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <!-- more options -->
  </script>

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

<script>
    function exportToPDF() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      
      // Title
      doc.setFontSize(16);
      doc.setTextColor(0, 25, 45);
      doc.text('General Ledger - <?= addslashes($account_name) ?>', 14, 15);
      
      // Account info
      doc.setFontSize(12);
      doc.setTextColor(100, 100, 100);
      doc.text('Account Code: <?= $account_code ?>', 14, 25);
      doc.text('Date Range: <?= addslashes($from_date ? $from_date . " to " . $to_date : "All Dates") ?>', 14, 32);
      
      // Table data from PHP
      const tableData = [
        ['Date', 'Reference', 'Description', 'Property', 'Debit', 'Credit', 'Balance']
      ];
      
      // Add data rows from PHP JSON
      const pdfData = <?= $pdfDataJson ?>;
      pdfData.forEach(row => {
        tableData.push([
          row.date,
          row.reference,
          row.description,
          row.building,
          row.debit,
          row.credit,
          row.balance
        ]);
      });
      
      // Table
      doc.autoTable({
        startY: 40,
        head: [tableData[0]],
        body: tableData.slice(1),
        headStyles: { fillColor: [0, 25, 45] },
        foot: [
          ['', '', '', 'Total:', 'Ksh<?= number_format($totalDebit, 2) ?>', 'Ksh<?= number_format($totalCredit, 2) ?>', 'Ksh<?= number_format($runningBalance, 2) ?>']
        ],
        footStyles: { fillColor: [200, 200, 200], textColor: [0, 0, 0] }
      });
      
      doc.save('General_Ledger_<?= $account_code ?>_<?= date('Y-m-d') ?>.pdf');
    }
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="../../../../landlord/js/adminlte.js"></script>

  <!--end::Script-->
</body>
<!--end::Body-->
</html>
