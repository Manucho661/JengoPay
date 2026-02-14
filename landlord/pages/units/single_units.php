<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

require_once "../db/connect.php";

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);
?>

<!-- Action scripts -->
<?php
require_once "./actions/getSingleUnits.php";
?>
<?php
if (isset($_POST['submit_reading'])) {

    require_once '../db/connect.php';

    // 🔐 Sanitize & collect inputs
    $unit_id          = (int) $_POST['id'];
    $reading_date     = $_POST['reading_date'];
    $meter_type       = $_POST['meter_type']; // Water / Electricity
    $current_reading  = (float) $_POST['current_reading'];
    $previous_reading = (float) ($_POST['previous_reading'] ?? 0);
    $units_consumed   = (float) $_POST['units_consumed'];
    $cost_per_unit    = (float) $_POST['cost_per_unit'];
    $final_bill       = (float) $_POST['final_bill'];

    // OPTIONAL: resolve tenant_id if unit is occupied
    $tenant_id = 0;
    $tenantStmt = $pdo->prepare("
        SELECT id FROM tenants 
        WHERE unit_id = ? AND tenant_status = 'Active'
        LIMIT 1
    ");
    $tenantStmt->execute([$unit_id]);
    if ($tenant = $tenantStmt->fetch()) {
        $tenant_id = $tenant['id'];
    }

    // 🛡️ Safety check
    if (!in_array($meter_type, ['Water', 'Electricity'])) {
        die('Invalid meter type');
    }

    // ✅ INSERT INTO bills
    $stmt = $pdo->prepare("
        INSERT INTO bills (
            unit_id,
            tenant_id,
            bill_name,
            quantity,
            unit_price,
            meter_type,
            sub_total,
            created_at
        ) VALUES (
            :unit_id,
            :tenant_id,
            :bill_name,
            :quantity,
            :unit_price,
            :meter_type,
            :sub_total,
            :created_at
        )
    ");

    $stmt->execute([
        ':unit_id'    => $unit_id,
        ':tenant_id'  => $tenant_id,
        ':bill_name'  => $meter_type,        // 👈 IMPORTANT
        ':quantity'   => $units_consumed,
        ':unit_price' => $cost_per_unit,
        ':meter_type' => $meter_type,
        ':sub_total'  => $final_bill,
        ':created_at' => $reading_date
    ]);

    // ✅ Success feedback
    echo "<script>
        alert('Meter reading saved successfully');
        window.location.href = 'single_units.php';
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
            /* background-color: rgba(128, 128, 128, 0.1); */
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

        /* Off-canvas */
        .offcanvas {
            width: 500px !important;
        }

        .offcanvas-header {
            background: var(--main-color);
            color: white;
            padding: 20px;
        }

        .offcanvas-title {
            color: white;
        }

        /* Tenant Info Card */
        .tenant-info-card {
            background: rgba(255, 193, 7, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent-color);
        }

        .tenant-info-card .tenant-name {
            font-size: 20px;
            font-weight: bold;
            color: var(--main-color);
            margin-bottom: 15px;
        }

        .tenant-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #666;
        }

        .tenant-detail i {
            color: var(--accent-color);
            width: 20px;
        }

        /* Arrears Alert */
        .arrears-alert {
            background: rgba(231, 76, 60, 0.1);
            border: 2px solid var(--danger-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .arrears-alert .amount {
            font-size: 32px;
            font-weight: bold;
            color: var(--danger-color);
        }

        .arrears-alert .label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        /* Reason Tags */
        .reason-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .reason-tag {
            padding: 10px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            color: var(--main-color);
        }

        .reason-tag:hover {
            border-color: var(--accent-color);
            background: rgba(255, 193, 7, 0.1);
        }

        .reason-tag.selected {
            border-color: var(--accent-color);
            background: var(--accent-color);
            color: var(--main-color);
            font-weight: 600;
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
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-dark" style="">

    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">

        <?php if (!empty($error)): ?>
            <div id="flashToastError"
                class="toast align-items-center text-bg-danger border-0"
                role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body small">
                        <?= htmlspecialchars($error) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div id="flashToastSuccess"
                class="toast align-items-center text-bg-success border-0"
                role="alert" aria-live="polite" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body small">
                        <?= htmlspecialchars($success) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!--begin::App Wrapper-->
    <div class="app-wrapper">

        <!--begin::Header-->
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php';
        ?>
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
                    <li class="breadcrumb-item active">Single units</li>
                </ol>
            </nav>
            <div class="container-fluid">
                <!--First Row-->
                <div class="row align-items-center mb-4">
                    <div class="col-12 d-flex align-items-center">
                        <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
                        <h3 class="mb-0 ms-3">Single units</h3>
                    </div>
                </div>
                <div class="row mb-4">

                    <div class="col-md-4 col-sm-6 col-12 d-flex">
                        <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                            <div>
                                <i class="bi bi bi-house-exclamation-fill fs-1 me-3 text-warning"></i>
                                <!-- <i class="bi bi-building fs-1 me-3 text-warning"></i> -->
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Vacant Units</p>
                                <b><?= $totalVacant ?></b>
                            </div>
                        </div>
                    </div>
                    <button class="action-btn vacate-btn" title="Vacate Tenant"
                        onclick='openVacateCanvas()'>
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                    <div class="col-md-4 col-sm-6 col-12 d-flex">
                        <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                            <div>
                                <i class="bi bi-house-lock-fill fs-1 me-3 text-warning"></i>
                                <!-- <i class="bi bi-building fs-1 me-3 text-warning"></i> -->
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Occupied Units</p>
                                <b><?= htmlspecialchars($totalUnderMaintenance); ?></b>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 d-flex">
                        <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                            <div>
                                <i class="fas fa-home fs-1 me-3 text-warning"></i>
                                <!-- <i class="bi bi-building fs-1 me-3 text-warning"></i> -->
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Under Maintenance</p>
                                <b><?= htmlspecialchars($totalOccupied); ?></b>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <!-- Filter by Building -->
                    <div class="col-md-12 col-sm-12">
                        <div class="card border-0 mb-4">
                            <div class="card-body ">
                                <h5 class="card-title mb-3"><i class="fas fa-filter"></i> Filter Buildings</h5>
                                <form method="GET">
                                    <!-- always reset to page 1 when applying filters -->
                                    <input type="hidden" name="page" value="1">

                                    <div class="filters-scroll">
                                        <div class="row g-3 mb-3 filters-row">

                                            <div class="col-auto filter-col">
                                                <label class="form-label text-muted small">Search</label>
                                                <input
                                                    type="text"
                                                    name="search"
                                                    class="form-control"
                                                    placeholder="Unit no..."
                                                    value="">
                                            </div>

                                            <div class="col-auto filter-col">
                                                <label class="form-label text-muted small">Buildings</label>
                                                <select class="form-select shadow-sm" name="building_id">
                                                    <option value="">All Buildings</option>

                                                </select>
                                            </div>

                                            <div class="col-auto filter-col">
                                                <label class="form-label text-muted small">Purpose</label>
                                                <select name="status" class="form-select">
                                                    <option value="" <?= ($status ?? '') === '' ? 'selected' : '' ?>>All Statuses</option>

                                                    <!-- Use values that match your DB exactly -->
                                                    <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                                                    <option value="unpaid" <?= ($status ?? '') === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                                    <option value="overpaid" <?= ($status ?? '') === 'overpaid' ? 'selected' : '' ?>>Overpaid</option>
                                                    <option value="partially paid" <?= ($status ?? '') === 'partially paid' ? 'selected' : '' ?>>Partially Paid</option>
                                                </select>
                                            </div>

                                            <div class="col-auto filter-col">
                                                <label class="form-label text-muted small">Occupancy status</label>
                                                <select name="status" class="form-select">
                                                    <option value="" <?= ($status ?? '') === '' ? 'selected' : '' ?>>All Modes</option>

                                                    <!-- Use values that match your DB exactly -->
                                                    <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                                                    <option value="unpaid" <?= ($status ?? '') === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                                    <option value="overpaid" <?= ($status ?? '') === 'overpaid' ? 'selected' : '' ?>>Overpaid</option>
                                                    <option value="partially paid" <?= ($status ?? '') === 'partially paid' ? 'selected' : '' ?>>Partially Paid</option>
                                                </select>
                                            </div>
                                            <div class="col-auto filter-col">
                                                <label class="form-label text-muted small">Ownership Mode</label>
                                                <select name="status" class="form-select">
                                                    <option value="" <?= ($status ?? '') === '' ? 'selected' : '' ?>>All Modes</option>

                                                    <!-- Use values that match your DB exactly -->
                                                    <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                                                    <option value="unpaid" <?= ($status ?? '') === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                                    <option value="overpaid" <?= ($status ?? '') === 'overpaid' ? 'selected' : '' ?>>Overpaid</option>
                                                    <option value="partially paid" <?= ($status ?? '') === 'partially paid' ? 'selected' : '' ?>>Partially Paid</option>
                                                </select>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end">
                                        <!-- Replace with your real page name -->
                                        <a href="expenses.php" class="btn btn-secondary">
                                            <i class="fas fa-redo"></i> Reset
                                        </a>

                                        <button type="submit" class="actionBtn">
                                            <i class="fas fa-search"></i> Apply Filters
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <?php
                include_once '../processes/encrypt_decrypt_function.php';
                //Submit Single Unit Information
                if (isset($_POST['submit'])) {
                    try {
                        // Insert unit data
                        $stmt = $pdo->prepare("INSERT INTO single_units(unit_number, purpose, building_link, location, monthly_rent, occupancy_status)
                                VALUES (:unit_number, :purpose, :building_link, :location, :monthly_rent, :occupancy_status)");
                        $stmt->execute([
                            ':unit_number'      => $_POST['unit_number'],
                            ':purpose'          => $_POST['purpose'],
                            ':building_link'    => $_POST['building_link'],
                            ':location'         => $_POST['location'],
                            ':monthly_rent'     => (string) $_POST['monthly_rent'], // decimals handled as strings
                            ':occupancy_status' => $_POST['occupancy_status'],
                        ]);

                        $unitId = $pdo->lastInsertId();

                        // Insert recurring expenses if available
                        if (!empty($_POST['bill'])) {
                            $stmtExp = $pdo->prepare("
                                    INSERT INTO single_unit_bills (unit_id, bill, qty, unit_price)
                                    VALUES (:unit_id, :bill, :qty, :unit_price)
                                    ");

                            foreach ($_POST['bill'] as $i => $bill) {
                                if (!empty($bill)) {
                                    $stmtExp->execute([
                                        ':unit_id'    => $unitId,
                                        ':bill'       => $bill,
                                        ':qty'        => (int) $_POST['qty'][$i],
                                        ':unit_price' => (string) $_POST['unit_price'][$i],
                                    ]);
                                }
                            }
                        }

                        echo '<div id="countdown" class="alert alert-success" role="alert"></div>
                            <script>
                            var timeleft = 10;
                            var downloadTimer = setInterval(function(){
                              if(timeleft <= 0){
                                clearInterval(downloadTimer);
                                window.location.href=window.location.href;
                                } else {
                                    document.getElementById("countdown").innerHTML = "Single Unit Information Submitted Successfully! Redirecting in " + timeleft + " seconds remaining";
                                }
                                timeleft -= 1;
                                }, 1000);
                                </script>';
                    } catch (PDOException $e) {
                        echo "❌ Database error: " . $e->getMessage();
                    }
                }

                //Meter Readings Submission PHP Script
                if (isset($_POST['submit_reading'])) {
                    // Collect and sanitize input data
                    $id = trim($_POST['id'] ?? null);
                    $reading_date = trim($_POST['reading_date'] ?? null);
                    $meter_type = trim($_POST['meter_type'] ?? null);
                    $current_reading = trim($_POST['current_reading'] ?? null);
                    $previous_reading = trim($_POST['previous_reading'] ?? null);
                    $units_consumed = trim($_POST['units_consumed'] ?? null);
                    $cost_per_unit = trim($_POST['cost_per_unit'] ?? null);
                    $final_bill = trim($_POST['final_bill'] ?? null);

                    try {
                        //Basic Validations to Ensure that No Required Field is Left Unfilled
                        if (empty($reading_date) || empty($meter_type) || empty($current_reading) || empty($cost_per_unit) || $current_reading < $previous_reading) {
                            echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Invalid Input',
                                        text: 'Please ensure all fields are filled and readings are valid.'
                                    });
                                </script>";
                            exit;
                        } else {
                            //Serverside Computation of the Derived Values
                            $units_consumed = $current_reading - $previous_reading;
                            $final_bill = $units_consumed * $cost_per_unit;

                            //Check if the for Double Entries of Meter Readings for the Same Unit in the Same Month
                            $checkReading = $pdo->prepare("SELECT * FROM building_units WHERE reading_date =:reading_date AND meter_type =:meter_type");
                            $checkReading->execute([
                                ':reading_date' => $reading_date,
                                ':meter_type' => $meter_type
                            ]);
                            if ($checkReading->rowCount() > 0) {
                                echo "
                                        <script>
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Double Reading!',
                                                text: 'Meter Reading for this Month has Already been Submitted!',
                                                width: '600px',
                                                padding: '0.6em',
                                                customClass: {
                                                    popup: 'compact-swal'
                                                },
                                                confirmButtonText: 'OK'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = 'single_units.php';
                                                }
                                            });
                                        </script>";
                                exit;
                            } else {
                                //If no Double Reading, then Submit the Meter Readings for this Month
                                $submitMeterReading = $pdo->prepare("UPDATE building_units SET 
                                        reading_date =:reading_date,
                                        meter_type =:meter_type,
                                        current_reading =:current_reading,
                                        previous_reading =:previous_reading,
                                        units_consumed =:units_consumed,
                                        cost_per_unit =:cost_per_unit,
                                        final_bill =:final_bill 
                                        WHERE
                                        id =:id
                                    ");
                                $submitMeterReading->execute([
                                    ':reading_date' => $reading_date,
                                    ':meter_type' => $meter_type,
                                    ':current_reading' => $current_reading,
                                    ':previous_reading' => $previous_reading,
                                    ':units_consumed' => $units_consumed,
                                    ':cost_per_unit' => $cost_per_unit,
                                    ':final_bill' => $final_bill,
                                    ':id' => $id,
                                ]);

                                echo "
                                    <script>
                                        setTimeout(() => {
                                          Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: 'Meter Reading Submitted Successfully.',
                                            showConfirmButton: true,
                                            confirmButtonText: 'OK'
                                            }).then((result) => {
                                              if (result.isConfirmed) {
                                              window.location.href = 'all_meter_readings.php';
                                            }
                                          });
                                        }, 800); // short delay to smooth transition from loader
                                    </script>";
                            }
                        }
                    } catch (Exception $e) {
                        echo
                        "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Database Error',
                                        text: 'Failed to insert meter reading: " . addslashes($e->getMessage()) . "'
                                    });
                                </script>";
                    }
                }

                //Change the Occupancy Status of the Vacant Unit to Under Maintenance
                if (isset($_POST['update_maintenance_status'])) {
                    try {
                        // Fetch current status of the unit
                        $check = $pdo->prepare("SELECT occupancy_status FROM building_units WHERE id = :id");
                        $check->execute([
                            ':id' => $_POST['id']
                        ]);
                        $current_status = $check->fetchColumn();
                        if ($current_status === $_POST['occupancy_status']) {
                            // No change made
                            echo "
                                <script>
                                    Swal.fire({
                                    title: 'Warning!',
                                    text: 'Update failed. You did not change the status.',
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                    }).then(() => {
                                    window.history.back();
                                    });
                                </script>";
                        } else {
                            // Update with the new status
                            $update = "UPDATE building_units SET occupancy_status = :occupancy_status WHERE id = :id";
                            $stmt = $pdo->prepare($update);
                            $stmt->bindParam(':occupancy_status', $_POST['occupancy_status'], PDO::PARAM_STR);
                            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
                            $stmt->execute();
                            // Success message
                            echo "
                                <script>
                                    Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Occupancy status updated successfully!',
                                    width: '600px',
                                    padding: '0.6em',
                                    customClass: {
                                    popup: 'compact-swal'
                                    },
                                    confirmButtonText: 'OK'
                                    }).then((result) => {
                                    if (result.isConfirmed) {
                                    window.location.href = 'single_units.php';
                                    }
                                    });
                                </script>";
                        }
                    } catch (PDOException $e) {
                        echo "
                                <script>
                                Swal.fire({
                                icon: 'error',
                                title: 'Database Error',
                                text: '" . addslashes($e->getMessage()) . "',
                                confirmButtonText: 'Close'
                                });
                                </script>";
                    }
                }

                //Change the Status to Vacant if the Unit is Occupied
                if (isset($_POST['update_vacant_status'])) {
                    try {
                        // Fetch current status of the unit
                        $check = $pdo->prepare("SELECT occupancy_status FROM building_units WHERE id = :id");
                        $check->execute([
                            ':id' => $_POST['id']
                        ]);
                        $current_status = $check->fetchColumn();
                        if ($current_status === $_POST['occupancy_status']) {
                            // No change made
                            echo "
                                    <script>
                                    Swal.fire({
                                    title: 'Warning!',
                                    text: 'Update failed. You did not change the status.',
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                    }).then(() => {
                                    window.history.back();
                                    });
                                    </script>";
                        } else {
                            // Update with the new status
                            $update = "UPDATE building_units SET occupancy_status = :occupancy_status WHERE id = :id";
                            $stmt = $pdo->prepare($update);
                            $stmt->bindParam(':occupancy_status', $_POST['occupancy_status'], PDO::PARAM_STR);
                            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
                            $stmt->execute();
                            // Success message
                            echo "
                                    <script>
                                        Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Occupancy status updated successfully!',
                                        width: '600px',
                                        padding: '0.6em',
                                        customClass: {
                                        popup: 'compact-swal'
                                        },
                                        confirmButtonText: 'OK'
                                        }).then((result) => {
                                        if (result.isConfirmed) {
                                        window.location.href = 'single_units.php';
                                        }
                                        });
                                    </script>";
                        }
                    } catch (PDOException $e) {
                        echo "
                                <script>
                                Swal.fire({
                                icon: 'error',
                                title: 'Database Error',
                                text: '" . addslashes($e->getMessage()) . "',
                                confirmButtonText: 'Close'
                                });
                                </script>";
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="card-header" style="background-color: #00192D; color:#fff;">
                                    <b>All Single Units (<span class="text-warning">0</span>)</b></b>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover" id="dataTable">
                                        <thead>
                                            <th>Unit No</th>
                                            <th>Building</th>
                                            <th>Purpose</th>
                                            <th>Monthly Rent</th>
                                            <th>Occupancy Status</th>
                                            <th>Added On</th>
                                            <th>Options</th>
                                        </thead>
                                        <tbody>
                                            <?php

                                            try {
                                                // get unit category id
                                                $userId = (int)$_SESSION['user']['id'];

                                                // 2) Fetch landlord id linked to logged-in user
                                                $landlordStmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
                                                $landlordStmt->execute([$userId]);
                                                $landlordId = $landlordStmt->fetchColumn();

                                                if (!$landlordId) {
                                                    throw new Exception("Landlord account not found for this user.");
                                                }

                                                // 3) Get unit category id (single_unit)
                                                $categoryStmt = $pdo->prepare("
                                                            SELECT id
                                                            FROM unit_categories
                                                            WHERE category_name = :category_name
                                                            LIMIT 1
                                                        ");
                                                $categoryStmt->execute([':category_name' => 'single_unit']);
                                                $unitCategoryId = $categoryStmt->fetchColumn();

                                                if (!$unitCategoryId) {
                                                    throw new Exception("Unit category not found.");
                                                }

                                                // 4) Fetch building units for this landlord + category, with building name
                                                $sql = "
                                                                SELECT 
                                                                    bu.*,
                                                                    b.building_name
                                                                FROM building_units bu
                                                                INNER JOIN buildings b ON bu.building_id = b.id
                                                                WHERE bu.unit_category_id = :unit_category_id
                                                                AND bu.landlord_id = :landlord_id
                                                                ORDER BY b.building_name ASC, bu.unit_number ASC
                                                            ";

                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute([
                                                    ':unit_category_id' => (int)$unitCategoryId,
                                                    ':landlord_id'      => (int)$landlordId,
                                                ]);
                                                // $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                // var_dump($rows); // dumps all rows
                                                // exit;

                                                while ($row = $stmt->fetch()) {

                                                    $id = encryptor('encrypt', $row['id']);
                                                    $unit_number = $row['unit_number'];
                                                    $purpose = $row['purpose'];
                                                    $location = $row['location'];
                                                    $monthly_rent = $row['monthly_rent'];
                                                    $occupancy_status = $row['occupancy_status'];
                                                    $created_at = $row['created_at'];
                                                    $building_name = $row['building_name'];
                                            ?>
                                                    <tr>
                                                        <td><i class="bi bi-house-door"></i><?= htmlspecialchars($unit_number) ?></td>
                                                        <td><i class="bi bi-building"></i>
                                                            <?= htmlspecialchars($building_name) ?></td>
                                                        <td>
                                                            <?php
                                                            if (htmlspecialchars($purpose) == 'Business') {
                                                                echo '<i class="bi bi-shop"></i> ' . htmlspecialchars($purpose);
                                                            } else if (htmlspecialchars($purpose) == 'Office') {
                                                                echo '<i class="bi bi-briefcase"></i> ' . htmlspecialchars($purpose);
                                                            } else if (htmlspecialchars($purpose) == 'Residential') {
                                                                echo '<i class="bi bi-file-person"></i> ' . htmlspecialchars($purpose);
                                                            } else if (htmlspecialchars($purpose) == 'Store') {
                                                                echo '<i class="bi bi-house-gear"></i> ' . htmlspecialchars($purpose);
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?= htmlspecialchars('Kshs.' . $monthly_rent) ?></td>
                                                        <td>
                                                            <?php
                                                            if (htmlspecialchars($occupancy_status) == 'Occupied') {
                                                                echo '<button class="btn btn-xs shadow" style="border:1px solid #2C9E4B; color:#2C9E4B;"><i class="fa fa-user"></i> ' . htmlspecialchars($occupancy_status) . '</button>';
                                                            } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                                echo '<button class="btn btn-xs shadow" style="border:1px solid #cc0001; color:#cc0001;"><i class="bi bi-house-exclamation"></i> ' . htmlspecialchars($occupancy_status) . '</button>';
                                                            } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                                echo '<button class="btn btn-xs shadow" style="border:1px solid #F74B00; color:#F74B00;"><i class="fa fa-calendar" ;?=""></i> ' . htmlspecialchars($occupancy_status) . '</button>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><i class="bi bi-calendar"></i>
                                                            <?= htmlspecialchars($created_at) ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default btn-sm shadow" style="border:1px solid rgb(0, 25, 45 ,.3);">Action</button>
                                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown" style="border:1px solid rgb(0, 25, 45 ,.3);"> <span class="sr-only">Toggle Dropdown</span></button>
                                                                <div class="dropdown-menu shadow" role="menu" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                                                    <?php
                                                                    if (htmlspecialchars($occupancy_status) == 'Occupied') {
                                                                    ?>
                                                                        <a class="dropdown-item" href="single_unit_details.php?details=<?php echo $id; ?>"><i class="bi bi-eye"></i> Details</a>
                                                                        <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id; ?>"><i class="bi bi-pen"></i> Edit</a>
                                                                        <a class="dropdown-item btn" data-toggle="modal" data-target="#meterReadingModal<?= $id; ?>"><i class="bi bi-speedometer"></i> Meter Reading</a>
                                                                        <a class="dropdown-item" data-attribute-unitid="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>" href="#" data-toggle="modal" data-target="#markAsVacant<?php echo $id; ?>"><i class="bi bi-house-exclamation"></i> Mark As Vacant</a>
                                                                    <?php
                                                                    } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                                    ?>
                                                                        <a class="dropdown-item" href="single_unit_details.php?details=<?php echo $id; ?>"><i class="bi bi-eye"></i> Details</a>
                                                                        <a class="dropdown-item" href="inspect_single_unit.php?inspect=<?php echo $id; ?>"><i class="bi bi-sliders"></i> Inspect</a>
                                                                        <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id; ?>"><i class="bi bi-pen"></i> Edit</a>
                                                                        <a class="dropdown-item" href="rent_single_unit.php?rent=<?php echo $id; ?>"><i class="bi bi-person-fill-check"></i> Rent It</a>
                                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#underMaintenance<?php echo $id; ?>"><i class="bi bi-house-gear"></i> Under Maintenance</a>
                                                                    <?php
                                                                    } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                                    ?>
                                                                        <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id; ?>"><i class="bi bi-pen"></i> Edit</a>
                                                                        <a class="dropdown-item" href="inspect_single_unit.php?inspect=<?php echo $id; ?>"><i class="bi bi-sliders"></i> Inspect</a>
                                                                        <a class="dropdown-item" href="single_unit_details.php?details=<?php echo $id; ?>"><i class="bi bi-eye"></i> Details</a>
                                                                        <a class="dropdown-item btn" data-toggle="modal" data-target="#meterReadingModal<?= $id; ?>"><i class="bi bi-speedometer"></i> Meter Reading</a>
                                                                        <a class="dropdown-item" href="rent_single_unit.php?rent=<?php echo $id; ?>"><i class="bi bi-person-fill-check"></i> Rent It</a>
                                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#markAsVacant<?php echo $id; ?>"><i class="bi bi-house-exclamation"></i> Mark As Vacant</a>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- Meter Readings Modal -->
                                                    <div class="modal fade shadow" id="meterReadingModal<?= htmlspecialchars($id); ?>">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="background-color:#00192D; color: #fff;">
                                                                    <b>Add Meter Reading for Unit <?= htmlspecialchars($unit_number); ?></b>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                                                                    <!-- Use htmlspecialchars to prevent XSS -->
                                                                    <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>">

                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label for="reading_date_<?= htmlspecialchars($id); ?>">Reading Date</label>
                                                                            <input type="date" class="form-control" name="reading_date" id="reading_date_<?= htmlspecialchars($id); ?>" required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="meter_type_<?= htmlspecialchars($id); ?>">Meter Type</label>
                                                                            <select class="form-control meter_type" name="meter_type" id="meter_type_<?= htmlspecialchars($id); ?>" required>
                                                                                <option value="" selected hidden>Meter Type</option>
                                                                                <option value="Water">Water</option>
                                                                                <option value="Electricity">Electricity</option>
                                                                            </select>
                                                                        </div>

                                                                        <hr>

                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="current_reading_<?= htmlspecialchars($id); ?>">Current Reading</label>
                                                                                    <input type="number" name="current_reading" id="current_reading_<?= htmlspecialchars($id); ?>" placeholder="Current Reading" class="form-control" required>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="previous_reading_<?= htmlspecialchars($id); ?>">Previous Reading</label>
                                                                                    <input type="number" name="previous_reading" id="previous_reading_<?= htmlspecialchars($id); ?>" placeholder="Previous Reading" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <hr>

                                                                        <fieldset class="border p-1">
                                                                            <legend class="w-auto" style="font-size: 18px; font-weight: bold; padding: 3px;">Calculations</legend>

                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label for="units_consumed_<?= htmlspecialchars($id); ?>">Units Consumed</label>
                                                                                        <input type="number" class="form-control" name="units_consumed" id="units_consumed_<?= htmlspecialchars($id); ?>" readonly>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label for="cost_per_unit_<?= htmlspecialchars($id); ?>">Cost Per Unit</label>
                                                                                        <input type="number" class="form-control" name="cost_per_unit" id="cost_per_unit_<?= htmlspecialchars($id); ?>" step="0.01">
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label for="final_bill_<?= htmlspecialchars($id); ?>">Bill</label>
                                                                                <input type="number" class="form-control" name="final_bill" id="final_bill_<?= htmlspecialchars($id); ?>" readonly>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>

                                                                    <div class="modal-footer text-right">
                                                                        <button type="submit" name="submit_reading" class="btn btn-sm btn-outline-dark">
                                                                            <i class="bi bi-send"></i> Submit
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Mark as Vacant Modal -->
                                                    <div class="modal fade shadow" id="markAsVacant<?php echo $id; ?>">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header"
                                                                    style="background-color:#00192D; color: #fff;">
                                                                    <b class="modal-title">Mark Unit <?= htmlspecialchars($row['unit_number']); ?> as Vacant</b>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id"
                                                                            value="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>">
                                                                        <div class="form-group">
                                                                            <label>Mark as Vacant</label>
                                                                            <input class="form-control" id="occupancy_status" name="occupancy_status" value="Vacant" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer text-right">
                                                                        <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="update_vacant_status"><i class="bi bi-send"></i> Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Under Maintenance Modal -->
                                                    <div class="modal fade shadow" id="underMaintenance<?php echo $id; ?>">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header"
                                                                    style="background-color:#00192D; color: #fff;">
                                                                    <p class="modal-title">Mark Unit
                                                                        <?= htmlspecialchars($row['unit_number']); ?> as
                                                                        Under Maintenance</p>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="" method="post" enctype="multipart/form-data"
                                                                    autocomplete="off">
                                                                    <div class="modal-body">
                                                                        <p class="text-center">The Unit Occupancy Status will be Changed to Under Maintenance</p>
                                                                        <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>">
                                                                        <div class="form-group">
                                                                            <label>Occupancy Status</label>
                                                                            <input type="text" class="form-control" id="occupancy_status" name="occupancy_status" value="Under Maintenance" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer text-right">
                                                                        <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;" name="update_maintenance_status"><i class="bi bi-send"></i> Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Rent Single Unit Modal -->
                                                    <div class="modal fade shadow" id="rentUnit<?php echo $id; ?>">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header"
                                                                    style="background-color:#00192D; color: #fff;">
                                                                    <p class="modal-title">Rent Out <?= $unit_number; ?></p>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                                                    <div class="modal-body">
                                                                        <p>Rent out this Unit&hellip;</p>
                                                                    </div>
                                                                    <div class="modal-footer text-right">
                                                                        <button type="submit" class="btn btn-sm" style="border:1px solid #00192D; color: #00192D;">
                                                                            <i class="bi bi-send"></i> Submit
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            } catch (PDOException $e) {
                                                echo '<div class="alert alert-danger>
                                                        Selection Failed! "' . $e->getMessage() . '"
                                                        </div>';
                                            }
                                            ?>
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
        <!-- end::footer -->
    </div>
    <!-- OffCanvas  -->
    <!-- Vacate Tenant Off-canvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="vacateOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"><i class="fas fa-sign-out-alt"></i> Vacate Tenant</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form id="vacateForm">
                <!-- Tenant Information -->
                <div class="tenant-info-card" id="tenantInfo">
                    <!-- Will be populated by JavaScript -->
                </div>

                <!-- Rent Arrears Alert -->
                <div id="arrearsSection">
                    <!-- Will be populated by JavaScript if arrears exist -->
                </div>

                <!-- Unit Information -->
                <div class="mb-4">
                    <h6 style="color: var(--main-color); font-weight: 600; margin-bottom: 15px;">
                        <i class="fas fa-door-open"></i> Unit Details
                    </h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted">Unit Number</small>
                            <div class="fw-bold" id="unitNumber"></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Building</small>
                            <div class="fw-bold" id="buildingName"></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Monthly Rent</small>
                            <div class="fw-bold" id="monthlyRent"></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Move-in Date</small>
                            <div class="fw-bold" id="moveInDate"></div>
                        </div>
                    </div>
                </div>

                <!-- Reason for Vacating -->
                <div class="mb-4">
                    <h6 style="color: var(--main-color); font-weight: 600; margin-bottom: 15px;">
                        <i class="fas fa-clipboard-list"></i> Reason for Vacating *
                    </h6>
                    <div class="reason-tags">
                        <div class="reason-tag" data-reason="Lease Expired" onclick="selectReason(this)">
                            <i class="fas fa-calendar-times"></i> Lease Expired
                        </div>
                        <div class="reason-tag" data-reason="Non-Payment" onclick="selectReason(this)">
                            <i class="fas fa-money-bill-wave"></i> Non-Payment
                        </div>
                        <div class="reason-tag" data-reason="Lease Violation" onclick="selectReason(this)">
                            <i class="fas fa-exclamation-triangle"></i> Lease Violation
                        </div>
                        <div class="reason-tag" data-reason="Voluntary Exit" onclick="selectReason(this)">
                            <i class="fas fa-handshake"></i> Voluntary Exit
                        </div>
                        <div class="reason-tag" data-reason="Property Maintenance" onclick="selectReason(this)">
                            <i class="fas fa-tools"></i> Property Maintenance
                        </div>
                        <div class="reason-tag" data-reason="Other" onclick="selectReason(this)">
                            <i class="fas fa-ellipsis-h"></i> Other
                        </div>
                    </div>
                    <input type="hidden" id="selectedReason" name="reason" required>
                </div>

                <!-- Additional Notes -->
                <div class="mb-4">
                    <label class="form-label" style="color: var(--main-color); font-weight: 600;">
                        <i class="fas fa-comment-alt"></i> Additional Notes
                    </label>
                    <textarea class="form-control" name="notes" rows="4"
                        placeholder="Provide any additional details about the vacation..."></textarea>
                </div>

                <!-- Vacate Date -->
                <div class="mb-4">
                    <label class="form-label" style="color: var(--main-color); font-weight: 600;">
                        <i class="fas fa-calendar"></i> Vacation Date *
                    </label>
                    <input type="date" name="vacate_date" class="form-control" required>
                </div>

                <!-- Security Deposit Balance -->
                <div class="mb-4">
                    <h6 style="color: var(--main-color); font-weight: 600; margin-bottom: 15px;">
                        <i class="fas fa-shield-alt"></i> Security Deposit
                    </h6>
                    <div style="background: rgba(39, 174, 96, 0.1); padding: 20px; border-radius: 10px; border-left: 4px solid var(--success-color);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Current Balance</small>
                                <div style="font-size: 28px; font-weight: bold; color: var(--success-color);" id="depositBalance">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-piggy-bank" style="font-size: 40px; color: var(--success-color); opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Deposit Action -->
                <div class="mb-4">
                    <label class="form-label" style="color: var(--main-color); font-weight: 600;">
                        <i class="fas fa-hand-holding-usd"></i> Security Deposit Action *
                    </label>
                    <select name="deposit_action" class="form-select" required>
                        <option value="">Select Action</option>
                        <option value="refund">Refund in Full</option>
                        <option value="partial">Partial Refund</option>
                        <option value="offset">Offset Against Arrears</option>
                        <option value="retain">Retain for Damages</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-danger btn-lg" onclick="submitVacation()">
                        <i class="fas fa-sign-out-alt"></i> Confirm Vacation
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/main.js"></script>
    <script src="../../../landlord/assets/main.js"></script>


    <script type="module" src="./js/main.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- open the vacate canvas -->
    <script>
        function openVacateCanvas() {
            console.log('yoyo');
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('vacateOffcanvas'));
            offcanvas.show();
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Function to calculate bill for a specific modal
            function calculateBill(modalId) {
                const currentEl = document.getElementById(`current_reading_${modalId}`);
                const previousEl = document.getElementById(`previous_reading_${modalId}`);
                const costEl = document.getElementById(`cost_per_unit_${modalId}`);
                const unitsEl = document.getElementById(`units_consumed_${modalId}`);
                const finalEl = document.getElementById(`final_bill_${modalId}`);

                if (!currentEl || !previousEl || !costEl || !unitsEl || !finalEl) return;

                let current = parseFloat(currentEl.value) || 0;
                let previous = parseFloat(previousEl.value) || 0;
                let cost = parseFloat(costEl.value) || 0;

                // Calculate Units Consumed
                let units = current - previous;
                unitsEl.value = units > 0 ? units : 0;

                // Calculate Final Bill
                let finalBill = units * cost;
                finalEl.value = finalBill.toFixed(2);
            }

            // Attach event listeners to all modals
            document.querySelectorAll('.modal').forEach(modal => {
                const modalId = modal.id.split('meterReadingModal')[1]; // Extract ID from modal

                ['current_reading_', 'previous_reading_', 'cost_per_unit_'].forEach(prefix => {
                    const el = document.getElementById(`${prefix}${modalId}`);
                    if (el) {
                        el.addEventListener('input', () => calculateBill(modalId));
                    }
                });
            });

        });
    </script>


    <!-- plugin for pdf -->
    <!-- Main Js File -->
    <!-- Meter Readings JavaScript -->




    <!-- toast script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const successEl = document.getElementById("flashToastSuccess");
            const errorEl = document.getElementById("flashToastError");

            if (successEl && window.bootstrap) {
                new bootstrap.Toast(successEl, {
                    delay: 8000,
                    autohide: true
                }).show();
            }

            if (errorEl && window.bootstrap) {
                new bootstrap.Toast(errorEl, {
                    delay: 10000,
                    autohide: true
                }).show();
            }
        });
    </script>
</body>
<!--end::Body-->

</html>