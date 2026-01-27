import { checkPaymentAPI } from "./APIs/validations.js";


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
