// custom select
function initializeCustomSelect(wrapper) {
  const select = wrapper.querySelector('.custom-select');
  const optionsContainer = wrapper.querySelector('.select-options');
  const options = wrapper.querySelectorAll('[role="option"]');

  const closeOptions = () => {
    optionsContainer.style.display = 'none';
    select.classList.remove('open');
    select.setAttribute('aria-expanded', 'false');
  };

  select.addEventListener('click', () => {
    const isOpen = optionsContainer.style.display === 'block';
    optionsContainer.style.display = isOpen ? 'none' : 'block';
    select.classList.toggle('open', !isOpen);
    select.setAttribute('aria-expanded', !isOpen);
  });

  options.forEach(option => {
    option.addEventListener('click', () => {
      select.textContent = option.textContent;
      select.setAttribute('data-value', option.dataset.value);
      options.forEach(opt => opt.classList.remove('selected'));
      option.classList.add('selected');
      closeOptions();
    });
  });

  document.addEventListener('click', (e) => {
    if (!wrapper.contains(e.target)) {
      closeOptions();
    }
  });

  select.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      select.click();
    }
    if (e.key === 'Escape') {
      closeOptions();
    }
  });
}

// combo-box
function combobox() {
  const comboBox = document.querySelector('.combo-box');
  const input = comboBox.querySelector('.combo-input');
  const button = comboBox.querySelector('.combo-button');
  const optionsList = comboBox.querySelector('.combo-options');
  const options = comboBox.querySelectorAll('.combo-option');

  // Toggle dropdown
  button.addEventListener('click', function (e) {
    e.stopPropagation();
    optionsList.classList.toggle('show');
    if (optionsList.classList.contains('show')) {
      input.focus();
    }
  });

  // Handle option selection
  options.forEach(option => {
    option.addEventListener('click', function () {
      input.value = this.textContent;
      optionsList.classList.remove('show');

      // Update selected style
      options.forEach(opt => opt.classList.remove('selected'));
      this.classList.add('selected');

      // Here you would filter your table/data
      console.log('Selected:', this.getAttribute('data-value'));
    });
  });

  // Filter options while typing
  input.addEventListener('input', function () {
    const searchTerm = this.value.toLowerCase();
    let hasVisibleOptions = false;

    options.forEach(option => {
      const optionText = option.textContent.toLowerCase();
      if (optionText.includes(searchTerm)) {
        option.style.display = 'block';
        hasVisibleOptions = true;
      } else {
        option.style.display = 'none';
      }
    });

    // Show "no results" if needed
    const noResults = optionsList.querySelector('.no-results');
    if (!hasVisibleOptions) {
      if (!noResults) {
        const noResultsElem = document.createElement('li');
        noResultsElem.className = 'no-results';
        noResultsElem.textContent = 'No matches found';
        optionsList.appendChild(noResultsElem);
      }
    } else if (noResults) {
      noResults.remove();
    }

    optionsList.classList.add('show');
  });

  // Close when clicking outside
  document.addEventListener('click', function () {
    optionsList.classList.remove('show');
  });

  // Keyboard navigation
  input.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowDown' && !optionsList.classList.contains('show')) {
      optionsList.classList.add('show');
    }
  });
}

document.addEventListener("DOMContentLoaded", function () {
  console.log('document loaded');
  combobox();
  document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
    initializeCustomSelect(wrapper);
    if (typeof bindItemHiddenInput === 'function') {
      bindItemHiddenInput(wrapper); // optional, if used
    }
  });
  
  //Add Expenses
  function calculateTotal() {
    console.log('total fired');
    const vatInclusiveContainer = document.getElementById('vatAmountInclusiveContainer');
    const vatAmountContainer = document.getElementById('vatAmountContainer');
    const zeroRatedExmptedContainer = document.getElementById('zeroRated&ExmptedContainer');
    let subTotal = 0;
    let vatAmountInclusive = 0;
    let vatAmountExclusive = 0;
    let totalVat = 0;
    let grandTotal = 0;
    let grandDiscount = 0;

    let hasInclusive = false;
    let hasExclusive = false;
    let isZeroRated = false;
    let isExempted = false;
    document.querySelectorAll('.item-row').forEach(row => {
      const qty = parseFloat(row.querySelector('.qty')?.value || 0);
      let unitPrice = parseFloat(row.querySelector('.unit-price')?.value || 0);
      let discount = parseFloat(row.querySelector('.discount')?.value || 0);
      const taxOption = row.querySelector("select[name='taxes[]']")?.value || "";
      const item_total = row.querySelector('.item-total');

      let total = 0;
      let itemTax = 0;
      let itemTaxInclusive = 0;
      let itemTaxExclusive = 0;
      // subtotal
      let lineTotal = unitPrice * qty; // ✅ Subtotal line without tax
      // grandTotal
      if (taxOption.includes('inclusive')) {
        hasInclusive = true;
        const basePrice = unitPrice / 1.16;
        total = basePrice * qty * 1.16;
        itemTaxInclusive = (basePrice * qty * 0.16);
      } else if (taxOption.includes('exclusive')) {
        hasExclusive = true;
        total = unitPrice * qty * 1.16;
        itemTaxExclusive = unitPrice * qty * 0.16;

      } else if (taxOption === 'zero') {
        isZeroRated = true;
        total = unitPrice * qty;
        itemTax = 0;
        document.getElementById('taxLabel').textContent = "VAT 0%:";
      } else if (taxOption === 'exempt') {
        isExempted = true;
        total = unitPrice * qty;
        itemTax = 0;
        document.getElementById('taxLabel').textContent = "EXEMPTED";
      }
      else {
        total = unitPrice * qty; // fallback
      }

      if (item_total) {
        // Extract discount From total
        total = total - discount;
        item_total.value = 'Ksh ' + total.toFixed(2);
      }

      // Remove discount, remove inclusive tax, add exclusive tax
      subTotal += (total - itemTaxInclusive + itemTaxExclusive);
      vatAmountInclusive += itemTaxInclusive;
      vatAmountExclusive += itemTaxExclusive;
      totalVat = vatAmountInclusive + vatAmountExclusive;
      grandDiscount += discount;
      grandTotal += total;
    });

    // vatInclusiveContainer.style.display = hasInclusive ? 'block' : 'non';
    if (hasExclusive || hasInclusive) {
      vatAmountContainer.style.setProperty('display', 'flex', 'important');

    } else {
      vatAmountContainer.style.setProperty('display', 'none', 'important');
    }
    if (isExempted || isZeroRated) {
      zeroRatedExmptedContainer.style.setProperty('display', 'flex', 'important');

    } else {
      zeroRatedExmptedContainer.style.setProperty('display', 'none', 'important');
    }

    document.getElementById('subTotal').value = 'Ksh ' + subTotal.toFixed(2);
    document.getElementById('subTotalhidden').value = subTotal.toFixed(2);
    document.getElementById('vatAmountInclusive').value = 'Ksh ' + vatAmountInclusive.toFixed(2);
    document.getElementById('vatAmountExclusive').value = 'Ksh ' + vatAmountExclusive.toFixed(2);
    // Visible input
    document.getElementById('vatAmountTotal').value = 'Ksh ' + totalVat.toFixed(2); //
    // hidden input
    document.getElementById('vatAmountTotalHidden').value = totalVat.toFixed(2);
    // grandTotal
    document.getElementById('grandDiscount').value = 'Ksh ' + grandDiscount.toFixed(2);
    document.getElementById('grandTotal').value = 'Ksh ' + grandTotal.toFixed(2);
    document.getElementById('grandTotalNumber').value = grandTotal.toFixed(2);

  }

  function attachEvents(row) {
    ["input", "change"].forEach(evt => {
      row.querySelectorAll(".unit-price, .qty, .discount, .form-select").forEach(el =>
        el.addEventListener(evt, calculateTotal)
      );
    });
  }

  // Attach events to all existing rows
  document.querySelectorAll(".item-row").forEach(attachEvents);

  // ✅ Initial calculation on page load
  calculateTotal();

  // add new Item row
  window.addRow = function () {
    const container = document.getElementById('itemsContainer');
    const existingRow = document.querySelector('.item-row');
    const newRow = existingRow.cloneNode(true);

    // Clear inputs in new row
    newRow.querySelectorAll('input, textarea, select').forEach(el => {
      if (el.tagName === 'SELECT') {
        el.selectedIndex = 0;
      } else {
        el.value = '';
      }
    });

    // Reset custom select display text to default placeholder
    const customSelect = newRow.querySelector('.custom-select');
    if (customSelect) {
      customSelect.textContent = 'select'; // replace with your placeholder if different
      customSelect.removeAttribute('data-value'); // clear selected value if needed
    }

    // Append new row to container
    container.appendChild(newRow);

    // Attach calculation events to new row inputs
    attachEvents(newRow);

    // Recalculate totals immediately
    calculateTotal();
  };
});


// create an expense
document.getElementById("expenseForm").addEventListener("submit", function (e) {
  e.preventDefault();
  console.log('expenseForm working');

  const form = document.getElementById("expenseForm");
  const formData = new FormData(form);
  for (let [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }
  fetch("actions/expenses/createExpense.php", {
    method: "POST",
    body: formData,
  })
    .then(response => response.text())
    .then(data => {
      console.log("Server response:", data);

      // ✅ Reload the page without resubmission
      window.location.href = window.location.href;
    })
    .catch(error => {
      console.error("Error submitting form:", error);
    });
});



// Display Pay Expense Modal
function payExpense(expenseId, expectedAmountToPay) {
  const expenseIdInput = document.getElementById('expenseId');
  const expectedAmountToPayInput = document.getElementById('expectedAmount');
  const paymentDateInput = document.getElementById('paymentDate');
  const payExpenseForm = document.getElementById('payExpenseForm');
  const modalElement = document.getElementById('payExpenseModal');

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


// payExpense
document.getElementById("payExpenseForm").addEventListener("submit", function (e) {
  e.preventDefault();
  console.log('PayexpenseForm working');

  const form = document.getElementById("payExpenseForm");
  const formData = new FormData(form);

  for (let [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }
  fetch("actions/expenses/payExpense.php", {
    method: "POST",
    body: formData,
  })
    .then(response => response.text())
    .then(data => {
      console.log("Server response:", data);

      // ✅ Reload the page without resubmission
      window.location.href = window.location.href;
    })
    .catch(error => {
      console.error("Error submitting form:", error);
    });
});