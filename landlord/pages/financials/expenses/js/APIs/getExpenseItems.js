function openExpenseModal(expenseId) {
    fetch(`actions/getExpenseItems.php?id=${expenseId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch data");
            }
            return response.json();
        })
        .then(data => {
            if (!data.length) {
                console.warn("No expense data found.");
                return;
            }

            const expense = data[0]; // Get the first (and likely only) row

            // Map values to HTML elements
            document.getElementById('expenseModalSupplierName').textContent = expense.supplier || '—';
            document.getElementById('expenseModalInvoiceNo').textContent = expense.expense_no || '—';
            document.getElementById('expenseModalTotalAmount').textContent = `KES ${parseFloat(expense.total || 0).toLocaleString()}`;
            document.getElementById('expenseModalTaxAmount').textContent = `KES ${parseFloat(expense.total_taxes || 0).toLocaleString()}`;
            document.getElementById('expenseModalUntaxedAmount').textContent = `KES ${parseFloat(expense.untaxed_amount || 0).toLocaleString()}`;
            // Payment status

            const status = expense.status || 'paid'; // Defaulting to 'paid' if status is not available
            const statusLabelElement = document.getElementById('expenseModalPaymentStatus'); // ID instead of class
            // Check the status and apply the appropriate class and text
            if (expense.status === "Paid") {
                statusLabelElement.textContent = "PAID";
                statusLabelElement.classList.remove("diagonal-unpaid-label"); // Remove the unpaid
                statusLabelElement.classList.add("diagonal-paid-label");
            } else if (expense.status === "Overpaid") {
                statusLabelElement.textContent = "PAID";
                statusLabelElement.classList.remove("diagonal-unpaid-label"); // Remove the unpaid
                statusLabelElement.classList.add("diagonal-paid-label");
                document.getElementById("overPaymentNote").style.display = "block";
                document.getElementById("overPaidAmount").textContent = `KES ${parseFloat(expense.amount_paid || 0).toLocaleString()}`;
                const total = parseFloat(expense.total || 0);
                const paid = parseFloat(expense.amount_paid || 0);
                const prepaid = paid - total;
                document.getElementById("prepaidAmount").textContent =
                    `KES ${prepaid.toLocaleString()}`;

                console.log(prepaid);

            } else if (expense.status === "partially paid") {
                statusLabelElement.textContent = "PARTIAl";
                statusLabelElement.classList.remove("diagonal-unpaid-label"); // Remove the unpaid
                statusLabelElement.classList.add("diagonal-partially-paid-label");
                document.getElementById("patialPaymentNote").style.display = "block";
                document.getElementById("partalPaidAmount").textContent = `KES ${parseFloat(expense.amount_paid || 0).toLocaleString()}`;
                const total = parseFloat(expense.total || 0);
                const paid = parseFloat(expense.amount_paid || 0);
                const balance = total - paid;

                document.getElementById("balanceAmount").textContent =
                    `KES ${balance.toLocaleString()}`;

                // patialPaymentNote

            } else {
                statusLabelElement.textContent = "UNPAID";
            }
            console.log(expense.status)
            const tableBody = document.getElementById('expenseItemsTableBody');
            tableBody.innerHTML = "";
            data.forEach((item) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                            <td>${item.description || '—'}</td>
                            <td class="text-end">${item.qty || 0}</td>
                            <td class="text-end">KES ${parseFloat(item.unit_price || 0).toLocaleString()}</td>
                            <td class="text-end">${item.taxes || '—'}</td>
                            <td class="text-end">${item.discount || '—'}%</td> <!-- Update if you have discount data -->
                            <td class="text-end">KES ${parseFloat(item.item_total || 0).toLocaleString()} </td>
                        `;
                tableBody.appendChild(row);
            });
            // Show the modal
            const expenseModal = new bootstrap.Modal(document.getElementById('expenseModal'));
            expenseModal.show();
        })
        .catch(error => {
            console.error("Error loading expense:", error);
        });
}