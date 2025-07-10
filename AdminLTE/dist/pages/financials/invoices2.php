<?php
include '../db/connect.php';

$stmt = $pdo->prepare("SELECT account_code, account_name FROM chart_of_accounts_1 ORDER BY account_name ASC");
$stmt->execute();
$accountItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
require_once '../db/connect.php';

// Get the highest existing invoice number
$stmt = $pdo->query("SELECT invoice_number FROM invoice ORDER BY id DESC LIMIT 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row && preg_match('/INV(\d+)/', $row['invoice_number'], $matches)) {
    $lastNumber = (int)$matches[1];
    $newNumber = $lastNumber + 1;
} else {
    $newNumber = 1; // Start at 1 if no previous invoice
}

$invoiceNumber = 'INV' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
?>


<?php
// At the top of your PHP file (before HTML)
require_once '../db/connect.php';

try {
    $stmt = $pdo->prepare("SELECT building_id FROM buildings ORDER BY building_id");
    $stmt->execute();
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $buildings = [];
    // You might want to log this error in production
    error_log("Error fetching buildings: " . $e->getMessage());
}
?>

<?php
include '../db/connect.php'; // Adjust this path!

$buildings = [];

// --- DEBUG START ---
echo "\n";
try {
    if (!isset($pdo) || !$pdo instanceof PDO) {
        echo "\n";
        die("Error: Database connection not established."); // Or handle more gracefully
    }
    $stmt = $pdo->query("SELECT building_id, building_name FROM buildings ORDER BY building_name ASC");
    $buildings = $stmt->fetchAll();
    echo "\n";
    echo "\n";
} catch (PDOException $e) {
    error_log("Database error fetching buildings: " . $e->getMessage());
    echo "\n"; // Show error in source for debug
    echo "<p>Error loading properties. Please try again later.</p>";
    $buildings = [];
}
echo "\n";
// --- DEBUG END ---
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
    <link rel="stylesheet" href="../../../dist/css/adminlte.css"/>
    <link rel="stylesheet" href="invoices.css">
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
                            <h3 class="mb-0 contact_section_header page-header"> 🧾 &nbsp; Invoices</h3>
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
                            <p class="text-muted">Manage your invoices</p>
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


                        <div class="col-md-12">
            <div class="card card-success">
              <div class="card-header" style="background-color: #00192D;">
                <h3 class="card-title"  style="color: #FFC107;">Add Tenant Invoice</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                  </button>
                </div>
                 <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <!-- <div class="row g-3"> -->
                  <!-- <div class="row g-3"> -->
                  <!-- <div class="form-section col-md-6"> -->
                <div class="card-body text-center">
                    <div class="form-section">
                      <b><h2 style="text-align: left; font-weight: 600;">Invoice Details</h2></b>
                  <form method="POST" action="submit_invoice.php">
  <div class="form-row">
    <input type="text" value="<?= $invoiceNumber ?>" disabled>
    <input type="hidden" name="invoice_number" value="<?= $invoiceNumber ?>"> <!-- for actual submission -->
    <input type="date" name="invoice_date" placeholder="Invoice Date" required>
  </div>
  <?php
require_once '../db/connect.php';

// Fetch buildings
$buildings = $pdo->query("SELECT building_id, building_name FROM buildings")->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="row g-3">
  <!-- Building Dropdown -->
  <div class="form-section col-md-6">
    <b><h2 style="text-align: left;font-weight: 600;">Property</h2></b>
    <select name="building_id" id="buildingSelect" class="form-control" required>
      <option value="" disabled selected>Select Property</option>
      <?php foreach ($buildings as $building): ?>
        <option value="<?= $building['building_id'] ?>">
          <?= htmlspecialchars($building['building_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-section col-md-6">
                        <b><h2 style="text-align: left;font-weight: 600;">Tenant Information</h2></b>
                        <select name="tenant" required>
                          <option value="select_tenant" disabled selected>Select Tenant</option>
                          <option value="peter_mwangi">Peter Mwangi</option>
                          <option value="brian_mwenda">Brian Mwenda</option>
                          <option value="silas_qwetu">Silas Qwetu</option>
                        </select>
                      </div>
                </div>
                <!-- </div>
                </div> -->

  <!-- Item Table -->
  <div class="form-section">
    <hr>
    <table class="items-table">
      <thead>
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
          <td>
            <select name="account_item[]" required>
              <option value="" disabled selected>Select Account Item</option>
              <?php foreach ($accountItems as $item): ?>
                <option value="<?= htmlspecialchars($item['account_code']) ?>">
                  <?= htmlspecialchars($item['account_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </td>
          <td><textarea name="description[]" placeholder="Description" rows="1" required></textarea></td>
          <td><input type="number" name="quantity[]" class="form-control quantity" placeholder="1" required></td>
          <td><input type="number" name="unit_price[]" class="form-control unit-price" placeholder="123" required></td>
          <td>
            <select name="taxes[]" class="form-select vat-option" required>
              <option value="" disabled selected>Select Option</option>
              <option value="inclusive">VAT 16% Inclusive</option>
              <option value="exclusive">VAT 16% Exclusive</option>
              <option value="zero">Zero Rated</option>
              <option value="exempted">Exempted</option>
            </select>
          </td>
          <td>
            <input type="number" name="total[]" class="form-control total" placeholder="0" readonly>
            <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
              <i class="fa fa-trash" style="font-size: 12px;"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <button type="button" class="add-btn" onclick="addRow()">
      <i class="fa fa-plus"></i> ADD MORE
    </button>
  </div>

  <!-- Submit -->
  <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
  <button type="submit" style="background-color: #00192D; color: #FFC107; padding: 8px 16px; border: none; border-radius: 4px;">
    Submit
  </button>
</div>

</form>

                    </div>

                    </div>

                </div>
            </div>
                        <div class="row">
                            <h6 class="mb-0 contact_section_header summary mb-2"></i> <b>Invoices</b></h6>
                            <div class="col-md-12">
                                <div class="card Content">
                                    <div class="card-body" style="overflow-x: auto;">
                                    <table id="invoice" class="table table-striped" style="width: 100%; padding:10px; height: fit-content;">
                              <thead>
                                  <tr>
                                      <th>Invoice Number</th>
                                      <th>Property Name</th>
                                      <th>Tenant</th>
                                      <th>Payment Status</th>
                                      <th>Invoice Date</th>
                                      <th>Taxes</th>
                                      <th>Totals</th>
                                      <th>ACTIONS</th>
                                  </tr>
                              </thead>
                              <tbody>

                              </tbody>
                          </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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


                <!-- Line Chart: Expenses vs Months -->
                <!-- <div class="mt-5">
                    <h6 class="fw-bold text-center">📊 Monthly Expense Trends</h6>
                    <canvas id="monthlyExpenseChart" height="100"></canvas>
                </div> -->


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


    <!-- OVERLAYS(that Covers whole viewport) -->
    <!-- Perfom an inspection -->
    <section id="perform_inspection_modal" class="perform_inspection_modal" style="display: none;">

        <div class="container-fluid">
            <div class="card">
                <div class="card-header" style="background-color:#00192D; color:#FFC107"><b>Perform Inspection</b></div>
                <div class="card-body">
                    <div class="card shadow" style="border:1px solid rbg(0,25,45,.2)">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label><i class="fa fa-home"></i> Unit No: <span id="modal_unit"></span> </label>
                                </div>
                                <div class="col-md-6">
                                    <label><i class="fa fa-building"></i> Building: <span id="modal_building_name">Angela's Apartment</span> </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label><i class="fa fa-table"></i> Floor Location: Second Floor</label>
                                </div>
                                <div class="col-md-6">
                                    <label><i class="fa fa-bed"></i> Rental Purpose: Residence</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card shadow">
                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-cogs"></i> <b>Inspect this Unit</b></div>
                        <form id="perform_inspection" onsubmit="performInspectionForm(event)" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                            <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-home"></i> <b>Floor Condition</b></div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-md-6">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="floor_condition" id="floorRepair" value="Needs Repair">
                                                                <label for="floorRepair"> Needs Repair</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="floor_condition" id="floorGood" value="Good">
                                                                <label for="floorGood"> Good</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card shadow" id="floorBadDescription" style="display:none;">
                                                    <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Describe the Repair Required</b></div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Describe the State</label>
                                                            <textarea name="floor_state" id="" class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach Photo</label>
                                                            <input type="file" class="form-control" name="floor_photo">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                            <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-table"></i> <b>Window(s) Condition</b></div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-md-6">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="window_condition" id="windowNeedsRepair" value="Needs Repair">
                                                                <label for="windowNeedsRepair"> Needs Repair</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="window_condition" id="windowGood" value="Good">
                                                                <label for="windowGood"> Good</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card shadow" id="windowBadDescription" style="display:none;">
                                                    <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Describe the Repair Required</b></div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Describe the State</label>
                                                            <textarea name="window_state" id="" class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach Photo</label>
                                                            <input type="file" class="form-control" name="window_photo">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                            <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-building"></i> <b>Doors Condition</b></div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-md-6">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="door_condition" id="doorGood" value="Good">
                                                                <label for="doorGood"> Good</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="door_condition" id="doorBad" value="Needs Repair">
                                                                <label for="doorBad"> Needs Repair</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card shadow mt-2" id="doorBadCard" style="display:none;">
                                                    <div class="card-header" style="background-color:#00192D; color:#FFC107"><b>Provide More Informations</b></div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="">Describe the Damage</label>
                                                            <textarea name="door_state" class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Attach Photo</label>
                                                            <input type="file" class="form-control" name="door_badphoto">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                            <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-bank"></i><b> Wall Condition</b></div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-md-6">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="wall_condition" id="wallNeedRepair" value="Needs Repair">
                                                                <label for="wallNeedRepair"> Needs Repair</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="wall_condition" id="wallGood" value="Good">
                                                                <label for="wallGood"> Good</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card shadow mt-2" id="wallNeedsRepairCard" style="display:none;">
                                                    <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="">Describe the Repair Needed</label>
                                                            <textarea name="wall_state" id="" cols="30" class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Attach Photo</label>
                                                            <input type="file" class="form-control" name="faulty_wall_photo" id="faulty_wall_photo">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                            <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-bell"></i><b> Bulb Holder(s)</b></div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-md-6">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="bulb_holder_condition" id="bulbHolderGood" value="Good">
                                                                <label for="bulbHolderGood"> Good</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card shadow p-3" style="order: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="bulb_holder_condition" id="bulbHolderNeedsRepair" value="Needs Repair">
                                                                <label for="bulbHolderNeedsRepair"> Needs Repair</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card shadow" id="bulbHolderCard" style="display:none;">
                                                    <div class="card-header" style="background-color:#00192D; color: #FFC107;"><b>Describe the Repair Needed</b></div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="">Describe the Fault</label>
                                                            <textarea name="bulb_holder_state" id="bulb_holder_desc" class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Attach Photo</label>
                                                            <input type="file" name="bulb_holder_photo" id="bulb_holder_photo" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                            <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-plug"></i> <b>Sockets</b></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 text-center">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="socket_condition" id="socketGood" value="Good">
                                                                <label for="socketGood"> Good</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 text-center">
                                                        <div class="card shadow p-3" style="border: 1px solid rgb(0,25,45,.2);">
                                                            <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                            <div class="icheck-dark d-inline">
                                                                <input type="radio" name="socket_condition" id="socketNeedsRepair" value="Needs Repair">
                                                                <label for="socketNeedsRepair"> Needs Repair</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card shadow" id="socketFaultyCard" style="border:1px solid rgb(0,25,45,.2); display:none;">
                                                    <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Describe the Fault</b></div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Describe the Fault</label>
                                                            <textarea name="socket_state" id="fault_socket_description" class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Attach Photos</label>
                                                            <input type="file" name="fault_socket_photo" id="fault_socket_photo" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <input type="hidden" name="inspection_id" id="modal_inspection_id">
                                <button type="submit" class="btn btn-sm next-btn" id="fifththStepNextBtn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Inspection(inspected) Modal -->
    <div class="modal fade" id="inspectionModal" tabindex="-1" aria-labelledby="inspectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-2 shadow-lg">
                <div class="modal-header d-flex justify-content-between align-items-center" id="inspectionModalHeader" style="background-color: #00192D !important;">
                    <h5 class="modal-title" style="color: #FFA000 !important; margin-left: 5px;" id="inspectionModalLabel">
                        Inspection Details - Ebenezer/Unit A12
                    </h5>
                    <!-- Centered Icon Button -->
                    <button type="button" class="btn btn-sm btn-warning mx-auto" id="downloadInspectionBtn" title="Submit Inspection">
                        <i class="bi bi-download"></i>
                    </button>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2">
                    <table class="table inspection-table table-striped rounded-2">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>Photos</th>
                            </tr>
                        </thead>
                        <tbody id="inspectionModalTableBody">
                            <tr>
                                <td>Floor</td>
                                <td><span class="status-bad">Needs Repair</span></td>
                                <td>Scratches and water damage near the corner.</td>
                                <td>
                                    <img src="https://www.districtfloordepot.com/wp-content/uploads/2022/02/types-of-floor-damage.jpg" class="repair-photo" alt="Floor damage">
                                </td>
                            </tr>
                            <tr>
                                <td>Window</td>
                                <td><span class="status-bad">Needs Repair</span></td>
                                <td>Broken lock on the left window pane.</td>
                                <td>
                                    <img src="https://apexwindowwerks.com/wp-content/uploads/2023/03/wood-window-repair-guide.jpg" class="repair-photo" alt="Window issue">
                                </td>
                            </tr>
                            <tr>
                                <td>Door</td>
                                <td><span class="status-good">Good</span></td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Wall</td>
                                <td><span class="status-bad">Needs Repair</span></td>
                                <td>Peeling paint and minor cracks.</td>
                                <td>
                                    <img src="https://images.pexels.com/photos/276267/pexels-photo-276267.jpeg" class="repair-photo" alt="Wall damage">
                                </td>
                            </tr>
                            <tr>
                                <td>Bulb</td>
                                <td><span class="status-good">Good</span></td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Sockets</td>
                                <td><span class="status-bad">Needs Repair</span></td>
                                <td>One socket in the kitchen is not working.</td>
                                <td>
                                    <img src="https://create.vista.com/wp-content/uploads/2021/09/damaged-socket.jpg" class="repair-photo" alt="Socket issue">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer d-flex justify-content-between p-2">
                    <small class="text-muted">📅 Inspection Date: <strong>2025-05-26</strong></small>
                    <button type="button" style="background-color:#FFC107 !important; color:#00192D;" class="btn btn-outline" data-bs-dismiss="modal" id="closeInspectionModal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- cloned pdf modal-->
    <div id="printArea" style="display: none;"></div>


    <!-- Main Js File -->
    <script src="inspections.js"></script>
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
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
    <!-- <canvas id="monthlyExpenseChart" height="100"></canvas> -->
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
function addRow() {
  const table = document.querySelector(".items-table tbody");
  const newRow = document.createElement("tr");

  newRow.innerHTML = `
    <td>
      <select name="account_item[]" required>
        <option value="" disabled selected>Select Option</option>
        <option value="rent">Rent</option>
        <option value="water">Water Bill</option>
        <option value="garbage">Garbage</option>
      </select>
    </td>
    <td><textarea name="description[]" placeholder="Description" rows="1" required></textarea></td>
    <td><input type="number" name="unit_price[]" class="form-control unit-price" placeholder="123" required></td>
    <td><input type="number" name="quantity[]" class="form-control quantity" placeholder="1" required></td>
    <td><input type="number" class="form-control subtotal" placeholder="0" readonly></td>
    <td>
      <select name="taxes[]" class="form-select vat-option" required>
        <option value="" disabled selected>Select Option</option>
        <option value="inclusive">VAT 16% Inclusive</option>
        <option value="exclusive">VAT 16% Exclusive</option>
        <option value="zero">Zero Rated</option>
        <option value="exempt">Exempted</option>
      </select>
    </td>
    <td><input type="number" class="form-control vat-amount" placeholder="0" readonly></td>
    <td><input type="number" name="total[]" class="form-control total" placeholder="0" readonly></td>
    <td><button type="button" class="delete-btn btn btn-danger btn-sm" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button></td>
  `;

  table.appendChild(newRow);
  attachEvents(newRow); // Attach calculation events to new row
}

function deleteRow(btn) {
  btn.closest("tr").remove();
  updateTotals();
}

function attachEvents(row) {
  const unitPriceInput = row.querySelector(".unit-price");
  const quantityInput = row.querySelector(".quantity");
  const vatSelect = row.querySelector(".vat-option");

  unitPriceInput.addEventListener("input", () => calculateRow(row));
  quantityInput.addEventListener("input", () => calculateRow(row));
  vatSelect.addEventListener("change", () => calculateRow(row));
}

function calculateRow(row) {
  const unitPrice = parseFloat(row.querySelector(".unit-price").value) || 0;
  const quantity = parseFloat(row.querySelector(".quantity").value) || 0;
  const vatOption = row.querySelector(".vat-option").value;

  const subtotal = unitPrice * quantity;
  let vatAmount = 0;

  if (vatOption === "inclusive") {
    vatAmount = subtotal - (subtotal / 1.16);
  } else if (vatOption === "exclusive") {
    vatAmount = subtotal * 0.16;
  } else if (vatOption === "zero" || vatOption === "exempt") {
    vatAmount = 0;
  }

  const total = (vatOption === "inclusive") ? subtotal : subtotal + vatAmount;

  row.querySelector(".subtotal").value = subtotal.toFixed(2);
  row.querySelector(".vat-amount").value = vatAmount.toFixed(2);
  row.querySelector(".total").value = total.toFixed(2);

  updateTotals();
}

function updateTotals() {
  // Optional: implement total summary across all rows here if needed
}

function printInvoice() {
  window.print();
}

function downloadPDF() {
  const element = document.querySelector(".invoice-container");
  html2pdf().from(element).save("invoice.pdf");
}

// Attach events to initial row(s) after DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".items-table tbody tr").forEach(row => {
    attachEvents(row);
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


<script>
$(document).ready(function() {
  $('#repaireExpenses').DataTable({
      "lengthChange": false,
      "dom": 'Bfrtip',
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('fetch_invoices.php')
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#invoice tbody');
            tbody.innerHTML = ''; // Clear existing rows

            data.forEach(invoice => {
                const statusButton = invoice.payment_status === 'PAID'
                    ? `<button style="background-color: green; color: white; border-radius: 10px;"><span>&#10004;</span> PAID</button>`
                    : `<button style="background-color: #00192D; color: #FFC107; border-radius: 10px;">!PENDING</button>`;

                const row = `
                    <tr>
                        <td>${invoice.invoice_number}</td>
                        <td>${invoice.property_name}</td>
                        <td>${invoice.tenant} </td>
                        <td>${statusButton}</td>
                        <td>${formatDate(invoice.invoice_date)}</td>
                        <td>Ksh ${formatCurrency(invoice.taxes)}</td>
                        <td>Ksh ${formatCurrency(invoice.total)}</td>
                        <td>
                            <a href="../financials/viewinvoices.php">
                                <button class="btn btn-sm" style="background-color: #0C5662; color:#fff;" title="Get Full Report"><i class="fa fa-file"></i>View</button>
                            </a>
                            <button class="btn btn-sm" style="background-color: #193042; color:#fff;" title="Delete Invoice">
                                <i class="fa fa-trash" style="font-size: 12px; color: red;"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        })
        .catch(error => {
            console.error('Error fetching invoices:', error);
        });

    // Utility function to format dates
    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-GB'); // e.g., 10-11-2025
    }

    // Utility function to format currency
    function formatCurrency(value) {
        const num = parseFloat(value || 0);
        return num.toLocaleString('en-KE', { minimumFractionDigits: 0 });
    }
});
</script>



<script>
  document.addEventListener("DOMContentLoaded", function() {
      const checkboxes = document.querySelectorAll("#columnFilter input[type='checkbox']");
      const menuButton = document.getElementById("menuButton");
      const columnFilter = document.getElementById("columnFilter");

      // Toggle menu visibility when clicking the three dots
      menuButton.addEventListener("click", function(event) {
          columnFilter.classList.toggle("hidden");
          columnFilter.style.display = columnFilter.classList.contains("hidden") ? "none" : "block";

          // Prevent closing immediately when clicking inside
          event.stopPropagation();
      });

      // Hide menu when clicking outside
      document.addEventListener("click", function(event) {
          if (!menuButton.contains(event.target) && !columnFilter.contains(event.target)) {
              columnFilter.classList.add("hidden");
              columnFilter.style.display = "none";
          }
      });

      // Column filtering logic
      checkboxes.forEach(checkbox => {
          checkbox.addEventListener("change", function() {
              let columnClass = `.col-${this.dataset.column}`;
              let elements = document.querySelectorAll(columnClass);

              elements.forEach(el => {
                  el.style.display = this.checked ? "" : "none";
              });
          });
      });
  });

      </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>
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

      const sales_chart_options = {
        series: [
          {
            name: 'Digital Goods',
            data: [28, 48, 40, 19, 86, 27, 90],
          },
          {
            name: 'Electronics',
            data: [65, 59, 80, 81, 56, 55, 40],
          },
        ],
        chart: {
          height: 180,
          type: 'area',
          toolbar: {
            show: false,
          },
        },
        legend: {
          show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth',
        },
        xaxis: {
          type: 'datetime',
          categories: [
            '2023-01-01',
            '2023-02-01',
            '2023-03-01',
            '2023-04-01',
            '2023-05-01',
            '2023-06-01',
            '2023-07-01',
          ],
        },
        tooltip: {
          x: {
            format: 'MMMM yyyy',
          },
        },
      };

      const sales_chart = new ApexCharts(
        document.querySelector('#sales-chart'),
        sales_chart_options,
      );
      sales_chart.render();

      //---------------------------
      // - END MONTHLY SALES CHART -
      //---------------------------

      function createSparklineChart(selector, data) {
        const options = {
          series: [{ data }],
          chart: {
            type: 'line',
            width: 150,
            height: 30,
            sparkline: {
              enabled: true,
            },
          },
          colors: ['var(--bs-primary)'],
          stroke: {
            width: 2,
          },
          tooltip: {
            fixed: {
              enabled: false,
            },
            x: {
              show: false,
            },
            y: {
              title: {
                formatter: function (seriesName) {
                  return '';
                },
              },
            },
            marker: {
              show: false,
            },
          },
        };

        const chart = new ApexCharts(document.querySelector(selector), options);
        chart.render();
      }

      const table_sparkline_1_data = [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54];
      const table_sparkline_2_data = [12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 44];
      const table_sparkline_3_data = [15, 46, 21, 59, 33, 15, 34, 42, 56, 19, 64];
      const table_sparkline_4_data = [30, 56, 31, 69, 43, 35, 24, 32, 46, 29, 64];
      const table_sparkline_5_data = [20, 76, 51, 79, 53, 35, 54, 22, 36, 49, 64];
      const table_sparkline_6_data = [5, 36, 11, 69, 23, 15, 14, 42, 26, 19, 44];
      const table_sparkline_7_data = [12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 74];

      createSparklineChart('#table-sparkline-1', table_sparkline_1_data);
      createSparklineChart('#table-sparkline-2', table_sparkline_2_data);
      createSparklineChart('#table-sparkline-3', table_sparkline_3_data);
      createSparklineChart('#table-sparkline-4', table_sparkline_4_data);
      createSparklineChart('#table-sparkline-5', table_sparkline_5_data);
      createSparklineChart('#table-sparkline-6', table_sparkline_6_data);
      createSparklineChart('#table-sparkline-7', table_sparkline_7_data);

      //-------------
      // - PIE CHART -
      //-------------

      const pie_chart_options = {
        series: [700, 500, 400, 600, 300, 100],
        chart: {
          type: 'donut',
        },
        labels: ['Chrome', 'Edge', 'FireFox', 'Safari', 'Opera', 'IE'],
        dataLabels: {
          enabled: false,
        },
        colors: ['#0d6efd', '#20c997', '#ffc107', '#d63384', '#6f42c1', '#adb5bd'],
      };

      const pie_chart = new ApexCharts(document.querySelector('#pie-chart'), pie_chart_options);
      pie_chart.render();

      //-----------------
      // - END PIE CHART -
      //-----------------
    </script>

<script>
  document.getElementById("exportButton").addEventListener("click", function() {
    // Example data (can be your table data or an array of objects)
    const data = [
      { Name: "John", Age: 30, City: "New York" },
      { Name: "Jane", Age: 25, City: "London" },
      { Name: "Mark", Age: 35, City: "Paris" }
    ];

    // Convert data to a worksheet
    const ws = XLSX.utils.json_to_sheet(data);

    // Create a new workbook and append the worksheet
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

    // Export the workbook as an Excel file
    XLSX.writeFile(wb, "ExportedData.xlsx");
  });
</script>

<script>
    // JavaScript to handle fetching tenants dynamically
    function fetchTenants(buildingId) {
        const tenantSelect = document.getElementById('tenantSelect');
        tenantSelect.innerHTML = '<option value="select_tenant" disabled selected>Loading Tenants...</option>'; // Show loading message

        if (buildingId) {
            // Make an AJAX request to a separate PHP script that fetches tenants
            // IMPORTANT: Replace 'get_tenants.php' with the actual path to your tenant-fetching script
            // This path should be relative to the HTML page, not the connect.php file
            fetch('fetch_tenants.php?building_id=' + buildingId) // Assuming get_tenants.php is in the same directory
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    tenantSelect.innerHTML = '<option value="select_tenant" disabled selected>Select Tenant</option>'; // Reset
                    if (data.length > 0) {
                        data.forEach(tenant => {
                            const option = document.createElement('option');
                            option.value = tenant.tenant_id;
                            // Concatenate first_name and middle_name for display
                            option.textContent = tenant.first_name + (tenant.middle_name ? ' ' + tenant.middle_name : '');
                            tenantSelect.appendChild(option);
                        });
                    } else {
                        tenantSelect.innerHTML = '<option value="select_tenant" disabled selected>No Tenants Found</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching tenants:', error);
                    tenantSelect.innerHTML = '<option value="select_tenant" disabled selected>Error loading tenants</option>';
                });
        } else {
            // If no building is selected, reset the tenant dropdown
            tenantSelect.innerHTML = '<option value="select_tenant" disabled selected>Select Tenant</option>';
        }
    }
</script>


<!-- <script> -->
<!-- <script>
document.getElementById('buildingSelect').addEventListener('change', function() {
    const buildingId = this.value;
    const tenantSelect = document.getElementById('tenantSelect');

    if (!buildingId) {
        tenantSelect.innerHTML = '<option value="" selected>Select a building first</option>';
        tenantSelect.disabled = true;
        return;
    }

    tenantSelect.disabled = false;
    tenantSelect.innerHTML = '<option value="">Loading tenants...</option>';

    fetch(`get_tenants.php?building_id=${buildingId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.error) throw new Error(data.error);

            if (data.length > 0) {
                let options = '<option value="" selected>Select Tenant</option>';
                data.forEach(tenant => {
                    // Exact field names from your database schema
                    const firstName = tenant.first_name || '';
                    const middleName = tenant.middle_name || '';

                    // Clean name formatting
                    const displayName = `${firstName} ${middleName}`.trim() || 'Unknown Tenant';
                    const unitInfo = tenant.unit_id ? ` - Unit ${tenant.unit_id}` : '';

                    options += `
                        <option value="${tenant.user_id}"
                                data-user-id="${tenant.user_id}"
                                data-unit-id="${tenant.unit_id || ''}">
                            ${displayName}${unitInfo}
                        </option>`;
                });
                tenantSelect.innerHTML = options;
            } else {
                tenantSelect.innerHTML = '<option value="" selected>No active tenants found</option>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            tenantSelect.innerHTML = `
                <option value="" selected>
                    Error: ${error.message.substring(0, 30)}${error.message.length > 30 ? '...' : ''}
                </option>`;
            tenantSelect.disabled = true;
        });
});
</script> -->

<!-- <script>
$(document).ready(function () {
  $('#buildingSelect').on('change', function () {
    var buildingId = $(this).find(':selected').data('id');
    var $tenantDropdown = $('#tenantSelect');

    $tenantDropdown.empty().append('<option selected>Loading tenants...</option>').prop('disabled', true);

    if (!buildingId) return;

    $.ajax({
      url: 'fetch_tenants_by_building.php',
      type: 'GET',
      dataType: 'json',
      data: { building_id: buildingId },
      success: function (response) {
        $tenantDropdown.empty();

        if (response.length > 0) {
          $tenantDropdown.append('<option value="" disabled selected>Select Tenant</option>');
          $.each(response, function (index, tenant) {
            $tenantDropdown.append(
              $('<option>', {
                value: tenant.id,
                text: 'Tenant ID: ' + tenant.id + ' - Phone: ' + tenant.phone_number,
                'data-user-id': tenant.user_id,
                'data-building-id': tenant.building_id
              })
            );
          });
          $tenantDropdown.prop('disabled', false);
        } else {
          $tenantDropdown.append('<option disabled>No tenants found</option>');
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching tenants:", error);
        $tenantDropdown.empty().append('<option disabled>Error loading tenants</option>');
      }
    });
  });
});
</script> -->

<script>
                 // Function to view the invoice in the modal
    function viewInvoice() {
      document.getElementById('viewInvoiceNumber').textContent = document.querySelector('[name="invoice_number"]').value;
      document.getElementById('viewInvoiceDate').textContent = document.querySelector('[name="invoice_date"]').value;
      document.getElementById('viewCustomerName').textContent = document.querySelector('[name="tenant_name"]').value;
      document.getElementById('viewCustomerAddress').textContent = document.querySelector('[name="customer_address"]').value;
      document.getElementById('viewCustomerEmail').textContent = document.querySelector('[name="customer_email"]').value;
      document.getElementById('viewPaymentMethod').textContent = document.querySelector('[name="payment_method"]').value;
      document.getElementById('viewShippingOption').textContent = document.querySelector('[name="shipping_option"]').value;
    }
                // Function to delete a row
                function deleteRow(button) {
                  // Find the row to delete
                  var row = button.parentElement.parentElement;
                  row.remove();
                  updateTotalAmount();
                }

                // Function to update the total amount of an item when quantity or price is changed
                function updateTotal(input) {
                  var row = input.parentElement.parentElement;
                  var quantity = row.querySelector('[name="item_quantity[]"]').value;
                  var price = row.querySelector('[name="item_price[]"]').value;
                  var totalCell = row.querySelector('[name="item_total[]"]');
                  totalCell.value = (quantity * price).toFixed(2);

                  updateTotalAmount();
                }

                // Function to calculate the total invoice amount
                function updateTotalAmount() {
                  var totalAmount = 0;
                  var rows = document.querySelectorAll('.items-table tbody tr');
                  rows.forEach(function(row) {
                    var total = parseFloat(row.querySelector('[name="item_total[]"]').value) || 0;
                    totalAmount += total;
                  });
                  document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
                }
              </script>

    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
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
      document.addEventListener('DOMContentLoaded', function () {
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

<!-- <script>
  $(document).ready(function() {
    // Fetch buildings when page loads
    $.ajax({
        url: 'fetch_building_rent.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var $dropdown = $('#buildingSelect');
            $dropdown.empty().append('<option value="" disabled selected>Select Property</option>');

            if (response && response.length > 0) {
                $.each(response, function(index, building) {
                    $dropdown.append(
                        $('<option>', {
                            value: building.building_name,
                            text: building.building_name,
                            'data-id': building.building_id
                        })
                    );
                });
            } else {
                $dropdown.append('<option value="" disabled>No buildings found</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching buildings:", error);
            $('#buildingSelect').append('<option value="" disabled>Error loading buildings</option>');
        }
    });
});
</script> -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
  const buildingSelect = document.getElementById('buildingSelect');
  const tenantSelect = document.getElementById('tenantSelect');

  buildingSelect.addEventListener('change', async function () {
    const buildingId = this.value;

    if (!buildingId) {
      resetTenantDropdown();
      return;
    }

    try {
      // Show loading state
      tenantSelect.innerHTML = '<option value="" disabled selected>Loading tenants...</option>';

      const response = await fetch(`fetch_tenants.php?building_id=${buildingId}`);

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();

      updateTenantDropdown(data);
    } catch (error) {
      console.error('Fetch error:', error);
      tenantSelect.innerHTML = '<option value="" disabled selected>Error loading tenants</option>';
    }
  });

  function updateTenantDropdown(tenants) {
    tenantSelect.innerHTML = '<option value="" disabled selected>Select Tenant</option>';

    if (Array.isArray(tenants)) { // ✅ Fixed syntax error here
      if (tenants.length > 0) {
        tenants.forEach(tenant => {
          const option = new Option(
            tenant.name,       // Text display
            tenant.tenant_id,  // Value
            false,             // Not default selected
            false              // Not default selected
          );
          tenantSelect.add(option);
        });
      } else {
        tenantSelect.innerHTML = '<option value="" disabled selected>No tenants found</option>';
      }
    } else {
      console.warn('Unexpected response format:', tenants);
      tenantSelect.innerHTML = '<option value="" disabled selected>Invalid data format</option>';
    }
  }

  function resetTenantDropdown() {
    tenantSelect.innerHTML = '<option value="" disabled selected>Select Tenant</option>';
  }
});
</script> -->



<!-- Required Scripts -->
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

<script>
  document.addEventListener("DOMContentLoaded", function () {
    function formatNumber(num) {
      return num.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function calculateRow(row) {
      const unitInput = row.querySelector(".unit-price");
      const quantityInput = row.querySelector(".quantity");
      const vatSelect = row.querySelector(".vat-option");
      const totalInput = row.querySelector(".total");

      const unitPrice = parseFloat(unitInput?.value) || 0;
      const quantity = parseFloat(quantityInput?.value) || 0;
      let subtotal = unitPrice * quantity;

      let vatAmount = 0;
      let total = subtotal;
      const vatType = vatSelect?.value;

      if (vatType === "inclusive") {
        subtotal = subtotal / 1.16;
        vatAmount = total - subtotal;
      } else if (vatType === "exclusive") {
        vatAmount = subtotal * 0.16;
        total += vatAmount;
      } else if (vatType === "zero") {
        vatAmount = 0; // VAT 0% for Zero Rated
        total = subtotal; // No tax added for Zero Rated
      } else if (vatType === "exempted") {
        vatAmount = 0; // No VAT for Exempted
        total = subtotal; // No tax added for Exempted
      }

      totalInput.value = formatNumber(total);
      return { subtotal, vatAmount, total, vatType };
    }

    function updateTotalAmount() {
      let subtotalSum = 0, taxSum = 0, grandTotal = 0, exemptedSum = 0, zeroVatSum = 0;
      let vat16Used = false, vat0Used = false, exemptedUsed = false;

      document.querySelectorAll(".items-table tbody tr").forEach(row => {
        if (row.querySelector(".unit-price")) {
          const { subtotal, vatAmount, total, vatType } = calculateRow(row);
          subtotalSum += subtotal;
          taxSum += vatAmount;
          grandTotal += total;

          if (vatType === "inclusive" || vatType === "exclusive") {
            vat16Used = true;
          } else if (vatType === "zero") {
            zeroVatSum += 0; // Zero Rated has zero VAT
            vat0Used = true;
          } else if (vatType === "exempted") {
            exemptedSum += 0; // Exempted has zero VAT
            exemptedUsed = true;
          }
        }
      });

      createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, zeroVatSum, exemptedSum, vat16Used, vat0Used, exemptedUsed });
    }

    function createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, zeroVatSum, exemptedSum, vat16Used, vat0Used, exemptedUsed }) {
      let summaryTable = document.querySelector(".summary-table");

      if (!summaryTable) {
        summaryTable = document.createElement("table");
        summaryTable.className = "summary-table table table-bordered";
        summaryTable.style = "width: 20%; float: right; font-size: 0.8rem; margin-top: 10px;";
        summaryTable.innerHTML = `<tbody></tbody>`;
        document.querySelector(".items-table").after(summaryTable);
      }

      const tbody = summaryTable.querySelector("tbody");
      tbody.innerHTML = `
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">Sub-total</th>
          <td><input type="text" class="form-control" value="${formatNumber(subtotalSum)}" readonly style="padding: 5px;"></td>
        </tr>
        ${vat16Used ? `
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">VAT 16%</th>
          <td><input type="text" class="form-control" value="${formatNumber(taxSum)}" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        ${vat0Used ? `
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">VAT 0%</th>
          <td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        ${exemptedUsed ? `
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">Exempted</th>
          <td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">Total</th>
          <td><input type="text" class="form-control" value="${formatNumber(grandTotal)}" readonly style="padding: 5px;"></td>
        </tr>
      `;
    }

    function attachEvents(row) {
      const inputs = [".unit-price", ".quantity", ".vat-option"];
      inputs.forEach(sel => {
        const el = row.querySelector(sel);
        if (el) {
          el.addEventListener("input", updateTotalAmount);
          el.addEventListener("change", updateTotalAmount);
        }
      });
    }

    window.addRow = function () {
      const table = document.querySelector(".items-table tbody");
      const newRow = document.createElement("tr");
      newRow.innerHTML = `
        <td>
          <select name="payment_method" required>
            <option value="" disabled selected>Select Option</option>
            <option value="credit_card">Rent</option>
            <option value="paypal">Water Bill</option>
            <option value="bank_transfer">Garbage</option>
          </select>
        </td>
        <td><textarea name="Description" placeholder="Description" rows="1" required></textarea></td>
        <td><input type="number" class="form-control quantity" placeholder="1"></td>
        <td><input type="number" class="form-control unit-price" placeholder="123"></td>
        <td>
          <select class="form-select vat-option">
            <option value="" disabled selected>Select Option</option>
            <option value="inclusive">VAT 16% Inclusive</option>
            <option value="exclusive">VAT 16% Exclusive</option>
            <option value="zero">Zero Rated</option>
            <option value="exempted">Exempted</option>
          </select>
        </td>
        <td>
          <input type="text" class="form-control total" placeholder="0" readonly>
          <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
            <i class="fa fa-trash" style="font-size: 12px;"></i>
          </button>
        </td>
      `;
      table.appendChild(newRow);
      attachEvents(newRow);
    };

    window.deleteRow = function (btn) {
      btn.closest("tr").remove();
      updateTotalAmount();
    };

    document.querySelectorAll(".items-table tbody tr").forEach(attachEvents);
    updateTotalAmount();
  });
</script>

</body>
<!--end::Body-->

</html>
