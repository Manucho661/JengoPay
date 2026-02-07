<?php
session_start();
require_once '../../db/connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);


$expenses = [];
$monthlyTotals = array_fill(1, 12, 0);


// register supplier
// require_once 'actions/registerSupplier.php';
// get suppliers
require_once 'actions/getSuppliers.php';
// create expenses script
require_once 'actions/createExpense.php';
//Include expense Batches
require_once 'actions/getExpenses.php';
// Include expense accounts
require_once 'actions/getExpenseAccounts.php';
// include buildings
require_once 'actions/getBuildings.php';

// filters
$status     = $_GET['status'] ?? null;
$q          = trim($_GET['q'] ?? ''); // search text


// Pagination logic
$itemsPerPage = 6;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalItems = count($expenses);
$totalPages = ceil($totalItems / $itemsPerPage);
$offset = ($currentPage - 1) * $itemsPerPage;
$currentExpenses = array_slice($expenses, $offset, $itemsPerPage);
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE | Dashboard v2</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE | Dashboard v2" />
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

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!--Main css file-->
    <link rel="stylesheet" href="../../../../landlord/assets/main.css" />

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="expenses.css">
    <!-- scripts for data_table -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Pdf pluggin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


    <!--Tailwind CSS  -->
    <style>
        :root {
            --primary: #00192D;
            --secondary: #FFC107;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #FFC107;
            --light-bg: #f8f9fa;
            --accent-color: #FFC107;
        }

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

        /* pagination styles */
        /* Pagination */
        .pagination {
            margin: 2rem 0;
        }

        .pagination .page-link {
            color: var(--primary-color);
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            margin: 0 0.2rem;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .pagination .page-link:hover {
            background: #FFC107;
            color: #00192D;
            border-color: #FFC107;
        }

        .pagination .page-item.active .page-link {
            background: #00192D;
            border-color: #00192D;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background: #fff;
        }

        .pagination-info {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* btn styles */
        .applyFilterBtn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.3rem 1.4rem;
            border-radius: 5px;
            font-weight: 400;
            transition: all 0.3s;
        }

        .applyFilterBtn:hover {
            background: var(--accent-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* filters */
        .form-select,
        .form-control {
            background: rgba(255, 193, 7, 0.05);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: none;
            outline: none;
            background: rgba(255, 193, 7, 0.1);
        }

        .filters-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .filters-row {
            flex-wrap: nowrap;
        }

        /* Default (desktop/tablet) */
        .filter-col {
            min-width: 220px;
        }

        /* Phones: make each filter narrower */
        @media (max-width: 576px) {
            .filter-col {
                min-width: 160px;
            }

            .filter-col input,
            .filter-col select {
                font-size: 0.9rem;
                padding: .375rem .5rem;
            }
        }

        .filters-scroll {
            mask-image: linear-gradient(to right, transparent 0, black 16px, black calc(100% - 16px), transparent 100%);
        }
    </style>
</head>

<body class="" style="">

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
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; ?>
        <!--end::Header-->

        <!--begin::Sidebar Wrapper-->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?>
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="main">
            <!--begin:: Main Container-->
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/Jengopay/landlord/pages/Dashboard/index2.php" style="text-decoration: none;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Expenses</li>
                    </ol>
                </nav>

                <!--begin::first Row-->
                <div class="row align-items-center mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
                        <h3 class="mb-0 ms-3">Expenses</h3>
                    </div>
                </div>

                <!-- Second row: action buttons -->
                <div class="d-flex align-items-center justify-content-between gap-2 flex-nowrap">

                    <!-- Left text -->
                    <div class="text-muted">
                        Manage your expenses
                    </div>

                    <!-- Desktop buttons -->
                    <div class="d-none d-md-flex gap-2">
                        <button class="btn bg-warning text-white seTAvailable fw-bold rounded-4"
                            id="supplier-list-open-btn"
                            style="background: linear-gradient(135deg, #00192D, #002B5B); color:white; width:100%; white-space: nowrap;">
                            Registered Suppliers
                        </button>

                        <button
                            type="button"
                            class="btn fw-bold rounded-4 text-white"
                            style="background: linear-gradient(135deg, #00192D, #002B5B); width:100%; white-space: nowrap;"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#supplierOffcanvas"
                            aria-controls="supplierOffcanvas">
                            Register Supplier
                        </button>


                    </div>

                    <!-- Mobile dropdown menu -->
                    <div class="dropdown d-md-none ms-auto">
                        <button class="btn btn-light border d-flex align-items-center justify-content-center rounded-4"
                            id="mobileActionsMenu"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="width:38px;height:38px;border-radius:8px;">
                            <i class="bi bi-three-dots text-dark"></i>
                        </button>


                        <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2"
                            aria-labelledby="mobileActionsMenu">
                            <li>
                                <button class="dropdown-item" type="button" id="supplier-list-open-btn-mobile">
                                    Registered Suppliers
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item" type="button" id="addSupplier-mobile">
                                    Register Supplier
                                </button>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- Third row: stats -->
                <div class="row g-3 mt-2 mb-4">
                    <!-- Total Items Card -->
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                            <div>
                                <i class="fa fa-box fs-1 me-3 text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Total Items</p>
                                <b><?php echo $expenseItemsNumber ?> Pieces</b>
                            </div>
                        </div>
                    </div>

                    <!-- Totals Card -->
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                            <div>
                                <i class="fa fa-calendar-alt fs-1 me-3 text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Total Amount</p>
                                <b>KSH <?php echo number_format($totalAmount, 2) ?></b>
                            </div>
                        </div>
                    </div>

                    <!-- Paid Card -->
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                            <div>
                                <i class="fa fa-check-circle fs-1 me-3 text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Paid</p>
                                <b>KSH <?php echo number_format($totalAmountSend, 2) ?></b>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Card -->
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
                            <div>
                                <i class="fa fa-hourglass-half fs-1 me-3 text-warning"></i>
                            </div>
                            <div>
                                <p class="mb-0" style="font-weight: bold;">Pending</p>
                                <b>KSH <?php echo number_format($TotalRemaining, 2) ?></b>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fourth Row: add expense -->
                <div class="row mb-2">
                    <div class="col-md-12 mb-2">
                        <div class="card border-0">
                            <div class="bg-white p-1 rounded-2 border-0">
                                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #e9ecef;">
                                    <a class="fw-bold text-decoration-none text-dark" data-bs-toggle="collapse" href="#addExpense" role="button" aria-expanded="false" aria-controls="addExpense" onclick="toggleIcon(this)">
                                        <span id="toggleIcon">➕</span> Click Here to Add an Expense
                                    </a>
                                </div>
                            </div>
                            <!-- ✅ Fixed & Complete Expense Form -->
                            <div class="collapse" id="addExpense">
                                <div class="card-body border-top border-2">
                                    <div class="alert mb-4" style="background-color: #FFF3CD; color: #856404; border: 1px solid #FFE8A1;">
                                        <i class="fas fa-exclamation-circle mr-2"></i> Please fill out all fields carefully to avoid delays.
                                    </div>
                                    <div class="card mb-3 shadow-sm border-0">
                                        <div class="card-body">
                                            <form method="POST" id="expenseForm">
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold">Building</label>
                                                        <select class="form-control shadow-sm" name="building_id" required>
                                                            <option value="">Select Building</option>
                                                            <?php foreach ($buildings as $building): ?>
                                                                <option value="<?= (int)$building['id'] ?>">
                                                                    <?= htmlspecialchars($building['building_name']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold">Date</label>
                                                        <input type="date" class="form-control rounded-1 shadow-none" name="date">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold">Expense No</label>
                                                        <input type="text" class="form-control rounded-1 shadow-none"
                                                            name="expense_no" placeholder="KRA000100039628">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label fw-bold">Supplier</label>
                                                        <input class="form-control rounded-1 shadow-none"
                                                            list="supplierList"
                                                            name="supplier_name"
                                                            placeholder="Search or select supplier">
                                                        <datalist id="supplierList">
                                                            <?php foreach ($suppliers as $supplier): ?>
                                                                <option
                                                                    value="<?= htmlspecialchars($supplier['supplier_name']) ?>"
                                                                    data-id="<?= htmlspecialchars($supplier['id']) ?>">
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </datalist>
                                                        <input type="hidden" name="supplier_id" id="supplier_id">
                                                        <small id="supplierError" class="text-danger d-none">The supplier doesn't exist. Please register them to continue.</small>
                                                    </div>

                                                </div>
                                                <!-- Hidden total -->
                                                <div class="row no-wrap mt-2">
                                                    <div class="text-muted mt-4 mb-4">Add the Spend items in the fields below</div>
                                                    <div class="col-md-12 rounded-2" id="itemsContainer">
                                                        <div class="row item-row g-3 mb-5 p-2" style="background-color: #f5f5f5; overflow:auto; white-space:nowrap;">
                                                            <!-- ITEM(SERVICE) -->
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-bold">ITEM(SERVICE)</label>
                                                                <select class="form-select shadow-none rounded-1" name="item_account_code[]" style="width: 100%;">
                                                                    <option value="" disabled selected>Select</option>
                                                                    <?php foreach ($accountItems as $item): ?>
                                                                        <option value="<?= htmlspecialchars($item['account_code']) ?>">
                                                                            <?= htmlspecialchars($item['account_name']) ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>

                                                            <!-- Description -->
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-bold">Description</label>
                                                                <input type="text" class="form-control description rounded-1 shadow-none" placeholder="Electricity" name="description[]" required />
                                                            </div>

                                                            <!-- Quantity -->
                                                            <div class="col-md-1">
                                                                <label class="form-label fw-bold">Qty</label>
                                                                <input type="number" class="form-control qty rounded-1 shadow-none" placeholder="1" name="qty[]" required />
                                                            </div>

                                                            <!-- Unit Price & Taxes -->
                                                            <div class="col-md-3 d-flex align-items-stretch">
                                                                <div class="unitPrice me-2 flex-grow-1">
                                                                    <label class="form-label fw-bold">Unit Price</label>
                                                                    <input type="number" class="form-control unit-price rounded-1 shadow-none" placeholder="123" name="unit_price[]" required />
                                                                </div>
                                                                <div class="taxes flex-grow-1">
                                                                    <label class="form-label fw-bold">Taxes</label>
                                                                    <select class="form-select rounded-1 shadow-none ellipsis-select" name="taxes[]" required>
                                                                        <option value="" selected disabled>Select--</option>
                                                                        <option value="inclusive">VAT 16% Inclusive</option>
                                                                        <option value="exclusive">VAT 16% Exclusive</option>
                                                                        <option value="zerorated">Zero Rated</option>
                                                                        <option value="exempted">Exempted</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Discount -->
                                                            <div class="col-md-2">
                                                                <label class="form-label fw-bold">Discount(%)</label>
                                                                <input type="number" class="form-control discount shadow-none rounded-1 mb-1" name="discount[]" placeholder="Ksh 0.00" required>
                                                            </div>

                                                            <!-- Total & Delete -->
                                                            <div class="col-md-2 d-flex align-items-stretch">
                                                                <div class="flex-grow-1 me-2">
                                                                    <label class="form-label fw-bold">Total (KSH)</label>
                                                                    <input type="text" class="form-control item-total shadow-none rounded-1 mb-1" placeholder="Ksh 0.00" name="item_total[]" required readonly />
                                                                    <input type="hidden" class="form-control item_totalForStorage shadow-none rounded-1 mb-1" placeholder="Ksh 0.00" name="item_totalForStorage[]" required readonly />
                                                                </div>
                                                                <div class="d-flex align-items-end">
                                                                    <label class="form-label fw-bold invisible">X</label>
                                                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#editPersonalInfoModal">
                                                                        <i class="fas fa-trash text-white"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Spend items table -->
                                                <div class="row mt-4 ">
                                                    <div class="col-md-12 d-flex justify-content-end">

                                                        <div class="d-flex justify-content-end">

                                                            <div class="d-flex flex-column align-items-end">

                                                                <div class="d-flex justify-content-end w-100 mb-2">
                                                                    <label class="me-2 border-end pe-3 text-end w-50"><strong>Untaxed Amount:</strong></label>
                                                                    <input type="text" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="subTotal" name="" value="Ksh 10,500">
                                                                    <input type="hidden" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="subTotalhidden" name="untaxedAmount" value="Ksh 10,500">
                                                                </div>

                                                                <div class="d-flex justify-content-end w-100 mb-2" id="vatAmountInclusiveContainer" style="display:none !important;">
                                                                    <label class="me-2 border-end pe-3 text-end w-50"><strong id="taxLabel">VAT 16% (Inclusive):</strong></label>
                                                                    <input type="text" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="vatAmountInclusive" value="Ksh 1,500">
                                                                </div>

                                                                <div class="d-flex justify-content-end w-100 mb-2" id="vatAmountExclusiveContainer" style="display: none !important;">
                                                                    <label class="me-2 border-end pe-3 text-end w-50"><strong id="taxLabel">VAT 16% (Exlusive):</strong></label>
                                                                    <input type="text" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="vatAmountExclusive" value="Ksh 1,500">

                                                                </div>

                                                                <div class="d-flex justify-content-end w-100 mb-2" id="vatAmountContainer" style="display: none;">
                                                                    <label class="me-2 border-end pe-3 text-end w-50"><strong id="taxLabel">VAT 16% :</strong></label>
                                                                    <input type="text" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="vatAmountTotal" value="Ksh 0.00">
                                                                    <input type="hidden" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="vatAmountTotalHidden" name="totalTax" value="Ksh 0.00">
                                                                </div>

                                                                <div class="d-flex justify-content-end w-100 mb-2" id="ExemptedContainer" style="display: none;">
                                                                    <label class="me-2 border-end pe-3 text-end w-50"><strong id="taxLabel">Exempted</strong></label>
                                                                    <input type="text" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" name="Exempted[]" id="Exempted" value="Ksh 0.00">
                                                                </div>

                                                                <div class="d-flex justify-content-end w-100 mb-2" id="zeroRatedContainer" style="display: none;">
                                                                    <label class="me-2 border-end pe-3 text-end w-50"><strong id="taxLabel">VAT 0%:</strong></label>
                                                                    <input type="text" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="zeroRated" value="Ksh 0.00">
                                                                </div>

                                                                <div class="d-flex justify-content-end w-100 mb-2" id="grandDiscountContainer">
                                                                    <label class="me-2 border-end pe-3 text-end w-50"><strong>Discount:</strong></label>
                                                                    <input type="text" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="grandDiscount" value="Ksh 0:00">
                                                                </div>

                                                                <div class="d-flex justify-content-end w-100 mt-3 pt-2 border-top border-warning">
                                                                    <label class="me-2 border-end pe-3 text-end w-50"><strong>Total Amount Due:</strong></label>
                                                                    <input type="hidden" name="total" id="grandTotalNumber" value="0.00" />
                                                                    <input type="text" readonly class="form-control-plaintext w-50 ps-3 fw-bold" id="grandTotal" value="Ksh 12,000">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-md-12 d-flex justify-content-between">
                                                        <button type="button" class="btn btn-outline-warning text-dark shadow-none" onclick="addRow()">➕ Add More</button>
                                                        <button type="submit" name="create_expense" id="submitBtn" class="btn btn-outline-warning text-dark shadow-none" disabled>✅ Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fifth Row: filter -->
                <div class="row g-3 mb-4">
                    <!-- Filter by Building -->
                    <div class="col-md-12 col-sm-12">
                        <div class="card border-0 mb-4">
                            <div class="card-body ">
                                <h5 class="card-title mb-3"><i class="fas fa-filter"></i> Filters</h5>
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
                                                    placeholder="Supplier or expense no..."
                                                    value="<?= htmlspecialchars($search ?? '') ?>">
                                            </div>

                                            <div class="col-auto filter-col">
                                                <label class="form-label text-muted small">Buildings</label>
                                                <select class="form-select shadow-sm" name="building_id">
                                                    <option value="">All Buildings</option>
                                                    <?php foreach ($buildings as $building): ?>
                                                        <?php $bid = (string)(int)$building['id']; ?>
                                                        <option value="<?= $bid ?>" <?= (($building_id ?? '') === $bid) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($building['building_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-auto filter-col">
                                                <label class="form-label text-muted small">Status</label>
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
                                                <label class="form-label text-muted small">Date From</label>
                                                <input
                                                    type="date"
                                                    name="date_from"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($date_from ?? '') ?>">
                                            </div>

                                            <div class="col-auto filter-col">
                                                <label class="form-label text-muted small">Date To</label>
                                                <input
                                                    type="date"
                                                    name="date_to"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($date_to ?? '') ?>">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end">
                                        <!-- Replace with your real page name -->
                                        <a href="expenses.php" class="btn btn-secondary">
                                            <i class="fas fa-redo"></i> Reset
                                        </a>

                                        <button type="submit" class="applyFilterBtn">
                                            <i class="fas fa-search"></i> Apply Filters
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sixth Row: Expenses Table -->
                <div class="row mt-2 mb-5">
                    <div class="col-md-12">
                        <div class="details-container bg-white p-2 rounded Content">
                            <h3 class="details-container_header text-start">
                                <span id="displayed_building">All Expenses</span> &nbsp; |&nbsp;
                                <span style="color:#FFC107"> <span id="enteries"><?= count($currentExpenses) ?></span> entries</span>
                            </h3>

                            <?php if (empty($currentExpenses)): ?>
                                <!-- Empty State Message -->
                                <div class="text-center py-5" style="margin: 3rem 0;">
                                    <div style="background-color: #f8f9fa; border-radius: 16px; padding: 3rem 2rem; max-width: 500px; margin: 0 auto;">

                                        <div style="font-size: 4rem; color: #00192D; margin-bottom: 1rem;">
                                            <i class="bi <?= $hasFilters ? 'bi-search' : 'bi-receipt' ?>"></i>
                                        </div>

                                        <h4 style="color: #00192D; font-weight: 600; margin-bottom: 1rem;">
                                            <?= $hasFilters ? 'No Results Found' : 'No Expense Items Found' ?>
                                        </h4>

                                        <p style="color: #6c757d; font-size: 1rem; margin-bottom: 1.5rem;">
                                            <?php if ($hasFilters): ?>
                                                No expenses match your selected filters. Try adjusting or clearing filters.
                                            <?php else: ?>
                                                Start tracking your finances by adding your first expense.
                                            <?php endif; ?>
                                        </p>

                                        <?php if ($hasFilters): ?>
                                            <a href="expenses.php" class="btn btn-secondary">
                                                <i class="fas fa-redo"></i> Clear Filters
                                            </a>
                                        <?php else: ?>
                                            <!-- Optional: show your "Add Expense" button here -->
                                            <!-- <button class="btn" style="background:#00192D;color:#FFC107">Add Expense</button> -->
                                        <?php endif; ?>

                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Expenses Table -->
                                <div class="table-responsive" style="overflow-x: auto;">
                                    <table id="expensesTable" class="expensesTable" style="width: 100%; min-width: 600px;">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Supplier</th>
                                                <th>Expense No</th>
                                                <th>Totals <span style="text-transform: lowercase;">Vs</span> paid</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($currentExpenses as $exp): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars(date('d M Y', strtotime($exp['created_at']))) ?></td>
                                                    <td><?= htmlspecialchars($exp['supplier_name']) ?></td>
                                                    <td>
                                                        <div style="color:#28a745;"><?= htmlspecialchars($exp['expense_no']) ?></div>
                                                    </td>
                                                    <td style="background-color: #f8f9fa; padding: 0.75rem; border-radius: 8px;">
                                                        <div style="font-weight: 600; color: #00192D; font-size: 1rem;">
                                                            KSH <?= number_format($exp['total'], 2) ?>
                                                        </div>
                                                        <div class="paid_amount" style="color: #007B8A; font-size: 0.9rem; margin-top: 4px;">
                                                            KSH <?= number_format($exp['total_paid'] ?? 0, 2) ?>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <?php
                                                        $status = strtolower($exp['status']);
                                                        $statusLabel = '';
                                                        $editButton = '<span class="edit-payment-btn"'
                                                            . ' style="background-color: #00192D; color: #FFC107; padding: 6px 10px; border-radius: 50%; cursor: pointer;"'
                                                            . ' data-bs-toggle="modal" data-amount="' . number_format($exp['id']) . '" data-bs-target="#editPaymentModal">'
                                                            . '<i class="bi bi-pencil"></i>'
                                                            . '</span>';

                                                        if ($status === 'paid') {
                                                            $statusLabel = '<span style="background-color: #28a745; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">Paid</span> ' . $editButton;
                                                        } elseif ($status === 'overpaid') {
                                                            $statusLabel = '<span style="background-color: #28a745; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">Overpaid</span> ' . $editButton;
                                                        } elseif ($status === 'partially paid') {
                                                            $statusLabel = '<span style="background-color: #17a2b8; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">Partially Paid</span> ' . $editButton;
                                                        } elseif ($status === 'unpaid') {
                                                            $statusLabel = '<span style="background-color: #FFC107; color: #00192D; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">Unpaid</span>';
                                                        } else {
                                                            $statusLabel = '<span class="text-muted">' . htmlspecialchars($exp['status']) . '</span>';
                                                        }

                                                        echo $statusLabel;
                                                        ?>

                                                        <?php if ($status === 'unpaid' || $status === 'partially paid'): ?>
                                                            <br>
                                                            <button
                                                                class="btn btn-sm d-inline-flex align-items-center gap-1 mt-2"
                                                                style="background-color: #00192D; color: #FFC107; border: none; border-radius: 8px; padding: 6px 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); font-weight: 500;"
                                                                data-action="pay-expense"
                                                                data-expense-id="<?= (int)$exp['id'] ?>"
                                                                data-expected-amount="<?= htmlspecialchars($exp['total'], ENT_QUOTES, 'UTF-8') ?>">
                                                                <i class="bi bi-credit-card-fill"></i>
                                                                Pay
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td>
                                                        <button
                                                            class="btn btn-sm d-flex align-items-center gap-1 px-3 py-2"
                                                            style="background-color: #00192D; color: white; border: none; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); font-weight: 500;"
                                                            onclick="openExpenseModal(<?= $exp['id'] ?>)">
                                                            <i class="bi bi-eye-fill"></i> View
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <?php if ($totalPages > 1): ?>
                                        <nav aria-label="Expense pagination">
                                            <ul class="pagination justify-content-center mt-2">
                                                <!-- Previous Button -->
                                                <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : ''; ?>">
                                                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>

                                                <!-- Page Numbers -->
                                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                    <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                    </li>
                                                <?php endfor; ?>

                                                <!-- Next Button -->
                                                <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : ''; ?>">
                                                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>

                                        <!-- Pagination Info -->
                                        <div class="pagination-info text-center mb-4">
                                            <p class="text-muted">
                                                Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $itemsPerPage, $totalItems); ?>
                                                of <?php echo $totalItems; ?> requests
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Seventh Row: Graph -->
                <div class="row graph">
                    <div class="col-md-12">
                        <div class="bg-white p-2 rounded">
                            <?php
                            // Group expenses by month and sum totals
                            $monthlyTotals = [];
                            try {
                                $stmt = $pdo->query("SELECT MONTH(expense_date) AS month, SUM(total) AS total FROM expenses GROUP BY MONTH(expense_date)");
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $monthNum = (int)$row['month'];
                                    $monthlyTotals[$monthNum] = (float)$row['total'];
                                }
                            } catch (PDOException $e) {
                                $monthlyTotals = [];
                            }
                            ?>
                            <!-- Line Chart: Expenses vs Months -->
                            <h6 class="fw-bold text-center">📊 Monthly Expense Trends</h6>
                            <canvas id="monthlyExpenseChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <!--end::Container-->
            </div>
        </main>
        <!--end::App Main-->

        <!--begin::Footer-->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
        <!--end::Footer-->
        <!-- Modals -->

        <!-- View Expense Modal -->
        <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content expense bg-light">
                    <div class="d-flex justify-content-between align-items-center p-2" style="background-color: #EAF0F4; border-bottom: 1px solid #CCC; border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                        <button class="btn btn-sm me-2" style="background-color: #00192D; color: #FFC107;" title="Download PDF" id="downloadExpPdf">
                            <i class="bi bi-download"></i>
                        </button>
                        <button class="btn btn-sm me-2" style="background-color: #00192D; color: #FFC107;" title="Print">
                            <i class="bi bi-printer"></i>
                        </button>
                        <button type="button" class="btn btn-sm" style="background-color: #FFC107; color: #00192D;" data-bs-dismiss="modal" title="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="modal-body bg-light" id="expenseModalBody">

                        <!-- 🔒 DO NOT TOUCH CARD BELOW -->
                        <div class="expense-card" id="expenseCard">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-stretch mb-3 position-relative" style="overflow: hidden;">
                                <div>
                                    <img id="expenseLogo" src="images/expensePdfLogo.png" alt="JengoPay Logo" class="expense-logo">
                                </div>

                                <!-- Diagonal PAID Label centered in the container -->
                                <!-- <div class="diagonal-paid-label">PAID</div> -->
                                <div class="diagonal-unpaid-label" id="expenseModalPaymentStatus">UNPAID</div>
                                <div class="address text-end" style="background-color: #f0f0f0; padding: 10px; border-radius: 8px;">
                                    <strong> <span id="building_name">Silver Spoon Towers</span> </strong><br>
                                    50303 Nairobi, Kenya<br>
                                    silver@gmail.com<br>
                                    +254 700 123456
                                </div>
                            </div>


                            <!-- expense Info -->
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0" id="expenseModalSupplierName">Josephat Koech</h6>
                                <div class="text-end">
                                    <h3 id="expenseModalInvoiceNo"> INV001</h3><br>
                                </div>
                            </div>

                            <div class="mb-1 rounded-2 d-flex justify-content-between align-items-center"
                                style="border: 1px solid #FFC107; padding: 4px 8px; background-color: #FFF4CC;">
                                <div class="d-flex flex-column expense-date m-0">
                                    <span class="m-0"><b>Expense Date</b></span>
                                    <p class="m-0">24/6/2025</p>
                                </div>
                                <div class="d-flex flex-column due-date m-0">
                                    <span class="m-0"><b>Due Date</b></span>
                                    <p class="m-0">24/6/2025</p>
                                </div>
                                <div></div>
                            </div>

                            <!-- Items Table -->
                            <div class="table-responsive ">
                                <table class="table table-striped table-bordered rounded-2 table-sm thick-bordered-table">
                                    <thead class="table">
                                        <tr class="custom-th text-dark">
                                            <th>Description</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Taxes</th>
                                            <th class="text-end">Discount</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="expenseItemsTableBody">
                                        <tr>
                                            <td>Web Design</td>
                                            <td class="text-end">1</td>
                                            <td class="text-end">KES 25,000</td>
                                            <td class="text-end">Inclusive</td>
                                            <td class="text-end">KES 25,000</td>
                                            <td class="text-end">KES 25,000</td>
                                        </tr>
                                        <tr>
                                            <td>Hosting (1 year)</td>
                                            <td class="text-end">1</td>
                                            <td class="text-end">KES 5,000</td>
                                            <td class="text-end">Exclusive</td>
                                            <td class="text-end">KES 25,000</td>
                                            <td class="text-end">KES 5,000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Totals and Terms -->
                            <div class="row">
                                <div class="col-6 terms-box">
                                    <strong>Note:</strong><br>
                                    This Expense Note Belongs to.<br>
                                    Silver Spoon Towers
                                    <br>
                                    <br>
                                    <div class="overPaymentNote" id="overPaymentNote" style="display:none">
                                        <p class="text-dark mb-0" style="">Paid:- <span class="text-dark" id="overPaidAmount"></span></p>
                                        <p class="text-dark mb-0">Prepaid:- <b><span id="prepaidAmount" class="text-success"></span></b> </p>
                                    </div>
                                    <div id="patialPaymentNote" style="display:none">
                                        <p class="text-dark mb-0" id="patialPaymentNote" style="">Paid:- <span class="text-dark" id="partalPaidAmount"></span></p>
                                        <p class="text-dark mb-0">Balance:- <b><span id="balanceAmount" class="text-danger"></span></b></p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <table class="table table-borderless table-sm text-end mb-0">
                                        <tr>
                                            <th>Untaxed Amount:</th>
                                            <td>
                                                <div id="expenseModalUntaxedAmount">KES 30,000</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>VAT (16%):</th>
                                            <td>
                                                <div id="expenseModalTaxAmount">KES 4,800</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total Amount:</th>
                                            <td><strong id="expenseModalTotalAmount">KES 34,800</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <hr>
                            <div class="text-center small text-muted" style="border-top: 1px solid #e0e0e0; padding-top: 10px;">
                                Thank you for your business!
                            </div>
                        </div>
                        <!-- 🔚 END CARD -->
                    </div>
                </div>
            </div>
        </div>
        <!-- for expense pdf -->
        <!-- for pdf -->
        <div id="printArea"></div>

        <!-- Edit Payment Modal -->
        <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-2 bg-white" style=" border: 1px solid #00192D;">

                    <!-- Header -->
                    <div class="modal-header border-bottom align-items-center" style="padding: 0.75rem 1rem; background-color: #EAF0F4;">
                        <h3 class="modal-title m-0" id="editPaymentLabel"
                            style="font-size: 1.25rem; font-weight: 600; color: #00192D;">
                            <i class="bi bi-pencil" style="margin-right: 6px; color: #00192D;"></i>
                            Edit Payments
                            <span style="font-weight: 400; font-size: 1rem; color: #6c757d;">
                                KRACU0100039628
                            </span>
                        </h3>
                        <button type="button" class="btn btn-sm" style="background-color: #FFC107; color: #00192D;" title="Close" id="closePaymentEditModalHeader">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body p-4">
                        <!-- Forms from js-->
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer p-4">
                        <button type="button" class="btn btn-secondary btn-sm" id="closePaymentEditModalFooter">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- payment modal -->
        <div class="modal fade" id="payExpenseModal" tabindex="-1" aria-labelledby="payExpenseLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 12px; border: 1px solid #00192D;">
                    <div class="modal-header" style="background-color: #00192D; color: white;">
                        <h5 class="modal-title" id="payExpenseLabel">Pay Expense</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="payExpenseForm">
                            <!-- id -->
                            <input type="hidden" name="expense_id" id="expenseId">
                            <!-- total amount -->
                            <input type="hidden" name="expected_amount" id="expectedAmount">

                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount to Pay(KSH)</label>
                                <input type="number" step="0.01" class="form-control shadow-none rounded-1" id="amountToPay" style="font-weight: 600;" name="amountToPay" value="1200" required>
                            </div>

                            <div class="mb-3">
                                <label for="paymentDate" class="form-label shadow-none ">Payment Date</label>
                                <input type="date" class="form-control shadow-none rounded-1" id="paymentDate" name="payment_date" required>
                                <small id="paymentMsg" style="color:red;"></small> <!-- error/success message -->
                            </div>

                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select class="form-select shadow-none rounded-1" id="paymentMethod" name="payment_account_id" required>
                                    <option value="100">Cash</option>
                                    <option value="110">M-Pesa</option>
                                    <option value="120">Bank Transfer</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="reference" class="form-label">Reference / Memo</label>
                                <input type="text" class="form-control shadow-none rounded-1" id="reference" name="reference">
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="payExpenseForm" id="confirmPaymentBtn" class="btn" style="background-color: #FFC107; color: #00192D;">
                            <i class="bi bi-credit-card"></i> Confirm Payment
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Previous year date warning -->
        <div class="modal fade" id="fyWarningModal" tabindex="-1" aria-labelledby="fyWarningLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #F8FAFC; border: 1px solid #193A4D;">
                    <div class="modal-header" style="background-color: #193A4D; color: white;">
                        <h5 class="modal-title" id="fyWarningLabel">⚠ Previous Financial Year</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="color: #193A4D;">
                        You’ve selected a date from the previous financial year.<br>
                        Are you sure you want to continue?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background-color: #FFC107; color: #193A4D;" id="confirmFY">Yes, continue</button>
                        <button type="button" class="btn btn-light" id="cancelFY" data-bs-dismiss="modal">No, cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create new supplier -->
        <!-- Offcanvas (RIGHT SIDE, FULL HEIGHT, 1/4 WIDTH) -->
        <div
            class="offcanvas offcanvas-end"
            tabindex="-1"
            id="supplierOffcanvas"
            aria-labelledby="supplierOffcanvasLabel"
            style="width: 25vw; min-width: 320px;">
            <!-- Header -->
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title" id="supplierOffcanvasLabel">
                    Register New Supplier
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="offcanvas-body">
                <form id="supplierRegForm" action="actions/registerSupplier.php" method="POST">
                    <div id="submitMsg" class="text-danger mb-2"></div>

                    <!-- Supplier Name -->
                    <div class="mb-3">
                        <label class="form-label" for="supplierName">Supplier Name</label>
                        <input
                            type="text"
                            class="form-control"
                            id="supplierName"
                            name="name"
                            required>
                        <small id="supplierNameMsg" class="text-danger"></small>
                    </div>

                    <!-- Email (required) -->
                    <div class="mb-3">
                        <label class="form-label" for="supplierEmail">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            id="supplierEmail"
                            name="email"
                            required>
                    </div>

                    <!-- Phone (required) -->
                    <div class="mb-3">
                        <label class="form-label" for="supplierPhone">Phone</label>
                        <input
                            type="text"
                            class="form-control"
                            id="supplierPhone"
                            name="phone"
                            required>
                    </div>

                    <!-- KRA -->
                    <div class="mb-3">
                        <label class="form-label" for="supplierKra">KRA Number</label>
                        <input
                            type="text"
                            class="form-control"
                            id="supplierKra"
                            name="kra"
                            required>
                        <small id="supplierKraMsg" class="text-danger"></small>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label class="form-label" for="supplierAddress">Address</label>
                        <input
                            type="text"
                            class="form-control"
                            id="supplierAddress"
                            name="address"
                            required>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-2 mt-4">
                        <button
                            type="button"
                            class="btn btn-secondary flex-grow-1"
                            data-bs-dismiss="offcanvas">
                            Cancel
                        </button>

                        <button
                            type="submit"
                            id="supplierSubmitBtn"
                            class="btn text-white flex-grow-1"
                            style="background: linear-gradient(135deg, #00192D, #002B5B); white-space: nowrap;"
                            name="register_supplier">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Edit Supplier details -->
        <div class="supplierEdit-modal-overlay" id="supplierEditOverlay"></div>
        <div class="supplierEdit-modal" id="supplierEditModal">
            <button class="supplierEdit-close-btn" id="supplierEditCloseBtn">X</button>
            <div class="d-flex">
                <h4>
                    <i class="fas fa-user-plus"></i> Edit Supplier Details
                </h4>


            </div>

            <form id="supplierEditForm" class="supplierEdit-form">
                <label for="supplierEditKra">KRA Number</label>
                <input type="text" id="supplierEditKra" name="kra" required>

                <label for="supplierEditName">Supplier Name</label>
                <input type="text" id="supplierEditName" name="name" required>

                <label for="supplierEditEmail">Email</label>
                <input type="email" id="supplierEditEmail" name="email" required>

                <label for="supplierEditPhone">Phone</label>
                <input type="text" id="supplierEditPhone" name="phone">

                <label for="supplierEditAddress">Address</label>
                <input type="text" id="supplierEditAddress" name="address">

                <!-- id -->
                <input type="hidden" id="supplierEditId" name="supplierEditId" value="">
                <div class="supplierEdit-form-actions">
                    <button type="submit" class="supplierEdit-submit-btn">Save</button>
                    <button type="button" class="supplierEdit-cancel-btn" id="supplierEditCancelBtn">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Registerd suppliers list -->
        <!-- Overlay -->
        <div class="supplier-list-overlay" id="supplierListOverlay"></div>

        <!-- Modal -->
        <div class="supplier-list-modal" id="supplierListModal">
            <div class="supplier-list-header">
                <h2>Suppliers</h2>
                <button class="supplier-list-close-btn" id="supplierListCloseBtn">X</button>
            </div>

            <!-- Search bar -->
            <div class="supplier-list-search">
                <input type="text" id="supplierSearchInput" placeholder="Search by name...">
                <button id="supplierSearchBtn">Search</button>
            </div>

            <!-- Supplier list -->
            <table class="supplier-list-table">
                <thead class="supplier-list-tableThead border-0">
                    <tr class="border-0">
                        <th class="border-0">Name</th>
                        <th>KRA PIN</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Supplied Items</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="supplier-list-tableTbody">
                    <?php foreach ($suppliers as $supplier): ?>
                        <tr class="supplier-list-tableTr shadow-sm text-muted">
                            <td><?= $supplier['supplier_name'] ?></td>
                            <td><?= $supplier['kra_pin'] ?></td>
                            <td><?= $supplier['address'] ?>, Nairobi</td>
                            <td>
                                <div class="" style="color:green;"><?= $supplier['email'] ?></div>
                                <div class="text-primary"><?= $supplier['phone'] ?></div>
                            </td>
                            <td>128</td>
                            <td style="vertical-align: middle;">
                                <div style="display: flex; gap: 8px; align-items: center; height: 100%;">
                                    <button
                                        class="editSupplier btn btn-sm d-flex align-items-center gap-1 px-3 py-2"
                                        style="background-color: #00192D; color: white; border: none; border-radius: 8px; 
                               box-shadow: 0 2px 6px rgba(0,0,0,0.1); font-weight: 500;"
                                        data-id="<?= $supplier['id'] ?>"
                                        data-name="<?= htmlspecialchars($supplier['supplier_name'], ENT_QUOTES) ?>"
                                        data-kra="<?= htmlspecialchars($supplier['kra_pin'], ENT_QUOTES) ?>"
                                        data-address="<?= htmlspecialchars($supplier['address'], ENT_QUOTES) ?>"
                                        data-email="<?= htmlspecialchars($supplier['email'], ENT_QUOTES) ?>"
                                        data-phone="<?= htmlspecialchars($supplier['phone'], ENT_QUOTES) ?>">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button
                                        class="btn btn-sm d-flex align-items-center gap-1 px-3 py-2"
                                        style="background-color: #ec5b53; color: white; border: none; border-radius: 8px; 
                                                box-shadow: 0 2px 6px rgba(0,0,0,0.1); font-weight: 500;">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

    </div>
    <!--end::App Wrapper-->

    <!-- plugin for pdf -->


    <!-- Main Js File -->
    <script src="../../../assets/main.js"></script>
    <!-- html2pdf depends on html2canvas and jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script type="module" src="./js/main.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <!-- pdf download plugin -->


    <!-- J  A V A S C R I PT -->


    <!-- links for dataTaable buttons -->

    <!-- apexcharts -->
    <script
        src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
        crossorigin="anonymous"></script>

    <!--end::Script-->
    <!-- dataTable control -->
    <!-- DATE TABLES -->
    </script>
    <script>
        $(document).ready(function() {
            const table = $('#repaireExpenses').DataTable({
                dom: 'Brtip', // ⬅ Changed to include Buttons in DOM
                order: [], // ⬅ disables automatic ordering by DataTables
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        exportOptions: {
                            columns: ':not(:last-child)' // ⬅ Exclude last column
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        exportOptions: {
                            columns: ':not(:last-child)' // ⬅ Exclude last column
                        },
                        customize: function(doc) {
                            // Center table
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                            // Optional: center-align the entire table
                            doc.styles.tableHeader.alignment = 'center';
                            doc.styles.tableBodyEven.alignment = 'center';
                            doc.styles.tableBodyOdd.alignment = 'center';

                            const body = doc.content[1].table.body;
                            for (let i = 1; i < body.length; i++) { // start from 1 to skip header
                                if (body[i][4]) {
                                    body[i][4].color = 'blue'; // set email column to blue
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: ':not(:last-child)' // ⬅ Exclude last column from print
                        }
                    }
                ]
            });
            // Append buttons to your div
            table.buttons().container().appendTo('#custom-buttons');
            // Custom search
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>

    <!-- date display only Previos dates -->
    <script>
        const dateInput = document.getElementById('dateInput');
        let tempDate = null;

        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('max', today);

        const fyModalElement = document.getElementById('fyWarningModal');
        const fyModal = new bootstrap.Modal(fyModalElement);

        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const now = new Date();

            // Financial year: Calendar year (Jan 1 – Dec 31)
            const yearStart = new Date(now.getFullYear(), 0, 1); // January 1
            const yearEnd = new Date(now.getFullYear(), 11, 31); // December 31

            // Check if selected date is outside current calendar year
            if (selectedDate < yearStart || selectedDate > yearEnd) {
                tempDate = this.value;
                fyModal.show();
            }
        });

        // "No, cancel" button
        document.getElementById('cancelFY').addEventListener('click', function() {
            dateInput.value = ""; // Clear input
            tempDate = null;
            fyModal.hide(); // Hide modal manually
        });

        // "Yes, continue" button
        document.getElementById('confirmFY').addEventListener('click', function() {
            fyModal.hide(); // Simply hide modal, keep selected date
        });
    </script>



    <script>
        function toggleIcon(anchor) {
            console.log('yoyo');
            const icon = anchor.querySelector('#toggleIcon');
            const isExpanded = anchor.getAttribute('aria-expanded') === 'true';
            icon.textContent = isExpanded ? '➕' : '✖';
        }

        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        function addRow() {
            // You'll need to implement dynamic row cloning if required.
            alert('Add row functionality goes here');
        }
    </script>

    <!-- Chart Section -->
    <!-- <canvas id="monthlyExpenseChart" height="100"></canvas> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let expenseChart;
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('monthlyExpenseChart').getContext('2d');
            expenseChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: "KSH Expenses",
                        data: [
                            <?= $monthlyTotals[1] ?? 0 ?>, <?= $monthlyTotals[2] ?? 0 ?>,
                            <?= $monthlyTotals[3] ?? 0 ?>, <?= $monthlyTotals[4] ?? 0 ?>,
                            <?= $monthlyTotals[5] ?? 0 ?>, <?= $monthlyTotals[6] ?? 0 ?>,
                            <?= $monthlyTotals[7] ?? 0 ?>, <?= $monthlyTotals[8] ?? 0 ?>,
                            <?= $monthlyTotals[9] ?? 0 ?>, <?= $monthlyTotals[10] ?? 0 ?>,
                            <?= $monthlyTotals[11] ?? 0 ?>, <?= $monthlyTotals[12] ?? 0 ?>
                        ],
                        backgroundColor: "rgba(0, 25, 45, 0.2)",
                        borderColor: "#00192D",
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: "#FFC107",
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'KSH ' + value.toLocaleString()
                            }
                        }
                    }
                }
            });
        });
    </script>

    <!-- Expense modal -->
    <script>
        // -------- helpers --------
        const $ = (id) => document.getElementById(id);

        const money = (n) => `KES ${Number(n || 0).toLocaleString()}`;

        function setText(id, value, fallback = '—') {
            $(id).textContent = (value ?? '') !== '' ? value : fallback;
        }

        function show(id) {
            $(id).style.display = 'block';
        }

        function hide(id) {
            $(id).style.display = 'none';
        }

        function resetPaymentNotes() {
            hide('overPaymentNote');
            hide('patialPaymentNote');
            setText('overPaidAmount', '');
            setText('prepaidAmount', '');
            setText('partalPaidAmount', '');
            setText('balanceAmount', '');
        }

        function setStatusBadge(statusRaw, expense) {
            const el = $('expenseModalPaymentStatus');

            // reset classes
            el.classList.remove('diagonal-paid-label', 'diagonal-unpaid-label', 'diagonal-partially-paid-label');
            resetPaymentNotes();

            const status = String(statusRaw || '').trim().toLowerCase();

            if (status === 'paid') {
                el.textContent = 'PAID';
                el.classList.add('diagonal-paid-label');
                return;
            }

            if (status === 'overpaid') {
                el.textContent = 'PAID';
                el.classList.add('diagonal-paid-label');

                show('overPaymentNote');
                const total = Number(expense.total || 0);
                const paid = Number(expense.amount_paid || 0);
                const prepaid = paid - total;

                $('overPaidAmount').textContent = money(paid);
                $('prepaidAmount').textContent = money(prepaid);
                return;
            }

            if (status === 'partially paid' || status === 'partial' || status === 'partially_paid') {
                el.textContent = 'PARTIAL';
                el.classList.add('diagonal-partially-paid-label');

                show('patialPaymentNote');
                const total = Number(expense.total || 0);
                const paid = Number(expense.amount_paid || 0);
                const balance = total - paid;

                $('partalPaidAmount').textContent = money(paid);
                $('balanceAmount').textContent = money(balance);
                return;
            }

            // default unpaid
            el.textContent = 'UNPAID';
            el.classList.add('diagonal-unpaid-label');
        }

        function renderExpenseHeader(expense) {
            setText('expenseModalSupplierName', expense.supplier);
            setText('expenseModalInvoiceNo', expense.expense_no);
            setText('building_name', expense.building_name);

            $('expenseModalTotalAmount').textContent = money(expense.total);
            $('expenseModalTaxAmount').textContent = money(expense.total_taxes);
            $('expenseModalUntaxedAmount').textContent = money(expense.untaxed_amount);

            setStatusBadge(expense.status, expense);
        }

        function renderExpenseItems(items) {
            const tableBody = $('expenseItemsTableBody');
            tableBody.innerHTML = '';

            const rowsHtml = items.map((item) => `
      <tr>
        <td>${item.description ?? '—'}</td>
        <td class="text-end">${item.qty ?? 0}</td>
        <td class="text-end">${money(item.unit_price)}</td>
        <td class="text-end">${item.taxes ?? '—'}</td>
        <td class="text-end">${item.discount ?? '—'}%</td>
        <td class="text-end">${money(item.item_total)}</td>
      </tr>
    `).join('');

            tableBody.innerHTML = rowsHtml;
        }

        async function fetchExpense(expenseId) {
            const res = await fetch(`actions/getExpenseItems.php?id=${encodeURIComponent(expenseId)}`);
            if (!res.ok) throw new Error('Failed to fetch data');
            const data = await res.json();
            if (!Array.isArray(data) || data.length === 0) return null;
            return data;
        }

        // -------- main --------
        async function openExpenseModal(expenseId) {
            try {
                const data = await fetchExpense(expenseId);
                if (!data) {
                    console.warn('No expense data found.');
                    return;
                }

                const expense = data[0]; // header fields come from first row
                renderExpenseHeader(expense);
                renderExpenseItems(data);

                const modal = bootstrap.Modal.getOrCreateInstance($('expenseModal'));
                modal.show();
            } catch (err) {
                console.error('Error loading expense:', err);
            }
        }
    </script>



    <!-- select wrapper -->

    <!--  for dropdwon menu, the suppliers and register supplier buttons-->
    <script>
        // Reuse your existing desktop button handlers by triggering them from mobile menu
        document.getElementById('supplier-list-open-btn-mobile')?.addEventListener('click', () => {
            document.getElementById('supplier-list-open-btn')?.click();
        });

        document.getElementById('addSupplier-mobile')?.addEventListener('click', () => {
            document.getElementById('addSupplier')?.click();
        });
    </script>

    <!-- capture supplier id -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const supplierInput = document.querySelector('[list="supplierList"]');
            const supplierIdInput = document.getElementById('supplier_id');
            const submitBtn = document.getElementById('submitBtn');
            const supplierListOptions = document.querySelectorAll('#supplierList option');
            const supplierErrorMsg = document.getElementById('supplierError');

            let debounceTimer;

            // Function to check if supplier exists in the list
            const isValidSupplier = (inputValue) => {
                let valid = false;
                supplierListOptions.forEach(option => {
                    if (option.value.toLowerCase() === inputValue.toLowerCase()) {
                        valid = true;
                        supplierIdInput.value = option.dataset.id;
                    }
                });
                return valid;
            };

            // Debounced validation function
            const validateSupplier = () => {
                const inputValue = supplierInput.value.trim();

                // If input is empty, clear the error message
                if (inputValue === "") {
                    supplierErrorMsg.classList.add('d-none'); // Hide error message if input is empty
                    submitBtn.disabled = true; // Disable submit button until supplier is selected
                    return;
                }

                if (isValidSupplier(inputValue)) {
                    submitBtn.disabled = false;
                    supplierErrorMsg.classList.add('d-none'); // Hide error message if valid supplier
                } else {
                    submitBtn.disabled = true;
                    supplierErrorMsg.classList.remove('d-none'); // Show error message if supplier doesn't exist
                }
            };

            // Event listener for input field
            supplierInput.addEventListener('input', function() {
                clearTimeout(debounceTimer); // Clear the previous timer
                debounceTimer = setTimeout(validateSupplier, 2000); // Wait 500ms after typing stops
            });

            // Initially disable the submit button and hide the error message
            submitBtn.disabled = true;
            supplierErrorMsg.classList.add('d-none');
        });
    </script>

    <!--light weight script tasks  -->
    <script>
        document.getElementById('closePaymentEditModalFooter')
            ?.addEventListener('click', function() {
                location.reload();
            });

        document.getElementById('closePaymentEditModalHeader')
            ?.addEventListener('click', function() {
                location.reload();
            });
    </script>


    <!-- Toast message script-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toast message -->
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