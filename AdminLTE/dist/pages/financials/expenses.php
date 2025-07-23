<?php
require_once '../db/connect.php';

$expenses = [];
$monthlyTotals = array_fill(1, 12, 0);

// === ✅ AJAX: Return Expense Details for Modal Popup ===
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['view_id'])) {
    header('Content-Type: application/json');

    try {
        $stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = ?");
        $stmt->execute([$_GET['view_id']]);
        $expense = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($expense) {
            echo json_encode(['success' => true, 'data' => $expense]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Expense not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'DB error: ' . $e->getMessage()]);
    }
    exit;
}

// === ✅ AJAX: Return Monthly Totals ===
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_totals'])) {
    $stmt = $pdo->query("SELECT MONTH(date) as month, SUM(total) as total FROM expenses GROUP BY MONTH(date)");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthlyTotals[(int)$row['month']] = (float)$row['total'];
    }
    echo json_encode($monthlyTotals);
    exit;
}

// === ✅ AJAX: Handle Expense Submission ===


// === ✅ Normal Page Load ===
try {
    $stmt = $pdo->query("SELECT * FROM expenses ORDER BY created_at DESC");
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT MONTH(expense_date) as month, SUM(total) as total FROM expenses GROUP BY MONTH(expense_date)");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthlyTotals[(int)$row['month']] = (float)$row['total'];
    }
} catch (PDOException $e) {
    $errorMessage = "❌ Failed to fetch expenses: " . $e->getMessage();
}

try {
    $ExpenseItemsQuery = $pdo->prepare("SELECT account_name FROM chart_of_accounts WHERE account_type = :type LIMIT 8");
    $ExpenseItemsQuery->execute(['type' => 'expenses']);
    $items = $ExpenseItemsQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = "❌ Failed to fetch expense items: " . $e->getMessage();
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
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
    <!-- <link rel="stylesheet" href="text.css" /> -->
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->

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


    <!--Tailwind CSS  -->
    <style>
        .app-wrapper {
            background-color: rgba(128, 128, 128, 0.1);

        }

        .modal-backdrop.show {
            opacity: 0.4 !important;
            /* Adjust the value as needed */
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-dark" style="">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <?php include_once '../includes/header.php' ?>
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
            <div> <?php include_once '../includes/sidebar1.php'; ?> </div> <!-- This is where the sidebar is inserted -->
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-8">
                            <h3 class="mb-0 contact_section_header"> 💰 &nbsp; Expenses</h3>
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Container-->
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row g-3 mb-4">
                        <p class="text-muted">Manage your expenses</p>
                        <div class="col-md-3">
                            <div class="custom-select-wrapper">
                                <div class="custom-select shadow-sm" tabindex="0" role="button" aria-haspopup="listbox" aria-expanded="false">
                                    Filter By Building
                                </div>
                                <div class="select-options" id="select-options" role="listbox">
                                    <div role="option" data-value="option1">Manucho</div>
                                    <div role="option" data-value="option2">Silver Spoon</div>
                                    <div role="option" data-value="option3">Ebenezer</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="custom-select-wrapper">
                                <div class="custom-select shadow-sm" tabindex="0" role="button" aria-haspopup="listbox" aria-expanded="false">
                                    Filter By Items
                                </div>
                                <div class="select-options" role="listbox">
                                    <div role="option" data-value="option1">Garbage</div>
                                    <div role="option" data-value="option2">Electricity</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="custom-select-wrapper">
                                <div class="custom-select shadow-sm" tabindex="0" role="button" aria-haspopup="listbox" aria-expanded="false">
                                    Filter By Status
                                </div>
                                <div class="select-options" role="listbox">
                                    <div role="option" data-value="option1">Paid</div>
                                    <div role="option" data-value="option2">Pending</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control filter-shadow ">
                        </div>
                    </div>
                    <div class="row mt-2 mb-2">
                        <h6 class="mb-0 contact_section_header summary mb-2"></i> <b>Summary</b></h6>
                        <div class="col-md-3">
                            <div class="summary-info-card shadow-sm bg-white p-3 rounded">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa fa-box icon"></i>
                                    <div>
                                        <div class="summary-info-card-label">ITEMS</div>
                                        <b id="items" class="summary-info-card-value">300 PIECES</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Phone Card -->
                        <div class="col-md-3">
                            <div class="summary-info-card shadow-sm bg-white p-3 rounded">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa fa-calendar-alt icon"></i>
                                    <div>
                                        <div class="summary-info-card-label">This Month</div>
                                        <b id="duration" class="summary-info-card-value">Ksh 100,000</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ID Card -->
                        <div class="col-md-3">
                            <div class="summary-info-card shadow-sm bg-white p-3 rounded">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa fa-check-circle icon"></i>
                                    <div>
                                        <div class="summary-info-card-label">Paid</div>
                                        <b id="paid" class="summary-info-card-value">KSH 90,000</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-info-card shadow-sm bg-white p-3 rounded">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa fa-hourglass-half icon"></i>
                                    <div>
                                        <div class="summary-info-card-label">Pending </div>
                                        <b id="pending" class="summary-info-card-value">KSH 45862394</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12 mb-4">
                            <div class="card shadow-sm">
                                <div class="bg-white p-1 rounded-2 border-0">
                                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #e9ecef;">
                                        <a class="text-white fw-bold text-decoration-none text-dark" data-bs-toggle="collapse" href="#addExpense" role="button" aria-expanded="false" aria-controls="addExpense" onclick="toggleIcon(this)">
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
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Date&nbsp;:</label>
                                                            <input type="date" id="dateInput" class="form-control rounded-1 shadow-none" name="date" placeholder="">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Expense No</label>
                                                            <input type="text" name="expense_no" class="form-control rounded-1 shadow-none" placeholder="KRA000100039628">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Supplier</label>
                                                            <input type="text" class="form-control rounded-1 shadow-none" placeholder="Supplier" name="supplier" required />
                                                        </div>
                                                    </div>
                                                    <!-- Hidden total -->
                                                    <div class="row mt-2">
                                                        <div class="text-muted mt-4 mb-4">Add the Spend items in the fields below</div>
                                                        <div class="col-md-12 rounded-2" id="itemsContainer">
                                                            <div class="row item-row g-3 mb-5 p-2" style="background-color: #f5f5f5;">
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <label class="form-label fw-bold">ITEM(SERVICE)</label>
                                                                            <select class="form-select shadow-none rounded-1" name="item_name[]" style="width: 100%;">
                                                                                <option value="" disabled selected>Select</option>
                                                                                <?php foreach ($items as $item): ?>
                                                                                    <option value="<?= htmlspecialchars($item['account_name']) ?>">
                                                                                        <?= htmlspecialchars($item['account_name']) ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="form-label fw-bold">Description</label>
                                                                            <input type="text" class="form-control description rounded-1 shadow-none" placeholder="Electricity" name="description[]" required />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="form-label fw-bold">Qty</label>
                                                                            <input type="number" class="form-control qty rounded-1 shadow-none" placeholder="1" name="qty[]" required />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="form-label fw-bold">Unit Price</label>
                                                                            <input type="number" class="form-control unit-price rounded-1 shadow-none" placeholder="123" name="unit_price[]" required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-md-3">
                                                                            <label class="form-label fw-bold">Taxes</label>
                                                                            <select class="form-select rounded-1 shadow-none " name="taxes[]" required>
                                                                                <option value="" selected disabled>--Select Option--</option>
                                                                                <option value="inclusive">VAT 16% Inclusive</option>
                                                                                <option value="exclusive">VAT 16% Exclusive</option>
                                                                                <option value="zero">Zero Rated</option>
                                                                                <option value="exempt">Exempted</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="form-label fw-bold">Total</label>
                                                                            <input type="text" class="form-control item-total shadow-none rounded-1 mb-1" placeholder="Ksh 0.00" name="item_total[]" required readonly />
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label class="form-label fw-bold">Discount</label>
                                                                            <input type="text" class="form-control item-total shadow-none rounded-1 mb-1" placeholder="Ksh 0.00" required readonly>
                                                                        </div>
                                                                        <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                                                                            <label class="form-label fw-bold">Remove Item</label>
                                                                            <button class="btn btn-sm btn-danger text" data-bs-toggle="modal" data-bs-target="#editPersonalInfoModal">
                                                                                <i class="fas fa-trash me-1 icon" style="color:white"></i>
                                                                            </button>
                                                                        </div>
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
                                                                        <label class="me-2 border-end pe-3 text-end w-50"><strong>Sub-Total:</strong></label>
                                                                        <input type="text" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="subTotal" value="Ksh 10,500">
                                                                    </div>

                                                                    <div class="d-flex justify-content-end w-100 mb-2" id="vatAmountInclusiveContainer" style="display:none !important;">
                                                                        <label class="me-2 border-end pe-3 text-end w-50"><strong id="taxLabel">VAT 16% (Inclusive):</strong></label>
                                                                        <input type="text" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="vatAmountInclusive" value="Ksh 1,500">
                                                                    </div>

                                                                    <div class="d-flex justify-content-end w-100 mb-2" id="vatAmountExclusiveContainer" style="display: none;">
                                                                        <label class="me-2 border-end pe-3 text-end w-50"><strong id="taxLabel">VAT 16% (Exlusive):</strong></label>
                                                                        <input type="text" readonly class="form-control w-50 ps-3 rounded-1 shadow-none" id="vatAmountExclusive" value="Ksh 1,500">
                                                                    </div>

                                                                    <div class="d-flex justify-content-end w-100 mt-3 pt-2 border-top border-warning">
                                                                        <label class="me-2 border-end pe-3 text-end w-50"><strong>Total:</strong></label>
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
                                                            <button type="submit" class="btn btn-secondary shadow-none">✕ Close</button>
                                                            <button type="submit" class="btn btn-outline-warning text-dark shadow-none">✅ Submit</button>
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
                    <!-- /raw -->
                    <div class="row mt-2 mb-5">
                        <h6 class="mb-0 contact_section_header summary mb-2"></i> <b>Details</b></h6>
                        <div class="col-md-12">
                            <div class="details-container bg-white p-2 rounded Content shadow-sm">
                                <h3 class="details-container_header text-start"> <span id="displayed_building">All Expenses</span> &nbsp; |&nbsp; <span style="color:#FFC107"> <span id="enteries">3</span> enteries</span></h3>
                                <div class="table-responsive" style="overflow-x: auto;">
                                    <div id="top-bar" class="filter-pdf-excel mb-2">
                                        <div class="d-flex" style="gap: 10px;">
                                            <div id="custom-search">
                                                <input type="text" id="searchInput" placeholder="Search Expense...">
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <div id="custom-buttons"></div>
                                        </div>
                                    </div>
                                    <table id="repaireExpenses" style="width: 100%; min-width: 600px;">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Supplier</th>
                                                <th>Expense No</th>
                                                <th>Totals</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($expenses as $exp): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars(date('d M Y', strtotime($exp['created_at']))) ?></td>
                                                    <td><?= htmlspecialchars($exp['supplier']) ?></td>
                                                    <td>
                                                        <div style="color:#28a745;"><?= htmlspecialchars($exp['expense_no']) ?></div>
                                                    </td>
                                                    <td>KSH <?= number_format($exp['total'], 2) ?></td>
                                                    <td>
                                                        <?php
                                                        $status = strtolower($exp['status']);
                                                        $statusLabel = '';

                                                        if ($status === 'paid') {
                                                            $statusLabel = '<span style="background-color: #28a745; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">Paid</span>';
                                                        } elseif ($status === 'unpaid') {
                                                            $statusLabel = '<span style="background-color: #FFC107; color: #00192D; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">Unpaid</span>';
                                                        } elseif ($status === 'partially paid') {
                                                            $statusLabel = '<span style="background-color: #17a2b8; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">Partially Paid</span>';
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
                                                                onclick="payExpense(<?= $exp['id'] ?>, <?= number_format($exp['total'], 2) ?>)">
                                                                <i class="bi bi-credit-card-fill"></i>
                                                                Pay
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>


                                                    <td>
                                                        <!-- view button -->
                                                        <button
                                                            class="btn btn-sm d-flex align-items-center gap-1 px-3 py-2"
                                                            style="background-color: #00192D; color: white; border: none; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); font-weight: 500;"
                                                            onclick="openExpenseModal(<?= $exp['id'] ?>, <?= $exp['total'] ?>)">
                                                            <i class="bi bi-eye-fill"></i>
                                                            View
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
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
                                                            <input type="number" class="form-control shadow-none rounded-1" id="amountToPay" style="font-weight: 600;" name="amountToPay" value="1200" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="paymentDate" class="form-label shadow-none ">Payment Date</label>
                                                            <input type="date" class="form-control shadow-none rounded-1" id="paymentDate" name="payment_date" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="paymentMethod" class="form-label">Payment Method</label>
                                                            <select class="form-select shadow-none rounded-1" id="paymentMethod" name="payment_method" required>
                                                                <option value="cash">Cash</option>
                                                                <option value="mpesa">M-Pesa</option>
                                                                <option value="bank">Bank Transfer</option>
                                                                <option value="card">Card</option>
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
                                                    <button type="submit" form="payExpenseForm" class="btn" style="background-color: #FFC107; color: #00192D;">
                                                        <i class="bi bi-credit-card"></i> Confirm Payment
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /row -->
                    <div class="row graph">
                        <div class="col-md-12">
                            <div class="bg-white p-2 shadow rounded-2">
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
                </div>
            </div>
            <!--end::App Content-->
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
        <!--end::Footer-->


        <!-- Modals -->
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


    </div>
    <!--end::App Wrapper-->

    <!-- Main Js File -->
    <script src="../../../dist/js/adminlte.js"></script>
    <script src="expense.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <!-- pdf download plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


    <!-- J  A V A S C R I PT -->
    <script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
        crossorigin="anonymous">
    </script>
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous">
    </script>
    <!-- links for dataTaable buttons -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <!--end::OverlayScrollbars Configure-->

    <!-- OPTIONAL SCRIPTS -->
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

            // Financial year start (July 1)
            const fyStart = new Date(now.getFullYear(), 6, 1); // July = month 6

            if (selectedDate < fyStart) {
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

        function updateExpenseChart() {
            fetch('expense2.php?get_totals=1')
                .then(res => res.json())
                .then(monthlyTotals => {
                    expenseChart.data.datasets[0].data = [
                        monthlyTotals[1], monthlyTotals[2], monthlyTotals[3], monthlyTotals[4],
                        monthlyTotals[5], monthlyTotals[6], monthlyTotals[7], monthlyTotals[8],
                        monthlyTotals[9], monthlyTotals[10], monthlyTotals[11], monthlyTotals[12]
                    ];
                    expenseChart.update();
                });
        }

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


    <!-- select wrapper -->


    <!-- Scripts -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<!--end::Body-->

</html>