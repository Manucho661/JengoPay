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
  <link rel="stylesheet" href="../../css/adminlte.css" />
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #FFC107 !important;
    }

  </style>
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper" style="height: 100 vh; ">
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
      <!-- <div id="sidebar"></div> -->
      <div> <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?> </div> <!-- This is where the sidebar is inserted -->

      <!--end::Sidebar Wrapper-->
    </aside>
    <!--end::Sidebar-->
    <!--begin::App Main-->
    <main class="app-main" style=" height:100%;">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row align-items-center mb-3">
            <div class="col-sm-8 d-flex">
              <span class="info-box-icon p-2 rounded" style="background-color:#00192D; color:#fff;">
                <i class="bi bi-tools"></i>
              </span>
              <h3 class="mb-0 mx-2">Maintenance Requests</h3>
            </div>

            <div class="col-sm-4">
              <ul class="nav justify-content-end border-bottom" id="requestNav">
                <li class="nav-item">
                  <a class="nav-link active" href="#" data-tab="all">
                    All Requests
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" data-tab="saved">
                    Saved
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" data-tab="cancelled">
                    Cancelled
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <!-- begin row -->
          <div class="row">
            <p class="text-muted">Manage maintenance requests for tenants</p>
            <div class="col-12 col-sm-6 col-md-3">
              
              <div class="summary-card mb-2">
                <div class="summary-card_icon"> <i class="fas fa-clipboard-check"></i></div>
                <div>
                  <div class="summary-card_label">Scheduled</div>
                  <div class="summary-card_value">200 </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="summary-card">
                <div class="summary-card_icon"><i class="fas fa-check-circle"></i></div>
                <div>
                  <div class="summary-card_label">Completed</div>
                  <div class="summary-card_value penalities">&nbsp;300</div>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="summary-card">
                <div class="summary-card_icon"> <i class="fas fa-spinner fa-spin"></i> </div>
                <div>
                  <div class="summary-card_label">In Progress</div>
                  <div class="summary-card_value">&nbsp;200</div>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
              <div class="summary-card">
                <div class="summary-card_icon" style="font-weight: bold;"><i class="fas fa-question-circle"></i> </div>
                <div>
                  <div class="summary-card_label">Incomplete</div>
                  <div class="summary-card_value">&nbsp;20</div>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <!--begin::Row-->
          <div class="row g-3 mb-4">
            <div class="col-md-3">
              <select class="form-select filter-shadow">
                <option selected>Filter by Building</option>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select filter-shadow ">
                <option selected>Filter by Tenant</option>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select filter-shadow">
                <option selected>Filter Status</option>
                <option>Pending</option>
                <option>Completed</option>
              </select>
            </div>
            <div class="col-md-3">
              <input type="date" class="form-control filter-shadow ">
            </div>
          </div>
          <!-- begin row -->
          <div class="row">
            <div class="col-md-12">
              <div class="Table-section bg-white p-2 rounded-2">
                <div class="table-section-header">
                  <div class="entries">
                    <h6 class="mb-0 contact_section_header summary mb-2 p-2 rounded-top" style="background-color: #00192D; color:#FFA000;"> <span class="text-white">Manucho |</span> 5 entries</h6>
                  </div>
                  <div class="search-pdf-excel d-flex justify-content-between">
                    <div id="custom-search">
                      <input type="text" id="searchInput" placeholder="Search request...">
                    </div>
                    <div id="custom-buttons"></div>
                  </div>
                </div>
                <div style="overflow: auto;">
                  <table id="requests-table" class=" display requests-table">
                    <thead class="mb-2">
                      <tr>
                        <th>REQUEST Date</th>
                        <th>PROPERTY + UNIT</th>
                        <th>CATEGORY + DESCRIPTION </th>
                        <th>PROVIDER</th>
                        <th>PRIORITY</th>
                        <th>STATUS</th>
                        <th>PAYMENT</th>
                        <th>ACTIONS</th>
                      </tr>
                    </thead>
                    <tbody id="maintenanceRequestsTableBod">
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
          <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?> 
    <!-- end::footer -->
  <!--end::App Wrapper-->
  <!-- Overlay Cards -->
  <!-- Overlay scripts -->
  <!-- main js file -->
  <script src="maintenance.js"></script>
  <script type="module" src="js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/js/bootstrap.bundle.min.js"></script>

  </script>

  <!-- End view announcement script -->

  <script>
    // Function to toggle the visibility of the overlay
    function toggleOverlay() {
      var overlay = document.getElementById('overlay');
      // If overlay is hidden, show it
      if (overlay.style.display === "none" || overlay.style.display === "") {
        overlay.style.display = "flex";
      } else {
        overlay.style.display = "none";
      }
    }
  </script>


  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->


  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let table = $('#maintanance').DataTable({
        lengthChange: false, // Removes "Show [X] entries"
        dom: 't<"bottom"p>', // Removes default search bar & keeps only table + pagination
      });

      // Link custom search box to DataTables search
      $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
      });
    });
  </script>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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


  <script>


  </script>

  <script>
    $(document).ready(function() {
      var table = $('#maintenance').DataTable({
        "lengthChange": false,
        "dom": 'Bfrtip',
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "initComplete": function() {
          // Move the buttons to the first .col-md-6
          table.buttons().container().appendTo('#maintenance_wrapper .col-md-6:eq(0)');

          // Move the search box to the second .col-md-6
          $('#maintenance_filter').appendTo('#maintenance_wrapper .col-md-6:eq(1)');
        }
      });
    });
  </script>

  <script>

  </script>

  </script>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->


  <!-- plugin for pdf -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <!-- individualRequest open -->
  <script>
    function goToIndividualRequest(requestID) {
      window.location.href = "individualrequest.php?id=" + requestID; // Navigate to the given page
    }
  </script>
</body>
<!--end::Body-->

</html>