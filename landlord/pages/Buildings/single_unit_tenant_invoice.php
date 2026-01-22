<?php 
session_start();
require_once "../db/connect.php";
// include_once 'includes/lower_right_popup_form.php';

// Initialize message variables to avoid undefined variable errors
$successMessage = $successMessage ?? null;
$errorMessage = $errorMessage ?? null;

// Handle "missing first name" validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['first_name'])) {
        $errorMessage = "Missing Information: Please enter your First Name.";
    }
}
?>

<?php 
require_once "../db/connect.php";

// First, let's include the encrypt_decrypt_function.php from the correct path
// Based on your code structure, it should be in the processes folder
$encrypt_file_path = __DIR__ . '/../processes/encrypt_decrypt_function.php';

if (file_exists($encrypt_file_path)) {
    require_once $encrypt_file_path;
} else {
    // If not found, try the absolute path
    $encrypt_file_path = $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/processes/encrypt_decrypt_function.php';
    if (file_exists($encrypt_file_path)) {
        require_once $encrypt_file_path;
    } else {
        // Last try - relative path
        $encrypt_file_path = 'processes/encrypt_decrypt_function.php';
        if (file_exists($encrypt_file_path)) {
            require_once $encrypt_file_path;
        } else {
            die("Error: encrypt_decrypt_function.php not found. Please check the file path.");
        }
    }
}

// Now the encryptor function should be available
// Initialize variables
$tenant_info = [];
$monthly_rent = 0;
$unit_number = '';
$unit_id = '';
$building_name = '';

// Fetch Tenant Information and their unit's monthly rent
if(isset($_GET['invoice']) && !empty($_GET['invoice'])) {
    $id = $_GET['invoice'];
    
    // Debug: Check what we received
    error_log("Received invoice ID: " . $id);
    
    try {
        // Decrypt the ID
        $decrypted_id = encryptor('decrypt', $id);
        
        // Debug: Check decrypted ID
        error_log("Decrypted ID: " . $decrypted_id);
        
        if ($decrypted_id !== false && !empty($decrypted_id)) {
            // Query to get tenant info with their unit details including monthly_rent
            $sql = "
                SELECT 
                    t.*,
                    bu.unit_number,
                    bu.monthly_rent,
                    bu.id as unit_id,
                    b.building_name
                FROM tenants t
                LEFT JOIN building_units bu ON t.unit_id = bu.id
                LEFT JOIN buildings b ON bu.building_id = b.id
                WHERE t.id = ?
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$decrypted_id]);
            $tenant_info = $stmt->fetch(PDO::FETCH_ASSOC);

            if($tenant_info) {
                // Get monthly rent from the building_units table
                $monthly_rent = floatval($tenant_info['monthly_rent'] ?? 0);
                $unit_number = $tenant_info['unit_number'] ?? 'N/A';
                $unit_id = $tenant_info['unit_id'] ?? '';
                $building_name = $tenant_info['building_name'] ?? '';
                
                // Debug information
                error_log("Tenant found: " . $tenant_info['first_name'] . " " . $tenant_info['last_name']);
                error_log("Monthly Rent: " . $monthly_rent);
                error_log("Unit Number: " . $unit_number);
                error_log("Unit ID: " . $unit_id);
                
                if ($monthly_rent <= 0) {
                    // Try alternative method to get rent
                    if (!empty($unit_id)) {
                        $rent_stmt = $pdo->prepare("
                            SELECT monthly_rent 
                            FROM building_units 
                            WHERE id = ?
                        ");
                        $rent_stmt->execute([$unit_id]);
                        $rent_result = $rent_stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($rent_result && !empty($rent_result['monthly_rent'])) {
                            $monthly_rent = floatval($rent_result['monthly_rent']);
                            error_log("Got rent via unit_id: " . $monthly_rent);
                        }
                    }
                    
                    // If still 0, try by unit_number
                    if ($monthly_rent <= 0 && !empty($unit_number)) {
                        $rent_stmt = $pdo->prepare("
                            SELECT monthly_rent 
                            FROM building_units 
                            WHERE unit_number = ?
                            ORDER BY id DESC 
                            LIMIT 1
                        ");
                        $rent_stmt->execute([$unit_number]);
                        $rent_result = $rent_stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($rent_result && !empty($rent_result['monthly_rent'])) {
                            $monthly_rent = floatval($rent_result['monthly_rent']);
                            error_log("Got rent via unit_number: " . $monthly_rent);
                        }
                    }
                    
                    if ($monthly_rent <= 0) {
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'No Rent Set',
                                text: 'Monthly rent is not set for this unit. Please check unit settings.',
                                confirmButtonColor: '#cc0001'
                            });
                        </script>";
                    }
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Tenant Found',
                        text: 'No tenant data found for the provided ID.',
                        confirmButtonColor: '#cc0001'
                    });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid ID',
                    text: 'The provided invoice ID could not be decrypted.',
                    confirmButtonColor: '#cc0001'
                });
            </script>";
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Database Error',
                text: 'Could not fetch tenant data. Please try again.',
                confirmButtonColor: '#cc0001'
            });
        </script>";
    } catch (Exception $e) {
        error_log("Encryption error: " . $e->getMessage());
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Encryption Error',
                text: 'Error processing the invoice ID.',
                confirmButtonColor: '#cc0001'
            });
        </script>";
    }
} else {
    // No invoice parameter provided
    echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'No ID Provided',
            text: 'Please select a tenant to create an invoice.',
            confirmButtonColor: '#cc0001'
        });
    </script>";
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
                    </span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div> <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?> </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        
        <!--begin::App Main-->
        <main class="app-main mt-4">
            <div class="content-wrapper">
                <!-- Main content -->
                <?php
                include_once '../processes/encrypt_decrypt_function.php';

                // Initialize variables
                $tenant_info = [];
                $monthly_rent = 0;
                $unit_number = '';
                $unit_id = '';
                $building_name = '';

                // Fetch Tenant Information and their unit's monthly rent
                if(isset($_GET['invoice']) && !empty($_GET['invoice'])) {
                    $id = $_GET['invoice'];
                    $decrypted_id = encryptor('decrypt', $id);

                    if ($decrypted_id !== null && $decrypted_id !== false) {
                        try {
                            // Query to get tenant info with their unit details including monthly_rent
                            $sql = "
                                SELECT 
                                    t.*,
                                    bu.unit_number,
                                    bu.monthly_rent,
                                    bu.id as unit_id,
                                    b.building_name
                                FROM tenants t
                                LEFT JOIN building_units bu ON t.unit_id = bu.id
                                LEFT JOIN buildings b ON bu.building_id = b.id
                                WHERE t.id = ?
                            ";
                            
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$decrypted_id]);
                            $tenant_info = $stmt->fetch(PDO::FETCH_ASSOC);

                            if($tenant_info) {
                                // Get monthly rent from the building_units table
                                $monthly_rent = floatval($tenant_info['monthly_rent'] ?? 0);
                                $unit_number = $tenant_info['unit_number'] ?? 'N/A';
                                $unit_id = $tenant_info['unit_id'] ?? '';
                                $building_name = $tenant_info['building_name'] ?? '';
                                
                                // Debug information (you can remove this in production)
                                // echo "<pre>Tenant Info: " . print_r($tenant_info, true) . "</pre>";
                                // echo "<pre>Monthly Rent: " . $monthly_rent . "</pre>";
                                // echo "<pre>Unit Number: " . $unit_number . "</pre>";
                                
                                if ($monthly_rent <= 0) {
                                    echo "<script>
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'No Rent Set',
                                            text: 'Monthly rent is not set for this unit. Please set it in the unit management.',
                                            confirmButtonColor: '#cc0001'
                                        });
                                    </script>";
                                }
                            } else {
                                echo "<script>
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'No Tenant Found',
                                        text: 'No tenant data found for the provided ID.',
                                        confirmButtonColor: '#cc0001'
                                    });
                                </script>";
                            }
                        } catch (PDOException $e) {
                            error_log("Database error fetching tenant info: " . $e->getMessage());
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Database Error',
                                    text: 'Could not fetch tenant data. Please try again.',
                                    confirmButtonColor: '#cc0001'
                                });
                            </script>";
                        }
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid ID',
                                text: 'The provided tenant ID is invalid.',
                                confirmButtonColor: '#cc0001'
                            });
                        </script>";
                    }
                } else {
                    // No invoice parameter provided
                    echo "<script>
                        Swal.fire({
                            icon: 'info',
                            title: 'No ID Provided',
                            text: 'Please select a tenant to create an invoice.',
                            confirmButtonColor: '#cc0001'
                        });
                    </script>";
                }
                ?>
              
                <div class="card shadow">
                    <div class="card-header" style="background-color: #00192D; color: #fff;">
                        <b>Create Invoice for Unit <?= htmlspecialchars($unit_number); ?> - <?= htmlspecialchars(($tenant_info['first_name'] ?? '') . ' ' . ($tenant_info['last_name'] ?? '')); ?></b>
                    </div>
                    <form id="invoiceForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" autocomplete="off">
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
                                               value="<?= htmlspecialchars(($tenant_info['first_name'] ?? '') . ' ' . ($tenant_info['last_name'] ?? '')); ?>" 
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
                            <input type="hidden" name="paymentStatus" value="Pending">
                            <input type="hidden" name="monthly_rent" id="monthlyRent" value="<?= htmlspecialchars($monthly_rent); ?>">
                            <input type="hidden" name="unit_id" value="<?= htmlspecialchars($unit_id); ?>">
                            <input type="hidden" name="tenant_id" value="<?= htmlspecialchars($decrypted_id ?? ''); ?>">

                            <!-- Invoice Items Table -->
                            <h5 class="mb-3">Invoice Items</h5>
                            <table id="invoiceTable" class="table table-bordered table-striped shadow">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Paid For</th>
                                        <th>Description</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Taxation</th>
                                        <th>Tax Amount</th>
                                        <th>Total Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="invoiceBody">
                                    <?php
                                    // Display Rent row automatically from monthly_rent
                                    if ($monthly_rent > 0) {
                                        // Calculate VAT (16% VAT Inclusive)
                                        $vatRate = 0.16;
                                        $totalPrice = $monthly_rent;
                                        $taxAmount = $totalPrice * ($vatRate / (1 + $vatRate));
                                        $netPrice = $totalPrice - $taxAmount;
                                        $quantity = 1;
                                        $taxType = 'VAT Inclusive';
                                        
                                        echo "<tr id='rowRent'>";
                                        echo "<td>Rent</td>";
                                        echo "<td>Monthly Rental Payment for Unit " . htmlspecialchars($unit_number) . " - " . date('F Y') . "</td>";
                                        echo "<td class='unit-price'>" . number_format($netPrice, 2) . "</td>";
                                        echo "<td class='quantity'>" . $quantity . "</td>";
                                        echo "<td class='tax-type'>" . $taxType . "</td>";
                                        echo "<td class='tax-amount'>" . number_format($taxAmount, 2) . "</td>";
                                        echo "<td class='total-price'>" . number_format($totalPrice, 2) . "</td>";
                                        echo "<td>";
                                        echo "<button type='button' class='btn btn-sm btn-danger' onclick='removeRow(\"rowRent\")'>";
                                        echo "<i class='fa fa-trash'></i>";
                                        echo "</button>";
                                        echo "</td>";
                                        echo "</tr>";
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
                            <button type="submit" onclick="return prepareInvoiceData()" name="submit" class="btn btn-sm shadow" style="border:1px solid #00192D; color:#00192D;">
                                <i class="fa fa-check"></i> Submit Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Offcanvas (Side Panel) for Add Item -->
            <div class="offcanvas-right shadow" id="addItemDrawer">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Add New Invoice Item</h5>
                    <button type="button" class="btn-close shadow" aria-label="Close" onclick="closeAddItemDrawer()" id="closeAddItem">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <form id="addItemForm">
                    <div class="mb-3">
                        <label for="drawerItemName" class="form-label">Paid For <span class="text-danger">*</span></label>
                        <select class="form-control" id="drawerItemName" onchange="checkDrawerOthersInput(this)" required>
                            <option value="Rent">Rent</option>
                            <option value="Water">Water</option>
                            <option value="Garbage">Garbage</option>
                            <option value="Electricity">Electricity</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Parking">Parking</option>
                            <option value="Internet">Internet</option>
                            <option value="Security">Security</option>
                            <option value="Other">Other</option>
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
                    </div>
                    <hr>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-sm shadow text-white" style="background-color:#00192D;">
                            <i class="fa fa-plus"></i> Add Item
                        </button>
                        <button type="button" class="btn btn-sm text-white shadow" onclick="closeAddItemDrawer()" style="background-color:#cc0001;">
                            <i class="fa fa-close"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
            <div class="offcanvas-backdrop" id="addItemDrawerBackdrop" onclick="closeAddItemDrawer()"></div>
        </main>
        <!-- /.content-wrapper -->
    </div>

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
        
        // Display monthly rent info
        const monthlyRent = <?= $monthly_rent ?>;
        if (monthlyRent > 0) {
            console.log('Monthly rent loaded: KES', monthlyRent);
        }
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
            // Don't allow removal of rent row if it's the only item
            const remainingRows = document.querySelectorAll('#invoiceBody tr');
            if (remainingRows.length <= 1 && rowId.includes('rowRent')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Remove',
                    text: 'At least one invoice item is required. Rent cannot be removed.',
                    confirmButtonColor: '#cc0001'
                });
                return;
            }
            
            row.remove();
            calculateTotals();
        }
    }

    function prepareInvoiceData() {
        // Gather all invoice items data
        const items = [];
        const rows = document.querySelectorAll('#invoiceBody tr');
        
        if (rows.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'No Items',
                text: 'Please add at least one item to the invoice.',
                confirmButtonColor: '#cc0001'
            });
            return false;
        }
        
        rows.forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 7) {
                const item = {
                    paidFor: cells[0].textContent.trim(),
                    description: cells[1].textContent.trim(),
                    unitPrice: parseFloat(cells[2].textContent.replace(/,/g, '')) || 0,
                    quantity: parseInt(cells[3].textContent) || 1,
                    taxType: cells[4].textContent.trim(),
                    taxAmount: parseFloat(cells[5].textContent.replace(/,/g, '')) || 0,
                    totalPrice: parseFloat(cells[6].textContent.replace(/,/g, '')) || 0
                };
                items.push(item);
            }
        });
        
        // Store in hidden field as JSON
        document.getElementById('invoiceItems').value = JSON.stringify(items);
        
        // Calculate final values
        calculateTotals();
        
        // Show confirmation
        Swal.fire({
            title: 'Confirm Invoice',
            text: 'Are you sure you want to submit this invoice?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#00192D',
            cancelButtonColor: '#cc0001',
            confirmButtonText: 'Yes, submit!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById('invoiceForm').submit();
            }
        });
        
        return false; // Prevent immediate form submission
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
                Swal.fire({
                    icon: 'error',
                    title: 'Item Name Required',
                    text: 'Please specify the item name.',
                    confirmButtonColor: '#cc0001'
                });
                return;
            }
        }
        
        const description = document.getElementById('drawerDescription').value || itemName;
        const unitPrice = parseFloat(document.getElementById('drawerUnitPrice').value) || 0;
        const quantity = parseInt(document.getElementById('drawerQuantity').value) || 1;
        const taxType = document.getElementById('drawerTaxType').value;
        
        // Calculate tax amount
        let totalPrice = unitPrice * quantity;
        let taxAmount = 0;
        
        if (taxType === 'VAT Inclusive') {
            // For VAT Inclusive: total = netPrice * 1.16
            taxAmount = totalPrice * (16/116); // Extract VAT from inclusive price
            totalPrice = unitPrice * quantity; // Keep total as inclusive
        } else if (taxType === 'VAT Exclusive') {
            // For VAT Exclusive: tax = netPrice * 0.16
            taxAmount = totalPrice * 0.16;
            totalPrice = totalPrice + taxAmount;
        } else {
            // No tax
            taxAmount = 0;
        }
        
        // Add row to table
        const rowId = 'row_' + Date.now(); // Unique ID
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
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Item Added',
            text: 'New item has been added to the invoice.',
            confirmButtonColor: '#00192D',
            timer: 1500
        });
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

    <script>
// Function to automatically add rent item to invoice
function autoAddRentItem(monthlyRent, unitNumber) {
    if (monthlyRent > 0) {
        // Check if rent already exists in the table
        const existingRentRows = document.querySelectorAll('#invoiceBody tr');
        let rentExists = false;
        
        existingRentRows.forEach(row => {
            const firstCell = row.querySelector('td:first-child');
            if (firstCell && firstCell.textContent.trim() === 'Rent') {
                rentExists = true;
            }
        });
        
        if (!rentExists) {
            // Calculate VAT (16% VAT Inclusive)
            const vatRate = 0.16;
            const totalPrice = monthlyRent;
            const taxAmount = totalPrice * (vatRate / (1 + vatRate));
            const netPrice = totalPrice - taxAmount;
            
            const rentRowId = 'rowRent_' + Date.now();
            const rentRow = document.createElement('tr');
            rentRow.id = rentRowId;
            rentRow.innerHTML = `
                <td>Rent</td>
                <td>Monthly Rental Payment for Unit ${unitNumber} - ${new Date().toLocaleString('default', { month: 'long', year: 'numeric' })}</td>
                <td class="unit-price">${netPrice.toFixed(2)}</td>
                <td class="quantity">1</td>
                <td class="tax-type">VAT Inclusive</td>
                <td class="tax-amount">${taxAmount.toFixed(2)}</td>
                <td class="total-price">${totalPrice.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow('${rentRowId}')">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            `;
            
            // Add to invoice body
            document.getElementById('invoiceBody').appendChild(rentRow);
            
            // Recalculate totals
            calculateTotals();
            
            // Show message
            console.log('Auto-added rent item: KES', monthlyRent);
        }
    }
}

// Call this function when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Get rent amount from PHP variable
    const monthlyRent = <?php echo $monthly_rent; ?>;
    const unitNumber = '<?php echo addslashes($unit_number); ?>';
    
    // Auto-add rent item
    autoAddRentItem(monthlyRent, unitNumber);
    
    // Rest of your initialization code...
});
</script>

    <!-- Main Js File -->
    <script src="../../js/adminlte.js"></script>
    <script src="../js/main.js"></script>
    <!-- html2pdf depends on html2canvas and jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
</body>
<!--end::Body-->

</html>