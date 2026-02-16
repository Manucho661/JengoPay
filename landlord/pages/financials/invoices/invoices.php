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
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Invoices</title>
  <!--begin::Primary Meta Tags-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/htmlHeader.php'; ?>

  <style>
    :root {
      --sidebar-width: 260px;
      --main-color: #00192D;
      --accent-color: #FFC107;
      --white-color: #FFFFFF;
      --light-bg: #f8f9fa;
      --success-color: #27ae60;
      --danger-color: #e74c3c;
    }

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

    /* Column Selector Dropdown */
    .column-selector {
      position: relative;
      display: inline-block;
    }

    .column-dropdown {
      position: absolute;
      top: 45px;
      right: 0;
      background: white;
      border: 1px solid #ddd;
      border-radius: 8px;

      padding: 15px;
      width: 250px;
      display: none;
      z-index: 2000;
    }

    .column-dropdown.show {
      display: block;
    }

    .column-dropdown-header {
      font-weight: 600;
      color: var(--main-color);
      margin-bottom: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid #e0e0e0;
    }

    .column-option {
      display: flex;
      align-items: center;
      padding: 8px 0;
    }

    .column-option input[type="checkbox"] {
      margin-right: 10px;
      accent-color: var(--accent-color);
    }

    .column-option label {
      color: #00192D;
      cursor: pointer;
      margin: 0;
      font-size: 14px;
    }

    /* Footer */
    .custom-footer {
      background: var(--main-color);
      color: white;
      padding: 30px;
      margin-top: auto;
    }

    .custom-footer a {
      color: var(--accent-color);
      text-decoration: none;
      transition: color 0.3s;
    }

    .custom-footer a:hover {
      color: white;
    }

    /* Modal */
    .modal-header {
      background: var(--main-color);
      color: white;
    }

    .modal-header .btn-close {
      filter: invert(1);
    }

    .modal-title {
      color: white;
    }

    .add-item-btn {
      background: rgba(255, 193, 7, 0.1);
      border: 1px dashed var(--accent-color);
      color: var(--accent-color);
      padding: 10px;
      width: 100%;
      border-radius: 5px;
      transition: all 0.3s;
    }

    .add-item-btn:hover {
      background: var(--accent-color);
      color: var(--main-color);
    }

    /* Bulk Actions */
    .bulk-actions-bar {
      background: rgba(255, 193, 7, 0.1);
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      display: none;
    }

    .bulk-actions-bar.show {
      display: block;
    }

    .btn-primary {
      background: var(--main-color);
      border-color: var(--main-color);
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
      background: #001a3a;
      border-color: #001a3a;
    }

    .btn-success {
      background: var(--accent-color);
      border-color: var(--accent-color);
      color: var(--main-color);
      font-weight: 600;
    }

    .btn-success:hover,
    .btn-success:focus,
    .btn-success:active {
      background: #e6ad06;
      border-color: #e6ad06;
      color: var(--main-color);
    }

    .btn:focus,
    .btn:active {
      box-shadow: none;
      outline: none;
    }

    /* Card */
    .card {
      border: none;

    }

    .card-title {
      color: var(--main-color);
      font-weight: 600;
    }

    /* Form Controls */
    .form-select,
    .form-control {
      background: rgba(255, 193, 7, 0.05);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--accent-color);
      box-shadow: none;
      outline: none;
      background: rgba(255, 193, 7, 0.1);
    }

    /* Table */


    .table tbody td {
      padding: 15px 10px;
      vertical-align: middle;
      color: var(--main-color);
      font-size: 14px;
    }

    .table-hover tbody tr:hover {
      background: rgba(255, 193, 7, 0.05);
    }

    .status-badge {
      padding: 5px 12px;
      border-radius: 15px;
      font-size: 11px;
      font-weight: 600;
      white-space: nowrap;
    }

    .badge-paid {
      background: rgba(39, 174, 96, 0.15);
      color: #27ae60;
    }

    .badge-overdue {
      background: rgba(231, 76, 60, 0.15);
      color: #e74c3c;
    }

    .badge-partial {
      background: rgba(243, 156, 18, 0.15);
      color: #f39c12;
    }

    .badge-draft {
      background: rgba(149, 165, 166, 0.15);
      color: #95a5a6;
    }

    .action-btn {
      width: 32px;
      height: 32px;
      border: none;
      border-radius: 5px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s;
      margin: 0 2px;
      font-size: 13px;
    }

    .action-btn.view-btn {
      background: rgba(0, 25, 45, 0.1);
      color: var(--main-color);
    }

    .action-btn.view-btn:hover {
      background: var(--main-color);
      color: white;
    }

    .action-btn.edit-btn {
      background: rgba(255, 193, 7, 0.2);
      color: #d39e00;
    }

    .action-btn.edit-btn:hover {
      background: var(--accent-color);
      color: var(--main-color);
    }

    .action-btn.delete-btn {
      background: rgba(231, 76, 60, 0.1);
      color: var(--danger-color);
    }

    .action-btn.delete-btn:hover {
      background: var(--danger-color);
      color: white;
    }

    /* Column Selector Dropdown */
    .column-selector {
      position: relative;
      display: inline-block;
    }

    .column-dropdown {
      position: absolute;
      top: 45px;
      right: 0;
      background: white;
      border: 1px solid #ddd;
      border-radius: 8px;

      padding: 15px;
      width: 250px;
      display: none;
      z-index: 1000;
    }

    .column-dropdown.show {
      display: block;
    }

    .column-dropdown-header {
      font-weight: 600;
      color: var(--main-color);
      margin-bottom: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid #e0e0e0;
    }

    .column-option {
      display: flex;
      align-items: center;
      padding: 8px 0;
    }

    .column-option input[type="checkbox"] {
      margin-right: 10px;
      accent-color: var(--accent-color);
    }

    .column-option label {
      cursor: pointer;
      margin: 0;
      font-size: 14px;
    }

    /* Footer */
    .custom-footer {
      background: var(--main-color);
      color: white;
      padding: 30px;
      margin-top: auto;
    }

    .custom-footer a {
      color: var(--accent-color);
      text-decoration: none;
      transition: color 0.3s;
    }

    .custom-footer a:hover {
      color: white;
    }

    /* Modal */
    .modal-header {
      background: var(--main-color);
      color: white;
    }

    .modal-header .btn-close {
      filter: invert(1);
    }

    .modal-title {
      color: white;
    }

    .add-item-btn {
      background: rgba(255, 193, 7, 0.1);
      border: 1px dashed var(--accent-color);
      color: var(--accent-color);
      padding: 10px;
      width: 100%;
      border-radius: 5px;
      transition: all 0.3s;
    }

    .add-item-btn:hover {
      background: var(--accent-color);
      color: var(--main-color);
    }

    /* Bulk Actions */
    .bulk-actions-bar {
      background: rgba(255, 193, 7, 0.1);
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      display: none;
    }

    .bulk-actions-bar.show {
      display: block;
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
                  <button class="cancelBtn">
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
            <div class="card-header" style="background-color: #00192D; color:#fff;">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <b>All invoices (<span class="text-warning">0</span>)</b>
                </div>
                <div class="d-flex">
                  <!-- Column Selector -->
                  <div class="column-selector">
                    <button class="btn btn-secondary mx-2" onclick="toggleColumnSelector()">
                      <i class="fas fa-columns"></i> Columns
                    </button>
                    <div class="column-dropdown" id="columnDropdown">
                      <div class="column-dropdown-header">
                        <i class="fas fa-list"></i> Show/Hide Columns
                      </div>
                      <div class="column-option">
                        <input type="checkbox" id="col-invoice" checked onchange="toggleColumn('invoice')">
                        <label for="col-invoice">Invoice Number</label>
                      </div>
                      <div class="column-option">
                        <input type="checkbox" id="col-tenant" checked onchange="toggleColumn('tenant')">
                        <label for="col-tenant">Tenant</label>
                      </div>
                      <div class="column-option">
                        <input type="checkbox" id="col-date" checked onchange="toggleColumn('date')">
                        <label for="col-date">Issue Date</label>
                      </div>
                      <div class="column-option">
                        <input type="checkbox" id="col-due" onchange="toggleColumn('due')">
                        <label for="col-due">Due Date</label>
                      </div>
                      <div class="column-option">
                        <input type="checkbox" id="col-subtotal" onchange="toggleColumn('subtotal')">
                        <label for="col-subtotal">Subtotal</label>
                      </div>
                      <div class="column-option">
                        <input type="checkbox" id="col-tax" onchange="toggleColumn('tax')">
                        <label for="col-tax">Tax</label>
                      </div>
                      <div class="column-option">
                        <input type="checkbox" id="col-total" checked onchange="toggleColumn('total')">
                        <label for="col-total">Total</label>
                      </div>
                      <div class="column-option">
                        <input type="checkbox" id="col-paid" onchange="toggleColumn('paid')">
                        <label for="col-paid">Paid</label>
                      </div>
                      <div class="column-option">
                        <input type="checkbox" id="col-balance" onchange="toggleColumn('balance')">
                        <label for="col-balance">Balance</label>
                      </div>
                      <div class="column-option">
                        <input type="checkbox" id="col-status" checked onchange="toggleColumn('status')">
                        <label for="col-status">Status</label>
                      </div>
                    </div>
                  </div>

                  <button class="actionBtn2" data-bs-toggle="offcanvas" data-bs-target="#createInvoiceOffcanvas">
                    <i class="fas fa-plus"></i> Create Invoice
                  </button>
                </div>
              </div>
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
                        <td class="col-due" style="display: none;">2026-02-01</td>
                        <td class="col-subtotal" style="display: none;">KES 70000</td>
                        <td class="col-tax" style="display: none;">KES 6000</td>
                        <td class="col-total"><strong>KES 12,000</strong></td>
                         <td class="col-paid" style="display: none;">KES 5000</td>
                        <td class="col-balance" style="display: none;">
                          <span class="text-danger">
                            KES 3000
                          </span>
                        </td>
                        <td class="col-status">
                          <span class="status-badge badge-draft">
                            Paid
                          </span>
                        </td>
                        <td>
                          <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                          <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                          <button class="action-btn delete-btn mt-1"><i class="fas fa-trash"></i></button>
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
                        <td class="col-due" style="display: none;">2026-02-01</td>
                        <td class="col-subtotal" style="display: none;">KES 70000</td>
                        <td class="col-tax" style="display: none;">KES 6000</td>
                        <td class="col-total"><strong>KES 9,500</strong></td>
                         <td class="col-paid" style="display: none;">KES 5000</td>
                        <td class="col-balance" style="display: none;">
                          <span class="text-danger">
                            KES 3000
                          </span>
                        </td>
                        <td class="col-status">
                          <span class="status-badge badge-payment-partial">
                            Partial
                          </span>
                        </td>
                        <td>
                          <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                          <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                          <button class="action-btn delete-btn mt-1"><i class="fas fa-trash"></i></button>
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
                        <td class="col-due" style="display: none;">2026-02-01</td>

                        <td class="col-subtotal" style="display: none;">KES 70000</td>
                        <td class="col-tax" style="display: none;">KES 6000</td>
                        <td class="col-total"><strong>KES 15,200</strong></td>
                        <td class="col-paid" style="display: none;">KES 5000</td>
                        <td class="col-balance" style="display: none;">
                          <span class="text-danger">
                            KES 3000
                          </span>
                        </td>

                        <td class="col-status">
                          <span class="status-badge badge-paid">
                            Unpaid
                          </span>
                        </td>

                        <td>
                          <button class="action-btn view-btn" title="View Invoice">
                            <i class="fas fa-eye"></i>
                          </button>
                          <button class="action-btn edit-btn" title="Edit">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="action-btn delete-btn mt-1" title="Delete">
                            <i class="fas fa-trash"></i>
                          </button>
                        </td>

                      </tr>
                      <tr>
                        <td><input type="checkbox" class="invoice-checkbox"></td>
                        <td class="col-invoice"><strong>INV-1001</strong></td>
                        <td class="col-tenant">
                          John Kamau<br>
                          <small class="text-muted">Unit A3</small>
                        </td>
                        <td class="col-date">2026-02-01</td>
                        <td class="col-due" style="display: none;">2026-02-01</td>
                        <td class="col-subtotal" style="display: none;">KES 70000</td>
                        <td class="col-tax" style="display: none;">KES 6000</td>

                        <td class="col-total"><strong>KES 12,000</strong></td>
                         <td class="col-paid" style="display: none;">KES 5000</td>

                        <td class="col-balance" style="display: none;">
                          <span class="text-danger">
                            KES 3000
                          </span>
                        </td>
                        <td class="col-status">
                          <span class="status-badge badge-draft">
                            Paid
                          </span>
                        </td>
                        <td>
                          <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                          <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                          <button class="action-btn delete-btn mt-1"><i class="fas fa-trash"></i></button>
                        </td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" class="invoice-checkbox"></td>
                        <td class="col-invoice"><strong>INV-1001</strong></td>
                        <td class="col-tenant">
                          John Kamau<br>
                          <small class="text-muted">Unit A3</small>
                        </td>
                        <td class="col-date">2026-02-01</td>
                        <td class="col-due" style="display: none;">2026-02-01</td>
                        <td class="col-subtotal" style="display: none;">KES 70000</td>
                        <td class="col-tax" style="display: none;">KES 6000</td>

                        <td class="col-total"><strong>KES 12,000</strong></td>
                        <td class="col-paid" style="display: none;">KES 5000</td>
                        <td class="col-balance" style="display: none;">
                          <span class="text-danger">
                            KES 3000
                          </span>
                        </td>
                        <td class="col-status">
                          <span class="status-badge badge-draft">
                            Paid
                          </span>
                        </td>
                        <td>
                          <button class="action-btn view-btn"><i class="fas fa-eye"></i></button>
                          <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                          <button class="action-btn delete-btn mt-1"><i class="fas fa-trash"></i></button>
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

    <!-- ModaLS and offcanvas -->
    <!-- Create Invoice Off-canvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="createInvoiceOffcanvas" style="width: 800px !important;">
      <div class="offcanvas-header" style="background: var(--main-color); color: white;">
        <h5 class="offcanvas-title" style="color: white;">
          <i class="fas fa-plus-circle"></i> Create New Invoice
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <form method="POST" id="createInvoiceForm">
          <!-- Invoice Details -->
          <div class="mb-4">
            <h6 style="color: var(--main-color); font-weight: 600; margin-bottom: 15px;">
              <i class="fas fa-info-circle"></i> Invoice Details
            </h6>
            <div class="mb-3">
              <label class="form-label">Invoice Number *</label>
              <input type="text" name="invoice_number" class="form-control" value="INV-2024-" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Building *</label>
              <select name="building" class="form-select" onchange="updateUnitsForBuilding(this.value)" required>
                <option value="">Select Building</option>
                <option value="1">Hindocha Tower</option>
                <option value="2">Vista Apartments</option>
                <option value="3">Green Valley Homes</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Unit *</label>
              <select name="unit" class="form-select" id="unitSelect" onchange="updateTenantForUnit(this.value)" required disabled>
                <option value="">Select building first</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Tenant *</label>
              <select name="tenant" class="form-select" id="tenantSelect" required disabled>
                <option value="">Select unit first</option>
              </select>
            </div>
            <div class="row g-3">
              <div class="col-6">
                <label class="form-label">Issue Date *</label>
                <input type="date" name="issue_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
              </div>
              <div class="col-6">
                <label class="form-label">Due Date *</label>
                <input type="date" name="due_date" class="form-control" required>
              </div>
            </div>
          </div>

          <!-- Invoice Items -->
          <div class="mb-4">
            <h6 style="color: var(--main-color); font-weight: 600; margin-bottom: 15px;">
              <i class="fas fa-list"></i> Invoice Items
            </h6>
            <div class="table-responsive">
              <table class="table invoice-items-table table-sm" id="invoiceItemsTable">
                <thead>
                  <tr>
                    <th style="width: 150px;">Item</th>
                    <th>Description</th>
                    <th style="width: 80px;">Quantity</th>
                    <th style="width: 100px;">Price</th>
                    <th style="width: 80px;">Discount</th>
                    <th style="width: 70px;">Tax %</th>
                    <th style="width: 100px;">Amount</th>
                    <th style="width: 40px;"></th>
                  </tr>
                </thead>
                <tbody id="invoiceItemsBody">
                  <tr class="item-row">
                    <td>
                      <select class="form-select form-select-sm item-name" name="items[0][item]" onchange="updateItemDescription(this)" required>
                        <option value="">Select Item</option>
                        <option value="rent">Rent</option>
                        <option value="water">Water</option>
                        <option value="electricity">Electricity</option>
                        <option value="security">Security</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="parking">Parking</option>
                        <option value="other">Other</option>
                      </select>
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sm item-desc" name="items[0][description]" placeholder="Item description" required>
                    </td>
                    <td>
                      <input type="number" class="form-control form-control-sm item-qty" name="items[0][quantity]" value="1" min="1" onchange="calculateRowTotal(this)" required>
                    </td>
                    <td>
                      <input type="number" class="form-control form-control-sm item-price" name="items[0][price]" placeholder="0.00" step="0.01" onchange="calculateRowTotal(this)" required>
                    </td>
                    <td>
                      <input type="number" class="form-control form-control-sm item-discount" name="items[0][discount]" value="0" min="0" step="0.01" onchange="calculateRowTotal(this)">
                    </td>
                    <td>
                      <input type="number" class="form-control form-control-sm item-tax" name="items[0][tax]" value="16" min="0" step="0.01" onchange="calculateRowTotal(this)">
                    </td>
                    <td>
                      <input type="text" class="form-control form-control-sm item-amount" name="items[0][amount]" value="0.00" readonly>
                    </td>
                    <td>
                      <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)" style="padding: 2px 6px;">
                        <i class="fas fa-times"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <button type="button" class="add-item-btn mt-2" onclick="addInvoiceItem()">
              <i class="fas fa-plus"></i> Add Item
            </button>
          </div>

          <!-- Totals -->
          <div class="mb-4">
            <div style="background: rgba(255, 193, 7, 0.1); padding: 20px; border-radius: 10px; border-left: 4px solid var(--accent-color);">
              <div class="d-flex justify-content-between mb-2">
                <span>Subtotal:</span>
                <strong id="invoiceSubtotal">KES 0.00</strong>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Total Discount:</span>
                <strong id="invoiceDiscount" style="color: var(--danger-color);">KES 0.00</strong>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Total Tax:</span>
                <strong id="invoiceTax">KES 0.00</strong>
              </div>
              <div class="d-flex justify-content-between pt-2 border-top">
                <strong>Total:</strong>
                <strong id="invoiceTotal" style="color: var(--accent-color); font-size: 20px;">KES 0.00</strong>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-primary btn-lg" onclick="previewInvoice()">
              <i class="fas fa-eye"></i> Preview
            </button>
            <button type="button" class="btn btn-secondary btn-lg" onclick="saveAsDraft()">
              <i class="fas fa-save"></i> Save as Draft
            </button>
            <button type="submit" name="create_invoice" class="btn btn-success btn-lg">
              <i class="fas fa-paper-plane"></i> Confirm and Send
            </button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>



    <!--begin::Footer-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
    <!--end::Footer-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



    <script src="../../../../landlord/assets/main.js"></script> <!-- links for dataTaable buttons -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- OPTIONAL SCRIPTS -->
    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"></script>


    <script>
      let itemCounter = 1;

      // Building to Units mapping
      const buildingUnits = {
        '1': [{
            id: '101',
            name: 'Unit 101',
            tenant: 'Sarah Johnson'
          },
          {
            id: '102',
            name: 'Unit 102',
            tenant: 'Michael Chen'
          },
          {
            id: '103',
            name: 'Unit 103',
            tenant: 'Vacant'
          }
        ],
        '2': [{
            id: '201',
            name: 'Unit 201',
            tenant: 'Emma Wilson'
          },
          {
            id: '202',
            name: 'Unit 202',
            tenant: 'James Anderson'
          },
          {
            id: '203',
            name: 'Unit 203',
            tenant: 'Vacant'
          }
        ],
        '3': [{
            id: '301',
            name: 'Unit 301',
            tenant: 'Lisa Brown'
          },
          {
            id: '302',
            name: 'Unit 302',
            tenant: 'Robert Taylor'
          }
        ]
      };

      // Item descriptions
      const itemDescriptions = {
        'rent': 'Monthly Rent - February 2024',
        'water': 'Water Bill',
        'electricity': 'Electricity Bill',
        'security': 'Security Fee',
        'maintenance': 'Maintenance Fee',
        'parking': 'Parking Fee',
        'other': ''
      };

      // Update units when building is selected
      function updateUnitsForBuilding(buildingId) {
        const unitSelect = document.getElementById('unitSelect');
        const tenantSelect = document.getElementById('tenantSelect');

        unitSelect.innerHTML = '<option value="">Select Unit</option>';
        tenantSelect.innerHTML = '<option value="">Select unit first</option>';
        tenantSelect.disabled = true;

        if (buildingId && buildingUnits[buildingId]) {
          buildingUnits[buildingId].forEach(unit => {
            const option = document.createElement('option');
            option.value = unit.id;
            option.textContent = unit.name;
            option.dataset.tenant = unit.tenant;
            unitSelect.appendChild(option);
          });
          unitSelect.disabled = false;
        } else {
          unitSelect.disabled = true;
        }
      }

      // Update tenant when unit is selected
      function updateTenantForUnit(unitId) {
        const unitSelect = document.getElementById('unitSelect');
        const tenantSelect = document.getElementById('tenantSelect');
        const selectedOption = unitSelect.options[unitSelect.selectedIndex];

        if (unitId && selectedOption) {
          const tenantName = selectedOption.dataset.tenant;
          tenantSelect.innerHTML = '';

          if (tenantName && tenantName !== 'Vacant') {
            const option = document.createElement('option');
            option.value = unitId;
            option.textContent = tenantName;
            option.selected = true;
            tenantSelect.appendChild(option);
            tenantSelect.disabled = false;
          } else {
            tenantSelect.innerHTML = '<option value="">Unit is vacant</option>';
            tenantSelect.disabled = true;
          }
        } else {
          tenantSelect.innerHTML = '<option value="">Select unit first</option>';
          tenantSelect.disabled = true;
        }
      }

      // Update item description when item is selected
      function updateItemDescription(select) {
        const row = select.closest('tr');
        const descInput = row.querySelector('.item-desc');
        const itemValue = select.value;

        if (itemDescriptions[itemValue]) {
          descInput.value = itemDescriptions[itemValue];
        }
      }

      // Toggle column selector dropdown
      function toggleColumnSelector() {
        const dropdown = document.getElementById('columnDropdown');
        dropdown.classList.toggle('show');
      }

      // Close dropdown when clicking outside
      document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('columnDropdown');
        const selector = document.querySelector('.column-selector');

        if (selector && !selector.contains(event.target)) {
          dropdown.classList.remove('show');
        }
      });

      // Toggle column visibility
      function toggleColumn(columnName) {
        const columns = document.querySelectorAll(`.col-${columnName}`);
        const checkbox = document.getElementById(`col-${columnName}`);

        columns.forEach(col => {
          col.style.display = checkbox.checked ? '' : 'none';
        });
      }

      // Select/deselect all invoices
      function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.invoice-checkbox');
        checkboxes.forEach(cb => {
          cb.checked = checkbox.checked;
        });
        updateBulkActions();
      }

      // Update bulk actions visibility
      function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.invoice-checkbox:checked');
        const bulkBar = document.getElementById('bulkActionsBar');
        const count = document.getElementById('selectedCount');

        count.textContent = checkboxes.length;
        bulkBar.classList.toggle('show', checkboxes.length > 0);
      }

      // Add invoice item row
      function addInvoiceItem() {
        const tbody = document.getElementById('invoiceItemsBody');
        const row = document.createElement('tr');
        row.className = 'item-row';
        row.innerHTML = `
                <td>
                    <select class="form-select form-select-sm item-name" name="items[${itemCounter}][item]" onchange="updateItemDescription(this)" required>
                        <option value="">Select Item</option>
                        <option value="rent">Rent</option>
                        <option value="water">Water</option>
                        <option value="electricity">Electricity</option>
                        <option value="security">Security</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="parking">Parking</option>
                        <option value="other">Other</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm item-desc" name="items[${itemCounter}][description]" placeholder="Item description" required>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-qty" name="items[${itemCounter}][quantity]" value="1" min="1" onchange="calculateRowTotal(this)" required>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-price" name="items[${itemCounter}][price]" placeholder="0.00" step="0.01" onchange="calculateRowTotal(this)" required>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-discount" name="items[${itemCounter}][discount]" value="0" min="0" step="0.01" onchange="calculateRowTotal(this)">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-tax" name="items[${itemCounter}][tax]" value="16" min="0" step="0.01" onchange="calculateRowTotal(this)">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm item-amount" name="items[${itemCounter}][amount]" value="0.00" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)" style="padding: 2px 6px;">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
        tbody.appendChild(row);
        itemCounter++;
      }

      // Remove invoice item row
      function removeItem(button) {
        const row = button.closest('tr');
        row.remove();
        calculateInvoiceTotal();
      }

      // Calculate row total
      function calculateRowTotal(input) {
        calculateInvoiceTotal();
      }

      // Calculate invoice total
      function calculateInvoiceTotal() {
        let subtotal = 0;
        let totalDiscount = 0;
        let totalTax = 0;

        const rows = document.querySelectorAll('.item-row');

        rows.forEach(row => {
          const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
          const price = parseFloat(row.querySelector('.item-price').value) || 0;
          const discount = parseFloat(row.querySelector('.item-discount').value) || 0;
          const taxRate = parseFloat(row.querySelector('.item-tax').value) || 0;

          const lineSubtotal = qty * price;
          const lineDiscount = discount;
          const afterDiscount = lineSubtotal - lineDiscount;
          const lineTax = afterDiscount * (taxRate / 100);
          const lineTotal = afterDiscount + lineTax;

          row.querySelector('.item-amount').value = lineTotal.toFixed(2);

          subtotal += lineSubtotal;
          totalDiscount += lineDiscount;
          totalTax += lineTax;
        });

        const total = subtotal - totalDiscount + totalTax;

        document.getElementById('invoiceSubtotal').textContent = 'KES ' + subtotal.toLocaleString('en-US', {
          minimumFractionDigits: 2
        });
        document.getElementById('invoiceDiscount').textContent = 'KES ' + totalDiscount.toLocaleString('en-US', {
          minimumFractionDigits: 2
        });
        document.getElementById('invoiceTax').textContent = 'KES ' + totalTax.toLocaleString('en-US', {
          minimumFractionDigits: 2
        });
        document.getElementById('invoiceTotal').textContent = 'KES ' + total.toLocaleString('en-US', {
          minimumFractionDigits: 2
        });
      }

      // Preview invoice
      function previewInvoice() {
        // Get form data
        const invoiceNumber = document.querySelector('input[name="invoice_number"]').value;
        const issueDate = document.querySelector('input[name="issue_date"]').value;
        const dueDate = document.querySelector('input[name="due_date"]').value;
        const building = document.querySelector('select[name="building"]').selectedOptions[0]?.text || '';
        const unit = document.querySelector('select[name="unit"]').selectedOptions[0]?.text || '';
        const tenant = document.querySelector('select[name="tenant"]').selectedOptions[0]?.text || '';

        // Populate preview
        document.getElementById('preview-invoice-number').textContent = invoiceNumber;
        document.getElementById('preview-issue-date').textContent = new Date(issueDate).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
        document.getElementById('preview-due-date').textContent = new Date(dueDate).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
        document.getElementById('preview-tenant').textContent = tenant;
        document.getElementById('preview-unit').textContent = unit;
        document.getElementById('preview-building').textContent = building;

        // Populate items
        const previewItems = document.getElementById('preview-items');
        previewItems.innerHTML = '';

        const rows = document.querySelectorAll('.item-row');
        rows.forEach(row => {
          const itemName = row.querySelector('.item-name').selectedOptions[0]?.text || '';
          const desc = row.querySelector('.item-desc').value;
          const qty = row.querySelector('.item-qty').value;
          const price = parseFloat(row.querySelector('.item-price').value) || 0;
          const discount = parseFloat(row.querySelector('.item-discount').value) || 0;
          const tax = parseFloat(row.querySelector('.item-tax').value) || 0;
          const amount = row.querySelector('.item-amount').value;

          const tr = document.createElement('tr');
          tr.innerHTML = `
                    <td>${itemName}</td>
                    <td>${desc}</td>
                    <td class="text-end">${qty}</td>
                    <td class="text-end">KES ${price.toFixed(2)}</td>
                    <td class="text-end">KES ${discount.toFixed(2)}</td>
                    <td class="text-end">${tax}%</td>
                    <td class="text-end">KES ${amount}</td>
                `;
          previewItems.appendChild(tr);
        });

        // Populate totals
        document.getElementById('preview-subtotal').textContent = document.getElementById('invoiceSubtotal').textContent;
        document.getElementById('preview-discount').textContent = document.getElementById('invoiceDiscount').textContent;
        document.getElementById('preview-tax').textContent = document.getElementById('invoiceTax').textContent;
        document.getElementById('preview-total').textContent = document.getElementById('invoiceTotal').textContent;

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('invoicePreviewModal'));
        modal.show();
      }

      // Continue editing
      function continueEditing() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('invoicePreviewModal'));
        modal.hide();
      }

      // Save as draft
      function saveAsDraft() {
        alert('Invoice saved as draft!\n\nIn a real application, this would save the invoice with status "Draft".');
      }

      // Confirm and send
      function confirmAndSend() {
        if (confirm('Are you sure you want to send this invoice to the tenant?')) {
          alert('Invoice sent successfully!\n\nIn a real application, this would:\n- Save the invoice\n- Send via email/SMS\n- Update status to "Sent"');

          // Close modals
          const previewModal = bootstrap.Modal.getInstance(document.getElementById('invoicePreviewModal'));
          if (previewModal) previewModal.hide();

          const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('createInvoiceOffcanvas'));
          if (offcanvas) offcanvas.hide();
        }
      }

      // View invoice
      function viewInvoice(invoiceId) {
        alert(`Opening invoice ${invoiceId}...\n\nIn a real application, this would open the invoice details page or PDF preview.`);
      }

      // Delete invoice
      function deleteInvoice(invoiceId) {
        if (confirm(`Are you sure you want to delete invoice ${invoiceId}?\n\nThis action cannot be undone.`)) {
          alert(`Invoice ${invoiceId} deleted!\n\nIn a real application, this would delete the invoice from the database.`);
        }
      }
    </script>

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