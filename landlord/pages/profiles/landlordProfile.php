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
require_once "./actions/getLandlordDetails.php";
?>
<?php
// Landlord data
$landlord = [
    'id' => 'LL-2024-001',
    'name' => 'John Doe',
    'email' => 'john.doe@propertyhub.com',
    'phone' => '+254 712 345 678',
    'profile_image' => 'https://ui-avatars.com/api/?name=John+Doe&size=200&background=FFC107&color=00192D&bold=true',
    'role' => 'Property Owner',
    'joined_date' => '2023-01-15',
    'last_login' => '2024-02-11 08:30:00',
    'two_factor_enabled' => true,
    'properties_count' => 3,
    'units_count' => 45,
    'tenants_count' => 38
];

// Subscription data
$subscription = [
    'plan' => 'Professional',
    'status' => 'Active',
    'billing_cycle' => 'Monthly',
    'amount' => 4999,
    'currency' => 'KES',
    'start_date' => '2023-01-15',
    'next_billing' => '2024-03-15',
    'payment_method' => 'M-Pesa',
    'auto_renewal' => true,
    'features' => [
        'Unlimited Properties',
        'Unlimited Units',
        'Tenant Management',
        'Financial Reports',
        'Maintenance Tracking',
        'SMS Notifications',
        'Email Support',
        'Mobile App Access'
    ]
];

// Available plans
$availablePlans = [
    [
        'name' => 'Basic',
        'price' => 1999,
        'billing' => 'Monthly',
        'properties' => '1 Property',
        'units' => 'Up to 10 Units',
        'features' => ['Basic Reports', 'Email Support']
    ],
    [
        'name' => 'Professional',
        'price' => 4999,
        'billing' => 'Monthly',
        'properties' => 'Unlimited Properties',
        'units' => 'Unlimited Units',
        'features' => ['Advanced Reports', 'SMS Notifications', 'Priority Support'],
        'current' => true
    ],
    [
        'name' => 'Enterprise',
        'price' => 9999,
        'billing' => 'Monthly',
        'properties' => 'Unlimited Properties',
        'units' => 'Unlimited Units',
        'features' => ['Custom Reports', 'API Access', 'Dedicated Support', 'White Label']
    ]
];

// Activity Log
$activityLog = [
    ['action' => 'Logged in', 'timestamp' => '2024-02-11 08:30:00', 'ip' => '197.156.xxx.xxx'],
    ['action' => 'Updated unit information', 'timestamp' => '2024-02-10 15:45:00', 'ip' => '197.156.xxx.xxx'],
    ['action' => 'Added new tenant', 'timestamp' => '2024-02-09 11:20:00', 'ip' => '197.156.xxx.xxx'],
    ['action' => 'Generated financial report', 'timestamp' => '2024-02-08 09:15:00', 'ip' => '197.156.xxx.xxx'],
    ['action' => 'Password changed', 'timestamp' => '2024-02-05 14:30:00', 'ip' => '197.156.xxx.xxx']
];

// Payment History
$paymentHistory = [
    ['date' => '2024-02-15', 'amount' => 4999, 'method' => 'M-Pesa', 'status' => 'Paid', 'invoice' => 'INV-2024-002'],
    ['date' => '2024-01-15', 'amount' => 4999, 'method' => 'M-Pesa', 'status' => 'Paid', 'invoice' => 'INV-2024-001'],
    ['date' => '2023-12-15', 'amount' => 4999, 'method' => 'M-Pesa', 'status' => 'Paid', 'invoice' => 'INV-2023-012'],
    ['date' => '2023-11-15', 'amount' => 4999, 'method' => 'M-Pesa', 'status' => 'Paid', 'invoice' => 'INV-2023-011']
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?= $landlord['name'] ?></title>

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

        /* Custom Header */
        .custom-header {
            background: var(--main-color);
            padding: 20px 30px;
            /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
        }

        .custom-header h1 {
            color: var(--white-color);
        }

        /* Profile Header */
        .profile-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            /* box-shadow: 0 2px 8px rgba(0, 25, 45, 0.08); */
            margin-bottom: 25px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid var(--accent-color);
            object-fit: cover;
        }

        .profile-info h2 {
            color: var(--main-color);
            margin-bottom: 10px;
        }

        .profile-info p {
            color: #666;
            margin-bottom: 5px;
        }

        .profile-badge {
            background: rgba(255, 193, 7, 0.2);
            color: var(--main-color);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        /* Stats */
        .stat-box {
            text-align: center;
            padding: 15px;
            background: rgba(255, 193, 7, 0.1);
            border-radius: 8px;
        }

        .stat-box .number {
            font-size: 32px;
            font-weight: bold;
            color: var(--main-color);
        }

        .stat-box .label {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
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
            /* box-shadow: 0 2px 8px rgba(0, 25, 45, 0.08); */
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

        /* Subscription Card */
        .subscription-card {
            background: linear-gradient(135deg, var(--main-color) 0%, #001a3a 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
        }

        .subscription-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: var(--accent-color);
            opacity: 0.1;
            border-radius: 50%;
        }

        .plan-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .plan-price {
            font-size: 42px;
            font-weight: bold;
            color: var(--accent-color);
        }

        /* Plan Cards */
        .plan-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s;
            position: relative;
        }

        .plan-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-5px);
        }

        .plan-card.current {
            border-color: var(--accent-color);
            background: rgba(255, 193, 7, 0.05);
        }

        .plan-card .current-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: var(--main-color);
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 11px;
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

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-active {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .badge-paid {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        /* Danger Zone */
        .danger-zone {
            border: 2px solid var(--danger-color);
            border-radius: 10px;
            padding: 25px;
            background: rgba(231, 76, 60, 0.05);
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

        /* Toggle Switch */
        .form-switch .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .form-switch .form-check-input:focus {
            box-shadow: none;
            border-color: var(--accent-color);
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
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="">
                        <li class="breadcrumb-item"><a href="/Jengopay/landlord/pages/Dashboard/dashboard.php" style="text-decoration: none;">Dashboard</a></li>
                        <li class="breadcrumb-item active">Your Profile</li>
                    </ol>
                </nav>

                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="<?= $fullName ?>" alt="Profile" class="profile-image">
                            <button class="btn btn-sm btn-outline-primary mt-3">
                                <i class="fas fa-camera"></i> Change Photo
                            </button>
                        </div>
                        <div class="col-md-7">
                            <div class="profile-info">
                                <h2><?= $fullName ?></h2>
                                <p><i class="fas fa-envelope text-warning"></i> <?= $profile['user_email'] ?></p>
                                <p><i class="fas fa-phone text-warning"></i> 075683843</p>
                                <p><i class="fas fa-id-badge text-warning"></i> ID: 38011790</p>
                                <div class="mt-2">
                                    <span class="profile-badge">Property Manager</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="stat-box">
                                        <div class="number"><?= $profile['total_properties'] ?></div>
                                        <div class="label">Properties</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box">
                                        <div class="number"><?= $profile['total_units'] ?></div>
                                        <div class="label">Units</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="stat-box">
                                        <div class="number"><?= $profile['active_tenants'] ?></div>
                                        <div class="label">Active Tenants</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- Content -->
            <main class="p-4 flex-grow-1">
                <!-- Profile Header -->
                

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-8">
                        <!-- Subscription Management -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4"><i class="fas fa-crown"></i> Subscription Management</h5>

                                <!-- Current Subscription -->
                                <div class="subscription-card mb-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="plan-name"><?= $subscription['plan'] ?> Plan</div>
                                            <p class="mb-2">
                                                <span class="status-badge badge-active">
                                                    <i class="fas fa-check-circle"></i> <?= $subscription['status'] ?>
                                                </span>
                                            </p>
                                            <p class="mb-1"><i class="fas fa-calendar-alt"></i> Next billing: <?= date('M d, Y', strtotime($subscription['next_billing'])) ?></p>
                                            <p class="mb-1"><i class="fas fa-credit-card"></i> Payment: <?= $subscription['payment_method'] ?></p>
                                            <p class="mb-0"><i class="fas fa-sync"></i> Auto-renewal: <?= $subscription['auto_renewal'] ? 'Enabled' : 'Disabled' ?></p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <div class="plan-price"><?= $subscription['currency'] ?> <?= number_format($subscription['amount']) ?></div>
                                            <p class="mb-0" style="opacity: 0.8;">/<?= strtolower($subscription['billing_cycle']) ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Features -->
                                <h6 class="mb-3" style="color: var(--main-color); font-weight: 600;">Your Plan Features</h6>
                                <div class="row g-2 mb-4">
                                    <?php foreach ($subscription['features'] as $feature): ?>
                                        <div class="col-md-6">
                                            <div style="background: rgba(255, 193, 7, 0.1); padding: 10px; border-radius: 5px;">
                                                <i class="fas fa-check-circle" style="color: var(--accent-color);"></i>
                                                <span style="margin-left: 8px;"><?= $feature ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="d-flex gap-2">
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#upgradePlanModal">
                                        <i class="fas fa-arrow-up"></i> Upgrade Plan
                                    </button>
                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paymentMethodModal">
                                        <i class="fas fa-credit-card"></i> Change Payment Method
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="cancelSubscription()">
                                        <i class="fas fa-times"></i> Cancel Subscription
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Payment History -->
                        <div class="card border-0 mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fas fa-history"></i> Payment History</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>Status</th>
                                                <th>Invoice</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($paymentHistory as $payment): ?>
                                                <tr>
                                                    <td><?= date('M d, Y', strtotime($payment['date'])) ?></td>
                                                    <td>KES <?= number_format($payment['amount']) ?></td>
                                                    <td><?= $payment['method'] ?></td>
                                                    <td>
                                                        <span class="status-badge badge-paid">
                                                            <?= $payment['status'] ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-download"></i> <?= $payment['invoice'] ?>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div class="card border-0 mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4"><i class="fas fa-shield-alt"></i> Security Settings</h5>

                                <!-- Change Password -->
                                <div class="mb-4">
                                    <h6 style="color: var(--main-color); font-weight: 600;">Change Password</h6>
                                    <button class="actionBtn" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="fas fa-key"></i> Update Password
                                    </button>
                                </div>

                                <!-- Change Email -->
                                <div class="mb-4">
                                    <h6 style="color: var(--main-color); font-weight: 600;">Change Email</h6>
                                    <button class="actionBtn" data-bs-toggle="modal" data-bs-target="#changeEmailModal">
                                        <i class="fas fa-envelope"></i> Update Email
                                    </button>
                                </div>

                                <!-- Two-Factor Authentication -->
                                <div class="mb-4">
                                    <h6 style="color: var(--main-color); font-weight: 600;">Two-Factor Authentication</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="twoFactorSwitch"
                                           
                                            onchange="toggleTwoFactor(this)">
                                        <label class="form-check-label" for="twoFactorSwitch">
                                        
                                        </label>
                                    </div>
                                    <small class="text-muted">Add an extra layer of security to your account</small>
                                </div>

                                <!-- Session Management -->
                                <div>
                                    <h6 style="color: var(--main-color); font-weight: 600;">Active Sessions</h6>
                                    <p class="text-muted mb-2">Last login: <?= date('M d, Y h:i A', strtotime($profile['created_at'])) ?></p>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-sign-out-alt"></i> Logout All Devices
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4 text-danger">
                                    <i class="fas fa-exclamation-triangle"></i> Danger Zone
                                </h5>
                                <div class="danger-zone">
                                    <h6 style="color: var(--danger-color); font-weight: 600;">Delete Account</h6>
                                    <p class="mb-3">Once you delete your account, there is no going back. This will permanently delete:</p>
                                    <ul class="mb-3">
                                        <li>All your properties and units</li>
                                        <li>All tenant information</li>
                                        <li>All financial records</li>
                                        <li>All messages and documents</li>
                                    </ul>
                                    <button class="btn btn-danger" onclick="deleteAccount()">
                                        <i class="fas fa-trash-alt"></i> Delete My Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-4">
                        <!-- Account Info -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fas fa-info-circle"></i> Account Information</h5>
                                <div class="mb-3">
                                    <small class="text-muted">Member Since</small>
                                    <div class="fw-bold"><?= date('M d, Y', strtotime($profile['created_at'])) ?></div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">Last Login</small>
                                    <div class="fw-bold"><?= date('M d, Y h:i A', strtotime($profile['created_at'])) ?></div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">Account Status</small>
                                    <div><span class="status-badge badge-active">Active</span></div>
                                </div>
                                <div>
                                    <small class="text-muted">2FA Status</small>
                                    <div class="fw-bold text-success">
                                        <i class="fas fa-check-circle"></i>
                                        <?= $landlord['two_factor_enabled'] ? 'Enabled' : 'Disabled' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Log -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fas fa-clock"></i> Recent Activity</h5>
                                <?php foreach ($activityLog as $activity): ?>
                                    <div class="mb-3 pb-3 border-bottom">
                                        <div class="fw-bold" style="color: var(--main-color); font-size: 14px;">
                                            <?= $activity['action'] ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= date('M d, Y h:i A', strtotime($activity['timestamp'])) ?>
                                        </small>
                                        <br>
                                        <small class="text-muted">IP: <?= $activity['ip'] ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fas fa-bolt"></i> Quick Actions</h5>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i> Download My Data
                                    </button>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-file-export"></i> Export Reports
                                    </button>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-bell"></i> Notification Settings
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

        <!--begin::Footer-->
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/footer.php'; ?>
        <!-- end footer -->
    </div>



    <!-- Upgrade Plan Modal -->
    <div class="modal fade" id="upgradePlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--main-color); color: white;">
                    <h5 class="modal-title" style="color: white;">
                        <i class="fas fa-arrow-up"></i> Upgrade Your Plan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <?php foreach ($availablePlans as $plan): ?>
                            <div class="col-md-4">
                                <div class="plan-card <?= isset($plan['current']) ? 'current' : '' ?>">
                                    <?php if (isset($plan['current'])): ?>
                                        <span class="current-badge">Current Plan</span>
                                    <?php endif; ?>
                                    <h4 style="color: var(--main-color);"><?= $plan['name'] ?></h4>
                                    <div style="font-size: 32px; font-weight: bold; color: var(--accent-color); margin: 15px 0;">
                                        KES <?= number_format($plan['price']) ?>
                                    </div>
                                    <p class="text-muted">/<?= strtolower($plan['billing']) ?></p>
                                    <hr>
                                    <div class="text-start mb-3">
                                        <p class="mb-2"><i class="fas fa-check text-success"></i> <?= $plan['properties'] ?></p>
                                        <p class="mb-2"><i class="fas fa-check text-success"></i> <?= $plan['units'] ?></p>
                                        <?php foreach ($plan['features'] as $feature): ?>
                                            <p class="mb-2"><i class="fas fa-check text-success"></i> <?= $feature ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (!isset($plan['current'])): ?>
                                        <button class="btn btn-success w-100">
                                            <?= $plan['price'] > $subscription['amount'] ? 'Upgrade' : 'Downgrade' ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--main-color); color: white;">
                    <h5 class="modal-title" style="color: white;">
                        <i class="fas fa-key"></i> Change Password
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Email Modal -->
    <div class="modal fade" id="changeEmailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--main-color); color: white;">
                    <h5 class="modal-title" style="color: white;">
                        <i class="fas fa-envelope"></i> Change Email
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Current Email</label>
                            <input type="email" class="form-control" value="<?= $landlord['email'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password (for verification)</label>
                            <input type="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Method Modal -->
    <div class="modal fade" id="paymentMethodModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--main-color); color: white;">
                    <h5 class="modal-title" style="color: white;">
                        <i class="fas fa-credit-card"></i> Change Payment Method
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select Payment Method</label>
                            <select class="form-select" required>
                                <option value="mpesa" selected>M-Pesa</option>
                                <option value="card">Credit/Debit Card</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">M-Pesa Phone Number</label>
                            <input type="tel" class="form-control" placeholder="+254 7XX XXX XXX" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Payment Method</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Two-Factor Authentication
        function toggleTwoFactor(checkbox) {
            const status = checkbox.checked ? 'enable' : 'disable';
            if (confirm(`Are you sure you want to ${status} Two-Factor Authentication?`)) {
                alert(`Two-Factor Authentication ${status}d successfully!\n\nIn a real application, this would ${status} 2FA for your account.`);
            } else {
                checkbox.checked = !checkbox.checked;
            }
        }

        // Cancel Subscription
        function cancelSubscription() {
            if (confirm('Are you sure you want to cancel your subscription?\n\nYou will lose access to:\n- All premium features\n- Property management tools\n- Tenant communication\n- Financial reports\n\nYour subscription will remain active until the end of your current billing period.')) {
                alert('Subscription cancelled!\n\nYour subscription will remain active until: <?= date('M d, Y', strtotime($subscription['next_billing'])) ?>\n\nIn a real application, this would cancel your subscription.');
            }
        }

        // Delete Account
        function deleteAccount() {
            const confirmed = confirm('⚠️ WARNING: This action cannot be undone!\n\nAre you absolutely sure you want to delete your account?\n\nThis will permanently delete:\n✗ All your properties\n✗ All units and tenants\n✗ All financial records\n✗ All messages and documents\n\nType "DELETE" in the next prompt to confirm.');

            if (confirmed) {
                const verification = prompt('Please type DELETE (in capital letters) to confirm:');
                if (verification === 'DELETE') {
                    alert('Account deletion initiated.\n\nIn a real application, this would:\n1. Send a confirmation email\n2. Archive all your data\n3. Schedule account deletion in 30 days\n4. Allow you to cancel within this period');
                } else {
                    alert('Account deletion cancelled.\n\nThe text you entered did not match.');
                }
            }
        }
    </script>
</body>

</html>