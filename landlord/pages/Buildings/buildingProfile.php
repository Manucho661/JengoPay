<?php
session_start();
require_once "../db/connect.php";
// 
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);
?>
<?php
// actions
require_once "./actions/getBuildingProfileDetails.php";

?>
<?php
// Building data


// Stats
$stats = [
    'monthly_revenue' => 2850000,
    'active_tenants' => 38,
    'submitted_requests' => 12,
    'occupied_units' => 38,
    'vacant_units' => 7,
    'occupancy_rate' => 84.4
];

// Units
$units = [
    ['id' => 'U-101', 'floor' => 1, 'number' => '101', 'type' => '2 Bedroom', 'size' => '85 sqm', 'rent' => 65000, 'status' => 'Occupied', 'tenant' => 'Sarah Johnson', 'lease_end' => '2024-08-15'],
    ['id' => 'U-102', 'floor' => 1, 'number' => '102', 'type' => '1 Bedroom', 'size' => '55 sqm', 'rent' => 45000, 'status' => 'Occupied', 'tenant' => 'Michael Chen', 'lease_end' => '2024-10-20'],
    ['id' => 'U-103', 'floor' => 1, 'number' => '103', 'type' => '2 Bedroom', 'size' => '85 sqm', 'rent' => 65000, 'status' => 'Vacant', 'tenant' => null, 'lease_end' => null],
    ['id' => 'U-201', 'floor' => 2, 'number' => '201', 'type' => '3 Bedroom', 'size' => '120 sqm', 'rent' => 95000, 'status' => 'Occupied', 'tenant' => 'Emma Wilson', 'lease_end' => '2024-12-01'],
    ['id' => 'U-202', 'floor' => 2, 'number' => '202', 'type' => '2 Bedroom', 'size' => '85 sqm', 'rent' => 65000, 'status' => 'Occupied', 'tenant' => 'James Anderson', 'lease_end' => '2024-09-15'],
    ['id' => 'U-203', 'floor' => 2, 'number' => '203', 'type' => '1 Bedroom', 'size' => '55 sqm', 'rent' => 45000, 'status' => 'Vacant', 'tenant' => null, 'lease_end' => null],
    ['id' => 'U-301', 'floor' => 3, 'number' => '301', 'type' => '2 Bedroom', 'size' => '85 sqm', 'rent' => 65000, 'status' => 'Occupied', 'tenant' => 'Lisa Brown', 'lease_end' => '2024-07-30'],
    ['id' => 'U-302', 'floor' => 3, 'number' => '302', 'type' => '3 Bedroom', 'size' => '120 sqm', 'rent' => 95000, 'status' => 'Occupied', 'tenant' => 'Robert Taylor', 'lease_end' => '2024-11-10'],
];

// Maintenance Requests
$requests = [
    ['id' => 'REQ-045', 'unit' => 'Unit 101', 'tenant' => 'Sarah Johnson', 'issue' => 'AC not cooling properly', 'priority' => 'High', 'status' => 'In Progress', 'date' => '2024-02-08'],
    ['id' => 'REQ-043', 'unit' => 'Unit 201', 'tenant' => 'Emma Wilson', 'issue' => 'Leaking faucet in kitchen', 'priority' => 'Medium', 'status' => 'Pending', 'date' => '2024-02-07'],
    ['id' => 'REQ-041', 'unit' => 'Unit 302', 'tenant' => 'Robert Taylor', 'issue' => 'Broken window lock', 'priority' => 'Low', 'status' => 'Pending', 'date' => '2024-02-06'],
];

// Financial Summary (Last 6 months)
$financialData = [
    ['month' => 'Sep 2023', 'revenue' => 2650000, 'expenses' => 450000],
    ['month' => 'Oct 2023', 'revenue' => 2750000, 'expenses' => 520000],
    ['month' => 'Nov 2023', 'revenue' => 2800000, 'expenses' => 480000],
    ['month' => 'Dec 2023', 'revenue' => 2850000, 'expenses' => 550000],
    ['month' => 'Jan 2024', 'revenue' => 2850000, 'expenses' => 490000],
    ['month' => 'Feb 2024', 'revenue' => 2850000, 'expenses' => 510000],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $building['building_name'] ?> - Building Profile</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../landlord/assets/main.css" />

    <style>
        :root {
            --sidebar-width: 260px;
            --main-color: #00192D;
            --accent-color: #FFC107;
            --white-color: #FFFFFF;
            --light-bg: #f8f9fa;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--main-color);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 193, 7, 0.2);
        }

        .sidebar-header h2 {
            font-size: 24px;
            margin-bottom: 5px;
            color: var(--accent-color);
        }

        .sidebar-header p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .menu-item {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            color: var(--white-color);
            text-decoration: none;
            transition: all 0.3s;
        }

        .menu-item:hover,
        .menu-item.active {
            background: rgba(255, 193, 7, 0.1);
            border-left: 4px solid var(--accent-color);
            color: var(--accent-color);
        }

        .menu-item i {
            margin-right: 15px;
            width: 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }


        /* Building Header */
        .building-header {
            background: white;
            padding: 30px;
            border-radius: 10px;

            margin-bottom: 25px;
        }

        .building-name {
            color: var(--main-color);
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .building-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            color: #666;
            font-size: 14px;
        }

        .building-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .building-meta-item i {
            color: var(--accent-color);
        }

        /* Image Gallery */
        .image-gallery {
            background: white;
            padding: 25px;
            border-radius: 10px;

            margin-bottom: 25px;
        }

        .gallery-main {
            width: 100%;
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .gallery-main img:hover {
            transform: scale(1.05);
        }

        .gallery-thumbnails {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }

        .gallery-thumb {
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s;
        }

        .gallery-thumb.active {
            border-color: var(--accent-color);
        }

        .gallery-thumb:hover {
            border-color: var(--accent-color);
            opacity: 0.8;
        }

        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;

            transition: all 0.3s;
            border-left: 4px solid var(--accent-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 25, 45, 0.15);
        }

        .stat-card h6 {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stat-card .value {
            font-size: 28px;
            font-weight: bold;
            color: var(--main-color);
        }

        .stat-card .icon {
            font-size: 28px;
            color: var(--accent-color);
        }

        /* Buttons */
        .btn-primary {
            background: var(--main-color);
            border-color: var(--main-color);
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background: #001a3a;
            border-color: #001a3a;
        }

        .btn-success {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--main-color);
            font-weight: 600;
        }

        .btn-success:hover,
        .btn-success:focus,
        .btn-success:active {
            background: #e6ad06;
            border-color: #e6ad06;
            color: var(--main-color);
        }

        .btn:focus,
        .btn:active {
            box-shadow: none;
            outline: none;
        }

        /* Card */
        .card {
            border: none;

        }

        .card-title {
            color: var(--main-color);
            font-weight: 600;
        }

        /* Form Controls */
        .form-select,
        .form-control {
            background: rgba(255, 193, 7, 0.05);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: none;
            outline: none;
            background: rgba(255, 193, 7, 0.1);
        }

        /* Table */
        .table thead th {
            background: var(--main-color);
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: var(--main-color);
        }

        .table-hover tbody tr:hover {
            background: rgba(255, 193, 7, 0.05);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-occupied {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .badge-vacant {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }

        .badge-high {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }

        .badge-medium {
            background: rgba(243, 156, 18, 0.15);
            color: #f39c12;
        }

        .badge-low {
            background: rgba(52, 152, 219, 0.15);
            color: #3498db;
        }

        .badge-pending {
            background: rgba(243, 156, 18, 0.15);
            color: #f39c12;
        }

        .badge-inprogress {
            background: rgba(52, 152, 219, 0.15);
            color: #3498db;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            border: none;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            margin: 0 2px;
        }

        .action-btn.view-btn {
            background: rgba(0, 25, 45, 0.1);
            color: var(--main-color);
        }

        .action-btn.view-btn:hover {
            background: var(--main-color);
            color: white;
        }

        .action-btn.edit-btn {
            background: rgba(255, 193, 7, 0.2);
            color: #d39e00;
        }

        .action-btn.edit-btn:hover {
            background: var(--accent-color);
            color: var(--main-color);
        }

        .action-btn.delete-btn {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        .action-btn.delete-btn:hover {
            background: var(--danger-color);
            color: white;
        }

        /* Amenities */
        .amenities-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .amenity-tag {
            background: rgba(255, 193, 7, 0.1);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 13px;
            color: var(--main-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .amenity-tag i {
            color: var(--accent-color);
        }

        /* Chart */
        .chart-container {
            height: 300px;
            padding: 20px;
        }

        /* Footer */
        .custom-footer {
            background: var(--main-color);
            color: white;
            padding: 30px;
            margin-top: auto;
        }

        .custom-footer a {
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .custom-footer a:hover {
            color: white;
        }

        /* Modal */
        .modal-header {
            background: var(--main-color);
            color: white;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .modal-title {
            color: white;
        }

        a {
            text-decoration: none !important;
        }
    </style>
</head>

<body>
    <div class="app-wrapper">
        <!--begin::Header-->
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php';
        ?>
        <!--end::Header-->

        <!--begin::Sidebar-->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?>
        <!--end::Sidebar-->
        <!-- Main Content -->
        <div class="main">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="">
                    <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Dashboard/dashboard.php" style="text-decoration: none;">Home</a></li>
                    <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/buildings/buildings.php" style="text-decoration: none;">Buildings</a></li>
                    <li class="breadcrumb-item active">Building Profile</li>
                </ol>
            </nav>

            <div class="container-fluid">
                <!--First Row-->
                <div class="row align-items-center mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <span style="width:5px;height:28px;background:#F5C518;" class="rounded"></span>
                        <h3 class="mb-0 ms-3"><?= $building['building_name'] ?></h3>
                    </div>
                </div>
                <!-- Content -->
                <main class="flex-grow-1">
                    <!-- Building Header -->
                    <div class="building-header">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <div class="building-meta">
                                    <div class="building-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?= $building['county_name'] ?> </span> (<span class="warning"><?= $building['constituency_name'] ?></span>)
                                    </div>
                                    <div class="building-meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>Registered: <?= date('M d, Y', strtotime($building['created_at'])) ?></span>
                                    </div>
                                    <div class="building-meta-item">
                                        <i class="fas fa-layer-group"></i>
                                        <span>2 Floors</span>
                                    </div>
                                    <div class="building-meta-item">
                                        <i class="fas fa-door-open"></i>
                                        <span><?= $totalUnits ?> Total Units</span>
                                    </div>
                                    <div class="building-meta-item">
                                        <i class="fas fa-key"></i>
                                        <span><?= $building['ownership_mode'] ?></span>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0" style="color: #666;"> Modern residential tower located in the heart of <?= $building['county_name'] ?>, featuring premium amenities and stunning city views.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="actionBtn">
                                    <i class="fas fa-edit"></i> Edit Building
                                </button>
                                <button class="actionBtn">
                                    <i class="fas fa-image"></i> Manage Photos
                                </button>
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div class="mt-4">
                            <h6 style="color: var(--main-color); font-weight: 600; margin-bottom: 15px;">
                                <i class="fas fa-star"></i> Amenities
                            </h6>
                            <?php
                            $amenities = json_decode($building['utilities'], true) ?? [];
                            $amenities = array_slice($amenities, 0, 7);
                            ?>

                            <div class="amenities-list">
                                <?php foreach ($amenities as $amenity): ?>
                                    <div class="amenity-tag">
                                        <i class="fas fa-check"></i>
                                        <span><?= htmlspecialchars($amenity) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-8">
                            <!-- Image Gallery -->
                            <div class="image-gallery">
                                <h5 class="card-title mb-3"><i class="fas fa-images"></i> Photo Gallery</h5>
                                <div class="gallery-main" id="mainImage">
                                    <img src="<?= $building['images'][0] ?>" alt="Building">
                                </div>
                                <div class="gallery-thumbnails">
                                    <?php foreach ($building['images'] as $index => $image): ?>
                                        <div class="gallery-thumb <?= $index === 0 ? 'active' : '' ?>" onclick="changeMainImage('<?= $image ?>', this)">
                                            <img src="<?= $image ?>" alt="Thumbnail">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Units Table -->
                            <div class="card mb-4">
                                <div class="card-body p-0">
                                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                        <h5 class="card-title mb-0"><i class="fas fa-door-open"></i> Units (<?= count($units) ?>)</h5>
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUnitModal">
                                            <i class="fas fa-plus"></i> Add Unit
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Floor</th>
                                                    <th>Type</th>
                                                    <th>Rent</th>
                                                    <th>Status</th>
                                                    <th>Tenant</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($units as $unit): ?>
                                                    <tr>
                                                        <td><strong><?= $unit['number'] ?></strong></td>
                                                        <td><?= $unit['floor'] ?></td>
                                                        <td><?= $unit['type'] ?></td>
                                                        <td>KES <?= number_format($unit['rent']) ?></td>
                                                        <td>
                                                            <span class="status-badge badge-<?= strtolower($unit['status']) ?>">
                                                                <?= $unit['status'] ?>
                                                            </span>
                                                        </td>
                                                        <td><?= $unit['tenant'] ?? '-' ?></td>
                                                        <td>
                                                            <button class="action-btn view-btn mb-1" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="action-btn edit-btn" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Maintenance Requests -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3"><i class="fas fa-tools"></i> Recent Maintenance Requests</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Unit</th>
                                                    <th>Title</th>
                                                    <th>Provider</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($requests as $request): ?>
                                                    <tr>
                                                        <td><strong><?= $request['id'] ?></strong></td>
                                                        <td><?= $request['unit'] ?></td>
                                                        <td><?= $request['issue'] ?></td>
                                                        <td>
                                                            <span class="status-badge badge-<?= strtolower($request['priority']) ?>">
                                                                <?= $request['priority'] ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="status-badge badge-<?= strtolower(str_replace(' ', '', $request['status'])) ?>">
                                                                <?= $request['status'] ?>
                                                            </span>
                                                        </td>
                                                        <td><?= date('M d, Y', strtotime($request['date'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-4">
                            <!-- Stats -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <div class="stat-card">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6>Monthly Revenue</h6>
                                                <div class="value">KES <?= number_format($stats['monthly_revenue']) ?></div>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-dollar-sign"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="stat-card">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6>Active Tenants</h6>
                                                <div class="value"><?= $stats['active_tenants'] ?></div>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="stat-card">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6>Maintenance Requests</h6>
                                                <div class="value"><?= $stats['submitted_requests'] ?></div>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-wrench"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="stat-card">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6>Occupancy Rate</h6>
                                                <div class="value"><?= $stats['occupancy_rate'] ?>%</div>
                                                <small style="color: #666;"><?= $stats['occupied_units'] ?> / <?= $building['total_units'] ?> units</small>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-chart-pie"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Financial Summary -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3"><i class="fas fa-chart-line"></i> Financial Trend (6 Months)</h5>
                                    <div class="chart-container">
                                        <canvas id="financialChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card ">
                                <div class="card-body">
                                    <h5 class="card-title mb-3"><i class="fas fa-bolt"></i> Quick Actions</h5>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-primary">
                                            <i class="fas fa-file-invoice"></i> Generate Report
                                        </button>
                                        <button class="btn btn-outline-primary">
                                            <i class="fas fa-envelope"></i> Message All Tenants
                                        </button>
                                        <button class="btn btn-outline-primary">
                                            <i class="fas fa-clipboard-list"></i> Schedule Inspection
                                        </button>
                                        <button class="btn btn-outline-primary">
                                            <i class="fas fa-download"></i> Export Unit Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <!--begin::Footer-->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
        <!-- end footer -->
    </div>

    <!-- Add Unit Modal -->
    <div class="modal fade" id="addUnitModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Add New Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Unit Number *</label>
                                <input type="text" name="unit_number" class="form-control" placeholder="e.g., 401" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Floor *</label>
                                <input type="number" name="floor" class="form-control" placeholder="e.g., 4" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Unit Type *</label>
                                <select name="unit_type" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="bedsitter">Bedsitter</option>
                                    <option value="1bedroom">1 Bedroom</option>
                                    <option value="2bedroom">2 Bedroom</option>
                                    <option value="3bedroom">3 Bedroom</option>
                                    <option value="penthouse">Penthouse</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Size (sqm) *</label>
                                <input type="number" name="size" class="form-control" placeholder="e.g., 85" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Monthly Rent (KES) *</label>
                                <input type="number" name="rent" class="form-control" placeholder="e.g., 65000" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="vacant">Vacant</option>
                                    <option value="occupied">Occupied</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_unit" class="btn btn-success">
                            <i class="fas fa-save"></i> Add Unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../../landlord/assets/main.js"></script>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Change main image in gallery
        function changeMainImage(imageSrc, element) {
            document.querySelector('#mainImage img').src = imageSrc;

            // Update active thumbnail
            document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                thumb.classList.remove('active');
            });
            element.classList.add('active');
        }

        // Financial Chart
        const ctx = document.getElementById('financialChart').getContext('2d');
        const financialData = <?= json_encode($financialData) ?>;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: financialData.map(d => d.month),
                datasets: [{
                    label: 'Revenue',
                    data: financialData.map(d => d.revenue),
                    borderColor: '#FFC107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Expenses',
                    data: financialData.map(d => d.expenses),
                    borderColor: '#00192D',
                    backgroundColor: 'rgba(0, 25, 45, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'KES ' + (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>