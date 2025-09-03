<?php
include '../../db/connect.php'; // adjust path as needed

try {
  // Rent Total (account_item = 500)
  $stmtRent = $pdo->prepare("
     SELECT SUM(sub_total) AS rent_total
     FROM invoice_items
     WHERE account_item = '500'
 ");
  $stmtRent->execute();
  $rentResult = $stmtRent->fetch(PDO::FETCH_ASSOC);
  $rentTotal = $rentResult['rent_total'] ?? 0;
  $formattedRent = number_format($rentTotal, 2);


  // Water Charges (510)
  $stmtWater = $pdo->prepare("
        SELECT SUM(sub_total) AS water_total
        FROM invoice_items
        WHERE account_item = '510'
    ");
  $stmtWater->execute();
  $waterTotal = $stmtWater->fetchColumn() ?? 0;
  $formattedWater = number_format($waterTotal, 2);

  // Garbage Collection Fees (515)
  $stmtGarbage = $pdo->prepare("
        SELECT SUM(sub_total) AS garbage_total
        FROM invoice_items
        WHERE account_item = '515'
    ");
  $stmtGarbage->execute();
  $garbageTotal = $stmtGarbage->fetchColumn() ?? 0;
  $formattedGarbage = number_format($garbageTotal, 2);


  // Late Payment Fees (account code 505)
  $stmtLateFees = $pdo->prepare("
        SELECT SUM(sub_total) AS late_fees_total
        FROM invoice_items
        WHERE account_item = '505'
        ");
  $stmtLateFees->execute();
  $lateFees = $stmtLateFees->fetchColumn() ?? 0;
  $formattedLateFees = number_format($lateFees, 2);

  // Commissions and Management Fees (account code 520)
  $stmtManagementFees = $pdo->prepare("
        SELECT SUM(sub_total) AS management_fees_total
        FROM invoice_items
        WHERE account_item = '520'
        ");
  $stmtManagementFees->execute();
  $managementFees = $stmtManagementFees->fetchColumn() ?? 0;
  $formattedManagementFees = number_format($managementFees, 2);

  // Other Income (Advertising, Penalties) (account code 525)
  $stmtOtherIncome = $pdo->prepare("
        SELECT SUM(sub_total) AS other_income_total
        FROM invoice_items
        WHERE account_item = '525'
        ");
  $stmtOtherIncome->execute();
  $otherIncome = $stmtOtherIncome->fetchColumn() ?? 0;
  $formattedOtherIncome = number_format($otherIncome, 2);

  // Updated Total Income Calculation
  $totalIncome =
    $rentTotal +
    $waterTotal +
    $garbageTotal +
    $lateFees +
    $managementFees +
    $otherIncome;

  $formattedTotalIncome = number_format($totalIncome, 2);


  // Get total Maintenance and Repair Costs using account code 600
  $stmt = $pdo->prepare("
        SELECT SUM(item_untaxed_amount) AS maintenance_total
        FROM expense_items
        WHERE item_account_code = '600'
      ");
  $stmt->execute();
  $maintenanceTotal = $stmt->fetchColumn() ?? 0;

  // Format with thousands separator and 2 decimal places
  $formattedMaintenance = number_format($maintenanceTotal, 2);


  // Fetch total for Staff Salaries and Wages using account code 605
  $stmt = $pdo->prepare("

     SELECT SUM(item_untaxed_amount) AS salary_total
     FROM expense_items
     WHERE item_account_code = '605'
 ");
  $stmt->execute();
  $salaryTotal = $stmt->fetchColumn() ?? 0;

  // Format the salary total with 2 decimal places and thousands separator
  $formattedSalaryTotal = number_format($salaryTotal, 2);

  // Fetch total Electricity Expense using account code 610
  $stmt = $pdo->prepare("
 SELECT SUM(item_untaxed_amount) AS electricity_total
 FROM expense_items
 WHERE item_account_code = '610'
");
  $stmt->execute();
  $electricityTotal = $stmt->fetchColumn() ?? 0;

  // Format result
  $formattedElectricity = number_format($electricityTotal, 2);

  // Fetch total Water Expense using account code 615
  $stmt = $pdo->prepare("
SELECT SUM(item_untaxed_amount) AS water_expense_total
FROM expense_items
WHERE item_account_code = '615'
");
  $stmt->execute();
  $waterExpenseTotal = $stmt->fetchColumn() ?? 0;

  // Format result
  $formattedWaterExpense = number_format($waterExpenseTotal, 2);

  // Fetch total Garbage Collection Expense using account code 620
  $stmt = $pdo->prepare("
SELECT SUM(item_untaxed_amount) AS garbage_expense_total
FROM expense_items
WHERE item_account_code = '620'
");
  $stmt->execute();
  $garbageExpenseTotal = $stmt->fetchColumn() ?? 0;

  // Format result
  $formattedGarbageExpense = number_format($garbageExpenseTotal, 2);

  // Fetch total Internet Expense using account code 625
  $stmt = $pdo->prepare("
SELECT SUM(item_untaxed_amount) AS internet_expense_total
FROM expense_items
WHERE item_account_code = '625'
");
  $stmt->execute();
  $internetExpenseTotal = $stmt->fetchColumn() ?? 0;

  // Format the result
  $formattedInternetExpense = number_format($internetExpenseTotal, 2);


  // Fetch total Security Expense using account code 630
  $stmt = $pdo->prepare("
SELECT SUM(item_untaxed_amount) AS security_expense_total
FROM expense_items
WHERE item_account_code = '630'
");
  $stmt->execute();
  $securityExpenseTotal = $stmt->fetchColumn() ?? 0;

  // Format the result
  $formattedSecurityExpense = number_format($securityExpenseTotal, 2);


  // Fetch total for Property Management Software Subscription using account code 635
  $stmt = $pdo->prepare("
  SELECT SUM(item_untaxed_amount) AS software_expense_total
  FROM expense_items
  WHERE item_account_code = '635'
");
  $stmt->execute();
  $softwareExpenseTotal = $stmt->fetchColumn() ?? 0;

  // Format the result
  $formattedSoftwareExpense = number_format($softwareExpenseTotal, 2);

  // Fetch total Marketing and Advertising Costs using account code 640
  $stmt = $pdo->prepare("
 SELECT SUM(item_untaxed_amount) AS marketing_expense_total
 FROM expense_items
 WHERE item_account_code = '640'
");
  $stmt->execute();
  $marketingExpenseTotal = $stmt->fetchColumn() ?? 0;

  // Format the result
  $formattedMarketingExpense = number_format($marketingExpenseTotal, 2);

  // Fetch total Legal and Compliance Fees using account code 645
  $stmt = $pdo->prepare("
 SELECT SUM(item_untaxed_amount) AS legal_expense_total
 FROM expense_items
 WHERE item_account_code = '645'
");
  $stmt->execute();
  $legalExpenseTotal = $stmt->fetchColumn() ?? 0;

  // Format the result
  $formattedLegalExpense = number_format($legalExpenseTotal, 2);

  // Fetch total Loan Interest Payments using account code 655
  $stmt = $pdo->prepare("
        SELECT SUM(item_untaxed_amount) AS loan_interest_total
        FROM expense_items
        WHERE item_account_code = '655'
    ");
  $stmt->execute();
  $loanInterestTotal = $stmt->fetchColumn() ?? 0;

  // Format the result
  $formattedLoanInterest = number_format($loanInterestTotal, 2);

  // Fetch total Bank/Mpesa Charges using account code 660
  $stmt = $pdo->prepare("
        SELECT SUM(item_untaxed_amount) AS bank_charges_total
        FROM expense_items
        WHERE item_account_code = '660'
    ");
  $stmt->execute();
  $bankChargesTotal = $stmt->fetchColumn() ?? 0;

  // Format the result
  $formattedBankCharges = number_format($bankChargesTotal, 2);

  // Fetch total for Other Expenses using account code 665
  $stmt = $pdo->prepare("
      SELECT SUM(item_untaxed_amount) AS other_expense_total
      FROM expense_items
      WHERE item_account_code = '665'
  ");
  $stmt->execute();
  $otherExpenseTotal = $stmt->fetchColumn() ?? 0;

  // Format the result
  $formattedOtherExpense = number_format($otherExpenseTotal, 2);

  // Total Expenses Calculation
  $totalExpenses =
    $maintenanceTotal +
    $salaryTotal +
    $electricityTotal +
    $waterExpenseTotal +
    $garbageExpenseTotal +
    $internetExpenseTotal +
    $securityExpenseTotal +
    $softwareExpenseTotal +
    $marketingExpenseTotal +
    $legalExpenseTotal +
    $loanInterestTotal +
    $bankChargesTotal +
    $otherExpenseTotal;

  $formattedTotalExpenses = number_format($totalExpenses, 2);

  // Net Profit Calculation
  $netProfit = $totalIncome - $totalExpenses;
  $formattedNetProfit = number_format($netProfit, 2);
} catch (PDOException $e) {
  echo "Database error: " . $e->getMessage();
  exit;
}
?>

<?php
include '../../db/connect.php';

// Calculate INCOME from invoice_items
$incomeStmt = $pdo->query("SELECT SUM(total) AS income FROM invoice_items");
$income = $incomeStmt->fetch(PDO::FETCH_ASSOC)['income'] ?? 0;

// Calculate EXPENSES from expense_items
$expenseStmt = $pdo->query("SELECT SUM(item_total) AS expenses FROM expense_items");
$expenses = $expenseStmt->fetch(PDO::FETCH_ASSOC)['expenses'] ?? 0;

// Calculate NET PROFIT
$netProfit = $income - $expenses;
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


          <div class="row">
            <div class="col-sm-8">
              <h3 class="mb-0 contact_section_header"> <i class="fas fa-file-invoice-dollar" style=" color:#FFC107;"></i> Profit & Loss</h3>
              <div class="row Summary mt-6">
                <!-- Summary -->
                <div class="col-md-12 ">

                  <div class="summary-section text-center p-2 row g-3">
                    <div class="col-6 col-md-4 ">
                      <div class="summary-item assets">

                        <div class="label "> <i class="fas fa-calculator"></i> INCOME </div>
                        <div class="value"><b>Ksh<?= $formattedTotalIncome ?></b></div>
                      </div>

                    </div>

                    <div class="col-6 col-md-4 ">
                      <div class="summary-item liabilities">


                        <div class="label"> <i class="fas fa-calculator"></i>EXPENSES</div>
                        <div class="value"> <b>Ksh<?= $formattedTotalExpenses ?></b></div>
                      </div>
                    </div>
                    <div class="col-6 col-md-4 ">
                      <div class="summary-item equity">

                        <div class="label"> <i class="fas fa-calculator"></i> NET PROFIT</div>

                        <div class="value"> Ksh<?= $formattedNetProfit ?></div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="col-sm-4">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#" style="color: #00192D;"> <i class="bi bi-house"></i> Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </div>

          </div>
          <!--end::Row-->

          <!-- /end row -->
        </div>
        <!--end::Container-->
      </div>
      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <!-- Info boxes -->

          <!-- /.row -->
          <!--begin::Row-->
          <div class="row first mb-2 mt-2 rounded-circle">


            <!-- /.col -->
          </div>
          <!--end::Row-->

          <!--begin::Row-->
          <div class="row table_buttons mb-2">
            <div class="col-md-6 col-12">
              <!-- Container -->
              <div class="container-fluid">
                <div class="row g-3 align-items-end">
                  <!-- Property Filter -->
                  <div class="col-12 col-md-4">
                    <label for="buildingFilter" class="form-label">Property</label>
                    <?php
                    include '../../db/connect.php';
                    $buildings = $pdo->query("SELECT building_id, building_name FROM buildings ORDER BY building_name")->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <select id="buildingFilter" class="form-control">
                      <option value="">-- Select Property --</option>
                      <option value="all">All</option>
                      <?php foreach ($buildings as $b): ?>
                        <option value="<?= $b['building_id'] ?>"><?= htmlspecialchars($b['building_name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>




                  <!-- Start Date -->
                  <div class="col-12 col-md-4">
                    <label class="form-label startDate me-2">Start Date</label>
                    <input type="date" class="form-control" id="startDate" />
                  </div>

                  <!-- End Date -->
                  <div class="col-12 col-md-4">
                    <label class="form-label endDate me-2">End Date</label>
                    <input type="date" class="form-control" id="endDate" />
                  </div>

                  <!-- Filter Button -->
                  <div class="col-12 mt-3">
                    <button id="filterBtn" class="btn btn-outline-dark"
                      style="color: #FFC107; background-color: #00192D;">
                      <i class="fas fa-filter"></i> Filter
                    </button>
                  </div>

                  <!-- Filter Panel (initially hidden) -->
                  <!-- <div id="filterPanel" style="display:none; margin-top: 10px;">
  <label for="search">Search:</label>
  <input type="text" id="search" placeholder="Type something...">
  <button>Apply</button>
</div> -->



                </div>
              </div>
            </div>



            <div class="col-md-6 col-12 d-flex justify-content-end" style="position: relative; min-height: 60px;">
              <div style="position: absolute; bottom: 0; right: 0;">
                <button class="btn rounded-circle shadow-sm" id="downloadBtn" style="background-color: #FFC107; border: none;">
                  <i class="fas fa-file-pdf" style="font-size: 24px; color: #00192D;"></i>
                </button>
                <button class="btn rounded-circle shadow-sm" onclick="exportToExcel()" style="background-color: #FFC107; border: none;">
                  <i class="fas fa-file-excel" style="font-size: 24px; color: #00192D;"></i>
                </button>
              </div>
            </div>
          </div>


          <!--end::Row-->

          <!--begin::Row-->
          <div class="row">
            <!-- Start col -->
            <div class="container balancesheet">
              <div>
                <h3 class=" text-start  balancesheet-header">December 31, 2024</h3>
                <!-- <div class="table-responsive"> -->
                <div class="table-responsive">
                  <table id="myTable" style="width: 100%;">
                    <thead style="background-color: rgba(128, 128, 128, 0.2); color: black;">
                      <tr>
                        <th style="font-size: 16px;">Description</th>
                        <th style="font-size: 16px;">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="category">
                        <td style="color:green; font-weight:500;"><b>Income</b></td>
                      </tr>

                      <?php if ($rentTotal > 0): ?>
                        <tr>
                          <td>Rental Income</td>
                          <td>Ksh<?= $formattedRent ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($waterTotal > 0): ?>
                        <tr>
                          <td>Water Charges (Revenue)</td>
                          <td>Ksh<?= $formattedWater ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($garbageTotal > 0): ?>
                        <tr>
                          <td>Garbage Collection Fees (Revenue)</td>
                          <td>Ksh<?= $formattedGarbage ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($lateFees > 0): ?>
                        <tr>
                          <td>Late Payment Fees</td>
                          <td>Ksh<?= $formattedLateFees ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($managementFees > 0): ?>
                        <tr>
                          <td>Commissions and Management Fees</td>
                          <td>Ksh<?= $formattedManagementFees ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($otherIncome > 0): ?>
                        <tr>
                          <td>Other Income (Advertising, Penalties)</td>
                          <td>Ksh<?= $formattedOtherIncome ?></td>
                        </tr>
                      <?php endif; ?>

                      <tr class="category">
                        <td style="font-weight:500;"><b>Total Income</b></td>
                        <td><b>Ksh<?= $formattedTotalIncome ?></b></td>
                      </tr>

                      <tr class="category">
                        <td style="color:green;"><b>Expenses</b></td>
                      </tr>

                      <?php if ($maintenanceTotal > 0): ?>
                        <tr>
                          <td>Maintenance and Repair Costs</td>
                          <td>Ksh<?= $formattedMaintenance ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($salaryTotal > 0): ?>
                        <tr>
                          <td>Staff Salaries and Wages</td>
                          <td>Ksh<?= $formattedSalaryTotal ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($electricityTotal > 0): ?>
                        <tr>
                          <td>Electricity Expense</td>
                          <td>Ksh<?= $formattedElectricity ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($waterExpenseTotal > 0): ?>
                        <tr>
                          <td>Water Expense</td>
                          <td>Ksh<?= $formattedWaterExpense ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($garbageExpenseTotal > 0): ?>
                        <tr>
                          <td>Garbage Collection Expense</td>
                          <td>Ksh<?= $formattedGarbageExpense ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($internetExpenseTotal > 0): ?>
                        <tr>
                          <td>Internet Expense</td>
                          <td>Ksh<?= $formattedInternetExpense ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($securityExpenseTotal > 0): ?>
                        <tr>
                          <td>Security Expense</td>
                          <td>Ksh<?= $formattedSecurityExpense ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($softwareExpenseTotal > 0): ?>
                        <tr>
                          <td>Property Management Software Subscription</td>
                          <td>Ksh<?= $formattedSoftwareExpense ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($marketingExpenseTotal > 0): ?>
                        <tr>
                          <td>Marketing and Advertising Costs</td>
                          <td>Ksh<?= $formattedMarketingExpense ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($legalExpenseTotal > 0): ?>
                        <tr>
                          <td>Legal and Compliance Fees</td>
                          <td>Ksh<?= $formattedLegalExpense ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($loanInterestTotal > 0): ?>
                        <tr>
                          <td>Loan Interest Payments</td>
                          <td>Ksh<?= $formattedLoanInterest ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($bankChargesTotal > 0): ?>
                        <tr>
                          <td>Bank/Mpesa Charges</td>
                          <td>Ksh<?= $formattedBankCharges ?></td>
                        </tr>
                      <?php endif; ?>

                      <?php if ($otherExpenseTotal > 0): ?>
                        <tr>
                          <td>Other Expenses (Office, Supplies, Travel)</td>
                          <td>Ksh<?= $formattedOtherExpense ?></td>
                        </tr>
                      <?php endif; ?>

                      <tr class="category">
                        <td><b>Total Expenses</b></td>
                        <td><b>Ksh<?= $formattedTotalExpenses ?></b></td>
                      </tr>
                      <tr class="category">
                        <td><b>Net Profit</b></td>
                        <td><b>Ksh<?= $formattedNetProfit ?></b></td>
                      </tr>
                    </tbody>
                  </table>
                </div>

              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->



    <footer class="app-footer">
      <!--begin::To the end-->
      <div class="float-end d-none d-sm-inline">Anything you want</div>
      <!--end::To the end-->
      <!--begin::Copyright-->
      <strong>
        Copyright &copy; 2014-2024&nbsp;
        <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;"> JENGO PAY</a>.
      </strong>
      All rights reserved.
      <!--end::Copyright-->
    </footer>
    <!--end::Footer-->
  </div>
  <!--end::App Wrapper-->




  <!-- Overlay Cards -->


  <!-- Lease Modal -->
  <div class="modal fadey" id="leaseyModal" tabindex="-1" aria-labelledby="leaseyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="leaseyModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="form-wrapper">
              <h1 class="text-warning">Assign Task</h1>
              <form id="taskForm">
                <div class="input-group">
                  <label for="taskName">Task About To Be Assigned:</label>
                  <input type="text" id="taskName" name="taskName" placeholder="Enter task name" style="border-radius: 10px; width: 100%;" required>
                </div>

                <div class="input-group">
                  <label for="serviceProvider">Select A Category For The Task</label>
                  <label for="taskName"></label>
                  <select id="serviceProvider" name="serviceProvider" style="border-radius: 10px;" required>
                    <option value="" id="taskName" disabled selected>Select A Category </option>
                    <option value="John">Electrical</option>
                    <option value="Jane">Plumbing</option>
                    <option value="Mike">Cleaning</option>
                  </select>
                </div>
                <div class="input-group">
                  <label for="serviceProvider">Select Service Provider:</label>
                  <select id="serviceProvider" name="serviceProvider" style="border-radius: 10px;" required>
                    <option value="" id="taskName" disabled selected>Select Provider</option>
                    <option value="John">John </option>
                    <option value="Jane">Jane </option>
                    <option value="Mike">Mike </option>
                  </select>
                </div>


                <button type="submit" class="submit-btn" style="border-radius: 10px; background-color: #00192D; width: 50%; margin-left: 6rem;">Assign</button>
              </form>
            </div>

            <!-- <div id="assignedTasks">
              <h2>Assigned Tasks</h2>
              <ul id="taskList"></ul>
          </div> -->
          </div>


        </div>
      </div>
    </div>
  </div>

  <!-- Lease Modal -->
  <div class="modal fade" id="leaseModal" tabindex="-1" aria-labelledby="leaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="leaseModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="beasy">
            <!-- Landlord Information Section -->
            <!-- Service Provider Information Section -->
            <div class="form-section">
              <h3 class="text-warning">Landlord Information</h3>
              <a href="#">
                <p>Martin White</p>
              </a>
              <!-- <label for="landlordName">Landlord Name(Martin White)</label> -->
              <!-- <input type="text" id="landlordName" name="landlordName" placeholder="Enter your full name" required> -->

              <h3 class="text-warning">Select Service Providers Information</h3>
              <label for="paymentMethod">Choose:</label>
              <select id="paymentMethod" name="paymentMethod" required>
                <option value="bank_transfer">ABX Electricals</option>
                <option value="bank_transfer">Sunsine Plumbers</option>
                <option value="bank_transfer">Favored Technologies</option>
              </select>
              <h3 class="text-warning">Payment Details</h3>
              <label for="amount">Payment Amount (KSH)</label>
              <input type="text" id="landlordName" name="landlordName" placeholder="Enter Amount" required>

              <label for="paymentMethod">Payment Method</label>
              <select id="paymentMethod" name="paymentMethod" required>
                <option value="mpesa_transfer" class="bossy">MPESA</option>
                <option value="mpesa_transfer" class="bossy">Bank</option>
                <option value="mpesa_transfer" class="bossy">Global Pay</option>
                <option value="mpesa_transfer" class="bossy">Cash</option>

              </select>
              <!-- Submit Button -->
              <button type="submit" class="bossy"> MAKE PAYMENT</button>
            </div>



            <!-- Payment Details Section
      <div class="form-section">
        <h3>Payment Details</h3>
        <label for="amount">Payment Amount (KSH)</label>
        <input type="number" id="amount" name="amount" placeholder="Enter the amount to pay" min="1" required>

        <label for="paymentMethod">Payment Method</label>
        <select id="paymentMethod" name="paymentMethod" required>
          <option value="mpesa_transfer" class="bossy">MPESA</option>
        </select>
      </div> -->

          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- End view announcement -->
  <!-- end overlay card. -->



  <!-- javascript codes begin here  -->
  <!--begin::Script-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->



  <!-- Overlay scripts -->
  <!-- View announcements script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script>
  document.getElementById('filterBtn').addEventListener('click', function() {
    let filterPanel = document.getElementById('filterPanel');
    if (filterPanel.style.display === 'none' || filterPanel.style.display === '') {
      filterPanel.style.display = 'block';
    } else {
      filterPanel.style.display = 'none';
    }
  });
</script> -->

  <!-- <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Set default dates (current month)
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

    document.getElementById('startDate').valueAsDate = firstDayOfMonth;
    document.getElementById('endDate').valueAsDate = today;

    // Add click event listener to filter button
    document.getElementById('filterBtn').addEventListener('click', function() {
        const buildingId = document.getElementById('buildingFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // Validate dates
        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            alert('End date must be after start date');
            return;
        }

        filterBalanceSheet(buildingId, startDate, endDate);
    });
});
</script> -->

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Default date range: current month
      const today = new Date();
      const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

      document.getElementById('startDate').valueAsDate = firstDayOfMonth;
      document.getElementById('endDate').valueAsDate = today;

      // Filter button click
      document.getElementById('filterBtn').addEventListener('click', function() {
        const buildingId = document.getElementById('buildingFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
          alert('End date must be after start date');
          return;
        }

        filterBalanceSheet(buildingId, startDate, endDate);
      });
    });

    function filterBalanceSheet(buildingId, startDate, endDate) {
      const filterBtn = document.getElementById('filterBtn');
      filterBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtering...';
      filterBtn.disabled = true;

      fetch('profit_loss_api.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            building_id: buildingId,
            start_date: startDate,
            end_date: endDate
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            updateProfitLossTable(data.data);

            const header = document.querySelector('.balancesheet-header');
            if (startDate && endDate) {
              header.textContent = `Profit & Loss Statement (${new Date(startDate).toLocaleDateString()} to ${new Date(endDate).toLocaleDateString()})`;
            } else {
              header.textContent = 'Profit & Loss Statement';
            }
          } else {
            throw new Error(data.message || 'Unknown error occurred');
          }
        })
        .catch(err => {
          alert('Error applying filters: ' + err.message);
        })
        .finally(() => {
          filterBtn.innerHTML = '<i class="fas fa-filter"></i> Filter';
          filterBtn.disabled = false;
        });
    }

    function updateProfitLossTable(data) {
      const updateRow = (label, value) => {
        const row = Array.from(document.querySelectorAll('tbody tr'))
          .find(tr => tr.cells[0] && tr.cells[0].textContent.trim().startsWith(label));
        if (row && row.cells[1]) {
          row.cells[1].textContent = 'Ksh' + (value?.toFixed(2) || '0.00');
        }
      };

      // Income
      updateRow('Rental Income', data.income.rent);
      updateRow('Water Charges (Revenue)', data.income.water);
      updateRow('Garbage Collection Fees(Revenue)', data.income.garbage);
      updateRow('Late Payment Fees', data.income.late_fees);
      updateRow('Commissions and Management Fees', data.income.commissions);
      updateRow('Other Income(Advertising,Penalties)', data.income.other_income);
      updateRow('Total Income', data.income.total);

      // Expenses
      updateRow('Maintenance and Repair Costs', data.expenses.maintenance);
      updateRow('Staff Salaries and Wages', data.expenses.salaries);
      updateRow('Electricity Expense', data.expenses.electricity);
      updateRow('Water Expense', data.expenses.water);
      updateRow('Garbage Collection Expense', data.expenses.garbage);
      updateRow('Internet Expense', data.expenses.internet);
      updateRow('Security Expense', data.expenses.security);
      updateRow('Property Management Software Subscription', data.expenses.software);
      updateRow('Marketing And Advertising Costs', data.expenses.marketing);
      updateRow('Legal and Compliance Fees', data.expenses.legal);
      updateRow('Loan Interest Payments', data.expenses.loan_interest);
      updateRow('Bank/Mpesa Charges', data.expenses.bank_charges);
      updateRow('Other Expenses (Office, Supplies, Travel)', data.expenses.other_expenses);
      updateRow('Total Expenses', data.expenses.total);

      // Net Profit
      updateRow('Net Profit', data.net_profit);
    }
  </script>


  <script>
    document.getElementById('buildingFilter').addEventListener('change', function() {
      const buildingId = this.value;

      fetch('fetch_building_financials.php?building_id=' + buildingId)
        .then(response => response.text())
        .then(html => {
          document.getElementById('myTable').querySelector('tbody').innerHTML = html;
        })
        .catch(error => console.error('Error fetching data:', error));
    });
  </script>

  <!-- <script>
function applyFilters() {
  const startDate = document.getElementById("startDate").value;
  const endDate = document.getElementById("endDate").value;
  const buildingId = document.getElementById("buildingFilter").value;

  const params = new URLSearchParams();

  if (startDate) params.append('start_date', startDate);
  if (endDate) params.append('end_date', endDate);
  if (buildingId && buildingId !== "") params.append('building_id', buildingId);

  // Reload with filters applied
  window.location.href = window.location.pathname + '?' + params.toString();
}
</script> -->




  <!-- <script>
  document.getElementById('applyFilter').addEventListener('click', function() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const buildingId = document.getElementById('buildingFilter').value;

    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
    }

    // Send AJAX request to fetch filtered data
    fetch(`fetch_profit_loss.php?start_date=${startDate}&end_date=${endDate}&building_id=${buildingId}`)
        .then(response => response.json())
        .then(data => {
            updateProfitLossTable(data);
            updateSummarySection(data.summary);
        })
        .catch(error => console.error('Error:', error));
});

function updateProfitLossTable(data) {
    // Update the table with new data
    const tbody = document.querySelector('#myTable tbody');
    tbody.innerHTML = '';

    // Rebuild table rows with filtered data
    // ... implementation depends on your data structure
}

function updateSummarySection(summary) {
    // Update the summary cards at the top
    document.querySelector('.summary-item.assets .value').innerHTML = `<b>KSH ${summary.total_income}</b>`;
    document.querySelector('.summary-item.liabilities .value').innerHTML = `<b>KSH ${summary.total_expenses}</b>`;
    document.querySelector('.summary-item.equity .value').innerHTML = `<b>KSH ${summary.net_profit}</b>`;
}
</script>
 -->

  <!-- <script>
function applyFilters() {
  const startDate = document.getElementById("startDate").value;
  const endDate = document.getElementById("endDate").value;
  const buildingId = document.getElementById("buildingFilter").value;

  // Redirect to same page with GET parameters
  const params = new URLSearchParams();

  if (startDate) params.append('start_date', startDate);
  if (endDate) params.append('end_date', endDate);
  if (buildingId && buildingId !== "all") params.append('building_id', buildingId);

  window.location.href = window.location.pathname + '?' + params.toString();
}
</script> -->



  <script>
    const more_announcement = document.getElementById('more_announcement_btn');
    const view_announcement = document.getElementById('view_announcement');
    const close_overlay = document.getElementById("close-overlay-btn");

    more_announcement.addEventListener('click', () => {

      view_announcement.style.display = "flex";
      document.querySelector('.app-wrapper').style.opacity = '0.3'; // Reduce opacity of main content
      const now = new Date();
      const formattedTime = now.toLocaleString(); // Format the date and time
      timestamp.textContent = `Sent on: ${formattedTime}`;


    });

    close_overlay.addEventListener('click', () => {

      view_announcement.style.display = "none";
      document.querySelector('.app-wrapper').style.opacity = '1';


    });
  </script>

  <!-- End view announcement script -->

  <script>
    function compare() {
      let date1 = new Date(document.getElementById("date1").value);
      let date2 = new Date(document.getElementById("date2").value);
      let resultDiv = document.getElementById("result");

      if (!date1 || !date2 || isNaN(date1) || isNaN(date2)) {
        resultDiv.innerHTML = "<p style='color:red;'>Please select both dates.</p>";
        resultDiv.classList.add("show");
        return;
      }

      let message = "";

      if (date1 > date2) {
        message = `The first date (<strong>${date1.toDateString()}</strong>) is later than the second date (<strong>${date2.toDateString()}</strong>).`;
      } else if (date1 < date2) {
        message = `The first date (<strong>${date1.toDateString()}</strong>) is earlier than the second date (<strong>${date2.toDateString()}</strong>).`;
      } else {
        message = `Both dates are the same (<strong>${date1.toDateString()}</strong>).`;
      }

      resultDiv.innerHTML = `<p>${message}</p>`;
      resultDiv.classList.add("show");
    }
  </script>


  <script>
    // Function to toggle the visibility of the overlay
    function toggleOverlay() {
      var overlay = document.getElementById('overlay');
      // If overlay is hidden, show it
      if (overlay.style.display === "none" || overlay.style.display === "") {
        overlay.style.display = "flex";
      } else {
        overlay.style.display = "none";
      }
    }
  </script>


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
  <script>
    // JavaScript to handle hover and hide functionality
    const more = document.getElementById("more");
    const more_icon = document.getElementById("more_icon");
    const more_options = document.getElementById("more_options");

    // Show panel when hovering over the accordion
    more_icon.addEventListener("mouseenter", () => {
      more_options.style.display = "block";
    });

    // Hide panel when moving out of both accordion and panel
    more.addEventListener("mouseleave", () => {
      more_options.style.display = "none";
    });
  </script>
  <!-- Begin script for datatable -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let table = $('#maintenanc').DataTable({
        lengthChange: false, // Removes "Show [X] entries"
        dom: 't<"bottom"p>', // Removes default search bar & keeps only table + pagination
      });

      // Link custom search box to DataTables search
      $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
      });
    });
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



  <!--
  Add expense scripts.

-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const addRowButton = document.getElementById("addRow");
      const tableBody = document.querySelector("tbody");

      function createNewRow() {
        const newRow = document.createElement("tr");
        newRow.innerHTML = `
                <td>
                    <select class="form-select">
                        <option selected disabled>Expense</option>
                        <option>Rent</option>
                        <option>Water</option>
                        <option>Internet</option>
                        <option>Taxes</option>
                        <option>Salaries</option>
                        <option>Others</option>
                    </select>
                </td>
                <td><textarea class="form-control" rows="2" placeholder="Enter details"></textarea></td>
                <td><input type="text" class="form-control" placeholder="123"></td>
                <td><input type="number" class="form-control" placeholder="1"></td>
                <td><input type="number" class="form-control" placeholder="123"></td>
                <td><button type="button" class="btn btn-danger remove-row">Delete</button></td>
            `;

        newRow.querySelector(".remove-row").addEventListener("click", function() {
          newRow.remove();
          checkIfTableEmpty();
        });

        return newRow;
      }

      function checkIfTableEmpty() {
        if (tableBody.children.length === 0) {
          tableBody.appendChild(createNewRow()); // Add default row if empty
        }
      }

      addRowButton.addEventListener("click", function() {
        tableBody.appendChild(createNewRow());
      });

      // Initialize the first row in case user removes all
      checkIfTableEmpty();
    });
  </script>


  <!-- BalanceTable scripts -->

  <script>
    function exportToPDF() {
      const {
        jsPDF
      } = window.jspdf;
      let doc = new jsPDF();

      doc.text("PROFIT&LOSS/DHABITI PROPERTIES ", 10, 10); // Title

      let table = document.getElementById("myTable");
      let rows = [];

      for (let i = 0; i < table.rows.length; i++) {
        let row = [];
        for (let j = 0; j < table.rows[i].cells.length; j++) {
          row.push(table.rows[i].cells[j].innerText);
        }
        rows.push(row);
      }
      doc.autoTable({
        head: [rows[0]], // Table Headers
        body: rows.slice(1), // Table Data
      });
      doc.save("profit&loss_data.pdf");
    }

    function exportToExcel() {
      let table = document.getElementById("myTable");
      let workbook = XLSX.utils.table_to_book(table, {
        sheet: "Sheet1"
      });
      let excelFile = XLSX.write(workbook, {
        bookType: 'xlsx',
        type: 'array'
      });
      let blob = new Blob([excelFile], {
        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
      });
      saveAs(blob, "profit&loss_data.xlsx");
    }
  </script>


  <script>
    document.getElementById('downloadBtn').addEventListener('click', function() {
      const {
        jsPDF
      } = window.jspdf;
      const doc = new jsPDF();

      // Check if autoTable is available
      if (typeof doc.autoTable !== 'function') {
        console.error("Error: autoTable plugin is not properly loaded.");
        alert("Error: autoTable plugin is not available.");
        return;
      }

      const table = document.getElementById("myTable");
      const rows = table.querySelectorAll("tbody tr");
      const header = document.querySelector('.balancesheet-header').textContent;

      // Get the current filter dates or use default text
      const startDate = document.getElementById('startDate').value;
      const endDate = document.getElementById('endDate').value;
      let dateRangeText = "From 1 January 2024 to December 31, 2024"; // Default

      if (startDate && endDate) {
        const start = new Date(startDate).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
        const end = new Date(endDate).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
        dateRangeText = `From ${start} to ${end}`;
      }

      const data = [];
      let sectionHeaders = [];

      rows.forEach((row, rowIndex) => {
        const rowData = [];
        row.querySelectorAll("td").forEach((cell) => {
          rowData.push(cell.innerText.trim());
        });

        if (row.classList.contains("category")) {
          sectionHeaders.push(rowIndex);
        }

        data.push(rowData);
      });

      // Header styling (unchanged from your original)
      doc.setFontSize(14);
      doc.setFont("helvetica", "bold");
      doc.text("Ebenezer Apartment,", 105, 6, {
        align: "center"
      });


      doc.setFontSize(14);
      doc.setFont("helvetica", "bold");
      doc.text("Profit and Loss Statement", 105, 10, {
        align: "center"
      });

      doc.setFontSize(12);
      doc.setFont("helvetica", "bold");
      doc.text(dateRangeText, 105, 14, {
        align: "center"
      });

      // Table configuration (unchanged from your original)
      doc.autoTable({
        startY: 20,
        head: [
          ['Description', 'Amount']
        ],
        body: data,
        headStyles: {
          fillColor: [0, 25, 45],
          textColor: [255, 255, 255],
          fontStyle: 'bold'
        },
        didParseCell: function(data) {
          if (data.section === 'body' && sectionHeaders.includes(data.row.index)) {
            data.cell.styles.fontSize = 12;
            data.cell.styles.fontStyle = 'bold';
          }
        }
      });

      doc.save('profit_loss_statement.pdf');
    });
  </script>
  <!-- End script for data_table -->



  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="../../../dist/js/adminlte.js"></script>
  <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
          },
        });
      }
    });
  </script>
  <!--end::OverlayScrollbars Configure-->
  <!-- OPTIONAL SCRIPTS -->
  <!-- apexcharts -->
  <script
    src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous"></script>


  <!-- DataTable Script -->

  <script>
    $(document).ready(function() {
      var table = $('#balanceSheet').DataTable({
        "lengthChange": false,
        "dom": 'Bfrtip',
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "initComplete": function() {
          // Move the buttons to the first .col-md-6
          table.buttons().container().appendTo('#balanceSheet_wrapper .col-md-6:eq(0)');

          // Move the search box to the second .col-md-6
          $('#balanceSheet_filter').appendTo('#balanceSheet_wrapper .col-md-6:eq(1)');
        }
      });
    });
  </script>


  <script>
    // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
    // IT'S ALL JUST JUNK FOR DEMO
    // ++++++++++++++++++++++++++++++++++++++++++

    /* apexcharts
     * -------
     * Here we will create a few charts using apexcharts
     */

    //-----------------------
    // - MONTHLY SALES CHART -
    //-----------------------



    //-----------------
    // - END PIE CHART -
    //-----------------
  </script>

  <!--end::Script-->
</body>
<!--end::Body-->

</html>