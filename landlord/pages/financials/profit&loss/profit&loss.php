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
$expenseStmt = $pdo->query("SELECT SUM(item_untaxed_amount) AS expenses FROM expense_items");
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
      <div > <?php include_once '../../includes/sidebar.php';?></div> <!-- This is where the sidebar is inserted -->
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
                    $buildings = $pdo->query("SELECT id, building_name FROM buildings ORDER BY building_name")->fetchAll(PDO::FETCH_ASSOC);
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
              <button class="btn" id="downloadBtn"
    style="color: #FFC107; background-color: #00192D; border-radius: 30px;">
    <i class="bi bi-download"></i> Download PDF
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
      <h3 class="text-start balancesheet-header">December 31, 2024</h3>
      <!-- <div class="table-responsive"> -->
       
      <!-- Collapsible Table -->
      <div class="table-responsive">
        <table id="myTable" class="table table-bordered" style="width: 100%;">
          <thead style="background-color: rgba(128, 128, 128, 0.2); color: black;">
            <tr>
              <th style="font-size: 16px;">Description</th>
              <th style="font-size: 16px;">Amount</th>
            </tr>
          </thead>
          <tbody id="accordionFinance">
            <!-- Income header -->
            <tr class="main-section-header">
              <td colspan="2" style="color:green;"><b>Income</b></td>
            </tr>

            
              <!-- Main Row -->
              <?php if ($rentTotal > 0): ?>
<tr class="main-row" data-bs-target="#rentDetails" aria-expanded="false" aria-controls="rentDetails" style="cursor:pointer;">
  <td>Rental Income</td>
  <td>Ksh<?= $formattedRent ?></td>
</tr>

<tr>
  <td colspan="2" class="p-0">
    <div id="rentDetails" class="collapse" data-bs-parent="#accordionFinance">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div>
          <span class="fw-bold text-dark">Rental Income</span>
          <span class="text-primary fw-bold ms-1"
                style="cursor:pointer;"
                data-bs-toggle="popover"
                data-bs-html="true"
                title="Options"
                data-bs-content="<a href='general_ledger.php?account_code=500&account_name=Rental Income' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
        </div>
        <span class="fw-bold text-success">Ksh<?= $formattedRent ?></span>
      </div>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($waterTotal > 0): ?>
<tr class="main-row" data-bs-target="#waterDetails" aria-expanded="false" aria-controls="waterDetails" style="cursor:pointer;">
  <td>Water Charges (Revenue)</td>
  <td>Ksh<?= $formattedWater ?></td>
</tr>

<tr>
  <td colspan="2" class="p-0">
    <div id="waterDetails" class="collapse" data-bs-parent="#accordionFinance">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div>
          <span class="fw-bold text-dark">Water Charges (Revenue)</span>
          <span class="text-primary fw-bold ms-1"
                style="cursor:pointer;"
                data-bs-toggle="popover"
                data-bs-html="true"
                title="Options"
                data-bs-content="<a href='general_ledger.php?account_code=510&account_name=Water Charges (Revenue)' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
        </div>
        <span class="fw-bold text-success">Ksh<?= $formattedWater ?></span>
      </div>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($garbageTotal > 0): ?>
<tr class="main-row" data-bs-target="#garbageDetails" aria-expanded="false" aria-controls="garbageDetails" style="cursor:pointer;">
  <td>Garbage Charges (Revenue)</td>
  <td>Ksh<?= $formattedGarbage ?></td>
</tr>

<tr>
  <td colspan="2" class="p-0">
    <div id="garbageDetails" class="collapse" data-bs-parent="#accordionFinance">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div>
          <span class="fw-bold text-dark">Garbage Charges (Revenue)</span>
          <span class="text-primary fw-bold ms-1"
                style="cursor:pointer;"
                data-bs-toggle="popover"
                data-bs-html="true"
                title="Options"
                data-bs-content="<a href='general_ledger.php?account_code=515&account_name=Garbage Charges (Revenue)' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
        </div>
        <span class="fw-bold text-success">Ksh<?= $formattedGarbage ?></span>
      </div>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($lateFees > 0): ?>
<tr class="main-row" data-bs-target="#lateDetails" aria-expanded="false" aria-controls="lateDetails" style="cursor:pointer;">
  <td>Late Payment Fees</td>
  <td>Ksh<?= $formattedLateFees ?></td>
</tr>

<tr>
  <td colspan="2" class="p-0">
    <div id="lateDetails" class="collapse" data-bs-parent="#accordionFinance">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div>
          <span class="fw-bold text-dark">Late Payment Fees</span>
          <span class="text-primary fw-bold ms-1"
                style="cursor:pointer;"
                data-bs-toggle="popover"
                data-bs-html="true"
                title="Options"
                data-bs-content="<a href='general_ledger.php?account_code=505&account_name=Late Payment Fees' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
        </div>
        <span class="fw-bold text-success">Ksh<?= $formattedLateFees ?></span>
      </div>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($managementFees > 0): ?>
<tr class="main-row" data-bs-target="#managementDetails" aria-expanded="false" aria-controls="managementDetails" style="cursor:pointer;">
  <td>Commissions and Management Fees</td>
  <td>Ksh<?= $formattedManagementFees ?></td>
</tr>

<tr>
  <td colspan="2" class="p-0">
    <div id="managementDetails" class="collapse" data-bs-parent="#accordionFinance">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div>
          <span class="fw-bold text-dark">Commissions and Management Fees</span>
          <span class="text-primary fw-bold ms-1"
                style="cursor:pointer;"
                data-bs-toggle="popover"
                data-bs-html="true"
                title="Options"
                data-bs-content="<a href='general_ledger.php?account_code=520&account_name=Commissions and Management Fees' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
        </div>
        <span class="fw-bold text-success">Ksh<?= $formattedManagementFees ?></span>
      </div>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($otherIncome > 0): ?>
<tr class="main-row" data-bs-target="#otherDetails" aria-expanded="false" aria-controls="otherDetails" style="cursor:pointer;">
  <td>Other Income (Advertising, Penalties)</td>
  <td style="text-align:right;">Ksh<?= $formattedOtherIncome ?></td>
</tr>

<tr>
  <td colspan="2" class="p-0">
    <div id="otherDetails" class="collapse" data-bs-parent="#accordionFinance">
      <div class="d-flex justify-content-between align-items-center p-3">
        <div>
          <span class="fw-bold text-dark">Other Income (Advertising, Penalties)</span>
          <span class="text-primary fw-bold ms-1"
                style="cursor:pointer;"
                data-bs-toggle="popover"
                data-bs-html="true"
                title="Options"
                data-bs-content="<a href='general_ledger.php?account_code=525&account_name=Other Income (Advertising, Penalties)' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
        </div>
        <span class="fw-bold text-success">Ksh<?= $formattedOtherIncome ?></span>
      </div>
    </div>
  </td>
</tr>
<?php endif; ?>


<!-- Total Income -->
<tr class="category">
  <td><b>Total Income</b></td>
  <td><b>Ksh<?= $formattedTotalIncome ?></b></td>
</tr>
<tr class="category">
  <td style="color:green;"><b>Expenses</b></td>
</tr>

<?php if ($maintenanceTotal > 0): ?>
<tr class="main-row" data-bs-target="#maintenanceDetails" aria-expanded="false" aria-controls="maintenanceDetails" style="cursor:pointer;">
  <td>Maintenance and Repair Costs</td>
  <td style="text-align:right;">Ksh<?= $formattedMaintenance ?></td>
</tr>

<tr class="collapse" id="maintenanceDetails">
  <td colspan="2" style="padding-left:40px;">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <span class="fw-bold text-dark">Maintenance and Repair Costs</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=600&account_name=Maintenance and Repair Costs' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedMaintenance ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($salaryTotal > 0): ?>
<tr class="main-row" data-bs-target="#salaryDetails" aria-expanded="false" aria-controls="salaryDetails" style="cursor:pointer;">
  <td>Staff Salaries and Wages</td>
  <td>Ksh<?= $formattedSalaryTotal ?></td>
</tr>

<tr class="collapse" id="salaryDetails">
  <td colspan="2" style="padding-left:40px;">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <span class="fw-bold text-dark">Staff Salaries and Wages</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=605&account_name=Staff Salaries and Wages' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedSalaryTotal ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($electricityTotal > 0): ?>
<tr class="main-row" data-bs-target="#electricityDetails" aria-expanded="false" aria-controls="electricityDetails" style="cursor:pointer;">
  <td>Electricity Expense</td>
  <td style="text-align:right;">Ksh<?= $formattedElectricity ?></td>
</tr>

<tr class="collapse" id="electricityDetails">
  <td colspan="2" style="padding-left:40px;">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <span class="fw-bold text-dark">Electricity Expense</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=610&account_name=Electricity Expense' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedElectricity ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($waterExpenseTotal > 0): ?>
<tr class="main-row" data-bs-target="#waterExpDetails" aria-expanded="false" aria-controls="waterExpDetails" style="cursor:pointer;">
  <td>Water Expense</td>
  <td>Ksh<?= $formattedWaterExpense ?></td>
</tr>

<tr class="collapse" id="waterExpDetails">
  <td colspan="2" style="padding-left:40px;">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <span class="fw-bold text-dark">Water Expense</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=615&account_name=Water Expense' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedWaterExpense ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($garbageExpenseTotal > 0): ?>
<tr class="main-row" data-bs-target="#garbageExpDetails" aria-expanded="false" aria-controls="garbageExpDetails" style="cursor:pointer;">
  <td>Garbage Collection Expense</td>
  <td>Ksh<?= $formattedGarbageExpense ?></td>
</tr>

<tr class="collapse" id="garbageExpDetails">
  <td colspan="2" style="padding-left:40px;">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <span class="fw-bold text-dark">Garbage Collection Expense</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=620&account_name=Garbage Collection Expense' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedGarbageExpense ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($internetExpenseTotal > 0): ?>
<tr class="main-row" data-bs-target="#internetDetails" aria-expanded="false" aria-controls="internetDetails" style="cursor:pointer;">
  <td>Internet Expense</td>
  <td>Ksh<?= $formattedInternetExpense ?></td>
</tr>

<tr class="collapse" id="internetDetails">
  <td colspan="2">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <span class="fw-bold text-dark">Internet Expense</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=625&account_name=Internet Expense' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedInternetExpense ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($securityExpenseTotal > 0): ?>
<tr class="main-row" data-bs-target="#securityDetails" aria-expanded="false" aria-controls="securityDetails" style="cursor:pointer;">
  <td>Security Expense</td>
  <td>Ksh<?= $formattedSecurityExpense ?></td>
</tr>

<tr class="collapse" id="securityDetails">
  <td colspan="2">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <span class="fw-bold text-dark">Security Expense</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=630&account_name=Security Expense' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedSecurityExpense ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($softwareExpenseTotal > 0): ?>
<tr class="main-row" data-bs-target="#softwareDetails" aria-expanded="false" aria-controls="softwareDetails" style="cursor:pointer;">
  <td>Property Management Software Subscription</td>
  <td>Ksh<?= $formattedSoftwareExpense ?></td>
</tr>

<tr class="collapse" id="softwareDetails">
  <td colspan="2">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <span class="fw-bold text-dark">Property Management Software Subscription</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=635&account_name=Property Management Software Subscription' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedSoftwareExpense ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($marketingExpenseTotal > 0): ?>
<tr class="main-row" data-bs-target="#marketingDetails" aria-expanded="false" aria-controls="marketingDetails" style="cursor:pointer;">
  <td>Marketing and Advertising Costs</td>
  <td>Ksh<?= $formattedMarketingExpense ?></td>
</tr>

<tr class="collapse" id="marketingDetails">
  <td colspan="2">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <span class="fw-bold text-dark">Marketing and Advertising Costs</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=640&account_name=Marketing and Advertising Costs' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedMarketingExpense ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($legalExpenseTotal > 0): ?>
<tr class="main-row" data-bs-target="#legalDetails" aria-expanded="false" aria-controls="legalDetails" style="cursor:pointer;">
  <td>Legal and Compliance Fees</td>
  <td>Ksh<?= $formattedLegalExpense ?></td>
</tr>

<tr class="collapse" id="legalDetails">
  <td colspan="2">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <span class="fw-bold text-dark">Legal and Compliance Fees</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=645&account_name=Legal and Compliance Fees' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedLegalExpense ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($loanInterestTotal > 0): ?>
<tr class="main-row" data-bs-target="#loanDetails" aria-expanded="false" aria-controls="loanDetails" style="cursor:pointer;">
  <td>Loan Interest Payments</td>
  <td>Ksh<?= $formattedLoanInterest ?></td>
</tr>

<tr class="collapse" id="loanDetails">
  <td colspan="2">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <span class="fw-bold text-dark">Loan Interest Payments</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=655&account_name=Loan Interest Payments' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedLoanInterest ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($bankChargesTotal > 0): ?>
<tr class="main-row" data-bs-target="#bankDetails" aria-expanded="false" aria-controls="bankDetails" style="cursor:pointer;">
  <td>Bank/Mpesa Charges</td>
  <td>Ksh<?= $formattedBankCharges ?></td>
</tr>

<tr class="collapse" id="bankDetails">
  <td colspan="2">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <span class="fw-bold text-dark">Bank/Mpesa Charges</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=660&account_name=Bank/Mpesa Charges' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedBankCharges ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


<?php if ($otherExpenseTotal > 0): ?>
<tr class="main-row" data-bs-target="#otherExpenseDetails" aria-expanded="false" aria-controls="otherExpenseDetails" style="cursor:pointer;">
  <td>Other Expenses (Office, Supplies, Travel)</td>
  <td>Ksh<?= $formattedOtherExpense ?></td>
</tr>

<tr class="collapse" id="otherExpenseDetails">
  <td colspan="2">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <span class="fw-bold text-dark">Other Expenses (Office, Supplies, Travel)</span>
        <span class="text-primary fw-bold ms-1"
              style="cursor:pointer;"
              data-bs-toggle="popover"
              data-bs-html="true"
              title="Options"
              data-bs-content="<a href='general_ledger.php?account_code=665&account_name=Other Expenses (Office, Supplies, Travel)' class='text-decoration-none text-dark d-block p-1'>View General Ledger</a>">⋮</span>
      </div>
      <span class="fw-bold text-success">Ksh<?= $formattedOtherExpense ?></span>
    </div>
  </td>
</tr>
<?php endif; ?>


            <!-- Activate popovers -->
            <script>
            document.addEventListener('DOMContentLoaded', function () {
              var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
              popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
              });
            });
            </script>


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
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Expand all sections
            document.getElementById('expandAll').addEventListener('click', function() {
                const collapses = document.querySelectorAll('.collapse-content');
                collapses.forEach(collapse => {
                    const bsCollapse = new bootstrap.Collapse(collapse, { toggle: true });
                    bsCollapse.show();
                    
                    // Rotate chevron icon
                    const targetId = collapse.getAttribute('id');
                    const trigger = document.querySelector(`[data-bs-target="#${targetId}"]`);
                    if (trigger) {
                        const icon = trigger.querySelector('i.fas');
                        if (icon) {
                            icon.classList.remove('fa-chevron-right');
                            icon.classList.add('fa-chevron-down');
                        }
                    }
                });
            });
            
            // Collapse all sections
            document.getElementById('collapseAll').addEventListener('click', function() {
                const collapses = document.querySelectorAll('.collapse-content');
                collapses.forEach(collapse => {
                    const bsCollapse = new bootstrap.Collapse(collapse, { toggle: true });
                    bsCollapse.hide();
                    
                    // Rotate chevron icon
                    const targetId = collapse.getAttribute('id');
                    const trigger = document.querySelector(`[data-bs-target="#${targetId}"]`);
                    if (trigger) {
                        const icon = trigger.querySelector('i.fas');
                        if (icon) {
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-right');
                        }
                    }
                });
            });
            
            // Toggle collapse functionality
            const toggleCollapseBtn = document.getElementById('toggleCollapse');
            let collapseEnabled = true;
            
            toggleCollapseBtn.addEventListener('click', function() {
                const table = document.getElementById('profitLossTable');
                const categoryRows = document.querySelectorAll('.category-row');
                const collapses = document.querySelectorAll('.collapse-content');
                
                if (collapseEnabled) {
                    // Disable collapse functionality
                    table.classList.add('no-collapse');
                    categoryRows.forEach(row => {
                        row.setAttribute('data-bs-toggle', '');
                    });
                    
                    // Expand all sections
                    collapses.forEach(collapse => {
                        collapse.classList.add('show');
                    });
                    
                    toggleCollapseBtn.innerHTML = '<i class="fas fa-eye me-1"></i> Show Collapse';
                    collapseEnabled = false;
                } else {
                    // Enable collapse functionality
                    table.classList.remove('no-collapse');
                    categoryRows.forEach(row => {
                        row.setAttribute('data-bs-toggle', 'collapse');
                    });
                    
                    toggleCollapseBtn.innerHTML = '<i class="fas fa-eye-slash me-1"></i> Hide Collapse';
                    collapseEnabled = true;
                }
            });
            
            // Handle chevron icon rotation on collapse toggle
            const categoryRows = document.querySelectorAll('.category-row');
            categoryRows.forEach(row => {
                row.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-bs-target').substring(1);
                    const target = document.getElementById(targetId);
                    const icon = this.querySelector('i.fas');
                    
                    if (target.classList.contains('show')) {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-right');
                    } else {
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-down');
                    }
                });
            });
        });
    </script>

<script>
// Toggle icon rotation on collapse
document.addEventListener('DOMContentLoaded', function() {
  // Handle individual collapse toggles
  var collapseElements = document.querySelectorAll('.collapse');
  collapseElements.forEach(function(collapseEl) {
    collapseEl.addEventListener('show.bs.collapse', function() {
      var trigger = document.querySelector('[data-bs-target="#' + this.id + '"]');
      if (trigger) {
        var icon = trigger.querySelector('i.toggle-icon');
        if (icon) {
          icon.classList.remove('fa-chevron-right');
          icon.classList.add('fa-chevron-down');
        }
      }
    });

    collapseEl.addEventListener('hide.bs.collapse', function() {
      var trigger = document.querySelector('[data-bs-target="#' + this.id + '"]');
      if (trigger) {
        var icon = trigger.querySelector('i.toggle-icon');
        if (icon) {
          icon.classList.remove('fa-chevron-down');
          icon.classList.add('fa-chevron-right');
        }
      }
    });
  });

  // Hide all details
  document.getElementById('hideAllBtn').addEventListener('click', function() {
    var collapses = document.querySelectorAll('.collapse');
    collapses.forEach(function(collapse) {
      var bsCollapse = new bootstrap.Collapse(collapse, {
        toggle: false
      });
      bsCollapse.hide();
    });
  });

  // Show all details
  document.getElementById('showAllBtn').addEventListener('click', function() {
    var collapses = document.querySelectorAll('.collapse');
    collapses.forEach(function(collapse) {
      var bsCollapse = new bootstrap.Collapse(collapse, {
        toggle: false
      });
      bsCollapse.show();
    });
  });
});
</script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
  const rows = document.querySelectorAll('.main-row');

  rows.forEach(row => {
    const collapseId = row.getAttribute('data-bs-target');
    const collapseDiv = document.querySelector(collapseId);

    if (!collapseDiv) return;

    // Handle toggle
    row.addEventListener('click', (e) => {
      if (e.target.closest('a, button, input, select, textarea')) return;
      const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseDiv, { toggle: false });
      bsCollapse.toggle();
    });

    // Arrow animation on expand/collapse
    collapseDiv.addEventListener('shown.bs.collapse', () => {
      const arrow = row.querySelector('span');
      if (arrow) arrow.textContent = "▾";
      row.setAttribute('aria-expanded', 'true');
    });

    collapseDiv.addEventListener('hidden.bs.collapse', () => {
      const arrow = row.querySelector('span');
      if (arrow) arrow.textContent = "▸";
      row.setAttribute('aria-expanded', 'false');
    });
  });

  // 🔘 Show all sections
  document.getElementById('showAllBtn').addEventListener('click', () => {
    document.querySelectorAll('.collapse').forEach(el => {
      const bsCollapse = bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
      bsCollapse.show();
    });
    document.querySelectorAll('.main-row span').forEach(span => span.textContent = "▾");
  });

  // 🔘 Hide all sections
  document.getElementById('hideAllBtn').addEventListener('click', () => {
    document.querySelectorAll('.collapse.show').forEach(el => {
      const bsCollapse = bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
      bsCollapse.hide();
    });
    document.querySelectorAll('.main-row span').forEach(span => span.textContent = "▸");
  });
});
</script>

<!-- Enable Bootstrap Popovers -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    popoverTriggerList.map(function (popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl)
    })
  });
</script>
<!-- </script> -->
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
  // Script to change the chevron icon when the collapse is toggled
  document.addEventListener('DOMContentLoaded', function () {
    var collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(function(collapseEl) {
      collapseEl.addEventListener('show.bs.collapse', function () {
        var trigger = document.querySelector('[data-bs-target="#' + this.id + '"]');
        if (trigger) {
          var icon = trigger.querySelector('i.fas');
          if (icon) {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
          }
        }
      });

      collapseEl.addEventListener('hide.bs.collapse', function () {
        var trigger = document.querySelector('[data-bs-target="#' + this.id + '"]');
        if (trigger) {
          var icon = trigger.querySelector('i.fas');
          if (icon) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
          }
        }
      });
    });
  });
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
document.getElementById('downloadBtn').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF("p", "mm", "a4");

    if (typeof doc.autoTable !== "function") {
        alert("autoTable plugin not loaded");
        return;
    }

    /** LOGO **/
    const logoUrl = "expenseLogo6.png";

    /** Business Info **/
    const businessName = "Ebenezer Apartment";
    const address = "50303 Nairobi, Kenya";
    const email = "silver@gmail.com";
    const phone = "+254 700 123456";

    /** Date Range **/
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;

    let dateRangeText = "For the period ending December 31, 2024";
    if (startDate && endDate) {
        const start = new Date(startDate).toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" });
        const end = new Date(endDate).toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" });
        dateRangeText = `From ${start} to ${end}`;
    }

    /** Draw Logo & Business Info **/
    doc.addImage(logoUrl, "PNG", 12, 10, 30, 30);

    doc.setFillColor(240, 240, 240);
    doc.roundedRect(140, 10, 60, 25, 3, 3, "F");

    doc.setFont("helvetica", "bold");
    doc.setFontSize(11);
    doc.text(businessName, 170, 16, { align: "right" });

    doc.setFontSize(9);
    doc.text(address, 170, 21, { align: "right" });
    doc.text(email, 170, 26, { align: "right" });
    doc.text(phone, 170, 31, { align: "right" });

    /** Title **/
    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.setTextColor(0, 25, 45);
    doc.text("Profit & Loss Statement", 105, 50, { align: "center" });

    doc.setFont("helvetica", "normal");
    doc.setFontSize(11);
    doc.text(dateRangeText, 105, 57, { align: "center" });

    /** Extract ONLY VISIBLE rows **/
    const table = document.getElementById("myTable");
    const allRows = table.querySelectorAll("tbody tr");

    const data = [];
    const sectionHeaders = [];

    allRows.forEach((row) => {
        const style = window.getComputedStyle(row);

        // SKIP HIDDEN ROWS
        if (style.display === "none" || style.visibility === "hidden") return;

        let rowData = [];
        row.querySelectorAll("td").forEach(td => rowData.push(td.innerText.trim()));

        // ❌ FIX: Skip rows where all TDs are empty → removes INCOME GAP
        if (rowData.every(cell => cell === "")) return;

        if (row.classList.contains("category")) {
            sectionHeaders.push(data.length);
        }

        data.push(rowData);
    });

    /** MAIN TABLE **/
    doc.autoTable({
        startY: 70,
        head: [["Description", "Amount"]],
        body: data,

        theme: "grid",
        margin: { left: 14, right: 14 },

        styles: {
            fontSize: 10,
            cellPadding: 3,
            lineWidth: 0.2,
            lineColor: [200, 200, 200],
        },

        headStyles: {
            fillColor: [0, 25, 45],
            textColor: [255, 255, 255],
            fontSize: 11,
            fontStyle: "bold",
        },

        alternateRowStyles: {
            fillColor: [250, 250, 250],
        },

        didParseCell: function (tableData) {
            if (
                tableData.section === "body" &&
                sectionHeaders.includes(tableData.row.index)
            ) {
                tableData.cell.styles.fontSize = 11;
                tableData.cell.styles.fontStyle = "bold";
                tableData.cell.styles.fillColor = [255, 244, 204];
                tableData.cell.styles.textColor = [0, 25, 45];
            }
        },

        didDrawPage: function () {
            doc.setDrawColor(255, 193, 7);
            doc.setLineWidth(1.5);
            doc.line(10, 5, 200, 5);
        }
    });

    /** Footer **/
    const pageHeight = doc.internal.pageSize.height;
    doc.setFontSize(9);
    doc.setTextColor(150, 150, 150);
    doc.text("Generated by JengoPay System", 105, pageHeight - 8, { align: "center" });

    doc.save("profit_loss_statement.pdf");
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