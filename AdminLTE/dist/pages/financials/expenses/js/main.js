import { setupExpenseForms } from "./expenses/expenseForm.js";
import { initializeCustomSelect } from "./ui/customSelect.js";
import { combobox } from "./ui/combobox.js";
import { payExpense } from "./expenses/payExpense.js";
import { setupExpenseCalculator } from "./expenses/expenseCalculator.js";
import { initSupplierModal } from "./expenses/createSupplier.js";

document.addEventListener("DOMContentLoaded", () => {
  console.log("Document ready");

  // UI setup
  combobox();
  document.querySelectorAll(".custom-select-wrapper").forEach(initializeCustomSelect);

  // Expense features
  setupExpenseCalculator();
  setupExpenseForms();

  // Attach payExpense to buttons dynamically
  document.querySelectorAll("[data-action='pay-expense']").forEach(button => {
    button.addEventListener("click", () => {
      const expenseId = button.getAttribute("data-expense-id");
      const expectedAmount = parseFloat(button.getAttribute("data-expected-amount"));
      payExpense(expenseId, expectedAmount);
    });
  });

  // ✅ Initialize supplier modal (close, form, etc.)
  initSupplierModal();

  // ✅ Use event delegation so dynamically created button works
  document.addEventListener("click", (e) => {
    if (e.target && e.target.id === "registerSupplierButton") {
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
});
