import { checkFieldAvailability, checkPaymentAPI } from "./APIs/validations";

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
                        invalidFields.delete(field); // remove from invalid
                        msgBox.textContent = ""; // clear message if input too short
                    }
                }
            });
        }
    });


}

// check amount remaining
export function checkPayment() {
    const payAmount = document.getElementById('amountToPay');
    const payMethodAcctId = document.getElementById('paymentMethod');
    const msgBox = document.getElementById("paymentMsg");   // small warning message area

    async function validatePayment() {
        const amount = parseFloat(payAmount.value) || 0;
        const methodaccId = payMethodAcctId.value;

        if (!amount || !methodaccId) {
            msgBox.textContent = "";
            return;
        }

        const data = await checkPaymentAPI(methodaccId, amount);
        if (data.error) {
            console.error("Payment check error:", data.error);
            return;
        }
        if (!data.is_enough) {
            console.log(data.total_debit)
            msgBox.textContent = `⚠️ Insufficient funds. Balance: ${data.balance}, Requested: ${amount}`;
            msgBox.style.color = "red";
        }
        else {
            msgBox.textContent = "";
            console.log('yoyo');
            console.log(methodaccId);
            console.log(data.total_debit)
        }

    }

    if (payAmount && payMethodAcctId) {
        // Check on amount input
        payAmount.addEventListener("keyup", validatePayment);

        // Check on payment method change
        payMethodAcctId.addEventListener("change", validatePayment);
    }

}
