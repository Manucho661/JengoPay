
import { html, render } from 'https://unpkg.com/lit@3.1.4/index.js?module';

export async function get_payment_details(editBtn) {
  // Get the expense ID from the element's data attribute
  console.log('yoiouyt');
  const expenseId = editBtn.getAttribute("data-amount");
  console.log("Fetching payment details for expenseId:", expenseId);

  if (!expenseId) {
    console.error("❌ No expense ID found on this element!");
    return;
  }

  try {
    // Make fetch call to PHP action, passing expenseId in query string
    const response = await fetch(`./actions/getPaymentDetails.php?expense_id=${encodeURIComponent(expenseId)}`);

    // Parse JSON response
    const data = await response.json();

    if (data.status === "success") {
      console.log("✅ Payment details fetched:", data.details);

      // Render the forms with the fetched payment details
      renderPaymentForms(data.details);

      // Optionally, open the modal after the data is loaded
      const paymentModal = new bootstrap.Modal(document.getElementById('editPaymentModal'));
      paymentModal.show();

    } else {
      console.warn("⚠️ Failed to fetch payment details:", data.message);
    }
  } catch (err) {
    console.error("❌ Error fetching payment details:", err);
  }
}



// render the details

function renderPaymentForms(paymentDetails) {
  // Create a form for each payment detail
  const formTemplate = html`
    ${paymentDetails.map(payment => html`
      <form class="payment-form border-0 rounded mb-2 p-4" style="border:1px solid #e0e0e0; background:#f5f5f5;">
        <input type="hidden" name="payment_id" value="${payment.id}">
        <div class="d-flex flex-wrap gap-2 align-items-end">
          <!-- amount -->
          <div style="flex:1 1 120px;">
            <label class="form-label small fw-semibold mb-1">Amount</label>
            <input type="number" step="0.01" class="form-control form-control-sm shadow-none" name="amount" value="${payment.amount_paid}" required>
          </div>

          <!-- date -->
          <div style="flex:1 1 140px;">
            <label class="form-label small fw-semibold mb-1">Date</label>
            <input type="date" class="form-control form-control-sm shadow-none" name="payment_date" value="${payment.payment_date}" required>
          </div>

          <!-- method -->
          <div style="flex:1 1 140px;">
            <label class="form-label small fw-semibold mb-1">Method</label>
            <select class="form-select form-select-sm shadow-none" name="payment_account_id" required>
              <option value="100" ?selected="${payment.account_name === 'Cash'}">Cash</option>
              <option value="110" ?selected="${payment.account_name === 'M-Pesa'}">M-Pesa</option>
              <option value="120" ?selected="${payment.account_name === 'Bank'}">Bank</option>
            </select>
          </div>

          <!-- reference -->
          <div style="flex:2 1 180px;">
            <label class="form-label small fw-semibold mb-1">Reference</label>
            <input type="text" class="form-control form-control-sm shadow-none" name="reference" value="${payment.reference_no}">
          </div>

          <!-- save -->
          <div class="ms-auto">
            <button type="submit" class="btn btn-sm fw-semibold save-edit" style="background: linear-gradient(135deg, #00192D, #002B5B); color:white; border-radius:6px;">
              Save
            </button>
          </div>
        </div>
      </form>
    `)}
  `;

  // Render all the payment forms inside the modal body
  const modalBody = document.querySelector('#editPaymentModal .modal-body');
  render(formTemplate, modalBody);
}