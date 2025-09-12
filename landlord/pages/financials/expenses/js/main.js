
import { setupExpenseForms } from "./expenses/expForms.js";
import { initializeCustomSelect } from "./ui/customSelect.js";
import { combobox } from "./ui/combobox.js";
import { payExpense, initSupplierModal, editExpModal } from "./expenses/modals.js";
import { setupExpenseCalculator } from "./expenses/expCalculator.js";
import { initSupplierListModal } from "./expenses/supplierList.js";
import { vldtSupplierReg, checkPayment} from "./expenses/expValidations.js";
import { downloadExpPDF } from "./expenses/expPdf.js";

document.addEventListener("DOMContentLoaded", () => {

  // UI setup
  combobox();
  document.querySelectorAll(".custom-select-wrapper").forEach(initializeCustomSelect);
  // Track invalid fields for forms
  const invalidFields = new Set();
  // Expense features
  setupExpenseCalculator();
  setupExpenseForms(invalidFields);

  // initialize edit
  editExpModal();

  // Attach payExpense to buttons dynamically
  document.querySelectorAll("[data-action='pay-expense']").forEach(button => {
    button.addEventListener("click", () => {
      const expenseId = button.getAttribute("data-expense-id");
      const expectedAmount = parseFloat(button.getAttribute("data-expected-amount"));
      payExpense(expenseId, expectedAmount);
    });
  });

  // Initialize supplier create modal
  initSupplierModal(invalidFields);
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

  // Initialize validation functions
  vldtSupplierReg(invalidFields);
  checkPayment();
  
  // expense pdf download
  document.getElementById('downloadExpPdf').addEventListener('click', downloadExpPDF);
});
