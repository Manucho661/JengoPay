import { createExpense, payExpense, registerSupplier, editSupplier } from "./expApi.js";

export function setupExpenseForms(invalidFields) {

  const submitMsg = document.getElementById("submitMsg");
  const submitBtn = document.getElementById("registerBtn");

  // Create expense
  const expenseForm = document.getElementById("expenseForm");
  if (expenseForm) {
    expenseForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const result = await createExpense(expenseForm);
      console.log("Created expense:", result);
      // window.location.reload();
    });
  }

  const payExpenseForm = document.getElementById("payExpenseForm");
  if (payExpenseForm) {
    payExpenseForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const result = await payExpense(payExpenseForm);
      console.log("Pay expense response:", result);
      // window.location.reload();
    });
  }

  const registerSupplierForm = document.getElementById("supplierForm");
  if (registerSupplierForm) {
    registerSupplierForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      if (invalidFields.size > 0) {
        submitMsg.textContent = "Please fix the errors before submitting the form.";
        submitMsg.style.color = "red";

        submitBtn.classList.add("shake");
        setTimeout(() => submitBtn.classList.remove("shake"), 300);

        return; // stop execution if there are invalid fields
      }

      const result = await registerSupplier(registerSupplierForm);
      console.log("Register supplier response:", result);
    });
  }

  const editSupplierForm = document.getElementById("supplierEditForm");
  if (editSupplierForm) {
    editSupplierForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const result = await editSupplier(editSupplierForm);
      console.log("Edit supplier response:", result);
    })
  }
}
