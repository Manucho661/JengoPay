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
    <link rel="stylesheet" href="../../../css/adminlte.css" />
    <!-- <link rel="stylesheet" href="text.css" /> -->
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="maintenance.css">
    <!-- scripts for data_table -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- jQuery (required for Bootstrap's JS plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5.3.3 JS (includes Popper.js for tooltips and popovers) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>


    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .expand-icon {
            cursor: pointer;
            text-align: center;
        }

        .expand-row {
            background-color: #f9f9f9;
        }

        .expand-row td {
            padding: 10px 20px;
        }

        #accountsTable tr td:first-child {
            font-weight: bold;
            /* Make the first td bold */
            font-size: larger;
        }

        #accountsTable tr td:last-child {
            font-weight: bold;
            /* Make the first td bold */
            color: #FF7F7F;
        }

        #accountsTable tr td {
            padding: 12px;
            /* Increase the padding inside each td element */
            font-weight: bold;
        }

        #accountsTable tr:last-child td {
            color: green;
            /* Change the text color to green for all cells in the last row */
        }

        .contact_section_header {
            font-family: 'Poppins', sans-serif;
            font-size: 22px;
            font-weight: 600;
        }

        /* Remove borders from all table rows and table cells */
        table tr,
        table td,
        table th {
            border: none;
        }

        #requestNav .nav-link {
            color: #00192D;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-bottom: 3px solid transparent;
            transition: border-color 0.2s ease;
        }

        #requestNav .nav-link.active {
            border-bottom-color: #00192D;
            /* underline highlight */
        }

        .custom-select-wrapper {
            position: relative;
            width: 100%;
        }

        .custom-select {
            appearance: none !important;
            /* Most modern browsers */
            -webkit-appearance: none !important;
            /* Safari and older WebKit */
            -moz-appearance: none !important;
            padding: 12px 16px;
            border-radius: 5px;
            background-color: #fff !important;
            border: 1px solid #ccc;
            cursor: pointer;
            position: relative;
            user-select: none;
            background: white !important;
            /* Remove browser background */
            background-image: none;
        }

        .custom-select:focus {
            border-color: #FFC107 !important;
            box-shadow: none !important;
        }

        .custom-select:hover {
            border-color: #FFC107 !important;
        }

        .custom-select::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%) rotate(45deg);
            width: 6px;
            height: 6px;
            border-right: 2px solid #FFC107;
            border-bottom: 2px solid #FFC107;
            transition: transform 0.2s;
        }

        .custom-select.open::after {
            transform: translateY(-50%) rotate(-135deg);
        }

        .select-options {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            border: 1px solid #ccc;
            border-top: none;
            background: white;
            z-index: 10;
            border-radius: 0 0 5px 5px;
            max-height: 200px;
            overflow-y: auto;
        }

        .select-options div {
            padding: 12px 16px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .select-options div:hover,
        .select-options .selected {
            background-color: #4C5D6E;
            color: #FFC107;
        }

        @media (max-width: 480px) {
            .custom-select {
                width: 100%;
            }
        }

        .details.table td {
            font-size: 14px !important;
        }

        .form-label {
            white-space: nowrap;
            /* Prevent the label text from wrapping */
        }
    </style>
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper" style="height: 100 vh; background-color:rgba(128,128,128, 0.1);">
        <!--begin::Header-->
        <?php include_once '../../includes/header.php' ?>
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
            <!-- <div id="sidebar"></div> -->
            <div> <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?> </div> <!-- This is where the sidebar is inserted -->

            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main" style=" height:100%;">
            <!--begin::App Content Header-->
            <div class="app-content-header" style="height:30%;">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row align-items-center mb-1">
                        <div class="col-sm-7">
                            <h3 class="mb-0">🖊️ <span class="contact_section_header">Aged Payable Accounts</span></h3>
                            <p class="text-muted"> Manage your Payable accounts</p>
                        </div>
                        <div class="col-sm-5">
                            <ul class="nav justify-content-end border-bottom" id="requestNav">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#" data-tab="all">
                                        Aged Payable
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" data-tab="saved">
                                        Balance Sheet
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" data-tab="cancelled">
                                        Aged Recievable
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group d-flex align-items-center">
                                <label for="selectFilter" class="form-label p-2">Choose an Building</label>
                                <select id="selectFilter" class="form-select" aria-label="Select filter">
                                    <option selected>Select a building</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <div class="app-content" style="height: 75%;">
                <!--begin::Container-->
                <div class="container-fluid details bg-white rounded-2 p-2 shadow-sm" style="height: 100%;">
                    <!-- Aging Payables Table -->
                    <div class="">
                        <div class="table-summary-section text-warning p-2 rounded-top-2 d-flex justify-content-between mb-2"
                            style="background: linear-gradient(135deg, #00192D, #003E57);">
                            <div class="d-flex">
                                <div class="buildings fw-bold text-white">
                                    All Buildings | &nbsp;
                                </div>
                                <div class="entries fw-bold text-warning">
                                    3 entries
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-sm me-2" style="background-color: #FFC107; color: #00192D;" title="Download PDF" id="downloadExpPdf">
                                    <i class="bi bi-download"></i>
                                </button>
                                <button class="btn btn-sm me-2" style="background-color: #FFC107; color: #00192D;" title="Print">
                                    <i class="bi bi-printer"></i>
                                </button>
                            </div>
                        </div>

                        <div class="table-section">
                            <table class="table table-striped table-hover" id="accountsTable">
                                <thead>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>0-30 Days</th>
                                        <th>31-60 Days</th>
                                        <th>61-90 Days</th>
                                        <th>90+ Days</th>
                                        <th>Total Payable</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Supplier Rows will be injected -->
                                </tbody>
                            </table>
                        </div>


                        <!-- 🔽 Pagination controls -->
                        <div class="border-top pt-2 mt-2 d-flex justify-content-end align-items-center" style="border-color: #dee2e6 !important;">
                            <!-- Left: page info -->
                            <div class="fw-bold text-muted small mx-4" id="pageInfo">
                                Page 1 of 3
                            </div>

                            <!-- Right: navigation buttons -->
                            <nav aria-label="Table navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item">
                                        <button class="page-link" id="firstPage" style="color:#00192D;">«</button>
                                    </li>
                                    <li class="page-item">
                                        <button class="page-link" id="prevPage" style="color:#00192D;">‹</button>
                                    </li>
                                    <li class="page-item">
                                        <button class="page-link" id="nextPage" style="color:#00192D;">›</button>
                                    </li>
                                    <li class="page-item">
                                        <button class="page-link" id="lastPage" style="color:#00192D;">»</button>
                                    </li>
                                </ul>
                            </nav>
                        </div>


                    </div>
                </div>
                <!--end::Container-->
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
                <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;"> JENGO PAY</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!-- Overlay Cards -->
    <!-- Overlay scripts -->
    <!-- main js file -->
    <script src="../../../js/adminlte.js"></script>
    <script type="module" src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/js/bootstrap.bundle.min.js"></script>

    </script>


    </script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->


    <script>
        // Custom JS to toggle the chevron icon
        const expandIcons = document.querySelectorAll('.expand-icon');

        expandIcons.forEach(icon => {
            icon.addEventListener('click', () => {
                const chevron = icon.querySelector('i');
                const target = document.querySelector(icon.getAttribute('data-bs-target'));

                // Toggle chevron direction when expanding/collapsing
                if (target.classList.contains('collapse') && target.classList.contains('show')) {
                    chevron.classList.remove('bi-chevron-up');
                    chevron.classList.add('bi-chevron-down');
                } else {
                    chevron.classList.remove('bi-chevron-down');
                    chevron.classList.add('bi-chevron-up');
                }
            });
        });
    </script>

    <!-- datable -->
    <script>
        $(document).ready(function() {
            $('#accountsTables').DataTable({
                //  "paging": false,  // Disable pagination
                // "lengthMenu": [[-1], ["All"]],  // Remove the "Show entries" dropdown
                "info": false, // Disable info (like "Showing 1 to 10 of 100 entries")
                "language": {
                    "search": "", // Set custom text for the search label
                    "searchPlaceholder": "Search Account" // Set the placeholder inside the search input
                }
            });
        });
    </script>

    </script>


</body>
<!--end::Body-->

</html>