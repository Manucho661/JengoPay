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
                <?php
                    include_once 'processes/encrypt_decrypt_function.php';
                    if(isset($_GET['rent']) && !empty($_GET['rent'])) {
                        $id = $_GET['rent'];
                        $id = encryptor('decrypt', $id);
                        try{
                            if(!empty($id)) {
                                $sql = "SELECT * FROM single_units WHERE id =:id";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(array(':id' => $id));
                                while ($row = $stmt->fetch()) {
                                    $unit_number = $row['unit_number'];
                                    $location = $row['location'];
                                    $building_link = $row['building_link'];
                                    $purpose = $row['purpose'];
                                }
                            }
                        }catch(PDOException $e){
                            //if the execution fails
                        }
                    }
                    //Process Submission of the Tenant Information into the Database
                ?>
                <div class="card shadow-sm">
                    <div class="card-header">
                        <b>Overview</b>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                    </div>
                    <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-home"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Unit No</span>
                                                <span class="info-box-number"><?= htmlspecialchars($unit_number); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fa fa-table"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Unit Floor</span>
                                                <span class="info-box-number"><?= htmlspecialchars($location);?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-building"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Building</span>
                                                <span class="info-box-number"><?= htmlspecialchars($building_link) ;?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box shadow" style="border:1px solid rgb(0,25,45,.3);">
                                            <span class="info-box-icon" style="background-color:#00192D; color:#fff;"><i class="fas fa-hotel"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Rental Purpose</span>
                                                <span class="info-box-number"><?= htmlspecialchars($purpose) ;?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="callout callout-danger shadow" id="callOutSection">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" id="closeCallOut">&times;</button>
                            <p style="font-weight:bold;"><span style="background-color:#cc0001; color:#fff; padding:3px; border-radius:4px;">Add Tenant!</span> Enter All the Required Relevant Tenant Details in Order to Rent out this Unit.</p>
                        </div>
                        <?php
                            if (isset($_POST['submit'])) {
                                
                                
                                $tm = md5(time()); // Unique prefix for uploaded files

                                // Upload files
                                $id_upload_destination = "all_uploads/" . $tm . $_FILES['id_upload']['name'];
                                move_uploaded_file($_FILES["id_upload"]["tmp_name"], $id_upload_destination);

                                $tax_pin_copy_destination = "all_uploads/" . $tm . $_FILES['tax_pin_copy']['name'];
                                move_uploaded_file($_FILES["tax_pin_copy"]["tmp_name"], $tax_pin_copy_destination);

                                $rental_agreement_destination = "all_uploads/" . $tm . $_FILES['rental_agreement']['name'];
                                move_uploaded_file($_FILES["rental_agreement"]["tmp_name"], $rental_agreement_destination);

                                try {
                                    // Check for duplicates of tenant Information
                                    $no_duplicate = "SELECT * FROM tenants WHERE main_contact = '$_POST[main_contact]' AND email = '$_POST[email]' AND id_no = '$_POST[id_no]' AND pass_no = '$_POST[pass_no]'";
                                    $stmt = $pdo->prepare($no_duplicate);
                                    $stmt->execute();

                                    //No Renting the Unit Twice with in the Same Building if the Tenant Status is Active
                                    $no_double_renting = "SELECT * FROM tenants WHERE account_no = '$_POST[account_no]' AND status = '$_POST[status]' AND building ='$_POST[building]'";
                                    $result = $pdo->prepare($no_double_renting);
                                    $result->execute();

                                    if ($stmt->rowCount() > 0) {
                                        echo "
                                            <script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Submission Failed',
                                                    text: 'Some Tenant Information Already Exists in the Database! Please Provide Accurate Details',
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
                                    } else if ($result->rowCount() > 0) {
                                        echo "
                                            <script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Submission Failed',
                                                    text: 'This Unit is Already Occuped by An Active Tenant. Double Renting Not Allowed!',
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
                                    } else {
                                        // Insert tenant
                                        $stmt = $pdo->prepare("INSERT INTO tenants 
                                            (first_name, middle_name, last_name, main_contact, alt_contact, email, idMode, id_no, pass_no, leasing_period, leasing_start_date, leasing_end_date, move_in_date, move_out_date, account_no, id_upload, tax_pin_copy, rental_agreement, income, job_title, job_location, casual_job, business_name, business_location, status, building) 
                                            VALUES 
                                            (:first_name, :middle_name, :last_name, :main_contact, :alt_contact, :email, :idMode, :id_no, :pass_no, :leasing_period, :leasing_start_date, :leasing_end_date, :move_in_date, :move_out_date, :account_no, :id_upload, :tax_pin_copy, :rental_agreement, :income, :job_title, :job_location, :casual_job, :business_name, :business_location, :status, :building)                                 ");

                                        $stmt->execute([
                                            ':first_name'        => $_POST['first_name'],
                                            ':middle_name'       => $_POST['middle_name'],
                                            ':last_name'         => $_POST['last_name'],
                                            ':main_contact'      => (string) $_POST['main_contact'],
                                            ':alt_contact'       => (string) $_POST['alt_contact'],
                                            ':email'             => $_POST['email'],
                                            ':idMode'            => $_POST['idMode'],
                                            ':id_no'             => (string) $_POST['id_no'],
                                            ':pass_no'           => $_POST['pass_no'],
                                            ':leasing_period'    => (string) $_POST['leasing_period'],
                                            ':leasing_start_date'=> $_POST['leasing_start_date'],
                                            ':leasing_end_date'  => $_POST['leasing_end_date'],
                                            ':move_in_date'      => $_POST['move_in_date'],
                                            ':move_out_date'     => $_POST['move_out_date'],
                                            ':account_no'        => $_POST['account_no'],
                                            ':id_upload'         => $id_upload_destination,
                                            ':tax_pin_copy'      => $tax_pin_copy_destination,
                                            ':rental_agreement'  => $rental_agreement_destination,
                                            ':income'            => $_POST['income'],
                                            ':job_title'         => $_POST['job_title'],
                                            ':job_location'      => $_POST['job_location'],
                                            ':casual_job'        => $_POST['casual_job'],
                                            ':business_name'     => $_POST['business_name'],
                                            ':business_location' => $_POST['business_location'],
                                            ':status'            => $_POST['status'],
                                            ':building'          => $_POST['building']
                                        ]);

                                        $tenant_id = $pdo->lastInsertId();

                                        // Insert deposits
                                        if (!empty($_POST['deposit_for']) && is_array($_POST['deposit_for'])) {
                                            $depositFor   = $_POST['deposit_for'];
                                            $requiredPay  = $_POST['required_pay'] ?? [];
                                            $amountPaid   = $_POST['amount_paid'] ?? [];

                                            $sqlDeposit = "INSERT INTO tenant_deposits 
                                            (tenant_id, deposit_for, required_pay, amount_paid, balance, subtotal) 
                                            VALUES (:tenant_id, :deposit_for, :required_pay, :amount_paid, :balance, :subtotal)";
                                            $stmtDep = $pdo->prepare($sqlDeposit);

                                            for ($i = 0; $i < count($depositFor); $i++) {
                                                $for = trim($depositFor[$i]);
                                                if ($for === '') continue;

                                                $req  = isset($requiredPay[$i]) ? floatval($requiredPay[$i]) : 0.0;
                                                $paid = isset($amountPaid[$i]) ? floatval($amountPaid[$i]) : 0.0;
                                                $bal  = max($req - $paid, 0);

                                                $stmtDep->execute([
                                                    ':tenant_id'    => $tenant_id,
                                                    ':deposit_for'  => $for,
                                                    ':required_pay' => number_format($req, 2, '.', ''),
                                                    ':amount_paid'  => number_format($paid, 2, '.', ''),
                                                    ':balance'      => number_format($bal, 2, '.', ''),
                                                    ':subtotal'     => number_format($paid, 2, '.', '')
                                                ]);
                                            }
                                        }

                                        // Success message
                                        echo "
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success!',
                                                text: 'Tenant Information Saved Successfully!',
                                                width: '600px',
                                                padding: '0.6em',
                                                customClass: {
                                                    popup: 'compact-swal'
                                                },
                                                confirmButtonText: 'OK'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = 'all_tenants.php';
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
                            ?>


                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                            <!-- CSRF Protection -->
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

                            <!-- Personal Information -->
                            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
                                <div class="card-header" style="background-color:#00192D; color: #fff;">Personal Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="first_name">First Name</label>
                                                <input type="text" id="first_name" name="first_name" required class="form-control" placeholder="First Name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="middle_name">Middle Name</label>
                                                <input type="text" id="middle_name" name="middle_name" required class="form-control" placeholder="Middle Name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" id="last_name" name="last_name" required class="form-control" placeholder="Last Name">
                                            </div>
                                        </div>
                                    </div> <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="main_contact">Main Contact</label> 
                                                <sup class="p-1" style="background-color:#00192D; color: #fff;">(Active WhatsApp No.)</sup>
                                                <input type="tel" id="main_contact" name="main_contact" pattern="^[0-9]{10}$" required class="form-control" placeholder="10 digit number">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="alt_contact">Alternative Contact</label>
                                                <input type="tel" id="alt_contact" name="alt_contact" pattern="^[0-9]{10}$" class="form-control" placeholder="10 digit number">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" name="email" required class="form-control" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Identification Mode</label>   
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="radio" id="idNational" name="idMode" value="national" required> 
                                                        <label for="idNational">National ID</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="radio" id="idPassport" name="idMode" value="passport"> 
                                                        <label for="idPassport">Passport</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- National ID Section -->
                                            <div id="nationalIdSection" class="popup" style="display:none;">
                                                <label for="nationalId">National ID Number:</label>
                                                <input type="text" id="nationalId" class="form-control" placeholder="ID Number" name="id_no" pattern="[0-9]{6,10}">
                                                <div id="nationalIdError" class="error text-danger small"></div><hr>
                                                <button type="button" onclick="closeId();" class="btn btn-sm btn-outline-dark">OK</button>
                                            </div>

                                            <!-- Passport Section -->
                                            <div id="passportPopup" class="popup" style="display:none;">
                                                <label for="passportNumber">Enter Passport Number:</label>
                                                <input type="text" id="passportNumber" class="form-control" placeholder="Passport Number" name="pass_no" pattern="[A-Z0-9]{5,15}">
                                                <div id="passportError" class="error text-danger small"></div>
                                                <button type="button" onclick="closePassport();" class="btn btn-sm mt-1 btn-outline-danger">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Deposits Information -->
                            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
                                <div class="card-header" style="background-color:#00192D; color: #fff;">Security Deposits</div>
                                <div class="card-body">
                                    <table id="paymentTable" class="table">
                                        <thead>
                                            <tr>
                                                <th>Deposit For</th>
                                                <th>Required Pay</th>
                                                <th>Amount Paid</th>
                                                <th>Balance</th>
                                                <th>Sub Total</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td><b>Totals</b></td>
                                                <td id="totalRequired">0</td>
                                                <td id="totalPaid">0</td>
                                                <td id="totalBalance">0</td>
                                                <td id="totalSub">0</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <button type="button" onclick="addRow()" class="btn btn-sm" style="background-color:#00192D; color: #fff;"><i class="bi bi-plus"></i> Add More</button>
                                </div>
                            </div>

                            <!-- Leasing Information -->
                            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
                                <div class="card-header" style="background-color:#00192D; color: #fff;">Leasing Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="leasingPeriod">Leasing Period (In Months)</label>
                                            <input type="number" id="leasingPeriod" required class="form-control" name="leasing_period">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="leasingStart">Leasing Starts On</label>
                                            <input type="date" id="leasingStart" required class="form-control" name="leasing_start_date">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="leasingEnd">Leasing Ends On</label>
                                            <input type="date" id="leasingEnd" readonly class="form-control" name="leasing_end_date">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-8">
                                            <label for="moveIn">Move In Date</label>
                                            <input type="date" id="moveIn" required class="form-control" name="move_in_date">
                                            <label for="moveOut">Move Out Date</label>
                                            <input type="date" id="moveOut" readonly class="form-control" name="move_out_date">
                                            <label for="account_no">Unit Number</label>
                                            <input type="text" id="account_no" name="account_no" required class="form-control" value="<?= htmlspecialchars($unit_number); ?>" readonly>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Uploads -->
                            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
                                <div class="card-header" style="background-color:#00192D; color: #fff;">Uploads Information</div>
                                <div class="card-body">
                                    <label for="id_upload">Identification Upload</label>
                                    <input type="file" id="id_upload" required name="id_upload" class="form-control" accept=".jpg,.jpeg,.png,.pdf"> <hr>
                                    <label for="tax_pin_copy">TAX PIN Upload</label>
                                    <input type="file" id="tax_pin_copy" name="tax_pin_copy" required class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                    <label for="rental_agreement">Rental Agreement Upload</label>
                                    <input type="file" id="rental_agreement" required name="rental_agreement" class="form-control" accept=".pdf">
                                </div>
                            </div>

                            <!-- Source of Income -->
                            <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
                                <div class="card-header" style="background-color:#00192D; color: #fff;">Source of Income</div>
                                <div class="card-body text-center">
                                    <label>Main Source of Income</label>
                                    <div class="row">
                                        <div class="col-md-4"><input type="radio" id="incomeFormal" name="income" value="formal"> <label for="incomeFormal">Formal Employment</label></div>
                                        <div class="col-md-4"><input type="radio" id="incomeCasual" name="income" value="casual"> <label for="incomeCasual">Casual Employment</label></div>
                                        <div class="col-md-4"><input type="radio" id="incomeBusiness" name="income" value="business"> <label for="incomeBusiness">Business</label></div>
                                    </div>
                                    <!-- Formal -->
                                    <div id="formalPopup" class="popup" style="display:none;">
                                        <p>Specify Job Title & Location:</p>
                                        <input type="text" id="formalWork" class="form-control" name="job_title" placeholder="Job Title">
                                        <input type="text" id="formalWorkLocation" class="form-control" name="job_location" placeholder="Job Location">
                                        <button type="button" class="btn btn-sm mt-2 btn-outline-dark" onclick="closePopup()">OK</button>
                                    </div>
                                    <!-- Casual -->
                                    <div id="casualPopup" class="popup" style="display:none;">
                                        <p>Please Specify:</p>
                                        <input type="text" id="casualWork" class="form-control" name="casual_job">
                                        <button type="button" class="btn btn-sm mt-2 btn-outline-dark" onclick="closePopup()">OK</button>
                                    </div>
                                    <!-- Business -->
                                    <div id="businessPopup" class="popup" style="display:none;">
                                        <p>Business Name and Location:</p>
                                        <input type="text" id="businessName" class="form-control" name="business_name" placeholder="Business Name">
                                        <input type="text" id="businessLocation" class="form-control" name="business_location" placeholder="Location">
                                        <button type="button" class="btn btn-sm mt-2 btn-outline-dark" onclick="closePopup()">OK</button>
                                    </div>
                                    <input type="hidden" name="status" value="Active">
                                    <input type="hidden" name="building" value="<?= htmlspecialchars($building_link) ;?>">
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="card shadow">
                                <div class="card-body text-right">
                                    <button type="submit" name="submit" class="btn btn-sm" style="background-color: #00192D; color: #fff;"><i class="bi bi-check2-all"></i> Submit</button>
                                </div>
                            </div>
                        </form>
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
<script>
// ==================== Deposits ====================
function addRow() {
  const tbody = document.querySelector("#paymentTable tbody");
  const row = document.createElement("tr");
  row.innerHTML = `
    <td><input type="text" class="form-control depositFor" name="deposit_for[]"></td>
    <td><input type="number" class="form-control requiredPay" name="required_pay[]" value="0" min="0"></td>
    <td><input type="number" class="form-control amountPaid" name="amount_paid[]" value="0" min="0"></td>
    <td class="balance"><input type="hidden" name="balance">0</td>
    <td class="subTotal"><input type="hidden" name="subtotal"0</td>
    <td><button type="button" class="btn btn-sm removeRow" style="background-color:#cc0001; color:#fff;"><i class="bi bi-trash"></i> Remove</button></td>
  `;
  tbody.appendChild(row);

  // Input listeners
  row.querySelectorAll(".requiredPay, .amountPaid").forEach(input => {
    input.addEventListener("input", () => {
      if (input.value < 0) input.value = 0;
      updateTableTotals();
    });
  });

  row.querySelector(".removeRow").addEventListener("click", () => {
    row.remove();
    updateTableTotals();
  });
  updateTableTotals();
}

function updateTableTotals() {
  let totalRequired = 0, totalPaid = 0, totalBalance = 0, totalSub = 0;
  document.querySelectorAll("#paymentTable tbody tr").forEach(row => {
    const required = parseFloat(row.querySelector(".requiredPay").value) || 0;
    const paid = parseFloat(row.querySelector(".amountPaid").value) || 0;
    const balance = Math.max(required - paid, 0);
    const sub = paid;

    row.querySelector(".balance").textContent = balance;
    row.querySelector(".subTotal").textContent = sub;

    totalRequired += required;
    totalPaid += paid;
    totalBalance += balance;
    totalSub += sub;
  });

  document.getElementById("totalRequired").textContent = totalRequired;
  document.getElementById("totalPaid").textContent = totalPaid;
  document.getElementById("totalBalance").textContent = totalBalance;
  document.getElementById("totalSub").textContent = totalSub;
}

// ==================== Popups ====================
document.querySelectorAll("input[name='idMode']").forEach(radio => {
  radio.addEventListener("change", function() {
    document.getElementById("nationalIdSection").style.display = this.value === "national" ? "block" : "none";
    document.getElementById("passportPopup").style.display = this.value === "passport" ? "block" : "none";
  });
});

document.querySelectorAll("input[name='income']").forEach(radio => {
  radio.addEventListener("change", function() {
    document.getElementById("formalPopup").style.display = this.value === "formal" ? "block" : "none";
    document.getElementById("casualPopup").style.display = this.value === "casual" ? "block" : "none";
    document.getElementById("businessPopup").style.display = this.value === "business" ? "block" : "none";
  });
});

function closePopup() {
  document.querySelectorAll(".popup").forEach(p => p.style.display = "none");
}

function closeId(){
  const idInput = document.getElementById('nationalId');
  if (!idInput.checkValidity()) {
    document.getElementById('nationalIdError').textContent = "Please enter a valid ID number.";
    return;
  }
  document.getElementById('nationalIdSection').style.display = 'none';
}

function closePassport(){
  const passInput = document.getElementById('passportNumber');
  if (!passInput.checkValidity()) {
    document.getElementById('passportError').textContent = "Please enter a valid Passport number.";
    return;
  }
  document.getElementById('passportPopup').style.display = 'none';
}

// ==================== Leasing Dates ====================
document.getElementById("leasingPeriod").addEventListener("input", calculateEndDate);
document.getElementById("leasingStart").addEventListener("change", calculateEndDate);
document.getElementById("moveIn").addEventListener("change", calculateEndDate);

function calculateEndDate() {
  const months = parseInt(document.getElementById("leasingPeriod").value) || 0;
  const startDate = new Date(document.getElementById("leasingStart").value);
  const moveInDate = new Date(document.getElementById("moveIn").value);

  if (months > 0 && !isNaN(startDate)) {
    const endDate = new Date(startDate);
    endDate.setMonth(endDate.getMonth() + months);
    const iso = endDate.toISOString().split("T")[0];
    document.getElementById("leasingEnd").value = iso;
    document.getElementById("moveOut").value = iso;
  }

  // Sync move-out with move-in + months if move-in is set
  if (months > 0 && !isNaN(moveInDate)) {
    const moveOut = new Date(moveInDate);
    moveOut.setMonth(moveOut.getMonth() + months);
    document.getElementById("moveOut").value = moveOut.toISOString().split("T")[0];
  }
}

// ==================== Init ====================
document.addEventListener("DOMContentLoaded", () => {
  addRow(); // Start with one row
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