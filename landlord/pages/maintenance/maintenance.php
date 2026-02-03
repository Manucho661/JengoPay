<?php
session_start();
require_once '../../db/connect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);

// Actions
// submit request
require_once "actions/submitRequest.php";
// get requests
require_once 'actions/getRequests.php';
// include buildings
require_once 'actions/getBuildings.php';

// Pagination logic
$itemsPerPage = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalItems = count($requests);
$totalPages = ceil($totalItems / $itemsPerPage);
$offset = ($currentPage - 1) * $itemsPerPage;
$currentRequests = array_slice($requests, $offset, $itemsPerPage);
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

  <!-- Bootstrap Icons -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet">

  <!--Main css file-->
  <link rel="stylesheet" href="../../../landlord/assets/main.css" />
  <link rel="stylesheet" href="maintenance.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
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
  </style>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

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
        <div class="row g-3">

          <!-- Total Requests -->
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
              <div>
                <i class="bi bi-clipboard-check fs-1 me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">Total Requests</p>
                <b>0</b>
              </div>
            </div>
          </div>

          <!-- Open -->
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
              <div>
                <i class="bi bi-hourglass-split fs-1 me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">Open</p>
                <b>0</b>
              </div>
            </div>
          </div>

          <!-- Completed -->
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
              <div>
                <i class="bi bi-check-circle-fill fs-1 me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">Completed</p>
                <b>0</b>
              </div>
            </div>
          </div>

          <!-- Closed -->
          <div class="col-lg-3 col-md-6 d-flex">
            <div class="stat-card d-flex align-items-center rounded-2 p-3 w-100">
              <div>
                <i class="bi bi-x-circle-fill fs-1 me-3 text-warning"></i>
              </div>
              <div>
                <p class="mb-0" style="font-weight: bold;">Closed</p>
                <b>0</b>
              </div>
            </div>
          </div>

        </div>

        <hr>
        <!-- Fourth Row: search and main call to action buttons -->
        <div class="row mb-4">
          <!-- Search -->
          <div class="col-md-6 d-flex">
            <input
              type="text"
              class="form-control filter-shadow"
              placeholder="Search requests..."
              style="border-radius: 25px 0 0 25px;">

            <button
              class="btn text-white"
              style="border-radius: 0 25px 25px 0; background: linear-gradient(135deg, #00192D, #002B5B)">
              Search
            </button>
          </div>

          <!-- Action buttons -->
          <div class="col-md-6 d-flex justify-content-end gap-2">
            <button
              type="button"
              class="btn text-white fw-bold rounded-4"
              style="background: linear-gradient(135deg, #00192D, #002B5B); white-space: nowrap;"
              data-bs-toggle="modal"
              data-bs-target="#requestModal">
              Create request
            </button>

            <button
              type="button"
              class="btn text-white fw-bold rounded-4"
              style="background: linear-gradient(135deg, #00192D, #002B5B); white-space: nowrap;">
              Set all available
            </button>

            <button
              type="button"
              class="btn text-white fw-bold bg-danger border-0 rounded-4"
              style="white-space: nowrap;">
              Cancel all requests
            </button>
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
                  All Requests
                </div>
              </div>
              <?php if (empty($currentRequests)): ?>
                <!-- Empty State Message -->
                <div class="text-center py-5" style="margin: 3rem 0;">
                  <div style="background-color: #f8f9fa; border-radius: 16px; padding: 3rem 2rem; max-width: 500px; margin: 0 auto;">
                    <div style="font-size: 4rem; color: #00192D; margin-bottom: 1rem;">
                      <i class="bi bi-receipt"></i>
                    </div>
                    <h4 style="color: #00192D; font-weight: 600; margin-bottom: 1rem;">
                      No requests items found
                    </h4>
                    <p style="color: #6c757d; font-size: 1rem; margin-bottom: 1.5rem;">
                      Start tracking your maintenance requests by adding your first request item
                    </p>

                  </div>
                </div>
              <?php else: ?>
                <div style="overflow: auto;">
                  <table id="requests-table" class="display requests-table">
                    <thead class="mb-2">
                      <tr>
                        <th>Request Date</th>
                        <th>Property</th>
                        <th>Title</th>
                        <th>Provider</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                      </tr>
                    </thead>

                    <tbody id="maintenanceRequestsTableBod">
                      <?php foreach ($currentRequests as $req): ?>
                        <tr
                          onclick="window.location.href='request_details.php?id=<?= urlencode($req['id']) ?>'"
                          style="cursor:pointer;">
                          <td class="text-muted small">
                            <?=
                            !empty($req['created_at'])
                              ? htmlspecialchars(date('M j, Y', strtotime($req['created_at'])))
                              : ''
                            ?>
                          </td>


                          <td>
                            <div><?= htmlspecialchars($req['building_name'] ?? '') ?></div>
                            <div style="color: green;"><?= htmlspecialchars($req['unit'] ?? '') ?></div>
                          </td>

                          <td>

                            <div style="color:green; border:none; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                              <?= htmlspecialchars($req['title'] ?? '') ?>
                            </div>
                          </td>

                          <td style="color: <?= !empty(trim($req['provider_name'] ?? '')) ? 'green' : '#b93232ff' ?>;">
                            <div>
                              <?= !empty(trim($req['provider_name'] ?? ''))
                                ? htmlspecialchars($req['provider_name'])
                                : 'Unassigned' ?>
                            </div>
                          </td>

                          <td><?= htmlspecialchars($req['priority'] ?? '') ?></td>

                          <td style="color: <?= in_array($req['status'], ['Terminated', 'Cancelled']) ? '#b93232ff' : 'green' ?>;">
                            <?= htmlspecialchars($req['status']) ?>
                          </td>


                          <td>
                            <?php if (($req['payment_status'] ?? '') === 'Paid'): ?>
                              <div class="Paid"><i class="bi bi-check-circle-fill"></i> Paid</div>
                            <?php else: ?>
                              <div class="Pending rounded-2"><i class="bi bi-hourglass-split"></i> Pending</div>
                            <?php endif; ?>
                          </td>

                          <td style="vertical-align: middle;">
                            <div style="display:flex; gap:8px; align-items:center; height:100%;">
                              <button
                                type="button"
                                onclick="event.stopPropagation(); window.location.href='request_details.php?id=<?= urlencode($req['id']) ?>'"
                                class="btn btn-sm d-flex align-items-center gap-1 px-3 py-2"
                                style="background-color:#00192D; color:white; border:none; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); font-weight:500;">
                                <i class="bi bi-eye-fill"></i>
                              </button>

                              <button
                                type="button"
                                onclick="event.stopPropagation(); /* delete handler here */"
                                class="btn btn-sm d-flex align-items-center gap-1 px-3 py-2"
                                style="background-color:#ec5b53; color:white; border:none; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); font-weight:500;">
                                <i class="bi bi-trash-fill"></i>
                              </button>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                  <!-- Pagination -->
                  <?php if ($totalPages > 1): ?>
                    <nav aria-label="Request pagination">
                      <ul class="pagination justify-content-center">
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
            <form
              id="requestForm"
              method="POST"
              action=""
              enctype="multipart/form-data">
              <!-- Category -->
              <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                  <option value="">Select a category</option>
                  <option value="Plumbing">Plumbing Request</option>
                  <option value="Security">Security</option>
                  <option value="Electrical">Electrical</option>
                  <option value="Maintenance">Maintenance</option>
                  <option value="Cleaning">Cleaning</option>
                  <option value="Other">Other</option>
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
                    <?php foreach ($buildings as $building): ?>
                      <option value="<?= (int)$building['id'] ?>">
                        <?= htmlspecialchars($building['building_name']) ?>
                      </option>
                    <?php endforeach; ?>
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

              <!-- Description -->
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Provide details about your request" required></textarea>
              </div>

              <!-- Upload Photo -->
              <div class="mb-3">
                <label for="photoUpload" class="form-label">Upload Photo</label>
                <input type="file" class="form-control" id="photoUpload" name="photos[]" accept="image/*">
                <!-- Preview Image -->
                <div id="photoPreview" class="mt-2" style="display: none;">
                  <img id="previewImage" src="" alt="Photo Preview" class="img-fluid" style="max-width: 100px;">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button
              type="submit"
              class="btn btn-primary"
              name="submitRequest"
              form="requestForm">
              Submit Request
            </button>

          </div>
        </div>
      </div>
    </div>
    <!-- end modals -->

    <!-- Scripts -->
    <script src="../../assets/main.js"></script>
    <script type="module" src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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

    <!-- preview uploaded photos when submitted the maintenance request -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const photoUpload = document.getElementById('photoUpload');
        const photoPreview = document.getElementById('photoPreview');
        const previewImage = document.getElementById('previewImage');

        // Event listener for file input
        photoUpload.addEventListener('change', function() {
          const file = this.files[0]; // Get the selected file

          if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
              // Show the image preview and set the image source
              photoPreview.style.display = 'block';
              previewImage.src = e.target.result;
            };

            // Read the image file
            reader.readAsDataURL(file);
          } else {
            // Hide preview if no file selected
            photoPreview.style.display = 'none';
          }
        });
      });
    </script>

</body>
<!--end::Body-->

</html>