export function initSupplierListModal() {
  const openBtn = document.getElementById("supplier-list-open-btn");
  const overlay = document.getElementById("supplierListOverlay");
  const modal = document.getElementById("supplierListModal");
  const closeBtn = document.querySelector(".supplier-list-close-btn");

  if (openBtn && overlay && modal) {
    // Open modal
    openBtn.addEventListener("click", (e) => {
      e.preventDefault();
      overlay.classList.add("active");
      modal.classList.add("active");
    });

    // Close modal
    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        overlay.classList.remove("active");
        modal.classList.remove("active");
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
