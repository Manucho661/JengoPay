import {openEditModal} from "./modals.js";

export function initSupplierListModal() {
  const openBtn = document.getElementById("supplier-list-open-btn");
  const overlay = document.getElementById("supplierListOverlay");
  const modal = document.getElementById("supplierListModal");
  const closeBtn = document.querySelector(".supplier-list-close-btn");
  const openEditBtns = document.querySelectorAll('.editSupplier');
  

  if (openBtn && overlay && modal) {
    // Open modal
    openBtn.addEventListener("click", (e) => {
      e.preventDefault();
      overlay.classList.add("active");
      modal.classList.add("active");
    });

    // Close modal function
    function closeListModal() {
      overlay.classList.remove("active");
      modal.classList.remove("active");
    }

  
    // Close supplier list modal
    if (closeBtn) {
      closeBtn.addEventListener("click", closeListModal);
    }


    // close list modal and open register modal
    if (openEditBtns.length > 0) {
      openEditBtns.forEach((btn) => {
        btn.addEventListener("click", ()=>{
          closeListModal();
          openEditModal(btn);
        });
      });
    }

    // Close when clicking outside
    overlay.addEventListener("click", (e) => {
      if (e.target === overlay) {
        overlay.classList.remove("active");
        modal.classList.remove("active");
      }
    });
  } else {
    console.warn("Supplier list modal elements not found in DOM.");
  }
}
