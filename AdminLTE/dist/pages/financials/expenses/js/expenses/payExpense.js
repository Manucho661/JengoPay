// Display Pay Expense Modal
export function payExpense(expenseId, expectedAmountToPay) {
  const expenseIdInput = document.getElementById('expenseId');
  const expectedAmountToPayInput = document.getElementById('expectedAmount');
  const paymentDateInput = document.getElementById('paymentDate');
  const payExpenseForm = document.getElementById('payExpenseForm');
  const modalElement = document.getElementById('payExpenseModal');

console.log("Sending expenseId:", expenseId);

  if (!expenseIdInput || !paymentDateInput || !payExpenseForm || !modalElement) {
    console.error("Modal or form elements not found.");
    return;
  }

  // Reset the form first
  payExpenseForm.reset();

  // Set hidden input with expense ID
  expenseIdInput.value = expenseId;
  expectedAmountToPayInput.value = expectedAmountToPay;
  // Set amount to pay (now after reset)
  document.getElementById('amountToPay').value = parseFloat(expectedAmountToPay);

  // Set today's date
  const today = new Date().toISOString().split('T')[0];
  paymentDateInput.value = today;

  // Show the modal
  const modal = new bootstrap.Modal(modalElement);
  modal.show();
}


// payExpense
document.getElementById("payExpenseForm").addEventListener("submit", function (e) {
  e.preventDefault();
  console.log('PayexpenseForm working');

  const form = document.getElementById("payExpenseForm");
  const formData = new FormData(form);

  for (let [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }
  fetch("actions/payExpense.php", {
    method: "POST",
    body: formData,
  })
    .then(response => response.text())
    .then(data => {
      console.log("Server response:", data);

      // ✅ Reload the page without resubmission
      // window.location.href = window.location.href;
    })
    .catch(error => {
      console.error("Error submitting form:", error);
    });
});