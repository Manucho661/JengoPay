export function openEditModal(button) {

    const supplierEditOverlay = document.querySelector('#supplierEditOverlay');
    const supplierEditModal = document.querySelector('#supplierEditModal');
    const closeBtn = document.querySelector(".supplierEdit-close-btn");

    supplierEditOverlay.classList.add('active');
    supplierEditModal.classList.add('active');

    const kra = button.dataset.kra;
    const name = button.dataset.name;
    const email = button.dataset.email;
    const phone = button.dataset.phone;
    const address = button.dataset.address;
    const id = button.dataset.id;

    document.getElementById('supplierEditKra').value = kra;
    document.getElementById('supplierEditName').value = name;
    document.getElementById('supplierEditEmail').value = email;
    document.getElementById('supplierEditPhone').value = phone;
    document.getElementById('supplierEditAddress').value = address;
    document.getElementById('supplierEditId').value = id;

    function closeEditSupplierModal() {
        supplierEditOverlay.classList.remove('active');
        supplierEditModal.classList.remove('active');
    }


    // Close supplier list modal
    if (closeBtn) {
        closeBtn.addEventListener("click", closeEditSupplierModal);
    }

    // Close when clicking outside
    supplierEditOverlay.addEventListener("click", (e) => {
        if (e.target === supplierEditOverlay) {
            supplierEditOverlay.classList.remove("active");
            supplierEditModal.classList.remove("active");
        }

    });

}

// register supplier modal
export function initSupplierModal(invalidFields) {
    const supplierOpenBtn = document.querySelector('.supplier-open-btn')
        || document.getElementById('registerSupplierButton');
    const supplierCloseBtn = document.querySelector('#supplierCloseBtn');
    const supplierCancelBtn = document.querySelector('#supplierCancelBtn');
    const supplierOverlay = document.querySelector('#supplierOverlay');
    const supplierModal = document.querySelector('#supplierModal');
    const supplierForm = document.querySelector('#supplierForm');

    function openSupplierModal() {
        supplierOverlay.classList.add('active');
        supplierModal.classList.add('active');
    }

    function closeSupplierModal() {
        supplierOverlay.classList.remove('active');
        supplierModal.classList.remove('active');
    }

    // Event listeners (guard against nulls)
    if (supplierOpenBtn) supplierOpenBtn.addEventListener('click', openSupplierModal);
    if (supplierCloseBtn) supplierCloseBtn.addEventListener('click', closeSupplierModal);
    if (supplierCancelBtn) supplierCancelBtn.addEventListener('click', closeSupplierModal);
    if (supplierOverlay) supplierOverlay.addEventListener('click', closeSupplierModal);

    if (supplierForm) {
        supplierForm.addEventListener('submit', function (e) {
            e.preventDefault();
            console.log(invalidFields.size)
            if (invalidFields.size == 0) {
                closeSupplierModal();
            }
        });
    }
}


// Display Pay Expense Modal
export function payExpense(expenseId, expectedAmountToPay) {
    const expenseIdInput = document.getElementById('expenseId');
    const expectedAmountToPayInput = document.getElementById('expectedAmount');
    const paymentDateInput = document.getElementById('paymentDate');
    const payExpenseForm = document.getElementById('payExpenseForm');
    const modalElement = document.getElementById('payExpenseModal');

    console.log("Sending expenseId:", expenseId);

    if (!expenseIdInput || !paymentDateInput || !payExpenseForm || !modalElement) {
        console.error("Modal or form elements not found.");
        return;
    }

    // Reset the form first
    payExpenseForm.reset();

    // Set hidden input with expense ID
    expenseIdInput.value = expenseId;
    expectedAmountToPayInput.value = expectedAmountToPay;
    // Set amount to pay (now after reset)
    document.getElementById('amountToPay').value = parseFloat(expectedAmountToPay);

    // Set today's date
    const today = new Date().toISOString().split('T')[0];
    paymentDateInput.value = today;

    // Show the modal
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}

// add data to edit expanse payment Modal
export function editExpModal() {
    const editButtons = document.querySelectorAll(".edit-payment-btn");
    editButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            // read the amount from the clicked button
            let amount = this.getAttribute("data-amount");
           
            // populate the modal field
            amount = amount.replace(/,/g, "");
            document.getElementById("editAmount").value = parseFloat(amount) || 0;

        });
    });

}