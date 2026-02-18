<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/jengopay/auth/auth_check.php';

require_once "../db/connect.php";

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);
?>

<?php
// Unit data
$unit = [
    'id' => 'U-101',
    'building' => [
        'id' => 'BLD-2024-001',
        'name' => 'Hindocha Tower',
        'address' => 'Westlands Avenue, Nairobi'
    ],
    'unit_number' => '101',
    'floor' => 1,
    'type' => '2 Bedroom',
    'size' => '85 sqm',
    'bedrooms' => 2,
    'bathrooms' => 2,
    'monthly_rent' => 65000,
    'status' => 'Occupied',
    'amenities' => ['Balcony', 'Parking Space', 'En-suite Bathrooms', 'Built-in Wardrobes'],
    'registration_date' => '2023-05-15',
    'last_renovation' => '2024-01-10'
];

// Revenue Stats
$revenueStats = [
    'total_revenue' => 780000,
    'months_collected' => 12,
    'current_month' => 65000,
    'arrears' => 130000,
    'collection_rate' => 85.7
];

// Active Tenancy
$activeTenant = [
    'name' => 'Sarah Johnson',
    'email' => 'sarah.j@email.com',
    'phone' => '+254 712 345 678',
    'move_in_date' => '2023-03-15',
    'lease_start' => '2023-03-15',
    'lease_end' => '2024-08-15',
    'rent_amount' => 65000,
    'security_deposit' => 130000,
    'rent_arrears' => 130000,
    'months_owed' => 2,
    'payment_status' => 'Overdue',
    'next_payment_due' => '2024-02-01'
];

// Previous Tenancies
$previousTenants = [
    [
        'tenant_name' => 'David Martinez',
        'move_in' => '2021-06-01',
        'move_out' => '2023-02-28',
        'duration' => '1 year 9 months',
        'final_rent' => 60000,
        'reason_for_leaving' => 'Voluntary Exit',
        'deposit_refunded' => 120000,
        'arrears_at_exit' => 0
    ],
    [
        'tenant_name' => 'Amanda Foster',
        'move_in' => '2020-01-15',
        'move_out' => '2021-05-30',
        'duration' => '1 year 4 months',
        'final_rent' => 55000,
        'reason_for_leaving' => 'Lease Expired',
        'deposit_refunded' => 110000,
        'arrears_at_exit' => 0
    ],
    [
        'tenant_name' => 'Peter Kim',
        'move_in' => '2018-08-01',
        'move_out' => '2019-12-31',
        'duration' => '1 year 5 months',
        'final_rent' => 50000,
        'reason_for_leaving' => 'Relocated',
        'deposit_refunded' => 100000,
        'arrears_at_exit' => 50000
    ]
];

// Maintenance Requests
$maintenanceRequests = [
    [
        'id' => 'REQ-045',
        'date' => '2024-02-08',
        'issue' => 'AC not cooling properly',
        'priority' => 'High',
        'status' => 'In Progress',
        'reported_by' => 'Sarah Johnson',
        'assigned_to' => 'ABC Maintenance Co.',
        'cost' => 8500
    ],
    [
        'id' => 'REQ-038',
        'date' => '2024-01-15',
        'issue' => 'Kitchen sink faucet leaking',
        'priority' => 'Medium',
        'status' => 'Completed',
        'reported_by' => 'Sarah Johnson',
        'assigned_to' => 'Plumbing Pro Services',
        'cost' => 4200
    ],
    [
        'id' => 'REQ-031',
        'date' => '2023-12-10',
        'issue' => 'Bedroom door handle broken',
        'priority' => 'Low',
        'status' => 'Completed',
        'reported_by' => 'Sarah Johnson',
        'assigned_to' => 'Handyman Services',
        'cost' => 1500
    ]
];

// Revenue History (Last 6 months)
$revenueHistory = [
    ['month' => 'Sep 2023', 'amount' => 65000, 'status' => 'Paid'],
    ['month' => 'Oct 2023', 'amount' => 65000, 'status' => 'Paid'],
    ['month' => 'Nov 2023', 'amount' => 65000, 'status' => 'Paid'],
    ['month' => 'Dec 2023', 'amount' => 65000, 'status' => 'Paid'],
    ['month' => 'Jan 2024', 'amount' => 65000, 'status' => 'Overdue'],
    ['month' => 'Feb 2024', 'amount' => 65000, 'status' => 'Overdue']
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit <?= $unit['unit_number'] ?> - <?= $unit['building']['name'] ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../../assets/main.css" />
    <!-- <link rel="stylesheet" href="text.css" /> -->
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

        /* Custom Header */
        .custom-header {
            background: var(--main-color);
            padding: 20px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .custom-header h1 {
            color: var(--white-color);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--main-color);
            font-weight: bold;
        }

        .custom-header .fw-bold {
            color: var(--white-color);
        }

        .custom-header .text-muted {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        /* Breadcrumb */
        .breadcrumb-custom {
            background: white;
            padding: 15px 30px;
            margin: 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .breadcrumb-custom a {
            color: var(--main-color);
            text-decoration: none;
        }

        .breadcrumb-custom a:hover {
            color: var(--accent-color);
        }

        .breadcrumb-custom .active {
            color: var(--accent-color);
            font-weight: 600;
        }

        /* Unit Header */
        .unit-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 25, 45, 0.08);
            margin-bottom: 25px;
        }

        .unit-number {
            font-size: 42px;
            font-weight: bold;
            color: var(--main-color);
        }

        .unit-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            color: #666;
            font-size: 14px;
            margin-top: 15px;
        }

        .unit-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .unit-meta-item i {
            color: var(--accent-color);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
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

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 25, 45, 0.08);
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

        .btn-danger {
            background: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn:focus,
        .btn:active {
            box-shadow: none;
            outline: none;
        }

        /* Card */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 25, 45, 0.08);
        }

        .card-title {
            color: var(--main-color);
            font-weight: 600;
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

        .badge-completed {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .badge-inprogress {
            background: rgba(52, 152, 219, 0.15);
            color: #3498db;
        }

        .badge-paid {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .badge-overdue {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }

        /* Tenant Card */
        .tenant-card {
            background: rgba(255, 193, 7, 0.1);
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid var(--accent-color);
        }

        .tenant-name {
            font-size: 24px;
            font-weight: bold;
            color: var(--main-color);
            margin-bottom: 15px;
        }

        .tenant-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #666;
        }

        .tenant-detail i {
            color: var(--accent-color);
            width: 20px;
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
    </style>
</head>

<body>
    <!--begin::Header-->
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php';
    ?>
    <!--end::Header-->

    <!--begin::Sidebar-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/sidebar.php'; ?>
    <!--end::Sidebar-->

    <!-- Main Content -->
    <div class="main-content">

        <!-- Breadcrumb -->
        <div class="breadcrumb-custom">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Units</a></li>
                    <li class="breadcrumb-item"><a href="#"><?= $unit['building']['name'] ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Unit <?= $unit['unit_number'] ?></li>
                </ol>
            </nav>
        </div>

        <!-- Content -->
        <main class="p-4 flex-grow-1">
            <!-- Unit Header -->
            <div class="unit-header">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="unit-number">Unit <?= $unit['unit_number'] ?></div>
                            <span class="status-badge badge-<?= strtolower($unit['status']) ?>">
                                <?= $unit['status'] ?>
                            </span>
                        </div>
                        <h5 class="mt-2" style="color: #666;"><?= $unit['building']['name'] ?></h5>
                        <div class="unit-meta">
                            <div class="unit-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= $unit['building']['address'] ?></span>
                            </div>
                            <div class="unit-meta-item">
                                <i class="fas fa-layer-group"></i>
                                <span>Floor <?= $unit['floor'] ?></span>
                            </div>
                            <div class="unit-meta-item">
                                <i class="fas fa-home"></i>
                                <span><?= $unit['type'] ?></span>
                            </div>
                            <div class="unit-meta-item">
                                <i class="fas fa-ruler-combined"></i>
                                <span><?= $unit['size'] ?></span>
                            </div>
                            <div class="unit-meta-item">
                                <i class="fas fa-bed"></i>
                                <span><?= $unit['bedrooms'] ?> Bedrooms</span>
                            </div>
                            <div class="unit-meta-item">
                                <i class="fas fa-bath"></i>
                                <span><?= $unit['bathrooms'] ?> Bathrooms</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <strong style="font-size: 24px; color: var(--main-color);">
                                KES <?= number_format($unit['monthly_rent']) ?>
                            </strong>
                            <span style="color: #666;"> / month</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit Unit
                        </button>
                        <?php if ($unit['status'] === 'Occupied'): ?>
                            <button class="btn btn-warning" onclick="vacateTenant()">
                                <i class="fas fa-sign-out-alt"></i> Vacate Tenant
                            </button>
                        <?php endif; ?>
                        <button class="btn btn-danger" onclick="removeUnit('<?= $unit['status'] ?>')">
                            <i class="fas fa-trash"></i> Remove Unit
                        </button>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="mt-4">
                    <h6 style="color: var(--main-color); font-weight: 600; margin-bottom: 15px;">
                        <i class="fas fa-star"></i> Amenities
                    </h6>
                    <div class="amenities-list">
                        <?php foreach ($unit['amenities'] as $amenity): ?>
                            <div class="amenity-tag">
                                <i class="fas fa-check"></i>
                                <span><?= $amenity ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Active Tenancy -->
                    <?php if ($unit['status'] === 'Occupied' && $activeTenant): ?>
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fas fa-user-check"></i> Active Tenancy</h5>
                                <div class="tenant-card">
                                    <div class="tenant-name"><?= $activeTenant['name'] ?></div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="tenant-detail">
                                                <i class="fas fa-phone"></i>
                                                <span><?= $activeTenant['phone'] ?></span>
                                            </div>
                                            <div class="tenant-detail">
                                                <i class="fas fa-envelope"></i>
                                                <span><?= $activeTenant['email'] ?></span>
                                            </div>
                                            <div class="tenant-detail">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span>Move-in: <?= date('M d, Y', strtotime($activeTenant['move_in_date'])) ?></span>
                                            </div>
                                            <div class="tenant-detail">
                                                <i class="fas fa-calendar-check"></i>
                                                <span>Lease Ends: <?= date('M d, Y', strtotime($activeTenant['lease_end'])) ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="tenant-detail">
                                                <i class="fas fa-money-bill-wave"></i>
                                                <span>Monthly Rent: KES <?= number_format($activeTenant['rent_amount']) ?></span>
                                            </div>
                                            <div class="tenant-detail">
                                                <i class="fas fa-shield-alt"></i>
                                                <span>Security Deposit: KES <?= number_format($activeTenant['security_deposit']) ?></span>
                                            </div>
                                            <div class="tenant-detail">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span style="color: var(--danger-color); font-weight: 600;">
                                                    Arrears: KES <?= number_format($activeTenant['rent_arrears']) ?>
                                                </span>
                                            </div>
                                            <div class="tenant-detail">
                                                <i class="fas fa-clock"></i>
                                                <span>Next Payment: <?= date('M d, Y', strtotime($activeTenant['next_payment_due'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Revenue History -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fas fa-history"></i> Payment History (Last 6 Months)</h5>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($revenueHistory as $payment): ?>
                                            <tr>
                                                <td><?= $payment['month'] ?></td>
                                                <td>KES <?= number_format($payment['amount']) ?></td>
                                                <td>
                                                    <span class="status-badge badge-<?= strtolower($payment['status']) ?>">
                                                        <?= $payment['status'] ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Requests -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fas fa-tools"></i> Maintenance Requests</h5>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Date</th>
                                            <th>Issue</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Cost (KES)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($maintenanceRequests as $request): ?>
                                            <tr>
                                                <td><strong><?= $request['id'] ?></strong></td>
                                                <td><?= date('M d, Y', strtotime($request['date'])) ?></td>
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
                                                <td><?= number_format($request['cost']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Previous Tenancies -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fas fa-history"></i> Previous Tenancies</h5>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Tenant Name</th>
                                            <th>Move-In</th>
                                            <th>Move-Out</th>
                                            <th>Duration</th>
                                            <th>Final Rent</th>
                                            <th>Reason</th>
                                            <th>Deposit Refunded</th>
                                            <th>Arrears</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($previousTenants as $tenant): ?>
                                            <tr>
                                                <td><strong><?= $tenant['tenant_name'] ?></strong></td>
                                                <td><?= date('M Y', strtotime($tenant['move_in'])) ?></td>
                                                <td><?= date('M Y', strtotime($tenant['move_out'])) ?></td>
                                                <td><?= $tenant['duration'] ?></td>
                                                <td>KES <?= number_format($tenant['final_rent']) ?></td>
                                                <td><?= $tenant['reason_for_leaving'] ?></td>
                                                <td class="text-success">KES <?= number_format($tenant['deposit_refunded']) ?></td>
                                                <td class="<?= $tenant['arrears_at_exit'] > 0 ? 'text-danger' : 'text-success' ?>">
                                                    KES <?= number_format($tenant['arrears_at_exit']) ?>
                                                </td>
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
                    <!-- Revenue Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6>Total Revenue</h6>
                                        <div class="value" style="font-size: 22px;">KES <?= number_format($revenueStats['total_revenue']) ?></div>
                                        <small style="color: #666;"><?= $revenueStats['months_collected'] ?> months collected</small>
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
                                        <h6>Current Month</h6>
                                        <div class="value" style="font-size: 22px;">KES <?= number_format($revenueStats['current_month']) ?></div>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="stat-card" style="border-left-color: var(--danger-color);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6>Outstanding Arrears</h6>
                                        <div class="value" style="font-size: 22px; color: var(--danger-color);">
                                            KES <?= number_format($revenueStats['arrears']) ?>
                                        </div>
                                    </div>
                                    <div class="icon" style="color: var(--danger-color);">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="stat-card" style="border-left-color: var(--success-color);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6>Collection Rate</h6>
                                        <div class="value" style="color: var(--success-color);">
                                            <?= $revenueStats['collection_rate'] ?>%
                                        </div>
                                    </div>
                                    <div class="icon" style="color: var(--success-color);">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Information -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fas fa-info-circle"></i> Unit Information</h5>
                            <div class="mb-3">
                                <small class="text-muted">Registration Date</small>
                                <div class="fw-bold"><?= date('M d, Y', strtotime($unit['registration_date'])) ?></div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Last Renovation</small>
                                <div class="fw-bold"><?= date('M d, Y', strtotime($unit['last_renovation'])) ?></div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Total Tenants</small>
                                <div class="fw-bold"><?= count($previousTenants) + ($unit['status'] === 'Occupied' ? 1 : 0) ?> (<?= $unit['status'] === 'Occupied' ? '1 current, ' : '' ?><?= count($previousTenants) ?> previous)</div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Maintenance Costs (Total)</small>
                                <div class="fw-bold">KES <?= number_format(array_sum(array_column($maintenanceRequests, 'cost'))) ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="fas fa-bolt"></i> Quick Actions</h5>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary">
                                    <i class="fas fa-file-invoice"></i> Generate Report
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="fas fa-camera"></i> Add Photos
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="fas fa-wrench"></i> Request Maintenance
                                </button>
                                <button class="btn btn-outline-primary">
                                    <i class="fas fa-calendar-plus"></i> Schedule Inspection
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="custom-footer">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0">&copy; 2024 PropertyHub. All rights reserved.</p>
                    <div class="d-flex gap-3">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Support</a>
                        <a href="#">Contact</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Vacate tenant
        function vacateTenant() {
            if (confirm('Are you sure you want to vacate the current tenant?\n\nThis will:\n- End the current tenancy\n- Mark the unit as vacant\n- Process final billing and security deposit')) {
                alert('Redirecting to vacate tenant process...\n\nIn a real application, this would open the vacate off-canvas or redirect to the vacation page.');
                // window.location.href = 'units.php?action=vacate&unit=<?= $unit['id'] ?>';
            }
        }

        // Remove unit
        function removeUnit(status) {
            if (status === 'Occupied') {
                alert('Cannot remove an occupied unit!\n\nPlease vacate the current tenant first before removing this unit.');
                return;
            }

            if (confirm('Are you sure you want to remove Unit <?= $unit['unit_number'] ?>?\n\nThis action cannot be undone.\n\nThe unit will be:\n- Permanently deleted from the system\n- All historical data will be archived')) {
                alert('Unit removed successfully!\n\nIn a real application, this would:\n- Archive all unit data\n- Remove from active listings\n- Update building statistics');
                // window.location.href = 'units.php?action=delete&unit=<?= $unit['id'] ?>';
            }
        }
    </script>
</body>

</html>