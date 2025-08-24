import { createExpense, payExpense } from "./expenseApi.js";

export function setupExpenseForms() {
  const expenseForm = document.getElementById("expenseForm");
  if (expenseForm) {
    expenseForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const result = await createExpense(expenseForm);
      console.log("Created expense:", result);
    //   window.location.reload();
    });
  }

  const payExpenseForm = document.getElementById("payExpenseForm");
  if (payExpenseForm) {
    payExpenseForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const result = await payExpense(payExpenseForm);
      console.log("Pay expense response:", result);
    //   window.location.reload();
    });
  }
}
