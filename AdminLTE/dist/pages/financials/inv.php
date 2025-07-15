<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management System</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            color: #333;
            line-height: 1.6;
            background-color: #f5f7fa;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #2a5bd7;
            color: white;
            border: 1px solid #2a5bd7;
        }

        .btn-primary:hover {
            background-color: #1e4bc4;
            border-color: #1e4bc4;
        }

        .btn-outline {
            background-color: transparent;
            color: #2a5bd7;
            border: 1px solid #2a5bd7;
        }

        .btn-outline:hover {
            background-color: #f0f5ff;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Header Styles */
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #2a5bd7;
        }

        .logo span {
            color: #ff6b00;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #4a5568;
        }

        /* Sidebar Navigation */
        .app-container {
            display: flex;
            min-height: calc(100vh - 66px);
        }

        .sidebar {
            width: 220px;
            background-color: white;
            border-right: 1px solid #e2e8f0;
            padding: 20px 0;
        }

        .sidebar-nav {
            margin-top: 20px;
        }

        .sidebar-nav li {
            margin-bottom: 5px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #4a5568;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-nav a:hover, .sidebar-nav a.active {
            background-color: #f0f5ff;
            color: #2a5bd7;
            border-left: 3px solid #2a5bd7;
        }

        .sidebar-nav i {
            margin-right: 10px;
            font-size: 18px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f5f7fa;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 24px;
            color: #1a365d;
        }

        .page-actions {
            display: flex;
            gap: 10px;
        }

        /* Invoice List */
        .invoice-list-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .invoice-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .invoice-list-title {
            font-weight: 600;
            color: #1a365d;
        }

        .invoice-list-filters {
            display: flex;
            gap: 15px;
        }

        .filter-dropdown {
            position: relative;
        }

        .filter-btn {
            background-color: transparent;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #4a5568;
        }

        .filter-btn:hover {
            background-color: #f8fafc;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 10;
            display: none;
        }

        .filter-dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu ul {
            padding: 10px 0;
        }

        .dropdown-menu li {
            padding: 8px 15px;
            cursor: pointer;
        }

        .dropdown-menu li:hover {
            background-color: #f8fafc;
        }

        .invoice-list {
            padding: 0;
        }

        .invoice-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s ease;
        }

        .invoice-item:hover {
            background-color: #f8fafc;
        }

        .invoice-checkbox {
            margin-right: 15px;
        }

        .invoice-number {
            width: 120px;
            font-weight: 600;
            color: #2a5bd7;
        }

        .invoice-customer {
            flex: 1;
            color: #4a5568;
        }

        .invoice-date {
            width: 100px;
            color: #718096;
            font-size: 14px;
        }

        .invoice-amount {
            width: 100px;
            font-weight: 600;
            text-align: right;
        }

        .invoice-status {
            width: 100px;
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-paid {
            background-color: #e6fffa;
            color: #38b2ac;
        }

        .status-pending {
            background-color: #fffaf0;
            color: #dd6b20;
        }

        .status-overdue {
            background-color: #fff5f5;
            color: #f56565;
        }

        .invoice-actions {
            width: 80px;
            display: flex;
            justify-content: flex-end;
        }

        .action-btn {
            background: none;
            border: none;
            color: #718096;
            cursor: pointer;
            padding: 5px;
        }

        .action-btn:hover {
            color: #2a5bd7;
        }

        /* Create Invoice Page */
        .invoice-form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            color: #1a365d;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #4a5568;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #2a5bd7;
        }

        .form-control-sm {
            padding: 6px 8px;
            font-size: 13px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            text-align: left;
            padding: 10px;
            background-color: #f8fafc;
            color: #718096;
            font-weight: 500;
            font-size: 13px;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .item-row input {
            width: 100%;
            border: 1px solid #e2e8f0;
            padding: 8px;
            border-radius: 4px;
        }

        .item-row input:focus {
            outline: none;
            border-color: #2a5bd7;
        }

        .item-name {
            width: 40%;
        }

        .item-qty, .item-rate, .item-amount {
            width: 15%;
        }

        .item-actions {
            width: 15%;
            text-align: center;
        }

        .remove-item {
            color: #f56565;
            cursor: pointer;
        }

        .add-item-btn {
            background-color: transparent;
            border: none;
            color: #2a5bd7;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px;
        }

        /* Summary Section */
        .summary-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .summary-label {
            width: 150px;
            text-align: right;
            padding-right: 20px;
            color: #718096;
        }

        .summary-value {
            width: 150px;
            text-align: right;
            font-weight: 500;
        }

        .total-row {
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 16px;
        }

        .total-value {
            color: #2a5bd7;
            font-weight: 600;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .action-left {
            display: flex;
            gap: 10px;
        }

        .action-right {
            display: flex;
            gap: 10px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .app-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
            }

            .sidebar-nav {
                display: flex;
                overflow-x: auto;
            }

            .sidebar-nav li {
                margin-bottom: 0;
                white-space: nowrap;
            }

            .sidebar-nav a {
                padding: 10px 15px;
            }

            .invoice-item {
                flex-wrap: wrap;
                gap: 10px;
            }

            .invoice-number, .invoice-customer, .invoice-date, .invoice-amount, .invoice-status, .invoice-actions {
                width: auto;
                flex: 1 1 100px;
            }

            .form-row {
                flex-direction: column;
                gap: 10px;
            }

            .items-table th, .items-table td {
                padding: 8px 5px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <div class="logo">Invoice<span>Pro</span></div>
            <div class="header-actions">
                <button class="btn btn-outline">
                    <i class="fas fa-question-circle"></i> Help
                </button>
                <div class="user-avatar">JD</div>
            </div>
        </div>
    </header>

    <!-- Main App Container -->
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-nav">
                <ul>
                    <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="#" class="active"><i class="fas fa-file-invoice"></i> Invoices</a></li>
                    <li><a href="#"><i class="fas fa-users"></i> Customers</a></li>
                    <li><a href="#"><i class="fas fa-boxes"></i> Products</a></li>
                    <li><a href="#"><i class="fas fa-chart-line"></i> Reports</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Invoice List View (Default) -->
            <div id="invoice-list-view">
                <div class="page-header">
                    <h1 class="page-title">Invoices</h1>
                    <div class="page-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button class="btn btn-outline">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <button class="btn btn-primary" id="create-invoice-btn">
                            <i class="fas fa-plus"></i> Create Invoice
                        </button>
                    </div>
                </div>

                <div class="invoice-list-container">
                    <div class="invoice-list-header">
                        <div class="invoice-list-title">All Invoices</div>
                        <div class="invoice-list-filters">
                            <div class="filter-dropdown">
                                <button class="filter-btn">
                                    <span>Status: All</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <ul>
                                        <li>All</li>
                                        <li>Paid</li>
                                        <li>Pending</li>
                                        <li>Overdue</li>
                                        <li>Draft</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="filter-dropdown">
                                <button class="filter-btn">
                                    <span>Date: This Month</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <ul>
                                        <li>Today</li>
                                        <li>This Week</li>
                                        <li>This Month</li>
                                        <li>This Quarter</li>
                                        <li>Custom Range</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="invoice-list">
                      
                        <!-- Invoice Item -->
                        <div class="invoice-item">
                            <div class="invoice-checkbox">
                                <input type="checkbox">
                            </div>
                            <div class="invoice-number">INV-2023-001</div>
                            <div class="invoice-customer">Acme Corporation</div>
                            <div class="invoice-date">May 15, 2023</div>
                            <div class="invoice-amount">$1,250.00</div>
                            <div class="invoice-status">
                                <span class="status-badge status-paid">Paid</span>
                            </div>
                            <div class="invoice-actions">
                                <button class="action-btn">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Invoice Item -->
                        <div class="invoice-item">
                            <div class="invoice-checkbox">
                                <input type="checkbox">
                            </div>
                            <div class="invoice-number">INV-2023-002</div>
                            <div class="invoice-customer">Globex Inc.</div>
                            <div class="invoice-date">May 18, 2023</div>
                            <div class="invoice-amount">$3,450.00</div>
                            <div class="invoice-status">
                                <span class="status-badge status-pending">Pending</span>
                            </div>
                            <div class="invoice-actions">
                                <button class="action-btn">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Invoice Item -->
                        <div class="invoice-item">
                            <div class="invoice-checkbox">
                                <input type="checkbox">
                            </div>
                            <div class="invoice-number">INV-2023-003</div>
                            <div class="invoice-customer">Wayne Enterprises</div>
                            <div class="invoice-date">May 5, 2023</div>
                            <div class="invoice-amount">$2,150.00</div>
                            <div class="invoice-status">
                                <span class="status-badge status-overdue">Overdue</span>
                            </div>
                            <div class="invoice-actions">
                                <button class="action-btn">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Invoice Item -->
                        <div class="invoice-item">
                            <div class="invoice-checkbox">
                                <input type="checkbox">
                            </div>
                            <div class="invoice-number">INV-2023-004</div>
                            <div class="invoice-customer">Stark Industries</div>
                            <div class="invoice-date">May 22, 2023</div>
                            <div class="invoice-amount">$5,750.00</div>
                            <div class="invoice-status">
                                <span class="status-badge status-paid">Paid</span>
                            </div>
                            <div class="invoice-actions">
                                <button class="action-btn">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Invoice Item -->
                        <div class="invoice-item">
                            <div class="invoice-checkbox">
                                <input type="checkbox">
                            </div>
                            <div class="invoice-number">INV-2023-005</div>
                            <div class="invoice-customer">Umbrella Corp</div>
                            <div class="invoice-date">May 25, 2023</div>
                            <div class="invoice-amount">$1,980.00</div>
                            <div class="invoice-status">
                                <span class="status-badge status-pending">Pending</span>
                            </div>
                            <div class="invoice-actions">
                                <button class="action-btn">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Invoice View (Hidden by default) -->
            <div id="create-invoice-view" style="display: none;">
                <div class="page-header">
                    <h1 class="page-title">Create Invoice</h1>
                    <div class="page-actions">
                        <button class="btn btn-outline" id="cancel-invoice-btn">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button class="btn btn-outline">
                            <i class="fas fa-save"></i> Save Draft
                        </button>
                        <button class="btn btn-primary" id="preview-invoice-btn">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                    </div>
                </div>

                <div class="invoice-form-container">
                    <!-- Customer Section -->
                    <div class="form-section">
                        <h3 class="section-title">Customer Details</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="customer">Customer</label>
                                <select id="customer" class="form-control">
                                    <option value="">Select a customer</option>
                                    <option value="1">Acme Corporation</option>
                                    <option value="2">Globex Inc.</option>
                                    <option value="3">Wayne Enterprises</option>
                                    <option value="4">Stark Industries</option>
                                    <option value="5">Umbrella Corp</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="invoice-number">Invoice #</label>
                                <input type="text" id="invoice-number" class="form-control" value="INV-2023-006" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="invoice-date">Invoice Date</label>
                                <input type="date" id="invoice-date" class="form-control" value="2023-05-30">
                            </div>
                            <div class="form-group">
                                <label for="due-date">Due Date</label>
                                <input type="date" id="due-date" class="form-control" value="2023-06-14">
                            </div>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="form-section">
                        <h3 class="section-title">Items</h3>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>ITEM</th>
                                    <th>DESCRIPTION</th>
                                    <th>QTY</th>
                                    <th>RATE</th>
                                    <th>AMOUNT</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="invoice-items">
                                <!-- Item Row -->
                                <tr class="item-row">
                                    <td class="item-name">
                                        <input type="text" placeholder="Item name" value="Website Design">
                                    </td>
                                    <td class="item-desc">
                                        <input type="text" placeholder="Description" value="Homepage redesign">
                                    </td>
                                    <td class="item-qty">
                                        <input type="number" placeholder="Qty" value="1" class="form-control-sm">
                                    </td>
                                    <td class="item-rate">
                                        <input type="number" placeholder="Rate" value="1200.00" class="form-control-sm">
                                    </td>
                                    <td class="item-amount">
                                        <input type="text" placeholder="Amount" value="$1,200.00" readonly class="form-control-sm">
                                    </td>
                                    <td class="item-actions">
                                        <span class="remove-item"><i class="fas fa-trash"></i></span>
                                    </td>
                                </tr>
                                <!-- Item Row -->
                                <tr class="item-row">
                                    <td class="item-name">
                                        <input type="text" placeholder="Item name" value="SEO Services">
                                    </td>
                                    <td class="item-desc">
                                        <input type="text" placeholder="Description" value="Monthly SEO package">
                                    </td>
                                    <td class="item-qty">
                                        <input type="number" placeholder="Qty" value="1" class="form-control-sm">
                                    </td>
                                    <td class="item-rate">
                                        <input type="number" placeholder="Rate" value="500.00" class="form-control-sm">
                                    </td>
                                    <td class="item-amount">
                                        <input type="text" placeholder="Amount" value="$500.00" readonly class="form-control-sm">
                                    </td>
                                    <td class="item-actions">
                                        <span class="remove-item"><i class="fas fa-trash"></i></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="add-item-btn" id="add-item-btn">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>

                    <!-- Notes & Terms -->
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea id="notes" class="form-control" rows="3" placeholder="Thank you for your business!"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="terms">Terms & Conditions</label>
                                <textarea id="terms" class="form-control" rows="3" placeholder="Payment due within 15 days"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="form-section">
                        <div class="summary-row">
                            <div class="summary-label">Subtotal:</div>
                            <div class="summary-value">$1,700.00</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Tax (10%):</div>
                            <div class="summary-value">$170.00</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Discount:</div>
                            <div class="summary-value">$0.00</div>
                        </div>
                        <div class="summary-row total-row">
                            <div class="summary-label">Total:</div>
                            <div class="summary-value total-value">$1,870.00</div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <div class="action-left">
                            <button class="btn btn-outline">
                                <i class="fas fa-paperclip"></i> Attach File
                            </button>
                        </div>
                        <div class="action-right">
                            <button class="btn btn-outline">
                                <i class="fas fa-envelope"></i> Send
                            </button>
                            <button class="btn btn-primary">
                                <i class="fas fa-check"></i> Save & Finalize
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


     <script>
        // Database simulation
        const database = {
            invoices: [
                { id: 1, number: 'INV-2023-001', customer: 'Acme Corporation', date: '2023-05-15', amount: 1250.00, status: 'paid' },
                { id: 2, number: 'INV-2023-002', customer: 'Globex Inc.', date: '2023-05-18', amount: 3450.00, status: 'pending' },
                { id: 3, number: 'INV-2023-003', customer: 'Wayne Enterprises', date: '2023-05-05', amount: 2150.00, status: 'overdue' },
                { id: 4, number: 'INV-2023-004', customer: 'Stark Industries', date: '2023-05-22', amount: 5750.00, status: 'paid' },
                { id: 5, number: 'INV-2023-005', customer: 'Umbrella Corp', date: '2023-05-25', amount: 1980.00, status: 'pending' }
            ],
            customers: [
                { id: 1, name: 'Acme Corporation', email: 'contact@acme.com', phone: '(555) 123-4567' },
                { id: 2, name: 'Globex Inc.', email: 'info@globex.com', phone: '(555) 234-5678' },
                { id: 3, name: 'Wayne Enterprises', email: 'office@wayne.com', phone: '(555) 345-6789' },
                { id: 4, name: 'Stark Industries', email: 'contact@stark.com', phone: '(555) 456-7890' },
                { id: 5, name: 'Umbrella Corp', email: 'support@umbrella.com', phone: '(555) 567-8901' }
            ],
            products: [
                { id: 1, name: 'Website Design', description: 'Custom website design', price: 1200.00 },
                { id: 2, name: 'SEO Services', description: 'Monthly SEO package', price: 500.00 },
                { id: 3, name: 'Hosting', description: 'Annual web hosting', price: 300.00 },
                { id: 4, name: 'Maintenance', description: 'Monthly maintenance', price: 200.00 }
            ],
            nextInvoiceId: 6
        };

        // DOM Elements
        const invoiceListView = document.getElementById('invoice-list-view');
        const createInvoiceView = document.getElementById('create-invoice-view');
        const createInvoiceBtn = document.getElementById('create-invoice-btn');
        const cancelInvoiceBtn = document.getElementById('cancel-invoice-btn');
        const previewInvoiceBtn = document.getElementById('preview-invoice-btn');
        const addItemBtn = document.getElementById('add-item-btn');
        const invoiceItems = document.getElementById('invoice-items');
        const invoiceList = document.querySelector('.invoice-list');
        const filterStatusBtn = document.querySelector('.filter-dropdown:first-child .filter-btn');
        const filterDateBtn = document.querySelector('.filter-dropdown:last-child .filter-btn');
        const statusDropdownItems = document.querySelectorAll('.filter-dropdown:first-child .dropdown-menu li');
        const dateDropdownItems = document.querySelectorAll('.filter-dropdown:last-child .dropdown-menu li');
        const customerSelect = document.getElementById('customer');
        const invoiceNumberInput = document.getElementById('invoice-number');
        const invoiceDateInput = document.getElementById('invoice-date');
        const dueDateInput = document.getElementById('due-date');
        const notesInput = document.getElementById('notes');
        const termsInput = document.getElementById('terms');
        const saveDraftBtn = document.querySelector('.page-actions .btn-outline:nth-child(2)');
        const saveFinalizeBtn = document.querySelector('.form-actions .btn-primary');
        const sendBtn = document.querySelector('.form-actions .btn-outline:nth-child(1)');
        const attachFileBtn = document.querySelector('.action-left .btn-outline');
        const exportBtn = document.querySelector('.page-actions .btn-outline:nth-child(2)');
        const filterBtn = document.querySelector('.page-actions .btn-outline:nth-child(1)');
        const helpBtn = document.querySelector('.header-actions .btn-outline');
        const sidebarLinks = document.querySelectorAll('.sidebar-nav a');

        // Initialize the app
        function initApp() {
            renderInvoiceList();
            populateCustomerSelect();
            setupEventListeners();
            updateInvoiceNumber();
        }

        // Render invoice list
        function renderInvoiceList(filterStatus = 'all', filterDate = 'this-month') {
            invoiceList.innerHTML = '';

            let filteredInvoices = [...database.invoices];

            // Filter by status
            if (filterStatus !== 'all') {
                filteredInvoices = filteredInvoices.filter(inv => inv.status === filterStatus);
            }

            // Filter by date (simplified for demo)
            if (filterDate === 'today') {
                filteredInvoices = filteredInvoices.filter(inv => inv.date === new Date().toISOString().split('T')[0]);
            } else if (filterDate === 'this-week') {
                // In a real app, you'd have proper date filtering
                filteredInvoices = filteredInvoices.slice(0, 3); // Just for demo
            } else if (filterDate === 'this-month') {
                // Current month filter would go here
            }

            // Render each invoice
            filteredInvoices.forEach(invoice => {
                const invoiceItem = document.createElement('div');
                invoiceItem.className = 'invoice-item';
                invoiceItem.innerHTML = `
                    <div class="invoice-checkbox">
                        <input type="checkbox">
                    </div>
                    <div class="invoice-number">${invoice.number}</div>
                    <div class="invoice-customer">${invoice.customer}</div>
                    <div class="invoice-date">${formatDate(invoice.date)}</div>
                    <div class="invoice-amount">$${invoice.amount.toFixed(2)}</div>
                    <div class="invoice-status">
                        <span class="status-badge status-${invoice.status}">${capitalizeFirstLetter(invoice.status)}</span>
                    </div>
                    <div class="invoice-actions">
                        <button class="action-btn">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                `;
                invoiceList.appendChild(invoiceItem);
            });
        }

        // Format date for display
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        // Capitalize first letter
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Populate customer select dropdown
        function populateCustomerSelect() {
            customerSelect.innerHTML = '<option value="">Select a customer</option>';
            database.customers.forEach(customer => {
                const option = document.createElement('option');
                option.value = customer.id;
                option.textContent = customer.name;
                customerSelect.appendChild(option);
            });
        }

        // Update invoice number
        function updateInvoiceNumber() {
            invoiceNumberInput.value = `INV-2023-${String(database.nextInvoiceId).padStart(3, '0')}`;
        }

        // Calculate invoice totals
        function calculateTotals() {
            let subtotal = 0;
            const itemRows = document.querySelectorAll('.item-row');

            itemRows.forEach(row => {
                const qty = parseFloat(row.querySelector('.item-qty input').value) || 0;
                const rate = parseFloat(row.querySelector('.item-rate input').value) || 0;
                const amount = qty * rate;
                row.querySelector('.item-amount input').value = '$' + amount.toFixed(2);
                subtotal += amount;
            });

            const tax = subtotal * 0.1; // 10% tax for demo
            const total = subtotal + tax;

            // Update summary section
            document.querySelector('.summary-row:nth-child(1) .summary-value').textContent = '$' + subtotal.toFixed(2);
            document.querySelector('.summary-row:nth-child(2) .summary-value').textContent = '$' + tax.toFixed(2);
            document.querySelector('.summary-row.total-row .summary-value').textContent = '$' + total.toFixed(2);

            return total;
        }

        // Save invoice to database
        function saveInvoice(isDraft = false) {
            const customerId = parseInt(customerSelect.value);
            const customer = database.customers.find(c => c.id === customerId);

            const items = [];
            document.querySelectorAll('.item-row').forEach(row => {
                items.push({
                    name: row.querySelector('.item-name input').value,
                    description: row.querySelector('.item-desc input').value,
                    qty: parseFloat(row.querySelector('.item-qty input').value) || 0,
                    rate: parseFloat(row.querySelector('.item-rate input').value) || 0
                });
            });

            const total = calculateTotals();

            const newInvoice = {
                id: database.nextInvoiceId++,
                number: invoiceNumberInput.value,
                customer: customer ? customer.name : 'Unknown Customer',
                date: invoiceDateInput.value,
                dueDate: dueDateInput.value,
                amount: total,
                status: isDraft ? 'draft' : 'pending',
                items: items,
                notes: notesInput.value,
                terms: termsInput.value
            };

            database.invoices.unshift(newInvoice);
            return newInvoice;
        }

        // Setup event listeners
        function setupEventListeners() {
            // Navigation
            createInvoiceBtn.addEventListener('click', () => {
                invoiceListView.style.display = 'none';
                createInvoiceView.style.display = 'block';
                updateInvoiceNumber();
            });

            cancelInvoiceBtn.addEventListener('click', () => {
                createInvoiceView.style.display = 'none';
                invoiceListView.style.display = 'block';
            });

            // Filter dropdowns
            statusDropdownItems.forEach(item => {
                item.addEventListener('click', () => {
                    filterStatusBtn.querySelector('span').textContent = `Status: ${item.textContent}`;
                    renderInvoiceList(item.textContent.toLowerCase());
                });
            });

            dateDropdownItems.forEach(item => {
                item.addEventListener('click', () => {
                    filterDateBtn.querySelector('span').textContent = `Date: ${item.textContent}`;
                    // In a real app, you would filter by date range
                });
            });

            // Invoice items
            addItemBtn.addEventListener('click', addNewItemRow);

            // Calculate totals when values change
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-rate')) {
                    calculateTotals();
                }
            });

            // Save buttons
            saveDraftBtn.addEventListener('click', () => {
                saveInvoice(true);
                alert('Invoice saved as draft!');
                createInvoiceView.style.display = 'none';
                invoiceListView.style.display = 'block';
                renderInvoiceList();
            });

            saveFinalizeBtn.addEventListener('click', () => {
                saveInvoice(false);
                alert('Invoice saved and finalized!');
                createInvoiceView.style.display = 'none';
                invoiceListView.style.display = 'block';
                renderInvoiceList();
            });

            // Other buttons
            previewInvoiceBtn.addEventListener('click', () => {
                alert('Invoice preview would open in a new window');
            });

            sendBtn.addEventListener('click', () => {
                const invoice = saveInvoice(false);
                alert(`Invoice ${invoice.number} would be sent to ${invoice.customer}`);
                createInvoiceView.style.display = 'none';
                invoiceListView.style.display = 'block';
                renderInvoiceList();
            });

            attachFileBtn.addEventListener('click', () => {
                alert('File attachment dialog would open');
            });

            exportBtn.addEventListener('click', () => {
                alert('Export functionality would be implemented here');
            });

            filterBtn.addEventListener('click', () => {
                alert('Advanced filter dialog would open');
            });

            helpBtn.addEventListener('click', () => {
                alert('Help documentation would open');
            });

            // Sidebar navigation
            sidebarLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    sidebarLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                    // In a real app, this would load the appropriate view
                    alert(`Loading ${link.textContent} section`);
                });
            });
        }

        // Add new item row
        function addNewItemRow() {
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            newRow.innerHTML = `
                <td class="item-name">
                    <input type="text" placeholder="Item name">
                </td>
                <td class="item-desc">
                    <input type="text" placeholder="Description">
                </td>
                <td class="item-qty">
                    <input type="number" placeholder="Qty" value="1" class="form-control-sm">
                </td>
                <td class="item-rate">
                    <input type="number" placeholder="Rate" class="form-control-sm">
                </td>
                <td class="item-amount">
                    <input type="text" placeholder="Amount" readonly class="form-control-sm">
                </td>
                <td class="item-actions">
                    <span class="remove-item"><i class="fas fa-trash"></i></span>
                </td>
            `;
            invoiceItems.appendChild(newRow);

            // Add event listener to remove button
            newRow.querySelector('.remove-item').addEventListener('click', () => {
                invoiceItems.removeChild(newRow);
                calculateTotals();
            });
        }

        // Initialize the app when DOM is loaded
        document.addEventListener('DOMContentLoaded', initApp);
    </script>

</body>
</html>
<!-- </html> -->