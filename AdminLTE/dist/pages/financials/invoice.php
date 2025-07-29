<?php
include '../db/connect.php';

$stmt = $pdo->prepare("SELECT account_code, account_name FROM chart_of_accounts ORDER BY account_name ASC");
$stmt->execute();
$accountItems = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Determine if this is a draft (adjust based on your form input)
$isDraft = isset($_POST['status']) && $_POST['status'] === 'draft';

// Get the highest existing invoice number for the correct prefix
$prefix = $isDraft ? 'DFT' : 'INV';
$stmt = $pdo->prepare("SELECT invoice_number FROM invoice WHERE invoice_number LIKE ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$prefix . '%']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Extract the highest number
if ($row && preg_match('/' . $prefix . '(\d+)/', $row['invoice_number'], $matches)) {
    $lastNumber = (int)$matches[1];
    $newNumber = $lastNumber + 1;
} else {
    $newNumber = 1; // Start at 1 if no previous invoice of this type
}

// Generate the new invoice number (e.g., DFT001 or INV001)
$invoiceNumber = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

try {
    $stmt = $pdo->prepare("SELECT building_id FROM buildings ORDER BY building_id");
    $stmt->execute();
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $buildings = [];
    // You might want to log this error in production
    error_log("Error fetching buildings: " . $e->getMessage());
}



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


$stmt = $pdo->query("
    SELECT
        i.invoice_number,
        i.invoice_date,
        i.due_date,
        i.total,
        CONCAT(u.first_name, ' ', u.middle_name) AS tenant_name
    FROM invoice i
    LEFT JOIN users u ON i.tenant = u.id
    ORDER BY i.invoice_number DESC
");

$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);



$buildingsStmt = $pdo->query(
    "SELECT building_id, building_name FROM buildings ORDER BY building_name"
);
$buildings = $buildingsStmt->fetchAll(PDO::FETCH_ASSOC);
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

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <!-- Add these to your head or before closing body -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--Tailwind CSS  -->
<style>
  /* ================ */
/* BASE STYLES */
/* ================ */
a {
  text-decoration: none;
  color: inherit;
}

ul {
  list-style: none;
}

.container {
  width: 90%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 15px;
}

/* ================ */
/* BUTTON STYLES */
/* ================ */
.btn {
  display: inline-block;
  padding: 8px 16px;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 14px;
}

.btn-primary {
  background-color: #2a5bd7;
  color: white;
  border: 1px solid #2a5bd7;
}

.btn-primary:hover {
  background-color: #1e4bc4;
  border-color: #1e4bc4;
}

.btn-outline {
  background-color: transparent;
  color: #2a5bd7;
  border: 1px solid #2a5bd7;
}

.btn-outline:hover {
  background-color: #f0f5ff;
}

.btn-icon {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.pay-btn {
  margin-top: 6px;
  padding: 4px 10px;
  font-size: 12px;
  font-weight: 600;
  background-color: #FFC107;
  color: #00192D;
  border: none;
  border-radius: 20px;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.pay-btn:hover {
  background-color: #e6ae00;
  color: white;
}

/* ================ */
/* LAYOUT STYLES */
/* ================ */
header {
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 0;
}

.logo {
  font-size: 24px;
  font-weight: 700;
  color: #2a5bd7;
}

.logo span {
  color: #ff6b00;
}

.header-actions {
  display: flex;
  gap: 15px;
  align-items: center;
}

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  color: #4a5568;
}

.app-container {
  display: flex;
  min-height: calc(100vh - 66px);
}

.main-content {
  flex: 1;
  padding: 20px;
  background-color: #f5f7fa;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.page-title {
  font-size: 24px;
  color: #1a365d;
}

.page-actions {
  display: flex;
  gap: 10px;
}

/* ================ */
/* INVOICE TABLE STYLES */
/* ================ */
.invoice-list-container {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.invoice-list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  border-bottom: 1px solid #e2e8f0;
}

.invoice-list-title {
  font-weight: 600;
  color: #1a365d;
}

.invoice-list-filters {
  display: flex;
  gap: 15px;
}

.invoice-item {
  display: flex;
  align-items: center;
  padding: 12px 15px;
  border-bottom: 1px solid #e2e8f0;
  transition: background-color 0.2s ease;
}

.invoice-item:hover {
  background-color: #f8fafc;
}

.invoice-header {
  background-color: #f8f9fa;
  font-weight: bold;
  border-bottom: 2px solid #dee2e6;
}

/* Column Widths */
.invoice-checkbox {
  flex: 0 0 40px;
  min-width: 40px;
}

.invoice-number {
  flex: 1 0 120px;
  min-width: 120px;
}

.invoice-customer {
  flex: 1 0 150px;
  min-width: 150px;
}

.invoice-date {
  flex: 1 0 100px;
  min-width: 100px;
}

.invoice-sub-total {
  flex: 1 0 100px;
  min-width: 100px;
  text-align: right;
}

.invoice-taxes {
  flex: 1 0 100px;
  min-width: 100px;
  text-align: right;
}

.invoice-amount {
  flex: 1 0 100px;
  min-width: 100px;
  text-align: right;
}

.invoice-status {
  flex: 1 0 120px;
  min-width: 120px;
  text-align: center;
}

.invoice-actions {
  flex: 0 0 80px;
  min-width: 80px;
  text-align: right;
}

.over-flow {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Status Badges */
.status-badge {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
}

.status-paid {
  background-color: #e6fffa;
  color: #38b2ac;
}

.status-pending {
  background-color: #fffaf0;
  color: #dd6b20;
}

.status-overdue {
  background-color: #fff5f5;
  color: #f56565;
}

.action-btn {
  background: none;
  border: none;
  color: #718096;
  cursor: pointer;
  padding: 5px;
}

.action-btn:hover {
  color: #2a5bd7;
}

/* ================ */
/* FORM STYLES */
/* ================ */
.invoice-form-container {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

.form-section {
  margin-bottom: 30px;
}

.section-title {
  font-size: 18px;
  color: #1a365d;
  margin-bottom: 15px;
  padding-bottom: 10px;
  border-bottom: 1px solid #e2e8f0;
}

.form-row {
  display: flex;
  gap: 20px;
  margin-bottom: 15px;
}

.form-group {
  flex: 1;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #4a5568;
  font-size: 14px;
}

.form-control {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  font-size: 14px;
  transition: border-color 0.3s ease;
}

.form-control:focus {
  outline: none;
  border-color: #2a5bd7;
}

.form-control-sm {
  padding: 6px 8px;
  font-size: 13px;
}

/* Items Table */
.items-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.items-table th {
  text-align: left;
  padding: 10px;
  background-color: #f8fafc;
  color: #718096;
  font-weight: 500;
  font-size: 13px;
}

.items-table td {
  padding: 12px 10px;
  border-bottom: 1px solid #e2e8f0;
}

.item-row input {
  width: 100%;
  border: 1px solid #e2e8f0;
  padding: 8px;
  border-radius: 4px;
}

.item-row input:focus {
  outline: none;
  border-color: #2a5bd7;
}

.item-name {
  width: 40%;
}

.item-qty,
        .item-rate,
        .item-amount {
  width: 15%;
}

.item-actions {
  width: 15%;
  text-align: center;
}

.remove-item {
  color: #f56565;
  cursor: pointer;
}

.add-item-btn {
  background-color: transparent;
  border: none;
  color: #2a5bd7;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 8px;
}

/* Form Actions */
.form-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #e2e8f0;
}

.action-left {
  display: flex;
  gap: 10px;
}

.action-right {
  display: flex;
  gap: 10px;
}

/* ================ */
/* COMPONENT STYLES */
/* ================ */
.filter-dropdown {
  position: relative;
}

.filter-btn {
  background-color: transparent;
  border: 1px solid #e2e8f0;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  color: #4a5568;
}

.filter-btn:hover {
  background-color: #f8fafc;
}

.dropdown-menu {
  position: absolute;
  right: 0;
  top: 100%;
  background-color: white;
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  min-width: 200px;
  z-index: 10;
  display: none;
}

        .filter-dropdown:hover .dropdown-menu {
          display: block;
        }

.dropdown-menu ul {
  padding: 10px 0;
}

.dropdown-menu li {
  padding: 8px 15px;
  cursor: pointer;
}

.dropdown-menu li:hover {
  background-color: #f8fafc;
}

/* Payment Status Badges */
.payment-status-badge {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  min-width: 60px;
  text-align: center;
  border: 1px solid #FFC107;
}

.badge-payment-paid {
  background-color: #e6f7ee;
  color: #00192D;
}

.badge-payment-partial {
  background-color: #fff8e6;
  color: #00192D;
  border-color: #FFC107;
}

.badge-payment-unpaid {
  background-color: #ffebee;
  color: #00192D;
  border-color: #FFC107;
}

/* Status Badges */
.badge-sent {
  background-color: #e3f2fd;
  color: #1565c0;
}

.badge-paid {
  background-color: #e8f5e9;
  color: #2e7d32;
}

.badge-overdue {
  background-color: #ffebee;
  color: #c62828;
}

.badge-cancelled {
  background-color: #efebe9;
  color: #4e342e;
}

.badge-draft {
  background-color: #e0e0e0;
  color: #424242;
}

/* ================ */
/* MODAL & PREVIEW STYLES */
/* ================ */
#invoicePreviewPanel {
  position: fixed;
  top: 0;
  right: -100%;
  width: 400px;
  height: 100vh;
  background: #fff;
  box-shadow: -2px 0 8px rgba(0,0,0,0.3);
  transition: right 0.3s ease-in-out;
  z-index: 9999;
}

#invoicePreviewPanel.active {
  right: 0;
}

.preview-content {
  padding: 20px;
  overflow-y: auto;
  height: 100%;
}

.close-btn {
  position: absolute;
  right: 10px;
  top: 10px;
  font-size: 24px;
  background: none;
  border: none;
  cursor: pointer;
}

/* ================ */
/* RESPONSIVE STYLES */
/* ================ */
@media (max-width: 768px) {
  .app-container {
    flex-direction: column;
  }

  .invoice-item {
    flex-wrap: wrap;
    gap: 10px;
  }

  .form-row {
    flex-direction: column;
    gap: 10px;
  }

  .items-table th,
  .items-table td {
    padding: 8px 5px;
  }

  /* Adjust column widths for mobile */
  .invoice-checkbox,
  .invoice-number,
  .invoice-customer,
  .invoice-date,
  .invoice-sub-total,
  .invoice-taxes,
  .invoice-amount,
  .invoice-status,
  .invoice-actions {
    flex: 1 1 100%;
    min-width: 100%;
    text-align: left;
  }
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

            <!-- Main Content -->
            <div class="main-content">
                <!-- Invoice List View (Default) -->
                <div id="invoice-list-view">
                    <div class="page-header">
                        <h1 class="page-title"> 🧾 Invoices</h1>
                        <div class="page-actions">
                            <button class="btn btn-outline" style="color: #FFC107; background-color:#00192D;" id="filterButton">
                                <i class="fas fa-filter"></i> Filter
                            </button>


                            <!-- Filter Modal (hidden by default) -->
                            <div id="filterModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4)">
                                <div style="background-color:#00192D; margin:5% auto; padding:20px; border:1px solid #FFC107; width:80%; max-width:600px; color:white;">
                                    <span style="float:right; cursor:pointer" id="closeFilter">&times;</span>
                                    <h3>Filter Invoices</h3>

                                    <div style="margin-bottom:15px;">
                                        <label>Status:</label>
                                        <select id="statusFilter" class="form-control" style="background-color:#00192D; color:#FFC107; border:1px solid #FFC107">
                                            <option value="">All</option>
                                            <option value="draft">Draft</option>
                                            <option value="sent">Sent</option>
                                            <option value="paid">Paid</option>
                                            <option value="overdue">Overdue</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>

                                    <div style="margin-bottom:15px;">
                                        <label>Payment Status:</label>
                                        <select id="paymentFilter" class="form-control" style="background-color:#00192D; color:#FFC107; border:1px solid #FFC107">
                                            <option value="">All</option>
                                            <option value="unpaid">Unpaid</option>
                                            <option value="partial">Partial</option>
                                            <option value="paid">Paid</option>
                                        </select>
                                    </div>

                                    <div style="margin-bottom:15px;">
                                        <label>Date Range:</label>
                                        <div style="display:flex; gap:10px;">
                                            <input type="date" id="dateFrom" class="form-control" style="background-color:#00192D; color:#FFC107; border:1px solid #FFC107">
                                            <input type="date" id="dateTo" class="form-control" style="background-color:#00192D; color:#FFC107; border:1px solid #FFC107">
                                        </div>
                                    </div>

                                    <button id="applyFilter" class="btn" style="background-color:#FFC107; color:#00192D">Apply Filters</button>
                                    <button id="resetFilter" class="btn btn-outline" style="color:#FFC107; border-color:#FFC107">Reset</button>
                                </div>
                            </div>
                            <!-- <button class="btn btn-outline" style="color: #FFC107; background-color:#00192D;">
                            <i class="fas fa-download"></i> Export
                        </button> -->
                            <button class="btn" id="create-invoice-btn" style="color: #FFC107; background-color:#00192D;">
                                <i class="fas fa-plus"></i> Create Invoice
                            </button>
                        </div>
                    </div>

                    <div class="invoice-list-container">
                        <div class="invoice-list-header">
                            <div class="invoice-list-title">All Invoices</div>
                            <div class="invoice-list-filters">
                                <div class="filter-dropdown">
                                    <button class="filter-btn">
                                        <span>Status: All</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <ul>
                                            <li>All</li>
                                            <li>Paid</li>
                                            <li>Pending</li>
                                            <li>Overdue</li>
                                            <li>Draft</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="filter-dropdown">
                                    <button class="filter-btn">
                                        <span>Date: This Month</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <ul>
                                            <li>Today</li>
                                            <li>This Week</li>
                                            <li>This Month</li>
                                            <li>This Quarter</li>
                                            <li>Custom Range</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>



                    <div class="invoice-item invoice-header">
    <div class="invoice-checkbox">
        <input type="checkbox">
    </div>
    <div class="invoice-number">
        <div class="over-flow" title="Invoice #">Invoice</div>
    </div>
    <div class="invoice-customer">
        <div class="over-flow" title="Customer">Tenant</div>
    </div>
    <div class="invoice-date">
        <div class="over-flow" title="Date">Date</div>
    </div>
    <div class="invoice-date">
        <div class="over-flow" title="Due Date">Due Date</div>
    </div>
    <div class="invoice-sub-total">
        <div class="over-flow" title="Sub-Total">Sub-Total</div>
    </div>
    <div class="invoice-taxes">
        <div class="over-flow" title="Taxes">Taxes</div>
    </div>
    <div class="invoice-amount">
        <div class="over-flow" title="Amount">Total</div>
    </div>
    <div class="invoice-status">
        <div class="over-flow" title="Status">Status</div>
    </div>
    <div class="invoice-status">
        <div class="over-flow" title="Payment Status">Payment Status</div>
    </div>
    <div class="invoice-actions">
        <div class="over-flow" title="Actions">Actions</div>
    </div>
</div>


                        <?php
                        // ----------------------------------------------------
                        // 1) Fetch invoices with tenant details and payment summary
                        // ----------------------------------------------------
                        $stmt = $pdo->query("
    SELECT
        i.id,
        i.invoice_number,
        i.invoice_date,
        i.due_date,
        i.sub_total,
        i.total,
        i.taxes,
        i.status,
        i.payment_status,
        CONCAT(u.first_name, ' ', u.middle_name) AS tenant_name,
        (SELECT COALESCE(SUM(p.amount), 0)
         FROM payments p
         WHERE p.invoice_id = i.id) AS paid_amount,
        i.building_id,
        i.account_item,
        i.description
    FROM invoice i
    LEFT JOIN users u ON u.id = i.tenant
    ORDER BY i.created_at DESC
");

                        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ----------------------------------------------------
// 2) Output each invoice item
// ----------------------------------------------------


foreach ($invoices as $invoice) {

    $tenantName = $invoice['tenant_name'] ?? 'Unknown Tenant';
    $invoiceDate = $invoice['invoice_date'] == '0000-00-00' ? 'Draft' : date('M d, Y', strtotime($invoice['invoice_date']));
    $dueDate = $invoice['due_date'] == '0000-00-00' ? 'Not set' : date('M d, Y', strtotime($invoice['due_date']));
    $subtotalFormatted = number_format($invoice['sub_total'], 2);
    $totalFormatted = number_format($invoice['total'], 2);
    $taxFormatted = $invoice['taxes'] ?: '0.00';
    $paidFormatted = number_format($invoice['paid_amount'], 2);
    $balance = $invoice['total'] - $invoice['paid_amount'];
    $balanceFormatted = number_format($balance, 2);


                            // Calculate overdue status
                            $isOverdue = false;
                            $overdueDays = 0;
                            if ($invoice['due_date'] != '0000-00-00' && $invoice['status'] != 'paid' && $invoice['status'] != 'cancelled') {
                                $today = new DateTime();
                                $dueDateObj = new DateTime($invoice['due_date']);
                                if ($today > $dueDateObj) {
                                    $isOverdue = true;
                                    $overdueDays = $today->diff($dueDateObj)->days;
                                }
                            }

                            // Determine status badge
                            $statusClass = 'badge-';
                            $statusText = ucfirst($invoice['status']);

                            switch ($invoice['status']) {
                                case 'draft':
                                    $statusClass .= 'draft';
                                    break;
                                case 'sent':
                                    $statusClass .= $isOverdue ? 'overdue' : 'sent';
                                    $statusText = $isOverdue ? 'Overdue (' . $overdueDays . 'd)' : 'Sent';
                                    break;
                                case 'paid':
                                    $statusClass .= 'paid';
                                    break;
                                case 'cancelled':
                                    $statusClass .= 'cancelled';
                                    break;
                                default:
                                    $statusClass .= 'draft';
                            }

                            // Payment status with amounts - updated logic
                            $paymentStatusClass = 'badge-';
                            $paymentStatusText = '';

                            // First check if any payment has been made
                            if ($invoice['paid_amount'] > 0) {
                                if ($invoice['paid_amount'] >= $invoice['total']) {
                                    // Fully paid
                                    $paymentStatusClass .= 'paid';
                                    $paymentStatusText = 'Paid (KES ' . $paidAmount . ')';
                                    $invoice['payment_status'] = 'paid'; // Update status in case it wasn't synced
                                } else {
                                    // Partial payment
                                    $paymentStatusClass .= 'partial';
                                    $paymentStatusText = 'Partial (KES ' . $paidAmount . ' of ' . $totalAmount . ')';
                                    $invoice['payment_status'] = 'partial'; // Update status in case it wasn't synced
                                }
                            } else {
                                // No payments made
                                $paymentStatusClass .= 'unpaid';
                                $paymentStatusText = $isOverdue ? 'Overdue (' . $overdueDays . 'd)' : 'Unpaid';
                                $invoice['payment_status'] = 'unpaid'; // Update status in case it wasn't synced
                            }

                            echo '<div class="invoice-item" onclick="openInvoiceDetails(' . $invoice['id'] . ')">';
                            echo '<div class="invoice-checkbox">
            <input type="checkbox" onclick="event.stopPropagation()">
          </div>
          <div class="invoice-number">' . htmlspecialchars($invoice['invoice_number']) . '</div>
          <div class="invoice-customer" title="' . htmlspecialchars($invoice['description']) . '">
              ' . htmlspecialchars($tenantName) . '
          </div>
          <div class="invoice-date">' . $invoiceDate . '</div>
          <div class="invoice-date' . ($isOverdue ? ' text-danger' : '') . '">
              ' . $dueDate . '
          </div>
          <div class="invoice-amount">KES ' . number_format($invoice['sub_total'], 2) . '</div>
          <div class="invoice-amount">VAT: ' . htmlspecialchars($invoice['taxes'] ?: '0.00') . '</div>
          <div class="invoice-amount">Total: KES ' . number_format($invoice['total'], 2) . '</div>

          <div class="invoice-status">
              <span class="status-badge ' . $statusClass . '">' . $statusText . '</span>
          </div>
          <div class="invoice-status">
              <span class="status-badge ' . $paymentStatusClass . '">' . $paymentStatusText . '</span>';

                            // Show payment button if applicable - updated logic
                            if ($invoice['status'] !== 'draft' && $invoice['status'] !== 'cancelled' && $invoice['paid_amount'] < $invoice['total']) {
                                $buttonText = $invoice['paid_amount'] > 0 ? 'Add Payment' : 'Pay Now';
                                $balance = $invoice['total'] - $invoice['paid_amount'];

                                echo '<br>
              <button class="btn pay-btn btn-sm mt-1"
                  onclick="event.stopPropagation(); openPayModal(this)"
                  data-invoice-id="' . $invoice['id'] . '"
                  data-tenant="' . htmlspecialchars($tenantName) . '"
                  data-total="' . $invoice['total'] . '"
                  data-paid="' . $invoice['paid_amount'] . '"
                  data-balance="' . $balance . '"
                  data-account-item="' . htmlspecialchars($invoice['account_item']) . '"
                  data-description="' . htmlspecialchars($invoice['description']) . '">
                  <i class="fas fa-credit-card me-1"></i>
                  ' . $buttonText . '
              </button>';
                            }

                            echo '</div>
          <div class="invoice-actions dropdown">
              <button class="action-btn dropdown-toggle" onclick="event.stopPropagation()" data-bs-toggle="dropdown">
                  <i class="fas fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="#" onclick="viewInvoice(' . $invoice['id'] . ')">
                      <i class="fas fa-eye me-2"></i>View Details
                  </a></li>';

                            // if ($invoice['status'] !== 'cancelled') {
                            //     echo '<li><a class="dropdown-item" href="#" onclick="downloadInvoice(' . $invoice['id'] . ')">
                            //               <i class="fas fa-file-pdf me-2"></i>Download PDF
                            //           </a></li>';
                            // }

                            // Edit option - available for drafts and sent invoices without payments
                            // if ($invoice['status'] === 'draft' || ($invoice['status'] === 'sent' && $invoice['paid_amount'] == 0)) {
                            //     echo '<li><a class="dropdown-item" href="#" onclick="editInvoice(' . $invoice['id'] . ')">
                            //               <i class="fas fa-edit me-2"></i>Edit Invoice
                            //           </a></li>';
                            // }
                            if ($invoice['status'] === 'draft' || ($invoice['status'] === 'sent' && $invoice['paid_amount'] == 0)) {
                                echo '<li><a class="dropdown-item" href="invoice_edit.php?id=' . $invoice['id'] . '">
                  <i class="fas fa-edit me-2"></i>Edit Invoice
              </a></li>';
                            }

                            echo '<li><hr class="dropdown-divider"></li>';

                            //  Delete option - only for drafts and cancelled invoices
                            // Delete option - only for drafts and cancelled invoices
                            if ($invoice['status'] === 'draft' || $invoice['status'] === 'cancelled') {
                                echo '<li><a class="dropdown-item text-danger" href="#" onclick="confirmDeleteInvoice(' . $invoice['id'] . ')">
            <i class="fas fa-trash-alt me-2"></i>Delete Invoice
        </a></li>';
                            }

                            // Cancel/Restore options
                            if ($invoice['status'] !== 'cancelled' && $invoice['status'] !== 'paid') {
                                echo '<li><a class="dropdown-item text-danger" href="#" onclick="confirmCancelInvoice(' . $invoice['id'] . ')">
                  <i class="fas fa-ban me-2"></i>Cancel Invoice
              </a></li>';
                            } else if ($invoice['status'] === 'cancelled') {
                                echo '<li><a class="dropdown-item" href="#" onclick="restoreInvoice(' . $invoice['id'] . ')">
                  <i class="fas fa-undo me-2"></i>Restore Invoice
              </a></li>';
                            }

                            echo '</ul>
          </div>
      </div>';
                        }
                        ?>


                        <!-- ✅ PAYMENT MODAL -->
                        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <form id="paymentForm" method="post" action="/originalTwo/AdminLTE/dist/pages/financials/submit_payment.php">
                                    <div class="modal-content shadow-lg border-0 rounded-4">

                                        <!-- Modal Header -->
                                        <div class="modal-header" style="background-color: #00192D;">
                                            <h5 class="modal-title text-warning fw-semibold" id="paymentModalLabel">
                                                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Make Payment
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="modal-body px-4 py-4 bg-light-subtle">
                                            <input type="hidden" name="invoice_id" id="invoiceId">
                                            <input type="hidden" id="invoiceTotal" name="total_amount" value="0">

                                            <div class="row g-4">
                                                <!-- Payment Date -->
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold text-dark">
                                                        <i class="fa-regular fa-calendar-days text-warning me-1"></i> Payment Date
                                                    </label>
                                                    <input type="date" class="form-control border-warning" id="paymentDate" name="payment_date" required>
                                                    <div class="form-text text-danger small" id="dateError" style="display: none;">
                                                        ⚠️ Future dates are not allowed.
                                                    </div>
                                                </div>

                                                <!-- Tenant Name -->
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold text-dark">
                                                        <i class="fa-solid fa-user-tag text-warning me-1"></i> Tenant Name
                                                    </label>
                                                    <input type="text" class="form-control border-warning" id="tenantName" name="tenant" readonly>
                                                </div>

                                                <!-- Payment Method -->
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold text-dark">
                                                        <i class="fa-solid fa-hand-holding-dollar text-warning me-1"></i> Payment Method
                                                    </label>
                                                    <select class="form-select border-warning text-dark" name="payment_method" required>
                                                        <option value="">-- Choose Method --</option>
                                                        <option value="MPESA">📱 MPESA</option>
                                                        <option value="Bank">🏦 Bank</option>
                                                        <option value="Cash">💵 Cash</option>
                                                    </select>
                                                </div>

                                                <!-- Amount -->
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold text-dark">
                                                        <i class="fa-solid fa-sack-dollar text-warning me-1"></i> Amount (KES)
                                                    </label>
                                                    <input type="number" class="form-control border-warning" id="amount" name="amount" step="0.01" min="0" required>
                                                    <div id="paymentStatus" class="mt-2 small fw-semibold"></div>
                                                </div>

                                                <!-- Reference Number -->
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold text-dark">
                                                        <i class="fa-solid fa-barcode text-warning me-1"></i> Reference Number
                                                    </label>
                                                    <input type="text" class="form-control border-warning" name="reference_number" placeholder="e.g. MPESA code or bank slip" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="modal-footer px-4 py-3" style="background-color: #00192D;">
                                            <button type="submit" class="btn fw-semibold" style="background-color: #FFC107; color: #00192D;">
                                                <i class="fa-solid fa-paper-plane me-1"></i> Submit Payment
                                            </button>
                                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                                                <i class="fa-solid fa-xmark-circle me-1"></i> Cancel
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="invoice-list">
                            <!-- Invoice Item -->
                            <div class="invoice-item">


                                <!-- <div class="invoice-checkbox">
                                <input type="checkbox">
                            </div>
                            <div class="invoice-number">INV-2023-005</div>
                            <div class="invoice-customer">Umbrella Corp</div>
                            <div class="invoice-date">May 25, 2023</div>
                            <div class="invoice-amount">$1,980.00</div>
                            <div class="invoice-status">
                                <span class="status-badge status-pending">Pending</span>
                            </div>
                            <div class="invoice-actions">
                                <button class="action-btn">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div> -->
                            </div>
                        </div>
                    </div>
                </div>




                <!-- Create Invoice View (Hidden by default) -->
                <div id="create-invoice-view" style="display: none;">
                    <input type="hidden" id="invoice-id" name="invoice_id">

                    <div class="page-header">
                        <h1 class="page-title">Create Invoice</h1>
                        <div class="page-actions">
                            <button class="btn btn-outline" id="cancel-invoice-btn" style="color: #FFC107; background-color:#00192D;">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <!-- <button  id="saveDraftBtn"  class="btn btn-outline" style="color: #FFC107; background-color:#00192D;">
                            <i class="fas fa-save"></i> Save Draft
                        </button> -->
                            <button id="saveDraftBtn" class="btn btn-outline" style="color: #FFC107; background-color:#00192D;" type="button">
                                <i class="fas fa-save"></i> Save Draft
                            </button>
                            <button class="btn btn-primary" id="preview-invoice-btn" style="color: #FFC107; background-color:#00192D;">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                        </div>
                    </div>

                    <!-- Sliding Preview Panel -->
                    <div id="invoicePreviewPanel">
                        <div class="preview-content">
                            <button id="closePreview" class="close-btn">&times;</button>
                            <h3>Invoice Preview</h3>
                            <div id="previewContent">
                                <!-- Populated by JS -->
                            </div>
                        </div>
                    </div>

                    <div class="invoice-form-container">
                        <!-- Customer Section -->
                        <div class="form-section">
                            <h3 class="section-title">Tenant Details</h3>
                            <form method="POST" action="submit_invoice.php">
                                <div class="form-row">

                                    <!-- Existing Invoice # input -->
                                    <div class="form-group">
                                        <label for="invoice-number">Invoice #</label>
                                        <input type="text"
                                            id="invoice-number"
                                            value="<?= $invoiceNumber ?>"
                                            class="form-control"
                                            readonly>
                                        <input type="hidden"
                                            name="invoice_number"
                                            value="<?= $invoiceNumber ?>">
                                    </div>



                                    <!-- ▲ NEW: Building selector -->
                                    <div class="form-group">
                                        <label for="building">Building</label>
                                        <select id="building" name="building_id" class="form-control" required>
                                            <option value="">Select a Building</option>
                                            <?php foreach ($buildings as $b): ?>
                                                <option value="<?= $b['building_id'] ?>">
                                                    <?= htmlspecialchars($b['building_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>


                                    <!-- ▼ Tenant selector (will be filled by JS) -->
                                    <div class="form-group">
                                        <label for="customer">Tenant</label>
                                        <select id="customer"
                                            name="tenant"
                                            class="form-control"
                                            required
                                            disabled>
                                            <option value="">Select a Tenant</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="invoice-date">Invoice Date</label>
                                        <input type="date"
                                            id="invoice-date"
                                            name="invoice_date"
                                            class="form-control"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="due-date">Due Date</label>
                                        <input type="date"
                                            id="due-date"
                                            name="due_date"
                                            class="form-control"
                                            required>
                                    </div>
                                </div>


                                <!-- Items Section -->
                                <div class="form-section">
                                    <h3 class="section-title">Items</h3>
                                    <table class="items-table">
                                        <thead>
                                            <tr>
                                                <th>Item (Service)</th>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Unit Price</th>
                                                <th>Taxes</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select id="account-item" name="account_item[]" class="select-account searchable-select" required>
                                                        <option value="" disabled selected>Select Account Item</option>
                                                        <?php foreach ($accountItems as $item): ?>
                                                            <option value="<?= htmlspecialchars($item['account_code']) ?>">
                                                                <?= htmlspecialchars($item['account_name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <style>

                                                </style>

          <td><textarea id="description" name="description[]" placeholder="Description" rows="1" required></textarea></td>
          <td><input id="quantity" type="number" name="quantity[]" class="form-control quantity" placeholder="1" required></td>
          <td><input id="unit_price"  type="number" name="unit_price[]" class="form-control unit-price" placeholder="123" required></td>
          <td>
            <select  id="taxes" name="vat_type[]" class="form-select vat-option" required>
              <option value="" disabled selected>Select Option</option>
              <option value="inclusive">VAT 16% Inclusive</option>
              <option value="exclusive">VAT 16% Exclusive</option>
              <option value="zero">Zero Rated</option>
              <option value="exempted">Exempted</option>
            </select>

          </td>
          <td>
             <input id="total" type="number"  class="form-control total" placeholder="0" readonly  style="display:none;">
            <button type="button"  class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
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

                                <!-- Notes & Terms -->
                                <div class="form-section">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="notes">Notes(Optional)</label>
                                            <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Thank you for your business!"></textarea>
                                        </div>
                                        <!-- <div class="form-group">
                                <label for="terms">Terms & Conditions</label>
                                <textarea id="terms"  name="terms_conditions" class="form-control" rows="3" placeholder="Payment due within 15 days"></textarea>
                            </div> -->
                                    </div>
                                </div>


                                <!-- Form Actions -->
                                <div class="form-actions">
                                    <div class="action-left">
                                        <button class="btn btn-outline">
                                            <i class="fas fa-paperclip"></i> Attach File
                                        </button>
                                    </div>
                                    <div class="action-right">
                                        <!-- <button class="btn btn-outline">
                                 Send
                            </button> -->
                                        <button type="submit" style="background-color: #00192D; color: #FFC107; padding: 8px 16px; border: none; border-radius: 4px;">
                                            <i class="fas fa-envelope"></i>
                                            Save&Send
                                        </button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>


    <script>
        // Edit Invoice
        // function editInvoice(invoiceId) {
        //     // Redirect to edit page or open edit modal
        //     window.location.href = 'edit_invoice.php?id=' + invoiceId;
        // }

        // Confirm Delete Invoice
        function confirmDeleteInvoice(invoiceId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteInvoice(invoiceId);
                }
            });
        }

        // Delete Invoice
        function deleteInvoice(invoiceId) {
            fetch('delete_invoice.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + invoiceId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Deleted!',
                            'Invoice has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload(); // Refresh the page
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'Failed to delete invoice.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the invoice.',
                        'error'
                    );
                });
        }

        // Confirm Cancel Invoice
        function confirmCancelInvoice(invoiceId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will cancel the invoice and mark it as non-payable.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    cancelInvoice(invoiceId);
                }
            });
        }

        // Cancel Invoice - Updated version
        function cancelInvoice(invoiceId) {
            fetch('cancel_invoice.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + invoiceId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Cancelled!',
                            'Invoice has been cancelled.',
                            'success'
                        ).then(() => {
                            // Update the UI without full page reload
                            updateInvoiceStatus(invoiceId, 'cancelled');
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'Failed to cancel invoice.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'An error occurred while cancelling the invoice.',
                        'error'
                    );
                });
        }

        // Function to update invoice status visually
        function updateInvoiceStatus(invoiceId, newStatus) {
            const invoiceItem = document.querySelector(`.invoice-item[data-id="${invoiceId}"]`);
            if (!invoiceItem) {
                location.reload(); // Fallback if element not found
                return;
            }

            // Update status badge
            const statusBadge = invoiceItem.querySelector('.invoice-status .status-badge');
            if (statusBadge) {
                // Remove all status classes
                statusBadge.classList.remove('badge-draft', 'badge-sent', 'badge-paid', 'badge-overdue');

                // Add new status class
                statusBadge.classList.add('badge-' + newStatus);

                // Update text
                statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
            }

            // Update payment status badge if exists
            const paymentStatusBadges = invoiceItem.querySelectorAll('.invoice-status .status-badge');
            if (paymentStatusBadges.length > 1) {
                const paymentStatusBadge = paymentStatusBadges[1];
                paymentStatusBadge.classList.remove('badge-paid', 'badge-partial', 'badge-unpaid');
                paymentStatusBadge.classList.add('badge-cancelled');
                paymentStatusBadge.textContent = 'Cancelled';
            }

            // Remove payment button if exists
            const payButton = invoiceItem.querySelector('.pay-btn');
            if (payButton) {
                payButton.remove();
            }

            // Update dropdown menu options
            const dropdownMenu = invoiceItem.querySelector('.dropdown-menu');
            if (dropdownMenu) {
                // Remove Cancel option
                const cancelOption = dropdownMenu.querySelector('a[onclick*="confirmCancelInvoice"]');
                if (cancelOption) {
                    cancelOption.parentNode.remove();
                }

                // Add Restore option
                const divider = dropdownMenu.querySelector('.dropdown-divider');
                if (divider) {
                    const restoreOption = document.createElement('li');
                    restoreOption.innerHTML = `
                <a class="dropdown-item" href="#" onclick="restoreInvoice(${invoiceId})">
                    <i class="fas fa-undo me-2"></i>Restore Invoice
                </a>
            `;
                    dropdownMenu.insertBefore(restoreOption, divider.nextSibling);
                }
            }
        }

        // Restore Invoice - Updated version
        function restoreInvoice(invoiceId) {
            fetch('restore_invoice.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + invoiceId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Restored!',
                            'Invoice has been restored.',
                            'success'
                        ).then(() => {
                            // Update the UI without full page reload
                            updateInvoiceStatus(invoiceId, data.invoice.status || 'sent');
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'Failed to restore invoice.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'An error occurred while restoring the invoice.',
                        'error'
                    );
                });
        }

        // Delete for Sent Invoice
        function deleteSentInvoice(invoiceId) {
            Swal.fire({
                title: 'Delete Sent Invoice?',
                text: "This invoice has been sent to the tenant. Are you sure you want to delete it?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it anyway'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteInvoice(invoiceId);
                }
            });
        }

        // View Invoice Details
        function viewInvoice(invoiceId) {
            window.location.href = 'invoice_details.php?id=' + invoiceId;
        }
    </script>

    <!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const saveDraftBtn = document.getElementById('saveDraftBtn');

    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', function(e) {
            e.preventDefault();
            saveAsDraft();
        });
    }

    function saveAsDraft() {
        // Get form element
        const form = document.querySelector('form[name="invoice-form"]') ||
                    document.querySelector('form[action="submit_invoice.php"]') ||
                    document.querySelector('form');

        if (!form) {
            console.error('Form not found');
            alert('Error: Form not found');
            return;
        }

        // Create FormData object
        const formData = new FormData(form);

        // Add draft-specific data
        formData.append('status', 'draft');
        formData.append('payment_status', 'unpaid');

        // Handle dynamic rows (if any)
        const rows = document.querySelectorAll('.items-table tbody tr');
        rows.forEach((row, index) => {
            const accountItem = row.querySelector('select[name="account_item[]"]');
            const description = row.querySelector('textarea[name="description[]"]');
            const quantity = row.querySelector('input[name="quantity[]"]');
            const unitPrice = row.querySelector('input[name="unit_price[]"]');
            const taxes = row.querySelector('select[name="taxes[]"]');

            if (accountItem && description && quantity && unitPrice && taxes) {
                formData.append(`account_item[${index}]`, accountItem.value);
                formData.append(`description[${index}]`, description.value);
                formData.append(`quantity[${index}]`, quantity.value);
                formData.append(`unit_price[${index}]`, unitPrice.value);
                formData.append(`taxes[${index}]`, taxes.value);
            }
        });

        // Send data via AJAX
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Draft saved successfully!');
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                throw new Error(data.error || 'Unknown error saving draft');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving draft: ' + error.message);
        });
    }
});
</script> -->

    <script>
        function openPayModal(button) {
            // Get all invoice data from button attributes
            const invoiceId = button.getAttribute('data-invoice-id');
            const tenant = button.getAttribute('data-tenant');
            const totalAmount = parseFloat(button.getAttribute('data-total'));
            const paidAmount = parseFloat(button.getAttribute('data-paid'));
            const balance = parseFloat(button.getAttribute('data-balance'));

            // Set today's date as default
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const todayStr = `${yyyy}-${mm}-${dd}`;

            // Set modal values
            document.getElementById('invoiceId').value = invoiceId;
            document.getElementById('tenantName').value = tenant;
            document.getElementById('invoiceTotal').value = totalAmount.toFixed(2);
            document.getElementById('amount').value = balance.toFixed(2);
            document.getElementById('paymentDate').value = todayStr;
            document.getElementById('paymentDate').setAttribute('max', todayStr);

            // Initialize payment status
            updatePaymentStatus();

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }

        function updatePaymentStatus() {
            const amountInput = document.getElementById('amount');
            const paymentStatus = document.getElementById('paymentStatus');
            const invoiceTotal = parseFloat(document.getElementById('invoiceTotal').value);
            const paymentAmount = parseFloat(amountInput.value) || 0;

            if (isNaN(paymentAmount)) {
                paymentStatus.innerHTML = '<span class="text-secondary">Enter valid payment amount</span>';
                return;
            }

            if (paymentAmount <= 0) {
                paymentStatus.innerHTML = '<span class="text-danger">⚠️ Amount must be greater than 0</span>';
            } else if (paymentAmount > invoiceTotal) {
                const overpayment = (paymentAmount - invoiceTotal).toFixed(2);
                paymentStatus.innerHTML = `<span class="text-danger">⚠️ Overpayment (KES ${overpayment} over)</span>`;
            } else if (paymentAmount === invoiceTotal) {
                paymentStatus.innerHTML = '<span class="text-success">✓ Full payment will be received</span>';
            } else {
                const remaining = (invoiceTotal - paymentAmount).toFixed(2);
                paymentStatus.innerHTML = `<span class="text-warning">⏳ Partial payment (KES ${remaining} remaining)</span>`;
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            // Setup amount input listener
            document.getElementById('amount').addEventListener('input', updatePaymentStatus);

            // Setup form submission
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);
                const invoiceId = formData.get('invoice_id');
                const paymentAmount = parseFloat(formData.get('amount'));
                const invoiceTotal = parseFloat(document.getElementById('invoiceTotal').value);

                // Validate payment amount
                if (paymentAmount <= 0) {
                    alert('❌ Payment amount must be greater than 0');
                    return;
                }

                if (paymentAmount > invoiceTotal) {
                    if (!confirm(`This payment will result in an overpayment of KES ${(paymentAmount - invoiceTotal).toFixed(2)}. Continue?`)) {
                        return;
                    }
                }

                // Submit payment
                fetch(form.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                            alertDiv.style.zIndex = '9999';
                            alertDiv.innerHTML = `
          <strong>✅ Success!</strong> ${data.message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
                            document.body.appendChild(alertDiv);

                            // Auto-remove after 5 seconds
                            setTimeout(() => {
                                alertDiv.remove();
                            }, 5000);

                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                            modal.hide();

                            // Refresh the page to update all data
                            window.location.reload();
                        } else {
                            alert('❌ ' + (data.message || 'Failed to submit payment'));
                        }
                    })
                    .catch(error => {
                        console.error('Payment error:', error);
                        alert('❌ Network or server error occurred');
                    });
            });
        });
    </script>

    <script>
        function checkPaymentStatus() {
            const amountInput = document.getElementById('amount');
            const invoiceTotal = parseFloat(document.getElementById('invoiceTotal').value);
            const paymentStatus = document.getElementById('paymentStatus');

            // Remove non-numeric characters and parse the input value
            const paidAmount = parseFloat(amountInput.value.replace(/[^0-9.]/g, '')) || 0;

            if (paidAmount <= 0) {
                paymentStatus.textContent = '';
                paymentStatus.className = 'mt-2 small fw-semibold';
                return;
            }

            if (paidAmount >= invoiceTotal) {
                paymentStatus.textContent = '✅ Full payment - invoice will be marked as paid';
                paymentStatus.className = 'mt-2 small fw-semibold text-success';
            } else if (paidAmount > 0 && paidAmount < invoiceTotal) {
                paymentStatus.textContent = '⚠️ Partial payment - invoice will be marked as partially paid';
                paymentStatus.className = 'mt-2 small fw-semibold text-warning';
            }
        }

        // When opening the modal, set the invoice total
        document.getElementById('paymentModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const invoiceTotal = button.getAttribute('data-invoice-total');
            document.getElementById('invoiceTotal').value = invoiceTotal;
        });
    </script>


    <!-- Main Js File -->
    <script src="invoice.js"></script>
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

</body>
<!--end::Body-->

</html>