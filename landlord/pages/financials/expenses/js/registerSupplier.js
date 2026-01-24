export async function registerSupplier(invalidFields, supplierForm) {
    {
        const submitMsg = document.getElementById("submitMsg");
        if (invalidFields.size > 0) {
            submitMsg.textContent = "Please fix the errors before submitting the form.";
            submitMsg.style.color = "red";

            submitBtn.classList.add("shake");
            setTimeout(() => submitBtn.classList.remove("shake"), 300);

            return; // stop execution if there are invalid fields
        }
        const formData = new FormData(supplierForm);
        const response = await fetch("actions/registerSupplier.php", {
            method: "POST",
            body: formData,
        });
        return response.text();
    };
}

export async function checkFieldAvailability(field, value) {
    if (value.length < 3) return { exists: false, skip: true };

    try {
        const response = await fetch(
            `actions/check_Regfields.php?field=${field}&value=${encodeURIComponent(value)}`
        );

        return await response.json();

    } catch (error) {
        return { error: "Network error: " + error.message };
    }
}
