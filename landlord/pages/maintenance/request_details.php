<?php
session_start(); // Must be called before any HTML output
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];          // get the ID from the URL
  $_SESSION['id'] = $id;      // store it in the session
  // echo "ID $id has been saved in session!";
} else {
  echo "No ID found in the URL.";
}

// success or error messages for actions
$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);
?>
<!-- Actions -->
<?php
// request details
require_once "actions/getRequestDetails.php";
// set duration
require_once "actions/setDurationBudget.php";
// get proposals
require_once "actions/getProposals.php";
// get provider details
require_once "actions/getProviderDetails.php"
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE | Dashboard v2</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE | Dashboard v2" />

  <!-- LINKS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
  <!--end::Fonts-->

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


  <!--Main css file-->
  <link rel="stylesheet" href="../../../landlord/assets/main.css" />
  <link rel="stylesheet" href="request_details.css">
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- scripts for data_table -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Pdf pluggin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

</head>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    /* background-color: var(--light-bg); */
    background-color: #f4f6f9;

  }

  :root {
    --primary: #00192D;
    --secondary: #FFC107;
    --success: #27ae60;
    --danger: #e74c3c;
    --warning: #FFC107;
    --light-bg: #f8f9fa;
    --accent-color: #FFC107;
  }

  .card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
  }

  .card-header {
    background-color: white;
    border-bottom: 2px solid var(--accent-color);
    padding: 1.25rem;
    font-weight: 600;
  }

  .content-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); */
    margin-bottom: 20px;
  }

  .content-card h5 {
    color: var(--main-color);
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--accent-color);
  }

  .badge-status {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
  }

  .request-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 8px;
  }

  .info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--light-bg);
  }

  .info-row:last-child {
    border-bottom: none;
  }

  .info-label {
    font-weight: 600;
    color: #6c757d;
  }


  @keyframes ripple {
    0% {
      transform: scale(1);
      opacity: 0.9;
    }

    100% {
      transform: scale(2.6);
      /* opacity: 0; */
    }
  }


  /* Hidden state before animation */

  .setBudget-btn,
  .viewDetails-btn,
  .messageProviderBtn {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0.3rem 1.4rem;
    border-radius: 5px;
    font-weight: 400;
    transition: all 0.3s;
  }

  .acceptProposalBtn {
    background-color: #27ae60;
    color: white;
    border: none;
    padding: 0.3rem 1.4rem;
    border-radius: 5px;
    font-weight: 400;
    transition: all 0.3s;
  }

  .setBudget-btn:hover,
  .viewDetails-btn:hover,
  .messageProviderBtn:hover,
  .acceptProposalBtn:hover {
    background: var(--accent-color);
    color: var(--primary-color);
    transform: translateY(-2px);
  }

  /* Offcanvas Styles */
  .offcanvas {
    width: 500px !important;
  }

  .provider-header {
    background: linear-gradient(135deg, #00192D, #1a3a52);
    color: white;
    padding: 2rem;
    margin: -1rem -1rem 1.5rem -1rem;
  }

  .stat-box {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 1rem;
  }

  .stat-box h4 {
    color: #FFC107;
    margin-bottom: 0.25rem;
  }

  .stat-box p {
    color: #6c757d;
    margin-bottom: 0;
    font-size: 0.875rem;
  }

  .job-item {
    padding: 1rem;
    border-left: 3px solid #FFC107;
    background-color: #f8f9fa;
    margin-bottom: 1rem;
    border-radius: 5px;
  }

  .job-item h6 {
    color: #00192D;
    margin-bottom: 0.5rem;
  }
</style>

<body class="layout-fixed sidebar-expand-lg" style="background-color:#f4f6f9;">
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
  <div class="app-wrapper" style="height: 100 vh; ">
    <!--begin::Header-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; ?>
    <!--end::Header-->

    <!-- start Sidebar -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?>
    <!--end::Sidebar-->

    <!-- Main Layout -->
    <main class="main" style="background-color:#f4f6f9;">
      <!--begin::App Content Header-->
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="">
          <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Dashboard/index2.php" style="text-decoration: none;">Home</a></li>
          <li class="breadcrumb-item"><a href="maintenance.php" style="text-decoration: none;">Requests</a></li>
          <li class="breadcrumb-item active">Request Details</li>
        </ol>
      </nav>

      <!-- Page Title & Actions -->
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center">
          <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
          <h3 class="mb-0 ms-3">Requests details</h3>
        </div>

        <div class="d-flex gap-2 flex-wrap justify-content-end">
          <button
            type="button"
            id="availabilityBtn"
            class="btn seTAvailable text-white fw-bold rounded-4"
            style="background: linear-gradient(135deg, #00192D, #002B5B); white-space: nowrap;"
            data-request-id="<?= htmlspecialchars($request['id']) ?>"
            data-status="<?= htmlspecialchars($request['availability']) ?>">
            Set Available
          </button>


          <button
            type="button"
            class="btn bg-danger text-white seTAvailable fw-bold rounded-4"
            style="white-space: nowrap;">
            Cancel Request
          </button>

          <button
            type="button"
            class="btn seTAvailable text-white fw-bold rounded-4"
            style="background: linear-gradient(135deg, #00192D, #002B5B); white-space: nowrap;">
            All Requests
          </button>
        </div>
      </div>


      <div class="container-fluid">
        <div class="row">
          <div class="col-md-8">
            <div class="content-card">
              <h5>Request Information (<span class="mx-2" style="font-size: 12px;"><?= htmlspecialchars($request['status'] ?? 'N/A') ?></span>)</h5>
              <div class="info-row">
                <span class="info-label">Category:</span>
                <span><?= htmlspecialchars($request['category'] ?? 'N/A') ?></span>
              </div>
              <div class="info-row">
                <span class="info-label">Title:</span>
                <span><?= htmlspecialchars($request['title'] ?? 'N/A') ?></span>
              </div>
              <div class="info-row">
                <span class="info-label">Request ID:</span>
                <span>#REQ-<?= htmlspecialchars($request['id'] ?? 'N/A') ?></span>
              </div>
              <div class="info-row">
                <span class="info-label">Property:</span>
                <span><?= htmlspecialchars($request['building_name'] ?? 'N/A') . ', ' . htmlspecialchars($request['unit_number'] ?? 'N/A') ?></span>
              </div>
              <div class="info-row">
                <span class="info-label">Created:</span>
                <span><?= !empty($request['created_at']) ? htmlspecialchars(date('M j, Y', strtotime($request['created_at']))) : 'N/A' ?></span>
              </div>
            </div>

            <!-- Description & Image -->
            <div class="content-card">
              <h5>Description & Photo</h5>
              <div>
                <p><?= htmlspecialchars($request['description'] ?? 'No description provided.') ?></p>
                <?php if (!empty($photos)): ?>
                  <img src="<?= htmlspecialchars($photos[0]['photo_path']) ?>" alt="Request Image" class="request-image mt-3">
                <?php else: ?>
                  <p>No image provided.</p>
                <?php endif; ?>
              </div>
            </div>
            <div class="content-card">
              <h5>
                Proposals Received
                <span class="badge bg-warning text-black">
                  <?= count($proposals) ?>
                </span>
              </h5>

              <div>
                <?php if (empty($proposals)): ?>

                  <!-- No proposals message -->

                  <div class="text-center py-5" style="margin: 3rem 0;">
                    <div style="background-color: #f8f9fa; border-radius: 16px; padding: 3rem 2rem; max-width: 500px; margin: 0 auto;">
                      <div style="font-size: 4rem; color: #00192D; margin-bottom: 1rem;">
                        <i class="bi bi-receipt"></i>
                      </div>
                      <h4 style="color: #00192D; font-weight: 600; margin-bottom: 1rem;">
                        No proposals received
                      </h4>
                      <p style="color: #6c757d; font-size: 1rem; margin-bottom: 1.5rem;">
                        <!-- Start tracking your finances by adding your first expense -->
                      </p>

                    </div>
                  </div>

                <?php else: ?>

                  <?php foreach ($proposals as $proposal): ?>
                    <div class="card proposal-card mb-3">
                      <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <h6 class="mb-1">
                              <?= htmlspecialchars($proposal['service_provider_name']) ?>
                            </h6>

                            <div class="text-warning mb-2">
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star-half-alt"></i>
                              <span class="text-muted">(4.5)</span>
                            </div>

                            <!-- message part left as-is -->
                            <p class="mb-2">
                              We can fix your blocked sink using professional equipment. We'll also inspect the pipes to prevent future blockages.
                            </p>

                            <div>
                              <span class="badge bg-success">
                                Budget: KES <?= htmlspecialchars($proposal['proposed_budget']) ?>
                              </span>
                              <span class="badge bg-info ms-2">
                                Duration: <?= htmlspecialchars($proposal['proposed_duration']) ?>
                              </span>
                            </div>
                          </div>

                          <button class="viewDetails-btn" data-bs-toggle="offcanvas" data-bs-target="#providerOffcanvas"
                            data-provider-name="<?php echo htmlspecialchars($proposal['service_provider_name']); ?>">View Details</button>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>

                <?php endif; ?>
              </div>
            </div>

          </div>

          <div class="col-md-4">
            <div class="content-card">
              <h5>Budget & Timeline</h5>
              <div>
                <?php if (is_null($request['budget']) || is_null($request['duration'])): ?>
                  <!-- Budget/Duration Not Set State -->
                  <div class="text-center py-4">
                    <p class="text-muted mb-3">Budget and duration not set</p>
                    <button class="setBudget-btn" data-bs-toggle="modal" data-bs-target="#durationBudgetModal">
                      Set Budget and Duration
                    </button>
                  </div>
                <?php else: ?>
                  <!-- Budget/Duration Set State -->
                  <div>
                    <div class="info-row">
                      <span class="info-label">Budget:</span>
                      <span id="budget">KSH <?= htmlspecialchars($request['budget']) ?></span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Duration:</span>
                      <span id="duration"><?= htmlspecialchars($request['duration']) ?> HRS</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Deadline:</span>
                      <span class="text-danger"><?= htmlspecialchars($request['category'] ?? 'N/A') ?></span>
                    </div>
                  </div>
                  <div>
                    <button class="setBudget-btn" data-bs-toggle="modal" data-bs-target="#durationBudgetModal">
                      Reset Budget and Duration
                    </button>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="content-card">
              <h5>Provider Details</h5>

              <?php if (!$assignment): ?>
                <!-- No assignment message -->
                <div class="text-center py-4 text-muted">
                  <i class="fas fa-user-slash fa-2x mb-2"></i>
                  <p class="mb-0">No provider has been assigned to this request yet.</p>
                </div>

              <?php else: ?>
                <!-- Provider details -->
                <div class="text-center mb-3">
                  <img
                    src="https://ui-avatars.com/api/?name=<?= urlencode($assignment['name']) ?>&size=80&background=3498db&color=fff"
                    alt="Provider"
                    class="rounded-circle mb-2">
                  <h6 class="mb-0"><?= htmlspecialchars($assignment['name']) ?></h6>
                  <small class="text-muted">Licensed Service Provider</small>
                </div>

                <div class="info-row">
                  <span class="info-label">Contact:</span>
                  <span><?= htmlspecialchars($assignment['phone']) ?></span>
                </div>

                <div class="info-row">
                  <span class="info-label">Email:</span>
                  <span><?= htmlspecialchars($assignment['email']) ?></span>
                </div>

                <div class="info-row">
                  <span class="info-label">Response:</span>
                  <span><span class="badge bg-success"><?= htmlspecialchars($assignment['status']) ?></span></span>
                </div>

                <div class="info-row">
                  <span class="info-label">Rating:</span>
                  <span>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star-half-alt text-warning"></i>
                    (4.5)
                  </span>
                </div>

                <div>
                  <button
                    class="setBudget-btn w-100 bg-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#durationBudgetModal">
                    Terminate Contract
                  </button>
                </div>

              <?php endif; ?>
            </div>
            <!-- Status & Payment -->
            <div class="content-card">
              <h5>Status & Payment</h5>

              <div>
                <div class="info-row">
                  <span class="info-label">Status:</span>
                  <span><span class="badge bg-warning">In Progress</span></span>
                </div>
                <div class="info-row">
                  <span class="info-label">Payment:</span>
                  <span><span class="badge bg-danger">Pending</span></span>
                </div>
                <div class="info-row">
                  <span class="info-label">Amount:</span>
                  <span class="fw-bold">KES 3,500</span>
                </div>
                <div class="info-row">
                  <span class="info-label">Method:</span>
                  <span>M-Pesa</span>
                </div>
                <button class="btn btn-primary w-100 mt-3">Process Payment</button>
              </div>
            </div>

            <!-- Other Requests -->
            <div class="content-card">
              <h5>Other Recent Requests</h5>

              <div class="p-0">
                <div class="list-group list-group-flush">
                  <a href="#" class="list-group-item list-group-item-action other-request">
                    <div class="d-flex justify-content-between">
                      <div>
                        <h6 class="mb-1">Leaking Faucet</h6>
                        <small class="text-muted">Apt 2A - Dec 7</small>
                      </div>
                      <span class="badge bg-success align-self-center">Completed</span>
                    </div>
                  </a>
                  <a href="#" class="list-group-item list-group-item-action other-request">
                    <div class="d-flex justify-content-between">
                      <div>
                        <h6 class="mb-1">Broken Window</h6>
                        <small class="text-muted">Apt 5C - Dec 6</small>
                      </div>
                      <span class="badge bg-warning align-self-center">In Progress</span>
                    </div>
                  </a>
                  <a href="#" class="list-group-item list-group-item-action other-request">
                    <div class="d-flex justify-content-between">
                      <div>
                        <h6 class="mb-1">AC Not Working</h6>
                        <small class="text-muted">Apt 1B - Dec 5</small>
                      </div>
                      <span class="badge bg-info align-self-center">Open</span>
                    </div>
                  </a>
                  <a href="#" class="list-group-item list-group-item-action other-request">
                    <div class="d-flex justify-content-between">
                      <div>
                        <h6 class="mb-1">Door Lock Issue</h6>
                        <small class="text-muted">Apt 4D - Dec 4</small>
                      </div>
                      <span class="badge bg-success align-self-center">Completed</span>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>
    <!-- Begin Footer -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
    <!-- end footer -->
  </div>

  <!-- OffCanvas -->
  <!-- Offcanvas for Provider Details -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="providerOffcanvas" aria-labelledby="providerOffcanvasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="providerOffcanvasLabel">Provider Details</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="provider-header">
        <div class="text-center">
          <img src="https://ui-avatars.com/api/?name=QuickFix+Plumbing&size=100&background=FFC107&color=00192D"
            alt="Provider" class="rounded-circle mb-3" id="providerAvatar">
          <h4 class="mb-1" id="providerName">QuickFix Plumbing Services</h4>
          <div class="mb-2" id="providerRatingStars">
            <span class="text-warning">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
              <span class="text-muted">(4.5)</span>
            </span>
          </div>
          <p class="mb-0 opacity-75" id="providerExperience">8 years of experience</p>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-6">
          <div class="stat-box">
            <h4 id="jobsCompleted">0</h4>
            <p>Jobs Completed</p>
          </div>
        </div>
        <div class="col-6">
          <div class="stat-box">
            <h4 id="completionRate">0%</h4>
            <p>Completion Rate</p>
          </div>
        </div>
      </div>

      <div class="mb-4">
        <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-warning"></i> Contact Information</h6>
        <div class="info-row">
          <span class="info-label"><i class="fas fa-envelope"></i> Email:</span>
          <span><a href="mailto:info@quickfix.ke" id="providerEmail">info@quickfix.ke</a></span>
        </div>
        <div class="info-row">
          <span class="info-label"><i class="fas fa-phone"></i> Phone:</span>
          <span><a href="tel:+254712345678" id="providerPhone">+254 712 345 678</a></span>
        </div>
        <div class="info-row">
          <span class="info-label"><i class="fas fa-clock"></i> Response Time:</span>
          <span id="responseTime">Within 2 hours</span>
        </div>
      </div>

      <div class="mb-4">
        <h6 class="fw-bold mb-3"><i class="fas fa-file-alt text-warning"></i> Proposal Details</h6>
        <p id="proposalDescription">We can fix your blocked sink using professional equipment. We'll also inspect the pipes to prevent future blockages.</p>
        <div class="d-flex gap-2">
          <span class="badge bg-success" id="proposalBudget">KES 3,500</span>
          <span class="badge bg-info" id="proposalDuration">2-3 hours</span>
        </div>
      </div>

      <div class="mb-4">
        <h6 class="fw-bold mb-3"><i class="fas fa-briefcase text-warning"></i> Recent Jobs</h6>
        <div id="recentJobsList">
          <div class="job-item">
            <h6>Kitchen Sink Repair</h6>
            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> Westlands Apartments</small><br>
            <small class="text-muted"><i class="fas fa-calendar"></i> Jan 28, 2026</small>
            <div class="mt-2">
              <span class="text-warning">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </span>
            </div>
          </div>
          <div class="job-item">
            <h6>Bathroom Plumbing Fix</h6>
            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> Riverside Tower</small><br>
            <small class="text-muted"><i class="fas fa-calendar"></i> Jan 25, 2026</small>
            <div class="mt-2">
              <span class="text-warning">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </span>
            </div>
          </div>
          <div class="job-item">
            <h6>Water Heater Installation</h6>
            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> Kilimani Residence</small><br>
            <small class="text-muted"><i class="fas fa-calendar"></i> Jan 20, 2026</small>
            <div class="mt-2">
              <span class="text-warning">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
              </span>
            </div>
          </div>
          <div class="job-item">
            <h6>Pipe Leak Repair</h6>
            <small class="text-muted"><i class="fas fa-map-marker-alt"></i> Lavington Estate</small><br>
            <small class="text-muted"><i class="fas fa-calendar"></i> Jan 15, 2026</small>
            <div class="mt-2">
              <span class="text-warning">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button class="acceptProposalBtn" id="acceptProposalBtn">
          <i class="fas fa-check"></i> Accept This Proposal
        </button>
        <button class="messageProviderBtn">
          <i class="fas fa-comment"></i> Send Message
        </button>
      </div>
    </div>
  </div>

  <!-- MODALS -->
  <!--Provider Modal -->
  <div class="modal fade" id="proposalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content custom-modal">

        <!-- Header -->
        <div class="modal-header border-bottom">
          <h5 class="modal-title text-navy fw-bold">Provider Application</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body text-dark">
          <div class="d-flex align-items-start mb-3">
            <img id="modalPhoto"
              src="images/download.webp"
              alt="Profile Picture"
              class="rounded-circle me-3 border border-2 border-navy"
              style="width:70px; height:70px;">
            <div>
              <h5 id="modalName" class="mb-0">
                Jane Doe
                <span id="modalBadge" class="badge bg-warning text-dark ms-2">Top Rated</span>
              </h5>
              <p id="modalTitle" class="text-muted mb-0">Full Stack Developer | React & Node.js</p>
              <p class="mb-0">
                <strong>Email:</strong> <span id="providerModalEmail" class="text-accent">jane.doe@email.com</span>
              </p>
              <p class="mb-0">
                <strong>Phone:</strong> <span id="providerModalPhone" class="text-accent">+254 700 123 456</span>
              </p>
            </div>
            <div class="ms-auto text-end">
              <h6 id="modalRate" class="text-accent mb-0">KSH 25/hr</h6>
              <small id="modalDelivery" class="d-block text-muted">5 days delivery</small>
              <small id="modalJobs" class="text-success">✅ 42 jobs completed</small>
            </div>
          </div>

          <hr>

          <p><strong>Location:</strong>
            <span id="modalLocation" class="text-accent">Nairobi, Kenya</span>
          </p>
        </div>

        <!-- Footer -->
        <div class="modal-footer border-top" id="proposalModalFooter">
          <div id="assignBox">
            <button type="button" class="btn btn-outline-navy" data-bs-toggle="modal" data-bs-target="#chatModal">Message</button>
            <button type="button" id="assignBtn" class="assignBtn btn btn-accent">Assign</button>
            <button type="button" class="btn btn-outline-danger">Reject</button>
          </div>
          <div id="confirmAssign" style="display:none; align-items: center; gap: 0.5rem;">
            <p class="mb-0">You're about to assign the request to the above provider, are sure?</p>
            <button class="actualAssignBtn m-1 btn btn-success" id="actualAssignBtn">Yes, Assign</button>
            <button id="cancelAssignBtn" class="m-1 btn btn-outline-danger">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- provider details-->
  <div class="modal fade" id="providerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content custom-modal">

        <!-- Header -->
        <div class="modal-header border-bottom">
          <h5 class="modal-title text-navy fw-bold">Provider Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body text-dark">
          <div class="d-flex align-items-start mb-3">
            <img id="providerModalPhoto"
              src="https://i.pravatar.cc/70"
              alt="Profile Picture"
              class="rounded-circle me-3 border border-2 border-navy"
              style="width:70px; height:70px;">
            <div>
              <h5 id="providerModalName" class="mb-0">
                Jane Doe
                <span id="modalBadge" class="badge bg-warning text-dark ms-2">Top Rated</span>
              </h5>
              <p id="providerModalTitle" class="text-muted mb-1">Full Stack Developer | React & Node.js</p>

              <!-- ✅ New contact details -->
              <p class="mb-0">
                <strong>Email:</strong> <span id="providerModalEmail" class="text-accent">jane.doe@email.com</span>
              </p>
              <p class="mb-0">
                <strong>Phone:</strong> <span id="providerModalPhone" class="text-accent">+254 700 123 456</span>
              </p>
            </div>
            <div class="ms-auto text-end">
              <h6 id="providerModalRate" class="text-accent mb-0">$25/hr</h6>
              <small id="providerModalDelivery" class="d-block text-muted">5 days delivery</small>
              <small id="providerModalJobs" class="text-success">✅ 42 jobs completed</small>
            </div>
          </div>

          <hr>

          <p><strong>Location:</strong>
            <span id="providerModalLocation" class="text-accent">Nairobi, Kenya</span>
          </p>
        </div>

        <!-- Footer -->
        <div class="modal-footer border-top">
          <div id="terminateBox">
            <button type="button" class="messageBtn btn btn-outline-navy">Message</button>
            <button type="button" id="terminateBtn" class="terminateBtn btn btn-outline-danger">Terminate</button>
          </div>
          <div id="confirmTerminateBox" style="display:none; align-items: center; gap: 0.5rem;">
            <p class="mb-0">You're about to terminate the assignment to <span id="providerName"></span> are sure?</p>
            <button class="actualTerminateBtn m-1 btn text-white" id="actualTerminateBtn">Yes, terminate</button>
            <button class="terminateCancel m-1 btn btn-outline-danger text-dark" style="" id="cancelTerminateBtn">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Payment Modals -->
  <!-- Record Payment Modal -->
  <div class="modal fade" id="recordPaymentModal" tabindex="-1" aria-labelledby="recordPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius: 12px; border: 1px solid #00192D;">
        <div class="modal-header" style="background-color: #00192D; color: white;">
          <h5 class="modal-title" id="payExpenseLabel">Record Request Payment</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <form id="payRequestForm">
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
          <button type="submit" form="payRequestForm" class="btn" style="background-color: #FFC107; color: #00192D;">
            <i class="bi bi-credit-card"></i> Confirm Payment
          </button>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- CHAT MODAL -->
  <!-- Floating Mini Chat Panel -->
  <div class="chat-panel" id="chatPanel">
    <div class="chat-panel-header">
      Messages
      <i class="bi bi-x-lg" id="closeChatPanel" style="cursor:pointer;"></i>
    </div>
    <div class="chat-list">
      <div class="chat-item" data-client="GreenLeaf Property Services">
        <strong>GreenLeaf Property Services</strong><br>
        <small>Can we review the last design updates?</small>
      </div>
      <div class="chat-item" data-client="Nairobi Water Services">
        <strong>Nairobi Water Services</strong><br>
        <small>Got the latest quote?</small>
      </div>
      <div class="chat-item" data-client="ZuriHost Softwares">
        <strong>ZuriHost Softwares</strong><br>
        <small>Thanks for sending the document!</small>
      </div>
    </div>
  </div>

  <!-- Right-side Chat Modal -->
  <div class="modal right fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title mb-0" id="chatModalLabel">Chat with Client</h5>
            <small class="text-muted" id="projectSubtitle">Ongoing Project</small>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="chat-body" id="chatBody">
          <div class="message client">
            <div class="bubble">Hello!</div>
          </div>
          <div class="message me">
            <div class="bubble">Hi there 👋</div>
          </div>
        </div>

        <div class="chat-footer">
          <form id="chatForm" class="input-group">
            <input
              type="text"
              id="chatInput"
              class="form-control"
              placeholder="Type a message..."
              required>
            <button class="btn btn-primary" id="chatSendBtn" type="submit">
              <i class="bi bi-send"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Set the reqeust available -->
  <div class="modal fade" id="durationBudgetModal" tabindex="-1" aria-labelledby="availabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(135deg, #00192D 0%, #003d5c 100%)">
          <h5 class="modal-title" id="availabilityModalLabel" style="color: white;">Set Budget and Duration</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form
          id=""
          action=""
          method="POST">
          <div class="modal-body">

            <input
              type="hidden"
              id="requestIdInput"
              name="request_id"
              value="<?= htmlspecialchars($request['id']) ?>">

            <!-- Price Input -->
            <div class="mb-3">
              <label for="priceInput" class="form-label">Enter Price</label>
              <input
                type="number"
                class="form-control"
                id="priceInput"
                name="budget"
                placeholder="Enter price"
                required>
            </div>

            <!-- Duration Selection -->
            <div class="mb-3">
              <label for="durationSelect" class="form-label">Duration</label>
              <select class="form-select" id="durationSelect" name="durationOption" required>
                <option value="">-- Choose Duration --</option>
                <option value="<24">Less than 24 hrs</option>
                <option value="1">1 day</option>
                <option value="2">2 days</option>
                <option value="3">3 days</option>
                <option value="custom">Enter your own</option>
              </select>
            </div>

            <!-- Custom Duration -->
            <div class="mb-3" id="customDurationDiv" style="display:none;">
              <label for="customDurationInput" class="form-label">Enter Custom Duration (days)</label>
              <input
                type="number"
                class="form-control"
                id="customDurationInput"
                name="customDuration"
                min="1">
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn bg-secondary text-white" data-bs-dismiss="modal">
              Close
            </button>
            <button type="submit" name="setBudgetDuration" class="btn" style="background-color:#00192D;color:white;">
              Confirm
            </button>
          </div>
        </form>

        <!-- Script -->
        <script>
          const durationSelect = document.getElementById('durationSelect');
          const customDurationDiv = document.getElementById('customDurationDiv');

          durationSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
              customDurationDiv.style.display = 'block';
            } else {
              customDurationDiv.style.display = 'none';
            }
          });
        </script>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <!-- Main Js File -->
  <script src="../../assets/main.js"></script>
  <!-- html2pdf depends on html2canvas and jsPDF -->
  <script type="module" src="./JS/requestDetails/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


  <script>
    // truncate the description in the request list
    document.querySelectorAll('.request-desc').forEach(el => {
      const text = el.textContent.trim();
      if (text.length > 60) {
        el.textContent = text.substring(0, 60) + '...';
      }
    });
  </script>
  <!-- change dates to nice human readabale format -->
  <script>
    document.querySelectorAll('.requestItemDate').forEach(el => {
      const dateStr = el.textContent.trim(); // Get the date text
      const dateObj = new Date(dateStr); // Convert to Date object

      if (!isNaN(dateObj)) { // Check if it's a valid date
        el.textContent = dateObj.toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric'
        });
      }
    });
  </script>



  <!-- payment accordian -->
  <!-- <script>
    document.getElementById("paidBtn").addEventListener("click", function() {
      const paymentContainer = document.getElementById("paymentContainer");

      if (paymentContainer.style.display === "none" || paymentContainer.style.display === "") {
        paymentContainer.style.display = "block";
        this.style.backgroundColor = "white"; // Change to white when opened
      } else {
        paymentContainer.style.display = "none";
        this.style.backgroundColor = ""; // Revert to default when closed
      }
    });
  </script> -->

  <!-- Record PAYMENT -->
  <!-- <script>
    document.getElementById("openRecordPaymentModalBtn").addEventListener("click", function() {
      // Get the modal element
      var myModal = new bootstrap.Modal(document.getElementById("recordPaymentModal"));
      myModal.show();
    });
  </script> -->


  <!-- Pay Request -->
  <script>
    document.getElementById("payRequestForm").addEventListener("submit", function(e) {
      e.preventDefault();
      console.log('PayexpenseForm working');

      const form = document.getElementById("payRequestForm");
      const formData = new FormData(form);

      for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
      }
      fetch("actions/individual/payRequest.php", {
          method: "POST",
          body: formData,
        })
        .then(response => response.text())
        .then(data => {
          console.log("Server response:", data);

          // ✅ Reload the page without resubmission
          window.location.href = window.location.href;
        })
        .catch(error => {
          console.error("Error submitting form:", error);
        });
    });
  </script>


  <!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
      const toggles = [{
        btn: "mobileNavToggleProperty",
        menu: "mobileNavMenuProperty"
      }];

      toggles.forEach(({
        btn,
        menu
      }) => {
        const button = document.getElementById(btn);
        const dropdown = document.getElementById(menu);

        button.addEventListener("click", () => {
          dropdown.classList.toggle("d-none");
        });

        document.addEventListener("click", (e) => {
          if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add("d-none");
          }
        });
      });
    });
  </script> -->
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

  <!-- Offcanvas script -->
  <script>
    // Handle View Details button click to populate offcanvas with provider data
    document.addEventListener('DOMContentLoaded', function() {
      const viewDetailsBtns = document.querySelectorAll('.viewDetails-btn');

      viewDetailsBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          const providerName = this.getAttribute('data-provider-name');

          // Update provider name in offcanvas
          if (providerName) {
            document.getElementById('providerName').textContent = providerName;

            // Update avatar with provider name
            const avatarImg = document.getElementById('providerAvatar');
            avatarImg.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(providerName)}&size=100&background=FFC107&color=00192D`;
            avatarImg.alt = providerName;
          }

          // You can add more data attributes and update other fields here
          // For example:
          // const providerEmail = this.getAttribute('data-provider-email');
          // if (providerEmail) {
          //     document.getElementById('providerEmail').textContent = providerEmail;
          //     document.getElementById('providerEmail').href = 'mailto:' + providerEmail;
          // }
        });
      });
    });

    function setAvailable() {
      if (confirm('Are you sure you want to set this request as available for bidding?')) {
        alert('Request has been set as available!');
        // Add your logic here
      }
    }

    function cancelRequest() {
      if (confirm('Are you sure you want to cancel this request? This action cannot be undone.')) {
        alert('Request has been cancelled!');
        // Add your logic here
      }
    }

    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth'
          });
        }
      });
    });

    // Optional: Add event listeners for offcanvas buttons if needed
    // You can populate these with PHP data or handle them with your backend
    document.getElementById('acceptProposalBtn')?.addEventListener('click', function() {
      alert('Accept proposal functionality - connect to your PHP backend');
    });

    document.getElementById('contactProviderBtn')?.addEventListener('click', function() {
      const phone = document.getElementById('providerPhone').textContent;
      window.location.href = `tel:${phone}`;
    });

    document.getElementById('messageProviderBtn')?.addEventListener('click', function() {
      alert('Message provider functionality - connect to your PHP backend');
    });
  </script>

</body>

</html>