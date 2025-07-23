 // Initialize the app
 function initApp() {
  renderInvoiceList();
  populateCustomerSelect();
  setupEventListeners();
  updateInvoiceNumber(); // Default to INV
}

// Generate invoice number based on draft status
function updateInvoiceNumber(isDraft = false) {
  const prefix = isDraft ? 'DFT' : 'INV';
  const nextNumber = getNextInvoiceNumber(prefix);
  invoiceNumberInput.value = `${prefix}${String(nextNumber).padStart(3, '0')}`;
}

// Get last invoice number and calculate next
function getNextInvoiceNumber(prefix) {
  const filtered = database.invoices.filter(inv => inv.number.startsWith(prefix));
  if (filtered.length === 0) return 1;

  const last = filtered.reduce((max, inv) => {
      const num = parseInt(inv.number.replace(prefix, ''), 10);
      return num > max ? num : max;
  }, 0);

  return last + 1;
}

// Save invoice (draft or finalized)
function saveInvoice(isDraft = false) {
  // Update invoice number based on status
  updateInvoiceNumber(isDraft);

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

// Set up all event listeners
function setupEventListeners() {
  // Save as Draft
  saveDraftBtn.addEventListener('click', () => {
      const invoice = saveInvoice(true);
      alert(`Draft saved: ${invoice.number}`);
      createInvoiceView.style.display = 'none';
      invoiceListView.style.display = 'block';
      renderInvoiceList();
      updateInvoiceNumber(); // Reset to normal after draft
  });

  // Finalize Invoice
  saveFinalizeBtn.addEventListener('click', () => {
      const invoice = saveInvoice(false);
      alert(`Invoice finalized: ${invoice.number}`);
      createInvoiceView.style.display = 'none';
      invoiceListView.style.display = 'block';
      renderInvoiceList();
  });

  // Other event listeners (addItem, filters, etc.) should remain here...
}

// Render invoices to the UI
function renderInvoiceList(filterStatus = 'all', filterDate = 'this-month') {
  invoiceList.innerHTML = '';

  let filteredInvoices = [...database.invoices];

  if (filterStatus !== 'all') {
      filteredInvoices = filteredInvoices.filter(inv => inv.status === filterStatus);
  }

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

// Helper functions
function formatDate(dateString) {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('en-US', options);
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

// Start everything
document.addEventListener('DOMContentLoaded', initApp);


//  INVOICE FUTURE DATE

document.addEventListener('DOMContentLoaded', function() {
const invoiceDateInput = document.getElementById('invoice-date');

// Set max date to today (YYYY-MM-DD format)
const today = new Date().toISOString().split('T')[0];
invoiceDateInput.setAttribute('max', today);

// Optional: Prevent manual input of future dates
invoiceDateInput.addEventListener('change', function() {
  const selectedDate = new Date(this.value);
  const today = new Date();

  if (selectedDate > today) {
      alert("Invoice date cannot be in the future!");
      this.value = today.toISOString().split('T')[0]; // Reset to today
  }
});
});



// DOM Elements
const invoiceListView = document.getElementById('invoice-list-view');
const createInvoiceView = document.getElementById('create-invoice-view');
const createInvoiceBtn = document.getElementById('create-invoice-btn');
const cancelInvoiceBtn = document.getElementById('cancel-invoice-btn');

// Show form
function showCreateInvoiceView() {
invoiceListView.style.display = 'none';
createInvoiceView.style.display = 'block';
}

// Back to list
function showInvoiceListView() {
createInvoiceView.style.display = 'none';
invoiceListView.style.display = 'block';
}

// Click handlers
createInvoiceBtn?.addEventListener('click', showCreateInvoiceView);
cancelInvoiceBtn?.addEventListener('click', showInvoiceListView);

// Edit logic
// JavaScript function
function editInvoice(invoiceId) {
  // Show the form view
  showCreateInvoiceView();

  // Populate form fields via AJAX
  fetch('get_invoice_data.php?id=' + invoiceId)
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok');
          }
          return response.json();
      })
      .then(data => {
          if (data.error) {
              alert(data.error);
              return;
          }

          // Fill form fields
          document.getElementById('invoice-id').value = data.id;
          document.getElementById('invoice-number').value = data.invoice_number;
          document.getElementById('invoice-date').value = data.invoice_date;
          // ... rest of your field population
      })
      .catch(error => {
          alert("Failed to load invoice data: " + error.message);
          console.error('Error:', error);
      });
}



// function editInvoice(invoiceid) {
//     fetch(`../invoice/actions/get_invoice_data.php?id=${id}`)
//         .then(response => response.json())
//         .then(data => {
//             if (data.error) {
//                 alert(data.error);
//                 return;
//             }

//             // Populate form fields (example)
//             document.getElementById('invoice_id').value = data.id;
//             document.getElementById('invoice_number').value = data.invoice_number;
//             document.getElementById('invoice_date').value = data.invoice_date;
//             document.getElementById('due_date').value = data.due_date;
//             document.getElementById('building_id').value = data.building_id;
//             document.getElementById('tenant').value = data.tenant;
//             document.getElementById('account_item').value = data.account_item;
//             document.getElementById('description').value = data.description;
//             document.getElementById('quantity').value = data.quantity;
//             document.getElementById('unit_price').value = data.unit_price;
//             document.getElementById('taxes').value = data.taxes;
//             document.getElementById('notes').value = data.notes;
//             document.getElementById('terms_conditions').value = data.terms_conditions;

//             // Open the form modal if using Bootstrap
//             const modal = new bootstrap.Modal(document.getElementById('editInvoiceModal'));
//             modal.show();
//         })
//         .catch(err => {
//             alert('Error fetching invoice details.');
//             console.error(err);
//         });
// }



document.addEventListener("DOMContentLoaded", function () {
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
} else if (vatType === "zero") {
  vatAmount = 0; // VAT 0% for Zero Rated
  total = subtotal; // No tax added for Zero Rated
} else if (vatType === "exempted") {
  vatAmount = 0; // No VAT for Exempted
  total = subtotal; // No tax added for Exempted
}

totalInput.value = formatNumber(total);
return { subtotal, vatAmount, total, vatType };
}

function updateTotalAmount() {
let subtotalSum = 0, taxSum = 0, grandTotal = 0, exemptedSum = 0, zeroVatSum = 0;
let vat16Used = false, vat0Used = false, exemptedUsed = false;

document.querySelectorAll(".items-table tbody tr").forEach(row => {
  if (row.querySelector(".unit-price")) {
    const { subtotal, vatAmount, total, vatType } = calculateRow(row);
    subtotalSum += subtotal;
    taxSum += vatAmount;
    grandTotal += total;

    if (vatType === "inclusive" || vatType === "exclusive") {
      vat16Used = true;
    } else if (vatType === "zero") {
      zeroVatSum += 0; // Zero Rated has zero VAT
      vat0Used = true;
    } else if (vatType === "exempted") {
      exemptedSum += 0; // Exempted has zero VAT
      exemptedUsed = true;
    }
  }
});

createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, zeroVatSum, exemptedSum, vat16Used, vat0Used, exemptedUsed });
}

function createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, zeroVatSum, exemptedSum, vat16Used, vat0Used, exemptedUsed }) {
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
    <th style="width: 50%; padding: 5px; text-align: left;">VAT 16%</th>
    <td><input type="text" class="form-control" value="${formatNumber(taxSum)}" readonly style="padding: 5px;"></td>
  </tr>` : ''}
  ${vat0Used ? `
  <tr>
    <th style="width: 50%; padding: 5px; text-align: left;">VAT 0%</th>
    <td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
  </tr>` : ''}
  ${exemptedUsed ? `
  <tr>
    <th style="width: 50%; padding: 5px; text-align: left;">Exempted</th>
    <td><input type="text" class="form-control" value="0.00" readonly style="padding: 5px;"></td>
  </tr>` : ''}
  <tr>
    <th style="width: 50%; padding: 5px; text-align: left;">Total</th>
    <td><input type="text" class="form-control" value="${formatNumber(grandTotal)}" readonly style="padding: 5px;"></td>
  </tr>
`;
}

function attachEvents(row) {
const inputs = [".unit-price", ".quantity", ".vat-option"];
inputs.forEach(sel => {
  const el = row.querySelector(sel);
  if (el) {
    el.addEventListener("input", updateTotalAmount);
    el.addEventListener("change", updateTotalAmount);
  }
});
}

window.addRow = function () {
const table = document.querySelector(".items-table tbody");
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
<td><input type="number" name="quantity[]" class="form-control quantity" placeholder="1" required></td>
<td><input type="number" name="unit_price[]" class="form-control unit-price" placeholder="123" required></td>
<td>
<select name="taxes[]" class="form-select vat-option" required>
  <option value="" disabled selected>Select Option</option>
  <option value="inclusive">VAT 16% Inclusive</option>
  <option value="exclusive">VAT 16% Exclusive</option>
  <option value="zero">Zero Rated</option>
  <option value="exempt">Exempted</option>
</select>
</td>
<td>
<input type="text" class="form-control total" placeholder="0" readonly>
<button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
  <i class="fa fa-trash" style="font-size: 12px;"></i>
</button>
</td>
`;
table.appendChild(newRow);
attachEvents(newRow);
};

window.deleteRow = function (btn) {
btn.closest("tr").remove();
updateTotalAmount();
};

document.querySelectorAll(".items-table tbody tr").forEach(attachEvents);
updateTotalAmount();
});



document.getElementById('saveDraftBtn').addEventListener('click', async function () {
  const form = document.querySelector('form');
  const formData = new FormData(form);
  formData.append('draft', '1');

  try {
    const response = await fetch('save_draft.php', {
      method: 'POST',
      body: formData
    });

    // First get the response text
    const responseText = await response.text();

    // Try to parse it as JSON
    let data;
    try {
      data = JSON.parse(responseText);
    } catch (e) {
      throw new Error(`Invalid JSON response: ${responseText}`);
    }

    if (data.success) {
      alert(`Draft saved: ${data.invoice_number}`);
      document.getElementById('invoice-number').value = data.invoice_number;

      // Hide draft form, show invoice list
      createInvoiceView.style.display = 'none';
      invoiceListView.style.display = 'block';
      renderInvoiceList();

      // Redirect if URL is provided
      if (data.redirect_url) {
        window.location.href = data.redirect_url;
      }
    } else {
      throw new Error(data.message || 'Unknown error saving draft');
    }
  } catch (err) {
    console.error('Error saving draft:', err);
    alert('Error saving draft: ' + err.message);
  }
});


// Preview Button - Updated to handle draft numbers
document.getElementById('preview-invoice-btn').addEventListener('click', function () {
  const previewPanel = document.getElementById('invoicePreviewPanel');
  const previewContent = document.getElementById('previewContent');

  // Draft checkbox check
  const isDraft = document.getElementById('draftCheckbox')?.checked;

  // Get form values
  const invoiceNumber = document.getElementById('invoice-number').value;
  const building = document.getElementById('building').selectedOptions[0]?.text || 'N/A';
  const tenant = document.getElementById('customer').selectedOptions[0]?.text || 'N/A';
  const invoiceDate = document.getElementById('invoice-date').value;
  const dueDate = document.getElementById('due-date').value;
  const status = isDraft ? 'DRAFT' : 'PENDING';

  // Build items table
  let tableRows = '';
  let subtotal = 0;

  document.querySelectorAll('.items-table tbody tr').forEach(row => {
      const item = row.querySelector('select[name="account_item[]"]').selectedOptions[0]?.text || '';
      const desc = row.querySelector('textarea[name="description[]"]').value;
      const qty = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
      const price = parseFloat(row.querySelector('input[name="unit_price[]"]').value) || 0;
      const tax = row.querySelector('select[name="taxes[]"]').value;
      const rowTotal = qty * price;
      subtotal += rowTotal;

      tableRows += `
          <tr>
              <td>${item}</td>
              <td>${desc}</td>
              <td>${qty}</td>
              <td>${price.toFixed(2)}</td>
              <td>${tax}</td>
              <td>${rowTotal.toFixed(2)}</td>
          </tr>`;
  });

  // Calculate tax and total
  const taxRate = 0.1;
  const taxAmount = subtotal * taxRate;
  const total = subtotal + taxAmount;

  previewContent.innerHTML = `
      <div class="invoice-header">
          <h2>${status}</h2>
          <div class="invoice-meta">
              <p><strong>Invoice #:</strong> ${invoiceNumber}</p>
              <p><strong>Status:</strong> ${status}</p>
              <p><strong>Date:</strong> ${invoiceDate}</p>
              <p><strong>Due Date:</strong> ${dueDate}</p>
          </div>
      </div>

      <div class="invoice-parties">
          <div class="from">
              <h3>From:</h3>
              <p>Your Company Name</p>
          </div>
          <div class="to">
              <h3>To:</h3>
              <p><strong>Building:</strong> ${building}</p>
              <p><strong>Tenant:</strong> ${tenant}</p>
          </div>
      </div>

      <table class="invoice-items">
          <thead>
              <tr>
                  <th>Item</th>
                  <th>Description</th>
                  <th>Qty</th>
                  <th>Unit Price</th>
                  <th>Tax</th>
                  <th>Total</th>
              </tr>
          </thead>
          <tbody>${tableRows}</tbody>
          <tfoot>
              <tr>
                  <td colspan="5" class="text-right">Subtotal:</td>
                  <td>${subtotal.toFixed(2)}</td>
              </tr>
              <tr>
                  <td colspan="5" class="text-right">Tax (10%):</td>
                  <td>${taxAmount.toFixed(2)}</td>
              </tr>
              <tr class="total">
                  <td colspan="5" class="text-right"><strong>Total:</strong></td>
                  <td><strong>${total.toFixed(2)}</strong></td>
              </tr>
          </tfoot>
      </table>

      ${isDraft ? '<div class="draft-watermark">DRAFT</div>' : ''}
  `;

  previewPanel.classList.add('active');
});

// Close Preview Panel
document.getElementById('closePreview').addEventListener('click', function () {
  document.getElementById('invoicePreviewPanel').classList.remove('active');
});

// Auto-update draft number if checkbox changes
const draftCheckbox = document.getElementById('draftCheckbox');
if (draftCheckbox) {
  draftCheckbox.addEventListener('change', function () {
      const invoiceNumberField = document.getElementById('invoice-number');
      if (this.checked) {
          invoiceNumberField.value = ''; // clear so backend assigns next
      } else {
          invoiceNumberField.value = ''; // same here; backend will assign finalized version later
      }
  });
}
// });





document.addEventListener('DOMContentLoaded', () => {
const buildingSelect = document.getElementById('building');
const tenantSelect   = document.getElementById('customer');

buildingSelect.addEventListener('change', () => {
  const buildingId = buildingSelect.value;

  // Reset tenant dropdown
  tenantSelect.innerHTML =
      '<option value="">Select a Tenant</option>';
  tenantSelect.disabled = !buildingId;

  if (!buildingId) return;

  // Fetch tenants for that building
  fetch(`../invoice/actions/get_tenants.php?building_id=${buildingId}`)
      .then(r => r.json())
      .then(tenants => {
          tenants.forEach(t => {
              const opt   = document.createElement('option');
              opt.value   = t.id;
              opt.textContent = t.name;
              tenantSelect.appendChild(opt);
          });
      })
      .catch(err => {
          console.error('Failed to load tenants', err);
          alert('Could not load tenants for this building.');
      });
});
});




/**
* Turn any .searchable-select <select> into a Select2 box
* Call this once at startup *and* after you add a new row dynamically.
*/
function initItemSelect($scope = $(document)) {
$scope.find('.searchable-select').select2({
  width: '100%',                 // fills the <td>
  placeholder: $(this).data('placeholder'),
  allowClear: true               // little “×” to clear a choice
});
}

$(function () {
// ── 1️⃣  first initialise everything already on the page
initItemSelect();

// ── 2️⃣  if you have an “Add Row” button, re‑init the new row
$('#addRowBtn').on('click', function () {
  const $newRow = $('#items-table tbody tr:first').clone(true);   // or however you create rows
  // clear any previous values
  $newRow.find('input, textarea').val('');
  $newRow.find('select').val(null).trigger('change');

  $('#items-table tbody').append($newRow);
  initItemSelect($newRow);        // ⭐ make its select searchable
});
});




$('.searchable-select').select2({
width:'100%',
placeholder:'Select or search an item…',
minimumInputLength: 2,
ajax: {
  url: '../invoice/actions/account-items-search.php',
  dataType: 'json',
  delay: 250,              // throttle queries
  data: params => ({ q: params.term }),    // term typed by user
  processResults: data => ({
      results: data.map(item => ({
          id: item.account_code,           // value sent to server
          text: item.account_name          // visible label
      }))
  }),
  cache: true
}
});



document.addEventListener('DOMContentLoaded', function() {
const filterButton = document.getElementById('filterButton');
const filterModal = document.getElementById('filterModal');
const closeFilter = document.getElementById('closeFilter');
const applyFilter = document.getElementById('applyFilter');
const resetFilter = document.getElementById('resetFilter');

// Show modal when filter button is clicked
filterButton.addEventListener('click', function() {
  filterModal.style.display = 'block';
});

// Close modal when X is clicked
closeFilter.addEventListener('click', function() {
  filterModal.style.display = 'none';
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
  if (event.target == filterModal) {
      filterModal.style.display = 'none';
  }
});

// Apply filters
applyFilter.addEventListener('click', function() {
  const status = document.getElementById('statusFilter').value;
  const paymentStatus = document.getElementById('paymentFilter').value;
  const dateFrom = document.getElementById('dateFrom').value;
  const dateTo = document.getElementById('dateTo').value;

  // Here you would typically make an AJAX call to your server with the filter parameters
  // For this example, we'll just log them
  console.log('Applying filters:', {
      status: status,
      paymentStatus: paymentStatus,
      dateFrom: dateFrom,
      dateTo: dateTo
  });

  // Close the modal
  filterModal.style.display = 'none';

  // In a real implementation, you would reload the table data with the filters applied
  // For example:
  // fetchFilteredInvoices(status, paymentStatus, dateFrom, dateTo);
});

// Reset filters
resetFilter.addEventListener('click', function() {
  document.getElementById('statusFilter').value = '';
  document.getElementById('paymentFilter').value = '';
  document.getElementById('dateFrom').value = '';
  document.getElementById('dateTo').value = '';

  // In a real implementation, you would reload the original unfiltered data
  // For example:
  // fetchAllInvoices();
});
});

// Example function for fetching filtered invoices (would need server-side implementation)
function fetchFilteredInvoices(status, paymentStatus, dateFrom, dateTo) {
// This would be an AJAX call to your server

fetch('../invoice/actions/filter.php', {
  method: 'POST',
  headers: {
      'Content-Type': 'application/json',
  },
  body: JSON.stringify({
      status: status,
      payment_status: paymentStatus,
      date_from: dateFrom,
      date_to: dateTo
  })
})
.then(response => response.json())
.then(data => {
  // Update your table with the filtered data
  updateInvoiceTable(data);
})
.catch(error => {
  console.error('Error:', error);
});
}

// Example function to update the table with filtered data
function updateInvoiceTable(invoices) {
// Implementation would depend on how your table is structured
// This would clear and repopulate the table with the filtered invoices
}
