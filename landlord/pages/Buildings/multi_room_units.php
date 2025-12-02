<?php
 require_once "../db/connect.php";
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
        <main class="app-main mt-4 mx-2 my-2">
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <b>Overview</b>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-home"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Multi-Room Units</span>
                                                <span class="info-box-number">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-users"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Occupied Units</span>
                                                <span class="info-box-number">0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-home"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Vacant Units</span>
                                                <span class="info-box-number">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        include_once 'processes/encrypt_decrypt_function.php';
                        if(isset($_POST['submit_bed_sitter_btn'])) {
                            try{
                                        // Insert unit data 
                                $stmt = $pdo->prepare("
                                    INSERT INTO multi_rooms (unit_number, purpose, building_link, location, water_meter, monthly_rent, occupancy_status)
                                    VALUES (:unit_number, :purpose, :building_link, :location, :water_meter, :monthly_rent, :occupancy_status)");
                                $stmt->execute([
                                    ':unit_number'      => $_POST['unit_number'],
                                    ':purpose'          => $_POST['purpose'],
                                    ':building_link'    => $_POST['building_link'],
                                    ':location'         => $_POST['location'],
                                        ':water_meter'     => (string) $_POST['water_meter'], // decimals handled as strings
                                        ':monthly_rent' => (string) $_POST['monthly_rent'],
                                        ':occupancy_status' => $_POST['occupancy_status'],
                                    ]);

                                $unitId = $pdo->lastInsertId();

                                        // Insert recurring expenses if available
                                if (!empty($_POST['bill'])) {
                                    $stmtExp = $pdo->prepare("
                                        INSERT INTO multi_roombills (unit_id, bill, qty, unit_price)
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
                                        document.getElementById("countdown").innerHTML = "Bed Seater Unit Information Submitted Successfully! Redirecting in " + timeleft + " seconds remaining";
                                    }
                                    timeleft -= 1;
                                    }, 1000);
                                    </script>';
                                } catch(PDOException $e){
                                    echo "❌ Database error: " . $e->getMessage();
                                }
                            }
                            ?>
                            <!-- Container Box -->
                            <div class="card shadow">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <b>All Multi Room Units</b>
                                        </div>
                                        <div class="col-md-6 text-right">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="dataTable">
                                            <thead>
                                                <th>Unit No</th>
                                                <th>Building</th>
                                                <th>Purpose</th>
                                                <th>Monthly Rent</th>
                                                <th>Water Meter</th>
                                                <th>Occupancy Status</th>
                                                <th>Added On</th>
                                                <th>Options</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                try{
                                                    $select = "SELECT * FROM multi_rooms_units";
                                                    $stmt = $pdo->prepare($select);
                                                    $stmt->execute();
                                                    while($row = $stmt->fetch()){
                                                        $id = encryptor('encrypt', $row['id']);
                                                        $unit_number = $row['unit_number'];
                                                        $building_link = $row['building_link'];
                                                        $purpose = $row['purpose'];
                                                        $location = $row['location'];
                                                        $monthly_rent = $row['monthly_rent'];
                                                        $water_meter = $row['water_meter'];
                                                        $occupancy_status = $row['occupancy_status'];
                                                        $created_at = $row['created_at'];
                                                        ?>
                                                        <tr>
                                                            <td><i class="bi bi-house-door"></i><?= htmlspecialchars($unit_number)?></td>
                                                            <td><i class="bi bi-building"></i>
                                                                <?= htmlspecialchars($building_link)?></td>
                                                                <td>
                                                                    <?php
                                                                    if(htmlspecialchars($purpose) == 'Business') {
                                                                        echo '<i class="bi bi-shop"></i> ' .htmlspecialchars($purpose);
                                                                    } else if (htmlspecialchars($purpose) == 'Office') {
                                                                        echo '<i class="bi bi-briefcase"></i> ' .htmlspecialchars($purpose);
                                                                    } else if (htmlspecialchars($purpose) == 'Residential') {
                                                                        echo '<i class="bi bi-file-person"></i> ' .htmlspecialchars($purpose);
                                                                    } else if (htmlspecialchars($purpose) == 'Store') {
                                                                        echo '<i class="bi bi-house-gear"></i> ' .htmlspecialchars($purpose);
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?= htmlspecialchars('Kshs.'. $monthly_rent)?></td>
                                                                <td><?= htmlspecialchars($water_meter)?></td>
                                                                <td>
                                                                    <?php
                                                                    if(htmlspecialchars($occupancy_status) == 'Occupied') {
                                                                        echo '<button class="btn btn-xs shadow" style="border:1px solid #2C9E4B; color:#2C9E4B;"><i class="fa fa-user"></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                                    } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                                        echo '<button class="btn btn-xs shadow" style="border:1px solid #cc0001; color:#cc0001;"><i class="bi bi-house-exclamation"></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                                    } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                                        echo '<button class="btn btn-xs shadow" style="border:1px solid #F74B00; color:#F74B00;"><i class="fa fa-calendar" ;?=""></i> '.htmlspecialchars($occupancy_status).'</button>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><i class="bi bi-calendar"></i>
                                                                    <?= htmlspecialchars($created_at)?>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default btn-sm shadow" style="border:1px solid rgb(0, 25, 45 ,.3);">Action</button>
                                                                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown" style="border:1px solid rgb(0, 25, 45 ,.3);"> <span class="sr-only">Toggle Dropdown</span></button>
                                                                        <div class="dropdown-menu shadow" role="menu" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                                                            <?php
                                                                            if(htmlspecialchars($occupancy_status) == 'Occupied') {
                                                                                ?>
                                                                                <a class="dropdown-item" href="bed_seater_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                                <a class="dropdown-item" href="edit_bed_seater.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                                <a class="dropdown-item btn" data-toggle="modal" data-target="#meterReadingModal<?= $id ;?>"><i class="bi bi-speedometer"></i> Meter Reading</a>
                                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#markAsVacant<?php echo $id;?>"><i class="bi bi-house-exclamation"></i> Mark As Vacant</a>
                                                                                <?php
                                                                            } else if (htmlspecialchars($occupancy_status) == 'Vacant') {
                                                                                ?>
                                                                                <a class="dropdown-item" href="edit_bed_seater.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                                <a class="dropdown-item" href="rent_multi_room_unit.php?rent=<?php echo $id ;?>"><i class="bi bi-person-fill-check"></i> Rent It</a>
                                                                                <a class="dropdown-item" href="inspect_multi_room_unit.php?inspect=<?php echo $id;?>"><i class="bi bi-sliders"></i> Inspect</a>
                                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                                <a class="dropdown-item" href="rent_multi_room_unit.php?rent=<?php echo $id;?>"><i class="bi bi-person-fill-check"></i> Rent It</a>
                                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#underMaintenance<?php echo $id;?>"><i class="bi bi-house-gear"></i> Under Maintenance</a>
                                                                                <?php
                                                                            } else if (htmlspecialchars($occupancy_status) == 'Under Maintenance') {
                                                                                ?>
                                                                                <a class="dropdown-item" href="edit_single_unit_details.php?edit=<?php echo $id;?>"><i class="bi bi-pen"></i> Edit</a>
                                                                                <a class="dropdown-item" href="inspect_single_unit.php?inspect=<?php echo $id;?>"><i class="bi bi-sliders"></i> Inspect</a>
                                                                                <a class="dropdown-item" href="single_unit_details.php?details=<?php echo $id;?>"><i class="bi bi-eye"></i> Details</a>
                                                                                <a class="dropdown-item btn" data-toggle="modal" data-target="#meterReadingModal<?= $id ;?>"><i class="bi bi-speedometer"></i> Meter Reading</a>
                                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#occupiedModal<?php echo $id;?>"><i class="bi bi-person-fill-check"></i> Rent It</a>
                                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#markAsVacant<?php echo $id;?>"><i class="bi bi-house-exclamation"></i> Mark As Vacant</a>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <!-- Meter Readings Modal -->
                                                            <div class="modal fade shadow" id="meterReadingModal<?= $id ;?>">
                                                                <div class="modal-dialog modal-md">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header" style="background-color:#00192D; color: #fff;">
                                                                            <b>Add Meter Reading for Unit <?= $unit_number;?></b>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <label>Reading Date</label>
                                                                                    <input type="date" class="form-control" name="reading_date" id="reading_date" required>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label>Unit Number</label>
                                                                                            <input class="form-control unit_number" name="unit_number" value="<?= $unit_number ;?>" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
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
                                                                                            <label>Previous Reading:</label>
                                                                                            <input type="number" name="previous_reading" placeholder="Previous Reading" class="form-control previous_reading">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label>Current Reading:</label>
                                                                                            <input type="number" name="current_reading" placeholder="Current Reading" requiredclass="form-control current_reading">
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
                                                                                                <input class="form-control consumption_units" name="consumption_units" readonly>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Cost Per Unit:</label>
                                                                                                <input class="form-control consumption_cost" type="text" name="consumption_cost">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>Bill</label>
                                                                                        <input class="form-control final_bill" name="final_bill" readonly type="text">
                                                                                    </div>
                                                                                    <input type="hidden" name="building_id" value="<?= $building_link;?>">
                                                                                    <input type="hidden" name="created_at">
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
                                                        </div>

                                                        <!-- Mark as Vacant Modal -->
                                                        <div class="modal fade shadow" id="markAsVacant<?php echo $id;?>">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content">
                                                                    <div class="modal-header"
                                                                    style="background-color:#00192D; color: #fff;">
                                                                    <b class="modal-title">Mark Unit <?= htmlspecialchars($row['unit_number']);?> as Vacant</b>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close" style="color:#fff;">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                                                <div class="modal-body">
                                                                    <p class="text-center">Change the Unit Occupancy Status to Vacant. This will Prepare the Unit for Another Possible Tenant</p>
                                                                    <input type="hidden" name="id"
                                                                    value="<?= htmlspecialchars(encryptor('decrypt', $id));?>">
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
                                                <div class="modal fade shadow" id="underMaintenance<?php echo $id;?>">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header"
                                                            style="background-color:#00192D; color: #fff;">
                                                            <p class="modal-title">Mark Unit
                                                                <?= htmlspecialchars($row['unit_number']);?> as
                                                            Under Maintenance</p>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="" method="post" enctype="multipart/form-data"
                                                        autocomplete="off">
                                                        <div class="modal-body">
                                                            <p class="text-center">Change the Unit Status to Under Maintenance. Meaning there are Some Works Being done on the Unit</p>
                                                            <input type="hidden" name="id" value="<?= htmlspecialchars(encryptor('decrypt', $id));?>">
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
                                        <div class="modal fade shadow" id="rentUnit<?php echo $id;?>">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header"
                                                    style="background-color:#00192D; color: #fff;">
                                                    <p class="modal-title">Rent Out <?= $unit_number;?></p>
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
                        }catch(PDOException $e){
                            echo '<div class="alert alert-danger>
                            Selection Failed! "'.$e->getMessage().'"
                            </div>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Container Box -->

    </section>

    <?php
    //This is the PHP Script that will change the occupancy Status when one clicks Rent Out
    if(isset($_POST['update_occupied_status'])) {
        $id = encryptor('encrypt', $_POST['id']);
        try {
            // Update with the new status
            $update = "UPDATE multi_rooms_units SET occupancy_status = :occupancy_status WHERE id = :id";
            $stmtUpdate = $pdo->prepare($update);
            $stmtUpdate->bindParam(':occupancy_status', $_POST['occupancy_status'], PDO::PARAM_STR);
            $stmtUpdate->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
            $stmtUpdate->execute();

            if($stmtUpdate->rowCount() > 0) {
                echo "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Unit marked as Occupied successfully. Click OK to Rent it Out',
                            confirmButtonText: 'OK'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'rent_multi_room_unit.php?rent={$id}';
                            }
                        });
                    </script>";
            } else {
                echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Database error: " . addslashes($e->getMessage()) . "',
                            confirmButtonText: 'OK'
                        });
                    </script>";
            }
        } catch (Exception $e) {
            echo "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Database Error',
                        text: '".addslashes($e->getMessage())."',
                        confirmButtonText: 'Close'
                    });
                </script>";
        }
    }

    //Change the Occupancy Status to Vacant if the Unit is Occupied and when the user clicks Mark as Vacant
    if(isset($_POST['update_vacant_status'])) {
        try {
            // Fetch current status of the unit
            $check = $pdo->prepare("SELECT occupancy_status FROM multi_rooms_units WHERE id = :id");
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
                $update = "UPDATE multi_rooms_units SET occupancy_status = :occupancy_status WHERE id = :id";
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
                                window.location.href = 'multi_room_units.php';
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
                    text: '".addslashes($e->getMessage())."',
                    confirmButtonText: 'Close'
                    });
                </script>";
            }
        }

        //Change the Unit Status to Under Maintenance when the User clicks the Relavant Button
        if(isset($_POST['update_maintenance_status'])) {
            try {
                // Fetch current status of the unit
                $check = $pdo->prepare("SELECT occupancy_status FROM multi_rooms_units WHERE id = :id");
                $check->execute([':id' => $_POST['id']]);
                $current_status = $check->fetchColumn();
                
                // Update with the new status
                $update = "UPDATE multi_rooms_units SET occupancy_status = :occupancy_status WHERE id = :id";
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
                                window.location.href = 'multi_room_units.php';
                            }
                        });
                    </script>";
            } catch (PDOException $e) {
                echo "
                    <script>
                        Swal.fire({
                        icon: 'error',
                        title: 'Database Error',
                        text: '".addslashes($e->getMessage())."',
                        confirmButtonText: 'Close'
                        });
                    </script>";
            }
        }
    ?>
            </div>
        </main>
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
    <script src="../../../landlord/assets/main.js"></script>

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