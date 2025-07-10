
<?php
require_once '../db/connect.php';

$expenses = [];
$monthlyTotals = array_fill(1, 12, 0);

// === AJAX: Return Monthly Totals ===
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_totals'])) {
    $stmt = $pdo->query("SELECT MONTH(date) as month, SUM(total) as total FROM expenses GROUP BY MONTH(date)");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthlyTotals[(int)$row['month']] = (float)$row['total'];
    }
    echo json_encode($monthlyTotals);
    exit;
}

// === AJAX: Handle Expense Submission ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $date = $_POST['date'] ?? null;
    $supplier = $_POST['supplier'] ?? null;
    $expense_number = $_POST['expense_number'] ?? null;
    $total = $_POST['total'] ?? null;

    if ($date && $supplier && $expense_number && $total) {
        try {
            $stmt = $pdo->prepare("INSERT INTO expenses (date, supplier, expense_number, total) VALUES (?, ?, ?, ?)");
            $stmt->execute([$date, $supplier, $expense_number, $total]);

            echo json_encode([
                'success' => true,
                'data' => [
                    'date' => $date,
                    'supplier' => $supplier,
                    'expense_number' => $expense_number,
                    'total' => $total
                ]
            ]);
            exit;
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'error' => '❌ Failed to save expense: ' . $e->getMessage()
            ]);
            exit;
        }
    } else {
        echo json_encode([
            'success' => false,
            'error' => '❌ Missing required fields.'
        ]);
        exit;
    }
}

// === Normal Load ===
try {
    $stmt = $pdo->query("SELECT * FROM expenses ORDER BY date DESC");
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT MONTH(date) as month, SUM(total) as total FROM expenses GROUP BY MONTH(date)");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthlyTotals[(int)$row['month']] = (float)$row['total'];
    }
} catch (PDOException $e) {
    $errorMessage = "❌ Failed to fetch expenses: " . $e->getMessage();
}
?>



<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <?php if (isset($successMessage)) echo "<div class='alert alert-success'>$successMessage</div>"; ?>
    <?php if (isset($errorMessage)) echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>
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
    <!-- LINKS -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
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
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
    <!-- <link rel="stylesheet" href="text.css" /> -->
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="expenses.css">
    <!-- scripts for data_table -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <!-- Pdf pluggin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


    <!--Tailwind CSS  -->
    <style>
        .app-wrapper {
            background-color: rgba(128, 128, 128, 0.1);

        }

        .modal-backdrop.show {
            opacity: 0.4 !important;
            /* Adjust the value as needed */
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <?php include_once '../includes/header.php' ?>
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
            <div> <?php include_once '../includes/sidebar1.php'; ?> </div> <!-- This is where the sidebar is inserted -->
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--MAIN MODALS -->
            <!-- add new inspection modal-->
            <div class="modal fade" id="newSchedule" tabindex="-1" aria-labelledby="newScheduleLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content shadow">
                        <form id="form_new_inspection" onsubmit="submitInspectionForm(event)">
                            <div class="modal-header" style="background-color:#00192D;">
                                <h5 class="modal-title" id="newScheduleLabel" style="color:#FFA000 !important">Schedule Inspection</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-4">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="inspectionDate" name="date" required>
                                    <label for="tenantEmail"><i class="fas fa-envelope me-1"></i> Select Date</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="buildingSelect" name="building_name" required>
                                        <option value="" selected disabled>Choose...</option>
                                        <option value="Manucho">Manucho</option>
                                        <option value="Ebenezer">Ebenezer</option>
                                    </select>
                                    <label for="buildingSelect"><i class="fas fa-building me-1"></i> Select Building</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="buildingSelect" name="unit_name" required>
                                        <option value="" selected disabled>Choose...</option>
                                        <option value="C234">C234</option>
                                        <option value="B156">B156</option>
                                    </select>
                                    <label for="buildingSelect"><i class="fas fa-building me-1"></i> Select Unit</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="buildingSelect" name="inspection_type" required>
                                        <option value="" selected disabled>Choose...</option>
                                        <option value="Move OUT">Move OUT</option>
                                        <option value="Move In">MOVE IN</option>
                                    </select>
                                    <label for="buildingSelect"><i class="fas fa-building me-1"></i> Inspection Type</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-shift" style="background-color:#00192D; color:#FFC107;">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



            <!--begin::App Content Header-->

            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row mb-4">
                        <div class="col-sm-8">
                            <h3 class="mb-0 contact_section_header page-header">  💰 &nbsp; Expenses</h3>  
                        </div>

                        <div class="col-sm-4 d-flex justify-content-end">
                            
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Container-->
                </div>
                <div class="app-content">
                    <div class="container-fluid">
                        <div class="row g-3 mb-4">
                            <p class="text-muted">Manage your expenses</p>
                            <div class="col-md-3">
                                <select class="form-select filter-shadow">
                                <option selected>Filter by Building</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select filter-shadow ">
                                <option selected>Filter by Tenant</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select filter-shadow">
                                <option selected>Filter Status</option>
                                <option>Pending</option>
                                <option>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control filter-shadow ">
                            </div>
                        </div>
                        <div class="row mt-2 mb-2">
                            <h6 class="mb-0 contact_section_header summary mb-2"></i> <b>Summary</b></h6>
                            <div class="col-md-3">
                                <div class="personal-info-card shadow-sm bg-white p-3 rounded">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-envelope icon"></i>
                                        <div>
                                            <div class="personal-info-card-label">ITEMS</div>
                                            <b id="email" class="personal-info-card-value email">300</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Phone Card -->
                            <div class="col-md-3">
                                <div class="personal-info-card shadow-sm bg-white p-3 rounded">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-phone icon"></i>
                                        <div>
                                            <div class="personal-info-card-label">This Month</div>
                                            <b id="phone" class="personal-info-card-value phone">Ksh 100,000</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ID Card -->
                            <div class="col-md-3">
                                <div class="personal-info-card shadow-sm bg-white p-3 rounded">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-id-card icon"></i>
                                        <div>
                                            <div class="personal-info-card-label">Pending Approval</div>
                                            <b id="id_no" class="personal-info-card-value">45862394</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="mb-0 contact_section_header summary mb-4"></i> <b></b></h6>
                            <div class="col-md-12 mb-4">
                                <div class="card shadow">
                                    <div class="bg-white p-1 rounded-2 border-0">
                                        <div class="card-header d-flex justify-content-between align-items-center" style="background: #00192D;">
                                        <a class="text-white fw-bold text-decoration-none" data-bs-toggle="collapse" href="#addExpense" role="button" aria-expanded="false" aria-controls="addExpense" onclick="toggleIcon(this)">
                                            <span id="toggleIcon">➕</span> Click Here to Add an Expense
                                        </a>
                                    </div>
                                    </div>
                                    <div class="collapse" id="addExpense">
                                        <div class="card-body border-top border-2">
                                            <div class="card mb-3 shadow-sm">
                                                <div class="card-header text-white" style="background: linear-gradient(to right, #00192D, #00788D);">
                                                    <h6 class="mb-0">Enter Expense Details</h6>
                                                </div>
                                                <div class="card-body">
                                                    <form method="POST" id="expenseForm">
                                                        <div class="row g-3">
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-bold">Expense No</label>
                                                                <input type="number" class="form-control" placeholder="123" name="expense_number" required />
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label class="form-label fw-bold">Expense for the Month of</label>
                                                                <select class="form-select" name="month" required>
                                                                    <option selected disabled>Select Month</option>
                                                                    <option>January</option>
                                                                    <option>February</option>
                                                                    <option>March</option>
                                                                    <option>April</option>
                                                                    <option>May</option>
                                                                    <option>June</option>
                                                                    <option>July</option>
                                                                    <option>August</option>
                                                                    <option>September</option>
                                                                    <option>October</option>
                                                                    <option>November</option>
                                                                    <option>December</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fw-bold">Year</label>
                                                                <input type="number" class="form-control" placeholder="2025" name="year" required />
                                                            </div>
                                                        </div>

                                                        <div class="row g-3 mt-2">
                                                            <div class="col-md-8">
                                                                <label class="form-label fw-bold">Entry Date</label>
                                                                <input type="date" class="form-control" name="date" required />
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label fw-bold">Supplier</label>
                                                                <input type="text" class="form-control" placeholder="Supplier" name="supplier" required />
                                                            </div>
                                                        </div>

                                                        <!-- Hidden total -->
                                                        <input type="hidden" name="total" id="grandTotal" value="0.00" />

                                                        <!-- Spend items table -->
                                                        <div class="mt-4">
                                                            <h6 class="text-center fw-bold text-secondary">Add the Spend items below</h6>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered align-middle" id="spendTable">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Item (Service)</th>
                                                                            <th>Description</th>
                                                                            <th>Qty</th>
                                                                            <th>Unit Price</th>
                                                                            <th>Taxes</th>
                                                                            <th>Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><select class="form-select">
                                                                                    <option selected disabled>--Select Option--</option>
                                                                                    <option>Rent</option>
                                                                                    <option>Water BIll</option>
                                                                                    <option>Garbage</option>
                                                                                </select></td>
                                                                            <td><textarea class="form-control" rows="1" placeholder="Description"></textarea></td>
                                                                            <td><input type="number" class="form-control qty" placeholder="1" /></td>
                                                                            <td><input type="number" class="form-control unit-price" placeholder="123" /></td>
                                                                            <td><select class="form-select">
                                                                                    <option selected disabled>--Select Option--</option>
                                                                                    <option>VAT 16% Inclusive</option>
                                                                                    <option>VAT 16% Exclusive</option>
                                                                                    <option>Zero Rated</option>
                                                                                    <option>Exempted</option>
                                                                                </select></td>
                                                                            <td class="d-flex align-items-center">
                                                                                <input type="text" class="form-control me-2 total-line" placeholder="0" readonly />
                                                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteRow(this)">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <button type="button" class="btn btn-outline-primary" onclick="addRow()">➕ Add More</button>
                                                            </div>
                                                            <div class="col-md-6 text-end">
                                                                <button type="submit" class="btn btn-success">✅ Submit</button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="mb-0 contact_section_header summary mb-2"></i> <b>Expenses</b></h6>
                            <div class="col-md-12">
                                <div class="details-container bg-white p-2 rounded shadow">
                                    <h3 class="details-container_header text-start"> <span id="displayed_building">All expenses</span>  &nbsp;	|&nbsp;	 <span style="color:#FFC107"> <span id="enteries">3</span>  enteries</span></h3>
                                    <div id="top-bar" class="d-flex justify-content-between filter-pdf-excel mb-2">
                                        <div class="d-flex" style="gap: 10px;">
                                            <div class="select-option-container">
                                            <div class="custom-select">All Buildings</div>
                                                <div class="select-options mt-1">
                                                <div class="selected" data-value="all">All Buildings</div>
                                                <div data-value="Manucho">Manucho</div>
                                                <div data-value="Pink House">Pink House</div>
                                                <div data-value="White House">White House</div>
                                                </div>
                                            </div>
                                            <div id="custom-search">
                                            <input type="text" id="searchInput" placeholder="Search tenant...">
                                            </div>
                                        </div>
                                        <!-- Shift Tenant Button -->
                                        <div class="d-flex">
                                            <div id="custom-buttons"></div>
                                        </div>
                                    </div>
                                    <div class="table-responsive" style="overflow-x: auto;">
                                        <table class="table-striped" id="repaireExpenses" style="width: 100%; min-width: 600px;">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Supplier</th>
                                                    <th>Expense No</th>
                                                    <th>Totals</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($expenses as $exp): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars(date('d M Y', strtotime($exp['date']))) ?></td>
                                                        <td><?= htmlspecialchars($exp['supplier']) ?></td>
                                                        <td><?= htmlspecialchars($exp['expense_number']) ?></td>
                                                        <td>KSH <?= number_format($exp['total'], 2) ?></td>
                                                        <td>
                                                            <button onclick="openexpensePopup()" class="btn btn-sm" style="background-color: #0C5662; color:#fff;" data-toggle="modal" data-target="#plumbingIssueModal" title="view"><i class="fa fa-file"></i></button>
                                                            <button class="btn btn-sm" style="background-color: #193042; color:#fff;" data-toggle="modal" data-target="#assignPlumberModal" title="Remove"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                             <h6 class="mb-0 contact_section_header summary"></i> <b>Monthly Trends</b></h6>
                            <?php
                                // Group expenses by month and sum totals
                                $monthlyTotals = [];
                                try {
                                    $stmt = $pdo->query("SELECT MONTH(date) AS month, SUM(total) AS total FROM expenses GROUP BY MONTH(date)");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $monthNum = (int)$row['month'];
                                        $monthlyTotals[$monthNum] = (float)$row['total'];
                                    }
                                } catch (PDOException $e) {
                                    $monthlyTotals = [];
                                }
                                ?>
                                <div class="col-md-12">
                                    <div class="mt-5 p-2 bg-white rounded-2 shadow" >
                                        <h6 class="fw-bold text-center">📊 Monthly Expense Trends</h6>
                                       <canvas id="monthlyExpenseChart" height="100"></canvas>
                                    </div>
                                </div>
                        </div>
                    </div>
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
                <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;">JENGO PAY</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!-- Main Js File -->
    <script src="expense.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>

    <!-- J  A V A S C R I PT -->

    <!-- steeper plugin -->
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
        crossorigin="anonymous">
    </script>
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous">
    </script>
    <script src="../../../dist/js/adminlte.js"></script>
    <!-- links for dataTaable buttons -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- apexcharts -->
    <script
        src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
        crossorigin="anonymous"></script>

    <!--end::Script-->
    <!-- date display only future date -->
    <script>
        const today = new Date().toISOString().split('T')[0];
        document.getElementById("inspectionDate").setAttribute("min", today);
    </script>

    <!-- pdf download plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


    <script>
        function toggleIcon(anchor) {
            console.log('yoyo');
            const icon = anchor.querySelector('#toggleIcon');
            const isExpanded = anchor.getAttribute('aria-expanded') === 'true';
            icon.textContent = isExpanded ? '➕' : '✖';
        }

        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        function addRow() {
            // You'll need to implement dynamic row cloning if required.
            alert('Add row functionality goes here');
        }
    </script>

    <!-- Chart Section -->
    <canvas id="monthlyExpenseChart" height="100"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let expenseChart;

        function updateExpenseChart() {
            fetch('expense2.php?get_totals=1')
                .then(res => res.json())
                .then(monthlyTotals => {
                    expenseChart.data.datasets[0].data = [
                        monthlyTotals[1], monthlyTotals[2], monthlyTotals[3], monthlyTotals[4],
                        monthlyTotals[5], monthlyTotals[6], monthlyTotals[7], monthlyTotals[8],
                        monthlyTotals[9], monthlyTotals[10], monthlyTotals[11], monthlyTotals[12]
                    ];
                    expenseChart.update();
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('monthlyExpenseChart').getContext('2d');
            expenseChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: "KSH Expenses",
                        data: [
                            <?= $monthlyTotals[1] ?? 0 ?>, <?= $monthlyTotals[2] ?? 0 ?>,
                            <?= $monthlyTotals[3] ?? 0 ?>, <?= $monthlyTotals[4] ?? 0 ?>,
                            <?= $monthlyTotals[5] ?? 0 ?>, <?= $monthlyTotals[6] ?? 0 ?>,
                            <?= $monthlyTotals[7] ?? 0 ?>, <?= $monthlyTotals[8] ?? 0 ?>,
                            <?= $monthlyTotals[9] ?? 0 ?>, <?= $monthlyTotals[10] ?? 0 ?>,
                            <?= $monthlyTotals[11] ?? 0 ?>, <?= $monthlyTotals[12] ?? 0 ?>
                        ],
                        backgroundColor: "rgba(0, 25, 45, 0.2)",
                        borderColor: "#00192D",
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: "#FFC107",
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'KSH ' + value.toLocaleString()
                            }
                        }
                    }
                }
            });
        });
    </script>
    <!-- Submit -->
     <script>
        function calculateTotal() {
            let grandTotal = 0;
            document.querySelectorAll('#expenseForm tbody tr').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty')?.value || 0);
                const price = parseFloat(row.querySelector('.unit-price')?.value || 0);
                const total = qty * price;
                row.querySelector('.total-line').value = total.toFixed(2);
                grandTotal += total;
            });
            document.getElementById('grandTotal').value = grandTotal.toFixed(2);
        }

        document.getElementById('expenseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            calculateTotal();

            const form = e.target;
            const formData = new FormData(form);

            fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.querySelector('#repaireExpenses tbody');
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${data.data.date}</td>
                            <td>${data.data.supplier}</td>
                            <td>${data.data.expense_number}</td>
                            <td>KSH ${parseFloat(data.data.total).toLocaleString()}</td>
                            <td>
                                <button class="btn btn-sm" style="background-color: #0C5662; color:#fff;"><i class="fa fa-file"></i></button>
                                <button class="btn btn-sm" style="background-color: #193042; color:#fff;"><i class="fa fa-trash"></i></button>
                            </td>
                        `;
                        tbody.prepend(row);
                        alert("✅ Expense saved and displayed!");

                        form.reset();
                        document.getElementById('grandTotal').value = "0.00";
                        document.getElementById('toggleIcon').click();

                        updateExpenseChart();
                    } else {
                        alert(data.error || "❌ Something went wrong.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("❌ Server error occurred.");
                });
        });

        document.addEventListener('input', function(e) {
            if (e.target.matches('.qty, .unit-price')) {
                calculateTotal();
            }
        });
    </script>
    <script>
    $(document).ready(function () {
      const table = $('#repaireExpenses').DataTable({
        dom: 'Brtip', // ⬅ Changed to include Buttons in DOM
        order: [], // ⬅ disables automatic ordering by DataTables
        buttons: [
          {
            extend: 'excelHtml5',
            text: 'Excel',
            exportOptions: {
              columns: ':not(:last-child)' // ⬅ Exclude last column
            }
          },
          {
            extend: 'pdfHtml5',
            text: 'PDF',
            exportOptions: {
              columns: ':not(:last-child)' // ⬅ Exclude last column
            },
            customize: function (doc) {
              // Center table
              doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

              // Optional: center-align the entire table
              doc.styles.tableHeader.alignment = 'center';
              doc.styles.tableBodyEven.alignment = 'center';
              doc.styles.tableBodyOdd.alignment = 'center';

              const body = doc.content[1].table.body;
                  for (let i = 1; i < body.length; i++) { // start from 1 to skip header
                    if (body[i][4]) {
                      body[i][4].color = 'blue'; // set email column to blue
                    }
                  }
            }
          },
              {
            extend: 'print',
            text: 'Print',
            exportOptions: {
              columns: ':not(:last-child)' // ⬅ Exclude last column from print
            }
          }
        ]
      });
      // Append buttons to your div
      table.buttons().container().appendTo('#custom-buttons');
      // Custom search
      $('#searchInput').on('keyup', function () {
        table.search(this.value).draw();
      });
    });
    </script>

</body>
<!--end::Body-->

</html>