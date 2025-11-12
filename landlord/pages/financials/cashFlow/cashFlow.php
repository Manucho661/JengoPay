<?php
require_once "actions/operatingOutFlow.php";
require_once  "actions/operatingInflow.php";
require_once "actions/investingInflow.php";
require_once "actions/investingOutflow.php";
require_once "actions/financiangInflow.php";
require_once "actions/financingOutflow.php";

$openingBalance = "";

$TotalInflows = $totalTenantDeposits + $cumulativeOperatingInflow + $totalInvestingOutflows + $totalFinancingInflows;
$TotalOutflows = $cumulativeOutflow + $totalInvestingOutflows + $totalFinancingOutflows;

$netCash = $TotalInflows - $TotalOutflows;

// Now format for display (not before calculations)
$TotalInflows = number_format($TotalInflows, 2);
$TotalOutflows = number_format($TotalOutflows, 2);
$netCash = number_format($netCash, 2);

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
    <link rel="stylesheet" href="../../../../landlord/assets/main.css" />
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

        .card {
            /* max-width: 900px; */
            /* margin: 0 auto; */
            background: #ffffff;
            border-radius: 16px;
            /* box-shadow: 0 6px 22px rgba(2, 6, 23, 0.06); */
            overflow: hidden;
            border: none !important;
        }

        header {
            background: linear-gradient(90deg, #00192D, #0b3b6e);
            color: #FFC107;
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 18px;
            margin: 0;
        }

        header p {
            margin: 0;
            opacity: 0.9;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: linear-gradient(180deg, #f2f7ff, #ffffff);
            border-radius: 12px;
            overflow: hidden;
        }

        caption {
            caption-side: top;
            text-align: left;
            padding: 12px 18px;
            font-weight: 600;
            color: #002B5B;
        }

        thead th {
            background: rgba(0, 43, 91, 0.06);
            text-align: left;
            padding: 12px 18px;
            font-size: 13px;
            color: #002B5B;
        }

        tbody td {
            padding: 12px 18px;
            border-top: 1px solid #eef2f6;
            font-size: 14px;
            color: #12223b;
        }

        .section {
            background: #fbfdff;
            font-weight: 700;
            color: #002B5B;
        }

        .right {
            text-align: right;
        }

        .negative {
            color: #b02a37;
        }

        .positive {
            color: #0b6b2d;
        }

        .total-row {
            background: #f7fbff;
            font-weight: 700;
        }

        .footnote {
            margin-top: 12px;
            font-size: 13px;
            color: #55627a;
        }

        @media (max-width:640px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px
            }

            thead th,
            tbody td {
                padding: 10px
            }
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
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-8">
                            <h3 class="mb-0 m-0 contact_section_header"> 💰 The Cash Flow</h3>
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Container-->
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex flex-column justify-content-center">
                            <div class="d-flex justify-content-between">
                                <div class="text-muted mt-0">
                                    Manage your cashflow
                                </div>

                                <div class="d-flex" style="vertical-align: middle;">
                                    <ul class="nav justify-content-end border-bottom" id="requestNav">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#" data-tab="all">
                                                Balance sheet
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#" data-tab="saved">
                                                Profit and Loss
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center p-2 rounded mb-3" style="background-color: #ffffff;">
                        <div class="col-md-9">
                            <div class="d-flex gap-3 align-items-center w-100">
                                <!-- Category Filter -->
                                <div class="form-group w-100">
                                    <label for="selectFilter" class="form-label p-2">Choose a Building</label>
                                    <select id="selectFilter" class="form-select w-100" aria-label="Select filter">
                                        <option selected>Select a building</option>
                                        <option value="1">Option 1</option>
                                        <option value="2">Option 2</option>
                                        <option value="3">Option 3</option>
                                        <option value="4">Option 4</option>
                                    </select>
                                </div>

                                <!-- Date Filter -->
                                <div class="form-group w-100">
                                    <label for="filterDate" class="form-label p-2">Select Date</label>
                                    <input type="date" id="filterDate" class="form-control w-100 border-1 shadow-sm" />
                                </div>
                            </div>
                        </div>

                        <!-- Export Buttons -->
                        <div class="col-md-3 d-flex justify-content-md-end mt-3 mt-md-0">
                            <div class="d-flex gap-2">
                                <button class="btn rounded-circle shadow-sm" id="downloadBtn" style="background-color: #FFC107; border: none;">
                                    <i class="fas fa-file-pdf" style="font-size: 24px; color: #00192D;"></i>
                                </button>
                                <button class="btn rounded-circle shadow-sm" id="exportToExcel" style="background-color: #FFC107; border: none;">
                                    <i class="fas fa-file-excel" style="font-size: 24px; color: #00192D;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="card border-0 shadow-none" style="border: none !important; box-shadow: none !important;">
                                <header>
                                    <div>
                                        <h1>Emmanuel Properties Ltd. — Cash Flow Statement</h1>
                                        <p>For the month of September 2025 (KES)</p>
                                    </div>
                                    <div style="text-align:right">
                                        <p style="font-weight:700" class="mb-2">Opening Cash Balance: <span style="color:#ffffff">KES 180,000</p>
                                        <p style="font-weight:700">Net Cash Movement: <span style="color:#ffffff"><?= $netCash ?></span></p>
                                        <p style="font-size:12px;margin-top:6px;opacity:0.9">Generated: Oct 8, 2025</p>
                                    </div>
                                </header>

                                <table aria-describedby="cf-note">
                                    <caption>Cash flows grouped by activity</caption>
                                    <thead>
                                        <tr>
                                            <th>Activity / Item</th>
                                            <th class="right">Cash Inflows (KES)</th>
                                            <th class="right">Cash Outflows (KES)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="section">
                                            <td colspan="3">Operating Activities</td>
                                        </tr>
                                        <?php foreach ($operatingInflows as $inflow): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($inflow['item_type']) ?></td>
                                                <td class="right"><?= number_format($inflow['total_amount'], 2) ?></td>
                                                <td class="right">&mdash;</td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td>Rent deposits from new tenants</td>
                                            <td class="right"> <?= number_format($totalTenantDeposits, 2) ?> </td>
                                            <td class="right">&mdash;</td>
                                        </tr>
                                        <!-- outflow -->

                                        <?php foreach ($operatingOutflows as $outflow): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($outflow['item_type']) ?></td>
                                                <td class="right">&mdash;</td>
                                                <td class="right"><?= number_format($outflow['total_amount'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <tr class="total-row">
                                            <td>Net Cash from Operating Activities</td>
                                            <td class="right positive"><?= number_format($totalTenantDeposits  + $cumulativeOperatingInflow, 2) ?></td>
                                            <td class="right"><?= number_format($cumulativeOutflow, 2) ?></td>
                                        </tr>

                                        <!-- Investing Activities -->
                                        <tr class="section">
                                            <td colspan="3">Investing Activities</td>
                                        </tr>
                                        <!-- outflow -->
                                        <?php foreach ($investingOutflows as $outflow): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($outflow['item_type']) ?></td>
                                                <td class="right">&mdash;</td>
                                                <td class="right"><?= number_format($outflow['total_amount'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <!-- inflow -->
                                        <?php foreach ($investingInflows as $Inflow): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($Inflow['item_type']) ?></td>
                                                <td class="right">&mdash;</td>
                                                <td class="right"><?= number_format($Inflow['total_amount'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="total-row">
                                            <td>Net Cash from Investing Activities</td>
                                            <td class="right"><?= number_format($totalInvestingInflows, 2) ?></td>
                                            <td class="right negative"><?= number_format($totalInvestingOutflows, 2) ?></td>
                                        </tr>
                                        <!-- Financing Activities -->
                                        <tr class="section">
                                            <td colspan="3">Financing Activities</td>
                                        </tr>
                                        <!-- outflow -->
                                        <?php foreach ($financingInflows as $outflow): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($outflow['item_type']) ?></td>
                                                <td class="right">&mdash;</td>
                                                <td class="right"><?= number_format($outflow['total_amount'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php foreach ($financingOutflows as $outflow): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($outflow['item_type']) ?></td>
                                                <td class="right">&mdash;</td>
                                                <td class="right"><?= number_format($outflow['total_amount'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <tr class="total-row">
                                            <td>Net Cash from Financing Activities</td>
                                            <td class="right positive"> <?= number_format($totalFinancingInflows, 2) ?> </td>
                                            <td class="right"><?= number_format($totalFinancingOutflows, 2) ?></td>
                                        </tr>
                                        <!-- Totals -->
                                        <tr style="height:8px">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="total-row">
                                            <td>Total Net Movement</td>
                                            <td class="right"><?= number_format($totalFinancingInflows, 2) + number_format($totalInvestingOutflows, 2) ?></td>
                                            <td class="right">
                                                <?= number_format($cumulativeOutflow + $totalFinancingOutflows + $totalInvestingInflows, 2) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:700">Net Cash (Inflows - Outflows)</td>
                                            <td class="right" colspan="2" style="font-weight:700;color:#002B5B"><?= $netCash ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
        <!--end::Footer-->

    </div>
    <!--end::App Wrapper-->

    <!-- plugin for pdf -->


    <!-- Main Js File -->
    <script src="../../../../landlord/js/adminlte.js"></script>
    <script type="module" src="js/main.js"></script>
    <!-- html2pdf depends on html2canvas and jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script type="module" src="./js/main.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <!-- pdf download plugin -->


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

    <!-- apexcharts -->
    <script
        src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
        crossorigin="anonymous"></script>
    </script>

    <!-- select wrapper -->


    <!-- Scripts -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
<!--end::Body-->

</html>