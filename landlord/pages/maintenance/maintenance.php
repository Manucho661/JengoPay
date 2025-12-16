<?php
session_start();
?>

<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Maintenance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE | Dashboard v2" />

  <!--begin::(Icons)-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!--Main css file-->
  <link rel="stylesheet" href="../../../landlord/assets/main.css" />
  <link rel="stylesheet" href="maintenance.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper" style="height: 100 vh; ">

    <!--begin::Header-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; ?>
    <!--end::Header-->

    <!--begin::Sidebar-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?>
    <!--end::Sidebar-->

    <!--begin::App Main-->
    <main class="main" style=" height:100%;">
      <!--begin::Container-->
      <div class="container-fluid">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb" style="">
            <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Dashboard/index2.php" style="text-decoration: none;">Home</a></li>
            <li class="breadcrumb-item active">Requests</li>
          </ol>
        </nav>
        <!--First Row-->
        <div class="row align-items-center mb-3">
          <div class="col-12 d-flex align-items-center">
            <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
            <h3 class="mb-0 ms-3">Maintenance Requests</h3>
          </div>
        </div>

        <!-- Second Row -->
        <div class="row">
          <div class="col-md-6">
            <p class="text-muted">Manage maintenance requests for tenants</p>
          </div>
        </div>

        <!-- Third Row: stats -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card d-flex align-items-center rounded-2 p-1">
              <div>
                <i class="fas fa-clipboard-check me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">Scheduled</p>
                <h3>400</h3>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card d-flex align-items-center rounded-2 p-1">
              <div>
                <i class="fas fa-check-circle me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">Completed</p>
                <h3>300</h3>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card d-flex align-items-center rounded-2 p-1">
              <div>
                <i class="fas fa-spinner fa-spin me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">In Progress</p>
                <h3>24</h3>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">

            <div class="stat-card d-flex align-items-center rounded-2 p-1">
              <div>
                <i class="fas fa-question-circle me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">Incomplete</p>
                <h3>20</h3>
              </div>
            </div>
          </div>
        </div>

        <hr>
        <!-- Fourth Row: search and main call to action buttons -->
        <div class="row mb-3">
          <div class="col-md-6 d-flex">
            <input
              type="text"
              class="form-control filter-shadow"
              placeholder="Search requests..."
              style="border-radius: 25px 0 0 25px;">

            <!-- Search Button -->
            <button
              class="btn text-white"
              style="border-radius: 0 25px 25px 0; background: linear-gradient(135deg, #00192D, #002B5B)">
              Search
            </button>
          </div>

          <div class="col-md-6 d-flex justify-content-end">
            <button
              type="button"
              class="btn bg-warning text-white seTAvailable fw-bold rounded-4"
              style="background: linear-gradient(135deg, #00192D, #002B5B); color:white; width:100%; white-space: nowrap;">
              Create request
            </button>

            <button type="button" class="btn bg-warning text-white seTAvailable fw-bold rounded-4" style="background: linear-gradient(135deg, #00192D, #002B5B); color:white; width:100%; white-space: nowrap;">Set all available</button>

            <button type="button" class="btn bg-warning text-white seTAvailable fw-bold bg-danger border-0 rounded-4" style="color:white; width:100%; white-space: nowrap;">Cancel all equests</button>
          </div>
        </div>


        <!-- Fifth row filters -->
        <div class="row g-3 mb-4 align-items-center">

          <!-- Filter by Building -->
          <div class="col-md-3">
            <select class="form-select filter-shadow">
              <option selected>Filter by Building</option>
            </select>
          </div>

          <!-- Filter by Tenant -->
          <div class="col-md-3">
            <select class="form-select filter-shadow">
              <option selected>Filter by Tenant</option>
            </select>
          </div>

          <!-- Filter Status -->
          <div class="col-md-2">
            <select class="form-select filter-shadow">
              <option selected>Filter Status</option>
              <option>Pending</option>
              <option>Completed</option>
            </select>
          </div>

          <!-- Date Filter -->
          <div class="col-md-2">
            <input type="date" class="form-control filter-shadow">
          </div>

          <!-- Apply Button -->
          <div class="col-md-2 text-end">
            <button class="btn w-100 text-white" style="background: linear-gradient(135deg, #00192D, #002B5B);">Apply</button>
          </div>

        </div>

        <!-- sixth Row: Table -->
        <div class="row">
          <div class="col-md-12">
            <div class="Table-section bg-white p-2 rounded-2">
              <div class="table-section-header rounded d-flex py-2" style="background-color: #00192D; color:#FFA000;">
                <div class="filtered-items text-white mx-3">
                  Manucho |
                </div>
                <div class="entries">
                  Showing <span id="showing-start">0</span> to <span id="showing-end">0</span> of <span id="total-records">0</span> records
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
                <div class="pagination mt-2 d-flex justify-content-end" id="pagination">

                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Seventh Row  graph-->
        <div class="row mt-4">
          <div class="col-12">
            <div class="requestsGraph bg-white px-2 rounded-2">
              <canvas id="requestsGraph" height="100"></canvas>
            </div>
          </div>
        </div>

      </div>
      <!--end:: Main Container-->
    </main>
    <!--end::App Main-->

    <!--begin::Footer-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
    <!-- end::footer -->

    <!-- begin modals -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="requestModalLabel">Submit a Request</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="requestForm">
              <!-- Category -->
              <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                  <option value="">Select a category</option>
                  <option value="plumbing">Plumbing Request</option>
                  <option value="security">Security</option>
                  <option value="electrical">Electrical</option>
                  <option value="maintenance">Maintenance</option>
                  <option value="cleaning">Cleaning</option>
                  <option value="cleaning">Other</option>
                </select>
              </div>

              <!-- Priority level -->
              <div class="mb-3">
                <label for="priority" class="form-label">Priority Level</label>
                <select class="form-select" id="priority" name="priority" required>
                  <option value="">Select a Level</option>
                  <option value="high">Low</option>
                  <option value="moderate">Moderate</option>
                  <option value="high">High</option>
                </select>
                <!-- <button type="button" class="btn btn-sm mt-2" id="otherRequestBtn">Other</button> -->
              </div>

              <!-- Building & Unit (same row) -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="building" class="form-label">Building</label>
                  <select class="form-select" id="building" name="building" required>
                    <option value="">Select Building</option>
                    <!-- Dynamically load buildings -->
                    <option value="1">Building A</option>
                    <option value="2">Building B</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="unit" class="form-label">Unit</label>
                  <select class="form-select" id="unit" name="unit" required>
                    <option value="">Select Unit</option>
                    <!-- Dynamically load units based on building -->
                    <option value="101">Unit 101</option>
                    <option value="102">Unit 102</option>
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label for="priority" class="form-label">Title</label>
                <input type="text" class="form-control" id="request_title" name="title" placeholder="Enter request title">
              </div>
              <!-- Other Request Field (Hidden by default) -->
              <!-- <div class="mb-3" id="otherRequestField">
                <label for="specifyRequest" class="form-label">Specify Request</label>
                <input type="text" class="form-control" id="specifyRequest" name="custom_request" placeholder="Enter your specific request">
              </div> -->

              <!-- Description -->
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Provide details about your request" required></textarea>
              </div>

              <!-- Upload Photo -->
              <div class="mb-3">
                <label for="photoUpload" class="form-label">Upload Photo</label>
                <input type="file" class="form-control" id="photoUpload" name="photos[]" accept="image/*">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="submitBtn">Submit Request</button>
          </div>
        </div>
      </div>
    </div>
    <!-- end modals -->

    <!-- Scripts -->
    <script src="../../assets/main.js"></script>
    <script type="module" src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"></script>

</body>
<!--end::Body-->

</html>