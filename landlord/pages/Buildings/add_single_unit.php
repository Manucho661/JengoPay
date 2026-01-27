<?php
session_start();
require_once "../db/connect.php";
//  include_once 'includes/lower_right_popup_form.php';
?>
<?php
require_once "../db/connect.php";

// Fetch all accounts relevant for recurring bills (Expenses)
$stmt = $pdo->prepare("SELECT account_name, account_code FROM chart_of_accounts WHERE account_type='Expenses' ORDER BY account_name ASC");
$stmt->execute();
$coaList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
require_once "../db/connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_unit'])) {
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // 1. Insert into building_units table
        $unitStmt = $pdo->prepare("
            INSERT INTO building_units (
                building_id, unit_category_id, unit_number, 
                number_of_rooms, number_of_washrooms, number_of_doors,
                purpose, water_meter, monthly_rent, location, 
                price, occupancy_status, created_at
            ) VALUES (
                :building_id, :unit_category_id, :unit_number,
                :number_of_rooms, :number_of_washrooms, :number_of_doors,
                :purpose, :water_meter, :monthly_rent, :location,
                :price, :occupancy_status, NOW()
            )
        ");
        
        // Get building_id from building_link (you need to adjust this based on your logic)
        $building_id = 1; // This should come from your form or session
        
        // Get unit_category_id based on purpose
        $purpose = $_POST['purpose'];
        $unit_category_id = 1; // Default - adjust based on your categories
        
        if ($purpose == 'Residential') $unit_category_id = 1;
        elseif ($purpose == 'Office') $unit_category_id = 2;
        elseif ($purpose == 'Business') $unit_category_id = 3;
        elseif ($purpose == 'Store') $unit_category_id = 4;
        
        $unitStmt->execute([
            ':building_id' => $building_id,
            ':unit_category_id' => $unit_category_id,
            ':unit_number' => $_POST['unit_number'],
            ':number_of_rooms' => NULL, // Add these to your form if needed
            ':number_of_washrooms' => NULL,
            ':number_of_doors' => NULL,
            ':purpose' => $purpose,
            ':water_meter' => NULL, // Add to form if needed
            ':monthly_rent' => $_POST['monthly_rent'],
            ':location' => $_POST['location'],
            ':price' => NULL, // Add to form if needed
            ':occupancy_status' => $_POST['occupancy_status']
        ]);
        
        // Get the last inserted unit ID
        $building_unit_id = $pdo->lastInsertId();
        
        // 2. Insert recurring bills into bills table
        if (isset($_POST['bill_name']) && is_array($_POST['bill_name'])) {
            $billStmt = $pdo->prepare("
                INSERT INTO bills (
                    building_unit_id, bill_name, quantity, 
                    unit_price, sub_total, created_at
                ) VALUES (
                    :building_unit_id, :bill_name, :quantity,
                    :unit_price, :sub_total, NOW()
                )
            ");
            
            // Process each bill row
            for ($i = 0; $i < count($_POST['bill_name']); $i++) {
                $bill_name = $_POST['bill_name'][$i];
                
                // If bill is "Other", use the other input
                if ($bill_name === 'Other' && isset($_POST['bill_name_other'][$i])) {
                    $bill_name = $_POST['bill_name_other'][$i];
                }
                
                // Convert bill_name to appropriate value for your table
                // Since your bills table has bill_name as INT, you might need to map it
                // For now, I'll store it as string - you may need to adjust
                $bill_name_value = $bill_name; // You might need to convert this to INT
                
                $quantity = intval($_POST['quantity'][$i]);
                $unit_price = floatval($_POST['unit_price'][$i]);
                $sub_total = $quantity * $unit_price;
                
                $billStmt->execute([
                    ':building_unit_id' => $building_unit_id,
                    ':bill_name' => $bill_name, // Note: This needs to match your table structure
                    ':quantity' => $quantity,
                    ':unit_price' => $unit_price * 100, // Convert to cents if needed
                    ':sub_total' => $sub_total * 100 // Convert to cents if needed
                ]);
            }
        }
        
        // Commit transaction
        $pdo->commit();
        
        // Success message
        $_SESSION['success_message'] = "Unit and recurring bills added successfully!";
        
        // Redirect or show success
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Unit and recurring bills have been saved successfully.',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'units.php'; // Redirect to units listing
                }
            });
        </script>";
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        
        // Error message
        error_log("Error adding unit: " . $e->getMessage());
        
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while saving the unit. Please try again.',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        </script>";
    }
}
?>

<?php
require_once "../db/connect.php";

// Make sure $unit_id, $tenant_id, $building_id are defined before this point
// For example, from your form or selection
// $unit_id = $_POST['unit_id'];
// $tenant_id = $_POST['tenant_id'];
// $building_id = $_POST['building_id'];

foreach ($_POST['bill_name'] as $i => $bill) {
    $stmt = $pdo->prepare("
        INSERT INTO recurring_bills 
        (building_id, unit_id, tenant_id, account_code, bill_name, qty, unit_price, subtotal) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $building_id,                             // The building this bill belongs to
        $unit_id,                                 // The unit this bill belongs to
        $tenant_id,                               // The tenant in the unit
        $_POST['account_code'][$i],               // Chart of account
        $_POST['bill_name'][$i],
        $_POST['qty'][$i],
        $_POST['unit_price'][$i],
        $_POST['qty'][$i] * $_POST['unit_price'][$i] // subtotal
    ]);
}
?>

<?php
// Function to create journal entry
function createJournalEntry($pdo, $date, $description, $entries)
{
    // Start transaction
    $pdo->beginTransaction();

    try {
        // Insert into journal_entries table (create this table if not exists)
        $sql = "INSERT INTO journal_entries (entry_date, description) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters using bindValue or bindParam (PDO)
        $stmt->bindValue(1, $date); // Bind the first parameter (entry_date)
        $stmt->bindValue(2, $description); // Bind the second parameter (description)

        // Execute the prepared statement
        $stmt->execute();

        $journal_id = $pdo->insert_id;

        // Insert each journal entry line
        $sql = "INSERT INTO journal_entry_lines 
                (journal_id, account_code, debit_amount, credit_amount) 
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        foreach ($entries as $entry) {
            $stmt->bind_param(
                "iidd",
                $journal_id,
                $entry['account_code'],
                $entry['debit'],
                $entry['credit']
            );
            $stmt->execute();
        }

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollback();
        return false;
    }
}

// Handle rent payment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $monthly_rent = floatval($_POST['monthly_rent']);
    $tenant_id = $_POST['tenant_id'] ?? null;
    $property_id = $_POST['property_id'] ?? null;
    $payment_method = $_POST['payment_method'] ?? 'cash'; // cash, mpesa, bank
    $payment_date = $_POST['payment_date'] ?? date('Y-m-d');
    $description = "Rent Payment - " . date('F Y');

    if ($tenant_id) {
        $description .= " - Tenant ID: " . $tenant_id;
    }

    // Validate amount
    if ($monthly_rent <= 0) {
        $response = [
            'success' => false,
            'message' => 'Invalid rent amount'
        ];
        echo json_encode($response);
        exit;
    }

    // Determine cash/bank account based on payment method
    $cash_account_code = 100; // Default to Cash (100)

    switch ($payment_method) {
        case 'mpesa':
            $cash_account_code = 110; // M-pesa
            break;
        case 'bank':
            $cash_account_code = 120; // Bank
            break;
        case 'cash':
        default:
            $cash_account_code = 100; // Cash
            break;
    }

    // Prepare journal entries
    $entries = [
        [
            'account_code' => $cash_account_code, // Cash/M-pesa/Bank account (debit)
            'debit' => $monthly_rent,
            'credit' => 0.00
        ],
        [
            'account_code' => 500, // Rental Income account (credit)
            'debit' => 0.00,
            'credit' => $monthly_rent
        ]
    ];

    // Create the journal entry
    $result = createJournalEntry($pdo, $payment_date, $description, $entries);

    // if ($result) {
    //     // Optionally, update tenant's payment record in another table
    //     if ($tenant_id) {
    //         $sql = "INSERT INTO tenant_payments 
    //                 (tenant_id, property_id, amount, payment_date, payment_method, journal_id) 
    //                 VALUES (?, ?, ?, ?, ?, ?)";
    //         $stmt = $conn->prepare($sql);
    //         $stmt->bind_param(
    //             "iidssi",
    //             $tenant_id,
    //             $property_id,
    //             $monthly_rent,
    //             $payment_date,
    //             $payment_method,
    //             $journal_id
    //         );
    //         $stmt->execute();
    //     }

    //     $response = [
    //         'success' => true,
    //         'message' => 'Rent payment recorded successfully!',
    //         'data' => [
    //             'amount' => $monthly_rent,
    //             'date' => $payment_date,
    //             'rent_account' => '500 - Rental Income',
    //             'cash_account' => $cash_account_code . ' - ' . getAccountName($conn, $cash_account_code)
    //         ]
    //     ];
    // } else {
    //     $response = [
    //         'success' => false,
    //         'message' => 'Failed to record payment'
    //     ];
    // }

    // echo json_encode($response);
}

// Helper function to get account name
function getAccountName($pdo, $account_code)
{
    $sql = "SELECT account_name FROM chart_of_accounts WHERE account_code = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bind_param("i", $account_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['account_name'];
    }

    return "Unknown Account";
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


    <!--Tailwind CSS  -->
    <style>
        .app-wrapper {
            background-color: rgba(128, 128, 128, 0.1);
        }

        .modal-backdrop.show {
            opacity: 0.4 !important;
            /* Adjust the value as needed */
        }

        .diagonal-paid-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Centered and rotated */
            background-color: rgba(0, 128, 0, 0.2);
            /* Light green with transparency */
            color: green;
            font-weight: bold;
            font-size: 24px;
            padding: 15px 40px;
            border: 2px solid green;
            border-radius: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
        }

        .diagonal-unpaid-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Centered and rotated */
            background-color: rgba(255, 0, 0, 0.2);
            /* Red with transparency for "UNPAID" */
            color: #ff4d4d;
            /* Softer red text color */
            font-weight: bold;
            font-size: 24px;
            padding: 15px 40px;
            border: 2px solid red;
            border-radius: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
        }

        .diagonal-partially-paid-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Centered and rotated */
            background-color: rgba(255, 165, 0, 0.2);
            /* Amber background with opacity */
            color: #ff9900;
            /* Amber or gold text */
            font-weight: bold;
            font-size: 24px;
            padding: 15px 40px;
            border: 2px solid #ff9900;
            /* Amber border */
            border-radius: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
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
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?>
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="main">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="">
                    <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Dashboard/index2.php" style="text-decoration: none;">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Buildings/buildings.php" style="text-decoration: none;">Buildings</a></li>
                    <li class="breadcrumb-item active">Add single unit</li>
                </ol>
            </nav>
            <div class="container-fluid">
                <?php
                include_once 'processes/encrypt_decrypt_function.php';

                $id = null;
                if (isset($_GET['add_single_unit']) && !empty($_GET['add_single_unit'])) {
                    $id = $_GET['add_single_unit'];
                    $id = encryptor('decrypt', $id);
                    $_SESSION['building_id'] = $id; // persist building id across different requests
                    try {
                        if (!empty($id)) {
                            $select = "SELECT * FROM buildings WHERE id =:id";
                            $stmt = $pdo->prepare($select);
                            $stmt->execute(array(':id' => $id));

                            while ($row = $stmt->fetch()) {
                                $building_name = $row['building_name'];
                                $county = $row['county'];
                                $constituency = $row['constituency'];
                                $ward = $row['ward'];
                                $structure_type = $row['structure_type'];
                                $floors_no = $row['floors_no'];
                                $no_of_units = $row['no_of_units'];
                                $building_type = $row['building_type'];
                                $tax_rate = $row['tax_rate'];
                                $ownership_info = $row['ownership_info'];
                                $first_name = $row['first_name'];
                                $last_name = $row['last_name'];
                                $id_number = $row['id_number'];
                                $primary_contact = $row['primary_contact'];
                                $other_contact = $row['other_contact'];
                                $owner_email = $row['owner_email'];
                                $postal_address = $row['postal_address'];
                                $entity_name = $row['entity_name'];
                                $entity_phone = $row['entity_phone'];
                                $entity_phoneother = $row['entity_phoneother'];
                                $entity_email = $row['entity_email'];
                                $entity_rep = $row['entity_rep'];
                                $rep_role = $row['rep_role'];
                                $entity_postal = $row['entity_postal'];
                                $ownership_proof = $row['ownership_proof'];
                                $title_deed = $row['title_deed'];
                                $legal_document = $row['legal_document'];
                                $photo_one = $row['photo_one'];
                                $photo_two = $row['photo_two'];
                                $photo_three = $row['photo_three'];
                                $photo_four = $row['photo_four'];
                                $added_on = $row['added_on'];
                                $ownership_proof = $row['ownership_proof'];
                                $title_deed = $row['title_deed'];
                                $legal_document = $row['legal_document'];
                                $photo_one = $row['photo_one'];
                                $photo_two = $row['photo_two'];
                                $photo_three = $row['photo_three'];
                                $photo_four = $row['photo_four'];
                            }
                        } else {
                            echo "<script>
                                Swal.fire({
                                  icon: 'error',
                                  title: 'No Information!',
                                  text: 'No Building Information could be Extracted from the Database',
                                  confirmButtonColor: '#cc0001'
                                  });
                                  </script>";
                        }
                    } catch (PDOException $e) {
                        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to Load Building Information. " . addslashes($e->getMessage()) . "',
                                    confirmButtonColor: '#cc0001'
                                    });
                                    </script>";
                    }
                }

                //if the Submit button is clicked
                if (isset($_POST['submit_unit'])) {

                    try {

                        $buildingId = $_SESSION['building_id'] ?? null;

                        if (!$buildingId) {
                            throw new Exception('Invalid building context.');
                        }

                        // START TRANSACTION FIRST (prevents race conditions)
                        $pdo->beginTransaction();

                        // --------------------------------------------------
                        // Check for duplicate unit_number + building_id
                        // --------------------------------------------------
                        $check = $pdo->prepare("
                                SELECT COUNT(*) 
                                FROM building_units 
                                WHERE unit_number = :unit_number 
                                AND building_id = :building_id
                            ");
                        $check->execute([
                            ':unit_number'  => $_POST['unit_number'],
                            ':building_id'  => $buildingId
                        ]);

                        if ($check->fetchColumn() > 0) {
                            throw new Exception('No double submission of data: this unit already exists in the database.');
                        }

                        // --------------------------------------------------
                        // Insert into building_units
                        // --------------------------------------------------
                        // $stmt = $pdo->prepare("
                        //     INSERT INTO building_units
                        //     (
                        //         structure_type,
                        //         first_name,
                        //         last_name,
                        //         owner_email,
                        //         entity_name,
                        //         entity_phone,
                        //         entity_phoneother,
                        //         entity_email,
                        //         unit_number,
                        //         purpose,
                        //         building_link,
                        //         location,
                        //         monthly_rent,
                        //         occupancy_status,
                        //         created_at
                        //     )
                        //     VALUES
                        //     (
                        //         :structure_type,
                        //         :first_name,
                        //         :last_name,
                        //         :owner_email,
                        //         :entity_name,
                        //         :entity_phone,
                        //         :entity_phoneother,
                        //         :entity_email,
                        //         :unit_number,
                        //         :purpose,
                        //         :building_link,
                        //         :location,
                        //         :monthly_rent,
                        //         :occupancy_status,
                        //         NOW()
                        //     )
                        // ");

                        // --------------------------------------------------
                        // Get unit_category_id (single_unit)
                        // --------------------------------------------------
                        $sql = "
                                SELECT id 
                                FROM unit_categories 
                                WHERE category_name = :category_name 
                                LIMIT 1
                            ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':category_name' => 'single_unit'
                        ]);

                        $singleUnitCategoryId = $stmt->fetchColumn();

                        // Safety check
                        if ($singleUnitCategoryId === false) {
                            throw new Exception('Unit category "single_unit" not found.');
                        }

                        // --------------------------------------------------
                        // Insert building unit (normalized approach)
                        // --------------------------------------------------
                        $stmt = $pdo->prepare("
                                INSERT INTO building_units 
                                (
                                    building_id,
                                    unit_category_id,
                                    unit_number,
                                    purpose,
                                    location,
                                    monthly_rent,
                                    occupancy_status
                                ) 
                                VALUES 
                                (
                                    :building_id,
                                    :unit_category_id,
                                    :unit_number,
                                    :purpose,
                                    :location,
                                    :monthly_rent,
                                    :occupancy_status
                                )
                            ");

                        $stmt->execute([
                            // ':structure_type' => $_POST['structure_type'],
                            // ':first_name' => $_POST['first_name'],
                            // ':last_name' => $_POST['last_name'],
                            // ':owner_email' => $_POST['owner_email'],
                            // ':entity_name' => $_POST['entity_name'],
                            // ':entity_phone' => $_POST['entity_phone'],
                            // ':entity_phoneother' => $_POST['entity_phoneother'],
                            // ':entity_email' => $_POST['entity_email'],
                            ':building_id'      => $buildingId,
                            ':unit_category_id' => $singleUnitCategoryId,
                            ':unit_number'      => $_POST['unit_number'],
                            ':purpose'          => $_POST['purpose'],
                            // ':building_link' => $_POST['building_link'],
                            ':location'         => $_POST['location'],
                            ':monthly_rent'     => $_POST['monthly_rent'],
                            ':occupancy_status' => $_POST['occupancy_status']
                        ]);

                        // --------------------------------------------------
                        // Get inserted unit_id (used later for bills)
                        // --------------------------------------------------
                        $unit_id = $pdo->lastInsertId();

                        // --------------------------------------------------
                        // Insert unit bills (currently disabled)
                        // --------------------------------------------------
                        // if (!empty($_POST['bill'])) {
                        //     $stmtBill = $pdo->prepare("
                        //         INSERT INTO single_unit_bills
                        //         (unit_id, bill, qty, unit_price, created_at)
                        //         VALUES
                        //         (:unit_id, :bill, :qty, :unit_price, NOW())
                        //     ");
                        //
                        //     foreach ($_POST['bill'] as $i => $billName) {
                        //         if ($billName != '') {
                        //             $qty       = $_POST['qty'][$i] ?? 0;
                        //             $unitPrice = $_POST['unit_price'][$i] ?? 0;
                        //
                        //             $stmtBill->execute([
                        //                 ':unit_id'    => $unit_id,
                        //                 ':bill'       => $billName,
                        //                 ':qty'        => $qty,
                        //                 ':unit_price' => $unitPrice
                        //             ]);
                        //         }
                        //     }
                        // }

                        // --------------------------------------------------
                        // Commit transaction
                        // --------------------------------------------------
                        $pdo->commit();

                        // Success alert
                        echo "
                            <script>
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Unit information saved successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href = 'single_units.php';
                                });
                            </script>";
                        exit;
                    } catch (Exception $e) {

                        if ($pdo->inTransaction()) {
                            $pdo->rollBack();
                        }

                        // Error alert
                        echo "
                            <script>
                                Swal.fire({
                                    title: 'Error!',
                                    text: '" . addslashes($e->getMessage()) . "',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        exit;
                    }
                }
                ?>
                
                <!--First Row-->
                <div class="row align-items-center mb-4">
                    <div class="col-12 d-flex align-items-center">
                        <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
                        <h3 class="mb-0 ms-3">Add a single unit</h3>
                        <span class="mx-4"></span>
                    </div>
                </div>
                
                <!-- Second Row -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="stat-card d-flex align-items-center rounded-2 p-1">
                            <div>
                                <i class="fas fa-building me-3 text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Building</p>
                                <h3><?= htmlspecialchars($building_name); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="stat-card d-flex align-items-center rounded-2 p-1">
                            <div>
                                <i class="fas fa-city me-3 text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Structure type</p>
                                <h3><?= htmlspecialchars($structure_type); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="stat-card d-flex align-items-center rounded-2 p-1">
                            <div>
                                <i class="fas fa-house-damage me-3 text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Building Type</p>
                                <h3><?= htmlspecialchars($structure_type); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="stat-card d-flex align-items-center rounded-2 p-1">
                            <div>
                                <i class="fas fa-city me-3 text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Number of Units</p>
                                <h3><?= htmlspecialchars($no_of_units); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="structure_type" value="<?= htmlspecialchars($structure_type); ?>">
    <input type="hidden" name="first_name" value="<?= htmlspecialchars($first_name); ?>">
    <input type="hidden" name="last_name" value="<?= htmlspecialchars($last_name); ?>">
    <input type="hidden" name="owner_email" value="<?= htmlspecialchars($owner_email); ?>">
    <input type="hidden" name="entity_name" value="<?= htmlspecialchars($entity_name); ?>">
    <input type="hidden" name="entity_phone" value="<?= htmlspecialchars($entity_phone); ?>">
    <input type="hidden" name="entity_phoneother" value="<?= htmlspecialchars($entity_phoneother); ?>">
    <input type="hidden" name="entity_email" value="<?= htmlspecialchars($entity_email); ?>">
    
    <?php
    // Fetch the Rental Income account from chart_of_accounts
    $rental_account_name = "Rental Income";
    $rental_account_code = 500;
    
    try {
        require_once "../db/connect.php"; // Make sure this path is correct
        $chartStmt = $pdo->prepare("SELECT account_name, account_code FROM chart_of_accounts WHERE account_code = 500");
        $chartStmt->execute();
        $rentalAccount = $chartStmt->fetch(PDO::FETCH_ASSOC);
        
        if($rentalAccount) {
            $rental_account_name = $rentalAccount['account_name'];
            $rental_account_code = $rentalAccount['account_code'];
        }
    } catch (Exception $e) {
        error_log("Error fetching chart of accounts: " . $e->getMessage());
        // Use default values if fetch fails
    }
    ?>
    
    <div class="card-body">
        <div class="card shadow" id="firstSection" style="border:1px solid rgb(0,25,45,.2);">
            <div class="card-header" style="background-color: #00192D; color:#fff;">
                <b class="text-warning">Unit Identification</b>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Unit Number</label>
                            <input type="text" name="unit_number" required class="form-control" id="unit_number" placeholder="Unit Number">
                        </div>
                        <div class="form-group">
                            <label for="">Purpose</label>
                            <select name="purpose" id="purpose" class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                <option value="" selected hidden>-- Select Option -- </option>
                                <option value="Office">Office</option>
                                <option value="Residential">Residential</option>
                                <option value="Business">Business</option>
                                <option value="Store">Store</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Link to the Building</label>
                            <input type="text" name="building_link" class="form-control" value="<?= htmlspecialchars($building_name); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Location with in the Building</label>
                            <input name="location" type="text" class="form-control" id="location" placeholder="Location e.g.Second Floor">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-sm next-btn" id="firstSectionNexttBtn">Next</button>
            </div>
        </div>

        <div class="card shadow" id="secondSection" style="border:1px solid rgb(0,25,45,.2); display:none;">
            <div class="card-header" style="background-color: #00192D; color:#fff;">
                <b>Financials and Other Information</b>
            </div>
            <div class="card-body">
                <!-- Updated Monthly Rent Section -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Monthly Rent <span class="text-muted small">(Linked to Chart of Accounts)</span></label>
                            <input type="number" 
                                   class="form-control" 
                                   id="monthly_rent" 
                                   name="monthly_rent" 
                                   placeholder="Enter Monthly Rent"
                                   min="0"
                                   step="0.01"
                                   required>
                            <small class="text-muted">This amount will be recorded as "<?= htmlspecialchars($rental_account_name) ?>" in your accounting</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Chart of Accounts Reference</label>
                            <div class="card" style="border: 1px solid #dee2e6;">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1" style="font-size: 0.9rem;">Account Reference:</h6>
                                    <p class="card-text mb-1" style="font-size: 0.8rem;">
                                        <strong>Account:</strong> <?= htmlspecialchars($rental_account_name) ?>
                                    </p>
                                    <p class="card-text mb-0" style="font-size: 0.8rem;">
                                        <strong>Code:</strong> <?= htmlspecialchars($rental_account_code) ?>
                                    </p>
                                    <p class="card-text mb-0" style="font-size: 0.8rem;">
                                        <strong>Type:</strong> Revenue - Rental Revenue
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header" style="background-color:#00192D; color: #fff;">Recurring Bills</div>
                    <div class="card-body">
                        <table id="expensesTable" class="table">
                            <thead>
                                <tr>
                                    <th>Bill</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Subtotal</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody id="expensesBody"></tbody>
                                <!-- Rows will be added here dynamically -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    <td id="totalQty">0</td>
                                    <td id="totalUnitPrice">0.00</td>
                                    <td id="totalSubtotal">0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="button" class="btn btn-sm shadow" style="border:1px solid #00192D; color:#00192D;" onclick="addRow()">+ Add Row</button>
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <label>Occupancy Status</label>
                    <select name="occupancy_status" id="occupancy_status" required class="form-control">
                        <option value="" selected hidden>-- Select Status --</option>
                        <option value="Occupied">Occupied</option>
                        <option value="Vacant">Vacant</option>
                        <option value="Under Maintenance">Under Maintenance</option>
                    </select>
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-sm" id="secondSectionBackBtn" type="button" style="background-color:#00192D; color:#fff;">Go Back</button>
                <button class="btn btn-sm" type="submit" name="submit_unit" style="background-color:#00192D; color: #fff;">
                    <i class="bi bi-send"></i> Submit
                </button>
            </div>
        </div>
    </div>
</form>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!--end::App Main-->

        <!--begin::Footer-->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
        <!-- end footer -->
    </div>
    <!--end::App Wrapper-->

    
    <script>
let rowCount = 0;

// Pass PHP CoA array to JavaScript
const coaOptions = <?php echo json_encode($coaList); ?>;

function addRow() {
    rowCount++;
    const tableBody = document.querySelector('#expensesTable tbody');
    const newRow = document.createElement('tr');
    newRow.id = 'row-' + rowCount;

    const billOptions = coaOptions.map(opt => `<option value="${opt.account_name}" data-code="${opt.account_code}">${opt.account_name}</option>`).join('');

    newRow.innerHTML = `
        <td>
            <select name="bill_name[]" class="form-control form-control-sm bill-select" required>
                <option value="" selected hidden>Select Bill</option>
                ${billOptions}
                <option value="Other" data-code="9999">Other</option>
            </select>
            <input type="text" name="bill_name_other[]" class="form-control form-control-sm mt-1 d-none" placeholder="Specify other bill">
        </td>
        <td>
            <input type="text" name="account_code[]" class="form-control form-control-sm coa-input" readonly>
        </td>
        <td><input type="number" name="quantity[]" class="form-control form-control-sm qty-input" min="1" value="1" required></td>
        <td><input type="number" name="unit_price[]" class="form-control form-control-sm price-input" min="0" step="0.01" value="0" required></td>
        <td class="subtotal-cell">0.00</td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowCount})">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;

    tableBody.appendChild(newRow);

    const qtyInput = newRow.querySelector('.qty-input');
    const priceInput = newRow.querySelector('.price-input');
    const billSelect = newRow.querySelector('.bill-select');
    const coaInput = newRow.querySelector('.coa-input');

    qtyInput.addEventListener('input', calculateTotals);
    priceInput.addEventListener('input', calculateTotals);

    billSelect.addEventListener('change', function() {
        const selectedOption = this.selectedOptions[0];
        coaInput.value = selectedOption.getAttribute('data-code');

        const otherInput = this.closest('td').querySelector('[name="bill_name_other[]"]');
        if (this.value === 'Other') {
            otherInput.classList.remove('d-none');
            otherInput.required = true;
        } else {
            otherInput.classList.add('d-none');
            otherInput.required = false;
        }
    });

    calculateTotals();
}

function removeRow(rowId) {
    const row = document.getElementById('row-' + rowId);
    if (row) {
        row.remove();
        calculateTotals();
    }
}

function calculateTotals() {
    let totalQty = 0;
    let totalUnitPrice = 0;
    let totalSubtotal = 0;

    document.querySelectorAll('#expensesTable tbody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = qty * price;

        row.querySelector('.subtotal-cell').textContent = subtotal.toFixed(2);

        totalQty += qty;
        totalUnitPrice += price;
        totalSubtotal += subtotal;
    });

    document.getElementById('totalQty').textContent = totalQty;
    document.getElementById('totalUnitPrice').textContent = totalUnitPrice.toFixed(2);
    document.getElementById('totalSubtotal').textContent = totalSubtotal.toFixed(2);
}

// Initialize first row
document.addEventListener('DOMContentLoaded', addRow);
</script>

    <!-- <script>
let coaAccounts = [];

// Fetch COA expenses on page load
fetch('fetch_coa_revenue.php')
    .then(res => res.json())
    .then(data => {
        coaAccounts = data;
    });

function addRow() {
    const tbody = document.getElementById('expensesBody');

    let coaOptions = `<option value="">Select Bill</option>`;
    coaAccounts.forEach(acc => {
        coaOptions += `<option value="${acc.account_code}">${acc.account_name}</option>`;
    });

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select class="form-control form-control-sm bill">
                ${coaOptions}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm qty" value="1" min="1" oninput="calculateRow(this)">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm price" value="0" min="0" step="0.01" oninput="calculateRow(this)">
        </td>
        <td class="subtotal">0.00</td>
        <td>
            <button class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); calculateTotals();">✕</button>
        </td>
    `;
    tbody.appendChild(row);
}
</script>

<script>
function calculateRow(el) {
    const row = el.closest('tr');
    const qty = parseFloat(row.querySelector('.qty').value) || 0;
    const price = parseFloat(row.querySelector('.price').value) || 0;

    const subtotal = qty * price;
    row.querySelector('.subtotal').innerText = subtotal.toFixed(2);

    calculateTotals();
}

function calculateTotals() {
    let totalQty = 0;
    let totalSubtotal = 0;

    document.querySelectorAll('#expensesBody tr').forEach(row => {
        totalQty += parseFloat(row.querySelector('.qty').value) || 0;
        totalSubtotal += parseFloat(row.querySelector('.subtotal').innerText) || 0;
    });

    document.getElementById('totalQty').innerText = totalQty;
    document.getElementById('totalSubtotal').innerText = totalSubtotal.toFixed(2);
    document.getElementById('totalUnitPrice').innerText = '—';
}
</script> -->


    <!-- <script>
// Dynamic rows for expenses table
let rowCount = 0;

function addRow() {
    rowCount++;
    const tableBody = document.querySelector('#expensesTable tbody');
    const newRow = document.createElement('tr');
    newRow.id = 'row-' + rowCount;
    newRow.innerHTML = `
        <td>
            <select name="bill_name[]" class="form-control form-control-sm bill-select" required>
                <option value="" selected hidden>Select Bill</option>
                <option value="Water">Water</option>
                <option value="Garbage">Garbage</option>
                <option value="Electricity">Electricity</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Internet">Internet</option>
                <option value="Security">Security</option>
                <option value="Parking">Parking</option>
                <option value="Other">Other</option>
            </select>
            <input type="text" name="bill_name_other[]" class="form-control form-control-sm mt-1 d-none" placeholder="Specify other bill">
        </td>
        <td><input type="number" name="quantity[]" class="form-control form-control-sm qty-input" min="1" value="1" required></td>
        <td><input type="number" name="unit_price[]" class="form-control form-control-sm price-input" min="0" step="0.01" value="0" required></td>
        <td class="subtotal-cell">0.00</td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowCount})">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;
    tableBody.appendChild(newRow);
    
    // Add event listeners for calculations
    const qtyInput = newRow.querySelector('.qty-input');
    const priceInput = newRow.querySelector('.price-input');
    const billSelect = newRow.querySelector('.bill-select');
    
    qtyInput.addEventListener('input', calculateTotals);
    priceInput.addEventListener('input', calculateTotals);
    billSelect.addEventListener('change', function() {
        const otherInput = this.closest('td').querySelector('[name="bill_name_other[]"]');
        if (this.value === 'Other') {
            otherInput.classList.remove('d-none');
            otherInput.required = true;
        } else {
            otherInput.classList.add('d-none');
            otherInput.required = false;
        }
    });
    
    calculateTotals();
}

function removeRow(rowId) {
    const row = document.getElementById('row-' + rowId);
    if (row) {
        row.remove();
        calculateTotals();
    }
}

function calculateTotals() {
    let totalQty = 0;
    let totalUnitPrice = 0;
    let totalSubtotal = 0;
    
    const rows = document.querySelectorAll('#expensesTable tbody tr');
    rows.forEach(row => {
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        const subtotalCell = row.querySelector('.subtotal-cell');
        
        if (qtyInput && priceInput && subtotalCell) {
            const qty = parseFloat(qtyInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const subtotal = qty * price;
            
            subtotalCell.textContent = subtotal.toFixed(2);
            
            totalQty += qty;
            totalUnitPrice += price;
            totalSubtotal += subtotal;
        }
    });
    
    // Update footer totals
    document.getElementById('totalQty').textContent = totalQty;
    document.getElementById('totalUnitPrice').textContent = totalUnitPrice.toFixed(2);
    document.getElementById('totalSubtotal').textContent = totalSubtotal.toFixed(2);
}

// Initialize with one row when page loads
document.addEventListener('DOMContentLoaded', function() {
    addRow();
    
    // Form validation before submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Validate that at least one bill row exists
        const billRows = document.querySelectorAll('#expensesTable tbody tr');
        if (billRows.length === 0) {
            e.preventDefault();
            alert('Please add at least one recurring bill or remove the entire recurring bills section.');
            return false;
        }
        
        // Validate each bill row
        let isValid = true;
        billRows.forEach(row => {
            const billSelect = row.querySelector('.bill-select');
            const qtyInput = row.querySelector('.qty-input');
            const priceInput = row.querySelector('.price-input');
            
            if (!billSelect.value || !qtyInput.value || !priceInput.value) {
                isValid = false;
            }
            
            // Check for "Other" bill with empty specification
            if (billSelect.value === 'Other') {
                const otherInput = row.querySelector('[name="bill_name_other[]"]');
                if (!otherInput.value.trim()) {
                    isValid = false;
                    otherInput.classList.add('is-invalid');
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields for recurring bills.');
            return false;
        }
        
        return true;
    });
});
</script> -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Validate monthly rent input
    const monthlyRentInput = document.getElementById('monthly_rent');
    if(monthlyRentInput) {
        monthlyRentInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (value < 0) {
                alert('Monthly rent cannot be negative');
                this.value = '';
                this.focus();
            }
        });
        
        // Format the input to 2 decimal places
        monthlyRentInput.addEventListener('change', function() {
            if(this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    }
    
    // Form submission validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const monthlyRent = parseFloat(monthlyRentInput.value);
        if (!monthlyRent || monthlyRent <= 0) {
            e.preventDefault();
            alert('Please enter a valid monthly rent amount');
            monthlyRentInput.focus();
            return false;
        }
        
        // Add chart of accounts reference to form data
        const coaInput = document.createElement('input');
        coaInput.type = 'hidden';
        coaInput.name = 'chart_of_accounts_ref';
        coaInput.value = '<?= $rental_account_code ?>';
        this.appendChild(coaInput);
        
        return true;
    });
});
</script>

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