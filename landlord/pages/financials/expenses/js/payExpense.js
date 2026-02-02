export async function payExpense(expenseForm) {
    const confirmButton = document.getElementById("confirmPaymentBtn");

    // Disable the button and change the text to "Saving"
    confirmButton.disabled = true;
    confirmButton.innerHTML = '<i class="bi bi-credit-card"></i> Saving...';

    const formData = new FormData(expenseForm);
    try {
        const response = await fetch("./actions/payExpense.php", {
            method: "POST",
            body: formData,
        });
        
        // You may want to check if the response is successful here
        if (response.ok) {
            window.location.reload();
        } else {
            // Handle the error (e.g., show a message to the user)
            alert("An error occurred while processing your payment.");
        }
    } catch (error) {
        // Handle any errors during the fetch operation
        alert("An error occurred. Please try again.");
    }
}
