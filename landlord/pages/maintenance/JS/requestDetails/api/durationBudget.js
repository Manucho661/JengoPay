import { getRequestDetails } from "./getRequestDetails.js";

export async function setDurationBudget(e) { 
    e.preventDefault(); // ✅ capital "D"
    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch("actions/request_details/setDurationBudget.php", {
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

        getRequestDetails();
        return result;
    } 
    catch (err) {
        console.error("❌ Error setting duration budget:", err);

        // You could also return the error if needed for further handling
        return { error: true, message: err.message };
    }
}
