<?php
session_start(); // Must be called before any HTML output

if (isset($_GET['id'])) {
  $id = $_GET['id'];          // get the ID from the URL
  $_SESSION['id'] = $id;      // store it in the session
  // echo "ID $id has been saved in session!";
} else {
  echo "No ID found in the URL.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE | Dashboard v2</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE | Dashboard v2" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />

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
  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="../../css/adminlte.css" />
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
  
    .preloader-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 70vh; /* adjust to taste */
  color: #333;
  font-family: Arial, sans-serif;
}

.pulse {
  position: relative;
  width: 90px;
  height: 90px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: visible;
  transition: transform 0.3s ease;
}

/* ripple uses semi-transparent same-hue */
.ripple {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: rgba(255, 193, 7, 0.45); /* soft ripple */
  animation: ripple 1.5s infinite ease-out;
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

.percentage {
  margin-top: 14px;
  font-size: 1.25rem;
  font-weight: 700;
  transition: opacity 0.4s ease, transform 0.25s ease;
}

/* fade class used to fade out both pulse and percentage smoothly */
.fade {
  /* opacity: 0; */
}
/* Hidden state before animation */

</style>

<body class="layout-fixed sidebar-expand-lg bg-body-dark" style="">
  <div class="app-wrapper" style="height: 100 vh; ">
    <!--begin::Header-->
    <?php include_once '../includes/header.php' ?>
    <!--end::Header-->
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

    <!-- Main Layout -->
    <div id="preloader" class="preloader-container">
      <div id="pulse" class="pulse bg-yellow-600">
        <span class="ripple"></span>
      </div>
      <div id="percent" class="percentage">0%</div>
    </div>
    <main class="app-main" id="appMain" style="display: none;">
      <!--begin::App Content Header-->
      <div class="app-content-header bg-white mb-2">
        <div class="container-fluid">
          <div class="row align-items-center gy-3 gx-2 mb-2">

            <!-- Request Name -->
            <div class="col-lg-4 col-md-6 col-12 d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="request-icon bg-warning text-white"><i class="fas fa-tools"></i></div>
                <h3 class="mb-0 fw-bold contact_section_header" id="request-name">
                  <!-- Dynamic request name -->
                </h3>
              </div>


            </div>

            <!-- Request Property -->
            <div class="col-lg-4 col-md-6 col-12 d-flex align-items-center border-left justify-content-between">


              <!-- Optional More Icon (you can hide one if not needed) -->
              <button class="btn btn-light border-0 d-lg-none mobileNavToggleProperty" id="mobileNavToggleProperty">
                <i class="bi bi-three-dots fs-5"></i>
              </button>

              <div id="mobileNavMenuProperty" class="mobile-nav-menu d-none position-absolute bg-white shadow rounded-3 mt-2">
              </div>
            </div>

            <!-- Navigation (desktop only) -->
            <div class="col-lg-4 d-none d-lg-flex justify-content-end align-items-center">
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-md-6">
              <div class="d-flex align-items-center flex-wrap">
                <span class="info-box-icon me-2 bg-warning">
                  <i class="bi bi-house fs-3 text-white"></i>
                </span>
                <div class="d-flex align-items-center flex-wrap">
                  <i><b class="mb-0 me-2" id="request-property"></b></i>
                  <i><b class="mb-0 text-success" id="request-unit"></b></i>
                </div>
              </div>
            </div>
            <div class="col-md-6 d-flex gap-1 flex-nowrap">

              <button type="button" id="availabilityBtn" class="btn seTAvailable text-white fw-bold bg-warning rounded-4"
                style="color:white; width:100%; white-space: nowrap;">
                Set Available
              </button>
              <!-- style="background: linear-gradient(135deg, #00192D, #002B5B); color:white; width:100%; white-space: nowrap;"> -->
              <button type="button" class="btn bg-danger text-white seTAvailable fw-bold rounded-4"
                style="width:100%; white-space: nowrap;">
                Cancel Request
              </button>
              <button type="button" class="btn bg-warning text-white seTAvailable fw-bold rounded-4"
                style=" color:dark; width:100%; white-space: nowrap;">
                All Requests
              </button>
            </div>
            <div class="new-messages">
              <div class="chat-toggle-btn" id="openChatPanel">
                <i class="bi bi-chat-dots-fill"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid rounded-2 mb-2">
          <div class="row bg-white rounded">
            <div class="col-md-4 border-right">
              <div class="card p-3 d-flex flex-row justify-content-between align-items-start border-0 shadow-none" style="height:100%;">

                <div class="d-flex flex-row gap-5">
                  <div>
                    <p class="fw-bold mb-1">Budget</p>
                    <p class="mb-0 fw-bold text-success" id="budget">Set Budget</p>
                  </div>
                  <div>
                    <p class="fw-bold mb-1">Duration</p>
                    <p class="mb-0 fw-bold text-warning" id="duration">Set Duration</p>
                  </div>
                </div>

                <button class="btn btn-warning btn-sm align-self-start " data-bs-toggle="modal" data-bs-target="#durationBudgetModal">
                  <i class="bi bi-pencil fw-semibold text-white"></i>
                </button>

              </div>
            </div>

            <div class="col-md-4 border-right">
              <div class="card p-3 d-flex flex-row gap-5 border-0 shadow-none d-flex ">
                <div>
                  <p class="fw-bold">Provider</p>
                  <p id="request-provider" class="request-provider text-success">Not Assigned</p>
                </div>
                <div>
                  <p class="fw-bold">Response</p>
                  <p id="provider_response" style="font-size: 15px; color: #b93232ff;" class="">Not assigned</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card p-3 d-flex flex-row gap-5 border-0 shadow-none d-flex ">
                <div>
                  <p class="fw-bold">Status</p>
                  <p id="request-status" class="request-status">Not Assigned</p>
                </div>
                <div>
                  <p class="fw-bold">Payment</p>
                  <p id="request-payment" style="font-size: 15px; color: #b93232ff;" class="">Not assigned</p>
                </div>
              </div>
            </div>
          </div>

          <div class="row py-2 bg-white rounded-2 mt-2">
            <div class="col-md-7 " style="min-height: 100%;">
              <!-- content displays here -->
              <!-- Row 2: Category & Description -->
              <div class="card p-3 shadow-none border-bottom">
                <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                  <span class="info-box-icon me-2 bg-warning p-2">
                    <i class="bi bi-info-circle fs-3 text-white p-2"></i>
                  </span>
                  <span style="font-weight: 600;">Description</span>
                </div>
                <div id="request-description" class="text-muted" style="margin-top: 6px; font-size: 15px; color: #333; line-height: 1.6;"></div>
              </div>

              <!-- Row 3: Photo -->
              <div class="card p-3 shadow-none mt-2">
                <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                  <span class="info-box-icon me-2 bg-warning p-2">
                    <i class="bi bi-image fs-3 text-white p-2"></i>
                  </span>
                  <span style="font-weight: 600;">Request Image</span>
                </div>
                <img id="request-photo" src="" alt="Photo" class="photo-preview w-100 rounded">
              </div>

            </div>
            <div class="col-md-5 border" style="max-height:500px; overflow:auto;">
              <div class="request-sidebar">
                <!-- <h3><i class="fa-solid fa-screwdriver-wrench"></i>Request NO 40</h3> -->
                <div class="d-flex flex-column">
                  <!-- Secondary Buttons Container -->
                  <div id="secondaryButtons" class="secondary-buttons p rounded-2" style="background-color: #E6EAF0;">
                    <button id="paidBtn" class="btn shadow-none">
                      <i class="fas fa-check-circle me-2"></i> Paid
                    </button>
                    <div id="paymentContainer" class="payment-container" style="display: none;">
                      <p class="text-muted justify-content-between">Choose the Option</p>
                      <div class="d-flex justify-content-between">
                        <button class="btn shadow-none">Cash</button>
                        <button class="btn shadow-none">Mpesa</button>
                        <button class="btn shadow-none">Bank</button>
                        <button class="btn shadow-none" id="openRecordPaymentModalBtn">Record</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="search-bar rounded-2">
                  <div class="text-muted rounded-2 w-100 mb-2 d-flex" style="background-color: #E6EAF0;">
                    <button onclick="toggleProposalsORotherRequests(proposals-list)" id="proposals" class="btn shadow-none m-1 border-0 shadow-0 flex-fill proposals">Proposals</button>
                    <button onclick="toggleProposalsORotherRequests(requestList)" id="otherRequests" class="btn shadow-none m-1 border-0 flex-fill">Other Requests</button>
                  </div>
                  <div>
                    <input class="rounded-2" type="text" id="searchInput" placeholder="Search by unit, category, or property...">
                  </div>
                  <!-- proposals list -->
                  <ul id="proposals-list" class="proposals-list visible">
                    <!-- Details rentered dynamical through Javascript (getProviderProposals.js) -->
                  </ul>
                  <!-- request list -->
                  <ul class="request-list" id="requestList">
                    <!-- Details rentered dynamical through Javascript (otherRequestDetails.js) -->
                  </ul>
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
        <div class="modal-header" style="background: linear-gradient(135deg, #00192D 0%, #FFC107 100%);">
          <h5 class="modal-title" id="availabilityModalLabel" style="color: white;">Set Budget and Duration</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="durationBudget">
          <div class="modal-body">
            <!-- Price Input -->
            <div class="mb-3">
              <label for="priceInput" class="form-label" style="color: white;">Enter Price</label>
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
              <label for="durationSelect" class="form-label" style="color: white;">Select Duration</label>
              <select class="form-select" id="durationSelect" name="durationOption">
                <option value="">-- Choose Duration --</option>
                <option value="<24">Less than 24 hrs</option>
                <option value="1">1 day</option>
                <option value="2">2 days</option>
                <option value="3">3 days</option>
                <option value="custom">Enter your own</option>
              </select>
            </div>

            <!-- Custom Duration Input (hidden by default) -->
            <div class="mb-3" id="customDurationDiv" style="display: none;">
              <label for="customDurationInput" class="form-label" style="color: white;">Enter Custom Duration (days)</label>
              <input
                type="number"
                class="form-control"
                id="customDurationInput"
                name="customDuration"
                placeholder="Enter duration"
                min="1">
            </div>
          </div>

          <!-- Footer Buttons -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn"
              style="background-color: #FFC107; color: #00192D;"
              data-bs-dismiss="modal">
              Close
            </button>
            <button
              type="submit"
              class="btn"
              style="background-color: #00192D; color: white;">
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
  <script>
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
  </script>

  <!-- Record PAYMENT -->
  <script>
    document.getElementById("openRecordPaymentModalBtn").addEventListener("click", function() {
      // Get the modal element
      var myModal = new bootstrap.Modal(document.getElementById("recordPaymentModal"));
      myModal.show();
    });
  </script>


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

  <script src="../../js/adminlte.js"></script>
  <script>
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
  </script>


</body>

</html>