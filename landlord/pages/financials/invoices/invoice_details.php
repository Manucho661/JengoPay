<?php
session_start();
require_once "../../db/connect.php";

// ---------- SET‑UP ----------
$id = $_GET['id'] ?? null; // if present we'll show the details pane

// small helper for status → badge colour
function statusClass(string $status): string
{
    return match (strtolower($status)) {
        'paid'        => 'status-paid',
        'overdue'     => 'status-overdue',
        'cancelled'   => 'status-cancelled',
        'sent'        => 'status-pending',   // treat "sent" as pending
        default       => 'status-draft',     // draft / anything else
    };
}

// Fetch invoices with tenant details
try {
    $stmt = $pdo->prepare("
        SELECT 
            i.id,
            i.invoice_no as invoice_number,
            i.invoice_date,
            i.due_date,
            i.status,
            i.payment_status,
            i.building_id,
            i.subtotal,
            i.taxes,
            i.total,
            -- Get tenant name from tenants table
            CONCAT(t.first_name, ' ', COALESCE(t.middle_name, ''), ' ', t.last_name) as tenant_name,
            t.email as tenant_email,
            t.phone as tenant_phone,
            t.account_no
        FROM invoice i
        LEFT JOIN tenants t ON t.id = i.tenant_id
        ORDER BY i.created_at DESC
    ");
    $stmt->execute();
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $invoices = [];
    error_log("Error fetching invoices: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice Details</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Invoice Details" />
    
    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        .app-wrapper {
            background-color: rgba(128, 128, 128, 0.1);
        }

        .wrapper {
            display: flex;
            height: calc(100vh - 120px);
            border-left: 1px solid #ccc;
        }

        /* LEFT LIST */
        .soda {
            width: 350px;
            background: #fff;
            border-right: 2px solid #d0d0d0;
            overflow-y: auto;
        }
        
        .soda-header {
            padding: 16px 20px;
            font-weight: 600;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }
        
        .invoice-list {
            height: calc(100% - 60px);
            overflow-y: auto;
        }
        
        .invoice-link {
            display: block;
            text-decoration: none;
            color: inherit;
            border-bottom: 1px solid #f3f3f3;
        }
        
        .invoice-link:hover, .invoice-link.active {
            background: #f5f8ff;
        }
        
        .invoice-item {
            display: flex;
            gap: 12px;
            padding: 12px 20px;
            align-items: flex-start;
            cursor: pointer;
        }
        
        .invoice-summary {
            flex: 1;
        }
        
        .invoice-customer {
            font-weight: 600;
            margin-bottom: 2px;
            color: #00192D;
        }
        
        .invoice-meta {
            font-size: 12px;
            color: #7a7a7a;
            margin-bottom: 4px;
        }
        
        .invoice-amount {
            font-weight: 600;
            white-space: nowrap;
            color: #00192D;
        }
        
        /* Status Badges */
        .status-badge {
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            letter-spacing: .3px;
            text-transform: uppercase;
            display: inline-block;
            margin-right: 4px;
        }
        
        .badge-paid { background: #e8f5e9; color: #2e7d32; }
        .badge-partial { background: #fff8e1; color: #ff8f00; }
        .badge-unpaid { background: #ffebee; color: #c62828; }
        .badge-sent { background: #e3f2fd; color: #1565c0; }
        .badge-overdue { background: #ffebee; color: #c62828; }
        .badge-cancelled { background: #eceff1; color: #546e7a; }
        .badge-draft { background: #eceff1; color: #546e7a; }
        
        /* RIGHT DETAILS PANE */
        .viewer {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            background: #fff;
        }
        
        .placeholder {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #9e9e9e;
        }
        
        /* Invoice Card Styles */
        .invoice-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .diagonal-paid-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            background-color: rgba(0, 128, 0, 0.2);
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
            background-color: rgba(255, 0, 0, 0.2);
            color: #ff4d4d;
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
            background-color: rgba(255, 165, 0, 0.2);
            color: #ff9900;
            font-weight: bold;
            font-size: 24px;
            padding: 15px 40px;
            border: 2px solid #ff9900;
            border-radius: 8px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
        }
        
        .expense-logo {
            width: 150px;
            height: auto;
        }
        
        .custom-th th {
            background-color: #00192D !important;
            color: white !important;
            font-size: small;
            border: 1px solid #FFC107 !important;
        }
        
        .thick-bordered-table td {
            border: 1px solid #FFC107 !important;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">

    <!--begin::Header-->
    <?php
    //  include $_SERVER['DOCUMENT_ROOT'] . '/Jengopay/landlord/pages/includes/header.php'; 
    ?>
    <!--end::Header-->

    <!--begin::Sidebar-->
    <?php
    // include_once '../../includes/sidebar.php';
    ?>
    <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="wrapper">
                        <!-- LEFT: LIST OF INVOICES -->
                        <aside class="soda">
                            <div class="soda-header">
                                <span><b>Invoices</b></span>
                            </div>
                            <div class="invoice-list">
                                <?php if (empty($invoices)): ?>
                                    <div class="p-3 text-center text-muted">No invoices found</div>
                                <?php else: ?>
                                    <?php foreach ($invoices as $row): ?>
                                        <?php
                                        $link = 'invoice_details.php?id=' . $row['id'];
                                        $tenantName = !empty($row['tenant_name']) ? $row['tenant_name'] : 'Unknown Tenant';
                                        $invoiceDate = $row['invoice_date'] ? date('d M Y', strtotime($row['invoice_date'])) : 'Not set';
                                        $dueDate = $row['due_date'] ? date('d M Y', strtotime($row['due_date'])) : 'Not set';
                                        $amount = isset($row['total']) ? number_format($row['total'], 2) : '0.00';
                                        
                                        $status = $row['status'] ?? 'draft';
                                        $statusClass = match(strtolower($status)) {
                                            'sent' => 'badge-sent',
                                            'paid' => 'badge-paid',
                                            'overdue' => 'badge-overdue',
                                            'cancelled' => 'badge-cancelled',
                                            default => 'badge-draft'
                                        };
                                        $statusText = ucfirst($status);
                                        
                                        $paymentStatus = $row['payment_status'] ?? 'unpaid';
                                        $paymentClass = match(strtolower($paymentStatus)) {
                                            'paid' => 'badge-paid',
                                            'partial' => 'badge-partial',
                                            default => 'badge-unpaid'
                                        };
                                        $paymentText = ucfirst($paymentStatus);
                                        ?>
                                        <a href="<?= htmlspecialchars($link) ?>" class="invoice-link <?= ($id == $row['id']) ? 'active' : '' ?>">
                                            <div class="invoice-item">
                                                <div class="invoice-summary">
                                                    <div class="invoice-customer"><?= htmlspecialchars($tenantName) ?></div>
                                                    <div class="invoice-meta">
                                                        <span class="invoice-number"><?= htmlspecialchars($row['invoice_number']) ?></span> •
                                                        <span class="invoice-date">Issued: <?= $invoiceDate ?></span> •
                                                        <span class="invoice-date">Due: <?= $dueDate ?></span>
                                                    </div>
                                                    <div class="invoice-status">
                                                        <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                                                        <span class="status-badge <?= $paymentClass ?>"><?= $paymentText ?></span>
                                                    </div>
                                                </div>
                                                <div class="invoice-amount">Ksh <?= $amount ?></div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </aside>

                        <!-- RIGHT: INVOICE DETAILS -->
                        <main class="viewer">
                            <?php if (!$id): ?>
                                <div class="placeholder">Select an invoice to view its details</div>
                            <?php else: ?>
                                <?php
                                // Fetch invoice details with tenant information
                                $info = $pdo->prepare("
                                    SELECT
                                        i.*,
                                        CONCAT(t.first_name, ' ', COALESCE(t.middle_name, ''), ' ', t.last_name) AS tenant_name,
                                        t.email AS tenant_email,
                                        t.phone AS tenant_phone,
                                        t.account_no
                                    FROM invoice i
                                    LEFT JOIN tenants t ON t.id = i.tenant_id
                                    WHERE i.id = ?
                                ");
                                $info->execute([$id]);
                                $inv = $info->fetch(PDO::FETCH_ASSOC);
                                
                                if (!$inv) {
                                    echo '<div class="placeholder">Invoice not found.</div>';
                                } else {
                                    // Fetch line items from invoice_items table
                                    try {
                                        $itemsStmt = $pdo->prepare("
                                            SELECT 
                                                ii.account_code,
                                                ca.account_name, 
                                                ii.paid_for as description, 
                                                ii.quantity, 
                                                ii.unit_price, 
                                                ii.tax_type, 
                                                ii.tax_amount as taxes, 
                                                ii.total_price as total
                                            FROM invoice_items ii
                                            LEFT JOIN chart_of_accounts ca 
                                                ON ii.account_code = ca.account_code
                                            WHERE ii.invoice_id = ?
                                        ");
                                        $itemsStmt->execute([$id]);
                                        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
                                    } catch (Exception $e) {
                                        $items = [];
                                    }
                                    
                                    $lineRows = '';
                                    $subTotal = 0;
                                    $vatTotal = 0;
                                    $grandTotal = 0;
                                    
                                    if (!empty($items)) {
                                        foreach ($items as $item) {
                                            $qty = (float)$item['quantity'];
                                            $price = (float)$item['unit_price'];
                                            $tax = (float)$item['taxes'];
                                            $lineTotal = (float)$item['total'];
                                            $vatLabel = $item['tax_type'] ?? 'N/A';
                                            
                                            $subTotal += $qty * $price;
                                            $vatTotal += $tax;
                                            $grandTotal += $lineTotal;
                                            
                                            $lineRows .= "<tr>
                                                <td>" . htmlspecialchars($item['account_name'] ?? 'N/A') . "</td>
                                                <td>" . htmlspecialchars($item['description']) . "</td>
                                                <td class='text-end'>{$qty}</td>
                                                <td class='text-end'>KES " . number_format($price, 2) . "</td>
                                                <td class='text-end'>" . htmlspecialchars($vatLabel) . "</td>
                                                <td class='text-end'>KES " . number_format($tax, 2) . "</td>
                                                <td class='text-end'>KES " . number_format($qty * $price, 2) . "</td>
                                            </tr>";
                                        }
                                    } else {
                                        // If no items, use invoice totals from main table
                                        $subTotal = (float)($inv['subtotal'] ?? 0);
                                        $vatTotal = (float)($inv['taxes'] ?? 0);
                                        $grandTotal = (float)($inv['total'] ?? 0);
                                        $lineRows = "<tr><td colspan='7' class='text-center'>No line items found</td></tr>";
                                    }
                                    
                                    $paymentStatus = strtolower($inv['payment_status'] ?? 'unpaid');
                                    $paymentLabelClass = '';
                                    $paymentLabelText = strtoupper($paymentStatus);
                                    
                                    if ($paymentStatus === 'paid') {
                                        $paymentLabelClass = 'diagonal-paid-label';
                                    } elseif ($paymentStatus === 'partial') {
                                        $paymentLabelClass = 'diagonal-partially-paid-label';
                                    } else {
                                        $paymentLabelClass = 'diagonal-unpaid-label';
                                    }
                                ?>
                                <div class="mb-3">
                                    <button type="button" class="btn me-2" 
                                            style="color: #FFC107; background-color: #00192D;" 
                                            onclick="printInvoice()">
                                        <i class="bi bi-printer-fill"></i> Print Invoice
                                    </button>
                                    <button type="button" class="btn" 
                                            style="color: #FFC107; background-color: #00192D;" 
                                            onclick="generatePDF()">
                                        <i class="bi bi-download"></i> Download PDF
                                    </button>
                                </div>
                                
                                <div id="printArea">
                                    <div class="invoice-card">
                                        <!-- Header -->
                                        <div class="d-flex justify-content-between align-items-start mb-3 position-relative" style="overflow: hidden;">
                                            <div><img src="expenseLogo6.png" alt="JengoPay Logo" class="expense-logo"></div>
                                            <div class="<?= $paymentLabelClass ?>"><?= $paymentLabelText ?></div>
                                            <div class="text-end" style="background-color: #f0f0f0; padding: 10px; border-radius: 8px;">
                                                <strong>Silver Spoon Towers</strong><br>
                                                50303 Nairobi, Kenya<br>
                                                silver@gmail.com<br>
                                                +254 700 123456
                                            </div>
                                        </div>

                                        <!-- Invoice Info -->
                                        <div class="row mb-4">
                                            <div class="col-6">
                                                <h6 class="mb-2"><strong><?= htmlspecialchars($inv['tenant_name'] ?? 'N/A') ?></strong></h6>
                                                <div class="tenant-details">
                                                    <?php if (!empty($inv['tenant_email'])): ?>
                                                        <div><strong><?= htmlspecialchars($inv['tenant_email']) ?></strong></div>
                                                    <?php else: ?>
                                                        <div><strong>No email provided</strong></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($inv['tenant_phone'])): ?>
                                                        <div><strong><?= htmlspecialchars($inv['tenant_phone']) ?></strong></div>
                                                    <?php else: ?>
                                                        <div><strong>No phone provided</strong></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($inv['account_no'])): ?>
                                                        <p><strong>Account: <?= htmlspecialchars($inv['account_no']) ?></strong></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h3><strong><?= htmlspecialchars($inv['invoice_no']) ?></strong></h3>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4 rounded-2 d-flex justify-content-between align-items-center"
                                            style="border: 1px solid #FFC107; padding: 10px; background-color: #FFF4CC;">
                                            <div class="d-flex flex-column">
                                                <span class="mb-1"><b>Invoice Date</b></span>
                                                <p class="m-0"><?= date('d/m/Y', strtotime($inv['invoice_date'])) ?></p>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="mb-1"><b>Due Date</b></span>
                                                <p class="m-0"><?= date('d/m/Y', strtotime($inv['due_date'])) ?></p>
                                            </div>
                                            <div></div>
                                        </div>

                                        <!-- Items Table -->
                                        <div class="table-responsive mb-4">
                                            <table class="table table-striped table-bordered rounded-2 table-sm thick-bordered-table">
                                                <thead class="table">
                                                    <tr class="custom-th">
                                                        <th>Item</th>
                                                        <th>Description</th>
                                                        <th class="text-end">Qty</th>
                                                        <th class="text-end">Unit Price</th>
                                                        <th class="text-end">VAT</th>
                                                        <th class="text-end">Taxes</th>
                                                        <th class="text-end">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?= $lineRows ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Totals -->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="terms-box">
                                                    <strong>Note:</strong><br>
                                                    <?= !empty($inv['notes']) ? htmlspecialchars($inv['notes']) : 'Thank you for your business!' ?>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <table class="table table-borderless table-sm text-end mb-0">
                                                    <tr>
                                                        <th>Subtotal:</th>
                                                        <td>KES <?= number_format($subTotal, 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>VAT (16%):</th>
                                                        <td>KES <?= number_format($vatTotal, 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Amount:</th>
                                                        <td><strong>KES <?= number_format($grandTotal, 2) ?></strong></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-center small text-muted">Thank you for your business!</div>
                                </div>
                                <?php
                                }
                                ?>
                            <?php endif; ?>
                        </main>
                    </div>
                </div>
            </div>
        </main>
        <!--end::App Main-->

        <!--begin::Footer-->
     
        <!-- end::footer -->
    </div>
    <!--end::App Wrapper-->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="../../js/adminlte.js"></script>
    
    <script>
    function generatePDF() {
        const element = document.getElementById('printArea');
        const printArea = element.cloneNode(true);
        
        // Remove the first column for cleaner PDF
        let headers = printArea.querySelectorAll("table thead tr th");
        if (headers.length > 0) {
            headers[0].remove();
        }

        let rows = printArea.querySelectorAll("table tbody tr");
        rows.forEach(row => {
            if (row.cells.length > 0) {
                row.deleteCell(0);
            }
        });

        const opt = {
            margin: 10,
            filename: 'invoice_<?= isset($inv) ? htmlspecialchars($inv['invoice_no']) : 'invoice' ?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(printArea).save();
    }

    function printInvoice() {
        let printArea = document.getElementById("printArea").cloneNode(true);

        // Remove the first column for printing
        let headers = printArea.querySelectorAll("table thead tr th");
        if (headers.length > 0) {
            headers[0].remove();
        }

        let rows = printArea.querySelectorAll("table tbody tr");
        rows.forEach(row => {
            if (row.cells.length > 0) {
                row.deleteCell(0);
            }
        });

        let printWindow = window.open("", "", "width=900,height=650");
        printWindow.document.write(`
            <html>
            <head>
                <title>Invoice Print</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    ${document.querySelector("style") ? document.querySelector("style").innerHTML : ""}
                </style>
            </head>
            <body onload="window.print(); window.close();">
                ${printArea.innerHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
    }

</body>
</html>