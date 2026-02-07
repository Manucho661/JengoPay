<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    // In a real application, you would save this to a database
    $success_message = "Expense added successfully!";
}

// Dummy expense data
$expenses = [
    [
        'id' => 'EXP-2024-001',
        'date' => '2024-01-15',
        'supplier' => 'Office Supplies Inc.',
        'amount' => 1250.00,
        'status' => 'paid',
        'items' => [
            ['name' => 'Printer Paper', 'qty' => 50, 'price' => 15.00],
            ['name' => 'Toner Cartridges', 'qty' => 10, 'price' => 50.00]
        ]
    ],
    [
        'id' => 'EXP-2024-002',
        'date' => '2024-01-18',
        'supplier' => 'Tech Solutions Ltd.',
        'amount' => 3500.00,
        'status' => 'pending',
        'items' => [
            ['name' => 'Laptops', 'qty' => 2, 'price' => 1500.00],
            ['name' => 'Software Licenses', 'qty' => 5, 'price' => 100.00]
        ]
    ],
    [
        'id' => 'EXP-2024-003',
        'date' => '2024-01-20',
        'supplier' => 'Facility Services Co.',
        'amount' => 850.00,
        'status' => 'paid',
        'items' => [
            ['name' => 'Cleaning Services', 'qty' => 1, 'price' => 500.00],
            ['name' => 'Maintenance', 'qty' => 1, 'price' => 350.00]
        ]
    ],
    [
        'id' => 'EXP-2024-004',
        'date' => '2024-01-22',
        'supplier' => 'Marketing Pro Agency',
        'amount' => 5200.00,
        'status' => 'pending',
        'items' => [
            ['name' => 'Ad Campaign', 'qty' => 1, 'price' => 4000.00],
            ['name' => 'Design Services', 'qty' => 1, 'price' => 1200.00]
        ]
    ],
    [
        'id' => 'EXP-2024-005',
        'date' => '2024-01-25',
        'supplier' => 'Office Furniture Plus',
        'amount' => 2100.00,
        'status' => 'paid',
        'items' => [
            ['name' => 'Desk Chairs', 'qty' => 6, 'price' => 200.00],
            ['name' => 'Standing Desks', 'qty' => 3, 'price' => 300.00]
        ]
    ],
    [
        'id' => 'EXP-2024-006',
        'date' => '2024-01-28',
        'supplier' => 'Utilities & Energy Corp.',
        'amount' => 1450.00,
        'status' => 'pending',
        'items' => [
            ['name' => 'Electricity', 'qty' => 1, 'price' => 800.00],
            ['name' => 'Water', 'qty' => 1, 'price' => 650.00]
        ]
    ],
    [
        'id' => 'EXP-2024-007',
        'date' => '2024-02-01',
        'supplier' => 'Travel & Transport Ltd.',
        'amount' => 980.00,
        'status' => 'paid',
        'items' => [
            ['name' => 'Flight Tickets', 'qty' => 2, 'price' => 400.00],
            ['name' => 'Hotel Accommodation', 'qty' => 1, 'price' => 180.00]
        ]
    ],
    [
        'id' => 'EXP-2024-008',
        'date' => '2024-02-05',
        'supplier' => 'Catering Services Inc.',
        'amount' => 650.00,
        'status' => 'pending',
        'items' => [
            ['name' => 'Corporate Event Catering', 'qty' => 1, 'price' => 650.00]
        ]
    ]
];

// Calculate statistics
$totalExpenses = count($expenses);
$totalAmount = array_sum(array_column($expenses, 'amount'));
$paidExpenses = array_filter($expenses, fn($e) => $e['status'] === 'paid');
$pendingExpenses = array_filter($expenses, fn($e) => $e['status'] === 'pending');
$totalPaid = array_sum(array_column($paidExpenses, 'amount'));
$totalPending = array_sum(array_column($pendingExpenses, 'amount'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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

        .menu-item:hover, .menu-item.active {
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            border-left: 4px solid var(--accent-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 25, 45, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-card.total .stat-icon {
            background: rgba(0, 25, 45, 0.1);
            color: var(--main-color);
        }

        .stat-card.amount .stat-icon {
            background: rgba(255, 193, 7, 0.2);
            color: var(--accent-color);
        }

        .stat-card.paid .stat-icon {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .stat-card.pending .stat-icon {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        .stat-card h2 {
            color: var(--main-color);
        }

        /* Bootstrap Button Overrides */
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

        .btn-outline-primary {
            color: var(--main-color);
            border-color: var(--main-color);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active {
            background: var(--main-color);
            border-color: var(--main-color);
            color: white;
        }

        /* Card Styling */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 25, 45, 0.08);
        }

        .card-title {
            color: var(--main-color);
            font-weight: 600;
        }

        /* Accordion */
        .expense-accordion {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .accordion-header-custom {
            background: white;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .accordion-header-custom:hover {
            background: rgba(255, 193, 7, 0.05);
            border-left-color: var(--accent-color);
        }

        .accordion-header-custom.active {
            background: rgba(0, 25, 45, 0.05);
            border-left-color: var(--main-color);
        }

        .accordion-header-custom strong {
            color: var(--main-color);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-paid {
            background: rgba(39, 174, 96, 0.15);
            color: var(--success-color);
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #d39e00;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            border: none;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .action-btn.view-btn {
            background: rgba(0, 25, 45, 0.1);
            color: var(--main-color);
        }

        .action-btn.view-btn:hover {
            background: var(--main-color);
            color: white;
        }

        .action-btn.delete-btn {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        .action-btn.delete-btn:hover {
            background: var(--danger-color);
            color: white;
        }

        .accordion-content-custom {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .accordion-content-custom.active {
            max-height: 600px;
        }

        /* Table Styling */
        .table-hover tbody tr:hover {
            background: rgba(255, 193, 7, 0.05);
        }

        .table thead th {
            color: var(--main-color);
            font-weight: 600;
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

        /* Form Controls */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        /* Alert */
        .alert-success {
            background: rgba(255, 193, 7, 0.1);
            border-color: var(--accent-color);
            color: var(--main-color);
        }

        .alert-info {
            background: rgba(0, 25, 45, 0.05);
            border-color: var(--main-color);
            color: var(--main-color);
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
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>ExpenseHub</h2>
            <p>Management System</p>
        </div>
        <nav class="mt-3">
            <a href="#" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="menu-item active">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Expenses</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-truck"></i>
                <span>Suppliers</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-chart-line"></i>
                <span>Reports</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="custom-header d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Expense Management</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="user-avatar">JD</div>
                    <div>
                        <div class="fw-bold" style="font-size: 14px;">John Doe</div>
                        <div class="text-muted" style="font-size: 12px;">Administrator</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 flex-grow-1">
            <!-- Success Message -->
            <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= $success_message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-card total d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">All Expenses</h6>
                            <h2 class="mb-0"><?= $totalExpenses ?></h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card amount d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Amount</h6>
                            <h2 class="mb-0">$<?= number_format($totalAmount, 2) ?></h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card paid d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Paid</h6>
                            <h2 class="mb-0">$<?= number_format($totalPaid, 2) ?></h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card pending d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Pending</h6>
                            <h2 class="mb-0">$<?= number_format($totalPending, 2) ?></h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-filter"></i> Filters</h5>
                    <form method="GET">
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Supplier or expense no...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Date From</label>
                                <input type="date" name="date_from" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small">Date To</label>
                                <input type="date" name="date_to" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Expenses Section -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="fas fa-list"></i> Expense List</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-truck"></i> View Suppliers
                            </button>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                                <i class="fas fa-plus"></i> Add Expense
                            </button>
                        </div>
                    </div>

                    <!-- Expense Accordion -->
                    <?php foreach ($expenses as $expense): ?>
                    <div class="expense-accordion">
                        <div class="accordion-header-custom" onclick="toggleExpenseAccordion(this)">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <small class="text-muted d-block text-uppercase" style="font-size: 11px;">Date</small>
                                    <strong><?= date('M d, Y', strtotime($expense['date'])) ?></strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block text-uppercase" style="font-size: 11px;">Supplier</small>
                                    <strong><?= $expense['supplier'] ?></strong>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted d-block text-uppercase" style="font-size: 11px;">Expense No</small>
                                    <strong><?= $expense['id'] ?></strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block text-uppercase" style="font-size: 11px;">Total Paid</small>
                                    <strong>$<?= number_format($expense['amount'], 2) ?></strong>
                                    <span class="status-badge status-<?= $expense['status'] ?> ms-2">
                                        <?= ucfirst($expense['status']) ?>
                                    </span>
                                </div>
                                <div class="col-md-2 text-end">
                                    <button class="action-btn view-btn" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn delete-btn ms-2" title="Delete" onclick="deleteExpense(event, '<?= $expense['id'] ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-content-custom">
                            <div class="p-4 border-top">
                                <h6 class="mb-3">Expense Items</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($expense['items'] as $item): ?>
                                            <tr>
                                                <td><?= $item['name'] ?></td>
                                                <td><?= $item['qty'] ?></td>
                                                <td>$<?= number_format($item['price'], 2) ?></td>
                                                <td><strong>$<?= number_format($item['qty'] * $item['price'], 2) ?></strong></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="custom-footer">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0">&copy; 2024 ExpenseHub. All rights reserved.</p>
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

    <!-- Add Expense Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Add New Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="expenseForm">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Expense Number *</label>
                                <input type="text" name="expense_no" class="form-control" placeholder="EXP-2024-XXX" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date *</label>
                                <input type="date" name="expense_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Supplier *</label>
                                <select name="supplier" class="form-select" required>
                                    <option value="">Select Supplier</option>
                                    <option value="Office Supplies Inc.">Office Supplies Inc.</option>
                                    <option value="Tech Solutions Ltd.">Tech Solutions Ltd.</option>
                                    <option value="Facility Services Co.">Facility Services Co.</option>
                                    <option value="Marketing Pro Agency">Marketing Pro Agency</option>
                                    <option value="Other">Other (New Supplier)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3">Expense Items</h6>
                                <div id="itemsContainer">
                                    <div class="row g-2 mb-2 expense-item">
                                        <div class="col-md-5">
                                            <input type="text" name="item_name[]" class="form-control" placeholder="Item name" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="item_qty[]" class="form-control" placeholder="Qty" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="item_price[]" class="form-control" placeholder="Price" step="0.01" min="0" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addItem()">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <strong>Total Amount:</strong> <span id="totalAmount">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_expense" class="btn btn-success">
                            <i class="fas fa-save"></i> Save Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleExpenseAccordion(element) {
            if (event.target.closest('.action-btn')) {
                return;
            }

            const content = element.nextElementSibling;
            const isActive = content.classList.contains('active');

            // Close all
            document.querySelectorAll('.accordion-content-custom').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelectorAll('.accordion-header-custom').forEach(item => {
                item.classList.remove('active');
            });

            // Toggle current
            if (!isActive) {
                content.classList.add('active');
                element.classList.add('active');
            }
        }

        function deleteExpense(event, expenseId) {
            event.stopPropagation();
            if (confirm(`Are you sure you want to delete expense ${expenseId}?`)) {
                alert(`Expense ${expenseId} would be deleted!\n\nIn a real application, this would send a request to the server.`);
            }
        }

        // Add item row
        function addItem() {
            const container = document.getElementById('itemsContainer');
            const newItem = document.querySelector('.expense-item').cloneNode(true);
            newItem.querySelectorAll('input').forEach(input => input.value = input.type === 'number' && input.name === 'item_qty[]' ? '1' : '');
            container.appendChild(newItem);
            calculateTotal();
        }

        // Remove item row
        function removeItem(button) {
            const items = document.querySelectorAll('.expense-item');
            if (items.length > 1) {
                button.closest('.expense-item').remove();
                calculateTotal();
            } else {
                alert('At least one item is required!');
            }
        }

        // Calculate total
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.expense-item').forEach(item => {
                const qty = parseFloat(item.querySelector('input[name="item_qty[]"]').value) || 0;
                const price = parseFloat(item.querySelector('input[name="item_price[]"]').value) || 0;
                total += qty * price;
            });
            document.getElementById('totalAmount').textContent = '$' + total.toFixed(2);
        }

        // Attach listeners to inputs
        document.addEventListener('input', function(e) {
            if (e.target.name === 'item_qty[]' || e.target.name === 'item_price[]') {
                calculateTotal();
            }
        });
    </script>
</body>
</html>