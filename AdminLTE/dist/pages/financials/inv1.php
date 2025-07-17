<?php
include '../db/connect.php';

$stmt = $pdo->prepare("SELECT account_code, account_name FROM chart_of_accounts ORDER BY account_name ASC");
$stmt->execute();
$accountItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
require_once '../db/connect.php';

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


<?php
require '../db/connect.php';

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
?>

<?php
// Get every building you need in the dropdown.
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

    <!--Tailwind CSS  -->
    <style>
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

        /* Header Styles */
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

        /* Sidebar Navigation */
        .app-container {
            display: flex;
            min-height: calc(100vh - 66px);
        }



        /* Main Content */
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

        /* Invoice List */
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

        .invoice-list {
            padding: 0;
        }

        .invoice-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s ease;
        }

        .invoice-item:hover {
            background-color: #f8fafc;
        }

        .invoice-checkbox {
            margin-right: 15px;
        }

        .invoice-number {
            width: 120px;
            font-weight: 600;
            color: #2a5bd7;
        }

        .invoice-customer {
            flex: 1;
            color: #4a5568;
        }

        .invoice-date {
            width: 100px;
            color: #718096;
            font-size: 14px;
        }

        .invoice-amount {
            width: 100px;
            font-weight: 600;
            text-align: right;
        }

        .invoice-status {
            width: 100px;
            text-align: center;
        }

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

        .invoice-actions {
            width: 80px;
            display: flex;
            justify-content: flex-end;
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

        /* Create Invoice Page */
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

        .item-qty, .item-rate, .item-amount {
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

        /* Summary Section */
        .summary-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .summary-label {
            width: 150px;
            text-align: right;
            padding-right: 20px;
            color: #718096;
        }

        .summary-value {
            width: 150px;
            text-align: right;
            font-weight: 500;
        }

        .total-row {
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 16px;
        }

        .total-value {
            color: #2a5bd7;
            font-weight: 600;
        }

        .invoice-header .over-flow {
    font-weight: bold;
}
.invoice-header {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .app-container {
                flex-direction: column;
            }


            .invoice-item {
                flex-wrap: wrap;
                gap: 10px;
            }

            .invoice-number, .invoice-customer, .invoice-date, .invoice-amount, .invoice-status, .invoice-actions {
                width: auto;
                flex: 1 1 100px;
            }

            .form-row {
                flex-direction: column;
                gap: 10px;
            }

            .items-table th, .items-table td {
                padding: 8px 5px;
            }
        }



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
.filter-dropdown {
    position: relative;
    display: inline-block;
}

.filter-btn {
    padding: 8px 12px;
    cursor: pointer;
    border: 1px solid #ccc;
    background-color: #fff;
    display: flex;
    align-items: center;
    gap: 6px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    display: none;
    background-color: white;
    border: 1px solid #ddd;
    z-index: 100;
    min-width: 120px;
}

.dropdown-menu ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.dropdown-menu ul li {
    padding: 10px;
    cursor: pointer;
}

.dropdown-menu ul li:hover {
    background-color: #f0f0f0;
}

.filter-dropdown.open .dropdown-menu {
    display: block;
}

.searchable-item-container {
  position: relative;
  width: 100%;
}

.searchable-item-input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-sizing: border-box;
}

.searchable-item-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  min-width: 250px; /* Ensure enough width for content */
  max-height: 300px; /* Increased to show more items */
  overflow-y: auto;
  background: white;
  border: 1px solid #ddd;
  border-top: none;
  border-radius: 0 0 4px 4px;
  z-index: 1000;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.searchable-item-group {
  padding: 5px 0;
}

.searchable-item-option {
  padding: 8px 15px;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  white-space: nowrap; /* Prevent text wrapping */
  overflow: hidden;
  text-overflow: ellipsis;
}

.searchable-item-option:hover {
  background-color: #f5f5f5;
}

.account-code {
  font-weight: bold;
  margin-right: 10px;
  color: #555;
  min-width: 60px; /* Ensure code column has consistent width */
}

.account-name {
  flex-grow: 1;
  text-overflow: ellipsis;
  overflow: hidden;
}
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
    border: 1px solid #FFC107; /* Border color for all payment statuses */
}

.badge-payment-paid {
    background-color: #e6f7ee;
    color: #00192D; /* Dark blue text */
}

.badge-payment-partial {
    background-color: #fff8e6;
    color: #00192D; /* Dark blue text */
    border-color: #FFC107; /* Yellow border */
}

.badge-payment-unpaid {
    background-color: #ffebee;
    color: #00192D; /* Dark blue text */
    border-color: #FFC107; /* Yellow border */
}

/* Status Badges (existing) */
.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    min-width: 60px;
    text-align: center;
}

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
.invoice-menu button {
    display: block;
    width: 100%;
    padding: 6px 12px;
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
}
.invoice-menu button:hover {
    background-color: #f1f1f1;
}
.hidden {
    display: none;
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
            <div class="over-flow" title="Invoice #">Invoice<!----></div>
        </div>
        <div class="invoice-customer">
            <div class="over-flow" title="Customer">Tenant<!----></div>
        </div>
        <div class="invoice-date">
            <div class="over-flow" title="Date">Date<!----></div>
        </div>
        <div class="invoice-date">
            <div class="over-flow" title="Date">Due Date<!----></div>
        </div>
        <div class="invoice-amount">
            <div class="over-flow" title="Amount">Amount<!----></div>
        </div>
        <div class="invoice-status">
            <div class="over-flow" title="Status">Status<!----></div>
        </div>
        <div class="invoice-status">
            <div class="over-flow" title="Status">Payment Status<!----></div>
        </div>
        <div class="invoice-actions">
            <div class="over-flow" title="Actions">Actions<!----></div>
        </div>
    </div>

    <?php
// ----------------------------------------------------
// 1) Fetch every invoice with its tenant's full name
// ----------------------------------------------------
$stmt = $pdo->query("
    SELECT
        i.id,
        i.invoice_number,
        i.invoice_date,
        i.due_date,
        i.total,
        i.status,
        i.payment_status,
        CONCAT(u.first_name,' ',u.middle_name) AS tenant_name
    FROM invoice i
    LEFT JOIN users u ON u.id = i.tenant
    ORDER BY i.invoice_number DESC
");

$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ----------------------------------------------------
// 2) Output each invoice item
// ----------------------------------------------------
foreach ($invoices as $invoice) {

    // Friendly formats
    $tenantName     = $invoice['tenant_name'] ?: 'Unknown';
    $invoiceDate    = date('M d, Y', strtotime($invoice['invoice_date']));
    $invoiceDueDate = date('M d, Y', strtotime($invoice['due_date']));
    $amount         = number_format($invoice['total'], 2);
    $menuId         = 'menu-' . $invoice['id']; // Unique menu ID

    // Map DB status → badge + display text
    switch ($invoice['status']) {
      case 'sent':
          $statusClass = 'badge-sent';
          $statusText  = 'Sent';
          $dataStatus  = 'pending';      // dropdown key
          break;

      case 'paid':
          $statusClass = 'badge-paid';
          $statusText  = 'Paid';
          $dataStatus  = 'paid';
          break;

      case 'overdue':
          $statusClass = 'badge-overdue';
          $statusText  = 'Overdue';
          $dataStatus  = 'overdue';
          break;

      case 'cancelled':
          $statusClass = 'badge-cancelled';
          $statusText  = 'Cancelled';
          $dataStatus  = 'cancelled';     // not in dropdown yet
          break;

      default:            // draft
          $statusClass = 'badge-draft';
          $statusText  = 'Draft';
          $dataStatus  = 'draft';
    }

    // Map payment_status → badge + display text
    switch ($invoice['payment_status']) {
      case 'paid':
          $paymentStatusClass = 'badge-paid';
          $paymentStatusText  = 'Paid';
          break;

      case 'partial':
          $paymentStatusClass = 'badge-partial';
          $paymentStatusText  = 'Partial';
          break;

      default:            // unpaid
          $paymentStatusClass = 'badge-unpaid';
          $paymentStatusText  = 'Unpaid';
    }

    // Generate a link to open the invoice details
    $href = 'invoice_details.php?id=' . $invoice['id'];

    // ---- HTML block -------------------------------------------------
    echo '<div class="invoice-item-wrapper" style="position: relative;">';
    echo '<a href="'. $href .'" class="invoice-link">';
    echo '
        <div class="invoice-item">
            <div class="invoice-checkbox">
                <input type="checkbox" onclick="event.stopPropagation()">
            </div>

            <div class="invoice-number">'   . htmlspecialchars($invoice['invoice_number']) . '</div>
            <div class="invoice-customer">' . htmlspecialchars($tenantName)               . '</div>
            <div class="invoice-date">'      . $invoiceDate                                . '</div>
            <div class="invoice-date">'      . $invoiceDueDate                             . '</div>
            <div class="invoice-amount">'    . $amount                                     . '</div>

            <div class="invoice-status">
                <span class="status-badge '. $statusClass .'">'. $statusText .'</span>
            </div>

            <div class="invoice-status">
                <span class="status-badge '. $paymentStatusClass .'">'. $paymentStatusText .'</span>
            </div>

            <div class="invoice-actions">
                <button class="action-btn menu-button" data-menu-id="'. $menuId .'" onclick="event.stopPropagation()">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
        </div>
    </a>';

    // Dropdown menu
    echo '
        <div class="invoice-menu hidden" id="'. $menuId .'" style="position:absolute; right:10px; top:35px; background:#fff; border:1px solid #ccc; z-index:999; padding:8px;">
            <button onclick="editInvoice('. $invoice['id'] .')">Edit</button>
            <button onclick="deleteInvoice('. $invoice['id'] .')">Delete</button>
           <button onclick="cancelInvoice('. $invoice['id'] .')">Cancel</button>



        </div>
    ';
    echo '</div>'; // Close wrapper
}
?>
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
                        <button  id="saveDraftBtn"  class="btn btn-outline" style="color: #FFC107; background-color:#00192D;">
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
    <input  type="text"
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
        <select name="account_item[]" class="select-account searchable-select" required>
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
             <input type="number" name="total[]" class="form-control total" placeholder="0" readonly  style="display:none;">
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
                                <label for="notes">Notes</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Thank you for your business!"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="terms">Terms & Conditions</label>
                                <textarea id="terms"  name="terms_conditions" class="form-control" rows="3" placeholder="Payment due within 15 days"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <!-- <div class="form-section">
                        <div class="summary-row">
                            <div class="summary-label">Subtotal:</div>
                            <div class="summary-value">$1,700.00</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Tax (10%):</div>
                            <div class="summary-value">$170.00</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Discount:</div>
                            <div class="summary-value">$0.00</div>
                        </div>
                        <div class="summary-row total-row">
                            <div class="summary-label">Total:</div>
                            <div class="summary-value total-value">$1,870.00</div>
                        </div>
                    </div> -->

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
<script>
function cancelInvoice(id) {
    if (!confirm("Are you sure you want to cancel this invoice?")) return;

    fetch('cancel_invoice.php?id=' + id)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Invoice cancelled successfully.");
                // Optionally: refresh or update the status badge in DOM
                location.reload();
            } else {
                alert("Failed to cancel invoice: " + (data.error || "Unknown error"));
            }
        })
        .catch(err => {
            alert("Error occurred while cancelling: " + err);
        });

    closeMenus();
}
</script>

<script>
function deleteInvoice(id) {
    if (!confirm("Are you sure you want to delete this invoice? This action cannot be undone.")) return;

    fetch('delete_invoice.php?id=' + id)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Invoice deleted successfully.");
                location.reload(); // Or remove from DOM dynamically
            } else {
                alert("Failed to delete invoice: " + (data.error || "Unknown error"));
            }
        })
        .catch(err => {
            alert("Error occurred while deleting: " + err);
        });

    closeMenus();
}
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    // Toggle the specific menu
    document.querySelectorAll('.menu-button').forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            const menuId = this.getAttribute('data-menu-id');
            const menu = document.getElementById(menuId);

            // Close any other open menus
            document.querySelectorAll('.invoice-menu').forEach(m => {
                if (m !== menu) {
                    m.classList.add('hidden');
                    m.style.display = 'none';
                }
            });

            // Toggle current menu
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden', !isHidden);
            menu.style.display = isHidden ? 'block' : 'none';
        });
    });

    // Hide menus when clicking outside
    document.addEventListener('click', function () {
        closeMenus();
    });
});

function closeMenus() {
    document.querySelectorAll('.invoice-menu').forEach(menu => {
        menu.classList.add('hidden');
        menu.style.display = 'none';
    });
}

function editInvoice(id) {
    window.location.href = 'edit_invoice.php?id=' + id;
}

function deleteInvoice(id) {
    if (confirm("Are you sure you want to delete invoice #" + id + "?")) {
        window.location.href = 'delete_invoice.php?id=' + id;
    }
}
</script>


<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const saveDraftBtn = document.getElementById('saveDraftBtn');

    saveDraftBtn.addEventListener('click', function () {
        // Example: Collect draft form data
        const invoiceNumber = document.querySelector('input[name="invoice_number"]').value;
        const tenant = document.querySelector('input[name="tenant"]').value;

        // Optional: Add more fields as needed

        // Example AJAX call to save draft (using fetch)
        fetch('save_draft.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                invoice_number: invoiceNumber,
                tenant: tenant,
                // Add other data fields here
            }),
        })
        .then(response => response.text())
        .then(data => {
            alert("Draft saved successfully!");
            console.log(data);
        })
        .catch(error => {
            console.error('Error saving draft:', error);
            alert("Failed to save draft.");
        });
    });
});
</script> -->

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
function showInvoiceActions(invoiceId) {
    // Implement your action menu logic here
    console.log('Actions for invoice', invoiceId);
    // Example: show a dropdown menu with view, edit, delete options
}
</script>

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
$(document).ready(function() {
    $('.searchable-select').select2({
        placeholder: "Search account items...",
        width: '100%',
        minimumResultsForSearch: 1 // Always show search box
    });
});
</script>


<script>
function addRow() {
  const table = document.querySelector(".items-table tbody");
  const newRow = document.createElement("tr");

  newRow.innerHTML = `
   <td>
      <select name="account_item[]" class="select-account searchable-select" required>
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
        <option value="exempt">Exempted</option>
      </select>
    </td>

    <td>
      <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
        <i class="fa fa-trash" style="font-size: 12px;"></i>
      </button>
    </td>
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
    document.addEventListener("DOMContentLoaded", function () {
        const dropdown = document.querySelector(".filter-dropdown");
        const btn = dropdown.querySelector(".filter-btn");
        const menu = dropdown.querySelector(".dropdown-menu");
        const statusText = btn.querySelector("span");

        // Toggle dropdown visibility
        btn.addEventListener("click", function (e) {
            e.stopPropagation(); // Prevent body click from closing immediately
            dropdown.classList.toggle("open");
        });

        // Close dropdown when clicking outside
        document.body.addEventListener("click", function () {
            dropdown.classList.remove("open");
        });

        // Handle selection
        menu.querySelectorAll("li").forEach((item) => {
            item.addEventListener("click", function () {
                const selectedStatus = this.getAttribute("data-status");
                statusText.textContent = `Status: ${this.textContent}`;

                // OPTIONAL: Filter invoices based on status
                filterInvoicesByStatus(selectedStatus);

                dropdown.classList.remove("open");
            });
        });

        // Optional: Sample invoice filter logic
        function filterInvoicesByStatus(status) {
            const allInvoices = document.querySelectorAll(".invoice-item");
            allInvoices.forEach(item => {
                const itemStatus = item.getAttribute("data-status");
                if (status === "all" || itemStatus === status) {
                    item.style.display = "";
                } else {
                    item.style.display = "none";
                }
            });
        }
    });
</script>



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
                           <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#invoiceModal"   style="background-color: #00192D; color:#FFC107;">
                             <i class="fas fa-eye"></i>
                            View Invoice
                          </button>
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
  document.addEventListener('DOMContentLoaded', function() {
  const selectContainers = document.querySelectorAll('.custom-select-container');

  selectContainers.forEach(container => {
    const searchInput = container.querySelector('.custom-select-search');
    const originalSelect = container.querySelector('.custom-select');
    const optionsContainer = container.querySelector('.custom-select-options');

    // Create options
    const options = Array.from(originalSelect.options).map(option => {
      return {
        value: option.value,
        text: option.textContent,
        selected: option.selected,
        disabled: option.disabled
      };
    });

    // Render options
    function renderOptions(filter = '') {
      optionsContainer.innerHTML = '';
      const filteredOptions = options.filter(option =>
        option.text.toLowerCase().includes(filter.toLowerCase())
      );

      filteredOptions.forEach(option => {
        const optionElement = document.createElement('div');
        optionElement.className = 'custom-select-option';
        optionElement.textContent = option.text;
        optionElement.dataset.value = option.value;

        if (option.selected) {
          optionElement.classList.add('selected');
          searchInput.placeholder = option.text;
        }

        if (option.disabled) {
          optionElement.classList.add('disabled');
        }

        optionElement.addEventListener('click', () => {
          if (!option.disabled) {
            originalSelect.value = option.value;
            searchInput.placeholder = option.text;
            searchInput.value = '';
            optionsContainer.querySelectorAll('.custom-select-option').forEach(opt => {
              opt.classList.remove('selected');
            });
            optionElement.classList.add('selected');
            optionsContainer.style.display = 'none';
          }
        });

        optionsContainer.appendChild(optionElement);
      });
    }

    // Initial render
    renderOptions();

    // Toggle options on search input focus
    searchInput.addEventListener('focus', () => {
      optionsContainer.style.display = 'block';
      renderOptions();
    });

    // Filter options on search (keep dropdown open)
    searchInput.addEventListener('input', (e) => {
      renderOptions(e.target.value);
      optionsContainer.style.display = 'block'; // Ensure it stays open while typing
    });

    // Close only if clicking outside the entire custom select container
    document.addEventListener('click', (e) => {
      if (!container.contains(e.target)) {
        optionsContainer.style.display = 'none';
      }
    });
  });
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

<script>
/* ---------- helpers ---------- */
function toNum(v)   { return isNaN(parseFloat(v)) ? 0 : parseFloat(v); }
function fmt(n)     { return n.toFixed(2); }

/* ---------- per‑row total ---------- */
function calcRow(row) {
  const qty   = toNum(row.querySelector('.quantity')  .value);
  const price = toNum(row.querySelector('.unit-price').value);
  const vatOp =       row.querySelector('.vat-option').value;
  let   base  = qty * price;        // raw line amount
  let   vat   = 0;

  if (vatOp === 'exclusive') { vat = base * 0.16; }
  // ‘inclusive’ already contains VAT, ‘zero’ & ‘exempted’ add none

  const lineTotal = base + vat;
  row.querySelector('.total').value = fmt(lineTotal);

  // store numbers on the row for the grand‑totals pass
  row.dataset.base = base;
  row.dataset.vat  = vat;
}

/* ---------- grand totals ---------- */
function calcInvoice() {
  let subtotal = 0, vatTotal = 0;

  document.querySelectorAll('.items-table tbody tr').forEach(tr=>{
    subtotal += +tr.dataset.base || 0;
    vatTotal += +tr.dataset.vat  || 0;
  });

  document.getElementById('subtotal-cell').innerText = fmt(subtotal);
  document.getElementById('vat-cell')     .innerText = fmt(vatTotal);
  document.getElementById('grand-cell')   .innerText = fmt(subtotal + vatTotal);
}

/* ---------- live listeners ---------- */
document.addEventListener('input', e=>{
  if (e.target.closest('.items-table') &&
      (e.target.classList.contains('quantity')   ||
       e.target.classList.contains('unit-price') ||
       e.target.classList.contains('vat-option'))) {

    const row = e.target.closest('tr');
    calcRow(row);
    calcInvoice();
  }
});

/* ---------- delete row ---------- */
function deleteRow(btn) {
  const tr = btn.closest('tr');
  tr.parentElement.removeChild(tr);
  calcInvoice();
}
</script>


<script>
// You can use this function to populate the modal with dynamic data
function showInvoice(invoiceData) {
    // Populate tenant info
    document.getElementById('tenantName').textContent = invoiceData.tenant_name || 'N/A';
    document.getElementById('tenantPhone').textContent = invoiceData.tenant_phone || 'Phone not available';
    document.getElementById('tenantEmail').textContent = invoiceData.tenant_email || 'Email not available';

    // Populate invoice info
    document.getElementById('invoiceNumber').textContent = invoiceData.invoice_number || 'N/A';
    document.getElementById('invoiceDate').textContent = invoiceData.invoice_date || 'N/A';
    document.getElementById('dueDate').textContent = invoiceData.due_date || 'N/A';

    // Populate items
    const itemsContainer = document.getElementById('invoiceItems');
    itemsContainer.innerHTML = '';

    if (invoiceData.items && invoiceData.items.length > 0) {
        invoiceData.items.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.description || ''}</td>
                <td>${item.quantity || ''}</td>
                <td>${item.unit_price || ''}</td>
                <td>${item.taxes || ''}</td>
                <td>${item.amount || ''}</td>
            `;
            itemsContainer.appendChild(row);
        });
    } else {
        itemsContainer.innerHTML = '<tr><td colspan="5">No items found for this invoice.</td></tr>';
    }

    // Show the modal
    var invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
    invoiceModal.show();
}

// PDF Download functionality
document.getElementById('downloadPdf').addEventListener('click', function() {
    const element = document.getElementById('invoiceContent');
    const opt = {
        margin: 10,
        filename: 'invoice_' + (document.getElementById('invoiceNumber').textContent || '') + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    // Generate PDF
    html2pdf().set(opt).from(element).save();
});

// Example usage:
/*
const sampleInvoiceData = {
    tenant_name: "John Doe",
    tenant_phone: "0712345678",
    tenant_email: "john.doe@example.com",
    invoice_number: "INV-001",
    invoice_date: "15/06/2023",
    due_date: "30/06/2023",
    items: [
        {
            description: "Rent for June 2023",
            quantity: 1,
            unit_price: "Ksh 10,000",
            taxes: "Ksh 1,500",
            amount: "Ksh 11,500"
        },
        {
            description: "Water Bill",
            quantity: 1,
            unit_price: "Ksh 500",
            taxes: "Ksh 0",
            amount: "Ksh 500"
        }
    ]
};
showInvoice(sampleInvoiceData);
*/
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
    // Initialize the app
    function initApp() {
        renderInvoiceList();
        populateCustomerSelect();
        setupEventListeners();
        updateInvoiceNumber(); // Default to INV
    }

    // Generate invoice number based on draft status
    function updateInvoiceNumber(isDraft = false) {
        const prefix = isDraft ? 'DFT' : 'INV';
        const nextNumber = getNextInvoiceNumber(prefix);
        invoiceNumberInput.value = `${prefix}${String(nextNumber).padStart(3, '0')}`;
    }

    // Get last invoice number and calculate next
    function getNextInvoiceNumber(prefix) {
        const filtered = database.invoices.filter(inv => inv.number.startsWith(prefix));
        if (filtered.length === 0) return 1;

        const last = filtered.reduce((max, inv) => {
            const num = parseInt(inv.number.replace(prefix, ''), 10);
            return num > max ? num : max;
        }, 0);

        return last + 1;
    }

    // Save invoice (draft or finalized)
    function saveInvoice(isDraft = false) {
        // Update invoice number based on status
        updateInvoiceNumber(isDraft);

        const customerId = parseInt(customerSelect.value);
        const customer = database.customers.find(c => c.id === customerId);

        const items = [];
        document.querySelectorAll('.item-row').forEach(row => {
            items.push({
                name: row.querySelector('.item-name input').value,
                description: row.querySelector('.item-desc input').value,
                qty: parseFloat(row.querySelector('.item-qty input').value) || 0,
                rate: parseFloat(row.querySelector('.item-rate input').value) || 0
            });
        });

        const total = calculateTotals();

        const newInvoice = {
            id: database.nextInvoiceId++,
            number: invoiceNumberInput.value,
            customer: customer ? customer.name : 'Unknown Customer',
            date: invoiceDateInput.value,
            dueDate: dueDateInput.value,
            amount: total,
            status: isDraft ? 'draft' : 'pending',
            items: items,
            notes: notesInput.value,
            terms: termsInput.value
        };

        database.invoices.unshift(newInvoice);
        return newInvoice;
    }

    // Set up all event listeners
    function setupEventListeners() {
        // Save as Draft
        saveDraftBtn.addEventListener('click', () => {
            const invoice = saveInvoice(true);
            alert(`Draft saved: ${invoice.number}`);
            createInvoiceView.style.display = 'none';
            invoiceListView.style.display = 'block';
            renderInvoiceList();
            updateInvoiceNumber(); // Reset to normal after draft
        });

        // Finalize Invoice
        saveFinalizeBtn.addEventListener('click', () => {
            const invoice = saveInvoice(false);
            alert(`Invoice finalized: ${invoice.number}`);
            createInvoiceView.style.display = 'none';
            invoiceListView.style.display = 'block';
            renderInvoiceList();
        });

        // Other event listeners (addItem, filters, etc.) should remain here...
    }

    // Render invoices to the UI
    function renderInvoiceList(filterStatus = 'all', filterDate = 'this-month') {
        invoiceList.innerHTML = '';

        let filteredInvoices = [...database.invoices];

        if (filterStatus !== 'all') {
            filteredInvoices = filteredInvoices.filter(inv => inv.status === filterStatus);
        }

        filteredInvoices.forEach(invoice => {
            const invoiceItem = document.createElement('div');
            invoiceItem.className = 'invoice-item';
            invoiceItem.innerHTML = `
                <div class="invoice-checkbox">
                    <input type="checkbox">
                </div>
                <div class="invoice-number">${invoice.number}</div>
                <div class="invoice-customer">${invoice.customer}</div>
                <div class="invoice-date">${formatDate(invoice.date)}</div>
                <div class="invoice-amount">$${invoice.amount.toFixed(2)}</div>
                <div class="invoice-status">
                    <span class="status-badge status-${invoice.status}">${capitalizeFirstLetter(invoice.status)}</span>
                </div>
                <div class="invoice-actions">
                    <button class="action-btn">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            `;
            invoiceList.appendChild(invoiceItem);
        });
    }

    // Helper functions
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Start everything
    document.addEventListener('DOMContentLoaded', initApp);
</script>

<!-- INVOICE FUTURE DATE -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const invoiceDateInput = document.getElementById('invoice-date');

    // Set max date to today (YYYY-MM-DD format)
    const today = new Date().toISOString().split('T')[0];
    invoiceDateInput.setAttribute('max', today);

    // Optional: Prevent manual input of future dates
    invoiceDateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();

        if (selectedDate > today) {
            alert("Invoice date cannot be in the future!");
            this.value = today.toISOString().split('T')[0]; // Reset to today
        }
    });
});
</script>

<script>
// DOM Elements
const invoiceListView = document.getElementById('invoice-list-view');
const createInvoiceView = document.getElementById('create-invoice-view');
const createInvoiceBtn = document.getElementById('create-invoice-btn');
const cancelInvoiceBtn = document.getElementById('cancel-invoice-btn');

// Show form
function showCreateInvoiceView() {
    invoiceListView.style.display = 'none';
    createInvoiceView.style.display = 'block';
}

// Back to list
function showInvoiceListView() {
    createInvoiceView.style.display = 'none';
    invoiceListView.style.display = 'block';
}

// Click handlers
createInvoiceBtn?.addEventListener('click', showCreateInvoiceView);
cancelInvoiceBtn?.addEventListener('click', showInvoiceListView);

// Edit logic
function editInvoice(invoiceId) {
    // Show the form view
    showCreateInvoiceView();

    // Optional: populate form fields via AJAX
    fetch('get_invoice_data.php?id=' + invoiceId)
        .then(response => response.json())
        .then(data => {
            // Replace the below with actual form field population
            document.getElementById('invoice-id').value = data.id;
            document.getElementById('invoice-number').value = data.invoice_number;
            document.getElementById('invoice-date').value = data.invoice_date;
            document.getElementById('due-date').value = data.due_date;
            document.getElementById('total').value = data.total;
            document.getElementById('tenant').value = data.tenant;
            // ... populate other fields as needed
        })
        .catch(error => {
            alert("Failed to load invoice data.");
            console.error(error);
        });
}
</script>

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
      <select name="account_item[]" class="select-account searchable-select" required>
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
        <option value="exempt">Exempted</option>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Save Draft Button - uses server-generated number
    document.getElementById('saveDraftBtn').addEventListener('click', function () {
        const form = document.querySelector('form');
        const formData = new FormData(form);
        formData.append('draft', '1');

        fetch('save_draft.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(`Draft saved: ${data.invoice_number}`);
                document.getElementById('invoice-number').value = data.invoice_number;

                // Hide draft form, show invoice list
                createInvoiceView.style.display = 'none';
                invoiceListView.style.display = 'block';
                renderInvoiceList();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => {
            console.error('Error saving draft:', err);
            // alert('Unexpected error occurred.');
        });
    });

    // Preview Button - Updated to handle draft numbers
    document.getElementById('preview-invoice-btn').addEventListener('click', function () {
        const previewPanel = document.getElementById('invoicePreviewPanel');
        const previewContent = document.getElementById('previewContent');

        // Draft checkbox check
        const isDraft = document.getElementById('draftCheckbox')?.checked;

        // Get form values
        const invoiceNumber = document.getElementById('invoice-number').value;
        const building = document.getElementById('building').selectedOptions[0]?.text || 'N/A';
        const tenant = document.getElementById('customer').selectedOptions[0]?.text || 'N/A';
        const invoiceDate = document.getElementById('invoice-date').value;
        const dueDate = document.getElementById('due-date').value;
        const status = isDraft ? 'DRAFT' : 'PENDING';

        // Build items table
        let tableRows = '';
        let subtotal = 0;

        document.querySelectorAll('.items-table tbody tr').forEach(row => {
            const item = row.querySelector('select[name="account_item[]"]').selectedOptions[0]?.text || '';
            const desc = row.querySelector('textarea[name="description[]"]').value;
            const qty = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
            const price = parseFloat(row.querySelector('input[name="unit_price[]"]').value) || 0;
            const tax = row.querySelector('select[name="taxes[]"]').value;
            const rowTotal = qty * price;
            subtotal += rowTotal;

            tableRows += `
                <tr>
                    <td>${item}</td>
                    <td>${desc}</td>
                    <td>${qty}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${tax}</td>
                    <td>${rowTotal.toFixed(2)}</td>
                </tr>`;
        });

        // Calculate tax and total
        const taxRate = 0.1;
        const taxAmount = subtotal * taxRate;
        const total = subtotal + taxAmount;

        previewContent.innerHTML = `
            <div class="invoice-header">
                <h2>${status}</h2>
                <div class="invoice-meta">
                    <p><strong>Invoice #:</strong> ${invoiceNumber}</p>
                    <p><strong>Status:</strong> ${status}</p>
                    <p><strong>Date:</strong> ${invoiceDate}</p>
                    <p><strong>Due Date:</strong> ${dueDate}</p>
                </div>
            </div>

            <div class="invoice-parties">
                <div class="from">
                    <h3>From:</h3>
                    <p>Your Company Name</p>
                </div>
                <div class="to">
                    <h3>To:</h3>
                    <p><strong>Building:</strong> ${building}</p>
                    <p><strong>Tenant:</strong> ${tenant}</p>
                </div>
            </div>

            <table class="invoice-items">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Tax</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>${tableRows}</tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">Subtotal:</td>
                        <td>${subtotal.toFixed(2)}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">Tax (10%):</td>
                        <td>${taxAmount.toFixed(2)}</td>
                    </tr>
                    <tr class="total">
                        <td colspan="5" class="text-right"><strong>Total:</strong></td>
                        <td><strong>${total.toFixed(2)}</strong></td>
                    </tr>
                </tfoot>
            </table>

            ${isDraft ? '<div class="draft-watermark">DRAFT</div>' : ''}
        `;

        previewPanel.classList.add('active');
    });

    // Close Preview Panel
    document.getElementById('closePreview').addEventListener('click', function () {
        document.getElementById('invoicePreviewPanel').classList.remove('active');
    });

    // Auto-update draft number if checkbox changes
    const draftCheckbox = document.getElementById('draftCheckbox');
    if (draftCheckbox) {
        draftCheckbox.addEventListener('change', function () {
            const invoiceNumberField = document.getElementById('invoice-number');
            if (this.checked) {
                invoiceNumberField.value = ''; // clear so backend assigns next
            } else {
                invoiceNumberField.value = ''; // same here; backend will assign finalized version later
            }
        });
    }
});
</script>



<script>
document.addEventListener('DOMContentLoaded', () => {
    const buildingSelect = document.getElementById('building');
    const tenantSelect   = document.getElementById('customer');

    buildingSelect.addEventListener('change', () => {
        const buildingId = buildingSelect.value;

        // Reset tenant dropdown
        tenantSelect.innerHTML =
            '<option value="">Select a Tenant</option>';
        tenantSelect.disabled = !buildingId;

        if (!buildingId) return;

        // Fetch tenants for that building
        fetch(`get_tenants.php?building_id=${buildingId}`)
            .then(r => r.json())
            .then(tenants => {
                tenants.forEach(t => {
                    const opt   = document.createElement('option');
                    opt.value   = t.id;
                    opt.textContent = t.name;
                    tenantSelect.appendChild(opt);
                });
            })
            .catch(err => {
                console.error('Failed to load tenants', err);
                alert('Could not load tenants for this building.');
            });
    });
});
</script>


 <script>
/**
 * Turn any .searchable-select <select> into a Select2 box
 * Call this once at startup *and* after you add a new row dynamically.
 */
function initItemSelect($scope = $(document)) {
    $scope.find('.searchable-select').select2({
        width: '100%',                 // fills the <td>
        placeholder: $(this).data('placeholder'),
        allowClear: true               // little “×” to clear a choice
    });
}

$(function () {
    // ── 1️⃣  first initialise everything already on the page
    initItemSelect();

    // ── 2️⃣  if you have an “Add Row” button, re‑init the new row
    $('#addRowBtn').on('click', function () {
        const $newRow = $('#items-table tbody tr:first').clone(true);   // or however you create rows
        // clear any previous values
        $newRow.find('input, textarea').val('');
        $newRow.find('select').val(null).trigger('change');

        $('#items-table tbody').append($newRow);
        initItemSelect($newRow);        // ⭐ make its select searchable
    });
});
</script>


<script>
  $('.searchable-select').select2({
    width:'100%',
    placeholder:'Select or search an item…',
    minimumInputLength: 2,
    ajax: {
        url: 'account-items-search.php',
        dataType: 'json',
        delay: 250,              // throttle queries
        data: params => ({ q: params.term }),    // term typed by user
        processResults: data => ({
            results: data.map(item => ({
                id: item.account_code,           // value sent to server
                text: item.account_name          // visible label
            }))
        }),
        cache: true
    }
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const filterModal = document.getElementById('filterModal');
    const closeFilter = document.getElementById('closeFilter');
    const applyFilter = document.getElementById('applyFilter');
    const resetFilter = document.getElementById('resetFilter');

    // Show modal when filter button is clicked
    filterButton.addEventListener('click', function() {
        filterModal.style.display = 'block';
    });

    // Close modal when X is clicked
    closeFilter.addEventListener('click', function() {
        filterModal.style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target == filterModal) {
            filterModal.style.display = 'none';
        }
    });

    // Apply filters
    applyFilter.addEventListener('click', function() {
        const status = document.getElementById('statusFilter').value;
        const paymentStatus = document.getElementById('paymentFilter').value;
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;

        // Here you would typically make an AJAX call to your server with the filter parameters
        // For this example, we'll just log them
        console.log('Applying filters:', {
            status: status,
            paymentStatus: paymentStatus,
            dateFrom: dateFrom,
            dateTo: dateTo
        });

        // Close the modal
        filterModal.style.display = 'none';

        // In a real implementation, you would reload the table data with the filters applied
        // For example:
        // fetchFilteredInvoices(status, paymentStatus, dateFrom, dateTo);
    });

    // Reset filters
    resetFilter.addEventListener('click', function() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('paymentFilter').value = '';
        document.getElementById('dateFrom').value = '';
        document.getElementById('dateTo').value = '';

        // In a real implementation, you would reload the original unfiltered data
        // For example:
        // fetchAllInvoices();
    });
});

// Example function for fetching filtered invoices (would need server-side implementation)
function fetchFilteredInvoices(status, paymentStatus, dateFrom, dateTo) {
    // This would be an AJAX call to your server
    fetch('filter.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            status: status,
            payment_status: paymentStatus,
            date_from: dateFrom,
            date_to: dateTo
        })
    })
    .then(response => response.json())
    .then(data => {
        // Update your table with the filtered data
        updateInvoiceTable(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Example function to update the table with filtered data
function updateInvoiceTable(invoices) {
    // Implementation would depend on how your table is structured
    // This would clear and repopulate the table with the filtered invoices
}
</script>
</body>
<!--end::Body-->

</html>
