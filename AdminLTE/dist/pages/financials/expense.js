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




document.addEventListener("DOMContentLoaded", function () {
  console.log('document loaded');
// initialize custom select
document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
    initializeCustomSelect(wrapper);
  });

  //Add Expenses
  function calculateTotal() {
    console.log('total fired');
    let subTotal = 0;
    let vatAmount = 0;
    let grandTotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
      const qty = parseFloat(row.querySelector('.qty')?.value || 0);
      let unitPrice = parseFloat(row.querySelector('.unit-price')?.value || 0);
      const taxOption = row.querySelector("select[name='taxes[]']")?.value || "";
      const item_total = row.querySelector('.item-total');
      let total = 0;
      let itemTax= 0;
      // subtotal
      let lineTotal = unitPrice * qty; // ✅ Subtotal line without tax
      // grandTotal
      if (taxOption.includes('inclusive')) {
        const basePrice = unitPrice / 1.16;
        total = basePrice * qty * 1.16;
        itemTax = (basePrice * qty * 0.16); // VAT portion
      } else if (taxOption.includes('exclusive')) {
        total = unitPrice * qty * 1.16;
        itemTax = unitPrice * qty * 0.16;
      } else if(taxOption === 'zero' || taxOption === 'exempt'){
        total = unitPrice * qty;
        itemTax = 0;
      }
      else {
        total = unitPrice * qty; // fallback
      }

       if (item_total) {
      item_total.value = 'Ksh ' + total.toFixed(2);
      } 
      subTotal += (total - itemTax);
      vatAmount += itemTax;
      grandTotal += total;
    });
    
    document.getElementById('subTotal').value = 'Ksh ' + subTotal.toFixed(2);
    document.getElementById('vatAmount').value= `Ksh ${vatAmount.toFixed(2)}`;
    document.getElementById('grandTotal').value = 'Ksh ' + grandTotal.toFixed(2);
  }

  function attachEvents(row) {
    ["input", "change"].forEach(evt => {
      row.querySelectorAll(".unit-price, .qty, .form-select").forEach(el =>
        el.addEventListener(evt, calculateTotal)
      );
    });
  }

  // Attach events to all existing rows
  document.querySelectorAll(".item-row").forEach(attachEvents);

  // ✅ Initial calculation on page load
  calculateTotal();

  // add new Item row
  window.addRow = function() {
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

  // Reinitialize custom select functionality for the new row
  const newCustomSelectWrapper = newRow.querySelector('.custom-select-wrapper');
  if (newCustomSelectWrapper) {
    initializeCustomSelect(newCustomSelectWrapper);
  }

  // Recalculate totals immediately
  calculateTotal();
};


});
