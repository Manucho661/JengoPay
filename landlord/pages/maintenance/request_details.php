<?php
session_start(); // Must be called before any HTML output

require_once "actions/individual/getARequest.php";
require_once "actions/individual/getGeralRequests.php";

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

      font-size: 1.2rem;
      font-weight: 600;
      display: flex;
      align-items: center;


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
      /* min-height: 600px; */
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

    .actualAssignBtn {
      white-space: nowrap;
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
      white-space: nowrap !important;
    }

    #confirmAssign .btn-outline-danger:hover {
      color: red !important;
      background-color: rgba(220, 53, 69, 0.1) !important;
      border: none;
      white-space: nowrap !important;
    }

    .contact_section_header,
    h3,
    .emoji {
      font-family: "Segoe UI Emoji", "Apple Color Emoji", "Noto Color Emoji", "Twemoji Mozilla", sans-serif;
    }

    .setAvailable {
      background: linear-gradient(135deg, #00192D, #002B5B);
      margin-right: 2px !important;
    }

    .info-box-icon {
      background-color: #00192D;
      color: #fff;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
    }

    #requestNav {
      border-bottom: 2px solid rgba(255, 215, 0, 0.6);
    }

    /* General font sizing for smaller screens */
    /* Smaller font sizing for phones */
    @media (max-width: 768px) {
      h3.contact_section_header {
        font-size: 1.05rem;
      }

      h4 {
        font-size: 0.9rem;
      }

      .info-box-icon i {
        font-size: 1.2rem !important;
      }
    }

    /* Mobile dropdown styling */
    .mobile-nav-menu {
      right: 15px;
      top: 50px;
      min-width: 160px;
      z-index: 1050;
    }

    .mobile-nav-menu a {
      display: block;
      padding: 8px 12px;
      color: #002B5B;
      text-decoration: none;
    }

    .mobile-nav-menu a:hover {
      background-color: #f0f4f8;
    }

    .mobileNavToggleProperty {
      border-radius: 50%;
      display: flex;
    }

    .terminateCancel:hover {
      color: white !important;
      background-color: #b93232ff !important;
    }

    .actualTerminateBtn {
      background-color: #b93232ff !important;
      white-space: nowrap;
    }

    .actualTerminateBtn:hover {
      background-color: white !important;
      color: black !important;
      border: 1px solid #b93232ff;
    }

    .terminateBtn:hover {
      background-color: #b93232ff !important;
      color: white !important;
    }

    .messageBtn:hover {
      background-color: rgba(128, 128, 128, 0.7) !important;
      color: white !important;
    }

    /* Chat section styles */
    .modal.right .modal-dialog {
      position: fixed;
      right: 0;
      margin: 0;
      top: 0;
      bottom: 0;
      height: 100%;
      min-width: 450px;
      transform: translateX(100%);
      transition: transform 0.3s ease-out;
    }

    .modal.right.show .modal-dialog {
      transform: translateX(0);
    }

    .modal.right .modal-content {
      height: 100%;
      border: 0;
      border-radius: 0;
      display: flex;
      flex-direction: column;
    }

    .chat-body {
      flex: 1;
      overflow-y: auto;
      padding: 1rem;
      background-color: #f8f9fa;
    }

    .message {
      margin-bottom: 1rem;
      display: flex;
      align-items: flex-end;
    }

    .message.client {
      justify-content: flex-start;
    }

    .message.me {
      justify-content: flex-end;
    }

    .message .bubble {
      max-width: 75%;
      padding: 0.6rem 1rem;
      border-radius: 1rem;
      font-size: 0.95rem;
    }

    .message.client .bubble {
      background-color: #e9ecef;
      color: #212529;
      border-top-left-radius: 0;
    }

    .message.me .bubble {
      background-color: #0d6efd;
      color: #fff;
      border-top-right-radius: 0;
    }

    .chat-footer {
      border-top: 1px solid #dee2e6;
      padding: 0.5rem;
      background-color: #fff;
    }

    .chat-footer input {
      border-radius: 2rem;
      padding: 0.5rem 1rem;
    }

    /* === Floating mini chat panel === */
    .chat-panel {
      position: fixed;
      top: 80px;
      /* Adjust distance from top */
      right: 30px;
      width: 320px;
      max-height: 400px;
      background: white;
      border: 1px solid #dee2e6;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      display: none;
      flex-direction: column;
      overflow: hidden;
      z-index: 1050;
    }

    .chat-panel-header {
      background-color: #FFC107;
      color: #00192D;
      padding: 0.75rem 1rem;
      font-weight: 500;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .chat-list {
      flex: 1;
      overflow-y: auto;
    }

    .chat-item {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid #f1f1f1;
      cursor: pointer;
      transition: background 0.2s;
    }

    .chat-item:hover {
      background: #f8f9fa;
    }

    .chat-item small {
      color: #6c757d;
    }

    /* === Top-right chat toggle button === */
    .chat-toggle-btn {
      position: fixed;
      top: 20px;
      right: 20px;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      background-color: #FFC107;
      color: white;
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      z-index: 1100;
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
      <div class="app-content-header bg-white mb-2">
        <div class="container-fluid">
          <div class="row align-items-center gy-3 gx-2 mb-2">

            <!-- Request Name -->
            <div class="col-lg-4 col-md-6 col-12 d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center flex-wrap">
                <span class="info-box-icon me-2">
                  <i class="bi bi-tools fs-3 text-white"></i>
                </span>
                <h3 class="mb-0 fw-bold contact_section_header" id="request-name">
                  <!-- Dynamic request name -->
                </h3>
              </div>


            </div>

            <!-- Request Property -->
            <div class="col-lg-4 col-md-6 col-12 d-flex align-items-center border-left justify-content-between">
              <div class="d-flex align-items-center flex-wrap">
                <span class="info-box-icon me-2">
                  <i class="bi bi-house fs-3 text-white"></i>
                </span>
                <div class="d-flex align-items-center flex-wrap">
                  <h4 class="mb-0 me-2" id="request-property"></h4>
                  <h4 class="mb-0 text-success" id="request-unit"></h4>
                </div>
              </div>

              <!-- Optional More Icon (you can hide one if not needed) -->
              <button class="btn btn-light border-0 d-lg-none mobileNavToggleProperty" id="mobileNavToggleProperty">
                <i class="bi bi-three-dots fs-5"></i>
              </button>

              <div id="mobileNavMenuProperty" class="mobile-nav-menu d-none position-absolute bg-white shadow rounded-3 mt-2">
                <!-- <a class="dropdown-item" href="maintenance.php" data-tab="all">All Requests</a>
                <a class="dropdown-item" href="#" data-tab="saved">Saved</a>
                <a class="dropdown-item" href="#" data-tab="cancelled">Cancelled</a> -->
              </div>
            </div>

            <!-- Navigation (desktop only) -->
            <div class="col-lg-4 d-none d-lg-flex justify-content-end align-items-center">
              <!-- <ul class="nav" id="requestNav">
                <li class="nav-item">
                  <a class="nav-link fw-semibold" href="maintenance.php" data-tab="all">All Requests</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link fw-semibold" href="#" data-tab="saved">Saved</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link fw-semibold" href="#" data-tab="cancelled">Cancelled</a>
                </li>
              </ul> -->
            </div>
          </div>
          <div class="row ">
            <div class="col-md-6">
            </div>
            <div class="col-md-6 d-flex gap-1 flex-nowrap">

              <button type="button" id="availabilityBtn" class="btn seTAvailable text-white fw-bold"
                style="background: linear-gradient(135deg, #00192D, #002B5B); color:white; width:100%; white-space: nowrap;">
                Set Available
              </button>
              <button type="button" class="btn bg-danger text-white seTAvailable fw-bold"
                style="width:100%; white-space: nowrap;">
                Cancel Request
              </button>
              <button type="button" class="btn bg-danger text-white seTAvailable fw-bold"
                style="background: linear-gradient(135deg, #00192D, #002B5B); color:white; width:100%; white-space: nowrap;">
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
        <div class="container-fluid px-0 rounded-2 mb-2">
          <div class="row">
            <div class="col-md-4">
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

                <button class="btn btn-outline-warning btn-sm align-self-start" data-bs-toggle="modal" data-bs-target="#durationBudgetModal">
                  <i class="bi bi-pencil"></i>
                </button>

              </div>
            </div>

            <div class="col-md-4">
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
        </div>
        <div class="container-fluid rounded-2">
          <div class="row py-2 bg-white rounded-2">
            <div class="col-md-7 border-end" style="border-right: 1px solid #ccc; padding-top:0 !important; min-height: 100%; padding-top:0 !important;">
              <!-- content displays here -->
              <!-- Row 2: Category & Description -->
              <div class="card p-3 shadow-none border-0">
                <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                  <span style="background-color: #00192D; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="fa-solid fa-align-left" style="color: white; font-size: 16px;"></i>
                  </span>
                  <span style="font-weight: 600;">Description</span>
                </div>
                <div id="request-description" class="text-muted" style="margin-top: 6px; font-size: 15px; color: #333; line-height: 1.6;"></div>
              </div>

              <!-- Row 3: Photo -->
              <div class="card p-3 shadow-none border-0">
                <div style="display: flex; align-items: center; gap: 10px; color: #00192D;">
                  <span style="background-color: #00192D; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="fa-solid fa-image" style="color: white; font-size: 16px;"></i>
                  </span>
                  <span style="font-weight: 600;">Request Image</span>
                </div>
                <img id="request-photo" src="" alt="Photo" class="photo-preview w-100 rounded">
              </div>

            </div>
            <div class="col-md-5" style="max-height:500px; overflow:auto;">
              <div class="request-sidebar rounded-2">
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
              <p class="mb-0">
                <strong>Email:</strong> <span id="providerModalEmail" class="text-accent">jane.doe@email.com</span>
              </p>
              <p class="mb-0">
                <strong>Phone:</strong> <span id="providerModalPhone" class="text-accent">+254 700 123 456</span>
              </p>
            </div>
            <div class="ms-auto text-end">
              <h6 id="modalRate" class="text-accent mb-0">$25/hr</h6>
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
          <h5 class="modal-title" id="availabilityModalLabel" style="color: white;">Set Availability</h5>
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
              Confirm Availability
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

  <!-- control the requests list sidebar height -->
  <!-- <script>
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
  </script> -->

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