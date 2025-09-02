<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Portal - Pay Rent</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #00192D;
            --accent-yellow: #FFC107;
            --light-bg: #F8F9FA;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-dark);
        }

        .tenant-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border: none;
        }

        .tenant-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: var(--primary-dark);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .btn-rent {
            background-color: var(--primary-dark);
            color: var(--accent-yellow);
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-rent:hover {
            background-color: #002a4a;
            color: #ffd351;
            transform: scale(1.03);
        }

        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover, .payment-method.active {
            border-color: var(--primary-dark);
            background-color: rgba(0, 25, 45, 0.05);
        }

        .payment-method i {
            font-size: 1.5rem;
            margin-right: 10px;
            color: var(--primary-dark);
        }

        .receipt-box {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .balance-positive {
            color: #dc3545;
            font-weight: bold;
        }

        .balance-negative {
            color: #28a745;
            font-weight: bold;
        }

        #paymentStatus {
            display: none;
        }

        .processing-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 0.2em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
        }

        @keyframes spinner-border {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<div > <?php include_once '../includes/sidebar1.php'; ?>  </div> <!-- This is where the sidebar is inserted -->
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-building me-2"></i>PropertyPro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-home me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-file-invoice-dollar me-1"></i> Rent</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-tools me-1"></i> Maintenance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user-circle me-1"></i> Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <?php
        // Include database connection
        include '../db/connect.php';

        try {
            // Get tenant ID from session or URL (you'll need to implement your authentication)
            $tenantId = 1; // Example - replace with your actual tenant ID

            // Get tenant data
            $stmt = $pdo->prepare("SELECT * FROM tenant_rent_summary WHERE id = ?");
            $stmt->execute([$tenantId]);
            $tenant = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$tenant) {
                throw new Exception("Tenant not found");
            }

            // Calculate current month and year
            $currentMonth = date('F');
            $currentYear = date('Y');

            // Get payment history
            $historyStmt = $pdo->prepare("SELECT * FROM tenant_rent_summary
                                        WHERE tenant_name = ?
                                        ORDER BY year DESC, FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December') DESC");
            $historyStmt->execute([$tenant['tenant_name']]);
            $paymentHistory = $historyStmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            die($e->getMessage());
        }
        ?>

        <div class="row">
            <!-- Tenant Summary -->
            <div class="col-lg-4 mb-4">
                <div class="card tenant-card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Tenant Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($tenant['tenant_name']) ?>&background=00192D&color=FFC107&size=128"
                                 class="rounded-circle mb-3" alt="Tenant Avatar">
                            <h4><?= htmlspecialchars($tenant['tenant_name']) ?></h4>
                            <p class="text-muted">Unit <?= htmlspecialchars($tenant['unit_code']) ?></p>
                        </div>

                        <div class="tenant-details">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span><i class="fas fa-building me-2"></i>Building:</span>
                                <span class="fw-bold"><?= htmlspecialchars($tenant['building_name']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span><i class="fas fa-home me-2"></i>Unit Type:</span>
                                <span class="fw-bold"><?= htmlspecialchars($tenant['unit_type']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span><i class="fas fa-dollar-sign me-2"></i>Monthly Rent:</span>
                                <span class="fw-bold">KSH <?= number_format($tenant['amount_paid'] + $tenant['balances'] - $tenant['overpayment'], 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span><i class="fas fa-info-circle me-2"></i>Status:</span>
                                <span class="badge <?= $tenant['balances'] > 0 ? 'bg-danger' : 'bg-success' ?>">
                                    <?= $tenant['balances'] > 0 ? 'Balance Due' : 'Current' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rent Payment Section -->
            <div class="col-lg-6">
                <div class="card tenant-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Rent Payment</h5>
                    </div>
                    <div class="card-body">
                        <!-- Current Balance -->
                        <div class="alert <?= $tenant['balances'] > 0 ? 'alert-danger' : 'alert-success' ?> d-flex align-items-center" role="alert">
                            <i class="fas <?= $tenant['balances'] > 0 ? 'fa-exclamation-circle' : 'fa-check-circle' ?> me-3 fs-4"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Current Balance</h5>
                                <p class="mb-0">
                                    <?php if ($tenant['balances'] > 0): ?>
                                        Outstanding balance of KSH <?= number_format($tenant['balances'], 2) ?>
                                        <?php if ($tenant['penalty'] > 0): ?>
                                            (includes KSH <?= number_format($tenant['penalty'], 2) ?> penalty for <?= $tenant['penalty_days'] ?> late days)
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Your account is current
                                        <?php if ($tenant['overpayment'] > 0): ?>
                                            with KSH <?= number_format($tenant['overpayment'], 2) ?> credit
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <span class="ms-auto fw-bold fs-4 <?= $tenant['balances'] > 0 ? 'balance-positive' : 'balance-negative' ?>">
                                KSH <?= number_format(abs($tenant['balances']), 2) ?>
                            </span>
                        </div>

                        <!-- Payment History -->
                        <!-- // include '../db/connect.php';

                        // $stmt = $pdo->query("SELECT * FROM payment_history ORDER BY created_at DESC");
                        // $paymentHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
                         -->

                        <h5 class="mt-4 mb-3"><i class="fas fa-history me-2"></i>Payment History</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Month</th>
                                        <th>Amount Paid</th>
                                        <th>Balance</th>
                                        <th>Receipt</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($paymentHistory as $payment): ?>
                                    <tr>
                                        <td><?= date('M j, Y', strtotime($payment['payment_date'])) ?></td>
                                        <td><?= $payment['month'] ?> <?= $payment['year'] ?></td>
                                        <td>KSH <?= number_format($payment['amount_paid'], 2) ?></td>
                                        <td class="<?= $payment['balances'] > 0 ? 'balance-positive' : 'balance-negative' ?>">
                                            KSH <?= number_format(abs($payment['balances']), 2) ?>
                                        </td>
                                        <td>
                                            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#receiptModal"
                                               data-date="<?= date('M j, Y', strtotime($payment['payment_date'])) ?>"
                                               data-amount="<?= number_format($payment['amount_paid'], 2) ?>"
                                               data-month="<?= $payment['month'] ?> <?= $payment['year'] ?>">
                                                View
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge <?= $payment['balances'] > 0 ? 'bg-warning' : 'bg-success' ?>">
                                                <?= $payment['balances'] > 0 ? 'Partial' : 'Paid' ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pay Rent Button -->
                        <div class="text-center mt-4">
                            <button  class="btn btn-rent px-4 py-2" data-bs-toggle="modal" data-bs-target="#rentModal">
                                <i class="fas fa-hand-holding-usd me-2"></i>Pay Rent Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rent Payment Modal with Daraja API Integration -->
    <div class="modal fade" id="rentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #00192D; color: #FFC107;">
                    <h5 class="modal-title"><i class="fas fa-hand-holding-usd me-2"></i>Make Rent Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Payment Methods -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Select Payment Method</h5>

                            <div class="payment-method active" data-method="mpesa">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-mobile-alt"></i>
                                    <div>
                                        <h6 class="mb-1">M-Pesa</h6>
                                        <p id="payBtn" class="small text-muted mb-0">Pay via M-Pesa mobile money</p>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-method" data-method="bank">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-university"></i>
                                    <div>
                                        <h6 class="mb-1">Bank Transfer</h6>
                                        <p class="small text-muted mb-0">Direct bank deposit</p>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-method" data-method="card">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-credit-card"></i>
                                    <div>
                                        <h6 class="mb-1">Credit/Debit Card</h6>
                                        <p class="small text-muted mb-0">Visa, Mastercard, etc.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- M-Pesa Payment Form -->
                            <div class="mt-4" id="mpesaForm">
                                <h6 class="mb-3">M-Pesa Payment Details</h6>
                                <form id="rentPaymentForm">
                                    <input type="hidden" name="tenant_id" value="<?= $tenantId ?>">
                                    <input type="hidden" name="month" value="<?= $currentMonth ?>">
                                    <input type="hidden" name="year" value="<?= $currentYear ?>">
                                    <input type="hidden" name="payment_method" value="mpesa">

                                    <div class="mb-3">
                                        <label class="form-label">Phone Number (2547XXXXXXXX)</label>
                                        <input type="tel" class="form-control" name="phone"
                                               pattern="254[0-9]{9}"
                                               title="Format: 2547XXXXXXXX"
                                               placeholder="e.g. 254712345678" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Amount (KSH)</label>
                                        <input type="number" class="form-control" name="amount"
                                               value="<?= max(0, $tenant['balances']) ?>"
                                               min="5"
                                               max="150000"
                                               required>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="savePayment" name="save_payment">
                                        <label class="form-check-label" for="savePayment">
                                            Save payment method for future use
                                        </label>
                                    </div>

                                    <div id="paymentStatus" class="alert alert-info">
                                        <span id="statusMessage"></span>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="col-md-6">
                            <div class="receipt-box">
                                <h5 class="mb-3">Payment Summary</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Monthly Rent:</span>
                                    <span>KSH <?= number_format($tenant['amount_paid'] + $tenant['balances'] - $tenant['overpayment'], 2) ?></span>
                                </div>
                                <?php if ($tenant['penalty'] > 0): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Late Fee (<?= $tenant['penalty_days'] ?> days):</span>
                                    <span>KSH <?= number_format($tenant['penalty'], 2) ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ($tenant['overpayment'] > 0): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Credit Applied:</span>
                                    <span class="text-success">-KSH <?= number_format($tenant['overpayment'], 2) ?></span>
                                </div>
                                <?php endif; ?>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total Amount Due:</span>
                                    <span>KSH <?= number_format(max(0, $tenant['balances']), 2) ?></span>
                                </div>

                                <hr>

                                <div class="alert alert-info mt-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Payment will be processed immediately. A receipt will be sent to your phone.
                                </div>

                                <button type="submit" form="rentPaymentForm" class="btn btn-rent w-100 py-2 mt-2" id="submitPaymentBtn">
                                    <i class="fas fa-lock me-2"></i>Confirm Payment
                                </button>

                                <div class="text-center mt-3">
                                    <img src="https://via.placeholder.com/150x50?text=Secure+Payment" alt="Secure Payment" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="receipt-box text-center">
                        <h4 class="mb-4">PropertyPro</h4>
                        <p class="mb-1">Payment Receipt</p>
                        <hr>

                        <div class="text-start">
                            <p><strong>Tenant:</strong> <span id="receiptTenant"><?= htmlspecialchars($tenant['tenant_name']) ?></span></p>
                            <p><strong>Unit:</strong> <?= htmlspecialchars($tenant['unit_code']) ?> (<?= htmlspecialchars($tenant['building_name']) ?>)</p>
                            <p><strong>Payment Date:</strong> <span id="receiptDate"></span></p>
                            <p><strong>For Month:</strong> <span id="receiptMonth"></span></p>
                            <p><strong>Amount:</strong> KSH <span id="receiptAmount"></span></p>
                        </div>

                        <hr>
                        <p class="small text-muted">Thank you for your payment!</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printReceiptBtn">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activate payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
                this.classList.add('active');
                document.querySelector('input[name="payment_method"]').value = this.dataset.method;
            });
        });

        // Receipt modal handler
        const receiptModal = document.getElementById('receiptModal');
        if (receiptModal) {
            receiptModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('receiptDate').textContent = button.getAttribute('data-date');
                document.getElementById('receiptMonth').textContent = button.getAttribute('data-month');
                document.getElementById('receiptAmount').textContent = button.getAttribute('data-amount');
            });
        }

        // Print receipt button
        document.getElementById('printReceiptBtn')?.addEventListener('click', function() {
            window.print();
        });

        // Form submission handler with Daraja API integration
        document.getElementById('rentPaymentForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('submitPaymentBtn');
            const paymentStatus = document.getElementById('paymentStatus');
            const statusMessage = document.getElementById('statusMessage');

            // Disable submit button and show processing status
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="processing-spinner me-2"></span> Processing...';

            paymentStatus.style.display = 'block';
            paymentStatus.className = 'alert alert-info';
            statusMessage.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Initiating M-Pesa payment request...';

            try {
                // Convert form data to JSON
                const jsonData = {};
                formData.forEach((value, key) => jsonData[key] = value);

                // Send payment request to your server
                const response = await fetch('process_mpesa_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(jsonData)
                });

                const result = await response.json();

                if (result.success) {
                    // Payment initiated successfully
                    paymentStatus.className = 'alert alert-success';
                    statusMessage.innerHTML = '<i class="fas fa-check-circle me-2"></i> Payment request sent! Check your phone to complete the transaction.';

                    // Poll for payment confirmation
                    await checkPaymentStatus(result.checkoutRequestID);
                } else {
                    throw new Error(result.message || 'Payment failed to initiate');
                }
            } catch (error) {
                paymentStatus.className = 'alert alert-danger';
                statusMessage.innerHTML = `<i class="fas fa-times-circle me-2"></i> Error: ${error.message}`;
                console.error('Payment error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Confirm Payment';
            }
        });

        // Function to check payment status
        async function checkPaymentStatus(checkoutRequestID) {
            const submitBtn = document.getElementById('submitPaymentBtn');
            const paymentStatus = document.getElementById('paymentStatus');
            const statusMessage = document.getElementById('statusMessage');

            try {
                // Check status every 3 seconds (max 10 times)
                for (let i = 0; i < 10; i++) {
                    const response = await fetch('check_payment_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ checkoutRequestID })
                    });

                    const result = await response.json();

                    if (result.success && result.status === 'completed') {
                        // Payment completed successfully
                        paymentStatus.className = 'alert alert-success';
                        statusMessage.innerHTML = '<i class="fas fa-check-circle me-2"></i> Payment completed successfully!';

                        // Close modal and refresh page after delay
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('rentModal'));
                            modal.hide();
                            window.location.reload();
                        }, 2000);
                        return;
                    } else if (result.success && result.status === 'pending') {
                        // Still waiting
                        statusMessage.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i> Waiting for payment confirmation... (Attempt ${i+1}/10)`;
                        await new Promise(resolve => setTimeout(resolve, 3000));
                    } else {
                        throw new Error(result.message || 'Payment verification failed');
                    }
                }

                throw new Error('Payment confirmation timed out. Please check your M-Pesa transactions.');
            } catch (error) {
                paymentStatus.className = 'alert alert-warning';
                statusMessage.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i> Notice: ${error.message}`;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Confirm Payment';
                console.error('Status check error:', error);
            }
        }
    </script>
    <!-- <script>
// Update the form submission handler
document.getElementById('rentPaymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitPaymentBtn');
    const paymentStatus = document.getElementById('paymentStatus');
    const statusMessage = document.getElementById('statusMessage');

    // Validate phone number
    const phone = formData.get('phone');
    if (!phone.match(/^254[0-9]{9}$/)) {
        paymentStatus.style.display = 'block';
        paymentStatus.className = 'alert alert-danger';
        statusMessage.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> Please enter a valid M-Pesa phone number (format: 2547XXXXXXXX)';
        return;
    }

    // Validate amount
    const amount = parseFloat(formData.get('amount'));
    if (amount < 100 || amount > 150000) {
        paymentStatus.style.display = 'block';
        paymentStatus.className = 'alert alert-danger';
        statusMessage.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> Amount must be between KSH 100 and KSH 150,000';
        return;
    }

    // Disable submit button and show processing status
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="processing-spinner me-2"></span> Processing...';

    paymentStatus.style.display = 'block';
    paymentStatus.className = 'alert alert-info';
    statusMessage.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Initiating M-Pesa payment request...';

    try {
        // Convert form data to JSON
        const jsonData = {};
        formData.forEach((value, key) => jsonData[key] = value);

        // Send payment request to server
        const response = await fetch('process_mpesa_payment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(jsonData)
        });

        const result = await response.json();

        if (result.success) {
            // Payment initiated successfully
            paymentStatus.className = 'alert alert-success';
            statusMessage.innerHTML = '<i class="fas fa-check-circle me-2"></i> Payment request sent! Check your phone to complete the transaction.';

            // Poll for payment confirmation
            await checkPaymentStatus(result.checkoutRequestID);
        } else {
            throw new Error(result.message || 'Payment failed to initiate');
        }
    } catch (error) {
        paymentStatus.className = 'alert alert-danger';
        statusMessage.innerHTML = `<i class="fas fa-times-circle me-2"></i> Error: ${error.message}`;
        console.error('Payment error:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Confirm Payment';
    }
});

// Function to check payment status
async function checkPaymentStatus(checkoutRequestID) {
    const submitBtn = document.getElementById('submitPaymentBtn');
    const paymentStatus = document.getElementById('paymentStatus');
    const statusMessage = document.getElementById('statusMessage');

    try {
        // Check status every 3 seconds (max 10 times)
        for (let i = 0; i < 10; i++) {
            const response = await fetch('check_payment_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ checkoutRequestID })
            });

            const result = await response.json();

            if (result.success && result.status === 'completed') {
                // Payment completed successfully
                paymentStatus.className = 'alert alert-success';
                statusMessage.innerHTML = '<i class="fas fa-check-circle me-2"></i> Payment completed successfully!';

                // Close modal and refresh page after delay
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('rentModal'));
                    modal.hide();
                    window.location.reload();
                }, 2000);
                return;
            } else if (result.success && result.status === 'pending') {
                // Still waiting
                statusMessage.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i> Waiting for payment confirmation... (Attempt ${i+1}/10)`;
                await new Promise(resolve => setTimeout(resolve, 3000));
            } else {
                throw new Error(result.message || 'Payment verification failed');
            }
        }

        throw new Error('Payment confirmation timed out. Please check your M-Pesa transactions.');
    } catch (error) {
        paymentStatus.className = 'alert alert-warning';
        statusMessage.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i> Notice: ${error.message}`;
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Confirm Payment';
        console.error('Status check error:', error);
    }
}
</script>

<script>
document.getElementById("payBtn").addEventListener("click", function () {
    fetch("stk_push.php", { method: "POST" })
        .then(response => response.json())
        .then(data => {
            alert("Payment request sent. Check your phone.");
        });
});
</script> -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const payBtn = document.getElementById("payBtn");

    payBtn.addEventListener("click", function () {
        const phoneNumber = prompt("Enter your phone number (e.g., 254708374149):");
        const amount = prompt("Enter amount to pay:");

        if (!phoneNumber || !/^2547\d{8}$/.test(phoneNumber)) {
            alert("Please enter a valid Safaricom number (e.g., 2547XXXXXXXX).");
            return;
        }

        if (!amount || isNaN(amount) || Number(amount) <= 0) {
            alert("Please enter a valid amount.");
            return;
        }

        fetch("stk_push.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                phone: phoneNumber,
                amount: amount
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("STK Push sent! Check your phone to complete the payment.");
            } else {
                alert("Error: " + (data.message || "Something went wrong"));
            }
        })
        .catch(error => {
            console.error("STK Error:", error);
            alert("Failed to send STK push.");
        });
    });
});
</script>


</body>
</html>