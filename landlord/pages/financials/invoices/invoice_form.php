<?php
include '../../db/connect.php';

// ===============================
// CHECK IF EDIT MODE
// ===============================
$editMode = false;
$invoiceData = null;
$invoiceItems = [];

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $editMode = true;
    $invoiceId = $_GET['edit'];
    
    // Fetch invoice main data
    $stmt = $pdo->prepare("
        SELECT i.*, t.first_name, t.middle_name, t.last_name, t.building
        FROM invoice i 
        LEFT JOIN tenants t ON i.tenant_id = t.id 
        WHERE i.id = ?
    ");
    $stmt->execute([$invoiceId]);
    $invoiceData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($invoiceData) {
        // Construct full tenant name
        $tenantFullName = $invoiceData['first_name'];
        if (!empty($invoiceData['middle_name'])) {
            $tenantFullName .= ' ' . $invoiceData['middle_name'];
        }
        $tenantFullName .= ' ' . $invoiceData['last_name'];
        
        // Add tenant name to invoiceData for easy access
        $invoiceData['tenant_full_name'] = $tenantFullName;
        
        // Fetch invoice items
        $stmt = $pdo->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
        $stmt->execute([$invoiceId]);
        $invoiceItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// ===============================
// INVOICE NUMBER GENERATION (Only for new invoices)
// ===============================
$invoiceNumber = '';
if (!$editMode) {
    $isDraft = isset($_POST['status']) && $_POST['status'] === 'draft';
    $prefix = $isDraft ? 'DFT' : 'INV';
    
    $stmt = $pdo->prepare("SELECT invoice_number FROM invoice WHERE invoice_number LIKE ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$prefix . '%']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && preg_match('/' . $prefix . '(\d+)/', $row['invoice_number'], $matches)) {
        $lastNumber = (int)$matches[1];
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    $invoiceNumber = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
} else {
    $invoiceNumber = $invoiceData['invoice_number'] ?? '';
}

// ===============================
// FETCH BUILDINGS
// ===============================
try {
    $stmt = $pdo->prepare("SELECT id, building_name FROM buildings ORDER BY building_name ASC");
    $stmt->execute();
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching buildings: " . $e->getMessage());
    $buildings = [];
}

// ===============================
// FETCH TENANTS
// ===============================
try {
    $stmt = $pdo->prepare("
        SELECT id, first_name, middle_name, last_name, main_contact, email, account_no, building
        FROM tenants
        WHERE status = 'Active'
        ORDER BY first_name ASC
    ");
    $stmt->execute();
    $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching tenants: " . $e->getMessage());
    $tenants = [];
}

// ===============================
// FETCH ACCOUNT ITEMS
// ===============================
$stmt = $pdo->prepare("
  SELECT account_code, account_name
  FROM chart_of_accounts
  WHERE account_type = 'Revenue'
  ORDER BY account_name ASC
");
$stmt->execute();
$accountItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
/* Enhanced Form Styles */
.form-section {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 18px;
    color: #1a365d;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e2e8f0;
    font-weight: 600;
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    flex: 1;
}

.form-group.full-width {
    flex: 1 0 100%;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2d3748;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #ffffff;
}

.form-control:focus {
    outline: none;
    border-color: #00192D;
    box-shadow: 0 0 0 3px rgba(0, 25, 45, 0.1);
}

.form-control:read-only {
    background-color: #f7fafc;
    color: #718096;
}

/* Table Styles */
.table-container {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
}

.items-table th {
    background: #f8fafc;
    padding: 16px 12px;
    text-align: left;
    font-weight: 600;
    color: #4a5568;
    font-size: 13px;
    border-bottom: 2px solid #e2e8f0;
}

.items-table td {
    padding: 16px 12px;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: top;
}

.item-row:hover {
    background-color: #f7fafc;
}

/* Button Styles */
.form-actions-center {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.btn-success {
    background-color: #00192D;
    color: #FFC107;
    border: 1px solid #00192D;
}

.btn-success:hover {
    background-color: #002640;
    border-color: #002640;
}

.btn-danger {
    background-color: #e53e3e;
    color: white;
    padding: 8px 12px;
}

.btn-danger:hover {
    background-color: #c53030;
}

.btn-attach {
    background-color: #00192D;
    color: #FFC107;
    padding: 12px 24px;
}

.btn-attach:hover {
    background-color: #002640;
}

.btn-submit {
    background-color: #00192D;
    color: #FFC107;
    padding: 14px 32px;
    font-size: 16px;
}

.btn-submit:hover {
    background-color: #002640;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 25, 45, 0.2);
}

/* File List */
.file-list {
    margin-top: 12px;
}

.file-list .list-group-item {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 8px 12px;
    margin-bottom: 4px;
}

/* Submit Section */
.submit-section {
    background: #f8fafc;
    border-color: #00192D;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 16px;
    }
    
    .form-section {
        padding: 16px;
        margin-bottom: 16px;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .items-table {
        min-width: 800px;
    }
}
</style>
</head>
<body>
<div class="invoice-form-container">
    <div class="form-section">
        <h3 class="section-title">
            <?php echo $editMode ? 'Edit Invoice' : 'Create New Invoice'; ?>
        </h3>
        <form id="myForm" method="POST" 
      action="/Jengopay/landlord/pages/financials/invoices/action/<?php echo $editMode ? 'update_invoice.php' : 'submit_invoice.php'; ?>" 
      enctype="multipart/form-data">
    
    <!-- Hidden field for invoice ID in edit mode -->
    <?php if ($editMode && $invoiceData): ?>
        <input type="hidden" name="invoice_id" value="<?php echo $invoiceData['id']; ?>">
    <?php endif; ?>

    <!-- Basic Information Section -->
    <div class="form-section">
        <h3 class="section-title">Basic Information</h3>
        <div class="form-row">
            <!-- Invoice Number -->
            <div class="form-group">
                <label for="invoice-number">Invoice #</label>
                <input type="text" id="invoice-number" value="<?php echo $invoiceNumber; ?>" class="form-control" readonly>
                <input type="hidden" name="invoice_number" value="<?php echo $invoiceNumber; ?>">
            </div>
            
            <!-- Invoice Date -->
            <div class="form-group">
                <label for="invoice-date">Invoice Date</label>
                <input type="date" id="invoice-date" name="invoice_date" class="form-control" 
                       value="<?php echo $editMode && $invoiceData ? $invoiceData['invoice_date'] : ''; ?>" required>
            </div>
            
            <!-- Due Date -->
            <div class="form-group">
                <label for="due-date">Due Date</label>
                <input type="date" id="due-date" name="due_date" class="form-control" 
                       value="<?php echo $editMode && $invoiceData ? $invoiceData['due_date'] : ''; ?>" required>
            </div>
        </div>

        <!-- Tenant Display -->
        <div class="form-group">
            <label for="tenant-display">Tenant</label>
            <?php
            if ($editMode && $invoiceData) {
                $tenantFullName = $invoiceData['first_name'] . 
                                ($invoiceData['middle_name'] ? ' ' . $invoiceData['middle_name'] : '') . 
                                ' ' . $invoiceData['last_name'];
                $displayValue = $tenantFullName . ' - ' . $invoiceData['building'];
            } else {
                $displayValue = "Tenant information";
            }
            ?>
            <input type="text" class="form-control" 
                   value="<?php echo htmlspecialchars($displayValue); ?>" 
                   readonly>
            <?php if ($editMode && $invoiceData): ?>
                <input type="hidden" name="tenant_id" value="<?php echo $invoiceData['tenant_id']; ?>">
            <?php else: ?>
                <input type="hidden" name="tenant_id" value="">
            <?php endif; ?>
        </div>
    </div>

    <!-- Items Section -->
    <div class="form-section">
        <h3 class="section-title">Invoice Items</h3>
        <div class="table-container">
            <table class="items-table" id="itemsTable">
                <thead>
                    <tr>
                        <th>Item (Service)</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Taxes</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    <?php if ($editMode && !empty($invoiceItems)): ?>
                        <?php foreach ($invoiceItems as $item): ?>
                            <tr class="item-row">
                                <td>
                                    <select name="account_item[]" class="form-select searchable-select" required>
                                        <option value="" disabled>Select Account Item</option>
                                        <?php foreach ($accountItems as $accountItem): 
                                            $selected = ($item['account_code'] == $accountItem['account_code']) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo htmlspecialchars($accountItem['account_code']); ?>" <?php echo $selected; ?>>
                                                <?php echo htmlspecialchars($accountItem['account_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="description[]" class="form-control" placeholder="Description" rows="1" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" class="form-control quantity" step="0.01" 
                                           value="<?php echo $item['quantity']; ?>" required>
                                </td>
                                <td>
                                    <input type="number" name="unit_price[]" class="form-control unit-price" step="0.01" 
                                           value="<?php echo $item['unit_price']; ?>" required>
                                </td>
                                <td>
                                    <select name="vat_type[]" class="form-select vat-option" required>
                                        <option value="" disabled>Select Option</option>
                                        <option value="inclusive" <?php echo $item['vat_type'] == 'inclusive' ? 'selected' : ''; ?>>VAT 16% Inclusive</option>
                                        <option value="exclusive" <?php echo $item['vat_type'] == 'exclusive' ? 'selected' : ''; ?>>VAT 16% Exclusive</option>
                                        <option value="zero" <?php echo $item['vat_type'] == 'zero' ? 'selected' : ''; ?>>Zero Rated</option>
                                        <option value="exempted" <?php echo $item['vat_type'] == 'exempted' ? 'selected' : ''; ?>>Exempted</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="total[]" class="form-control total" 
                                           value="<?php echo number_format($item['total_amount'], 2); ?>" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Empty row for new invoice -->
                        <tr class="item-row">
                            <td>
                                <select name="account_item[]" class="form-select searchable-select" required>
                                    <option value="" disabled selected>Select Account Item</option>
                                    <?php foreach ($accountItems as $item): ?>
                                        <option value="<?php echo htmlspecialchars($item['account_code']); ?>">
                                            <?php echo htmlspecialchars($item['account_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <textarea name="description[]" class="form-control" placeholder="Description" rows="1" required></textarea>
                            </td>
                            <td>
                                <input type="number" name="quantity[]" class="form-control quantity" step="0.01" required>
                            </td>
                            <td>
                                <input type="number" name="unit_price[]" class="form-control unit-price" step="0.01" required>
                            </td>
                            <td>
                                <select name="vat_type[]" class="form-select vat-option" required>
                                    <option value="" disabled selected>Select Option</option>
                                    <option value="inclusive">VAT 16% Inclusive</option>
                                    <option value="exclusive">VAT 16% Exclusive</option>
                                    <option value="zero">Zero Rated</option>
                                    <option value="exempted">Exempted</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="total[]" class="form-control total" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm delete-btn">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="form-actions-center">
            <button type="button" class="btn btn-success add-btn" id="addMoreBtn">
                <i class="fa fa-plus"></i> ADD MORE ITEMS
            </button>
        </div>
    </div>

    <!-- Additional Information Section -->
    <div class="form-section">
        <h3 class="section-title">Additional Information</h3>
        <div class="form-row">
            <div class="form-group full-width">
                <label for="notes">Notes (Optional)</label>
                <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Thank you for your business!"><?php echo $editMode && $invoiceData ? htmlspecialchars($invoiceData['notes']) : ''; ?></textarea>
            </div>
        </div>
    </div>

    <!-- Attachments Section -->
    <div class="form-section">
        <h3 class="section-title">Attachments</h3>
        <div class="form-row">
            <div class="form-group full-width">
                <input type="file" id="fileInput" name="attachment[]" multiple style="display: none;">
                <button type="button" class="btn btn-attach" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-paperclip"></i> Attach Files
                </button>
                <div id="fileList" class="file-list"></div>
            </div>
        </div>
    </div>

    <!-- Submit Section -->
    <div class="form-section submit-section">
        <div class="form-actions-center">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-share-square"></i> 
                <?php echo $editMode ? 'Update Invoice' : 'Save & Send Invoice'; ?>
            </button>
        </div>
    </div>
        </form>
    </div>
</div>
                                    <!-- Add this hidden file input if you want actual file attachment -->
<!-- <input type="file" id="fileInput" style="display: none;"> -->
                                    <!-- <div class="action-right"> -->
                                        <!-- <button type="submit" style="background-color: #00192D; color: #FFC107; padding: 8px 16px; border: none; border-radius: 4px;">
                                            <i class="fas fa-envelope"></i>
                                            Save&Send
                                        </button> -->
                                    <!-- </div> -->


                            </form>
                        </div>
                    </div>


                    <script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('/Jengopay/landlord/pages/financials/invoices/action/get_tenants.php')
        .then(response => response.json())
        .then(data => {
            const tenantSelect = document.getElementById('customer');
            tenantSelect.innerHTML = '<option value="">Select Tenant</option>';

            data.forEach(tenant => {
                let fullName = tenant.first_name + ' ' + (tenant.middle_name ? tenant.middle_name + ' ' : '') + tenant.last_name;
                let option = document.createElement('option');
                option.value = tenant.id;
                option.textContent = fullName + " - " + tenant.building;
                tenantSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading tenants:', error));
});
</script>



                    <script>
document.addEventListener("DOMContentLoaded", function() {
  // Open modal and load payments
  document.getElementById("paymentsHistoryModal").addEventListener("show.bs.modal", function () {
    loadPayments(); // default load without filters
  });

  // 🔹 Apply Filters button
  document.getElementById("applyFilters").addEventListener("click", function () {
    loadPayments();
  });

  // 🔹 Load payments (with optional filters)
  function loadPayments() {
    let month = document.getElementById("filterMonth").value;
    let method = document.getElementById("filterMethod").value;

    fetch("/Jengopay/landlord/pages/financials/invoices/get_payments.php?month=" 
          + encodeURIComponent(month) + "&method=" + encodeURIComponent(method))
      .then(res => res.json())
      .then(data => {
        let tbody = document.querySelector("#paymentsTable tbody");
        tbody.innerHTML = "";

        if (!data || data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="6" class="text-center">No records found</td></tr>`;
          return;
        }

        data.forEach(p => {
          tbody.innerHTML += `
            <tr>
              <td>${p.tenant}</td>
              <td>Ksh ${parseFloat(p.amount).toLocaleString()}</td>
              <td>${p.payment_method}</td>
              <td>${p.payment_date}</td>
              <td>${p.status}</td>
              <td>
                <button class="btn btn-sm btn-warning edit-payment" 
                        data-id="${p.id}"
                        data-amount="${p.amount}"
                        data-tenant="${p.tenant}"
                        data-date="${p.payment_date}"
                        data-method="${p.payment_method}"
                        data-ref="${p.reference_number}">
                  <i class="fas fa-edit"></i> Edit
                </button>
              </td>
            </tr>`;
        });

        // Re-bind edit buttons
        document.querySelectorAll(".edit-payment").forEach(btn => {
          btn.addEventListener("click", function () {
            openEdit(
              this.dataset.id,
              this.dataset.amount,
              this.dataset.tenant,
              this.dataset.date,
              this.dataset.method,
              this.dataset.ref
            );
          });
        });
      });
  }

  // 🔹 Open edit modal
  window.openEdit = function(id, amount, tenant, payment_date, method, ref) {
    document.getElementById("editPaymentId").value = id;
    document.getElementById("editAmount").value = amount;
    document.getElementById("tenantName").value = tenant;
    document.getElementById("paymentDate").value = payment_date;
    document.getElementById("paymentMethod").value = method;
    document.getElementById("referenceNumber").value = ref;
    new bootstrap.Modal(document.getElementById("editPaymentModal")).show();
  };

  // 🔹 Submit edit form
  document.getElementById("editPaymentForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let amount = document.getElementById("editAmount").value;
    if (!confirm(`Are you sure you want to record this amount: KES ${amount}?`)) {
      return;
    }

    let formData = new FormData(this);

    fetch("/Jengopay/landlord/pages/financials/invoices/update_payment.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(result => {
      if (result.success) {
        alert("✅ Payment updated successfully!");
        bootstrap.Modal.getInstance(document.getElementById("editPaymentModal")).hide();
        loadPayments(); // refresh table with current filters
      } else {
        alert("❌ Update failed: " + result.message);
      }
    })
    .catch(err => {
      console.error("Error updating payment:", err);
    });
  });
});
</script>

    <script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';

    if (this.files.length > 0) {
        const list = document.createElement('ul');
        list.className = 'list-group';

        for (let i = 0; i < this.files.length; i++) {
            const item = document.createElement('li');
            item.className = 'list-group-item';
            item.textContent = this.files[i].name;
            list.appendChild(item);
        }

        fileList.appendChild(list);
    } else {
        fileList.textContent = 'No files selected';
    }
});
</script>


<!-- JS TOGGLE -->
<script>
  document.getElementById("paymentsToggle").addEventListener("click", function() {
    const container = document.getElementById("paymentsContainer");
    container.style.display = (container.style.display === "none" || container.style.display === "") ? "block" : "none";
  });
</script>


<!-- <script>
  document.addEventListener("DOMContentLoaded", function () {
    const filterBtn = document.getElementById("filterBtn");
    const searchInput = document.getElementById("searchInput");
    const applyFilter = document.getElementById("applyFilter");
    const statusFilter = document.getElementById("statusFilter");
    const paymentStatusFilter = document.getElementById("paymentStatusFilter");

    // Open filter modal
    filterBtn.addEventListener("click", function () {
        new bootstrap.Modal(document.getElementById("filterModal")).show();
    });

    // Apply filters
    applyFilter.addEventListener("click", function () {
        const statusValue = statusFilter.value.toLowerCase();
        const paymentValue = paymentStatusFilter.value.toLowerCase();
        filterInvoices(statusValue, paymentValue, searchInput.value.toLowerCase());
        bootstrap.Modal.getInstance(document.getElementById("filterModal")).hide();
    });

    // Live search
    searchInput.addEventListener("input", function () {
        filterInvoices(statusFilter.value.toLowerCase(), paymentStatusFilter.value.toLowerCase(), this.value.toLowerCase());
    });

    function filterInvoices(status, paymentStatus, searchText) {
        document.querySelectorAll(".invoice-item:not(.invoice-header)").forEach(item => {
            const invoiceStatus = item.querySelector(".invoice-status span")?.innerText.toLowerCase() || "";
            const paymentStatusText = item.querySelectorAll(".invoice-status span")[1]?.innerText.toLowerCase() || "";
            const tenantName = item.querySelector(".invoice-customer")?.innerText.toLowerCase() || "";
            const invoiceNumber = item.querySelector(".invoice-number")?.innerText.toLowerCase() || "";

            const matchStatus = !status || invoiceStatus.includes(status);
            const matchPayment = !paymentStatus || paymentStatusText.includes(paymentStatus);
            const matchSearch = !searchText || tenantName.includes(searchText) || invoiceNumber.includes(searchText);

            item.style.display = matchStatus && matchPayment && matchSearch ? "" : "none";
        });
    }
});

</script> -->


<script>
  function filterInvoices(status, paymentStatus, searchText) {
    document.querySelectorAll(".invoice-item:not(.invoice-header)").forEach(item => {
        const invoiceStatus = item.querySelector(".invoice-status span")?.innerText.toLowerCase() || "";
        const paymentStatusText = item.querySelectorAll(".invoice-status span")[1]?.innerText.toLowerCase() || "";
        const tenantName = item.querySelector(".invoice-customer")?.innerText.toLowerCase() || "";
        const invoiceNumber = item.querySelector(".invoice-number")?.innerText.toLowerCase() || "";

        // ✅ Capture Paid Amount text (second .invoice-status may contain it, or inside button text)
        const paidAmountText = paymentStatusText.match(/kes\s*[\d,]+(\.\d+)?/i)?.[0].toLowerCase() || "";

        const matchStatus = !status || invoiceStatus.includes(status);
        const matchPayment = !paymentStatus || paymentStatusText.includes(paymentStatus);
        const matchSearch = !searchText
            || tenantName.includes(searchText)
            || invoiceNumber.includes(searchText)
            || paymentStatusText.includes(searchText)
            || paidAmountText.includes(searchText); // ✅ Added

        item.style.display = matchStatus && matchPayment && matchSearch ? "" : "none";
    });
}

</script>


<script>
document.getElementById("applyFilters").addEventListener("click", function () {
  let month = document.getElementById("filterMonth").value;
  let method = document.getElementById("filterMethod").value;

  fetch("/Jengopay/landlord/pages/financials/invoices/get_payments.php?month=" 
        + encodeURIComponent(month) + "&method=" + encodeURIComponent(method))
    .then(response => response.json())
    .then(data => {
      let tbody = document.querySelector("#paymentsTable tbody");
      tbody.innerHTML = "";

      if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center">No records found</td></tr>`;
        return;
      }

      data.forEach(row => {
        tbody.innerHTML += `
          <tr>
            <td>${row.tenant || "-"}</td>
            <td>Ksh ${parseFloat(row.amount).toLocaleString()}</td>
            <td>${row.payment_method}</td>
            <td>${row.payment_date}</td>
            <td>${row.status}</td>
            <td>
              <button class="btn btn-sm btn-warning edit-payment" data-id="${row.id}">
                <i class="fas fa-edit">Edit</i>
              </button>
            </td>
          </tr>
        `;
      });

      // ✅ Re-bind edit button click after refreshing table
      document.querySelectorAll(".edit-payment").forEach(btn => {
        btn.addEventListener("click", function () {
          let id = this.getAttribute("data-id");
          loadPaymentForEdit(id); 
          new bootstrap.Modal(document.getElementById("editPaymentModal")).show();
        });
      });
    })
    .catch(err => {
      console.error("Error fetching payments:", err);
    });
});


// ✅ Define how payment details load into edit modal
function loadPaymentForEdit(id) {
  fetch("/Jengopay/landlord/pages/financials/invoices/get_payments.php?id=" + encodeURIComponent(id))
    .then(response => response.json())
    .then(data => {
      if (!data) {
        alert("Could not load payment details.");
        return;
      }

      // ✅ Match modal inputs
      document.getElementById("editPaymentId").value = data.id;
      document.getElementById("invoiceId").value = data.invoice_id;
      document.getElementById("tenantName").value = data.tenant;
      document.getElementById("editAmount").value = data.amount;
      document.getElementById("paymentMethod").value = data.payment_method;
      document.getElementById("paymentDate").value = data.payment_date;
      document.getElementById("referenceNumber").value = data.reference_number;
    })
    .catch(err => {
      console.error("Error loading payment details:", err);
    });
}
</script>




<!-- Add this script to handle the filtering -->
<!-- <script>
document.getElementById('filterBtn').addEventListener('click', function() {
    // Get the table or data you want to filter
    const table = document.querySelector('table'); // Adjust selector as needed

    // Create filter options (you can customize this based on your needs)
    const filterOptions = {
        invoiceNumber: prompt('Filter by Invoice Number (leave blank for all):'),
        tenant: prompt('Filter by Tenant ID (leave blank for all):'),
        accountItem: prompt('Filter by Account Item (leave blank for all):'),
        minAmount: parseFloat(prompt('Minimum Amount (leave blank for no minimum):')) || 0,
        maxAmount: parseFloat(prompt('Maximum Amount (leave blank for no maximum):')) || Infinity
    };

    // Loop through table rows and apply filters
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const invoiceNumber = cells[1].textContent;
        const tenant = cells[2].textContent;
        const accountItem = cells[3].textContent;
        const total = parseFloat(cells[9].textContent);

        const showRow =
            (filterOptions.invoiceNumber === '' || invoiceNumber.includes(filterOptions.invoiceNumber)) &&
            (filterOptions.tenant === '' || tenant.includes(filterOptions.tenant)) &&
            (filterOptions.accountItem === '' || accountItem.includes(filterOptions.accountItem)) &&
            (total >= filterOptions.minAmount) &&
            (total <= filterOptions.maxAmount);

        row.style.display = showRow ? '' : 'none';
    });

    alert('Filter applied!');
});
</script> -->
    <script src="/Jengopay/landlord/pages/financials/invoices/js/invoice.js"></script>
    <!-- <script>
document.addEventListener("DOMContentLoaded", function () {
    const addMoreBtn = document.getElementById("addMoreBtn");
    const itemsBody = document.getElementById("itemsBody");

    addMoreBtn.addEventListener("click", function () {
        const newRow = document.createElement("tr");

        newRow.innerHTML = `
            <td>
                <select name="account_item[]" class="select-account searchable-select" required>
                    <option value="" disabled selected>Select Account Item</option>
                    <?php foreach ($accountItems as $item): ?>
                        <option value="<?= htmlspecialchars($item['account_code']) ?>">
                            <?= htmlspecialchars($item['account_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><textarea name="description[]" placeholder="Description" rows="1" required></textarea></td>
            <td><input type="number" name="quantity[]" class="form-control quantity" required></td>
            <td><input type="number" name="unit_price[]" class="form-control unit-price" required></td>
            <td>
                <select name="vat_type[]" class="form-select vat-option" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="inclusive">VAT 16% Inclusive</option>
                    <option value="exclusive">VAT 16% Exclusive</option>
                    <option value="zero">Zero Rated</option>
                    <option value="exempted">Exempted</option>
                </select>
                </td>
             <td><input type="text" name="total[]" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm delete-btn"><i class="fa fa-trash"></i></button></td>
        `;

        itemsBody.appendChild(newRow);
    });

    // Event delegation to delete dynamically added rows
    itemsBody.addEventListener("click", function (e) {
        if (e.target.closest(".delete-btn")) {
            const row = e.target.closest("tr");
            row.remove();
        }
    });
});
</script> -->


<!-- <script>
// Store the original invoices data
let originalInvoices = <?php echo json_encode($invoices); ?>;
let displayedInvoices = [...originalInvoices];

// Toggle filter panel visibility
document.getElementById('filterBtn').addEventListener('click', function() {
    const filterPanel = document.getElementById('filterPanel');
    filterPanel.style.display = filterPanel.style.display === 'none' ? 'block' : 'none';
});

// Array to store active filters
let activeFilters = [];

function applyFilter() {
    const filterField = document.getElementById('filterField');
    const searchInput = document.getElementById('searchInput');

    const selectedField = filterField.value;
    const selectedFieldText = filterField.options[filterField.selectedIndex].text;
    const searchValue = searchInput.value.trim().toLowerCase();

    if (searchValue === '') {
        alert('Please enter a search value');
        return;
    }

    // Add filter to active filters if not already present
    const filterExists = activeFilters.some(filter =>
        filter.field === selectedField && filter.value === searchValue
    );

    if (!filterExists) {
        activeFilters.push({
            field: selectedField,
            fieldText: selectedFieldText,
            value: searchValue
        });
        updateActiveFiltersDisplay();
        filterInvoices();
    }

    // Clear the search input
    searchInput.value = '';
}

function filterInvoices() {
    // Start with all invoices
    let filteredInvoices = [...originalInvoices];

    // Apply each active filter
    activeFilters.forEach(filter => {
        filteredInvoices = filteredInvoices.filter(invoice => {
            switch(filter.field) {
                case 'invoice-number':
                    return invoice.invoice_number.toLowerCase().includes(filter.value);
                case 'invoice-customer':
                    const tenantName = (invoice.tenant_name || 'Unknown Tenant').toLowerCase();
                    return tenantName.includes(filter.value);
                case 'invoice-date':
                    const invoiceDate = invoice.invoice_date === '0000-00-00' ? 'draft' :
                                      dateToFilterString(invoice.invoice_date);
                    return invoiceDate.includes(filter.value);
                case 'due-date':
                    const dueDate = invoice.due_date === '0000-00-00' ? 'not set' :
                                   dateToFilterString(invoice.due_date);
                    return dueDate.includes(filter.value);
                case 'invoice-sub-total':
                    return String(invoice.sub_total).includes(filter.value);
                case 'invoice-taxes':
                    return String(invoice.taxes || 0).includes(filter.value);
                case 'invoice-amount':
                    return String(invoice.total).includes(filter.value);
                case 'invoice-status':
                    return invoice.status.toLowerCase().includes(filter.value);
                case 'payment-status':
                    // Calculate payment status
                    const paidAmount = parseFloat(invoice.paid_amount) || 0;
                    const total = parseFloat(invoice.total) || 0;
                    let paymentStatus = '';

                    if (paidAmount >= total) {
                        paymentStatus = 'paid';
                    } else if (paidAmount > 0) {
                        paymentStatus = 'partial';
                    } else {
                        paymentStatus = 'unpaid';
                        if (invoice.due_date !== '0000-00-00') {
                            const today = new Date();
                            const dueDate = new Date(invoice.due_date);
                            if (today > dueDate) {
                                paymentStatus = 'overdue';
                            }
                        }
                    }
                    return paymentStatus.includes(filter.value);
                default:
                    return true;
            }
        });
    });

    displayedInvoices = filteredInvoices;
    renderFilteredInvoices();
}

function dateToFilterString(dateString) {
    if (dateString === '0000-00-00') return '';
    const date = new Date(dateString);
    return date.toISOString().split('T')[0]; // YYYY-MM-DD format
}

function renderFilteredInvoices() {
    const container = document.getElementById('invoice-items-list');

    // Clear existing items (except header)
    const header = container.querySelector('.invoice-header');
    container.innerHTML = '';
    container.appendChild(header);

    // Render filtered invoices
    displayedInvoices.forEach(invoice => {
        const tenantName = invoice.tenant_name || 'Unknown Tenant';
        const invoiceDate = invoice.invoice_date === '0000-00-00' ? 'Draft' :
                          formatDate(invoice.invoice_date);
        const dueDate = invoice.due_date === '0000-00-00' ? 'Not set' :
                       formatDate(invoice.due_date);

        // Calculate overdue status
        let isOverdue = false;
        let overdueDays = 0;
        if (invoice.due_date != '0000-00-00' && invoice.status != 'paid' && invoice.status != 'cancelled') {
            const today = new Date();
            const dueDateObj = new Date(invoice.due_date);
            if (today > dueDateObj) {
                isOverdue = true;
                overdueDays = Math.floor((today - dueDateObj) / (1000 * 60 * 60 * 24));
            }
        }

        // Determine status badge
        let statusClass = 'badge-';
        let statusText = capitalizeFirstLetter(invoice.status);

        switch (invoice.status) {
            case 'draft':
                statusClass += 'draft';
                break;
            case 'sent':
                statusClass += isOverdue ? 'overdue' : 'sent';
                statusText = isOverdue ? 'Overdue (' + overdueDays + 'd)' : 'Sent';
                break;
            case 'paid':
                statusClass += 'paid';
                break;
            case 'cancelled':
                statusClass += 'cancelled';
                break;
            default:
                statusClass += 'draft';
        }

        // Payment status with amounts
        let paymentStatusClass = 'badge-';
        let paymentStatusText = '';
        const paidAmount = parseFloat(invoice.paid_amount) || 0;
        const totalAmount = parseFloat(invoice.total) || 0;
        const paidFormatted = paidAmount.toFixed(2);
        const totalFormatted = totalAmount.toFixed(2);

        if (paidAmount > 0) {
            if (paidAmount >= totalAmount) {
                paymentStatusClass += 'paid';
                paymentStatusText = 'Paid (KES ' + paidFormatted + ')';
            } else {
                paymentStatusClass += 'partial';
                paymentStatusText = 'Partial (KES ' + paidFormatted + ' of ' + totalFormatted + ')';
            }
        } else {
            paymentStatusClass += 'unpaid';
            paymentStatusText = isOverdue ? 'Overdue (' + overdueDays + 'd)' : 'Unpaid';
        }

        // Create invoice item element
        const invoiceItem = document.createElement('div');
        invoiceItem.className = 'invoice-item';
        invoiceItem.onclick = function() { openInvoiceDetails(invoice.id); };

        invoiceItem.innerHTML = `
            <div class="invoice-checkbox">
                <input type="checkbox" onclick="event.stopPropagation()">
            </div>
            <div class="invoice-number">${escapeHtml(invoice.invoice_number)}</div>
            <div class="invoice-customer" title="${escapeHtml(invoice.description)}">
                ${escapeHtml(tenantName)}
            </div>
            <div class="invoice-date">${invoiceDate}</div>
            <div class="invoice-date${isOverdue ? ' text-danger' : ''}">
                ${dueDate}
            </div>
            <div class="invoice-amount">${numberFormat(invoice.sub_total, 2)}</div>
            <div class="invoice-amount">${escapeHtml(invoice.taxes || '0.00')}</div>
            <div class="invoice-amount">${numberFormat(invoice.total, 2)}</div>
            <div class="invoice-status">
                <span class="status-badge ${statusClass}">${statusText}</span>
            </div>
            <div class="invoice-status">
                <span class="status-badge ${paymentStatusClass}">${paymentStatusText}</span>
                ${(invoice.status !== 'draft' && invoice.status !== 'cancelled' && paidAmount < totalAmount) ? `
                <br>
                <button class="btn pay-btn btn-sm mt-1"
                    onclick="event.stopPropagation(); openPayModal(this)"
                    data-invoice-id="${invoice.id}"
                    data-tenant="${escapeHtml(tenantName)}"
                    data-total="${totalAmount}"
                    data-paid="${paidAmount}"
                    data-balance="${totalAmount - paidAmount}"
                    data-account-item="${escapeHtml(invoice.account_item)}"
                    data-description="${escapeHtml(invoice.description)}">
                    <i class="fas fa-credit-card me-1"></i>
                    ${paidAmount > 0 ? 'Add Payment' : 'Pay'}
                </button>
                ` : ''}
            </div>
            <div class="invoice-actions dropdown">
                <button class="action-btn dropdown-toggle" onclick="event.stopPropagation()" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" onclick="viewInvoice(${invoice.id})">
                        <i class="fas fa-eye me-2"></i>View Details
                    </a></li>
                    ${(invoice.status === 'draft' || (invoice.status === 'sent' && paidAmount == 0)) ? `
                    <li><a class="dropdown-item" href="invoice_edit.php?id=${invoice.id}">
                        <i class="fas fa-edit me-2"></i>Edit Invoice
                    </a></li>
                    ` : ''}
                    <li><hr class="dropdown-divider"></li>
                    ${(invoice.status === 'draft' || invoice.status === 'cancelled') ? `
                    <li><a class="dropdown-item text-danger" href="#" onclick="confirmDeleteInvoice(${invoice.id})">
                        <i class="fas fa-trash-alt me-2"></i>Delete Invoice
                    </a></li>
                    ` : ''}
                    ${(invoice.status !== 'cancelled' && invoice.status !== 'paid') ? `
                    <li><a class="dropdown-item text-danger" href="#" onclick="confirmCancelInvoice(${invoice.id})">
                        <i class="fas fa-ban me-2"></i>Cancel Invoice
                    </a></li>
                    ` : (invoice.status === 'cancelled') ? `
                    <li><a class="dropdown-item" href="#" onclick="restoreInvoice(${invoice.id})">
                        <i class="fas fa-undo me-2"></i>Restore Invoice
                    </a></li>
                    ` : ''}
                </ul>
            </div>
        `;

        container.appendChild(invoiceItem);
    });
}

// Helper functions
function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function numberFormat(number, decimals) {
    if (isNaN(number)) return '0.00';
    return parseFloat(number).toFixed(decimals);
}

function formatDate(dateString) {
    if (dateString === '0000-00-00') return '';
    const date = new Date(dateString);
    const options = { month: 'short', day: 'numeric', year: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function updateActiveFiltersDisplay() {
    const activeFiltersContainer = document.getElementById('activeFilters');
    activeFiltersContainer.innerHTML = '';

    if (activeFilters.length === 0) {
        return;
    }

    const filtersTitle = document.createElement('span');
    filtersTitle.textContent = 'Active Filters: ';
    filtersTitle.style.marginRight = '10px';
    activeFiltersContainer.appendChild(filtersTitle);

    activeFilters.forEach((filter, index) => {
        const filterTag = document.createElement('span');
        filterTag.className = 'badge bg-warning text-dark me-2';
        filterTag.style.cursor = 'pointer';

        const filterText = document.createElement('span');
        filterText.textContent = `${filter.fieldText}: ${filter.value}`;
        filterTag.appendChild(filterText);

        const removeBtn = document.createElement('span');
        removeBtn.className = 'ms-2';
        removeBtn.innerHTML = '&times;';
        removeBtn.onclick = function(e) {
            e.stopPropagation();
            removeFilter(index);
        };
        filterTag.appendChild(removeBtn);

        activeFiltersContainer.appendChild(filterTag);
    });
}

function removeFilter(index) {
    activeFilters.splice(index, 1);
    updateActiveFiltersDisplay();
    filterInvoices();
}

// Initialize by rendering all invoices
document.addEventListener('DOMContentLoaded', function() {
    renderFilteredInvoices();

    // Add keyboard support for the search input
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            applyFilter();
        }
    });
});
</script> -->

<script>
document.getElementById('resetFilter').addEventListener('click', function () {
    document.getElementById('statusFilter').value = '';
    document.getElementById('paymentFilter').value = '';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
});
</script>


</script>

<!-- <script>
// Toggle filter panel visibility
document.getElementById('filterBtn').addEventListener('click', function() {
    const filterPanel = document.getElementById('filterPanel');
    filterPanel.style.display = filterPanel.style.display === 'none' ? 'block' : 'none';
});

// Array to store active filters
let activeFilters = [];

function applyFilter() {
    const filterField = document.getElementById('filterField');
    const searchInput = document.getElementById('searchInput');

    const selectedField = filterField.options[filterField.selectedIndex].text;
    const searchValue = searchInput.value.trim();

    if (searchValue === '') {
        alert('Please enter a search value');
        return;
    }

    // Add filter to active filters if not already present
    const filterExists = activeFilters.some(filter =>
        filter.field === selectedField && filter.value === searchValue
    );

    if (!filterExists) {
        activeFilters.push({
            field: selectedField,
            value: searchValue,
            fieldValue: filterField.value
        });
        updateActiveFiltersDisplay();
    }

    // Here you would typically filter your data/table
    // For now, we'll just log the filters
    console.log('Applying filter:', selectedField, searchValue);

    // Clear the search input
    searchInput.value = '';
}

function updateActiveFiltersDisplay() {
    const activeFiltersContainer = document.getElementById('activeFilters');
    activeFiltersContainer.innerHTML = '';

    if (activeFilters.length === 0) {
        return;
    }

    const filtersTitle = document.createElement('span');
    filtersTitle.textContent = 'Active Filters: ';
    filtersTitle.style.marginRight = '10px';
    activeFiltersContainer.appendChild(filtersTitle);

    activeFilters.forEach((filter, index) => {
        const filterTag = document.createElement('span');
        filterTag.className = 'badge bg-warning text-dark me-2';
        filterTag.style.cursor = 'pointer';

        const filterText = document.createElement('span');
        filterText.textContent = `${filter.field}: ${filter.value}`;
        filterTag.appendChild(filterText);

        const removeBtn = document.createElement('span');
        removeBtn.className = 'ms-2';
        removeBtn.innerHTML = '&times;';
        removeBtn.onclick = function(e) {
            e.stopPropagation();
            removeFilter(index);
        };
        filterTag.appendChild(removeBtn);

        activeFiltersContainer.appendChild(filterTag);
    });
}

function removeFilter(index) {
    activeFilters.splice(index, 1);
    updateActiveFiltersDisplay();

    // Here you would typically reapply the remaining filters
    console.log('Remaining filters:', activeFilters);
}

// Optional: Add keyboard support for the search input
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        applyFilter();
    }
});
</script> -->

<!-- Add this script to handle the filtering -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('filterBtn');
    const invoiceItemsList = document.getElementById('invoice-items-list');
    let originalInvoiceItems = invoiceItemsList.innerHTML;
    let filterModal = null;

    filterBtn.addEventListener('click', function() {
        // Remove existing modal if any
        const existingModal = document.getElementById('filterModal');
        if (existingModal) existingModal.remove();

        const filterModalHTML = `
            <div class="modal fade" id="filterModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Filter Invoices</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select id="filterStatus" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="draft">Draft</option>
                                    <option value="sent">Sent</option>z
                                    <option value="paid">Paid</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Status</label>
                                <select id="filterPaymentStatus" class="form-select">
                                    <option value="">All</option>
                                    <option value="paid">Paid</option>
                                    <option value="partial">Partial</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="overdue">Overdue</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount Range</label>
                                <div class="input-group">
                                    <input type="number" id="filterMinAmount" class="form-control" placeholder="Min" step="0.01">
                                    <span class="input-group-text">to</span>
                                    <input type="number" id="filterMaxAmount" class="form-control" placeholder="Max" step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="resetFilters">Reset</button>
                            <button type="button" class="btn btn-primary" id="applyFilters">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', filterModalHTML);
        filterModal = new bootstrap.Modal(document.getElementById('filterModal'));

        document.getElementById('applyFilters').addEventListener('click', function() {
            const statusFilter = document.getElementById('filterStatus').value;
            const paymentStatusFilter = document.getElementById('filterPaymentStatus').value;
            const minAmount = parseFloat(document.getElementById('filterMinAmount').value) || 0;
            const maxAmount = parseFloat(document.getElementById('filterMaxAmount').value) || Infinity;

            applyFilters(statusFilter, paymentStatusFilter, minAmount, maxAmount);
            filterModal.hide();
        });

        document.getElementById('resetFilters').addEventListener('click', function() {
            invoiceItemsList.innerHTML = originalInvoiceItems;
            filterModal.hide();
        });

        filterModal.show();
    });

    function applyFilters(statusFilter, paymentStatusFilter, minAmount, maxAmount) {
        const items = document.querySelectorAll('.invoice-item:not(.invoice-header)');

        items.forEach(item => {
            // Get status values
            const statusBadge = item.querySelector('.invoice-status .status-badge');
            const status = statusBadge ? statusBadge.textContent.trim().toLowerCase() : '';

            // Get payment status values
            const paymentBadge = item.querySelector('.invoice-status:nth-of-type(2) .status-badge');
            const paymentText = paymentBadge ? paymentBadge.textContent.trim().toLowerCase() : '';

            // Get amount value
            const amountText = item.querySelector('.invoice-amount:nth-of-type(3)')?.textContent || '0';
            const amount = parseFloat(amountText.replace(/[^0-9.]/g, '')) || 0;

            // Status matching
            const statusMatch = !statusFilter ||
                              status.includes(statusFilter) ||
                              (statusFilter === 'sent' && status.includes('overdue'));

            // Payment status matching - more precise logic
            let paymentMatch = true;
            if (paymentStatusFilter) {
                if (paymentStatusFilter === 'paid') {
                    paymentMatch = paymentText.includes('paid') && !paymentText.includes('partial');
                }
                else if (paymentStatusFilter === 'partial') {
                    paymentMatch = paymentText.includes('partial');
                }
                else if (paymentStatusFilter === 'unpaid') {
                    paymentMatch = paymentText.includes('unpaid') && !paymentText.includes('partial');
                }
                else if (paymentStatusFilter === 'overdue') {
                    paymentMatch = paymentText.includes('overdue');
                }
            }

            // Amount matching
            const amountMatch = amount >= minAmount && amount <= maxAmount;

            // Show/hide based on all conditions
            item.style.display = (statusMatch && paymentMatch && amountMatch) ? '' : 'none';
        });
    }
});
</script> -->

<script>
document.getElementById('building').addEventListener('change', function() {
    const buildingId = this.value;
    const tenantSelect = document.getElementById('customer');

    // Reset tenant dropdown
    tenantSelect.innerHTML = '<option value="">Select a Tenant</option>';
    tenantSelect.disabled = true;

    if (buildingId) {
        fetch(`/Jengopay/landlord/pages/financials/invoices/action/fetch_tenants.php?building_id=${buildingId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(tenant => {
                        const option = document.createElement('option');
                        option.value = tenant.unit_number;
                        option.textContent = `${tenant.first_name} ${tenant.last_name} - ${tenant.unit_number} (${tenant.structure_type})`;
                        tenantSelect.appendChild(option);
                    });
                    tenantSelect.disabled = false;
                } else {
                    const opt = document.createElement('option');
                    opt.textContent = 'No occupied tenants found';
                    tenantSelect.appendChild(opt);
                }
            })
            .catch(error => console.error('Error fetching tenants:', error));
    }
});
</script>



<script>
  document.addEventListener("DOMContentLoaded", function () {
    const addMoreBtn = document.getElementById("addMoreBtn");
    const itemsBody = document.getElementById("itemsBody");

    function formatNumber(num) {
      return num.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function calculateRow(row) {
      const unitInput = row.querySelector(".unit-price");
      const quantityInput = row.querySelector(".quantity");
      const vatSelect = row.querySelector(".vat-option");
      const totalInput = row.querySelector(".total");

      const unitPrice = parseFloat(unitInput?.value) || 0;
      const quantity = parseFloat(quantityInput?.value) || 0;
      let subtotal = unitPrice * quantity;

      let vatAmount = 0;
      let total = subtotal;
      const vatType = vatSelect?.value;

      if (vatType === "inclusive") {
        subtotal = subtotal / 1.16;
        vatAmount = total - subtotal;
      } else if (vatType === "exclusive") {
        vatAmount = subtotal * 0.16;
        total += vatAmount;
      } else if (vatType === "zero" || vatType === "exempted") {
        vatAmount = 0;
        total = subtotal;
      }

      totalInput.value = formatNumber(total);
      return { subtotal, vatAmount, total, vatType };
    }

    function updateTotalAmount() {
      let subtotalSum = 0, taxSum = 0, grandTotal = 0;
      let vat16Used = false, vat0Used = false, exemptedUsed = false;

      document.querySelectorAll("#itemsBody tr").forEach(row => {
        if (row.querySelector(".unit-price")) {
          const { subtotal, vatAmount, total, vatType } = calculateRow(row);
          subtotalSum += subtotal;
          taxSum += vatAmount;
          grandTotal += total;

          if (vatType === "inclusive" || vatType === "exclusive") {
            vat16Used = true;
          } else if (vatType === "zero") {
            vat0Used = true;
          } else if (vatType === "exempted") {
            exemptedUsed = true;
          }
        }
      });

      createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, vat16Used, vat0Used, exemptedUsed });
    }

    function createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, vat16Used, vat0Used, exemptedUsed }) {
      let summaryTable = document.querySelector(".summary-table");

      if (!summaryTable) {
        summaryTable = document.createElement("table");
        summaryTable.className = "summary-table table table-bordered";
        summaryTable.style = "width: 20%; float: right; font-size: 0.8rem; margin-top: 10px;";
        summaryTable.innerHTML = `<tbody></tbody>`;
        document.querySelector(".items-table").after(summaryTable);
      }

      const tbody = summaryTable.querySelector("tbody");
      tbody.innerHTML = `
        <tr>
          <th style="width: 50%; padding: 5px; text-align: left;">Sub-total</th>
          <td><input type="text" class="form-control" value="${formatNumber(subtotalSum)}" readonly style="padding: 5px;"></td>
        </tr>
        ${vat16Used ? `
        <tr>
          <th style="padding: 5px;">VAT 16%</th>
          <td><input type="text" class="form-control" value="${formatNumber(taxSum)}" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        ${vat0Used ? `
        <tr>
          <th style="padding: 5px;">VAT 0%</th>
          <td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        ${exemptedUsed ? `
        <tr>
          <th style="padding: 5px;">Exempted</th>
          <td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
        </tr>` : ''}
        <tr>
          <th style="padding: 5px;">Total</th>
          <td><input type="text" class="form-control" value="${formatNumber(grandTotal)}" readonly style="padding: 5px;"></td>
        </tr>
      `;
    }

    function attachEvents(row) {
      ["input", "change"].forEach(evt => {
        row.querySelectorAll(".unit-price, .quantity, .vat-option").forEach(input => {
          input.addEventListener(evt, updateTotalAmount);
        });
      });
    }

    addMoreBtn.addEventListener("click", function () {
      const newRow = document.createElement("tr");

      newRow.innerHTML = `
       <td style="min-width: 180px;">
    <select name="account_item[]" class="form-select searchable-select" required>
      <option value="" disabled selected>Select Account Item</option>
      <?php foreach ($accountItems as $item): ?>
        <option value="<?= htmlspecialchars($item['account_code']) ?>">
          <?= htmlspecialchars($item['account_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </td>

  <td style="min-width: 200px;">
    <textarea name="description[]" class="form-control" placeholder="Description" rows="1" required></textarea>
  </td>

  <td style="min-width: 100px;">
    <input type="number" name="quantity[]" class="form-control quantity" step="0.01" required>
  </td>

  <td style="min-width: 120px;">
    <input type="number" name="unit_price[]" class="form-control unit-price" step="0.01" required>
  </td>

  <td style="min-width: 180px;">
    <select name="vat_type[]" class="form-select vat-option" required>
      <option value="" disabled selected>Select Option</option>
      <option value="inclusive">VAT 16% Inclusive</option>
      <option value="exclusive">VAT 16% Exclusive</option>
      <option value="zero">Zero Rated</option>
      <option value="exempted">Exempted</option>
    </select>
  </td>

  <td style="min-width: 120px;">
    <input type="text" name="total[]" class="form-control total" readonly>
  </td>

  <td style="min-width: 50px; text-align: center;">
    <button type="button" class="btn btn-danger btn-sm delete-btn">
      <i class="fa fa-trash"></i>
    </button>
  </td>
      `;

      itemsBody.appendChild(newRow);
      attachEvents(newRow);
      updateTotalAmount();
    });

    // Delete row
    itemsBody.addEventListener("click", function (e) {
      if (e.target.closest(".delete-btn")) {
        e.target.closest("tr").remove();
        updateTotalAmount();
      }
    });

    // Attach events to any existing rows
    document.querySelectorAll("#itemsBody tr").forEach(attachEvents);
    updateTotalAmount();
  });
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const itemsBody = document.getElementById("itemsBody");

    // Trigger calculation on input changes
    itemsBody.addEventListener("input", function (e) {
        if (e.target.classList.contains("quantity") ||
            e.target.classList.contains("unit-price") ||
            e.target.classList.contains("vat-option")) {

            const row = e.target.closest("tr");
            calculateRowTotal(row);
        }
    });

    // Recalculate total when tax option changes
    itemsBody.addEventListener("change", function (e) {
        if (e.target.classList.contains("vat-option")) {
            const row = e.target.closest("tr");
            calculateRowTotal(row);
        }
    });

    function calculateRowTotal(row) {
        const qty = parseFloat(row.querySelector(".quantity")?.value) || 0;
        const price = parseFloat(row.querySelector(".unit-price")?.value) || 0;
        const tax = row.querySelector(".vat-option")?.value;

        let total = qty * price;

        if (tax === "exclusive") {
            total *= 1.16;
        } // inclusive means total is already inclusive
        // zero & exempted = no tax change

        row.querySelector(".total").value = total.toFixed(2);
    }
});
</script>


    <script>
        // Edit Invoice
        // function editInvoice(invoiceId) {
        //     // Redirect to edit page or open edit modal
        //     window.location.href = 'edit_invoice.php?id=' + invoiceId;
        // }

        // Confirm Delete Invoice
       // Confirm Delete Invoice (Soft Delete with 30-day retention)
       function confirmDeleteInvoice(invoiceId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This invoice will be marked for deletion and permanently removed after 30 days (only allowed for drafts or cancelled invoices with no payments).",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/Jengopay/landlord/pages/financials/invoices/action/delete_invoice.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + encodeURIComponent(invoiceId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Deleted!',
                        'The invoice has been marked for deletion.',
                        'success'
                    ).then(() => location.reload());
                } else {
                    Swal.fire(
                        'Not Allowed',
                        data.message || 'This invoice cannot be deleted.',
                        'warning'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Error!',
                    'Request failed: ' + error,
                    'error'
                );
            });
        }
    });
}


        // Delete Invoice
        function deleteInvoice(invoiceId) {
            fetch('/Jengopay/landlord/pages/financials/invoices/delete_invoice.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + invoiceId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Deleted!',
                            'Invoice has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload(); // Refresh the page
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'Failed to delete invoice.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the invoice.',
                        'error'
                    );
                });
        }

        // Confirm Cancel Invoice
        function confirmCancelInvoice(invoiceId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will cancel the invoice and mark it as non-payable.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    cancelInvoice(invoiceId);
                }
            });
        }

        // Cancel Invoice - Updated version
        function cancelInvoice(invoiceId) {
            fetch('/Jengopay/landlord/pages/financials/invoices/action/cancel_invoice.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + invoiceId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Cancelled!',
                            'Invoice has been cancelled.',
                            'success'
                        ).then(() => {
                            // Update the UI without full page reload
                            updateInvoiceStatus(invoiceId, 'cancelled');
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'Failed to cancel invoice.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'An error occurred while cancelling the invoice.',
                        'error'
                    );
                });
        }

        // Function to update invoice status visually
        function updateInvoiceStatus(invoiceId, newStatus) {
            const invoiceItem = document.querySelector(`.invoice-item[data-id="${invoiceId}"]`);
            if (!invoiceItem) {
                location.reload(); // Fallback if element not found
                return;
            }

            // Update status badge
            const statusBadge = invoiceItem.querySelector('.invoice-status .status-badge');
            if (statusBadge) {
                // Remove all status classes
                statusBadge.classList.remove('badge-draft', 'badge-sent', 'badge-paid', 'badge-overdue');

                // Add new status class
                statusBadge.classList.add('badge-' + newStatus);

                // Update text
                statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
            }

            // Update payment status badge if exists
            const paymentStatusBadges = invoiceItem.querySelectorAll('.invoice-status .status-badge');
            if (paymentStatusBadges.length > 1) {
                const paymentStatusBadge = paymentStatusBadges[1];
                paymentStatusBadge.classList.remove('badge-paid', 'badge-partial', 'badge-unpaid');
                paymentStatusBadge.classList.add('badge-cancelled');
                paymentStatusBadge.textContent = 'Cancelled';
            }

            // Remove payment button if exists
            const payButton = invoiceItem.querySelector('.pay-btn');
            if (payButton) {
                payButton.remove();
            }

            // Update dropdown menu options
            const dropdownMenu = invoiceItem.querySelector('.dropdown-menu');
            if (dropdownMenu) {
                // Remove Cancel option
                const cancelOption = dropdownMenu.querySelector('a[onclick*="confirmCancelInvoice"]');
                if (cancelOption) {
                    cancelOption.parentNode.remove();
                }

                // Add Restore option
                const divider = dropdownMenu.querySelector('.dropdown-divider');
                if (divider) {
                    const restoreOption = document.createElement('li');
                    restoreOption.innerHTML = `
                <a class="dropdown-item" href="#" onclick="restoreInvoice(${invoiceId})">
                    <i class="fas fa-undo me-2"></i>Restore Invoice
                </a>
            `;
                    dropdownMenu.insertBefore(restoreOption, divider.nextSibling);
                }
            }
        }

        // Restore Invoice - Updated version
        function restoreInvoice(invoiceId) {
            fetch('/Jengopay/landlord/pages/financials/action/restore_invoice.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + invoiceId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Restored!',
                            'Invoice has been restored.',
                            'success'
                        ).then(() => {
                            // Update the UI without full page reload
                            updateInvoiceStatus(invoiceId, data.invoice.status || 'sent');
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            data.message || 'Failed to restore invoice.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'An error occurred while restoring the invoice.',
                        'error'
                    );
                });
        }

        // Delete for Sent Invoice
        function deleteSentInvoice(invoiceId) {
            Swal.fire({
                title: 'Delete Sent Invoice?',
                text: "This invoice has been sent to the tenant. Are you sure you want to delete it?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it anyway'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteInvoice(invoiceId);
                }
            });
        }

        // View Invoice Details
        function viewInvoice(invoiceId) {
            window.location.href = '/Jengopay/landlord/pages/financials/invoices/invoice_details.php?id=' + invoiceId;
        }
    </script>


<script>
document.getElementById('customer').addEventListener('change', function () {
    document.getElementById('tenant_id').value = this.value;
});
</script>


    <!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const saveDraftBtn = document.getElementById('saveDraftBtn');

    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', function(e) {
            e.preventDefault();
            saveAsDraft();
        });
    }

    function saveAsDraft() {
        // Get form element
        const form = document.querySelector('form[name="invoice-form"]') ||
                    document.querySelector('form[action="submit_invoice.php"]') ||
                    document.querySelector('form');

        if (!form) {
            console.error('Form not found');
            alert('Error: Form not found');
            return;
        }

        // Create FormData object
        const formData = new FormData(form);

        // Add draft-specific data
        formData.append('status', 'draft');
        formData.append('payment_status', 'unpaid');

        // Handle dynamic rows (if any)
        const rows = document.querySelectorAll('.items-table tbody tr');
        rows.forEach((row, index) => {
            const accountItem = row.querySelector('select[name="account_item[]"]');
            const description = row.querySelector('textarea[name="description[]"]');
            const quantity = row.querySelector('input[name="quantity[]"]');
            const unitPrice = row.querySelector('input[name="unit_price[]"]');
            const taxes = row.querySelector('select[name="taxes[]"]');

            if (accountItem && description && quantity && unitPrice && taxes) {
                formData.append(`account_item[${index}]`, accountItem.value);
                formData.append(`description[${index}]`, description.value);
                formData.append(`quantity[${index}]`, quantity.value);
                formData.append(`unit_price[${index}]`, unitPrice.value);
                formData.append(`taxes[${index}]`, taxes.value);
            }
        });

        // Send data via AJAX
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Draft saved successfully!');
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                throw new Error(data.error || 'Unknown error saving draft');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving draft: ' + error.message);
        });
    }
});
</script> -->

<!-- <script>
function filterFunction() {
    // Add your filter logic here
    console.log("Filter button clicked!");
    // Example: Show/hide filter options, filter a list, etc.
}
</script> -->
    <script>
        function openPayModal(button) {
            // Get all invoice data from button attributes
            const invoiceId = button.getAttribute('data-invoice-id');
            const tenant = button.getAttribute('data-tenant');
            const totalAmount = parseFloat(button.getAttribute('data-total'));
            const paidAmount = parseFloat(button.getAttribute('data-paid'));
            const balance = parseFloat(button.getAttribute('data-balance'));

            // Set today's date as default
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const todayStr = `${yyyy}-${mm}-${dd}`;

            // Set modal values
            document.getElementById('invoiceId').value = invoiceId;
            document.getElementById('tenantName').value = tenant;
            document.getElementById('invoiceTotal').value = totalAmount.toFixed(2);
            document.getElementById('amount').value = balance.toFixed(2);
            document.getElementById('paymentDate').value = todayStr;
            document.getElementById('paymentDate').setAttribute('max', todayStr);

            // Initialize payment status
            updatePaymentStatus();

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }

        function updatePaymentStatus() {
            const amountInput = document.getElementById('amount');
            const paymentStatus = document.getElementById('paymentStatus');
            const invoiceTotal = parseFloat(document.getElementById('invoiceTotal').value);
            const paymentAmount = parseFloat(amountInput.value) || 0;

            if (isNaN(paymentAmount)) {
                paymentStatus.innerHTML = '<span class="text-secondary">Enter valid payment amount</span>';
                return;
            }

            if (paymentAmount <= 0) {
                paymentStatus.innerHTML = '<span class="text-danger">⚠️ Amount must be greater than 0</span>';
            } else if (paymentAmount > invoiceTotal) {
                const overpayment = (paymentAmount - invoiceTotal).toFixed(2);
                paymentStatus.innerHTML = `<span class="text-danger">⚠️ Overpayment (KES ${overpayment} over)</span>`;
            } else if (paymentAmount === invoiceTotal) {
                paymentStatus.innerHTML = '<span class="text-success">✓ Full payment will be received</span>';
            } else {
                const remaining = (invoiceTotal - paymentAmount).toFixed(2);
                paymentStatus.innerHTML = `<span class="text-warning">⏳ Partial payment (KES ${remaining} remaining)</span>`;
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            // Setup amount input listener
            document.getElementById('amount').addEventListener('input', updatePaymentStatus);

            // Setup form submission
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);
                const invoiceId = formData.get('invoice_id');
                const paymentAmount = parseFloat(formData.get('amount'));
                const invoiceTotal = parseFloat(document.getElementById('invoiceTotal').value);

                // Validate payment amount
                if (paymentAmount <= 0) {
                    alert('❌ Payment amount must be greater than 0');
                    return;
                }

                if (paymentAmount > invoiceTotal) {
                    if (!confirm(`This payment will result in an overpayment of KES ${(paymentAmount - invoiceTotal).toFixed(2)}. Continue?`)) {
                        return;
                    }
                }

                // Submit payment
                fetch(form.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                            alertDiv.style.zIndex = '9999';
                            alertDiv.innerHTML = `
          <strong>✅ Success!</strong> ${data.message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
                            document.body.appendChild(alertDiv);

                            // Auto-remove after 5 seconds
                            setTimeout(() => {
                                alertDiv.remove();
                            }, 5000);

                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                            modal.hide();

                            // Refresh the page to update all data
                            window.location.reload();
                        } else {
                        console.error("❌ Server returned error:", data); // <-- shows PHP error in console
        alert('❌ ' + (data.message || 'Failed to submit payment'));
                        }
                    })
                    .catch(error => {
                        console.error('Payment error:', error);
                        alert('❌ Network or server error occurred');
                    });
            });
        });
    </script>

    <script>
        function checkPaymentStatus() {
            const amountInput = document.getElementById('amount');
            const invoiceTotal = parseFloat(document.getElementById('invoiceTotal').value);
            const paymentStatus = document.getElementById('paymentStatus');

            // Remove non-numeric characters and parse the input value
            const paidAmount = parseFloat(amountInput.value.replace(/[^0-9.]/g, '')) || 0;

            if (paidAmount <= 0) {
                paymentStatus.textContent = '';
                paymentStatus.className = 'mt-2 small fw-semibold';
                return;
            }

            if (paidAmount >= invoiceTotal) {
                paymentStatus.textContent = '✅ Full payment - invoice will be marked as paid';
                paymentStatus.className = 'mt-2 small fw-semibold text-success';
            } else if (paidAmount > 0 && paidAmount < invoiceTotal) {
                paymentStatus.textContent = '⚠️ Partial payment - invoice will be marked as partially paid';
                paymentStatus.className = 'mt-2 small fw-semibold text-warning';
            }
        }

        // When opening the modal, set the invoice total
        document.getElementById('paymentModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const invoiceTotal = button.getAttribute('data-invoice-total');
            document.getElementById('invoiceTotal').value = invoiceTotal;
        });
    </script>


    <!-- Main Js File -->
    <!-- <script src="invoice.js"></script> -->
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>

    <!-- J  A V A S C R I PT -->

    <!-- steeper plugin -->
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
        crossorigin="anonymous">
    </script>

    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous">
    </script>
<script src="../../../../landlord/js/adminlte.js"></script>    <!-- links for dataTaable buttons -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- apexcharts -->
    <script
        src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
        crossorigin="anonymous"></script>

    <!--end::Script-->
    <!-- date display only future date -->
    <script>
        const today = new Date().toISOString().split('T')[0];
        document.getElementById("inspectionDate").setAttribute("min", today);
    </script>

</body>
</html>