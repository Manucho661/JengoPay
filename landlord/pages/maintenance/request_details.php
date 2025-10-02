<?php
require_once "actions/individual/getARequest.php";
require_once "actions/individual/getGeralRequests.php";

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

  <style>
    .app-wrapper {
      background-color: rgba(128, 128, 128, 0.1);
    }

    .layout {
      display: flex;
      height: calc(100vh - 60px);
      width: 100%;
      overflow: hidden;
    }

    .sidebar-container {
      width: 250px;
      background-color: #00192D;
      color: #fff;
      overflow-y: auto;
    }

    .request-sidebar {
      background: #ffffff;
      display: flex;
      flex-direction: column;
      border-right: 1px solid #e0e0e0;
    }

    .request-sidebar h3 {
      background: #00192D;
      color: white;
      padding: 1.2rem;
      font-size: 1.2rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 0;
      /* border-top-right-radius: 10px; */
      border-top-left-radius: 10px;
    }

    .search-bar {
      padding: 1rem;
      background: #fff;
      border-bottom: 1px solid #e0e0e0;
    }

    .search-bar input {
      width: 100%;
      padding: 0.6rem 1rem;
      border-radius: 30px;
      border: 1px solid #ced4da;
      outline: none;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .search-bar input:focus {
      border-color: #FFC107;
      box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.25);
    }

    /* IMPROVED REQUEST LIST STYLES */
    .request-list {
      display: none;
      list-style: none;
      padding: 0;
      margin: 0;
      overflow-y: auto;
    }

    .request-item {
      padding: 15px;
      border-bottom: 1px solid rgba(0, 25, 45, 0.1);
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 12px;
      background: white;
      margin: 8px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .request-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 25, 45, 0.1);
      background: #FFF7E0;
    }

    .request-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #00192D;
      color: #FFC107;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }

    .request-content {
      flex-grow: 1;
      min-width: 0;
    }

    .request-desc {
      font-weight: 500;
      color: #00192D;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 4px;
    }

    .request-meta {
      display: flex;
      gap: 10px;
      font-size: 0.85rem;
      color: #6c757d;
      flex-wrap: wrap;
    }

    .request-date {
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .request-status {
      display: flex;
      align-items: center;
      gap: 4px;
    }


    /* Status indicators */
    .status-pending {
      color: #FFC107;
    }

    .status-completed {
      color: #28a745;
    }

    .status-in-progress {
      color: #17a2b8;
    }

    .status-cancelled {
      color: #dc3545;
    }

    /* Priority indicators */
    .priority-high {
      color: #dc3545;
    }

    .priority-medium {
      color: #fd7e14;
    }

    .priority-low {
      color: #28a745;
    }

    .detail-row {
      margin-bottom: 1.4rem;
      display: flex;
      align-items: center;
      gap: 16px;
      font-size: 1rem;
      color: #333;
    }

    .detail-label {
      font-weight: 600;
      min-width: 150px;
      color: #00192D;
    }

    .detail-icon {
      color: #FFC107;
      font-size: 1.2rem;
      width: 28px;
      text-align: center;
    }



    .photo-preview {
      border-radius: 10px;
      /* border: 1px solid #ccc; */
      max-width: 100%;
      margin-top: 5px;
    }

    .no-selection {
      text-align: center;
      margin-top: 20%;
      color: #999;
      font-size: 1.3rem;
    }

    footer {
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
    }

    .row-card {
      background-color: #fff;
      border-radius: 10px;
      /* box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08); */
      padding: 1.5rem;
      border-left: 5px solid transparent;
    }

    .row-card:hover {
      background-color: #fdfaf3;
    }

    .detail-row {
      display: flex;
      align-items: center;
      gap: 8px;
      line-height: 1.3;
    }

    .detail-icon {
      width: 18px;
      text-align: center;
      color: #FFC107;
    }

    .detail-label {
      font-weight: 500;
      color: #FFC107;
      min-width: fit-content;
    }

    .photo-preview {
      max-height: 250px;
      object-fit: cover;
    }


    .active-request {
      background-color: #FFF7E0;
      border-left: 3px solid #FFC107;
    }

    /* Button Container */
    /* Button Container */
    .button-container {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      width: 100%;
      padding: 0.5rem;
    }

    /* Base Button Styles */
    .availability-btn,
    .unassigned-btn,
    .paid-btn {
      padding: 0.625rem 1rem;
      font-weight: 500;
      width: 100%;
      border: none;
      border-radius: 0.25rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Unassigned Button */
    .unassigned-btn {
      background-color: white;
      color: #00192D;
      /* border: 1px solid #00192D !important; */
    }

    /* Paid Button */

    /* Button Icons */
    .availability-btn i,
    .unassigned-btn i,
    .paid-btn i {
      width: 1.25rem;
      text-align: center;
      margin-right: 0.5rem;
    }

    /* Hover States */
    .availability-btn:hover {
      background-color: #5a6268;
      transform: translateY(-1px);
    }

    .unassigned-btn:hover {
      background-color: #f8f9fa;
      transform: translateY(-1px);
    }

    .paid-btn:hover {
      background-color: #e0a800;
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Active States */
    .availability-btn:active,
    .unassigned-btn:active,
    .paid-btn:active {
      transform: translateY(0);
      box-shadow: none;
    }

    /* Focus States */
    .availability-btn:focus,
    .unassigned-btn:focus,
    .paid-btn:focus {
      outline: none;
      box-shadow: 0 0 0 0.25rem rgba(0, 25, 45, 0.25);
    }

    /* Secondary Buttons Container */
    .secondary-buttons {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      width: 100%;
      display: none;
      /* Hidden by default */
    }

    .btn:focus {
      box-shadow: none !important;
      outline: none;
    }

    .btn:hover {
      /* border: 1px solid #FFC107 !important; */
      background-color: white !important;
    }

    /* Applications */
    .container.proposalContainer {
      width: 80%;
      margin: 0 auto;
    }

    .proposal-card {
      background-color: #fff;
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 30px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.07);
      transition: transform 0.2s ease;

      margin: 0 auto;
    }

    .proposal-card:hover {
      transform: scale(1.01);
    }

    .proposal-header {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .profile-pic {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e3e3e3;
    }

    .proposal-header h5 {
      margin-bottom: 4px;
      font-weight: 600;
    }

    .proposal-header p {
      margin: 0;
      font-size: 0.95rem;
      color: #6c757d;
    }

    .proposal-meta {
      text-align: right;
    }

    .proposal-meta h6 {
      margin-bottom: 4px;
      font-size: 1rem;
      color: #0d6efd;
    }

    .proposal-meta small {
      color: #888;
      display: block;
    }

    .btn-action {
      margin-left: 8px;
    }

    hr {
      margin: 1rem 0;
    }

    #customBackdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 10000;
    }

    #proposalContainer {
      position: fixed;
      /* detach from page flow */
      top: 50%;
      /* vertical center */
      left: 50%;
      /* horizontal center */
      transform: translate(-50%, -50%);
      max-height: 90vh;
      width: 600px;
      /* adjust width as needed */
      overflow-y: auto;
      background: black;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
      z-index: 10000;
      /* above backdrop */
    }

    #proposalContainer {
      position: fixed;
      /* detach from page flow */
      top: 50%;
      /* vertical center */
      left: 50%;
      /* horizontal center */
      transform: translate(-50%, -50%);
      max-height: 90vh;
      width: 600px;
      /* adjust width as needed */
      overflow-y: auto;
      background: black;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
      z-index: 1050;
      /* above backdrop */
    }


    .payment-container {
      overflow: hidden;
      transition: max-height 0.3s ease;
    }

    .unavailable {
      background-color: #E6EAF0 !important;
    }

    .available {
      background-color: white !important;
    }

    .requestsArea {
      min-height: 100vh;
    }

    /* the propsal and requests section */

    /* proposals */
    .proposals {
      list-style: none;
      padding: 0;
      margin: 0;
      overflow-y: auto;
    }

    .proposals-list {
      display: none;
      list-style: none;
      padding: 0;
      margin: 0;
      overflow-y: auto;
      min-height: 600px;
    }

    .visible {
      display: block;
    }

    .proposal-item {
      padding: 15px;
      border-bottom: 1px solid rgba(0, 25, 45, 0.1);
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 12px;
      background: white;
      margin: 8px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .active-btn {
      background-color: white;
      /* your brand blue */

      font-weight: bold;
    }

    /* Custom modal theme */
    /* Modal container */
    .custom-modal {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Navy + Gold palette */
    .text-navy {
      color: #00192D !important;
    }

    .border-navy {
      border-color: #00192D !important;
    }

    .text-accent {
      color: #e0a800 !important;
    }

    /* Accent button */
    .btn-accent {
      background-color: #e0a800;
      color: #00192D;
      border: none;
    }

    .btn-accent:hover {
      background-color: #c99700;
      color: #fff;
    }

    /* Outline navy button */
    .btn-outline-navy {
      border: 1px solid #00192D;
      color: #00192D;
    }

    .btn-outline-navy:hover {
      background-color: #00192D;
      color: #fff;
    }


    #assignBox .btn-outline-navy:hover {
      color: navy !important;
      /* Keep text navy */
      background-color: rgba(0, 43, 91, 0.1) !important;
      /* optional subtle bg */
    }

    #assignBox .btn-outline-danger:hover {
      color: red !important;
      /* Keep text red */
      background-color: rgba(220, 53, 69, 0.1) !important;
      border: none;
    }

    #assignBox .btn-accent:hover {
      color: #00192D !important;
      border-color: #c99700 !important;
      border: 1px solid #c99700 !important;
    }

    .request-provider:hover {
      cursor: pointer;
      text-decoration: underline;
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

    #confirmAssign .btn-success:hover {
      color: #28a745 !important;
      background-color: rgba(40, 167, 69, 0.1) !important;
      border: none;
    }

    #confirmAssign .btn-outline-danger:hover {
      color: red !important;
      background-color: rgba(220, 53, 69, 0.1) !important;
      border: none;
    }

    .contact_section_header,
    h3,
    .emoji {
      font-family: "Segoe UI Emoji", "Apple Color Emoji", "Noto Color Emoji", "Twemoji Mozilla", sans-serif;
    }
  </style>
</head>

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
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row align-items-center mb-3">
            <div class="col-sm-8">
              <div class="d-flex">
                <h3 class="mb-0"> 🛠 <span class="contact_section_header">Maintenance Requests</span> </h3>
                <p class="text-muted mt-2">&nbsp;:-
                  <b><span id="requestID" class="text-success"><?php echo $requestId; ?></span></b>
                </p>
                <p class="text-muted mx-5 mt-2"> Received:- <span class="text-dark">19-02-25</span></p>
              </div>
            </div>
            <div class="col-sm-4">
              <ul class="nav justify-content-end border-bottom" id="requestNav">
                <li class="nav-item">
                  <a class="nav-link" href="maintenance.php" data-tab="all">All Requests</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" data-tab="saved">Saved</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" data-tab="cancelled">Cancelled</a>
                </li>
              </ul>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <div class="app-content">
        <div class="container-fluid rounded-2 mb-2">
          <div class="row p-1">
            <div class="col-md-4 px-2">
              <a href="javascript:history.back()"
                class="btn shadow-none rounded-4 shadow-sm"
                style="background-color: #E6EAF0;; color: #00192D; font-weight: 500; width:100%;">
                All Requests
              </a>
            </div>
            <div class="col-md-4 px-2">
              <button id="availabilityBtn"
                class="btn shadow-none rounded-4 shadow-sm"
                style="background-color: #E6EAF0; color: #00192D; font-weight: 500; width:100%;">
                <!-- JS will fill this -->
              </button>
            </div>
            <div class="col-md-4 px-2">
              <button id="cancelRequestBtn"
                class="btn shadow-none text-danger rounded-4 shadow-sm" data-request-id="<?php echo $requestId; ?>" data-status="<?php echo $request['availability']; ?>"
                style="background-color: #E6EAF0; color: #00192D; font-weight: 500; width:100%;  margin-left:2px; ">
                Cancel
              </button>
            </div>
          </div>
        </div>
        <div class="container-fluid rounded-2 p-1">
          <div class="row">

            <div class="col-md-7" style="padding-right:5px; padding-top:0 !important;">
              <!-- content displays here -->
              <div class="container-fluid main-content" style="padding: 0px !important;">

                <!-- Row 1: Property, Unit, Provider, Status -->
                <div class="row-card mb-1 p-3 rounded border-0">
                  <div class="row gx-3 gy-3 p-3 rounded border-0">

                    <!-- Property -->
                    <div class="col-md-3">
                      <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                        <span style="background-color: #00192D; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                          <i class="fa-solid fa-building" style="color: #FFC107; font-size: 16px;"></i>
                        </span>
                        <span style="font-weight: 600;">Property</span>
                      </div>
                      <div id="request-property" style="margin-top: 6px; font-size: 15px; color: #333;"></div>
                    </div>

                    <!-- Unit -->
                    <div class="col-md-2">
                      <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                        <span style="background-color: #00192D; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                          <i class="fa-solid fa-door-closed" style="color: #FFC107; font-size: 16px;"></i>
                        </span>
                        <span style="font-weight: 600;">Unit</span>
                      </div>
                      <div id="request-unit" style="margin-top: 6px; font-size: 15px; color: #333;"></div>
                    </div>

                    <!-- Provider -->
                    <div class="col-md-4">
                      <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                        <span style="background-color: #00192D; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                          <i class="bi bi-file-text" style="color: #FFC107; font-size: 16px;"></i>
                        </span>
                        <span style="font-weight: 600;">Provider</span>
                      </div>
                      <div id="request-provider" class="request-provider text-success" style="margin-top: 6px; font-size: 15px; color: #b93232ff;">Unassigned</div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-3">
                      <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                        <span style="background-color: #00192D; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                          <i class="bi bi-file-text" style="color: #FFC107; font-size: 16px;"></i>
                        </span>
                        <span style="font-weight: 600;">Status</span>
                      </div>
                      <div id="request-status" style="margin-top: 6px; font-size: 15px; color: #b93232ff;">Unassigned</div>
                    </div>

                  </div>
                </div>

                <!-- Row 2: Category & Description -->
                <div class="row-card mb-1 p-3 rounded bg-white">
                  <div class="row gx-3 gy-3 p-3 rounded border-0" style="border: 1px solid #e0e0e0;">
                    <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                      <span style="background-color: #00192D; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <i class="fa-solid fa-align-left" style="color: white; font-size: 16px;"></i>
                      </span>
                      <span style="font-weight: 600;">Description</span>
                    </div>
                    <div id="request-description" class="text-muted" style="margin-top: 6px; font-size: 15px; color: #333; line-height: 1.6;"></div>
                  </div>
                </div>

                <!-- Row 3: Photo -->
                <div class="row-card mb-1 p-3 rounded bg-white">
                  <div class="row gx-3 gy-3 p-3 rounded border-0">
                    <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                      <span style="background-color: #00192D; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <i class="fa-solid fa-image" style="color: white; font-size: 16px;"></i>
                      </span>
                      <span style="font-weight: 600;">Request Image</span>
                    </div>
                    <img id="request-photo" src="" alt="Photo" class="photo-preview w-100 rounded">
                  </div>
                </div>

              </div>
            </div>
            <div class="col-md-5" style="overflow: hidden; padding-right:10px; padding-left:0px !important;">
              <div class="request-sidebar rounded-2">
                <!-- <h3><i class="fa-solid fa-screwdriver-wrench"></i>Request NO 40</h3> -->
                <div class="d-flex flex-column p-2">
                  <!-- Secondary Buttons Container -->
                  <div id="secondaryButtons" class="secondary-buttons p-1 rounded-2" style="background-color: #E6EAF0;">
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

                  </ul>
                  <!-- request list -->
                  <ul class="request-list" id="requestList">
                    <?php foreach ($requests as $requestItem) : ?>
                      <li class="request-item">
                        <div class="request-icon">
                          <i class="fas fa-tools"></i>
                        </div>
                        <div class="request-content">
                          <div class="request-desc">
                            <?= $requestItem['description'] ?>
                          </div>
                          <div class="request-meta">
                            <div class="request-date">
                              <i class="far fa-calendar-alt"></i>
                              <span class="requestItemDate"><?= $requestItem['request_date'] ?></span>
                            </div>
                            <div class="request-status">
                              <i class="fas fa-circle"></i>
                              <?= $requestItem['status'] ?>
                            </div>

                            <div class="request-priority">
                              <i class="fas fa-circle"></i>
                              <?= $requestItem['priority'] ?>
                            </div>

                          </div>
                        </div>
                      </li>
                    <?php endforeach ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
    </main>
    <!-- Begin Footer -->
              <div> <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?> </div> 
              <!-- end footer -->
  </div>

  <!-- ASSign Modal -->
  <div class="modal fade" id="assignProviderModal" tabindex="-1" aria-labelledby="assignProviderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-3">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="assignProviderModalLabel">
            <i class="bi bi-person-check-fill me-2"></i>Assign Service Provider
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Form -->
        <form id="assignProviderForm">
          <div class="modal-body">

            <!-- Hidden Fields -->
            <input type="hidden" name="maintenance_request_id" id="maintenance_request_id">
            <input type="hidden" name="unit_id" id="unit_id">

            <!-- Service Provider Dropdown -->
            <div class="mb-3">
              <label for="service_provider_id" class="form-label">
                <i class="bi bi-tools me-1"></i>Service Provider
              </label>
              <select class="form-select" name="service_provider_id" id="service_provider_id" required>
                <option selected disabled value="">Select a provider</option>
                <!-- Populate dynamically -->
                <option value="1">John Doe - Plumbing</option>
                <option value="2">Jane Smith - Electrical</option>
              </select>
            </div>

            <!-- Scheduled Date -->
            <div class="mb-3">
              <label for="scheduled_date" class="form-label">
                <i class="bi bi-calendar-event me-1"></i>Scheduled Date
              </label>
              <input type="date" class="form-control" name="scheduled_date" id="scheduled_date">
            </div>

            <!-- Notes / Instructions -->
            <div class="mb-3">
              <label for="instructions" class="form-label">
                <i class="bi bi-pencil-square me-1"></i>Instructions
              </label>
              <textarea class="form-control" name="instructions" id="instructions" rows="3" placeholder="Any special notes..."></textarea>
            </div>

          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle me-1"></i>Cancel
            </button>
            <button type="submit" class="btn btn-success">
              <i class="bi bi-check2-circle me-1"></i>Assign
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
  </div>
  <!-- Modal -->
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
              src="https://i.pravatar.cc/70"
              alt="Profile Picture"
              class="rounded-circle me-3 border border-2 border-navy"
              style="width:70px; height:70px;">
            <div>
              <h5 id="modalName" class="mb-0">
                Jane Doe
                <span id="modalBadge" class="badge bg-warning text-dark ms-2">Top Rated</span>
              </h5>
              <p id="modalTitle" class="text-muted mb-0">Full Stack Developer | React & Node.js</p>
            </div>
            <div class="ms-auto text-end">
              <h6 id="modalRate" class="text-accent mb-0">$25/hr</h6>
              <small id="modalDelivery" class="d-block text-muted">5 days delivery</small>
              <small id="modalJobs" class="text-success">✅ 42 jobs completed</small>
            </div>
          </div>

          <hr>

          <p><strong>Cover Letter:</strong></p>
          <p id="modalDescription" class="bg-light p-2 rounded border">
            Default cover letter here...
          </p>

          <p><strong>Location:</strong>
            <span id="modalLocation" class="text-accent">Nairobi, Kenya</span>
          </p>
        </div>

        <!-- Footer -->
        <div class="modal-footer border-top">
          <div id="assignBox">
            <button type="button" class="btn btn-outline-navy">Message</button>
            <button type="button" id="assignBtn" class="btn btn-accent">Assign</button>
            <button type="button" class="btn btn-outline-danger">Reject</button>
          </div>
          <div id="confirmAssign" style="display:none; align-items: center; gap: 0.5rem;">
            <p class="mb-0">You're about to assign the request to the above provider, are sure?</p>
            <button class="m-1 btn btn-success" id="actualAssignBtn">Yes, Assign</button>
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

          <p><strong>Cover Letter:</strong></p>
          <p id="providerModalDescription" class="bg-light p-2 rounded border">
            Default cover letter here...
          </p>

          <p><strong>Location:</strong>
            <span id="providerModalLocation" class="text-accent">Nairobi, Kenya</span>
          </p>
        </div>

        <!-- Footer -->
        <div class="modal-footer border-top">
          <div id="assignBox">
            <button type="button" class="btn btn-outline-navy">Message</button>
            <button type="button" class="btn btn-accent">Assign</button>
            <button type="button" class="btn btn-outline-danger">Reject</button>
          </div>
          <div  style="display:none; align-items: center; gap: 0.5rem;">
            <p class="mb-0">You're about to assign the request to the above provider, are sure?</p>
            <button class="m-1 btn btn-success" id="actualAssignBtn">Yes, Assign</button>
            <button class="m-1 btn btn-outline-danger">Cancel</button>
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

  <!-- control the requests list sidebar height -->
  <script>
    const first = document.querySelector('.main-content');
    const second = document.querySelector('.request-sidebar');


    function syncHeight() {
      const firstHeight = first.offsetHeight; // measure first element
      console.log(firstHeight);

      second.style.maxHeight = firstHeight + 'px'; // cap second
    }

    window.addEventListener('resize', syncHeight);
    window.addEventListener('load', syncHeight);
    syncHeight();
  </script>

  <script src="../../js/adminlte.js"></script>
</body>

</html>