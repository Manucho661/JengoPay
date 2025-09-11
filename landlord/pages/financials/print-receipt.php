<?php
include '../db/connect.php';

$tenantId = $_GET['tenant_id'] ?? null;
if (!$tenantId) { echo "Invalid tenant ID."; exit; }

$stmt = $pdo->prepare("SELECT * FROM tenant_rent_summary WHERE id = ?");
$stmt->execute([$tenantId]);
$tenant = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$tenant) { echo "Tenant not found."; exit; }

/* ───────── 1. Numbers & gate flags ───────── */
$amountPaid  = (float) $tenant['amount_paid'];
$penaltyAmt  = (float) $tenant['penalty'];
$arrearsAmt  = (float) $tenant['arrears'];

$showPenalty = $penaltyAmt > 0;
$showArrears = $arrearsAmt > 0;

// Recalculate total due (i.e. Rent + Penalty + Arrears)
$total = 0;
$total += $amountPaid; // Rent payment stored as amount_paid
if ($showPenalty) $total += $penaltyAmt;
if ($showArrears) $total += $arrearsAmt;


$recalculatedBalance = $amountPaid - $total;
$formattedBalance = number_format($recalculatedBalance, 2);


/*  ↑↑↑             ↑↑↑  */

/* ───────── 2. Display strings ───────── */
$name         = htmlspecialchars($tenant['tenant_name']);
$unit         = htmlspecialchars($tenant['unit_code']);
$property     = htmlspecialchars($tenant['building_name'] ?? 'XXX');
$amount       = number_format($amountPaid, 2);
$penalty      = number_format($penaltyAmt, 2);
$penaltyDays  = (int) $tenant['penalty_days'];
$arrears      = number_format($arrearsAmt, 2);
$paymentMode  = htmlspecialchars($tenant['payment_mode'] ?? 'Mpesa');
$reference    = htmlspecialchars($tenant['reference_number'] ?? 'TCO2X12E80');
$date         = !empty($tenant['payment_date'])
                ? date("d/m/Y", strtotime($tenant['payment_date']))
                : date("d/m/Y");
$printDate    = date("d/m/Y H:i");
$receiptNo    = "RC" . str_pad($tenantId, 5, '0', STR_PAD_LEFT);
$accountNo    = !empty($tenant['account_no'])
                ? htmlspecialchars($tenant['account_no'])
                : $unit;

/* ───────── 3. Re‑calculate TOTAL ───────── */
$total = $amountPaid
       + ($showPenalty  ? $penaltyAmt  : 0)
       + ($showArrears  ? $arrearsAmt  : 0)
       + ($recalculatedBalance   > 0 ? $recalculatedBalance : 0);  // add only when tenant still owes

$totalAmountFormatted = number_format($total, 2);
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
    <link rel="stylesheet" href="../../../landlord/css/adminlte.css"/>
    <link rel="stylesheet" href="invoices.css">
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <style>
/* body{font-family:Arial,sans-serif;margin:0;padding:20px;background:#fff} */
        .receipt-container{max-width:600px;margin:0 auto}
        .company-header{text-align:center;margin-bottom:15px;line-height:1.3}
        .company-header h1{font-size:18px;margin:5px 0}
        .company-header p{font-size:12px;margin:2px 0}
        .receipt-title{text-align:center;font-size:16px;font-weight:bold;margin:10px 0;
                       padding-bottom:5px;border-bottom:1px solid #000}
        table{border-collapse:collapse;width:100%}
        .receipt-table{margin:10px 0;font-size:13px}
        .receipt-table td{padding:2px 5px;white-space:nowrap}
        .receipt-table td:first-child,.receipt-table td:nth-child(3){font-weight:bold}
        .amount-table{width:100%;border-collapse:collapse;margin:15px 0;font-size:14px}
        .amount-table td{padding:5px;border:none}
        .amount-table td:last-child{text-align:right}
        .amount-table td.negative{color:red;font-weight:bold}   /* highlight over‑pay */
        .divider{border-top:1px dashed #000;margin:10px 0}
        .footer{text-align:center;margin-top:20px;font-size:12px}
        @media print{.print-button{display:none}body{padding:0}}
   </style>

</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
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
            <div> <?php include_once '../includes/sidebar.php'; ?> </div> <!-- This is where the sidebar is inserted -->
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--MAIN MODALS -->
            <!-- add new inspection modal-->

            <div class="receipt-container">
    <div class="company-header">
        <h1>BT JENGOPAY</h1>
        <p>P.O BOX 37987 – 00100 – 8TH FLOOR</p>
        <p>INTERNATIONAL LIFE HSE, MAMA NGINA ST.</p>
        <p>TEL: 0733717726</p>
        <p>EMAIL: PROPERTYMANAGEMENT@BTJENGOPAY.CO.KE</p>
    </div>

    <div class="divider"></div>

    <div class="receipt-title">RECEIPT</div>

    <table class="receipt-table">
    <tr>
        <td>Received From:</td><td><?= $name ?></td>
        <td>Receipt No:</td><td><?= $receiptNo ?></td>
    </tr>
    <tr>
        <td>A/c NO:</td><td><?= $accountNo ?></td>
        <td>Date:</td><td><?= $date ?></td>
    </tr>
    <tr>
        <td>Unit No:</td><td><?= $unit ?></td>
        <td>Payment Mode:</td><td><?= $paymentMode ?></td>
    </tr>
    <tr>
        <td>Property:</td><td><?= $property ?></td>
        <td>Reference No:</td><td><?= $reference ?></td>
    </tr>
    <tr>
        <td><strong>Balance:</strong></td>
        <td colspan="1" class=" > 0 ? 'negative' : '' ?>">
        0.00
    </td>
    <td><strong>Amount (KES):</strong></td>
    <td><?= $totalAmountFormatted ?></td>
    </tr>
</table>

    <div class="divider"></div>

    <div class="receipt-title">DESCRIPTION</div>

    <table class="amount-table">
        <tr>
            <td>Rent Payment</td><td><?= $amount ?></td>
        </tr>

        <?php if ($showPenalty): ?>
        <tr>
            <td>Penalty (<?= $penaltyDays ?> days)</td><td><?= $penalty ?></td>
        </tr>
        <?php endif; ?>

        <?php if ($showArrears): ?>
        <tr>
            <td>Arrears</td><td><?= $arrears ?></td>
        </tr>
        <?php endif; ?>


    </table>

    <div class="divider"></div>

    <table class="amount-table">
        <tr><td>TOTAL (KES)</td><td><?= $totalAmountFormatted ?></td></tr>
    </table>

    <div class="divider"></div>

    <table class="receipt-table">
        <tr>
            <td>Received By:</td><td>N/A</td>
            <td>Signature:</td><td></td>
        </tr>
    </table>

    <div class="footer">
        <p>Thank You For Your Business</p>
        <div class="divider"></div>
        <p>PRINTED: <?= $printDate ?></p>
    </div>
</div>

<div class="print-button">
    <button onclick="window.print()" style="
        background:#00192D;color:#FFC107;padding:10px 25px;
        border:2px solid #FFC107;border-radius:8px;
        font-size:16px;font-weight:bold;cursor:pointer;
        margin:20px auto;display:block">
        Print Receipt
    </button>
</div>


<!-- javascript -->
    <!-- pdf download plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

</body>
<!--end::Body-->

</html>
