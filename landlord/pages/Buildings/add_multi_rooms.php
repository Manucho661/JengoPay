<?php
session_start();
require_once "../db/connect.php";
//  include_once 'includes/lower_right_popup_form.php';
?>
<?php
require_once "../db/connect.php";

$stmt = $pdo->prepare("SELECT id, account_name
                            FROM chart_of_accounts
                            WHERE account_name = 'Rental Income'");
$stmt->execute();
$rentAccounts = $stmt->fetchAll(PDO::FETCH_ASSOC);


// logged in landlord id
$userId = $_SESSION['user']['id'];

// Fetch landlord ID linked to the logged-in user
$stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ?");
$stmt->execute([$userId]);
$landlord = $stmt->fetch();

// Check if landlord exists for the user
if (!$landlord) {
    throw new Exception("Landlord account not found for this user.");
}

$landlord_id = $landlord['id']; // Store the landlord_id from the session

// actions
include_once "./actions/units/add_multi_rooms.php";
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
        <!--end::Sidebar Wrapper-->

        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="main">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="">
                    <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Dashboard/index2.php" style="text-decoration: none;">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Buildings/buildings.php" style="text-decoration: none;">Buildings</a></li>
                    <li class="breadcrumb-item active">Multi room units</li>
                </ol>
            </nav>
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!--First Row-->
                        <div class="row align-items-center mb-4">
                            <div class="col-12 d-flex align-items-center">
                                <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
                                <h3 class="mb-0 ms-3">Add a multi room unit</h3>
                                <span class="mx-4"></span>
                            </div>
                        </div>
                        <div class="row">
                            
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
                                        <h3><?= htmlspecialchars($building_type); ?></h3>
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
                        <hr>
                        <div class="card shadow">
                            <div class="card-header" style="background-color: #00192D; color:#fff;">
                                <p>Add Unit (<?= htmlspecialchars($building_name); ?>)</p>
                            </div>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="structure_type" value="<?= htmlspecialchars($structure_type); ?>">
                                <input type="hidden" name="first_name" value="<?= htmlspecialchars($first_name); ?>">
                                <input type="hidden" name="last_name" value="<?= htmlspecialchars($last_name); ?>">
                                <input type="hidden" name="owner_email" value="<?= htmlspecialchars($owner_email); ?>">
                                <input type="hidden" name="entity_name" value="<?= htmlspecialchars($entity_name); ?>">
                                <input type="hidden" name="entity_phone" value="<?= htmlspecialchars($entity_phone); ?>">
                                <input type="hidden" name="entity_phoneother" value="<?= htmlspecialchars($entity_phoneother); ?>">
                                <input type="hidden" name="entity_email" value="<?= htmlspecialchars($entity_email); ?>">
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
                                            <div class="form-group">
                                                <label>Water Meter Number</label>
                                                <input type="number" class="form-control" id="water_meter" name="water_meter" placeholder="Water Meter">
                                            </div>
                                            <div class="form-group">
                                                <label>Select Rent Account (Chart of Accounts)</label>
                                                <select class="form-control" id="rent_account" name="rent_account" required>
                                                    <option value="">-- Select Rent Account --</option>
                                                    <?php foreach ($rentAccounts as $acc): ?>
                                                        <option value="<?= $acc['id']; ?>">
                                                            <?= $acc['account_name']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group" id="monthly_rent_group" style="display:none;">
                                                <label>Monthly Rent Amount (KES)</label>
                                                <input type="number" class="form-control" id="monthly_rent" name="monthly_rent" placeholder="Enter Monthly Rent">
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
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
        <!-- end footery -->
    </div>
    <!--end::App Wrapper-->

    <!-- plugin for pdf -->
    <script>
        document.getElementById('rent_account').addEventListener('change', function() {
            let selectedText = this.options[this.selectedIndex].text.trim();

            // Show input only when "Rental Income" is selected
            if (selectedText === "Rental Income") {
                document.getElementById('monthly_rent_group').style.display = 'block';
                document.getElementById('monthly_rent').required = true;
            } else {
                document.getElementById('monthly_rent_group').style.display = 'none';
                document.getElementById('monthly_rent').required = false;
                document.getElementById('monthly_rent').value = '';
            }
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