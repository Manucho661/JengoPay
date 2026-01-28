<?php
session_start(); 
require_once "../db/connect.php";
// Assuming you have a PDO connection $pdo
if(isset($_GET['tenant_id']) && !empty($_GET['tenant_id'])) {
    $tenant_id = $_GET['tenant_id'];
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                first_name, 
                middle_name, 
                last_name, 
                main_contact, 
                alt_contact, 
                email,
                idMode,
                id_no,
                pass_no,
                leasing_period,
                leasing_start_date,
                leasing_end_date,
                move_in_date,
                move_out_date,
                account_no,
                unit_category,
                id_upload,
                tax_pin_copy,
                rental_agreement,
                income,
                job_title,
                job_location,
                casual_job,
                business_name,
                business_location,
                tenant_status,
                tenant_occupancy_status,
                building_link,
                tenant_reg
            FROM tenants 
            WHERE id = ?
        ");
        
        $stmt->execute([$tenant_id]);
        $tenant_info = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($tenant_info) {
            // Use the fetched data in your form
            $full_name = htmlspecialchars(
                ($tenant_info['first_name'] ?? '') . ' ' . 
                ($tenant_info['middle_name'] ?? '') . ' ' . 
                ($tenant_info['last_name'] ?? '')
            );
            $main_contact = htmlspecialchars($tenant_info['main_contact'] ?? '');
            $alt_contact = htmlspecialchars($tenant_info['alt_contact'] ?? '');
            $email = htmlspecialchars($tenant_info['email'] ?? '');
        } else {
            // Handle no tenant found
            $tenant_info = null;
        }
    } catch (PDOException $e) {
        // Handle database error
        error_log("Database error: " . $e->getMessage());
        $tenant_info = null;
    }
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $pdo->beginTransaction();
    
    try {
        $invoice_no   = $_POST['invoice_no'];
        $tenant_id   = $_POST['tenant_id']; // Make sure this is coming from form
        $receiver     = $_POST['receiver'];
        $phone        = $_POST['phone'];
        $email        = $_POST['email'];
        $invoice_date = $_POST['invoice_date'];
        $due_date     = $_POST['due_date'];
        $notes        = $_POST['notes'];
        $subtotal     = $_POST['subtotalValue'];
        $total_tax    = $_POST['totalTaxValue'];
        $final_total  = $_POST['finalTotalValue'];
        
        // Insert invoice
        $stmt = $pdo->prepare("
            INSERT INTO invoice 
            (invoice_no, tenant_id, receiver, phone, email, invoice_date, due_date, notes, subtotal, taxes, total)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $invoice_no, $tenant_id, $receiver, $phone, $email,
            $invoice_date, $due_date, $notes,
            $subtotal, $total_tax, $final_total
        ]);
        
        $invoice_id = $pdo->lastInsertId();
        
        // Insert invoice items with tenant_id
        $items = json_decode($_POST['invoice_items'], true);
        
        $itemStmt = $pdo->prepare("
            INSERT INTO invoice_items
            (invoice_id, tenant_id, account_code, paid_for, description, unit_price, quantity, tax_type, tax_amount, total_price)
            VALUES (?,?,?,?,?,?,?,?,?,?)
        ");
        
        foreach ($items as $item) {
            // Debug: Check what data you're receiving
            error_log("Item data: " . print_r($item, true));
            
            // Validate account_code
            $account_code = !empty($item['account_code']) ? $item['account_code'] : null;
            
            // If account_code is empty but paid_for is Rent, use 500
            if (empty($account_code) && stripos($item['paid_for'], 'rent') !== false) {
                $account_code = 500; // Rental Income
            }
            // If account_code is empty but paid_for is Water, use 510
            elseif (empty($account_code) && stripos($item['paid_for'], 'water') !== false) {
                $account_code = 510; // Water Charges
            }
            // If account_code is empty but paid_for is Garbage, use 515
            elseif (empty($account_code) && stripos($item['paid_for'], 'garbage') !== false) {
                $account_code = 515; // Garbage Collection
            }
            
            // Make sure tenant_id is included in the execute statement
            $itemStmt->execute([
                $invoice_id,
                $tenant_id, // Add tenant_id here
                $account_code,
                $item['paid_for'] ?? '',
                $item['description'] ?? '',
                $item['unit_price'] ?? 0,
                $item['quantity'] ?? 1,
                $item['tax_type'] ?? 'VAT Inclusive',
                $item['tax_amount'] ?? 0,
                $item['total_price'] ?? 0
            ]);
        }
        
        $pdo->commit();
        
        header("Location: /jengopay/landlord/pages/financials/invoices/invoice.php?success=1");
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        // More detailed error message
        die("Error saving invoice: " . $e->getMessage() . "\n" . 
            "Items data: " . print_r($items, true));
    }
}
?>
<?php
$invoiceSubTotal = 0; // Net (before tax)
$invoiceTaxTotal = 0; // VAT
$invoiceGrandTotal = 0; // Net + Tax
?>

<?php
require_once "../db/connect.php";
$stmt = $pdo->prepare("
    SELECT account_code, account_name
    FROM chart_of_accounts
    WHERE financial_statement = 'Income Statement'
      AND account_type = 'Revenue'
    ORDER BY account_code ASC
");
$stmt->execute();
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php
require_once "../db/connect.php";
include_once '../processes/encrypt_decrypt_function.php';

$tenant_info = [];
$monthly_rent = 0;
$final_bill = 0;
$garbage_data = [];

if(isset($_GET['invoice']) && !empty($_GET['invoice'])) {
    $id = $_GET['invoice'];
    $decrypted_id = encryptor('decrypt', $id);

    if ($decrypted_id !== null && $decrypted_id !== false) {
        try {
            // Fetch tenant info
            $stmt = $pdo->prepare("SELECT * FROM tenants WHERE id = ?");
            $stmt->execute([$decrypted_id]);
            $tenant_info = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            // Fetch tenant's active unit monthly rent dynamically
         // Fetch tenant's active unit monthly rent dynamically
if(!empty($decrypted_id)) {
    // First, get the unit_id from tenancies table
    $unitStmt = $pdo->prepare("
        SELECT t.unit_id, bu.monthly_rent
        FROM tenancies t
        JOIN building_units bu ON t.unit_id = bu.id
        WHERE t.tenant_id = ? AND t.status = 'Active'
        ORDER BY t.id DESC
        LIMIT 1
    ");
    $unitStmt->execute([$decrypted_id]);
    $unitResult = $unitStmt->fetch(PDO::FETCH_ASSOC);
    
    if($unitResult) {
        // Store unit_id and monthly_rent
        $tenant_info['unit_id'] = $unitResult['unit_id'];
        $monthly_rent = floatval($unitResult['monthly_rent']);
        $final_bill = $monthly_rent;
    }
}


// Fetch rental income account from chart_of_accounts
try {
    $chartStmt = $pdo->prepare("SELECT account_name, account_code FROM chart_of_accounts WHERE account_code = 500");
    $chartStmt->execute();
    $rentalAccount = $chartStmt->fetch(PDO::FETCH_ASSOC);
    
    if($rentalAccount) {
        // You can use this information if needed
        $rental_account_name = $rentalAccount['account_name'];
        $rental_account_code = $rentalAccount['account_code'];
    }
} catch (Exception $e) {
    error_log("Error fetching chart of accounts: " . $e->getMessage());
    $rental_account_name = "Rental Income";
    $rental_account_code = 500;
}

            // Fetch garbage/other charges if exists
            try {
                $garbageStmt = $pdo->prepare("SELECT bill, qty, unit_price, subtotal FROM bills WHERE tenant_id = ? AND bill = 'Garbage'");
                $garbageStmt->execute([$decrypted_id]);
                $garbage_data = $garbageStmt->fetch(PDO::FETCH_ASSOC) ?: [];
            } catch(Exception $e) {
                $garbage_data = [];
            }

            // If water bills are stored in the bills table
// Fetch water bill data for current month
try {
    if(isset($tenant_info['unit_id']) && !empty($tenant_info['unit_id'])) {
        // Get current month's water bills
        $waterStmt = $pdo->prepare("
            SELECT 
                b.id AS bill_id,
                b.building_unit_id,
                t.account_no,
                CONCAT(ten.first_name, ' ', ten.last_name) AS tenant_name,
                b.quantity,
                b.unit_price,
                b.sub_total,
                b.created_at,
                MONTH(b.created_at) AS billing_month,
                YEAR(b.created_at) AS billing_year
            FROM bills b
            INNER JOIN tenancies t ON b.building_unit_id = t.unit_id
            INNER JOIN tenants ten ON t.tenant_id = ten.id
            WHERE b.building_unit_id = ?
                AND b.bill_name = 0
                AND MONTH(b.created_at) = MONTH(CURRENT_DATE())
                AND YEAR(b.created_at) = YEAR(CURRENT_DATE())
                AND t.status = 'Active'
            ORDER BY b.created_at DESC
        ");
        $waterStmt->execute([$tenant_info['unit_id']]);
        $water_data = $waterStmt->fetch(PDO::FETCH_ASSOC);
        
        if($water_data) {
            // Process water data for display
            $water_quantity = $water_data['quantity'] ?? 0;
            $water_unit_price = $water_data['unit_price'] ?? 0;
            $water_total = $water_data['sub_total'] ?? 0;
            
            // Store in session or variable for use
            $_SESSION['water_bill_data'] = $water_data;
        } else {
            $water_data = [];
            $water_quantity = 0;
            $water_unit_price = 0;
            $water_total = 0;
        }
    }
} catch(Exception $e) {
    error_log("Error fetching water bill: " . $e->getMessage());
    $water_data = [];
    $water_quantity = 0;
    $water_unit_price = 0;
    $water_total = 0;
}


$recurringBills = [];

if (!empty($tenant_info['unit_id'])) {
    $stmt = $pdo->prepare("
        SELECT 
            unit_id,
            bill_name,
            account_code,
            quantity,
            unit_price,
            subtotal
        FROM recurring_bills
        WHERE unit_id = ?
          AND MONTH(created_at) = MONTH(CURRENT_DATE())
          AND YEAR(created_at) = YEAR(CURRENT_DATE())
    ");
    $stmt->execute([$tenant_info['unit_id']]);
    $recurringBills = $stmt->fetchAll(PDO::FETCH_ASSOC);
}



        } catch(PDOException $e) {
            error_log("Database error: ".$e->getMessage());
            $tenant_info = [];
            $monthly_rent = 0;
        }
    }
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


    <style>
    /*========================== Add Items in the Invoice ====================================*/
.offcanvas-right {
  position: fixed;
  top: 0;
  right: 0;
  width: 400px; /* Adjust width as needed */
  height: 100%;
  background-color: #fff;
  box-shadow: -5px 0 15px rgba(0,0,0,0.1);
  transform: translateX(100%);
  transition: transform 0.3s ease-in-out;
  z-index: 1050; /* Above Bootstrap modals */
  padding: 20px;
  overflow-y: auto; /* Enable scrolling for long forms */
}
.offcanvas-right.show {
  transform: translateX(0);
}
.offcanvas-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
  z-index: 1040;
  opacity: 0;
  transition: opacity 0.3s ease-in-out;
  pointer-events: none; /* Allows clicks through when not visible */
}
.offcanvas-backdrop.show {
  opacity: 1;
  pointer-events: auto; /* Blocks clicks when visible */
}
#closeAddItem{
  background-color: #cc0001;
  color: #fff;
  border: 0;
  border-radius: 3px;
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
        
            <!--begin::Sidebar Wrapper-->
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?> 
           
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main mt-4">
            <div class="content-wrapper">
                <!-- Main content -->
                <?php
include_once '../processes/encrypt_decrypt_function.php';

$tenant_info = [
    'first_name' => '', 'middle_name' => '', 'last_name' => '', 
    'phone' => '', 'alt_phone' => '', 'email' => '',
    'idMode' => '', 'id_no' => '', 'pass_no' => '',
    'leasing_period' => '', 'leasing_start_date' => '', 'leasing_end_date' => '',
    'move_in_date' => '', 'move_out_date' => '', 'account_no' => '',
    'unit_category' => '', 'id_upload' => '', 'tax_pin_copy' => '',
    'rental_agreement' => '', 'income' => '', 'job_title' => '',
    'job_location' => '', 'casual_job' => '', 'business_name' => '',
    'business_location' => '', 'tenant_status' => '', 
    'tenant_occupancy_status' => '', 'building_link' => '', 'tenant_reg' => '',
    'unit_id' => '' // Added unit_id
];

$monthly_rent = 0;
$final_bill = 0;
$garbage_data = [];
$unit_number = ''; // New variable for unit number

// Fetch Tenant Information from the tenants table
if(isset($_GET['invoice']) && !empty($_GET['invoice'])) {
    $id = $_GET['invoice'];
    $decrypted_id = encryptor('decrypt', $id);

    if ($decrypted_id !== null && $decrypted_id !== false) {
        try {
            // First, get tenant info from tenants table
            $tenant = $pdo->prepare("SELECT * FROM tenants WHERE id = ?");
            $tenant->execute([$decrypted_id]);
            $tenant_info = $tenant->fetch(PDO::FETCH_ASSOC);

            if(!$tenant_info) {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Tenant Found',
                        text: 'No tenant data found for the provided ID.'
                    });
                </script>";
                
                // Reset to default empty values
                $tenant_info = [
                    'first_name' => '', 'middle_name' => '', 'last_name' => '', 
                    'phone' => '', 'alt_phone' => '', 'email' => '',
                    'idMode' => '', 'id_no' => '', 'pass_no' => '',
                    'leasing_period' => '', 'leasing_start_date' => '', 'leasing_end_date' => '',
                    'move_in_date' => '', 'move_out_date' => '', 'account_no' => '',
                    'unit_category' => '', 'id_upload' => '', 'tax_pin_copy' => '',
                    'rental_agreement' => '', 'income' => '', 'job_title' => '',
                    'job_location' => '', 'casual_job' => '', 'business_name' => '',
                    'business_location' => '', 'tenant_status' => '', 
                    'tenant_occupancy_status' => '', 'building_link' => '', 'tenant_reg' => '',
                    'unit_id' => ''
                ];
            } else {
                // Merge with defaults to ensure all keys exist
                $default_keys = [
                    'first_name' => '', 'middle_name' => '', 'last_name' => '', 
                    'phone' => '', 'alt_phone' => '', 'email' => '',
                    'idMode' => '', 'id_no' => '', 'pass_no' => '',
                    'leasing_period' => '', 'leasing_start_date' => '', 'leasing_end_date' => '',
                    'move_in_date' => '', 'move_out_date' => '', 'account_no' => '',
                    'unit_category' => '', 'id_upload' => '', 'tax_pin_copy' => '',
                    'rental_agreement' => '', 'income' => '', 'job_title' => '',
                    'job_location' => '', 'casual_job' => '', 'business_name' => '',
                    'business_location' => '', 'tenant_status' => '', 
                    'tenant_occupancy_status' => '', 'building_link' => '', 'tenant_reg' => '',
                    'unit_id' => ''
                ];
                
                $tenant_info = array_merge($default_keys, $tenant_info);
                
                // Now fetch unit_id and monthly_rent from tenancies and building_units tables
                $unitStmt = $pdo->prepare("
                    SELECT t.unit_id, bu.monthly_rent, bu.unit_number
                    FROM tenancies t
                    JOIN building_units bu ON t.unit_id = bu.id
                    WHERE t.tenant_id = ? AND t.status = 'Active'
                    ORDER BY t.id DESC
                    LIMIT 1
                ");
                $unitStmt->execute([$decrypted_id]);
                $unitResult = $unitStmt->fetch(PDO::FETCH_ASSOC);
                
                if($unitResult) {
                    $tenant_info['unit_id'] = $unitResult['unit_id'];
                    $monthly_rent = floatval($unitResult['monthly_rent']);
                    $unit_number = htmlspecialchars($unitResult['unit_number'] ?? '');
                    $final_bill = $monthly_rent;
                }
                
                // FETCH GARBAGE DATA
                try {
                    $garbage_stmt = $pdo->prepare("SELECT bill, qty, unit_price, subtotal FROM tenant_bills WHERE tenant_id = ? AND bill = 'Garbage'");
                    $garbage_stmt->execute([$decrypted_id]);
                    $garbage_data = $garbage_stmt->fetch(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $garbage_data = [];
                }
            }
        } catch (PDOException $e) {
            error_log("Database error fetching tenant info: " . $e->getMessage());
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Database Error',
                    text: 'Could not fetch tenant data. Please try again.'
                });
            </script>";
            
            $tenant_info = [
                'first_name' => '', 'middle_name' => '', 'last_name' => '', 
                'phone' => '', 'alt_phone' => '', 'email' => '',
                'idMode' => '', 'id_no' => '', 'pass_no' => '',
                'leasing_period' => '', 'leasing_start_date' => '', 'leasing_end_date' => '',
                'move_in_date' => '', 'move_out_date' => '', 'account_no' => '',
                'unit_category' => '', 'id_upload' => '', 'tax_pin_copy' => '',
                'rental_agreement' => '', 'income' => '', 'job_title' => '',
                'job_location' => '', 'casual_job' => '', 'business_name' => '',
                'business_location' => '', 'tenant_status' => '', 
                'tenant_occupancy_status' => '', 'building_link' => '', 'tenant_reg' => '',
                'unit_id' => ''
            ];
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid ID',
                text: 'The provided tenant ID is invalid.'
            });
        </script>";
        
        $tenant_info = [
            'first_name' => '', 'middle_name' => '', 'last_name' => '', 
            'phone' => '', 'alt_phone' => '', 'email' => '',
            'idMode' => '', 'id_no' => '', 'pass_no' => '',
            'leasing_period' => '', 'leasing_start_date' => '', 'leasing_end_date' => '',
            'move_in_date' => '', 'move_out_date' => '', 'account_no' => '',
            'unit_category' => '', 'id_upload' => '', 'tax_pin_copy' => '',
            'rental_agreement' => '', 'income' => '', 'job_title' => '',
            'job_location' => '', 'casual_job' => '', 'business_name' => '',
            'business_location' => '', 'tenant_status' => '', 
            'tenant_occupancy_status' => '', 'building_link' => '', 'tenant_reg' => '',
            'unit_id' => ''
        ];
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'No ID Provided',
            text: 'Please select a tenant to create an invoice.'
        });
    </script>";
}
?>
          
              
<div class="card shadow">
<div class="card-header" style="background-color: #00192D; color: #fff;">
    <b>Create Invoice for Unit <?= $unit_number ?: 'N/A'; ?> - <?= htmlspecialchars(($tenant_info['first_name'] ?? '') . ' ' . ($tenant_info['middle_name'] ?? '') . ' ' . ($tenant_info['last_name'] ?? ''));?></b>
</div>
            <form id="invoiceForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="tenant_id" value="<?= htmlspecialchars($decrypted_id ?? ''); ?>">

            <div class="card-body">
                    <!-- Tenant Info Section -->
                    <div class="row">
    <div class="col-md-3">
        <div class="form-group mb-3">
            <label>Invoice Number:</label>
            <input type="text" id="invoiceNumber" name="invoice_no" required class="form-control" readonly>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group mb-3">
            <label>Invoice To:</label>
            <input type="text" 
                   name="receiver" 
                   required 
                   class="form-control" 
                   value="<?= htmlspecialchars(($tenant_info['first_name'] ?? '') . ' ' . ($tenant_info['middle_name'] ?? '') . ' ' . ($tenant_info['last_name'] ?? '')); ?>" 
                   readonly>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group mb-3">
            <label>Main Contact</label>
            <input class="form-control" 
                   value="<?= htmlspecialchars($tenant_info['phone'] ?? ''); ?>" 
                   readonly 
                   name="phone">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group mb-3">
            <label>Alternative Contact</label>
            <input class="form-control" 
                   value="<?= htmlspecialchars($tenant_info['alt_phone'] ?? ''); ?>" 
                   readonly 
                   name="alt_phone">
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label>Email</label>
        <input class="form-control" 
               value="<?= htmlspecialchars($tenant_info['email'] ?? ''); ?>" 
               readonly 
               name="email">
    </div>

    <div class="col-md-4">
        <label>Invoice Date</label>
        <input type="date" id="invoiceDate" name="invoice_date" required class="form-control">
    </div>

    <div class="col-md-4">
        <label>Date Due</label>
        <input type="date" id="dateDue" name="due_date" required class="form-control" readonly>
    </div>
</div>


                    <hr>
                    <!-- <input type="hidden" name="paymentStatus" value="Pending">
                    <input type="hidden" name="monthly_rent" id="monthlyRent" value="<?= htmlspecialchars($monthly_rent); ?>">
                    <input type="hidden" name="final_bill" id="finalBill" value="<?= htmlspecialchars($final_bill); ?>"> -->

                                       <!-- Invoice Items Table -->
                                       <h5 class="mb-3">Invoice Items</h5>
                                       <table id="invoiceTable" class="table table-bordered table-striped shadow">
    <thead class="table-dark">
    <tr>
    <th>Paid For</th>
    <th>Account Code</th> <!-- This should be column 2 -->
    <th>Description</th>  <!-- This should be column 3 -->
    <th>Unit Price</th>   <!-- This should be column 4 -->
    <th>Quantity</th>     <!-- This should be column 5 -->
    <th>Taxation</th>     <!-- This should be column 6 -->
    <th>Tax Amount</th>   <!-- This should be column 7 -->
    <th>Total Price</th>  <!-- This should be column 8 -->
    <th>Action</th>       <!-- This should be column 9 -->
</tr>
    </thead>
    <tbody id="invoiceBody">
        <?php
        // Display Rent row automatically since it's in monthly_rent
        if ($monthly_rent > 0) {
            // Get account code for Rent (500)
            $rentAccountCode = 500;
            $rentAccountName = "Rental Income";
            $rentUnitPrice = $monthly_rent;
            $rentQuantity = 1;
            $rentTaxType = 'VAT Inclusive';
            $rentTotalPrice = $monthly_rent;
            $rentTaxAmount = round($rentTotalPrice * (16/116), 2);
            $rentNetPrice = $rentTotalPrice - $rentTaxAmount;
            
            echo "<tr id='rowRent'>";
            echo "<td>" . htmlspecialchars($rentAccountName) . "</td>";
            echo "<td class='account-code'>" . htmlspecialchars($rentAccountCode) . "</td>";
            echo "<td>Monthly Rental Payment</td>";
            echo "<td class='unit-price'>" . number_format($rentNetPrice, 2) . "</td>";
            echo "<td class='quantity'>" . $rentQuantity . "</td>";
            echo "<td class='tax-type'>" . $rentTaxType . "</td>";
            echo "<td class='tax-amount'>" . number_format($rentTaxAmount, 2) . "</td>";
            echo "<td class='total-price'>" . number_format($rentTotalPrice, 2) . "</td>";
            echo "<td>";
            echo "<button type='button' class='btn btn-sm btn-danger' onclick='removeRow(\"rowRent\")'>";
            echo "<i class='fa fa-trash'></i>";
            echo "</button>";
            echo "</td>";
            echo "</tr>";
        }
        
        // For water bill (use account code 510)
        if (!empty($tenant_info['unit_id'])) {
            try {
                $waterStmt = $pdo->prepare("
                    SELECT 
                        SUM(quantity)   AS total_qty,
                        SUM(sub_total)  AS total_amount
                    FROM bills
                    WHERE unit_id = ?
                      AND bill_name = 'Water'
                      AND MONTH(created_at) = MONTH(CURRENT_DATE())
                      AND YEAR(created_at) = YEAR(CURRENT_DATE())
                ");
                $waterStmt->execute([$tenant_info['unit_id']]);
                $water = $waterStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($water && $water['total_amount'] > 0) {
                    $waterAccountCode = 510;
                    $waterAccountName = "Water Charges (Revenue)";
                    $qty   = (float) $water['total_qty'];
                    $total = (float) $water['total_amount'];
                    $taxAmount = round($total * (16 / 116), 2);
                    $netTotal  = $total - $taxAmount;
                    $unitPrice = $qty > 0 ? $netTotal / $qty : 0;
                    ?>
                    <tr id="rowWater">
                        <td><?= htmlspecialchars($waterAccountName) ?></td>
                        <td class="account-code"><?= htmlspecialchars($waterAccountCode) ?></td>
                        <td>Water Consumption (Monthly)</td>
                        <td class="unit-price"><?= number_format($unitPrice, 2) ?></td>
                        <td class="quantity"><?= $qty ?></td>
                        <td class="tax-type">VAT Inclusive</td>
                        <td class="tax-amount"><?= number_format($taxAmount, 2) ?></td>
                        <td class="total-price"><?= number_format($total, 2) ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="removeRow('rowWater')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
            } catch (PDOException $e) {
                error_log("Water bill fetch error: " . $e->getMessage());
            }
        }
        
        // For garbage bills (use account code 515)
        if (!empty($recurringBills)) {
            foreach ($recurringBills as $index => $bill) {
                $garbageAccountCode = 515;
                $garbageAccountName = "Garbage Collection Fees (Revenue)";
                $qty        = (int) ($bill['quantity'] ?? 1);
                $total      = (float) ($bill['subtotal'] ?? 0);
                $billName   = $bill['bill_name'];
                $rowId      = 'rowRecurring' . $index;
                
                if ($total <= 0) continue;
                
                $taxAmount = round($total * (16 / 116), 2);
                $netTotal  = $total - $taxAmount;
                $unitPrice = $qty > 0 ? $netTotal / $qty : 0;
                ?>
                <tr id="<?= $rowId ?>">
                    <td><?= htmlspecialchars($garbageAccountName) ?></td>
                    <td class="account-code"><?= htmlspecialchars($garbageAccountCode) ?></td>
                    <td><?= htmlspecialchars($billName) ?></td>
                    <td class="unit-price"><?= number_format($unitPrice, 2) ?></td>
                    <td class="quantity"><?= $qty ?></td>
                    <td class="tax-type">VAT Inclusive</td>
                    <td class="tax-amount"><?= number_format($taxAmount, 2) ?></td>
                    <td class="total-price"><?= number_format($total, 2) ?></td>
                    <td>
                        <button type="button"
                                class="btn btn-sm btn-danger"
                                onclick="removeRow('<?= $rowId ?>')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
                            <tr><td colspan="6" class="text-end">Subtotal:</td><td id="subtotal" class="text-end">0.00</td><td></td></tr>
                            <tr><td colspan="6" class="text-end">Total Tax:</td><td id="totalTax" class="text-end">0.00</td><td></td></tr>
                            <tr><td colspan="6" class="text-end"><strong>Final Total:</strong></td><td id="finalTotal" class="text-end">0.00</td><td></td></tr>
                        </tfoot>
</table>
                    <hr>
                    <!-- Changed addRow() to open the drawer -->
                    <button type="button" onclick="openAddItemDrawer()" class="btn btn-sm shadow text-white" style="background-color:#00192D;">
                        <i class="fa fa-plus"></i> Add Item
                    </button>
                    <hr>

                    <!-- Notes & Attachment Section -->
                    <div class="row mb-3 shadow p-3 rounded">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea name="notes" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Attachment</label>
                                <input type="file" name="attachment" accept=".pdf,.jpg,.png,.docx" class="form-control">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="invoice_items" id="invoiceItems">
                    <input type="hidden" name="subtotalValue" id="subtotalValue">
                    <input type="hidden" name="totalTaxValue" id="totalTaxValue">
                    <input type="hidden" name="finalTotalValue" id="finalTotalValue">
                </div>
                <div class="card-footer text-right">
                    <button type="submit" onclick="return prepareInvoiceData()" name="submit" class="btn btn-sm shadow" style="border:1px solid #00192D; color:#00192D;"><i class="fa fa-check"></i> Submit Invoice</button>
                </div>
            </form>
          </div>
        </div>
        <!-- Offcanvas (Side Panel) for Add Item -->
        <div class="offcanvas-right shadow" id="addItemDrawer">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Add New Invoice Item</h5>
            <button type="button" class="btn-close shadow" aria-label="Close" onclick="closeAddItemDrawer()" id="closeAddItem"><i class="fa fa-close"></i></button>
        </div>
            <form id="addItemForm">
                <div class="mb-3">
                    <label for="drawerItemName" class="form-label">Paid For <span class="text-danger">*</span></label>
                    <select class="form-control" id="drawerItemName" onchange="checkDrawerOthersInput(this)" required>
    <option value="">-- Select Item --</option>
    <?php foreach ($accounts as $account): ?>
        <option value="<?= htmlspecialchars($account['account_name']) ?>" 
                data-account-code="<?= htmlspecialchars($account['account_code']) ?>">
            <?= htmlspecialchars($account['account_name']) ?> (<?= htmlspecialchars($account['account_code']) ?>)
        </option>
    <?php endforeach; ?>
</select>

                    <input type="text" class="form-control mt-2 d-none" id="drawerOtherInput" placeholder="Please specify">
                </div>
                <div class="mb-3">
                    <label for="drawerDescription" class="form-label">Description</label>
                    <input type="text" class="form-control" id="drawerDescription">
                </div>
                <div class="mb-3">
                    <label for="drawerUnitPrice" class="form-label">Unit Price <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="drawerUnitPrice" step="0.01" value="0" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="drawerQuantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="drawerQuantity" value="1" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="drawerTaxType" class="form-label">Taxation</label>
                    <select class="form-control" id="drawerTaxType">
                        <option value="VAT Inclusive">VAT 16% Inclusive</option>
                        <option value="VAT Exclusive">VAT 16% Exclusive</option>
                        <option value="Zero Rated">Zero Rated</option>
                        <option value="Exempted">Exempted</option>
                    </select>
                </div> <hr>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-sm shadow text-white" style="background-color:#00192D;"><i class="fa fa-plus"></i> Add Item</button>
                    <button type="button" class="btn btn-sm text-white shadow" onclick="closeAddItemDrawer()" style="background-color:#cc0001;"><i class="fa fa-close"></i> Cancel</button>
                </div>
            </form>
        </div>
        <div class="offcanvas-backdrop" id="addItemDrawerBackdrop" onclick="closeAddItemDrawer()"></div>
                                                
            <!-- /.content -->
            <!-- Help Pop Up Form -->
        
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
      

    </div>
    <!-- ./wrapper -->
    <!-- Required Scripts -->
    <?php include_once '../includes/required_scripts.php';?>
    <!-- Meter Readings JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate initial totals based on database data
        calculateTotals();
        
        // Set today's date as invoice date and due date (30 days from now)
        const today = new Date();
        const dueDate = new Date();
        dueDate.setDate(today.getDate() + 30);
        
        document.getElementById('invoiceDate').valueAsDate = today;
        document.getElementById('dateDue').valueAsDate = dueDate;
        
        // Generate invoice number
        const invoiceNumber = 'INV-' + today.getFullYear() + '-' + 
                             String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                             Math.floor(1000 + Math.random() * 9000);
        document.getElementById('invoiceNumber').value = invoiceNumber;
    });

    function calculateTotals() {
        let subtotal = 0;
        let totalTax = 0;
        
        // Loop through all rows in the invoice body
        const rows = document.querySelectorAll('#invoiceBody tr');
        rows.forEach(row => {
            const totalPriceCell = row.querySelector('.total-price');
            const taxAmountCell = row.querySelector('.tax-amount');
            
            if (totalPriceCell && taxAmountCell) {
                const totalPrice = parseFloat(totalPriceCell.textContent.replace(/,/g, '')) || 0;
                const taxAmount = parseFloat(taxAmountCell.textContent.replace(/,/g, '')) || 0;
                
                subtotal += totalPrice;
                totalTax += taxAmount;
            }
        });
        
        // Update footer totals
        document.getElementById('subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('totalTax').textContent = totalTax.toFixed(2);
        document.getElementById('finalTotal').textContent = (subtotal + totalTax).toFixed(2);
        
        // Update hidden fields
        document.getElementById('subtotalValue').value = subtotal.toFixed(2);
        document.getElementById('totalTaxValue').value = totalTax.toFixed(2);
        document.getElementById('finalTotalValue').value = (subtotal + totalTax).toFixed(2);
    }

    function removeRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
            calculateTotals();
        }
    }
    function prepareInvoiceData() {
    const items = [];
    const rows = document.querySelectorAll('#invoiceBody tr');
    
    rows.forEach((row) => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 8) {
            // Get account_code - from column 2 (index 1)
            const account_code = cells[1].textContent.trim();
            
            const item = {
                paid_for: cells[0].textContent.trim(),
                account_code: account_code,
                description: cells[2] ? cells[2].textContent.trim() : '',
                unit_price: parseFloat(cells[3] ? cells[3].textContent.replace(/,/g, '') : 0) || 0,
                quantity: parseInt(cells[4] ? cells[4].textContent : 1) || 1,
                tax_type: cells[5] ? cells[5].textContent.trim() : 'VAT Inclusive',
                tax_amount: parseFloat(cells[6] ? cells[6].textContent.replace(/,/g, '') : 0) || 0,
                total_price: parseFloat(cells[7] ? cells[7].textContent.replace(/,/g, '') : 0) || 0
            };
            items.push(item);
        }
    });
    
    if (items.length === 0) {
        alert("No items to invoice!");
        return false;
    }
    
    document.getElementById('invoiceItems').value = JSON.stringify(items);
    calculateTotals();
    
    return true;
}
    // Functions for the add item drawer
    function openAddItemDrawer() {
        document.getElementById('addItemDrawer').classList.add('show');
        document.getElementById('addItemDrawerBackdrop').classList.add('show');
    }

    function closeAddItemDrawer() {
        document.getElementById('addItemDrawer').classList.remove('show');
        document.getElementById('addItemDrawerBackdrop').classList.remove('show');
        document.getElementById('addItemForm').reset();
        document.getElementById('drawerOtherInput').classList.add('d-none');
    }

    // Handle add item form submission
    document.getElementById('addItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let itemName = document.getElementById('drawerItemName').value;
        if (itemName === 'Other') {
            itemName = document.getElementById('drawerOtherInput').value.trim();
            if (!itemName) {
                alert('Please specify the item name');
                return;
            }
        }
        
        const description = document.getElementById('drawerDescription').value || itemName;
        const unitPrice = parseFloat(document.getElementById('drawerUnitPrice').value) || 0;
        const quantity = parseInt(document.getElementById('drawerQuantity').value) || 1;
        const taxType = document.getElementById('drawerTaxType').value;
        
        // Calculate tax amount
        const totalPrice = unitPrice * quantity;
        let taxAmount = 0;
        if (taxType === 'VAT Inclusive') {
            taxAmount = totalPrice * 0.16;
        } else if (taxType === 'VAT Exclusive') {
            taxAmount = totalPrice * 0.16;
        }
        
        // Add row to table
        const rowId = 'row' + Date.now(); // Unique ID
        const newRow = document.createElement('tr');
        newRow.id = rowId;
        newRow.innerHTML = `
            <td>${itemName}</td>
            <td>${description}</td>
            <td class="unit-price">${unitPrice.toFixed(2)}</td>
            <td class="quantity">${quantity}</td>
            <td class="tax-type">${taxType}</td>
            <td class="tax-amount">${taxAmount.toFixed(2)}</td>
            <td class="total-price">${totalPrice.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow('${rowId}')">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        
        document.getElementById('invoiceBody').appendChild(newRow);
        
        // Recalculate totals
        calculateTotals();
        
        // Close drawer and reset form
        closeAddItemDrawer();
    });

    function checkDrawerOthersInput(select) {
        const otherInput = document.getElementById('drawerOtherInput');
        if (select.value === 'Other') {
            otherInput.classList.remove('d-none');
            otherInput.required = true;
        } else {
            otherInput.classList.add('d-none');
            otherInput.required = false;
        }
    }
    </script>
    
    <!-- <script>
    function calculateRowTotal(row) {
        let unitPrice = parseFloat($(row).find('.unit-price').text()) || 0;
        let quantity = parseFloat($(row).find('.quantity').text()) || 0;
        let taxType = $(row).find('.tax-type').text().trim();
        
        let netTotal = unitPrice * quantity;
        let taxAmount = 0;
        let totalPrice = 0;
        
        if (taxType === 'VAT Inclusive') {
            // For VAT Inclusive: total = netTotal * 1.16
            totalPrice = netTotal * 1.16;
            taxAmount = totalPrice * (16/116);
        } else if (taxType === 'VAT Exclusive') {
            // For VAT Exclusive: tax = netTotal * 0.16, total = netTotal + tax
            taxAmount = netTotal * 0.16;
            totalPrice = netTotal + taxAmount;
        } else {
            // No tax
            totalPrice = netTotal;
            taxAmount = 0;
        }
        
        // Update the row
        $(row).find('.tax-amount').text(taxAmount.toFixed(2));
        $(row).find('.total-price').text(totalPrice.toFixed(2));
        
        return { netTotal, taxAmount, totalPrice };
    }

    function updateTotals() {
        let subtotal = 0;
        let totalTax = 0;
        let finalTotal = 0;
        
        $('#invoiceBody tr').each(function() {
            let rowTotal = parseFloat($(this).find('.total-price').text()) || 0;
            let rowTax = parseFloat($(this).find('.tax-amount').text()) || 0;
            
            finalTotal += rowTotal;
            totalTax += rowTax;
            subtotal = finalTotal - totalTax;
        });
        
        $('#subtotal').text(subtotal.toFixed(2));
        $('#totalTax').text(totalTax.toFixed(2));
        $('#finalTotal').text(finalTotal.toFixed(2));
    }
    </script> -->
<script>
function calculateInvoiceTotals() {
    let subtotal = 0;
    let totalTax = 0;
    let finalTotal = 0;

    document.querySelectorAll("#invoiceBody tr").forEach(row => {
        let unitPrice = parseFloat(row.querySelector(".unit-price")?.innerText.replace(/,/g, "")) || 0;
        let quantity  = parseFloat(row.querySelector(".quantity")?.innerText.replace(/,/g, "")) || 0;
        let taxAmount = parseFloat(row.querySelector(".tax-amount")?.innerText.replace(/,/g, "")) || 0;
        let totalPrice= parseFloat(row.querySelector(".total-price")?.innerText.replace(/,/g, "")) || 0;

        subtotal += unitPrice * quantity;
        totalTax += taxAmount;
        finalTotal += totalPrice;
    });

    document.getElementById("subtotal").innerText = subtotal.toFixed(2);
    document.getElementById("totalTax").innerText = totalTax.toFixed(2);
    document.getElementById("finalTotal").innerText = finalTotal.toFixed(2);
}

// Remove row and recalc
function removeRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) row.remove();
    calculateInvoiceTotals();
}

// Run on page load
document.addEventListener("DOMContentLoaded", calculateInvoiceTotals);
</script>

<script>
function prepareInvoiceData() {
    let items = [];

    document.querySelectorAll("#invoiceBody tr").forEach(row => {
        let item = {
            paid_for: row.children[0].innerText.trim(),
            description: row.children[1].innerText.trim(),
            unit_price: row.querySelector(".unit-price").innerText.replace(/,/g,''),
            quantity: row.querySelector(".quantity").innerText,
            tax_type: row.querySelector(".tax-type").innerText,
            tax_amount: row.querySelector(".tax-amount").innerText.replace(/,/g,''),
            total_price: row.querySelector(".total-price").innerText.replace(/,/g,'')
        };
        items.push(item);
    });

    if (items.length === 0) {
        alert("Invoice must have at least one item");
        return false;
    }

    document.getElementById("invoiceItems").value = JSON.stringify(items);

    document.getElementById("subtotalValue").value = document.getElementById("subtotal").innerText;
    document.getElementById("totalTaxValue").value = document.getElementById("totalTax").innerText;
    document.getElementById("finalTotalValue").value = document.getElementById("finalTotal").innerText;

    return true; // allow submit
}
</script>

    <!-- Main Js File -->
    <script src="../../js/adminlte.js"></script>
    <script src="../js/main.js"></script>
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