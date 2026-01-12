<?php
session_start();
require_once "../db/connect.php";
include_once '../processes/encrypt_decrypt_function.php';
//  include_once 'includes/lower_right_popup_form.php';
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
                    <li class="breadcrumb-item active">Bed Sitter units</li>
                </ol>
            </nav>
            <div class="container-fluid">
                <!--First Row-->
                <div class="row align-items-center mb-4">
                    <div class="col-12 d-flex align-items-center">
                        <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
                        <h3 class="mb-0 ms-3">Bed Sitter units</h3>
                    </div>
                </div>

                <div class="row mb-4">
                    <?php
                    try {
                        // Count Vacant
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM building_units WHERE occupancy_status = 'Vacant'");
                        $stmt->execute();
                        $vacant = $stmt->fetchColumn();
                        // Count Occupied
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM building_units WHERE occupancy_status = 'Occupied'");
                        $stmt->execute();
                        $occupied = $stmt->fetchColumn();
                        // Count Under Maintenance
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM building_units WHERE occupancy_status = 'Under Maintenance'");
                        $stmt->execute();
                        $maintenance = $stmt->fetchColumn();
                    } catch (PDOException $e) {
                        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                    ?>
                    <div class="col-md-4 col-sm-6 col-12 d-flex">
                        <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                            <div>
                                <i class="bi bi bi-house-exclamation-fill fs-1 me-3 text-warning"></i>
                                <!-- <i class="bi bi-building fs-1 me-3 text-warning"></i> -->
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Vacant Units</p>
                                <b><?= $vacant; ?></b>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 d-flex">
                        <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                            <div>
                                <i class="bi bi-house-lock-fill fs-1 me-3 text-warning"></i>
                                <!-- <i class="bi bi-building fs-1 me-3 text-warning"></i> -->
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Occupied Units</p>
                                <b><?= htmlspecialchars($occupied); ?></b>
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
                                <b><?= htmlspecialchars($maintenance); ?></b>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- Container Box -->
                    <div class="card shadow">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <b>All Bed Sitter Units</b>
                                </div>
                                <div class="col-md-6 text-right"></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">

                            <?php
                                            try {
                                                // Fetch all units
                                                $stmt = $pdo->query("SELECT * FROM building_units ORDER BY created_at DESC");
                                                $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            } catch (PDOException $e) {
                                                die("❌ Database error: " . $e->getMessage());
                                            }
                                            ?>
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
                                                        $categoryStmt = $pdo->prepare("
                                                            SELECT id 
                                                            FROM unit_categories 
                                                            WHERE category_name = :category_name
                                                            LIMIT 1
                                                        ");
                                                        $categoryStmt->execute([
                                                            ':category_name' => 'single_unit'
                                                        ]);

                                                        $unitCategoryId = $categoryStmt->fetchColumn();

                                                        if (!$unitCategoryId) {
                                                            throw new Exception('Unit category not found');
                                                        }
                                                        $select = "
                                                                SELECT 
                                                                    bu.*,
                                                                    b.building_name
                                                                FROM building_units bu
                                                                INNER JOIN buildings b 
                                                                    ON bu.building_id = b.id
                                                                WHERE bu.unit_category_id = :unit_category_id
                                                            ";

                                                        $stmt = $pdo->prepare($select);
                                                        $stmt->execute([
                                                            ':unit_category_id' => $unitCategoryId
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
                                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#markAsVacant<?php echo $id; ?>"><i class="bi bi-house-exclamation"></i> Mark As Vacant</a>
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
                                                <div class="modal fade shadow" id="meterReadingModal<?= $id; ?>">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background-color:#00192D; color: #fff;">
                                                                <b>Add Meter Reading for Unit <?= $unit_number; ?></b>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                                                                <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>">
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>Reading Date</label>
                                                                        <input type="date" class="form-control" name="reading_date" id="reading_date" required>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label>Meter Type</label>
                                                                                <select class="form-control meter_type" name="meter_type" required>
                                                                                    <option value="" selected hidden> Meter Type</option>
                                                                                    <option value="Water">Water</option>
                                                                                    <option value="Electricity">Electricity</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Current Reading:</label>
                                                                                <input type="number" name="current_reading" placeholder="Current Reading" required class="form-control" id="current_reading">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Previous Reading:</label>
                                                                                <input type="number" name="previous_reading" placeholder="Previous Reading" class="form-control" id="previous_reading">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>

                                                                    <fieldset class="border p-1">
                                                                        <legend class="w-auto" style="font-size: 18px; font-weight: bold; padding: 3px;">
                                                                            Calculations</legend>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label>Units Consumed:</label>
                                                                                    <input class="form-control" id="units_consumed" name="units_consumed" readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label>Cost Per Unit:</label>
                                                                                    <input class="form-control" id="cost_per_unit" type="text" name="cost_per_unit">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Bill</label>
                                                                            <input class="form-control" id="final_bill" name="final_bill" readonly type="text">
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                                <div class="modal-footer text-right">
                                                                    <button type="submit" name="submit_reading" class="btn btn-sm" style="border:1px solid #00192D;">
                                                                        <i class="bi bi-send"></i> Submit
                                                                    </button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Mark as Vacant Modal -->
                                                <div class="modal fade shadow" id="markAsVacant<?php echo $id; ?>">
                                                    <div class="modal-dialog modal-md">
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
                                                                    <p class="text-center">Change the Unit Occupancy Status to Vacant. This will Prepare the Unit for Another Possible Tenant</p>
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
                                                                    <p class="text-center">Change the Unit Status to Under Maintenance. Meaning there are Some Works Being done on the Unit</p>
                                                                    <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id)); ?>">
                                                                    <div class="form-group">
                                                                        <label>Status</label>
                                                                        <input class="form-control" id="occupancy_status" name="occupancy_status" value="Under Maintenance" readonly>
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
                                <?php
                                //Change the Occupancy Status to Vacant if the Unit is Occupied and when the user clicks Mark as Vacant
                                if (isset($_POST['update_vacant_status'])) {
                                    try {
                                        // Fetch current status of the unit
                                        $check = $pdo->prepare("SELECT occupancy_status FROM bedsitter_units WHERE id = :id");
                                        $check->execute([':id' => $_POST['id']]);
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
                                            $update = "UPDATE bedsitter_units SET occupancy_status = :occupancy_status WHERE id = :id";
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
                                        window.location.href = 'bed_sitter_units.php';
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

                                //Change the Unit Status to Under Maintenance when the User clicks the Relavant Button
                                if (isset($_POST['update_maintenance_status'])) {
                                    try {
                                        // Fetch current status of the unit
                                        $check = $pdo->prepare("SELECT occupancy_status FROM bedsitter_units WHERE id = :id");
                                        $check->execute([':id' => $_POST['id']]);
                                        $current_status = $check->fetchColumn();

                                        // Update with the new status
                                        $update = "UPDATE bedsitter_units SET occupancy_status = :occupancy_status WHERE id = :id";
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
                                    window.location.href = 'bed_sitter_units.php';
                                }
                            });
                        </script>";
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

                                //Submit Meter Reading
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
                                            $checkReading = $pdo->prepare("SELECT * FROM bedsitter_units WHERE reading_date =:reading_date AND meter_type =:meter_type");
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
                                                $submitMeterReading = $pdo->prepare("UPDATE bedsitter_units SET 
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
                                        window.location.href = 'bed_sitter_units.php';
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
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- Container Box -->
                </section>

            </div>
        </main>
        <!--end::App Main-->
        
        <!--begin::Footer-->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
        <!-- end::footer -->

    </div>
    <!--end::App Wrapper-->

    <!-- plugin for pdf -->


    <!-- Main Js File -->
    <script src="../../js/adminlte.js"></script>
    <script src="js/main.js"></script>
    <script src="../../../landlord/assets/main.js"></script>

    <!-- html2pdf depends on html2canvas and jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script type="module" src="./js/main.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <!-- pdf download plugin -->

    <!-- Help Pop Up Form -->

    </div>

    <!-- Footer -->
    <?php include_once '../includes/footer.php'; ?>

    </div>

    <!-- Required Scripts -->
    <?php include_once '../includes/required_scripts.php'; ?>


    <!-- Scripts -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<!--end::Body-->

</html>