
      // Add expense
  document.addEventListener("DOMContentLoaded", function () {
  console.log('documentloading');
   function calculateTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
      const qty = parseFloat(row.querySelector('.qty')?.value || 0);
      let unitPrice = parseFloat(row.querySelector('.unit-price')?.value || 0);
      unitPrice = unitPrice / 1.16; // Extract base price
      total = unitPrice * qty * 1.16; // Add back VAT
      // row.querySelector('.total-line').value = total.toFixed(2);
      grandTotal = total;
    })
   }


  function updateTotalAmount1() {
    console.log('update Total function working'); // ✅ Should now fire on input changes
  }

  function attachEvents(row) {
    ["input", "change"].forEach(evt => {
      row.querySelectorAll(".unit-price, .qty, .form-select").forEach(el =>
        el.addEventListener(evt, updateTotalAmount1)
      );
    });
  }

  // ✅ Apply event listeners to all item rows
  document.querySelectorAll(".item-row").forEach(attachEvents);
});
