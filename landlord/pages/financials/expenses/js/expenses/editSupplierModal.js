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
    supplierEditOverlay.addEventListener("click", (e)=>{
      if (e.target === supplierEditOverlay) {
        supplierEditOverlay.classList.remove("active");
        supplierEditModal.classList.remove("active");
      }

    });
    
}