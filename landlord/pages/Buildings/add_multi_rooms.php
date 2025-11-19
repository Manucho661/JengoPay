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
        <main class="app-main mt-4">
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
<div class="container-fluid">
                    <div class="row">
                        <?php
                            include_once 'processes/encrypt_decrypt_function.php';
                            if(isset($_GET['add_multi_rooms']) && !empty($_GET['add_multi_rooms'])) {
                                $id = $_GET['add_multi_rooms'];
                                $id = encryptor('decrypt', $id);
                            try{
                                if(!empty($id)) {
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
                            }catch(PDOException $e){
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
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 🔍 Check for duplicate unit_number + building_link
        $check = $pdo->prepare("SELECT COUNT(*) FROM multi_rooms_units WHERE unit_number = :unit_number AND building_link = :building_link");
        $check->execute([
            ':unit_number'   => $_POST['unit_number'],
            ':building_link' => $_POST['building_link']
        ]);

        if ($check->fetchColumn() > 0) {
            echo "
            <script>
                Swal.fire({
                    title: 'Warning!',
                    text: 'This unit already exists in the Database. No duplicate entries allowed.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
            </script>";
            exit;
        }

        // Begin Transaction
        $pdo->beginTransaction();

        // Insert into multi_rooms
        $stmt = $pdo->prepare("INSERT INTO multi_rooms_units
            (structure_type, first_name, last_name, owner_email, entity_name, entity_phone, entity_phoneother, entity_email, unit_number, purpose, building_link, location, water_meter, monthly_rent, number_of_rooms, number_of_washrooms, number_of_doors, occupancy_status, created_at)
            VALUES (:structure_type, :first_name, :last_name, :owner_email, :entity_name, :entity_phone, :entity_phoneother, :entity_email, :unit_number, :purpose, :building_link, :location, :water_meter, :monthly_rent, :number_of_rooms, :number_of_washrooms, :number_of_doors, :occupancy_status, NOW())");

        $stmt->execute([
            ':structure_type'    => $_POST['structure_type'],
            ':first_name'        => $_POST['first_name'],
            ':last_name'         => $_POST['last_name'],
            ':owner_email'       => $_POST['owner_email'],
            ':entity_name'       => $_POST['entity_name'],
            ':entity_phone'      => $_POST['entity_phone'],
            ':entity_phoneother' => $_POST['entity_phoneother'],
            ':entity_email'      => $_POST['entity_email'],
            ':unit_number'       => $_POST['unit_number'],
            ':purpose'           => $_POST['purpose'],
            ':building_link'     => $_POST['building_link'],
            ':location'          => $_POST['location'],
            ':water_meter'       => $_POST['water_meter'],
            ':monthly_rent'      => $_POST['monthly_rent'],
            ':number_of_rooms'   => $_POST['number_of_rooms'],
            ':number_of_washrooms'=> $_POST['number_of_washrooms'],
            ':number_of_doors'   => $_POST['number_of_doors'],
            ':occupancy_status'  => $_POST['occupancy_status']
        ]);

        // Get the new unit_id
        $unit_id = $pdo->lastInsertId();

        // Insert bills if provided
        if (!empty($_POST['bill'])) {
            $stmtBill = $pdo->prepare("INSERT INTO multi_roombills (unit_id, bill, qty, unit_price, subtotal, created_at) 
                                        VALUES (:unit_id, :bill, :qty, :unit_price, :subtotal, NOW())");

            foreach ($_POST['bill'] as $i => $billName) {
                if ($billName != "") {
                    $qty       = isset($_POST['qty'][$i]) ? (int)$_POST['qty'][$i] : 0;
                    $unitPrice = isset($_POST['unit_price'][$i]) ? (float)$_POST['unit_price'][$i] : 0;
                    $subtotal  = $qty * $unitPrice;

                    $stmtBill->execute([
                        ':unit_id'    => $unit_id,
                        ':bill'       => $billName,
                        ':qty'        => $qty,
                        ':unit_price' => $unitPrice,
                        ':subtotal'   => $subtotal
                    ]);
                }
            }
        }

        // Commit transaction
        $pdo->commit();

        // SweetAlert success
        echo "
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'Multi-room unit and its bills saved successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'multi_room_units.php';
            });
        </script>";
        exit;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo "
        <script>
            Swal.fire({
                title: 'Error!',
                text: '". addslashes($e->getMessage()) ."',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
        exit;
    }
}
                    
                        ?>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box shadow">
                                        <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="bi bi-building"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Building</span>
                                            <span class="info-box-number"><?= htmlspecialchars($building_name) ;?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box shadow">
                                        <span class="info-box-icon" style="background-color:#00192D; color: #fff;"><i class="bi bi-houses"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Structure Type</span>
                                            <span class="info-box-number"><?= htmlspecialchars($structure_type) ;?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box shadow">
                                        <span class="info-box-icon" style="background-color:#00192D; color: #fff;"><i class="bi bi-house-exclamation"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Building Type</span>
                                            <span class="info-box-number"><?= htmlspecialchars($building_type) ;?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box shadow">
                                        <span class="info-box-icon" style="background-color:#00192D; color: #fff;"><i class="bi bi-table"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Number of Units</span>
                                            <span class="info-box-number"><?= htmlspecialchars($no_of_units) ;?></span>
                                        </div>
                                    </div>
                                </div>
                            </div> <hr>
                            <div class="card shadow">
                                <div class="card-header" style="background-color: #00192D; color:#fff;">
                                    <p>Add Unit (<?= htmlspecialchars($building_name);?>)</p>
                                </div>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="structure_type" value="<?= htmlspecialchars($structure_type);?>">
                                    <input type="hidden" name="first_name" value="<?= htmlspecialchars($first_name);?>">
                                    <input type="hidden" name="last_name" value="<?= htmlspecialchars($last_name);?>">
                                    <input type="hidden" name="owner_email" value="<?= htmlspecialchars($owner_email);?>">
                                    <input type="hidden" name="entity_name" value="<?= htmlspecialchars($entity_name);?>">
                                    <input type="hidden" name="entity_phone" value="<?= htmlspecialchars($entity_phone);?>">
                                    <input type="hidden" name="entity_phoneother" value="<?= htmlspecialchars($entity_phoneother);?>">
                                    <input type="hidden" name="entity_email" value="<?= htmlspecialchars($entity_email);?>">
                                    <div class="card-body">
                                        <div class="card shadow" id="firstSection" style="border:1px solid rgb(0,25,45,.2);">
                                            <div class="card-header" style="background-color: #00192D; color:#fff;">
                                                <b>Unit Identification</b>
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
                                                            <input type="text" name="building_link" class="form-control" value="<?= htmlspecialchars($building_name);?>" readonly>
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
                                                <div class="form-group">
                                                    <label>Water Meter Number</label>
                                                    <input type="number" class="form-control" id="water_meter" name="water_meter" placeholder="Water Meter">
                                                </div>
                                                <div class="form-group">
                                                    <label>Monthly Rent</label>
                                                    <input type="number" class="form-control" id="monthly_rent" name="monthly_rent" placeholder="Monthly Rent">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Number of Rooms</label>
                                                            <input type="number" class="form-control" name="number_of_rooms" id="number_of_rooms" placeholder="Number of Rooms">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Number of Washrooms</label>
                                                            <input type="number" class="form-control" name="number_of_washrooms" id="number_of_washrooms" placeholder="Number of Washrooms">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Number of Main Entry Doors</label>
                                                            <input type="number" class="form-control" name="number_of_doors" id="number_of_washrooms" placeholder="Number of Main Entry Doors">
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
                                                            <tbody>
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
                                                <div class="form-group">
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
                                                <button class="btn btn-sm" type="submit" name="submit_unit" style="background-color:#00192D; color: #fff;"><i class="bi bi-send"></i> Submit</button
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                </section>
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