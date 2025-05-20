// IIFE to contain everything
(() => {
  // Modal toggles
  const inspectionModal = document.getElementById("add-inspection");

  function toggleModal(visible) {
    if (!inspectionModal) return;
    inspectionModal.style.display = visible ? "flex" : "none";
  }

  window.openAddInspection = () => toggleModal(true);
  window.closeAddInspection = () => toggleModal(false);

  // Custom select logic
  function setupCustomSelects() {
    document.querySelectorAll('.select-option-container').forEach(container => {
      const select = container.querySelector('.custom-select');
      const optionsContainer = container.querySelector('.select-options');
      const options = optionsContainer.querySelectorAll('div');

      select.addEventListener('click', () => {
        const isOpen = optionsContainer.style.display === "block";
        optionsContainer.style.display = isOpen ? "none" : "block";
        select.style.borderRadius = isOpen ? "5px" : "5px 5px 0 0";
        select.classList.toggle("open", !isOpen);
      });

      options.forEach(option => {
        option.addEventListener('click', () => {
          select.textContent = option.textContent;
          select.setAttribute("data-value", option.getAttribute("data-value"));
          options.forEach(opt => opt.classList.remove("selected"));
          option.classList.add("selected");
          optionsContainer.style.display = "none";
          select.style.borderRadius = "5px";
          select.classList.remove("open");
        });
      });

      // Close select dropdown when clicking outside
      document.addEventListener('click', (e) => {
        if (!e.target.closest('.select-option-container')) {
          optionsContainer.style.display = "none";
          select.style.borderRadius = "5px";
          select.classList.remove("open");
        }
      });
    });
  }

  // Generic form submission handler
  function handleFormSubmit(formId, url, extraFields = {}) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener("submit", function (event) {
      event.preventDefault();
      const formData = new FormData(form);
      Object.entries(extraFields).forEach(([key, value]) => formData.append(key, value));

      fetch(url, {
        method: "POST",
        body: new URLSearchParams(formData)
      })
        .then(res => res.text())
        .then(data => {
          alert(data); // Replace with custom notification
          location.reload();
        })
        .catch(err => console.error("Form submission failed:", err));
    });
  }

  // Initialize everything after DOM loads
  document.addEventListener("DOMContentLoaded", () => {
    setupCustomSelects();
    handleFormSubmit("form_new_inspection", "../actions/inspections/add_record.php", { type: "inspections" });
    handleFormSubmit("perform_inspection", "actions/add_record.php");
  });

})();
