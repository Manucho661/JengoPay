import { setupExpenseForms } from "./expenses/expenseForm.js";
import { initializeCustomSelect } from "./ui/customSelect.js";
import { combobox } from "./ui/combobox.js";
import { payExpense } from "./expenses/payExpense.js";
import { setupExpenseCalculator } from "./expenses/expenseCalculator.js";

document.addEventListener("DOMContentLoaded", () => {
  console.log("Documents ready");

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

});
