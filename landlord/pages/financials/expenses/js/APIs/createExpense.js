// Create expense
export async function createExpense(expenseForm) {
    {
        const formData = new FormData(expenseForm);
        const response = await fetch("./actions/createExpense.php", {
            method: "POST",
            body: formData,
        });
        window.location.reload();
    };
}