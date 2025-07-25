<?php
// ---------- SET‑UP ----------
include '../db/connect.php';      // PDO $pdo
$id   = $_GET['id'] ?? null;      // if present we’ll show the details pane

// small helper for status → badge colour
function statusClass(string $status): string
{
    return match (strtolower($status)) {
        'paid'        => 'status-paid',
        'overdue'     => 'status-overdue',
        'cancelled'   => 'status-cancelled',
        'sent'        => 'status-pending',   // treat “sent” as pending
        default       => 'status-draft',     // draft / anything else
    };
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
    <link rel="stylesheet" href="../../../dist/css/adminlte.css"/>
   <!-- <link rel="stylesheet" href="invoices.css"> -->
    <!-- <link rel="stylesheet" href="text.css" /> -->
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

     <!-- <link rel="stylesheet" href="expenses.css"> -->
    <!-- scripts for data_table -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <!-- Pdf pluggin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<!-- Add these to your head or before closing body -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
 .expense-logo{
          width: 20%;
          height: 20%;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->

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
            <!--MAIN MODALS -->
            <!-- add new inspection modal-->

            <!--begin::App Content Header-->
            <?php
require_once '../db/connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Fetch invoice
    $stmt = $pdo->prepare("
        SELECT i.*, CONCAT(u.first_name, ' ', u.middle_name) AS customer_name, u.email, t.phone_number
        FROM invoice i
        LEFT JOIN tenants t ON i.tenant = t.id
        LEFT JOIN users u ON u.id = t.user_id
        WHERE i.id = ?
    ");
    $stmt->execute([$id]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($invoice):
        $invoice_date = date('d/m/Y', strtotime($invoice['invoice_date']));
        $due_date = date('d/m/Y', strtotime($invoice['due_date']));
        $qty = (float)$invoice['quantity'];
        $unit_price = (float)$invoice['unit_price'];
        $subtotal = $qty * $unit_price;
        $vat = $invoice['taxes'] === 'exclusive' ? $subtotal * 0.16 : 0;
        $total = $subtotal + $vat;
?>
<div class="invoice-card">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-3">
        <!-- <img id="invoiceLogo" alt="Company Logo" class="invoice-logo"> -->
        <img id="expenseLogo" src="expenseLogo6.png" alt="JengoPay Logo" class="expense-logo">
        <!-- <script>
            const logos = [
                "https://upload.wikimedia.org/wikipedia/commons/thumb/0/08/Unilever.svg/200px-Unilever.svg.png",
                "https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/IBM_logo.svg/200px-IBM_logo.svg.png",
                "https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Amazon_logo.svg/200px-Amazon_logo.svg.png",
                "https://upload.wikimedia.org/wikipedia/commons/thumb/4/4a/Microsoft_logo.svg/200px-Microsoft_logo.svg.png",
                "https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Google_2015_logo.svg/200px-Google_2015_logo.svg.png",
                "https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/200px-Apple_logo_black.svg.png",
                "https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Pepsi_logo_2014.svg/200px-Pepsi_logo_2014.svg.png",
                "https://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Toyota_logo.svg/200px-Toyota_logo.svg.png",
                "https://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/Adobe_Corporate_Logo.png/200px-Adobe_Corporate_Logo.png",
                "https://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Nike_logo.svg/200px-Nike_logo.svg.png"
            ];
            document.addEventListener("DOMContentLoaded", () => {
                const logoImg = document.getElementById("invoiceLogo");
                logoImg.src = logos[Math.floor(Math.random() * logos.length)];
            });
        </script> -->

<div class="text-end" style="background-color: #f0f0f0; padding: 10px; border-radius: 8px;">
                    <strong>Customer Name</strong><br>
                    123 Example St<br>
                    Nairobi, Kenya<br>
                    customer@example.com<br>
                    +254 700 123456
                </div>
    </div>

    <!-- Invoice Info -->
    <div class="d-flex justify-content-between">
        <h6 class="mb-0"><?= htmlspecialchars($invoice['account_item']) ?></h6>
        <div class="text-end">
            <h3><?= htmlspecialchars($invoice['invoice_number']) ?></h3><br>
        </div>
    </div>

    <!-- Dates -->
    <div class="mb-1 rounded-2 d-flex justify-content-between align-items-center"
         style="border: 1px solid #FFC107; padding: 4px 8px; background-color: #FFF4CC;">
        <div class="d-flex flex-column invoice-date m-0">
            <span><b>Invoice Date</b></span>
            <p class="m-0"><?= $invoice_date ?></p>
        </div>
        <div class="d-flex flex-column due-date m-0">
            <span><b>Due Date</b></span>
            <p class="m-0"><?= $due_date ?></p>
        </div>
        <div></div>
    </div>

    <!-- Items Table -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered rounded-2 table-sm thick-bordered-table">
            <thead class="table">
                <tr class="custom-th">
                    <th>Description</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($invoice['description']) ?></td>
                    <td class="text-end"><?= $qty ?></td>
                    <td class="text-end">KES <?= number_format($unit_price, 2) ?></td>
                    <td class="text-end">KES <?= number_format($subtotal, 2) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Totals and Terms -->
    <div class="row">
        <div class="col-6 terms-box">
            <strong>Terms:</strong><br>
            <?= !empty($invoice['terms_conditions']) ? htmlspecialchars($invoice['terms_conditions']) : 'Payment due in 14 days.<br>Late fee: 2% monthly.' ?>
        </div>
        <div class="col-6">
            <table class="table table-borderless table-sm text-end mb-0">
                <tr>
                    <th>Subtotal:</th>
                    <td>KES <?= number_format($subtotal, 2) ?></td>
                </tr>
                <tr>
                    <th>VAT (16%):</th>
                    <td>KES <?= number_format($vat, 2) ?></td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td><strong>KES <?= number_format($total, 2) ?></strong></td>
                </tr>
            </table>
        </div>
    </div>

    <hr>

    <div class="text-center small text-muted">
        <?= !empty($invoice['notes']) ? htmlspecialchars($invoice['notes']) : 'Thank you for your business!' ?>
    </div>
</div>
<?php
    else:
        echo "<div class='alert alert-warning'>Invoice not found.</div>";
    endif;
} else {
    echo "<div class='alert alert-info'>No invoice selected.</div>";
}
?>




                <!-- Line Chart: Expenses vs Months -->
                <!-- <div class="mt-5">
                    <h6 class="fw-bold text-center">📊 Monthly Expense Trends</h6>
                    <canvas id="monthlyExpenseChart" height="100"></canvas>
                </div> -->


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
    </div>
    <!--end::App Wrapper-->


    <!-- OVERLAYS(that Covers whole viewport) -->



    <!-- Main Js File -->

</body>
<!--end::Body-->

</html>
