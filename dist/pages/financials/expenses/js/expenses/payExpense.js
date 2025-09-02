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