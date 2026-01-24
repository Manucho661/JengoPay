
import { initSupplierModal, payExpenseModal } from "./modals.js";
import { setupExpenseCalculator } from "./calculateExpense.js";
import { vldtSupplierReg, checkPayment } from "./validations.js";
import { downloadExpPDF } from "./downloadPdf.js";
import { payExpense } from "./payExpense.js";
import { registerSupplier } from "./registerSupplier.js";
import { editSupplier } from "./APIs/editSupplier.js";
import { get_payment_details } from "./APIs/getPaymentDetails.js";
import { edit_submittedPayments } from "./APIs/editPayment.js";
import { initSupplierListModal } from "./modals.js";


document.addEventListener("DOMContentLoaded", () => {
  // Track invalid fields for forms
  const invalidFields = new Set();
  // Expense features
  setupExpenseCalculator();


  // PAYMENTS
  // pay expense modal
  document.addEventListener("click", (e) => {
    const button = e.target.closest("[data-action='pay-expense']");
    if (button) {
      const expenseId = button.getAttribute("data-expense-id");
      const expectedAmount = parseFloat(button.getAttribute("data-expected-amount"));
      payExpenseModal(expenseId, expectedAmount);
    }
  });

  // payExpense
  document.getElementById("payExpenseForm").addEventListener('submit', (e) => {
    e.preventDefault();
    payExpense(e.target);
  });

  // edit payment
  document.addEventListener("click", (e) => {
    const editBtn = e.target.closest(".edit-payment-btn");
    if (editBtn) {
      e.preventDefault();
      get_payment_details(editBtn); // pass the element, not the event
    }
  });

  // edit the payments 
  const editPaymentModal = document.getElementById("editPaymentModal");

  if (editPaymentModal) {

    editPaymentModal.addEventListener("submit", (e) => {
      if (e.target.classList.contains("payment-form")) {
        e.preventDefault();
        edit_submittedPayments(e.target);
      }
    });
  }



  // SUPPLIERS
  // Initialize supplier create modal
  initSupplierModal(invalidFields);

  // creat supplier
  document.getElementById("supplierForm").addEventListener('submit', (e) => {
    e.preventDefault();
    registerSupplier(invalidFields, e.target);
  });

  //Initialize supplier list modal
  initSupplierListModal();

  // Open supplier create modal on click
  document.addEventListener("click", (e) => {
    if (e.target && (e.target.id === "registerSupplierButton" || e.target.id === "addSupplier")) {
      e.preventDefault();
      console.log("create supplier button clicked");

      const supplierOverlay = document.getElementById("supplierOverlay");
      const supplierModal = document.getElementById("supplierModal");

      if (supplierOverlay && supplierModal) {
        supplierOverlay.classList.add("active");
        supplierModal.classList.add("active");
      }
    }
  });

  // Open supplier list modal on click
  document.addEventListener("click", (e) => {
    if (e.target && e.target.id === "supplier-list-open-btn") {
      e.preventDefault();
      console.log("supplier list button clicked");

      const listOverlay = document.getElementById("supplierListOverlay");
      const listModal = document.getElementById("supplierListModal");

      if (listOverlay && listModal) {
        listOverlay.classList.add("active");
        listModal.classList.add("active");
      }
    }
  });

  // Edit supplier
  document.getElementById("supplierEditForm").addEventListener('submit', (e) => {
    e.preventDefault();
    editSupplier(e.target);
  });


  // Initialize validation functions
  vldtSupplierReg(invalidFields);
  checkPayment();

  // expense pdf download
  document.getElementById('downloadExpPdf').addEventListener('click', downloadExpPDF);


});
