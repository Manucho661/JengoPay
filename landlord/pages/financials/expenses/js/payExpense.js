export async function payExpense(expenseForm) {
    {
        const formData = new FormData(expenseForm);
        const response = await fetch("./actions/payExpense.php", {
            method: "POST",
            body: formData,
        });
        window.location.reload();
    };
}