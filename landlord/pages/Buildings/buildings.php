<?php

require_once "../db/connect.php"
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
        <main class="app-main mx-2 mt-4">
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <?php
                    include_once 'processes/encrypt_decrypt_function.php';
                    //nclude_once 'db_connection.php'; // ensure PDO $conn is here

                    if (isset($_POST['submit_building'])) {
                        $tm = md5(time()); // Unique prefix for uploaded files

                        // ---------- File Upload Handling ----------
                        function uploadFile($fileKey, $tm)
                        {
                            if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                                $name = basename($_FILES[$fileKey]['name']);
                                $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $name); // sanitize
                                $destination = "all_uploads/" . $tm . "_" . $safeName;
                                move_uploaded_file($_FILES[$fileKey]['tmp_name'], $destination);
                                return $destination;
                            }
                            return null;
                        }

                        $ownership_proof_destination = uploadFile('ownership_proof', $tm);
                        $title_deed_destination      = uploadFile('title_deed', $tm);
                        $legal_document_destination  = uploadFile('legal_document', $tm);
                        $photo_one_destination       = uploadFile('photo_one', $tm);
                        $photo_two_destination       = uploadFile('photo_two', $tm);
                        $photo_three_destination     = uploadFile('photo_three', $tm);
                        $photo_four_destination      = uploadFile('photo_four', $tm);

                        // ---------- Input Fields ----------
                        $building_name   = $_POST['building_name'] ?? null;
                        $county          = $_POST['county'] ?? null;
                        $constituency    = $_POST['constituency'] ?? null;
                        $ward            = $_POST['ward'] ?? null;
                        $structure_type  = $_POST['structure_type'] ?? null;
                        $floors_no       = $_POST['floors_no'] ?? null;
                        $no_of_units     = $_POST['no_of_units'] ?? null;
                        $building_type   = $_POST['building_type'] ?? null;
                        $tax_rate        = $_POST['tax_rate'] ?? null;
                        $ownership_info  = $_POST['ownership_info'] ?? null;
                        $first_name      = $_POST['first_name'] ?? null;
                        $last_name       = $_POST['last_name'] ?? null;
                        $id_number       = $_POST['id_number'] ?? null;
                        $primary_contact = $_POST['primary_contact'] ?? null;
                        $other_contact   = $_POST['other_contact'] ?? null;
                        $owner_email     = $_POST['owner_email'] ?? null;
                        $postal_address  = $_POST['postal_address'] ?? null;
                        $entity_name     = $_POST['entity_name'] ?? null;
                        $entity_phone    = $_POST['entity_phone'] ?? null;
                        $entity_phoneother = $_POST['entity_phoneother'] ?? null;
                        $entity_email    = $_POST['entity_email'] ?? null;
                        $entity_rep      = $_POST['entity_rep'] ?? null;
                        $rep_role        = $_POST['rep_role'] ?? null;
                        $entity_postal   = $_POST['entity_postal'] ?? null;
                        $confirm         = $_POST['confirm'] ?? 0;

                        // ---------- Amenities ----------
                        $amenities = $_POST['amenities'] ?? [];
                        $amenities_json = json_encode($amenities, JSON_UNESCAPED_UNICODE);

                        try {
                            $sql = "INSERT INTO buildings (building_name, county, constituency, ward, structure_type, floors_no, no_of_units, building_type, tax_rate, ownership_info, first_name, last_name, id_number, primary_contact, other_contact, owner_email, postal_address, entity_name, entity_phone, entity_phoneother, entity_email, entity_rep, rep_role, entity_postal, ownership_proof, title_deed, legal_document, utilities, photo_one, photo_two, photo_three, photo_four, confirm) VALUES (:building_name, :county, :constituency, :ward, :structure_type, :floors_no, :no_of_units, :building_type, :tax_rate, :ownership_info, :first_name, :last_name, :id_number, :primary_contact, :other_contact, :owner_email, :postal_address, :entity_name, :entity_phone, :entity_phoneother, :entity_email, :entity_rep, :rep_role, :entity_postal, :ownership_proof, :title_deed, :legal_document, :utilities, :photo_one, :photo_two, :photo_three, :photo_four, :confirm)";

                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':building_name'   => $building_name,
                                ':county'          => $county,
                                ':constituency'    => $constituency,
                                ':ward'            => $ward,
                                ':structure_type'  => $structure_type,
                                ':floors_no'       => $floors_no,
                                ':no_of_units'     => $no_of_units,
                                ':building_type'   => $building_type,
                                ':tax_rate'        => $tax_rate,
                                ':ownership_info'  => $ownership_info,
                                ':first_name'      => $first_name,
                                ':last_name'       => $last_name,
                                ':id_number'       => $id_number,
                                ':primary_contact' => $primary_contact,
                                ':other_contact'   => $other_contact,
                                ':owner_email'     => $owner_email,
                                ':postal_address'  => $postal_address,
                                ':entity_name'     => $entity_name,
                                ':entity_phone'    => $entity_phone,
                                ':entity_phoneother' => $entity_phoneother,
                                ':entity_email'    => $entity_email,
                                ':entity_rep'      => $entity_rep,
                                ':rep_role'        => $rep_role,
                                ':entity_postal'   => $entity_postal,
                                ':ownership_proof' => $ownership_proof_destination,
                                ':title_deed'      => $title_deed_destination,
                                ':legal_document'  => $legal_document_destination,
                                ':utilities'       => $amenities_json,
                                ':photo_one'       => $photo_one_destination,
                                ':photo_two'       => $photo_two_destination,
                                ':photo_three'     => $photo_three_destination,
                                ':photo_four'      => $photo_four_destination,
                                ':confirm'         => $confirm,
                            ]);

                            $building_id = $pdo->lastInsertId();

                            echo "
                        <script>
                        Swal.fire({
                            title: 'Success!',
                            text: 'Building registered successfully! ID: $building_id',
                            icon: 'success',
                            confirmButtonText: 'OK'
                            }).then(() => {
                                document.getElementById('buildingForm').reset();
                                });
                                </script>";
                        } catch (PDOException $e) {
                            echo "
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                <script>
                                Swal.fire({
                                    title: 'Database Error!',
                                    text: '" . addslashes($e->getMessage()) . "',
                                    icon: 'error',
                                    confirmButtonText: 'Close'
                                    });
                                    </script>";
                        }
                    }

                    ?>
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
                            <?php
                            $count_buildings = "SELECT building_type, COUNT(*) AS total FROM buildings GROUP BY building_type";
                            $result = $pdo->prepare($count_buildings);
                            $result->execute();
                            //Initialize the countings for all the buildings
                            $counts = [
                                'Residential' => 0,
                                'Commercial'   => 0,
                                'Industrial'   => 0,
                                'Mixed-Use'    => 0,
                                'Ware House'   => 0
                            ];
                            while ($row = $result->fetch()) {
                                $counts[$row['building_type']] = $row['total'];
                            }

                            // Assign icons for each building type
                            $icons = [
                                'Residential' => 'bi-house-door-fill',
                                'Commercial'   => 'bi-shop',
                                'Industrial'   => 'bi-building-gear',
                                'Mixed-Use'    => 'bi-buildings',
                                'Ware House'   => 'bi-box-seam'
                            ];
                            ?>
                            <div class="row g-3">
                                <?php foreach ($counts as $type => $total): ?>
                                    <div class="col-md-3">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="bi  <?php echo $icons[$type]; ?>"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text"><?php echo $type; ?></span>
                                                <span class="info-box-number"><?php echo $total; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card shadow-sm">
                        <div class="card-header" style="background-color: #00192D;">
                            <div class="row">
                                <div class="col-md-6 mt-2"><b class="text-white">Registered Buildings (1000)</b></div>
                                <div class="col-md-6 text-right mt-2">
                                    <button class="btn btn-sm" style="border: 1px solid #fff; color:#fff; font-weight:bold;" data-toggle="modal" data-target="#addBuildingModal"><i class="fas fa-building"></i>
                                        Add Building</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable">
                                    <thead>
                                        <th>Building</th>
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th>No. of Units</th>
                                        <th>Ownership Mode</th>
                                        <th>Reg. Date</th>
                                        <th>Options</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $show_buildings = "SELECT * FROM buildings";
                                        $results_show_buildings = $pdo->prepare($show_buildings);
                                        $results_show_buildings->execute();
                                        while ($row = $results_show_buildings->fetch()) {
                                            $id = encryptor('encrypt', $row['id']);
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
                                        ?>
                                            <tr>
                                                <td><i class="fas fa-building"></i> <?php echo $building_name; ?></td>
                                                <td>
                                                    <?php
                                                    if ($structure_type == 'High Rise') {
                                                    ?>
                                                        <i class="fas fa-bars"></i> <?php echo $structure_type; ?>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <i class="fas fa-home"></i> <?php echo $structure_type; ?>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($building_type == 'Residential') {
                                                    ?>
                                                        <button class="btn btn-sm" style="border: 1px solid rgb(0, 25, 45);"><i class="fas fa-hotel"></i> <?php echo $building_type; ?></button>
                                                    <?php
                                                    } else if ($building_type == 'Commercial') {
                                                    ?>
                                                        <button class="btn btn-sm" style="border: 1px solid rgb(0, 25, 45);"><i class="fas fa-building"></i> <?php echo $building_type; ?></button>
                                                    <?php
                                                    } else if ($building_type == 'Industrial') {
                                                    ?>
                                                        <button class="btn btn-sm" style="border: 1px solid rgb(0, 25, 45);"><i class="fas fa-industry"></i> <?php echo $building_type; ?></button>
                                                    <?php
                                                    } else if ($building_type == 'Ware House') {
                                                    ?>
                                                        <button class="btn btn-sm" style="border: 1px solid rgb(0, 25, 45);"><i class="fas fa-bank"></i> <?php echo $building_type; ?></button>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <button class="btn btn-sm" style="border: 1px solid rgb(0, 25, 45);"><i class="fas fa-home"></i> <?php echo $building_type; ?></button>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><i class="fas fa-home"></i> <?php echo $no_of_units; ?></td>
                                                <td>
                                                    <?php
                                                    if ($ownership_info == 'Individual') {
                                                    ?>
                                                        <i class="fa fa-user-circle" style="font-size:1.5rem;"></i>
                                                        <?php echo $ownership_info; ?>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <i class="fa fa-users" style="font-size:1.5rem;"></i>
                                                        <?php echo $ownership_info; ?>
                                                    <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><i class="fa fa-calendar"></i> <?php echo $added_on; ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-sm" style="border:1px solid rgb(0, 25, 45 ,.3);">Action</button>
                                                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon btn-sm" data-toggle="dropdown" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu shadow" role="menu" style="border:1px solid rgb(0, 25, 45 ,.3);">
                                                            <a href="add_single_unit.php?add_single_unit=<?php echo $id; ?>" class="dropdown-item" onclick="return confirmAddUnit(event, '<?php echo $building_name; ?>')"><i class="bi bi-house"></i> Add Single Unit</a>
                                                            <a href="add_bed_sitter.php?add_bed_sitter=<?php echo $id; ?>" class="dropdown-item" onclick="return confirmAddBedsitter(event, '<?php echo $building_name; ?>')"> <i class="bi bi-house"></i> Add Bedsitter</a>
                                                            <a href="add_multi_rooms.php?add_multi_rooms=<?php echo $id; ?>" class="dropdown-item" onclick="return confirmAddMultiRooms(event, '<?php echo $building_name; ?>')"> <i class="bi bi-houses"></i> Add Multi Rooms
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <!-- Edit Building Modal -->
                                                </td>
                                            <?php
                                        }
                                            ?>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Register New Building Modal Popup -->
                    <div class="modal fade" id="addBuildingModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#00192D; color:#fff;">
                                    <b class="modal-title">Add New Building</b>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="buildingForm" method="POST" enctype="multipart/form-data" action="">
                                        <!-- First Section -->
                                        <div class="card shadow" id="firstSection" style="border:1px solid rgb(0,25,45,.2);">
                                            <div class="card-header" style="background-color: #00192D; color:#fff;">
                                                <b>Building Identification</b>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Building Name</label> <sup class="text-danger"><b>*</b></sup>
                                                            <input type="text" class="form-control" id="building_name" name="building_name" placeholder="Building Name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <h5 class="text-center" style="font-weight: bold;">Location Information</h5>
                                                <div class="row">
                                                    <div class="col-12 col-sm-4">
                                                        <div class="form-group">
                                                            <label>County</label>
                                                            <select class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;" id="county" name="county" onchange="FetchConstituency(this.value)">
                                                                <option value="" hidden selected>-- Select County --
                                                                </option>
                                                                <?php
                                                                try {
                                                                    $select_county = "SELECT * FROM county ORDER BY id ASC";
                                                                    $result = $pdo->prepare($select_county);
                                                                    $result->execute();

                                                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                                ?>
                                                                        <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                                                            <?php echo htmlspecialchars($row['name']); ?>
                                                                        </option>
                                                                    <?php
                                                                    }
                                                                } catch (PDOException $e) {
                                                                    // Log error (optional)
                                                                    error_log("Database Error: " . $e->getMessage());
                                                                    // Show a fallback option instead of crashing
                                                                    ?>
                                                                    <option value="">Unable to load counties</option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>


                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Constituency</label>
                                                            <select class="form-control select2 select2-danger" name="constituency" id="constituency" data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="FetchWard(this.value)">
                                                                <option value="" selected hidden>-- Select Constituency --
                                                                </option>
                                                            </select>
                                                            <b class="errorMessages" id="constituencyError"></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Ward</label>
                                                            <select class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" style="width: 100%;" name="ward" id="ward">
                                                                <option value="" selected hidden>-- Choose Ward --</option>
                                                            </select>
                                                            <b class="errorMessages" id="wardError"></b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Structural Type</label>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="radio" id="highRise" name="structure_type" data-toggle="modal" data-target="#specifyFloors" value="High Rise">
                                                                        <label for="highRise">High Rise</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="radio" id="lowStructure" name="structure_type" value="Low Structure">
                                                                        <label for="lowStructure">Low Structure</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal fade shadow" style="margin-top:150px;" id="specifyFloors">
                                                            <div class="modal-dialog modal-sm">
                                                                <div class="modal-content">
                                                                    <div class="modal-header" style="background-color:#00192D; color:#fff;">
                                                                        <b class="modal-title">Number of Floors</b>
                                                                        <button type="button" class="close" onclick="closeFloorsSpecify()" style="color:#fff;">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group" id="floorsNo">
                                                                            <input type="text" class="form-control" id="floors_no" name="floors_no" placeholder="Number of Floors">
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer text-right">
                                                                        <button class="btn btn-sm" onclick="closeFloorsSpecify();" type="button" style="background-color:#cc0001;color:#fff;">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Number of Units</label>
                                                            <input type="text" class="form-control" id="no_of_units" name="no_of_units" placeholder="Number of Units">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Building Type</label>
                                                        <select id="building_type" name="building_type" class="form-control">
                                                            <option value="" selected hidden>--Select Building
                                                                Type--</option>
                                                            <option value="Residential">Residential</option>
                                                            <option value="Commercial">Commercial</option>
                                                            <option value="Industrial">Industrial</option>
                                                            <option value="Mixed-Use">Mixed-Use</option>
                                                            <option value="Mixed-Use">Ware House</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group">
                                                    <label>Tax Rate(%)</label>
                                                    <input type="text" class="form-control" name="tax_rate" id="tax_rate" placeholder="Tax Rate">
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="button" class="btn btn-sm next-btn" id="firstSectionNexttBtn">Next</button>
                                            </div>
                                        </div>
                                        <div class="card shadow" id="secondSection" style="border:1px solid rgb(0,25,45,.2); display:none;">
                                            <div class="card-header" style="background-color:rgb(0,25,45); color:#fff;">
                                                Ownership Information</div>
                                            <div class="card-body">
                                                <div class="form-group text-center">
                                                    <label>Ownership Mode</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="radio" name="ownership_info" id="ownership_type" data-toggle="modal" data-target="#individual-owner" value="Individual"> Individual
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="radio" name="ownership_info" id="ownership_type" data-toggle="modal" data-target="#entity-owner" value="Entity"> Entity
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Individual Ownership Information Modal -->
                                                <div class="modal fade mt-2 shadow p-2" id="individual-owner">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <b>Enter Individual's Information</b>
                                                                <button type="button" class="close" onclick="closeIndividualOwnerInfo()" aria-label="Close" style="color:#000;">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>First Name</label>
                                                                            <input type="text" class="form-control" id="firstName" placeholder="First Name" name="first_name">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Last Name</label>
                                                                            <input type="text" class="form-control" id="lastName" placeholder="Last Name" name="last_name">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Identification Number</label>
                                                                    <input type="text" class="form-control" id="id_number" name="id_number">
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Primary Contact</label>
                                                                            <input type="text" class="form-control" id="primary_contact" name="primary_contact" placeholder="Phone Number">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Other Contact</label>
                                                                            <input type="text" class="form-control" id="other_contact" name="other_contact" placeholder="Other Number">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input type="text" class="form-control" id="ownerEmail" placeholder="Email" name="owner_email">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Postal Address </label><sup>(Optional)</sup>
                                                                    <input type="text" class="form-control" id="postal_address" name="postal_address" placeholder="Postal Address">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer text-right">
                                                                <button type="button" class="btn btn-sm" onclick="closeIndividualOwnerInfo()" style="background-color:#cc0001; color: #fff;">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Entity Ownership Modal -->
                                                <div class="modal fade" id="entity-owner">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <b>Enter Entity's Information</b>
                                                                <button type="button" class="close" onclick="closeEntityOwnership()" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Entity Name</label>
                                                                            <input type="text" class="form-control" id="entityName" name="entity_name" placeholder="Entity Name">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Primary Contact</label>
                                                                            <input type="text" class="form-control" id="entity_phone" name="entity_phone" placeholder="Primary Contact">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Other Contact</label>
                                                                            <input type="text" class="form-control" id="entity_phoneother" name="entity_phoneother" placeholder="Other Contact">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Official Email</label>
                                                                    <input type="text" class="form-control" id="entityEmail" placeholder="Entity Email" name="entity_email">
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Entity Representative</label>
                                                                            <input type="text" class="form-control" id="entityRepresentative" placeholder="Entity Representative" name="entity_rep">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Role</label>
                                                                            <select id="entityRepRole" class="form-control" name="rep_role">
                                                                                <option value="" selected hidden> --Select
                                                                                    Role --</option>
                                                                                <option value="CEO">CEO</option>
                                                                                <option value="Treasury">Treasury</option>
                                                                                <option value="Board Member">BoardMember
                                                                                </option>
                                                                                <option value="Signatory">Signatory</option>
                                                                                <option value="Founder">Founder</option>
                                                                                <option value="Co-Founder">Co-Founder
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Postal Address</label> <sup>Optional</sup>
                                                                    <input class="form-control" name="entity_postal" id="postal_co" placeholder="Postal Address">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer text-right">
                                                                <button type="button" class="btn btn-sm" style="background-color:#cc0001; color: #fff;" onclick="closeEntityOwnership()">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Proof of Ownership</label>
                                                    <input type="file" class="form-control" id="ownership_proof" name="ownership_proof">
                                                </div>
                                                <div class="form-group">
                                                    <label>Title Deed Copy</label>
                                                    <input type="file" class="form-control" id="title_deed" name="title_deed">
                                                </div>
                                                <div class="form-group">
                                                    <label>Other Legal Document</label>
                                                    <input type="file" class="form-control" id="legal_document" name="legal_document">
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="button" class="btn btn-danger btn-sm back-btn" id="secondSectionBackBtn">Back</button>
                                                <button type="button" class="btn btn-sm next-btn" id="secondSectionNextBtn">Next</button>
                                            </div>
                                        </div>
                                        <div class="card" id="thirdSection" style="border:1px solid rgb(0,25,45,.2); display:none;">
                                            <div class="card-header" style="background-color: #00192D; color:#fff;">
                                                <b>Amenities &amp; Features</b>
                                            </div>
                                            <div class="card-body">
                                                <!-- In-Unit Features -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;"><i class="bi bi-house-add-fill"></i> In-Unit Features</div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Laundry Washer and Dryer" name="amenities[]" id="laundry-washer">
                                                                    <label class="form-check-label" for="utilities-amenities">Laundry Washer and Dryer</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Dishwasher" name="amenities[]" id="dishwasher">
                                                                    <label class="form-check-label" for="utilities-amenities">Dishwasher</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Air Conditioning" name="amenities[]" id="air-conditioning">
                                                                    <label class="form-check-label" for="utilities-amenities">Air Conditioning</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Storage Space" name="amenities[]" id="storage-space">
                                                                    <label class="form-check-label" for="utilities-amenities">Storage Space</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Large Closets" name="amenities[]" id="large-closets">
                                                                    <label class="form-check-label" for="utilities-amenities">Large Closets</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Walk-in Closets" name="amenities[]" id="large-closets">
                                                                    <label class="form-check-label" for="utilities-amenities">Walk-in Closets</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Patio" name="amenities[]" id="patio">
                                                                    <label class="form-check-label" for="utilities-amenities">Patio</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Balcony" name="amenities[]" id="balcony">
                                                                    <label class="form-check-label" for="utilities-amenities">Balcony</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Energy-Efficient Appliances" name="amenities[]" id="enrgy-efficient-app">
                                                                    <label class="form-check-label" for="utilities-amenities">Energy-Efficient Appliances</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Kitchen" name="amenities[]" id="kitchen">
                                                                    <label class="form-check-label" for="utilities-amenities">Kitchen</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Marble or Granite Countertops" name="amenities[]" id="utilities-amenities">
                                                                    <label class="form-check-label" for="utilities-amenities">Marble or Granite Countertops</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Garbage Disposal" name="amenities[]" id=garbage-disposal>
                                                                    <label class="form-check-label" for="utilities-amenities">Garbage Disposal</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Built-in Microwave" name="amenities[]" id="built-in-microwave">
                                                                    <label class="form-check-label" for="utilities-amenities">Built-in Microwave</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Large Windows with lots of Light" name="amenities[]" id="utilities-amenities">
                                                                    <label class="form-check-label" for="utilities-amenities">Large Windows with lots of Light</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Window Coverings" name="amenities[]" id="window-coverings">
                                                                    <label class="form-check-label" for="utilities-amenities">Window Coverings</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Views" name="amenities[]" id="views">
                                                                    <label class="form-check-label" for="utilities-amenities">Views</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Fireplace" name="amenities[]" id="fire-place">
                                                                    <label class="form-check-label" for="utilities-amenities">Fireplace</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Hardwood Floors" name="amenities[]" id="hard-wood-floor">
                                                                    <label class="form-check-label" for="utilities-amenities">Hardwood Floors</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Furnished" name="amenities[]" id="furnished">
                                                                    <label class="form-check-label" for="utilities-amenities">Furnished</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Pet Amenities -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;"><i class="fa fa-paw"></i> Pet Amenities</div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Pet-Friendly" name="amenities[]" id="pet-friendly">
                                                                    <label class="form-check-label" for="utilities-amenities">Pet-Friendly</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Pet Washing Stations" name="amenities[]" id="pet-washing">
                                                                    <label class="form-check-label" for="utilities-amenities">Pet Washing Stations</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Dog Parks" name="amenities[]" id="dog-parks">
                                                                    <label class="form-check-label" for="utilities-amenities">Dog Parks</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Pet Walking" name="amenities[]" id="pet-walking">
                                                                    <label class="form-check-label" for="utilities-amenities">Pet Walking</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Pet Grooming" name="amenities[]" id="pet-grooming">
                                                                    <label class="form-check-label" for="utilities-amenities">Pet Grooming</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Laundry & Cleaning -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="fa fa-bath"></i> Laundry &amp; Cleaning
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Laundry Facilities" name="amenities[]" id="laundry-facilities">
                                                                    <label class="form-check-label" for="utilities-amenities">Laundry Facilities</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Laundry Services" name="amenities[]" id="laundry-services">
                                                                    <label class="form-check-label" for="utilities-amenities">Laundry Services</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Dry Cleaning" name="amenities[]" id="dru-cleaning">
                                                                    <label class="form-check-label" for="utilities-amenities">Dry Cleaning</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Cleaning" name="amenities[]" id="cleaning">
                                                                    <label class="form-check-label" for="utilities-amenities">Cleaning</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Health and Fitness  -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="bi bi-heart-pulse-fill"></i> Health &amp; Fitness
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Swimming Pool" name="amenities[]" id="swimming-pool">
                                                                    <label class="form-check-label" for="utilities-amenities">Swimming Pool</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Hot Tub" name="amenities[]" id="hot-tub">
                                                                    <label class="form-check-label" for="utilities-amenities">Hot Tub</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Fitness Center" name="amenities[]" id="fitness-center">
                                                                    <label class="form-check-label" for="utilities-amenities">Fitness Center</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Fitness Classes" name="amenities[]" id="utilities-amenities">
                                                                    <label class="form-check-label" for="utilities-amenities">Fitness Classes</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Yoga Studio" name="amenities[]" id="yoga-studio">
                                                                    <label class="form-check-label" for="utilities-amenities">Yoga Studio</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Sauna" name="amenities[]" id="sauna">
                                                                    <label class="form-check-label" for="utilities-amenities">Sauna</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Spa" name="amenities[]" id="spa">
                                                                    <label class="form-check-label" for="utilities-amenities">Spa</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Sports Court" name="amenities[]" id="sports-court">
                                                                    <label class="form-check-label" for="utilities-amenities">Sports Court</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Tennis Court" name="amenities[]" id="tenis-court">
                                                                    <label class="form-check-label" for="utilities-amenities">Tennis Court</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Pickle Ball Courts" name="amenities[]" id="picle-ball-courts">
                                                                    <label class="form-check-label" for="utilities-amenities">Pickle Ball Courts</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Active Lifestyle -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="bi bi-bicycle"></i> Active Lifestyle / Biking
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Biking" name="amenities[]" id="bikibg">
                                                                    <label class="form-check-label" for="utilities-amenities">Biking</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Bike Storage" name="amenities[]" id="bike-storage">
                                                                    <label class="form-check-label" for="utilities-amenities">Bike Storage</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Bike Repair Center" name="amenities[]" id="bike-repair">
                                                                    <label class="form-check-label" for="utilities-amenities">Bike Repair Center</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Bike-Share" name="amenities[]" id="bike-share">
                                                                    <label class="form-check-label" for="utilities-amenities">Bike-Share</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Outdoor and Community Spaces -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="bi bi-umbrella"></i> Outdoor and Community Spaces
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Grilling Areas" name="amenities[]" id="grilling-area">
                                                                    <label class="form-check-label" for="utilities-amenities">Grilling Areas</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Picnic Areas" name="amenities[]" id="picnic-area">
                                                                    <label class="form-check-label" for="utilities-amenities">Picnic Areas</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Fire Pits" name="amenities[]" id="fire-pits">
                                                                    <label class="form-check-label" for="utilities-amenities">Fire Pits</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Rooftop Deck" name="amenities[]" id="roog-top-deck">
                                                                    <label class="form-check-label" for="utilities-amenities">Rooftop Deck</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Garden" name="amenities[]" id="garden">
                                                                    <label class="form-check-label" for="utilities-amenities">Garden</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Patio" name="amenities[]" id="patio">
                                                                    <label class="form-check-label" for="utilities-amenities">Patio</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Family & Accessibility -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="fa fa-users"></i> Family &amp; Accessibility
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Family Friendly" name="amenities[]" id="friendly-family">
                                                                    <label class="form-check-label" for="utilities-amenities">Family Friendly</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Playground" name="amenities[]" id="playground">
                                                                    <label class="form-check-label" for="utilities-amenities">Playground</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="On-Site Daycare" name="amenities[]" id="on-sitedaycare">
                                                                    <label class="form-check-label" for="utilities-amenities">On-Site Daycare</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="On-Site Classes" name="amenities[]" id="on-site-classes">
                                                                    <label class="form-check-label" for="utilities-amenities">On-Site Classes</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Wheelchair Accessibility" name="amenities[]" id="wheel-chair">
                                                                    <label class="form-check-label" for="utilities-amenities">Wheelchair Accessibility</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Level (Garden Versus Penthouse)" name="amenities[]" id="garden-vs-penthouse">
                                                                    <label class="form-check-label" for="utilities-amenities">Level (Garden Versus Penthouse)</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Security -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="bi bi-lock-fill"></i> Security
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Gated Community" name="amenities[]" id="gated-community">
                                                                    <label class="form-check-label" for="utilities-amenities">Gated Community</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Controlled Access" name="amenities[]" id="controlled-access">
                                                                    <label class="form-check-label" for="utilities-amenities">Controlled Access</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Security Guard" name="amenities[]" id="security-guard">
                                                                    <label class="form-check-label" for="utilities-amenities">Security Guard</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Door Staff" name="amenities[]" id="door-staff">
                                                                    <label class="form-check-label" for="utilities-amenities">Door Staff</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Parking and Transportation -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="bi bi-truck-front-fill"></i> Parking &amp; Transportation
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Secured Garage" name="amenities[]" id="secured-garage">
                                                                    <label class="form-check-label" for="utilities-amenities">Secured Garage</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Parking Space" name="amenities[]" id="parking-space">
                                                                    <label class="form-check-label" for="utilities-amenities">Parking Space</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Guest Parking" name="amenities[]" id="guest-parking">
                                                                    <label class="form-check-label" for="utilities-amenities">Guest Parking</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Access to Public Transport" name="amenities[]" id="publick-tport">
                                                                    <label class="form-check-label" for="utilities-amenities">Access to Public Transport</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Electric Vehicle Charging Stations" name="amenities[]" id="charging-stations">
                                                                    <label class="form-check-label" for="utilities-amenities">Electric Vehicle Charging Stations</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Valet Parking" name="amenities[]" id="valet-parking">
                                                                    <label class="form-check-label" for="utilities-amenities">Valet Parking</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Property Management -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="bi bi-building"></i> Property Management
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Online Payments" name="amenities[]" id="online-payments">
                                                                    <label class="form-check-label" for="utilities-amenities">Online Payments</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Online Maintenance Requests" name="amenities[]" id="online-requests">
                                                                    <label class="form-check-label" for="utilities-amenities">Online Maintenance Requests</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="On-Site Property Management" name="amenities[]" id="property-mgt">
                                                                    <label class="form-check-label" for="utilities-amenities">On-Site Property Management</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="24-Hour Emergency Maintenance" name="amenities[]" id="emergency-maintenance">
                                                                    <label class="form-check-label" for="utilities-amenities">24-Hour Emergency Maintenance</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Mail and Deliveries -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="bi bi-truck"></i> Mail &amp; Delivery
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Mailroom" name="amenities[]" id="mailroom">
                                                                    <label class="form-check-label" for="utilities-amenities">Mailroom</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Package Lockers" name="amenities[]" id="page-lockers">
                                                                    <label class="form-check-label" for="utilities-amenities">Package Lockers</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Business and Work -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="bi bi-briefcase"></i> Business &amp; Work
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Conference Rooms" name="amenities[]" id="conference-rooms">
                                                                    <label class="form-check-label" for="utilities-amenities">Conference Rooms</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Co-Working Spaces" name="amenities[]" id="coworking-spaces">
                                                                    <label class="form-check-label" for="utilities-amenities">Co-Working Spaces</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Printing Services" name="amenities[]" id="printing-services">
                                                                    <label class="form-check-label" for="utilities-amenities">Printing Services</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- General and Convinience -->
                                                <div class="card shadow">
                                                    <div class="card-header" style="background-color:#00192D; color: #fff;">
                                                        <i class="fa fa-handshake-o"></i> General &amp; Convinience
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Wi-Fi in Common Areas" name="amenities[]" id="wifi-areas">
                                                                    <label class="form-check-label" for="utilities-amenities">Wi-Fi in Common Areas</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="High-Speed Internet Access" name="amenities[]" id="internet-access">
                                                                    <label class="form-check-label" for="utilities-amenities">High-Speed Internet Access</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Complimentary Coffee Bar" name="amenities[]" id="complementary-coffee">
                                                                    <label class="form-check-label" for="utilities-amenities">Complimentary Coffee Bar</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Guest Suits" name="amenities[]" id="guest-suits">
                                                                    <label class="form-check-label" for="utilities-amenities">Guest Suits</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="On-Site Convenience Store" name="amenities[]" id="onsite-store">
                                                                    <label class="form-check-label" for="utilities-amenities">On-Site Convenience Store</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Recycling Center" name="amenities[]" id="recycling-center">
                                                                    <label class="form-check-label" for="utilities-amenities">Recycling Center</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Daily Trash Pick-up" name="amenities[]" id="trash-pickup">
                                                                    <label class="form-check-label" for="utilities-amenities">Daily Trash Pick-up</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Extra Storage Space" name="amenities[]" id="extra-storage">
                                                                    <label class="form-check-label" for="utilities-amenities">Extra Storage Space</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="Elevator" name="amenities[]" id="elevator">
                                                                    <label class="form-check-label" for="utilities-amenities">Elevator</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="button" class="btn btn-danger btn-sm back-btn" id="thirdSectionBackBtn">Back</button>
                                                <button type="button" class="btn btn-sm next-btn" id="thirdSectionNextBtn">Next</button>
                                            </div>
                                        </div>
                                        <div class="card" id="fourthSection" style="border:1px solid rgb(0,25,45,.2); display:none;">
                                            <div class="card-header" style="background-color: #00192D; color:#fff;">
                                                <b>Photos</b>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Photo 1</label>
                                                            <input type="file" class="form-control" name="photo_one" id="photo_one">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Photo 2</label>
                                                            <input type="file" class="form-control" name="photo_two" id="photo_two">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Photo 3</label>
                                                            <input type="file" class="form-control" name="photo_three" id="photo_three">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Photo 4</label>
                                                            <input type="file" class="form-control" name="photo_four" id="photo_four">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="button" class="btn btn-danger btn-sm back-btn" id="fourthSectionBackBtn">Back</button>
                                                <button type="button" class="btn btn-sm next-btn" id="fourthSectionNextBtn">Next</button>
                                            </div>
                                        </div>
                                        <div class="card" id="fifthSection" style="border:1px solid rgb(0,25,45,.2); display:none;">
                                            <div class="card-header" style="background-color: #00192D; color:#fff;">
                                                <b>Confirmation</b>
                                            </div>
                                            <div class="card-body text-center">
                                                <input type="checkbox" required name="confirm" value="Confirmation"> I here
                                                by confirm that all the information filled in this form is accurare. I
                                                therefore issue my consent to Biccount Technologies to go ahead and register
                                                my rental property for further property management services that I will be
                                                receiving.
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="button" class="btn btn-danger btn-sm back-btn" id="fifthSectionBackBtn">Back</button>
                                                <button type="submit" name="submit_building" class="btn btn-sm next-btn" id="fifthSectionSubmitBtn" name="submit_building_btn">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <div> <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?> </div>
        <!-- end footer -->

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