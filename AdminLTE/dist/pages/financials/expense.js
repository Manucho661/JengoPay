
  document.addEventListener("DOMContentLoaded", function () {
    function formatNumber(num) {
      return num.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function calculateRow(row) {
      const unitInput = row.querySelector(".unit-price");
      const quantityInput = row.querySelector(".quantity");
      const vatSelect = row.querySelector(".vat-option");
      const totalInput = row.querySelector(".total");

      const unitPrice = parseFloat(unitInput?.value) || 0;
      const quantity = parseFloat(quantityInput?.value) || 0;
      let subtotal = unitPrice * quantity;

      let vatAmount = 0;
      let total = subtotal;
      const vatType = vatSelect?.value;

      if (vatType === "inclusive") {
        subtotal = subtotal / 1.16;
        vatAmount = total - subtotal;
      } else if (vatType === "exclusive") {
        vatAmount = subtotal * 0.16;
        total += vatAmount;
      } else if (vatType === "zero" || vatType === "exempted") {
        vatAmount = 0;
        total = subtotal;
      }

      totalInput.value = formatNumber(total);
      return { subtotal, vatAmount, total, vatType };
    }

    function updateTotalAmount() {
      let subtotalSum = 0, taxSum = 0, grandTotal = 0;
      let vat16Used = false, vat0Used = false, exemptedUsed = false;

      document.querySelectorAll(".items-table tbody tr").forEach(row => {
        if (row.querySelector(".unit-price")) {
          const { subtotal, vatAmount, total, vatType } = calculateRow(row);
          subtotalSum += subtotal;
          taxSum += vatAmount;
          grandTotal += total;

          if (vatType === "inclusive" || vatType === "exclusive") vat16Used = true;
          else if (vatType === "zero") vat0Used = true;
          else if (vatType === "exempted") exemptedUsed = true;
        }
      });

      createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, vat16Used, vat0Used, exemptedUsed });
    }

    function createOrUpdateSummaryTable({ subtotalSum, taxSum, grandTotal, vat16Used, vat0Used, exemptedUsed }) {
      let summaryTable = document.querySelector(".summary-table");

      if (!summaryTable) {
        summaryTable = document.createElement("table");
        summaryTable.id = "summary-table"; // 👈 Add this line
        summaryTable.className = "summary-table table table-bordered";
        summaryTable.style = "width: 20%; float: right; font-size: 0.8rem; margin-top: 10px;";
        summaryTable.innerHTML = `<tbody></tbody>`;
        document.querySelector(".items-table").after(summaryTable);
      }

      const tbody = summaryTable.querySelector("tbody");
      tbody.innerHTML = `
        <tr>
          <th style="width: 50%; padding: 5px;">Sub-total</th>
          <td><input type="text" class="form-control" value="${formatNumber(subtotalSum)}" readonly></td>
        </tr>
        ${vat16Used ? `
        <tr>
          <th>VAT 16%</th>
          <td><input type="text" class="form-control" value="${formatNumber(taxSum)}" readonly></td>
        </tr>` : ''}
        ${vat0Used ? `
        <tr>
          <th>VAT 0%</th>
          <td><input type="text" class="form-control" value="0.00" readonly></td>
        </tr>` : ''}
        ${exemptedUsed ? `
        <tr>
          <th>Exempted</th>
          <td><input type="text" class="form-control" value="0.00" readonly></td>
        </tr>` : ''}
        <tr>
          <th>Total</th>
          <td><input type="text" class="form-control" value="${formatNumber(grandTotal)}" readonly></td>
        </tr>
      `;
    }

    function attachEvents(row) {
      ["input", "change"].forEach(evt => {
        row.querySelectorAll(".unit-price, .quantity, .vat-option").forEach(el =>
          el.addEventListener(evt, updateTotalAmount)
        );
      });
    }

    window.addRow = function () {
      const table = document.querySelector(".items-table tbody");
      const newRow = document.createElement("tr");
      newRow.innerHTML = `
        <td>
          <select name="payment_method" required>
            <option value="" disabled selected>Select Option</option>
            <option value="credit_card">Rent</option>
            <option value="paypal">Water Bill</option>
            <option value="bank_transfer">Garbage</option>
          </select>
        </td>
        <td><textarea name="Description" placeholder="Description" rows="1" required></textarea></td>
        <td><input type="number" class="form-control quantity" placeholder="1"></td>
        <td><input type="number" class="form-control unit-price" placeholder="123"></td>
        <td>
          <select class="form-select vat-option">
            <option value="" disabled selected>Select Option</option>
            <option value="inclusive">VAT 16% Inclusive</option>
            <option value="exclusive">VAT 16% Exclusive</option>
            <option value="zero">Zero Rated</option>
            <option value="exempted">Exempted</option>
          </select>
        </td>
        <td>
          <input type="text" class="form-control total" placeholder="0" readonly>
          <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
            <i class="fa fa-trash" style="font-size: 12px;"></i>
          </button>
        </td>
      `;
      table.appendChild(newRow);
      attachEvents(newRow);
    };

    window.deleteRow = function (btn) {
      btn.closest("tr").remove();
      updateTotalAmount();
    };

    document.querySelectorAll(".items-table tbody tr").forEach(attachEvents);
    updateTotalAmount();
  });




  const   more_announcement = document.getElementById('more_announcement_btn');
  const   view_announcement = document.getElementById('view_announcement');
  const   close_overlay = document.getElementById("close-overlay-btn");

  more_announcement.addEventListener('click', ()=>{

     view_announcement.style.display= "flex";
     document.querySelector('.app-wrapper').style.opacity = '0.3'; // Reduce opacity of main content
     const now = new Date();
            const formattedTime = now.toLocaleString(); // Format the date and time
            timestamp.textContent = `Sent on: ${formattedTime}`;
  });

     close_overlay.addEventListener('click', ()=>{

     view_announcement.style.display= "none";
     document.querySelector('.app-wrapper').style.opacity = '1';


     });

    function addRow() {
      const table = document.querySelector(".items-table tbody");
      const newRow = document.createElement("tr");
      newRow.innerHTML = `
        <td>
                                            <select name="payment_method" required>
                                              <option value="" disabled selected>Select Option</option>
                                              <option value="credit_card">Rent</option>
                                              <option value="paypal">Water Bill</option>
                                              <option value="bank_transfer">Garbage</option>
                                            </select>
                                          </td>
                                          <td><textarea name="Description" placeholder="Description" rows="1" required></textarea></td>
                                          <td><input type="number" class="form-control quantity" placeholder="1"></td>
                                          <td><input type="number" class="form-control unit-price" placeholder="123"></td>
                                          <td>
                                            <select class="form-select vat-option">
                                              <option value="" disabled selected>Select Option</option>
                                              <option value="inclusive">VAT 16% Inclusive</option>
                                              <option value="exclusive">VAT 16% Exclusive</option>
                                              <option value="zero">Zero Rated</option>
                                              <option value="exempted">Exempted</option>
                                            </select>
                                          </td>
                                          <td>
                                            <input type="text" class="form-control total" placeholder="0" readonly>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" onclick="deleteRow(this)" title="Delete">
                                              <i class="fa fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                          </td>

      `;
      table.appendChild(newRow);
  }
  function deleteRow(btn) {
      btn.closest("tr").remove();
  }
      function printInvoice() {
          window.print();
      }
      function downloadPDF() {
          const element = document.querySelector(".invoice-container");
          html2pdf().from(element).save("invoice.pdf");
      }