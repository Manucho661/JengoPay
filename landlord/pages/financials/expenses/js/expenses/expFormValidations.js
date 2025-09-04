import { checkFieldAvailability} from "./expenseApi.js";

export function vldtSupplierReg(invalidFields) {

    ["supplierName", "supplierKra"].forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.addEventListener("keyup", async (e) => {
                const data = await checkFieldAvailability(field, e.target.value);
                const msgBox = document.getElementById(field + "Msg");
                if (msgBox) {
                    if (data.error) {
                        console.error(`Error checking ${field}:`, data.error); // log raw DB/network error
                        msgBox.textContent = `Unable to validate ${field}. Try again later.`;
                        msgBox.style.color = "orange";
                    } else if (data.exists) {
                        msgBox.textContent = data.Message;
                        msgBox.style.color = "red";
                        // prevent form submission
                          invalidFields.add(field); // accessible here

                    } else {
                        msgBox.textContent = ""; // clear message if input too short
                    }
                }
            });
        }
    });


}