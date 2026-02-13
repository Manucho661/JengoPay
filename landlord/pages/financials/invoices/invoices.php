<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';
include '../../db/connect.php';


/* -----------------------------
   FETCH REVENUE ACCOUNTS
----------------------------- */
try {
  $stmt = $pdo->prepare("
        SELECT account_code, account_name
        FROM chart_of_accounts
        WHERE account_type = 'Revenue'
        ORDER BY account_name ASC
    ");
  $stmt->execute();
  $accountItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Error fetching revenue accounts: " . $e->getMessage());
  $accountItems = [];
}

/* -----------------------------
   INVOICE NUMBER GENERATION
----------------------------- */
$isDraft = isset($_POST['status']) && $_POST['status'] === 'draft';
$prefix  = $isDraft ? 'DFT' : 'INV';

try {
  $stmt = $pdo->prepare("
        SELECT invoice_no
        FROM invoice
        WHERE invoice_no LIKE ? 
        ORDER BY id DESC 
        LIMIT 1
    ");
  $stmt->execute([$prefix . '%']);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row && preg_match('/' . $prefix . '(\d+)/', $row['invoice_no'], $matches)) {
    $lastNumber = (int)$matches[1];
    $newNumber  = $lastNumber + 1;
  } else {
    $newNumber = 1;
  }

  $invoiceNumber = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
} catch (PDOException $e) {
  error_log("Error generating invoice number: " . $e->getMessage());
  $invoiceNumber = $prefix . "001";
}

/* -----------------------------
   FETCH BUILDINGS LIST
----------------------------- */
try {
  $stmt = $pdo->prepare("
        SELECT id, building_name 
        FROM buildings 
        ORDER BY building_name ASC
    ");
  $stmt->execute();
  $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Error fetching buildings: " . $e->getMessage());
  $buildings = [];
}

/* -----------------------------
   FETCH ACTIVE TENANTS FROM ALL UNIT TABLES
----------------------------- */
try {
  $sql = "
        SELECT 
            id,
            tfirst_name AS first_name,
            tmiddle_name AS middle_name,
            tlast_name AS last_name,
            building_link AS building,
            unit_number,
            'Bedsitter' AS unit_type
        FROM bedsitter_units
        WHERE tenant_status = 'Active'

        UNION ALL

        SELECT 
            id,
            tfirst_name AS first_name,
            tmiddle_name AS middle_name,
            tlast_name AS last_name,
            building_link AS building,
            unit_number,
            'Single' AS unit_type
        FROM single_units
        WHERE tenant_status = 'Active'

        UNION ALL

        SELECT 
            id,
            tfirst_name AS first_name,
            tmiddle_name AS middle_name,
            tlast_name AS last_name,
            building_link AS building,
            unit_number,
            'Multi-Room' AS unit_type
        FROM multi_rooms_units
        WHERE tenant_status = 'Active'
    ";

  $stmt = $pdo->query($sql);
  $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Error fetching tenants: " . $e->getMessage());
  $tenants = [];
}

?>

<?php
// ----------------------------------------------------
// 1) Fetch invoices with tenant details and payment summary - UPDATED WITH PROPER JOIN
// ----------------------------------------------------
$stmt = $pdo->query("
    SELECT
        i.id,
        i.invoice_no,
        i.receiver,
        i.phone,
        i.email,
        i.invoice_date,
        i.due_date,
        i.notes AS description,
        COALESCE(i.subtotal, 0) AS subtotal,
        COALESCE(i.total, 0) AS total,
        COALESCE(i.taxes, 0) AS taxes,
        i.status,
        i.payment_status,
        
        -- Tenant details from tenants table
        t.id AS tenant_id,
        CONCAT(t.first_name, ' ', t.last_name) AS tenant_name,
        t.account_no,
        
        -- Payment calculations
        (SELECT COALESCE(SUM(p.amount), 0)
         FROM payments p
         WHERE p.invoice_id = i.id) AS paid_amount,
        
        -- Invoice items totals
        (SELECT COALESCE(SUM(unit_price * quantity), 0) 
         FROM invoice_items 
         WHERE invoice_id = i.id) AS items_subtotal,
        
        (SELECT COALESCE(SUM(tax_amount), 0) 
         FROM invoice_items 
         WHERE invoice_id = i.id) AS items_taxes,
        
        (SELECT COALESCE(SUM(total_price), 0) 
         FROM invoice_items 
         WHERE invoice_id = i.id) AS items_total,
        
        -- Final display values
        CASE
            WHEN EXISTS (SELECT 1 FROM invoice_items WHERE invoice_id = i.id)
            THEN (SELECT COALESCE(SUM(unit_price * quantity), 0) FROM invoice_items WHERE invoice_id = i.id)
            ELSE i.subtotal
        END AS display_subtotal,
        
        CASE
            WHEN EXISTS (SELECT 1 FROM invoice_items WHERE invoice_id = i.id)
            THEN (SELECT COALESCE(SUM(tax_amount), 0) FROM invoice_items WHERE invoice_id = i.id)
            ELSE i.taxes
        END AS display_taxes,
        
        CASE
            WHEN EXISTS (SELECT 1 FROM invoice_items WHERE invoice_id = i.id)
            THEN (SELECT COALESCE(SUM(total_price), 0) FROM invoice_items WHERE invoice_id = i.id)
            ELSE i.total
        END AS display_total
        
    FROM invoice i
    LEFT JOIN tenants t ON i.tenant_id = t.id
    ORDER BY i.created_at DESC
");
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <link rel="stylesheet" href="/Jengopay/landlord/pages/financials/invoices/css/invoices.css">
  <!-- <link rel="stylesheet" href="text.css" /> -->
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../../../landlord/assets/main.css" />

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
      background-color: #00192D;
      color: #FFC107;
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
    /* header {
      background-color: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    } */

    .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 0;
    }

    /* .logo {
      font-size: 24px;
      font-weight: 700;
      color: #2a5bd7;
    }

    .logo span {
      color: #ff6b00;
    } */

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
      box-shadow: -2px 0 8px rgba(0, 0, 0, 0.3);
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
      .invoice-item>div {
        padding: 8px 4px;
      }
    }

    @media (max-width: 992px) {
      .invoice-container {
        overflow-x: auto;
      }

      .invoice-item {
        min-width: 900px;
        /* Forces horizontal scroll */
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
      .filter-panel .row>div {
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

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <!--begin::Header-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; ?>
    <!--end::Header-->
    <!--begin::Sidebar-->

    <!--begin::Sidebar Wrapper-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/JengoPay/landlord/pages/includes/sidebar.php'; ?>

    <!--end::Sidebar-->
    <!--begin::App Main-->
    <main class="main">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/Jengopay/landlord/pages/Dashboard/dashboard.php" style="text-decoration: none;">Home</a>
          </li>
          <li class="breadcrumb-item active">Invoices</li>
        </ol>
      </nav>

      <!--begin::first Row-->
      <div class="row align-items-center mb-3">
        <div class="col-12 d-flex align-items-center">
          <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
          <h3 class="mb-0 ms-3">Invoices</h3>
        </div>
      </div>
      <!-- Third row: stats -->
      <div class="row g-3 mt-2 mb-4">
        <!-- Total Items Card -->
        <div class="col-md-6 col-lg-3">
          <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
            <div>
              <i class="fa fa-box fs-1 me-3 text-warning"></i>
            </div>
            <div>
              <p class="mb-0" style="font-weight: bold;">Total Items</p>
              <b>Pieces</b>
            </div>
          </div>
        </div>

        <!-- Totals Card -->
        <div class="col-md-6 col-lg-3">
          <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
            <div>
              <i class="fa fa-calendar-alt fs-1 me-3 text-warning"></i>
            </div>
            <div>
              <p class="mb-0" style="font-weight: bold;">Total Amount</p>
              <b>KSH </b>
            </div>
          </div>
        </div>

        <!-- Paid Card -->
        <div class="col-md-6 col-lg-3">
          <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
            <div>
              <i class="fa fa-check-circle fs-1 me-3 text-warning"></i>
            </div>
            <div>
              <p class="mb-0" style="font-weight: bold;">Paid</p>
              <b>KSH </b>
            </div>
          </div>
        </div>

        <!-- Pending Card -->
        <div class="col-md-6 col-lg-3">
          <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100" style="border-left-color:#e74c3c">
            <div>
              <i class="fa fa-hourglass-half fs-1 me-3" style="color:#e74c3c"></i>
            </div>
            <div>
              <p class="mb-0" style="font-weight: bold;">Outstanding</p>
              <b style="color:#e74c3c">KSH </b>
            </div>
          </div>
        </div>
      </div>
      <div class="row g-3 mt-2 mb-4">
        <div class="col-md-12">
          <div class="card border-0">
            <div class="card-body">
              <div class="row g-3 align-items-end">
                <div class="col-md-3">
                  <label class="form-label text-muted small">Search</label>
                  <input type="text" class="form-control" placeholder="Invoice number, tenant...">
                </div>
                <div class="col-md-2">
                  <label class="form-label text-muted small">Status</label>
                  <select class="form-select">
                    <option value="">All Status</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                    <option value="partial">Partial</option>
                    <option value="draft">Draft</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label class="form-label text-muted small">Date From</label>
                  <input type="date" class="form-control">
                </div>
                <div class="col-md-2">
                  <label class="form-label text-muted small">Date To</label>
                  <input type="date" class="form-control">
                </div>
                <div class="col-md-3 text-end">
                  <button class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                  </button>
                  <button class="actionBtn">
                    <i class="fas fa-search"></i> Apply
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mt-2 mb-4">
        <div class="col-md-12">
          <div class="card border-0">
            <div class="card-header">
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <div class="table-responsive">
                  <table class="table table-hover mb-0" id="invoicesTable">
                    <thead>
                      <tr>
                        <th style="width: 50px;">
                          <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                        </th>
                        <th class="col-invoice">Invoice #</th>
                        <th class="col-tenant">Tenant</th>
                        <th class="col-date">Issue Date</th>
                        <th class="col-due" style="display: none;">Due Date</th>
                        <th class="col-subtotal" style="display: none;">Subtotal</th>
                        <th class="col-tax" style="display: none;">Tax</th>
                        <th class="col-total">Total</th>
                        <th class="col-paid" style="display: none;">Paid</th>
                        <th class="col-balance" style="display: none;">Balance</th>
                        <th class="col-status">Status</th>
                        <th style="width: 120px;">Actions</th>
                      </tr>
                    </thead>

                    <tbody>

                      <!-- Row 1 -->
                      <tr>
                        <td><input type="checkbox" class="invoice-checkbox"></td>
                        <td class="col-invoice"><strong>INV-1001</strong></td>
                        <td class="col-tenant">
                          John Kamau<br>
                          <small class="text-muted">Unit A3</small>
                        </td>
                        <td class="col-date">2026-02-01</td>
                        <td class="col-total"><strong>KES 12,000</strong></td>
                        <td class="col-status">
                          <span class="status-badge bg-success text-white px-2 py-1 rounded">
                            Paid
                          </span>
                        </td>
                        <td>
                          <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                          <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                          <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                        </td>
                      </tr>

                      <!-- Row 2 -->
                      <tr>
                        <td><input type="checkbox" class="invoice-checkbox"></td>
                        <td class="col-invoice"><strong>INV-1002</strong></td>
                        <td class="col-tenant">
                          Mary Wanjiku<br>
                          <small class="text-muted">Unit B1</small>
                        </td>
                        <td class="col-date">2026-02-03</td>
                        <td class="col-total"><strong>KES 9,500</strong></td>
                        <td class="col-status">
                          <span class="status-badge bg-warning text-dark px-2 py-1 rounded">
                            Partial
                          </span>
                        </td>
                        <td>
                          <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                          <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                          <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                        </td>
                      </tr>

                      <!-- Row 3 -->
                      <tr>
                        <td><input type="checkbox" class="invoice-checkbox"></td>
                        <td class="col-invoice"><strong>INV-1003</strong></td>
                        <td class="col-tenant">
                          Alex Otieno<br>
                          <small class="text-muted">Unit C2</small>
                        </td>
                        <td class="col-date">2026-02-05</td>
                        <td class="col-total"><strong>KES 15,200</strong></td>
                        <td class="col-status">
                          <span class="status-badge bg-danger text-white px-2 py-1 rounded">
                            Unpaid
                          </span>
                        </td>
                        <td>
                          <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                          <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                          <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                        </td>
                      </tr>

                    </tbody>
                  </table>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!--begin::Footer-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
    <!--end::Footer-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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


    <script src="../../../../landlord/assets/main.js"></script> <!-- links for dataTaable buttons -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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