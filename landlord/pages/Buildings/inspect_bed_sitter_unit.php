<?php
 require_once "../db/connect.php";
//  include_once 'includes/lower_right_popup_form.php';
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
    <link rel="stylesheet" href="../../css/adminlte.css" />
    <!-- <link rel="stylesheet" href="text.css" /> -->
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->

    <!-- jquery link-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!--Tailwind CSS  -->
    <style>
        .app-wrapper {
            background-color: rgba(128, 128, 128, 0.1);
        }

        .modal-backdrop.show {
            opacity: 0.4 !important;
            /* Adjust the value as needed */
        }

        .diagonal-paid-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Centered and rotated */
            background-color: rgba(0, 128, 0, 0.2);
            /* Light green with transparency */
            color: green;
            font-weight: bold;
            font-size: 24px;
            padding: 15px 40px;
            border: 2px solid green;
            border-radius: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
        }

        .diagonal-unpaid-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Centered and rotated */
            background-color: rgba(255, 0, 0, 0.2);
            /* Red with transparency for "UNPAID" */
            color: #ff4d4d;
            /* Softer red text color */
            font-weight: bold;
            font-size: 24px;
            padding: 15px 40px;
            border: 2px solid red;
            border-radius: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
        }

        .diagonal-partially-paid-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Centered and rotated */
            background-color: rgba(255, 165, 0, 0.2);
            /* Amber background with opacity */
            color: #ff9900;
            /* Amber or gold text */
            font-weight: bold;
            font-size: 24px;
            padding: 15px 40px;
            border: 2px solid #ff9900;
            /* Amber border */
            border-radius: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
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
        <main class="app-main mt-4">
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
<div class="container-fluid">
                        <div class="card shadow" style="border:1px solid rbg(0,25,45,.2)">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label><i class="fa fa-home"></i> Unit No: CH-01</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label><i class="fa fa-building"></i> Building: Angela's Apartment</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label><i class="fa fa-table"></i> Floor Location: Second Floor</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label><i class="fa fa-bed"></i> Rental Purpose: Residence</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow" style="border:1px solid rbg(0,25,45,.2)">
                            <div class="card-header" style="color:#FFC107; background-color:#00192D;"><b>Inspect this Unit</b></div>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="card-body">
                                    <!-- Indicators Section Start Here -->
                                    <div class="row mt-2" style="justify-content:center; align-items:center;">
                                        <!-- Step One Doors, Windows and Wall Section -->
                                        <div class="col-md-2 text-center">
                                            <b class="shadow" style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:14px; padding-right:14px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;" id="stepOneIndicatorNo">1</b>
                                            <p class="mt-2" id="stepOneIndicatorText" style="font-size:14px; font-weight:bold;">Door Window &amp; Wall</p>
                                        </div>
                                        <!-- Step Two Sink Washroom Shower Details -->
                                        <div class="col-md-2 text-center">
                                            <b class="shadow" style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:14px; padding-right:14px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;" id="stepTwoIndicatorNo">2</b>
                                            <p class="mt-2" id="stepTwoIndicatorText" style="font-size:14px; font-weight:bold;">Sink Washroom &amp; Shower</p>
                                        </div>
                                        <!-- Step Three Kitchen, Wardrobe Information -->
                                        <div class="col-md-2 text-center">
                                            <b class="shadow" style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:14px; padding-right:14px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;" id="stepThreeIndicatorNo">3</b>
                                            <p class="mt-2" id="stepThreeIndicatorText" style="font-size:14px; font-weight:bold;">Kitchen &amp; Wardrobe</p>
                                        </div>
                                        <!-- Step 4 Faucet, Water Meter Sockets Information -->
                                        <div class="col-md-2 text-center">
                                            <b class="shadow" style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:14px; padding-right:14px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;" id="stepFourIndicatorNo">4</b>
                                            <p class="mt-2" id="stepFourIndicatorText" style="font-size:14px; font-weight:bold;">Faucet, Meter &amp; Sockets</p>
                                        </div>
                                        <!-- Step Five Lighting, Balcony and Ceiling Board  -->
                                        <div class="col-md-2 text-center">
                                            <b class="shadow" style="background-color:#00192D; color:#FFC107; border-radius:35px; padding-left:14px; padding-right:14px; padding-bottom:7px; padding-top:7px; font-size:1.5rem;" id="stepFiveIndicatorNo">5</b>
                                            <p class="mt-2" id="stepFiveIndicatorText" style="font-size:14px; font-weight:bold;">Lighting, Balcony &amp; Ceiling</p>
                                        </div>
                                    </div><hr>
                                    <!-- Indicators Section End Here -->
                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);" id="sectionOneCard">
                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Door, Window &amp; Wall Condition</b></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                            <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-door-open"></i> <b>Door(s) Condition</b></div>
                                                                <div class="card-body">
                                                                    <div class="row text-center">
                                                                        <div class="col-md-6">
                                                                            <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                                <div class="icheck-dark d-inline p-2">
                                                                                    <input type="radio" name="door_condition" id="doorGood" value="Good">
                                                                                    <label for="doorGood"> Good</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                                <div class="icheck-dark d-inline p-2">
                                                                                    <input type="radio" name="door_condition" id="doorBad" value="Faulty">
                                                                                    <label for="doorBad"> Faulty</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="doorBadDescription">
                                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                            <div class="card-body">
                                                                                <div class="form-group">
                                                                                    <label>Describe the Fault</label>
                                                                                    <textarea name="faulty_door" id="faulty_door" class="form-control"></textarea>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Attach Photo</label>
                                                                                    <input type="file" name="faulty_door_photo" id="faulty_door_photo" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                            <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-table"></i> <b>Window(s) Condition</b></div>
                                                            <div class="card-body">
                                                                <div class="row text-center">
                                                                    <div class="col-md-6">
                                                                        <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                            <div class="icheck-dark d-inline p-2">
                                                                                <input type="radio" name="window_condition" id="windowGood" value="Good">
                                                                                <label for="windowGood"> Good</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                            <div class="icheck-dark d-inline p-2">
                                                                                <input type="radio" name="window_condition" id="windowBad" value="Faulty">
                                                                                <label for="windowBad"> Faulty</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyWindowCard">
                                                                    <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                    <div class="card-body">
                                                                        <div class="form-group">
                                                                            <label>Describe the Fault</label>
                                                                            <textarea name="faulty_window_desc" id="faulty_window_desc" class="form-control"></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Attach Photo</label>
                                                                            <input type="file" class="form-control" name="faulty_window_photo" id="faulty_window_photo">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                            <div class="card-header" style="color:#FFC107; background-color:#00192D; border:1px solid rgb(0,25,45,.2);"><i class="fa fa-home"></i> <b>Wall Condition</b></div>
                                                            <div class="card-body">
                                                                <div class="row text-center">
                                                                    <div class="col-md-6">
                                                                        <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                            <div class="icheck-dark d-inline p-2">
                                                                                <input type="radio" name="wall_condition" id="wallGood" value="Good">
                                                                                <label for="wallGood"> Good</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                            <div class="icheck-dark d-inline p-2">
                                                                                <input type="radio" name="wall_condition" id="wallFaulty" value="Faulty">
                                                                                <label for="wallFaulty"> Faulty</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyWallCard">
                                                                    <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                    <div class="card-body">
                                                                        <div class="form-group">
                                                                            <label>Describe the Fault</label>
                                                                            <textarea name="faulty_wall_desc" id="faulty_wall_desc" class="form-control"></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Attach Photo</label>
                                                                            <input type="file" class="form-control" name="faulty_wall_photo" id="faulty_wall_photo">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-sm next-btn" id="sectionOneNextBtn">Next</button>
                                        </div>
                                    </div>
                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2); display:none;" id="sectionTwoCard">
                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Sink Washroom &amp; Shower</b></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-sink"></i> <b>Sink</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="sink_condition" id="sinkGood" value="Good">
                                                                            <label for="sinkGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="sink_condition" id="sinkFaulty" value="Faulty">
                                                                            <label for="sinkFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultySinkCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_sink_desc" id="faulty_sink_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_sink_photo" id="faulty_sink_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-toilet"></i> <b>Washroom</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="washroom_condition" id="washroomGood" value="Good">
                                                                            <label for="washroomGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="washroom_condition" id="washroomFaulty" value="Faulty">
                                                                            <label for="washroomFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyWashroomCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_washroom_desc" id="faulty_sink_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_washroom_photo" id="faulty_washroom_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-shower"></i> <b>Shower</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="shower_condition" id="showerGood" value="Good">
                                                                            <label for="showerGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="shower_condition" id="showerFaulty" value="Faulty">
                                                                            <label for="showerFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyShowerCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_shower_desc" id="faulty_shower_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_washroom_photo" id="faulty_washroom_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn" id="sectionTwoBackBtn">Back</button>
                                            <button type="button" class="btn btn-sm next-btn" id="sectionTwoNextBtn">Next</button>
                                        </div>
                                    </div>
                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2); display:none;" id="sectionThreeCard">
                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Kitchen &amp; Wardrobe</b></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-fire"></i> <b>Kitchen</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="kitchen_condition" id="kitchenGood" value="Good">
                                                                            <label for="kitchenGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="kitchen_condition" id="kitchenFaulty" value="Faulty">
                                                                            <label for="kitchenFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyKitchenCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_kitchen_desc" id="faulty_kitchen_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_kitchen_photo" id="faulty_kitchen_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-box"></i> <b>Kitchen Cabinets</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="cabinets_condition" id="cabinetsGood" value="Good">
                                                                            <label for="cabinetsGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="cabinets_condition" id="cabinetsFaulty" value="Faulty">
                                                                            <label for="cabinetsFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyKitchenCabinetsCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_kitchen_cabinet_desc" id="faulty_kitchen_cabinet_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_kitchen_cabinet_photo" id="faulty_kitchen_cabinet_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-building"></i> <b>Wardrobe</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="wardrobe_condition" id="wardrobeGood" value="Good">
                                                                            <label for="wardrobeGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="wardrobe_condition" id="wardrobeFaulty" value="Faulty">
                                                                            <label for="wardrobeFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="wardrobeFaultyCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_wardrobe_desc" id="faulty_wardrobe_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_wardrobe_photo" id="faulty_wardrobe_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn" id="sectionThreeBackBtn">Back</button>
                                            <button type="button" class="btn btn-sm next-btn" id="sectionThreeNextBtn">Next</button>
                                        </div>
                                    </div>
                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2); display:none;" id="sectionFourCard">
                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Faucet, Water-Meter &amp; Sockets</b></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-faucet"></i> <b>Faucet</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="faucet_condition" id="faucetGood" value="Good">
                                                                            <label for="faucetGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="faucet_condition" id="faucetFaulty" value="Faulty">
                                                                            <label for="faucetFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyFaucetCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_faucet_desc" id="faulty_faucet_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_faucet_photo" id="faulty_faucet_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fa fa-tachometer"></i> <b>Water Meter</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="water_meter_condition" id="waterMeterGood" value="Good">
                                                                            <label for="waterMeterGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="water_meter_condition" id="waterMeterFaulty" value="Faulty">
                                                                            <label for="waterMeterFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyWaterMeterCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_water_meter_desc" id="faulty_water_meter_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_water_meter_photo" id="faulty_water_meter_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-plug"></i> <b>Sockets</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="socket_condition" id="socketGood" value="Good">
                                                                            <label for="socketGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="socket_condition" id="socketFaulty" value="Faulty">
                                                                            <label for="socketFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultySocketsCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_socket_desc" id="faulty_socket_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_socket_photo" id="faulty_socket_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn" id="sectionFourBackBtn">Back</button>
                                            <button type="button" class="btn btn-sm next-btn" id="sectionFourNextBtn">Next</button>
                                        </div>
                                    </div>
                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2); display:none;" id="sectionFiveCard">
                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>Lighting, Balcony &amp; Ceiling</b></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-bell"></i> <b>Bulb Holder</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="bulb_holder_condition" id="bulbHolderGood" value="Good">
                                                                            <label for="bulbHolderGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="bulb_holder_condition" id="bulbHolderFaulty" value="Faulty">
                                                                            <label for="bulbHolderFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyBulbHolderCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_bulb_holder_desc" id="faulty_bulb_holder_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_bulb_holder_photo" id="faulty_bulb_holder_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-bank"></i> <b>Balcony (Verrander)</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="balcony_condition" id="baclonyGood" value="Good">
                                                                            <label for="baclonyGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="balcony_condition" id="baclonyFaulty" value="Faulty">
                                                                            <label for="baclonyFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyBalconyCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_sink_desc" id="faulty_balcony_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_balcony_photo" id="faulty_sink_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card shadow" style="border:1px solid rgb(0,25,45,.2);">
                                                        <div class="card-header" style="background-color:#00192D; color:#FFC107;"><i class="fas fa-square"></i> <b>Ceiling Board</b></div>
                                                        <div class="card-body">
                                                            <div class="row text-center">
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-thumbs-up" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="Ceiling_board_condition" id="CeilingBoardGood" value="Good">
                                                                            <label for="CeilingBoardGood"> Good</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card shadow p-2" style="border:1px solid rgb(0,25,45,.2);"> <i class="fa fa-wrench" style="font-size:30px;"></i>
                                                                        <div class="icheck-dark d-inline p-2">
                                                                            <input type="radio" name="Ceiling_board_condition" id="CeilingBoardFaulty" value="Faulty">
                                                                            <label for="CeilingBoardFaulty"> Faulty</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card shadow" style="display:none; border:1px solid rgb(0,25,45,.2);" id="faultyCeilingBoardCard">
                                                                <div class="card-header" style="background-color:#00192D; color:#FFC107;"><b>More Information</b></div>
                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label>Describe the Fault</label>
                                                                        <textarea name="faulty_Ceiling_desc" id="faulty_Ceiling_desc" class="form-control"></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Attach Photo</label>
                                                                        <input type="file" class="form-control" name="faulty_Ceiling_photo" id="faulty_Ceiling_photo">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="button" class="btn btn-danger btn-sm back-btn" id="sectionFiveBackBtn">Back</button>
                                            <button type="submit" class="btn btn-sm next-btn" id="sectionFiveNextBtn">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
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
                <a href="https://adminlte.io" class="text-decoration-none" style="color: #00192D;">JENGO PAY</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>

    </div>
    <!--end::App Wrapper-->

    <!-- plugin for pdf -->
    <!-- Main Js File -->
    <script src="../../js/adminlte.js"></script>
    <script src="js/main.js"></script>
    <!-- html2pdf depends on html2canvas and jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script type="module" src="./js/main.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <!-- pdf download plugin -->
<script>
            document.getElementById('doorBad').addEventListener('click', function () {
                document.getElementById('doorBadDescription').style.display = 'block';
            });
            document.getElementById('doorGood').addEventListener('click', function () {
                doorGood
                document.getElementById('doorBadDescription').style.display = 'none';
            });
            document.getElementById('windowBad').addEventListener('click', function () {
                document.getElementById('faultyWindowCard').style.display = 'block';
            });
            document.getElementById('windowGood').addEventListener('click', function () {
                document.getElementById('faultyWindowCard').style.display = 'none';
            });
            document.getElementById('wallFaulty').addEventListener('click', function () {
                wallGood
                document.getElementById('faultyWallCard').style.display = 'block';
            });
            document.getElementById('wallGood').addEventListener('click', function () {
                document.getElementById('faultyWallCard').style.display = 'none';
            });
            document.getElementById('sinkFaulty').addEventListener('click', function () {
                document.getElementById('faultySinkCard').style.display = 'block';
            });
            document.getElementById('sinkGood').addEventListener('click', function () {
                document.getElementById('faultySinkCard').style.display = 'none';
            });
            document.getElementById('washroomFaulty').addEventListener('click', function () {
                document.getElementById('faultyWashroomCard').style.display = 'block';
            });
            document.getElementById('washroomGood').addEventListener('click', function () {
                document.getElementById('faultyWashroomCard').style.display = 'none';
            });
            document.getElementById('showerFaulty').addEventListener('click', function () {
            document.getElementById('faultyShowerCard').style.display = 'block';
            });
            document.getElementById('showerGood').addEventListener('click', function () {
                document.getElementById('faultyShowerCard').style.display = 'none';
            });
            document.getElementById('kitchenGood').addEventListener('click', function () {
                document.getElementById('faultyKitchenCard').style.display = 'none';
            });
            document.getElementById('kitchenFaulty').addEventListener('click', function () {
                document.getElementById('faultyKitchenCard').style.display = 'block';
            });
            document.getElementById('cabinetsGood').addEventListener('click', function () {
                document.getElementById('faultyKitchenCabinetsCard').style.display = 'none';
            });
            document.getElementById('cabinetsFaulty').addEventListener('click', function () {
                document.getElementById('faultyKitchenCabinetsCard').style.display = 'block';
            });
            document.getElementById('wardrobeGood').addEventListener('click', function () {
                document.getElementById('wardrobeFaultyCard').style.display = 'none';
            });
            document.getElementById('wardrobeFaulty').addEventListener('click', function () {
                document.getElementById('wardrobeFaultyCard').style.display = 'block';
            });
            document.getElementById('faucetGood').addEventListener('click', function () {
                document.getElementById('faultyFaucetCard').style.display = 'none';
            });
            document.getElementById('faucetFaulty').addEventListener('click', function () {
                document.getElementById('faultyFaucetCard').style.display = 'block';
            });
            document.getElementById('waterMeterGood').addEventListener('click', function () {
                document.getElementById('faultyWaterMeterCard').style.display = 'none';
            });
            document.getElementById('waterMeterFaulty').addEventListener('click', function () {
                document.getElementById('faultyWaterMeterCard').style.display = 'block';
            });
            document.getElementById('socketGood').addEventListener('click', function () {
                document.getElementById('faultySocketsCard').style.display = 'none';
            });
            document.getElementById('socketFaulty').addEventListener('click', function () {
                document.getElementById('faultySocketsCard').style.display = 'block';
            });
            document.getElementById('bulbHolderGood').addEventListener('click', function () {
                document.getElementById('faultyBulbHolderCard').style.display = 'none';
            });
            document.getElementById('bulbHolderFaulty').addEventListener('click', function () {
                document.getElementById('faultyBulbHolderCard').style.display = 'block';
            });
            document.getElementById('baclonyGood').addEventListener('click', function () {
                document.getElementById('faultyBalconyCard').style.display = 'none';
            });
            document.getElementById('baclonyFaulty').addEventListener('click', function () {
                document.getElementById('faultyBalconyCard').style.display = 'block';
            });
            document.getElementById('CeilingBoardGood').addEventListener('click', function () {
                document.getElementById('faultyCeilingBoardCard').style.display = 'none';
            });
            document.getElementById('CeilingBoardFaulty').addEventListener('click', function () {
                document.getElementById('faultyCeilingBoardCard').style.display = 'block';
            });
            //DOM for step by step inspection of the bed sitter unit
            $(document).ready(function () {
                $('#sectionOneNextBtn').click(function (e) {
                    e.preventDefault();
                    $("#sectionOneCard").hide();
                    $("#sectionTwoCard").show();
                });
                $("#sectionTwoNextBtn").click(function (e) {
                    e.preventDefault();
                    $("#sectionThreeCard").show();
                    $("#sectionTwoCard").hide();
                });
                $("#sectionThreeNextBtn").click(function (e) {
                    e.preventDefault();
                    $("#sectionThreeCard").hide();
                    $("#sectionFourCard").show();
                });
                $("#sectionFourNextBtn").click(function (e) {
                    e.preventDefault();
                    $("#sectionFiveCard").show();
                    $("#sectionFourCard").hide();
                    $("#stepFourIndicatorNo").css('background-color', '#FFC107');
                    $("#stepFourIndicatorNo").css('color', '#00192D');
                    $("#stepFourIndicatorNo").html('<i class="fa fa-check"></i>');
                    $("#stepFourIndicatorText").html('Done');
                });
                //Back Buttons
                $("#sectionTwoBackBtn").click(function (e) {
                    e.preventDefault();
                    $("#sectionOneCard").show();
                    $("#sectionTwoCard").hide();
                    $("#stepOneIndicatorNo").css('background-color', '#00192D');
                    $("#stepOneIndicatorNo").css('color', '#FFC107');
                    $("#stepOneIndicatorNo").html('1');
                    $("#stepOneIndicatorText").html('Door Window &amp; Wall');
                });
                $("#sectionThreeBackBtn").click(function (e) {
                    e.preventDefault();
                    $("#sectionThreeCard").hide();
                    $("#sectionTwoCard").show();
                    $("#stepTwoIndicatorNo").css('background-color', '#00192D');
                    $("#stepTwoIndicatorNo").css('color', '#FFC107');
                    $("#stepTwoIndicatorNo").html('2');
                    $("#stepTwoIndicatorText").html('Sink Washroom &amp; Shower');
                });
                $("#sectionFourBackBtn").click(function (e) {
                    e.preventDefault();
                    $("#sectionThreeCard").show();
                    $("#sectionFourCard").hide();
                    $("#stepThreeIndicatorNo").css('background-color', '#00192D');
                    $("#stepThreeIndicatorNo").css('color', '#FFC107');
                    $("#stepThreeIndicatorNo").html('3');
                    $("#stepThreeIndicatorText").html('Kitchen &amp; Wardrobe');
                });
                $("#sectionFiveBackBtn").click(function (e) {
                    e.preventDefault();
                    $("#sectionFiveCard").hide();
                    $("#sectionFourCard").show();
                    $("#stepFourIndicatorNo").css('background-color', '#00192D');
                    $("#stepFourIndicatorNo").css('color', '#FFC107');
                    $("#stepFourIndicatorNo").html('4');
                    $("#stepFourIndicatorText").html('Faucet, Meter &amp; Sockets');
                });
            });
        </script>

    <!-- Scripts -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<!--end::Body-->

</html>