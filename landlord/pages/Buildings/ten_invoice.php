<?php
require_once '../db/connect.php';
include_once 'processes/encrypt_decrypt_function.php'; // ensure this file defines encryptor()

// Check if invoice parameter exists
if (isset($_GET['invoice'])) {
    $tenantId = encryptor('decrypt', $_GET['invoice']);

    // Fetch tenant data from DB
    $stmt = $pdo->prepare("SELECT * FROM tenants WHERE id = ?");
    $stmt->execute([$tenantId]);
    $tenant_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tenant_info) {
        die("Tenant not found.");
    }
} else {
    die("No tenant selected.");
}
?>

<?php
require_once '../db/connect.php';
include_once 'processes/encrypt_decrypt_function.php';

$tenant_info = [];

if (isset($_GET['invoice'])) {
    $tenantId = encryptor('decrypt', $_GET['invoice']);

    // Fetch tenant details from database
    $stmt = $pdo->prepare("
        SELECT 
            id,
            first_name,
            middle_name,
            last_name,
            main_contact,
            alt_contact,
            email,
            building
        FROM tenants
        WHERE id = ?
    ");
    $stmt->execute([$tenantId]);
    $tenant_info = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$tenant_info) {
    echo "<p class='text-danger'>Tenant details not found.</p>";
    exit;
}
?>

<?php
include '../db/connect.php';

// Fetch only revenue accounts
$stmt = $pdo->prepare("
  SELECT account_code, account_name
  FROM chart_of_accounts
  WHERE account_type = 'Revenue'
  ORDER BY account_name ASC
");
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

// Fetch buildings list
try {
    $stmt = $pdo->prepare("SELECT id, building_name FROM tenants ORDER BY building_name ASC");
    $stmt->execute();
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching buildings: " . $e->getMessage());
    $buildings = [];
}
// Fetch all active tenants using PDO (for JavaScript approach)
// try {
//   $stmt = $pdo->query("SELECT id, first_name, middle_name, last_name, building FROM tenants WHERE status = 'Active'");
//   $tenants = $stmt->fetchAll();
// } catch (PDOException $e) {
//   $tenants = [];
//   error_log("Error fetching tenants: " . $e->getMessage());
// }
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
        <link rel="stylesheet" href="../../assets/main.css" />
    <!-- <link rel="stylesheet" href="text.css" /> -->
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->

    <!-- jquery link-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Pdf pluggin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
  background-color:  #00192D;
  color:#FFC107;
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
  min-width: 100px;
  z-index: 70;
  /* display: none; */
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

/* Table responsive wrapper - must come first */
.table-responsive {
  display: block;
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

/* Button groups */
.btn-group-responsive {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}

/* Filter tags */
.filter-tag {
  display: inline-block;
  background-color: #FFC107;
  color: #00192D;
  border-radius: 15px;
  padding: 5px 10px;
  margin: 5px 5px 0 0;
  font-size: 0.9em;
}

.filter-tag .remove-btn {
  margin-left: 8px;
  cursor: pointer;
  color: #00192D;
  font-weight: bold;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
  .invoice-item > div {
    padding: 8px 4px;
  }
}

@media (max-width: 992px) {
  .invoice-container {
    overflow-x: auto;
  }

  .invoice-item {
    min-width: 900px; /* Forces horizontal scroll */
  }

  .form-row {
    flex-direction: column;
    gap: 15px;
  }

  .form-group {
    width: 100%;
    margin-bottom: 15px;
  }
}

@media (max-width: 768px) {
  .app-container {
    flex-direction: column;
  }

  .page-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .page-actions {
    margin-top: 15px;
    width: 100%;
    justify-content: flex-start;
  }

  .invoice-list-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .invoice-list-filters {
    margin-top: 15px;
    width: 100%;
    flex-direction: column;
  }

  .filter-dropdown {
    width: 100%;
    margin-bottom: 10px;
  }

  #invoicePreviewPanel {
    width: 100%;
  }

  /* Modals */
  .modal-dialog {
    margin: 10px;
    width: calc(100% - 20px) !important;
    max-width: none;
  }

  /* Stack form rows vertically */
  .form-row {
    flex-direction: column;
  }
}

@media (max-width: 576px) {
  .btn {
    padding: 6px 12px;
    font-size: 12px;
  }

  .section-title {
    font-size: 16px;
  }

  .items-table th,
  .items-table td {
    padding: 6px 4px;
    font-size: 12px;
  }

  .summary-table {
    width: 100% !important;
    float: none !important;
  }

  .page-actions .btn {
    margin-bottom: 5px;
    width: 100%;
  }

  /* Button groups */
  .btn-group-responsive .btn {
    flex: 1 0 calc(50% - 5px);
    min-width: calc(50% - 5px);
  }
}

@media (max-width: 480px) {
  .filter-panel .row > div {
    margin-bottom: 10px;
  }

  .form-control,
  .form-select {
    font-size: 12px;
    padding: 8px;
  }

  /* Mobile table styles */
  .invoice-item {
    flex-wrap: wrap;
  }

  .search-container {
    transition: all 0.2s ease;
    overflow: hidden;
}
.search-container:focus-within {
    border-color: #5E3A56;
    box-shadow: 0 0 0 2px rgba(94, 58, 86, 0.1);
}
#searchTermDisplay {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    max-height: 32px;
    overflow-y: auto;
}
#searchTermDisplay .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    display: inline-flex;
    align-items: center;
}
#searchTermDisplay .btn-close {
    font-size: 0.5rem;
    opacity: 0.8;
}
#searchTermDisplay .btn-close:hover {
    opacity: 1;
}
#clearSearch {
    cursor: pointer;
}
#clearSearch:hover {
    color: #5E3A56 !important;
}
#noResultsMessage {
    background: #f8f9fa;
    border-radius: 8px;
    margin: 15px 0;
}

  .table td,
  .table th {
    color: white;
    background-color: #00192D;
    border: 1px solid #FFC107;
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
    padding: 8px 5px;
  }
}
</style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-dark" style="">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; ?>
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
            <div> <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?> </div> <!-- This is where the sidebar is inserted -->
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
     <!-- Main content -->
     <section class="content">
                                <div class="container-fluid">
                                  
                                    <div class="card shadow">
                                        <div class="card-header" style="background-color:#00192D; color:#fff;"><b>Create Invoice</b></div>
                                        <!-- <form id="invoiceForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" enctype="multipart/form-data" autocomplete="off"> -->
                                        <form  id="myForm" method="POST" action="/Jengopay/landlord/pages/financials/invoices/action/submit_invoice.php" enctype="multipart/form-data">

                                        <div class="card-body">
                                                <div class="row">
                                                <div class="col-md-4">
                                                        <!-- Existing Invoice # input -->
                                                    <div class="form-group">
                                                        <label for="invoice-number">Invoice  Number</label>
                                                        <input type="text"
                                                            id="invoice-number"
                                                            value="<?= $invoiceNumber ?>"
                                                            class="form-control"
                                                            readonly>
                                                        <input type="hidden"
                                                            name="invoice_number"
                                                            value="<?= $invoiceNumber ?>">
                                                    </div>
                                                    </div>
                                                    <div class="col-md-4">
    <div class="form-group">
        <label>Building:</label>
        <input type="text" name="building" class="form-control"
               value="<?= htmlspecialchars($tenant_info['building']); ?>" readonly>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group">
        <label>Tenant Name</label>
        <input type="text" name="receiver" required class="form-control"
               value="<?= htmlspecialchars($tenant_info['first_name'] . ' ' . $tenant_info['middle_name'] . ' ' . $tenant_info['last_name']); ?>" readonly>
    </div>
</div>


                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Invoice Date:</label>
                                                            <input type="date" id="invoiceDate" name="invoice_date" required class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Date Due:</label>
                                                            <input type="date" id="dateDue" name="due_date" required class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                </div><hr>
                                                <!-- Hidden Payment Status -->
                                                <input type="hidden" name="paymentStatus" value="Pending">
                                                <!-- Invoice Table -->
                                               
                                <!-- Items Section -->
                                <div class="form-section">
        <h3 class="section-title">Items</h3>
        <table class="items-table" id="itemsTable">
            <thead>
                <tr>
                    <th>Item (Service)</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Taxes</th>
                    <th>Total</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="itemsBody">
                <!-- One initial row -->
                <!-- <tr> -->
                <!-- <td> -->
                <!-- <select name="account_item[]" class="select-account searchable-select" required>
            <option value="" disabled selected>Select Account Item</option>
            <?php foreach ($accountItems as $item): ?>
              <option value="<?= htmlspecialchars($item['account_code']) ?>">
                <?= htmlspecialchars($item['account_name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
            </td>
            <td style="min-width: 200px;">
          <textarea name="description[]" class="form-control" placeholder="Description" rows="1" required></textarea>
        </td>
                    <td><input type="number" name="quantity[]" class="form-control quantity" required></td>
                    <td><input type="number" name="unit_price[]" class="form-control unit-price" required></td>
                    <td>
                        <select name="vat_type[]" class="form-select vat-option" required>
                            <option value="" disabled selected>Select Option</option>
                            <option value="inclusive">VAT 16% Inclusive</option>
                            <option value="exclusive">VAT 16% Exclusive</option>
                            <option value="zero">Zero Rated</option>
                            <option value="exempted">Exempted</option>
                        </select>
                    </td>
                    <td><input type="text" name="total[]" class="form-control total" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm delete-btn"><i class="fa fa-trash"></i></button></td> -->
                </tr>
            </tbody>
        </table>

        <!-- Add More Button -->
        <button type="button" class="btn btn-success add-btn" id="addMoreBtn" style="background-color: #00192D; color: #FFC107;">
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
                                   <!-- File Upload Section -->
                              <div class="form-section">
                                  <h3 class="section-title">Attachments</h3>
                                  <div class="form-row">
                                      <div class="form-group">
                                          <!-- Hidden file input -->
                                          <input type="file" id="fileInput" name="attachment[]" multiple style="display: none;">

                                          <!-- Button to trigger file input -->
                                          <button type="button"  class="btn btn-outline-secondary" onclick="document.getElementById('fileInput').click()"  style="background-color: #00192D; color: #FFC107;">
                                              <i class="fas fa-paperclip"></i> Attach Files
                                          </button>

                                          <!-- Display selected files -->
                                          <div id="fileList" class="mt-2"></div>
                                      </div>
                                  </div>
</div>
                                    </div>
                                </div>

                                <div class="action-right" style="display: flex; justify-content: flex-end;">
    <button type="submit" style="background-color: #00192D; color: #FFC107; padding: 8px 16px; border: none; border-radius: 4px;">
    <i class="fas fa-share-square"></i> Save & Send
    </button>
</div>

                                    <!-- Add this hidden file input if you want actual file attachment -->
<!-- <input type="file" id="fileInput" style="display: none;"> -->
                                    <!-- <div class="action-right"> -->
                                        <!-- <button type="submit" style="background-color: #00192D; color: #FFC107; padding: 8px 16px; border: none; border-radius: 4px;">
                                            <i class="fas fa-envelope"></i>
                                            Save&Send
                                        </button> -->
                                    <!-- </div> -->


                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>

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
                        console.error("❌ Server returned error:", data); // <-- shows PHP error in console
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
    <!-- <script src="invoice.js"></script> -->
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
<script src="../../../../landlord/js/adminlte.js"></script>    <!-- links for dataTaable buttons -->
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

   <!-- <script>
// // Store the original invoices data
// let originalInvoices = [];
// let displayedInvoices = [];
// let activeFilters = [];

// Handle window resize for responsive adjustments
function handleResize() {
  const windowWidth = window.innerWidth;

  // Adjust layout based on screen size
  const formRows = document.querySelectorAll('.form-row');

  if (windowWidth < 768) {
    // Mobile-specific adjustments
    formRows.forEach(row => {
      row.style.flexDirection = 'column';
    });
  } else {
    // Desktop layout
    formRows.forEach(row => {
      row.style.flexDirection = 'row';
    });
  }
}

// Enhanced filter functionality
// function applyFilter() {
//   const filterField = document.getElementById('filterField');
//   const searchInput = document.getElementById('searchInput');

//   const selectedField = filterField.value;
//   const selectedFieldText = filterField.options[filterField.selectedIndex].text;
//   const searchValue = searchInput.value.trim().toLowerCase();

//   if (searchValue === '') {
//     alert('Please enter a search value');
//     return;
//   }

//   // Add filter to active filters if not already present
//   const filterExists = activeFilters.some(filter =>
//     filter.field === selectedField && filter.value === searchValue
//   );

//   if (!filterExists) {
//     activeFilters.push({
//       field: selectedField,
//       fieldText: selectedFieldText,
//       value: searchValue
//     });
//     updateActiveFiltersDisplay();
//     filterInvoices();
//   }

//   // Clear the search input
//   searchInput.value = '';
// }

function updateActiveFiltersDisplay() {
  const activeFiltersContainer = document.getElementById('activeFilters');
  if (!activeFiltersContainer) return;

  activeFiltersContainer.innerHTML = '';

  if (activeFilters.length === 0) {
    return;
  }

  const filtersTitle = document.createElement('span');
  filtersTitle.textContent = 'Active Filters: ';
  filtersTitle.style.marginRight = '10px';
  activeFiltersContainer.appendChild(filtersTitle);

  activeFilters.forEach((filter, index) => {
    const filterTag = document.createElement('span');
    filterTag.className = 'filter-tag';

    const filterText = document.createElement('span');
    filterText.textContent = `${filter.fieldText}: ${filter.value}`;
    filterTag.appendChild(filterText);

    const removeBtn = document.createElement('span');
    removeBtn.className = 'remove-btn';
    removeBtn.innerHTML = '&times;';
    removeBtn.onclick = function(e) {
      e.stopPropagation();
      removeFilter(index);
    };
    filterTag.appendChild(removeBtn);

    activeFiltersContainer.appendChild(filterTag);
  });
}

function removeFilter(index) {
  activeFilters.splice(index, 1);
  updateActiveFiltersDisplay();
  filterInvoices();
}

function filterInvoices() {
  // Implement your actual filtering logic here
  console.log('Filtering invoices with:', activeFilters);
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
  // Initialize responsive layout
  handleResize();

  // Set up event listeners
  window.addEventListener('resize', handleResize);

  // Initialize filter input
  // const searchInput = document.getElementById('searchInput');
  // if (searchInput) {
  //   searchInput.addEventListener('keyup', function(e) {
  //     if (e.key === 'Enter') {
  //       applyFilter();
  //     }
//   //   });
//   }
// });
</script> -->

<script src="/Jengopay/landlord/pages/financials/invoices/js/invoice.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const addMoreBtn = document.getElementById("addMoreBtn");
    const itemsBody = document.getElementById("itemsBody");

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
      } else if (vatType === "zero" || vatType === "exempted") {
        vatAmount = 0;
        total = subtotal;
      }

      totalInput.value = formatNumber(total);
      return { subtotal, vatAmount, total, vatType };
    }

    function updateTotalAmount() {
      let subtotalSum = 0, taxSum = 0, grandTotal = 0;
      let vat16Used = false, vat0Used = false, exemptedUsed = false;

      document.querySelectorAll("#itemsBody tr").forEach(row => {
        if (row.querySelector(".unit-price")) {
          const { subtotal, vatAmount, total, vatType } = calculateRow(row);
          subtotalSum += subtotal;
          taxSum += vatAmount;
          grandTotal += total;

          if (vatType === "inclusive" || vatType === "exclusive") {
            vat16Used = true;
          } else if (vatType === "zero") {
            vat0Used = true;
          } else if (vatType === "exempted") {
            exemptedUsed = true;
          }
        }
      });

      createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, vat16Used, vat0Used, exemptedUsed });
    }

    function createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, vat16Used, vat0Used, exemptedUsed }) {
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
          <th style="padding: 5px;">VAT 16%</th>
          <td><input type="text" class="form-control" value="${formatNumber(taxSum)}" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        ${vat0Used ? `
        <tr>
          <th style="padding: 5px;">VAT 0%</th>
          <td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        ${exemptedUsed ? `
        <tr>
          <th style="padding: 5px;">Exempted</th>
          <td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        <tr>
          <th style="padding: 5px;">Total</th>
          <td><input type="text" class="form-control" value="${formatNumber(grandTotal)}" readonly style="padding: 5px;"></td>
        </tr>
      `;
    }

    function attachEvents(row) {
      ["input", "change"].forEach(evt => {
        row.querySelectorAll(".unit-price, .quantity, .vat-option").forEach(input => {
          input.addEventListener(evt, updateTotalAmount);
        });
      });
    }

    addMoreBtn.addEventListener("click", function () {
      const newRow = document.createElement("tr");

      newRow.innerHTML = `
       <td style="min-width: 180px;">
    <select name="account_item[]" class="form-select searchable-select" required>
      <option value="" disabled selected>Select Account Item</option>
      <?php foreach ($accountItems as $item): ?>
        <option value="<?= htmlspecialchars($item['account_code']) ?>">
          <?= htmlspecialchars($item['account_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </td>

  <td style="min-width: 200px;">
    <textarea name="description[]" class="form-control" placeholder="Description" rows="1" required></textarea>
  </td>

  <td style="min-width: 100px;">
    <input type="number" name="quantity[]" class="form-control quantity" step="0.01" required>
  </td>

  <td style="min-width: 120px;">
    <input type="number" name="unit_price[]" class="form-control unit-price" step="0.01" required>
  </td>

  <td style="min-width: 180px;">
    <select name="vat_type[]" class="form-select vat-option" required>
      <option value="" disabled selected>Select Option</option>
      <option value="inclusive">VAT 16% Inclusive</option>
      <option value="exclusive">VAT 16% Exclusive</option>
      <option value="zero">Zero Rated</option>
      <option value="exempted">Exempted</option>
    </select>
  </td>

  <td style="min-width: 120px;">
    <input type="text" name="total[]" class="form-control total" readonly>
  </td>

  <td style="min-width: 50px; text-align: center;">
    <button type="button" class="btn btn-danger btn-sm delete-btn">
      <i class="fa fa-trash"></i>
    </button>
  </td>
      `;

      itemsBody.appendChild(newRow);
      attachEvents(newRow);
      updateTotalAmount();
    });

    // Delete row
    itemsBody.addEventListener("click", function (e) {
      if (e.target.closest(".delete-btn")) {
        e.target.closest("tr").remove();
        updateTotalAmount();
      }
    });

    // Attach events to any existing rows
    document.querySelectorAll("#itemsBody tr").forEach(attachEvents);
    updateTotalAmount();
  });
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const itemsBody = document.getElementById("itemsBody");

    // Trigger calculation on input changes
    itemsBody.addEventListener("input", function (e) {
        if (e.target.classList.contains("quantity") ||
            e.target.classList.contains("unit-price") ||
            e.target.classList.contains("vat-option")) {

            const row = e.target.closest("tr");
            calculateRowTotal(row);
        }
    });

    // Recalculate total when tax option changes
    itemsBody.addEventListener("change", function (e) {
        if (e.target.classList.contains("vat-option")) {
            const row = e.target.closest("tr");
            calculateRowTotal(row);
        }
    });

    function calculateRowTotal(row) {
        const qty = parseFloat(row.querySelector(".quantity")?.value) || 0;
        const price = parseFloat(row.querySelector(".unit-price")?.value) || 0;
        const tax = row.querySelector(".vat-option")?.value;

        let total = qty * price;

        if (tax === "exclusive") {
            total *= 1.16;
        } // inclusive means total is already inclusive
        // zero & exempted = no tax change

        row.querySelector(".total").value = total.toFixed(2);
    }
});
</script>


    <script>
        // Edit Invoice
        // function editInvoice(invoiceId) {
        //     // Redirect to edit page or open edit modal
        //     window.location.href = 'edit_invoice.php?id=' + invoiceId;
        // }

        // Confirm Delete Invoice
       // Confirm Delete Invoice (Soft Delete with 30-day retention)
       function confirmDeleteInvoice(invoiceId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This invoice will be marked for deletion and permanently removed after 30 days (only allowed for drafts or cancelled invoices with no payments).",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/Jengopay/landlord/pages/financials/invoices/action/delete_invoice.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + encodeURIComponent(invoiceId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Deleted!',
                        'The invoice has been marked for deletion.',
                        'success'
                    ).then(() => location.reload());
                } else {
                    Swal.fire(
                        'Not Allowed',
                        data.message || 'This invoice cannot be deleted.',
                        'warning'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Error!',
                    'Request failed: ' + error,
                    'error'
                );
            });
        }
    });
}


        // Delete Invoice
        function deleteInvoice(invoiceId) {
            fetch('/Jengopay/landlord/pages/financials/invoices/delete_invoice.php', {
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
            fetch('/Jengopay/landlord/pages/financials/invoices/action/cancel_invoice.php', {
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
            fetch('/Jengopay/landlord/pages/financials/action/restore_invoice.php', {
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
            window.location.href = '/Jengopay/landlord/pages/financials/invoices/invoice_details.php?id=' + invoiceId;
        }
    </script>


<script>
  function filterInvoices(status, paymentStatus, searchText) {
    document.querySelectorAll(".invoice-item:not(.invoice-header)").forEach(item => {
        const invoiceStatus = item.querySelector(".invoice-status span")?.innerText.toLowerCase() || "";
        const paymentStatusText = item.querySelectorAll(".invoice-status span")[1]?.innerText.toLowerCase() || "";
        const tenantName = item.querySelector(".invoice-customer")?.innerText.toLowerCase() || "";
        const invoiceNumber = item.querySelector(".invoice-number")?.innerText.toLowerCase() || "";

        // ✅ Capture Paid Amount text (second .invoice-status may contain it, or inside button text)
        const paidAmountText = paymentStatusText.match(/kes\s*[\d,]+(\.\d+)?/i)?.[0].toLowerCase() || "";

        const matchStatus = !status || invoiceStatus.includes(status);
        const matchPayment = !paymentStatus || paymentStatusText.includes(paymentStatus);
        const matchSearch = !searchText
            || tenantName.includes(searchText)
            || invoiceNumber.includes(searchText)
            || paymentStatusText.includes(searchText)
            || paidAmountText.includes(searchText); // ✅ Added

        item.style.display = matchStatus && matchPayment && matchSearch ? "" : "none";
    });
}

</script>


<script>
document.getElementById("applyFilters").addEventListener("click", function () {
  let month = document.getElementById("filterMonth").value;
  let method = document.getElementById("filterMethod").value;

  fetch("/Jengopay/landlord/pages/financials/invoices/get_payments.php?month=" 
        + encodeURIComponent(month) + "&method=" + encodeURIComponent(method))
    .then(response => response.json())
    .then(data => {
      let tbody = document.querySelector("#paymentsTable tbody");
      tbody.innerHTML = "";

      if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center">No records found</td></tr>`;
        return;
      }

      data.forEach(row => {
        tbody.innerHTML += `
          <tr>
            <td>${row.tenant || "-"}</td>
            <td>Ksh ${parseFloat(row.amount).toLocaleString()}</td>
            <td>${row.payment_method}</td>
            <td>${row.payment_date}</td>
            <td>${row.status}</td>
            <td>
              <button class="btn btn-sm btn-warning edit-payment" data-id="${row.id}">
                <i class="fas fa-edit">Edit</i>
              </button>
            </td>
          </tr>
        `;
      });

      // ✅ Re-bind edit button click after refreshing table
      document.querySelectorAll(".edit-payment").forEach(btn => {
        btn.addEventListener("click", function () {
          let id = this.getAttribute("data-id");
          loadPaymentForEdit(id); 
          new bootstrap.Modal(document.getElementById("editPaymentModal")).show();
        });
      });
    })
    .catch(err => {
      console.error("Error fetching payments:", err);
    });
});


// ✅ Define how payment details load into edit modal
function loadPaymentForEdit(id) {
  fetch("/Jengopay/landlord/pages/financials/invoices/get_payments.php?id=" + encodeURIComponent(id))
    .then(response => response.json())
    .then(data => {
      if (!data) {
        alert("Could not load payment details.");
        return;
      }

      // ✅ Match modal inputs
      document.getElementById("editPaymentId").value = data.id;
      document.getElementById("invoiceId").value = data.invoice_id;
      document.getElementById("tenantName").value = data.tenant;
      document.getElementById("editAmount").value = data.amount;
      document.getElementById("paymentMethod").value = data.payment_method;
      document.getElementById("paymentDate").value = data.payment_date;
      document.getElementById("referenceNumber").value = data.reference_number;
    })
    .catch(err => {
      console.error("Error loading payment details:", err);
    });
}
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  // Open modal and load payments
  document.getElementById("paymentsHistoryModal").addEventListener("show.bs.modal", function () {
    loadPayments(); // default load without filters
  });

  // 🔹 Apply Filters button
  document.getElementById("applyFilters").addEventListener("click", function () {
    loadPayments();
  });

  // 🔹 Load payments (with optional filters)
  function loadPayments() {
    let month = document.getElementById("filterMonth").value;
    let method = document.getElementById("filterMethod").value;

    fetch("/Jengopay/landlord/pages/financials/invoices/get_payments.php?month=" 
          + encodeURIComponent(month) + "&method=" + encodeURIComponent(method))
      .then(res => res.json())
      .then(data => {
        let tbody = document.querySelector("#paymentsTable tbody");
        tbody.innerHTML = "";

        if (!data || data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="6" class="text-center">No records found</td></tr>`;
          return;
        }

        data.forEach(p => {
          tbody.innerHTML += `
            <tr>
              <td>${p.tenant}</td>
              <td>Ksh ${parseFloat(p.amount).toLocaleString()}</td>
              <td>${p.payment_method}</td>
              <td>${p.payment_date}</td>
              <td>${p.status}</td>
              <td>
                <button class="btn btn-sm btn-warning edit-payment" 
                        data-id="${p.id}"
                        data-amount="${p.amount}"
                        data-tenant="${p.tenant}"
                        data-date="${p.payment_date}"
                        data-method="${p.payment_method}"
                        data-ref="${p.reference_number}">
                  <i class="fas fa-edit"></i> Edit
                </button>
              </td>
            </tr>`;
        });

        // Re-bind edit buttons
        document.querySelectorAll(".edit-payment").forEach(btn => {
          btn.addEventListener("click", function () {
            openEdit(
              this.dataset.id,
              this.dataset.amount,
              this.dataset.tenant,
              this.dataset.date,
              this.dataset.method,
              this.dataset.ref
            );
          });
        });
      });
  }

  // 🔹 Open edit modal
  window.openEdit = function(id, amount, tenant, payment_date, method, ref) {
    document.getElementById("editPaymentId").value = id;
    document.getElementById("editAmount").value = amount;
    document.getElementById("tenantName").value = tenant;
    document.getElementById("paymentDate").value = payment_date;
    document.getElementById("paymentMethod").value = method;
    document.getElementById("referenceNumber").value = ref;
    new bootstrap.Modal(document.getElementById("editPaymentModal")).show();
  };

  // 🔹 Submit edit form
  document.getElementById("editPaymentForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let amount = document.getElementById("editAmount").value;
    if (!confirm(`Are you sure you want to record this amount: KES ${amount}?`)) {
      return;
    }

    let formData = new FormData(this);

    fetch("/Jengopay/landlord/pages/financials/invoices/update_payment.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(result => {
      if (result.success) {
        alert("✅ Payment updated successfully!");
        bootstrap.Modal.getInstance(document.getElementById("editPaymentModal")).hide();
        loadPayments(); // refresh table with current filters
      } else {
        alert("❌ Update failed: " + result.message);
      }
    })
    .catch(err => {
      console.error("Error updating payment:", err);
    });
  });
});
</script>


<script>
// Debug: Check if PHP variables are available
console.log('Buildings data:', <?php echo json_encode($buildings ?? []); ?>);

document.getElementById('building').addEventListener('change', function() {
    const buildingId = this.value;
    const tenantSelect = document.getElementById('customer');
    
    console.log('Building selected:', buildingId);
    
    // Clear existing options
    tenantSelect.innerHTML = '<option value="">Loading tenants...</option>';
    tenantSelect.disabled = true;
    
    if (!buildingId) {
        tenantSelect.innerHTML = '<option value="">Select a Building First</option>';
        return;
    }
    
    // Fetch tenants via AJAX
    fetch(`/Jengopay/landlord/pages/financials/invoices/action/get_tenants.php?building_id=${buildingId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(tenants => {
            console.log('Tenants received:', tenants);
            tenantSelect.innerHTML = '<option value="">Select a Tenant</option>';
            
            if (tenants.length > 0 && !tenants.error) {
                tenants.forEach(tenant => {
                    const option = document.createElement('option');
                    option.value = tenant.id;
                    option.textContent = tenant.full_name;
                    tenantSelect.appendChild(option);
                });
                tenantSelect.disabled = false;
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = tenants.error ? tenants.error : 'No tenants found for this building';
                tenantSelect.appendChild(option);
                tenantSelect.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error fetching tenants:', error);
            tenantSelect.innerHTML = '<option value="">Error loading tenants</option>';
            tenantSelect.disabled = false;
        });
});
</script>

    <script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';

    if (this.files.length > 0) {
        const list = document.createElement('ul');
        list.className = 'list-group';

        for (let i = 0; i < this.files.length; i++) {
            const item = document.createElement('li');
            item.className = 'list-group-item';
            item.textContent = this.files[i].name;
            list.appendChild(item);
        }

        fileList.appendChild(list);
    } else {
        fileList.textContent = 'No files selected';
    }
});
</script>


<!-- JS TOGGLE -->
<script>
  document.getElementById("paymentsToggle").addEventListener("click", function() {
    const container = document.getElementById("paymentsContainer");
    container.style.display = (container.style.display === "none" || container.style.display === "") ? "block" : "none";
  });
</script>

    <!-- pdf download plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

                            <script>
// Format currency
function formatCurrency(num) {
    return num.toLocaleString("en-KE", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// Generate invoice number
function generateInvoiceNumber() {
    const today = new Date();
    const datePart = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, "0")}-${String(today.getDate()).padStart(2, "0")}`;
    let storedData = JSON.parse(localStorage.getItem("invoiceCounter")) || {};
    let counter = 1;
    if (storedData.date === datePart) counter = storedData.counter + 1;
    storedData = { date: datePart, counter: counter };
    localStorage.setItem("invoiceCounter", JSON.stringify(storedData));
    return `INV-${datePart}-${String(counter).padStart(3, "0")}`;
}

// Add row
function addRow() {
    const tbody = document.getElementById("invoiceBody");

    // Validate last row before adding
    const rows = tbody.querySelectorAll("tr");
    if (rows.length > 0) {
        const lastRow = rows[rows.length - 1];
        if (!validateRow(lastRow)) {
            Swal.fire({
                icon: "warning",
                title: "Incomplete Row",
                text: "Can't add a new row without filling the previous one.",
                confirmButtonColor: "#00192D"
            });
            return;
        }
    }

    const row = document.createElement("tr");
    row.innerHTML = `
        <td>
            <select class="form-control itemName" required
                onchange="updateItemOptions(); checkOthersInput(this); validateField(this); validateAllRows();">
                ${buildItemOptions()}
            </select>
            <input type="text" class="form-control mt-1 otherInput d-none" 
                placeholder="Please specify" oninput="validateField(this); validateAllRows();" />
        </td>
        <td><input type="text" class="form-control description"></td>
        <td><input type="number" class="form-control unitPrice" step="0.01" value="0" min="0"
            oninput="updateTotals(); validateField(this); validateAllRows();"></td>
        <td><input type="number" class="form-control quantity" value="1" min="1"
            oninput="updateTotals(); validateField(this); validateAllRows();"></td>
        <td>
            <select class="form-control taxType" onchange="updateTotals();">
                <option value="VAT Inclusive">VAT 16% Inclusive</option>
                <option value="VAT Exclusive">VAT 16% Exclusive</option>
                <option value="Zero Rated">Zero Rated</option>
                <option value="Exempted">Exempted</option>
            </select>
        </td>
        <td class="taxAmount amount">0.00</td>
        <td class="totalPrice amount">0.00</td>
        <td>
            <button type="button" onclick="this.closest('tr').remove(); updateTotals(); updateItemOptions(); validateAllRows();" 
                class="btn btn-sm shadow" style="background-color:#cc0001; color:#fff;">Remove</button>
        </td>`;
    tbody.appendChild(row);

    updateTotals();
    updateItemOptions();
    validateAllRows();
}

// Build options dynamically based on selected items
function buildItemOptions(currentValue = "") {
    const allItems = ["Rent","Water","Internet","Electricity","Garbage",
        "Penalty","Parking","Maintenance","Security","Welfare","Fumigation","Others"];

    const selected = Array.from(document.querySelectorAll(".itemName"))
        .map(s => s.value)
        .filter(v => v && v !== "Others");

    let options = `<option value="">-- Select Item --</option>`;
    allItems.forEach(item => {
        if (item === currentValue || item === "Others" || !selected.includes(item)) {
            options += `<option ${item === currentValue ? "selected" : ""}>${item}</option>`;
        }
    });
    return options;
}

// Refresh options on all selects
function updateItemOptions() {
    document.querySelectorAll(".itemName").forEach(select => {
        const currentValue = select.value;
        select.innerHTML = buildItemOptions(currentValue);
        select.value = currentValue; // keep selection
    });
}

// Validate row
function validateRow(row) {
    let valid = true;
    const itemName = row.querySelector(".itemName");
    const unitPrice = row.querySelector(".unitPrice");
    const quantity = row.querySelector(".quantity");
    const otherInput = row.querySelector(".otherInput");

    if (itemName.value.trim() === "") {
        itemName.classList.add("border-danger");
        valid = false;
    }
    if (itemName.value === "Others" && otherInput.value.trim() === "") {
        otherInput.classList.add("border-danger");
        valid = false;
    }
    if (unitPrice.value.trim() === "" || unitPrice.value <= 0) {
        unitPrice.classList.add("border-danger");
        valid = false;
    }
    if (quantity.value.trim() === "" || quantity.value <= 0) {
        quantity.classList.add("border-danger");
        valid = false;
    }
    return valid;
}

// Remove red border once fixed
function validateField(input) {
    if (input.value.trim() !== "" && !(input.type === "number" && input.value <= 0)) {
        input.classList.remove("border-danger");
    }
}

// Others input
function checkOthersInput(select) {
    const otherInput = select.parentElement.querySelector(".otherInput");
    if (select.value === "Others") {
        otherInput.classList.remove("d-none");
        otherInput.required = true;
    } else {
        otherInput.classList.add("d-none");
        otherInput.required = false;
        otherInput.value = "";
        otherInput.classList.remove("border-danger");
    }
}

// Update totals
function updateTotals() {
    let subtotal = 0, totalTax = 0, finalTotal = 0;
    document.querySelectorAll("#invoiceBody tr").forEach(row => {
        const unitPrice = parseFloat(row.querySelector(".unitPrice").value) || 0;
        const quantity = parseInt(row.querySelector(".quantity").value) || 0;
        const taxType = row.querySelector(".taxType").value;
        let taxAmount = 0, totalPrice = 0;

        if (taxType === "VAT Inclusive") {
            let priceWithoutTax = unitPrice / 1.16;
            taxAmount = (unitPrice - priceWithoutTax) * quantity;
            totalPrice = unitPrice * quantity;
        } else if (taxType === "VAT Exclusive") {
            taxAmount = unitPrice * 0.16 * quantity;
            totalPrice = (unitPrice * quantity) + taxAmount;
        } else {
            taxAmount = 0;
            totalPrice = unitPrice * quantity;
        }

        subtotal += unitPrice * quantity;
        totalTax += taxAmount;
        finalTotal += totalPrice;

        row.querySelector(".taxAmount").textContent = formatCurrency(taxAmount);
        row.querySelector(".totalPrice").textContent = formatCurrency(totalPrice);
    });

    document.getElementById("subtotal").textContent = formatCurrency(subtotal);
    document.getElementById("totalTax").textContent = formatCurrency(totalTax);
    document.getElementById("finalTotal").textContent = formatCurrency(finalTotal);

    document.getElementById("subtotalValue").value = subtotal.toFixed(2);
    document.getElementById("totalTaxValue").value = totalTax.toFixed(2);
    document.getElementById("finalTotalValue").value = finalTotal.toFixed(2);
}

// Prepare invoice
function prepareInvoiceData() {
    const rows = document.querySelectorAll("#invoiceBody tr");
    if (rows.length === 0) {
        Swal.fire("Error", "Please add at least one invoice item before saving.", "error");
        return false;
    }

    for (let row of rows) {
        if (!validateRow(row)) {
            Swal.fire("Error", "Please fill all required fields correctly before submitting.", "error");
            row.scrollIntoView({ behavior: "smooth", block: "center" });
            return false;
        }
    }

    const items = [];
    rows.forEach(row => {
        let itemName = row.querySelector(".itemName").value;
        const otherInput = row.querySelector(".otherInput").value.trim();
        if (itemName === "Others" && otherInput) {
            itemName = `Others - ${otherInput}`;
        }

        items.push({
            item_name: itemName,
            description: row.querySelector(".description").value,
            unit_price: parseFloat(row.querySelector(".unitPrice").value) || 0,
            quantity: parseInt(row.querySelector(".quantity").value) || 0,
            tax_type: row.querySelector(".taxType").value,
            tax_amount: parseFloat(row.querySelector(".taxAmount").textContent.replace(/,/g, "")) || 0,
            total_price: parseFloat(row.querySelector(".totalPrice").textContent.replace(/,/g, "")) || 0
        });
    });

    document.getElementById("invoiceItems").value = JSON.stringify(items);
    return true;
}

// Init
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("invoiceNumber").value = generateInvoiceNumber();
    const invoiceDateInput = document.getElementById("invoiceDate");
    const dateDueInput = document.getElementById("dateDue");
    const today = new Date().toISOString().split("T")[0];
    invoiceDateInput.setAttribute("min", today);

    invoiceDateInput.addEventListener("change", function () {
        const invoiceDate = new Date(this.value);
        if (isNaN(invoiceDate)) return;
        const dueDate = new Date(invoiceDate);
        dueDate.setDate(dueDate.getDate() + 2);
        dateDueInput.value = dueDate.toISOString().split("T")[0];
    });

    addRow();
});
</script>

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

    </div>
    <!--end::App Wrapper-->

    <!-- plugin for pdf -->


    <!-- Main Js File -->
    <script src="../../js/adminlte.js"></script>
    <script src="js/main.js"></script>
    <!-- html2pdf depends on html2canvas and jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script type="module" src="./js/main.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <!-- pdf download plugin -->


    <!-- Scripts -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<!--end::Body-->

</html>