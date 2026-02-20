<?php
 require_once "../../db/connect.php";
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
            <section class="content">
                <div class="container-fluid">
                    <?php
                    include_once 'processes/encrypt_decrypt_function.php';
                    if(isset($_GET['edit']) && !empty($_GET['edit'])) {
                        $unit_id = $_GET['edit'];
                        $unit_id = encryptor('decrypt', $unit_id);

                    try{
                        // 1. Fetch unit details
                        $stmt = $pdo->prepare("SELECT * FROM single_units WHERE id = ?");
                        $stmt->execute([$unit_id]);
                        $unit = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (!$unit) {
                            die("Unit not found!");
                        }

                        // 2. Fetch bills (always return array)
                        $stmtBills = $pdo->prepare("SELECT * FROM single_unit_bills WHERE unit_id = ?");
                        $stmtBills->execute([$unit_id]);
                        $bills = $stmtBills->fetchAll(PDO::FETCH_ASSOC) ?: [];
                        }catch(PDOException $e){  
                            //if the query fails to select
                        }
                    }

                    //If the Update Button is Clicked
                    if(isset($_POST['update'])) {
                        $id               = $_POST['id'];
                        $unit_number      = $_POST['unit_number'];
                        $purpose          = $_POST['purpose'];
                        $building_link    = $_POST['building_link'];
                        $location         = $_POST['location'];
                        $monthly_rent     = $_POST['monthly_rent'];
                        $occupancy_status = $_POST['occupancy_status'];
                        try{
                            //No Update to be done until Any of the Form values are changed
                            $no_changes = "SELECT * FROM single_units WHERE unit_number = '$_POST[unit_number]' AND purpose = '$_POST[purpose]' AND building_link = '$_POST[building_link]' AND location = '$_POST[location]' AND monthly_rent = '$_POST[monthly_rent]' AND occupancy_status = '$_POST[occupancy_status]'";
                            $stmt = $pdo->prepare($no_changes);
                            $stmt->execute();
                            if($stmt->rowCount() > 0) {
                                ?>
                    <script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning!',
                            text: 'Unit Update Failed! You Have not Changed Anything.',
                            confirmButtonColor: '#00192D'
                            }).then(() => {
                            window.location.href = 'single_units.php'; // redirect after confirmation
                        });
                    </script>
                    <?php
                            } else {
                                // Start transaction
                                if (!$pdo->inTransaction()) {
                                    $pdo->beginTransaction();
                                }

                                // 1. Update main unit details
                                $stmt = $pdo->prepare("
                                    UPDATE single_units 
                                    SET unit_number = ?, 
                                        purpose = ?, 
                                        building_link = ?, 
                                        location = ?, 
                                        monthly_rent = ?, 
                                        occupancy_status = ?
                                    WHERE id = ?
                                ");

                                $stmt->execute([
                                    $unit_number,
                                    $purpose,
                                    $building_link,
                                    $location,
                                    $monthly_rent,
                                    $occupancy_status,
                                    $id
                                ]);

                                // 2. Delete old bills
                                $stmt = $pdo->prepare("DELETE FROM single_unit_bills WHERE unit_id = ?");
                                $stmt->execute([$id]);

                                // 3. Insert updated bills
                                if (!empty($_POST['bill'])) {
                                    $stmt = $pdo->prepare("
                                        INSERT INTO single_unit_bills (unit_id, bill, qty, unit_price) 
                                        VALUES (?, ?, ?, ?)
                                    ");
                                    foreach ($_POST['bill'] as $i => $billName) {
                                        $bill       = trim($billName);
                                        $qty        = !empty($_POST['qty'][$i]) ? $_POST['qty'][$i] : 0;
                                        $unit_price = !empty($_POST['unit_price'][$i]) ? $_POST['unit_price'][$i] : 0;

                                        if ($bill !== "") { // avoid inserting empty rows
                                            $stmt->execute([$id, $bill, $qty, $unit_price]);
                                        }
                                    }
                                }

                                // Commit changes
                                if ($pdo->inTransaction()) {
                                    $pdo->commit();
                                }

                                // ✅ Success feedback
                                echo
                                    "<script>
                                      Swal.fire({
                                      icon: 'success',
                                      title: 'Update Successful!',
                                      text: 'Unit Information Updated Successfully.',
                                      confirmButtonColor: '#00192D'
                                      }).then(() => {
                                      window.location.href = 'single_units.php'; // redirect after confirmation
                                      });
                                    </script>";
                            }

                        } catch(Exception $e){
                            if ($pdo->inTransaction()) {
                                $pdo->rollBack();
                            }
                            echo "
                                <script>
                                  Swal.fire({
                                    icon: 'error',
                                    title: 'Database Error',
                                    text: '".addslashes($e->getMessage())."',
                                    confirmButtonColor: '#00192D'
                                  });
                                </script>";
                        }
                    } 
                ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" method="POST" enctype="multipart/form-data">
                        <!-- Unit Details -->
                        <input type="hidden" value="<?= htmlspecialchars($unit['id']); ?>" name="id">
                        <div class="card shadow" style="border: 1px solid rgb(0,25,45,.3);">
                            <div class="card-header" style="background-color: rgb(0, 25,45); color:#fff;"><i class="bi bi-house-add"></i> Unit Details</div>
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="row p-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Unit Number</label>
                                                <input type="text" name="unit_number" class="form-control" value="<?= htmlspecialchars($unit['unit_number']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Purpose</label>
                                                <input type="text" name="purpose" class="form-control" value="<?= htmlspecialchars($unit['purpose']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row p-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Building Link</label>
                                                <input type="text" name="building_link" class="form-control" value="<?= htmlspecialchars($unit['building_link']); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Location</label>
                                                <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($unit['location']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Monthly Rent</label>
                                                <input type="number" step="0.01" name="monthly_rent" class="form-control" value="<?= htmlspecialchars($unit['monthly_rent']); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Occupancy Status</label>
                                                <select name="occupancy_status" class="form-control">
                                                    <option value="<?= htmlspecialchars($unit['occupancy_status']); ?>" selected hidden><?= htmlspecialchars($unit['occupancy_status']); ?></option>
                                                    <option value="Occupied">Occupied</option>
                                                    <option value="Vacant">Vacant</option>
                                                    <option value="Under Maintenance">Under Maintenance</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card shadow">
                            <div class="card-body text-right">
                                <button class="btn btn-sm shadow" type="submit" name="update" style="border: 1px solid #00192D; color: #00192D;"><i class="bi bi-check"></i> Update and Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
                <!-- Main content -->                                         
            <!-- /.content -->
            <!-- Help Pop Up Form -->     
        </div>
        <!-- /.content-wrapper -->
        <!-- Footer -->
    </div>
    <!-- ./wrapper -->
    <!-- Required Scripts -->
 
    <!-- Meter Readings JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

 
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableBody = document.querySelector("#billsTable tbody");

            // Add new row
            document.getElementById("addRow").addEventListener("click", function() {
                const row = document.createElement("tr");
                row.innerHTML = `
            <td><input type="text" name="bill[]" class="form-control bill"></td>
            <td><input type="number" name="qty[]" class="form-control qty" value="1"></td>
            <td><input type="number" step="0.01" name="unit_price[]" class="form-control unit_price"></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td><button type="button" class="btn btn-sm shadow removeRow" style="background-color:#cc0001; color:#fff; font-weight:bold;">X</button></td>`;
                tableBody.appendChild(row);
            });

            // Delegate remove buttons
            tableBody.addEventListener("click", function(e) {
                if (e.target.classList.contains("removeRow")) {
                    e.target.closest("tr").remove();
                }
            });

            // Auto-calc subtotal
            tableBody.addEventListener("input", function(e) {
                if (e.target.classList.contains("qty") || e.target.classList.contains("unit_price")) {
                    const row = e.target.closest("tr");
                    const qty = parseFloat(row.querySelector(".qty").value) || 0;
                    const price = parseFloat(row.querySelector(".unit_price").value) || 0;
                    row.querySelector(".subtotal").value = (qty * price).toFixed(2);
                }
            });
        });
    </script>
   
</body>
<!--end::Body-->

</html>