export function combobox() {
  const comboBox = document.querySelector('.combo-box');
  const input = comboBox.querySelector('.combo-input');
  const button = comboBox.querySelector('.combo-button');
  const optionsList = comboBox.querySelector('.combo-options');
  const hiddenInput = comboBox.querySelector('.supplier-hidden-input');

  // Toggle dropdown
  button.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    optionsList.classList.toggle('show');
    if (optionsList.classList.contains('show')) {
      input.focus();
    }
  });

  // Use event delegation for dynamic options
  optionsList.addEventListener('click', function (e) {
    const option = e.target.closest('.combo-option');
    if (!option) return;

    input.value = option.textContent.trim();
    hiddenInput.value = option.getAttribute('data-value');
    optionsList.classList.remove('show');

    // Remove previous selections
    const allOptions = optionsList.querySelectorAll('.combo-option');
    allOptions.forEach(opt => opt.classList.remove('selected'));
    option.classList.add('selected');

    console.log('Selected:', hiddenInput.value);
  });

  // Filter options while typing
  input.addEventListener('input', function () {
    const searchTerm = this.value.toLowerCase();
    let hasVisibleOptions = false;
    const allOptions = optionsList.querySelectorAll('.combo-option');

    allOptions.forEach(option => {
      const optionText = option.textContent.toLowerCase();
      if (optionText.includes(searchTerm)) {
        option.style.display = 'block';
        hasVisibleOptions = true;
      } else {
        option.style.display = 'none';
      }
      // save the text in it doesn't exit
    });

    // Show "no results" message
    const noResults = optionsList.querySelector('.no-results');
    const registerSupplierLi = optionsList.querySelector('.registerSupplier');

    if (!hasVisibleOptions) {
      if (!noResults && !registerSupplierLi) {
        const noResultsElem = document.createElement('li');
        noResultsElem.className = 'no-results';
        noResultsElem.textContent = 'No matches found';

        const registerSupplierLi = document.createElement('li');
        registerSupplierLi.className = 'registerSupplier';

        // ✅ Create button (only once)
        const registerSupplierButton = document.createElement('button');
        registerSupplierButton.className = 'registerSupplierbtn';
        registerSupplierButton.id = 'registerSupplierButton';
        registerSupplierButton.innerHTML = '<span class="plus-icon"></span> Create Supplier';

        registerSupplierButton.addEventListener('click', () => {
          const supplierOverlay = document.getElementById("supplierOverlay");
          const supplierModal = document.getElementById("supplierModal");

          if (supplierOverlay && supplierModal) {
            supplierOverlay.classList.add("active");
            supplierModal.classList.add("active");
          }
        });

        registerSupplierLi.appendChild(registerSupplierButton);

        optionsList.appendChild(noResultsElem);
        optionsList.appendChild(registerSupplierLi);
      }
    } else {
      if (noResults) noResults.remove();
      if (registerSupplierLi) registerSupplierLi.remove();
    }

    optionsList.classList.add('show');
  });

  // Close when clicking outside
  document.addEventListener('click', function (e) {
    if (!comboBox.contains(e.target)) {
      optionsList.classList.remove('show');
    }
  });

  // Keyboard support
  input.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowDown' && !optionsList.classList.contains('show')) {
      optionsList.classList.add('show');
    }
  });
}