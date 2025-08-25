export function initSupplierModal() {
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
            alert("Supplier Registered:\n" +
                "KRA: " + supplierForm.kra.value + "\n" +
                "Name: " + supplierForm.name.value + "\n" +
                "Email: " + supplierForm.email.value);
            supplierForm.reset();
            closeSupplierModal();
        });
    }
}
