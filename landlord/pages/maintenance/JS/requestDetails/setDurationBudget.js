

export async function setDurationBudget(e) { 
    e.preventDefault(); // ✅ capital "D"
    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch("actions/requestDetails/setDurationBudget.php", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();

        // ✅ Close modal only after success
        const modalEl = document.getElementById('durationBudgetModal');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        modalInstance.hide();

        console.log("Duration budget working");
        console.log("Server response:", result.message);
        
        return result;
    } 
    catch (err) {
        console.error("❌ Error setting duration budget:", err);
        return { error: true, message: err.message };
    }
}
