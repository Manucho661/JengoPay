
export function setupExpenseCalculator() {
  //Add Expenses
  function calculateTotal() {
    console.log('total fired');
    const vatInclusiveContainer = document.getElementById('vatAmountInclusiveContainer');
    const vatAmountContainer = document.getElementById('vatAmountContainer');
    const zeroRatedContainer = document.getElementById('zeroRatedContainer');
    const exemptedContainer = document.getElementById('ExemptedContainer');
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
      const item_totalForStorage = row.querySelector('.item_totalForStorage');
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

      } else if (taxOption === 'zeroRated') {
        isZeroRated = true;
        total = unitPrice * qty;
        itemTax = 0;
        document.getElementById('taxLabel').textContent = "VAT 0%:";
      } else if (taxOption === 'exempted') {
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
        let discountAmount = (discount / 100) * total;
        total = total - discountAmount;
        item_total.value = 'Ksh ' + total.toFixed(2);
        item_totalForStorage.value = total.toFixed(2);
      }

      // Remove discount, remove inclusive tax, add exclusive tax
      subTotal = lineTotal;
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
    if (isZeroRated) {
      zeroRatedContainer.style.setProperty('display', 'flex', 'important');

    } else {
      zeroRatedContainer.style.setProperty('display', 'none', 'important');
    }

    if (isExempted) {
      exemptedContainer.style.setProperty('display', 'flex', 'important');

    } else {
      exemptedContainer.style.setProperty('display', 'none', 'important');
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
};