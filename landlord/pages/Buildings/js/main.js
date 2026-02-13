
import { getTenantUnitDetails } from "./getTenantUnitDetails.js";


document.getElementById("buttonContainer").addEventListener("click", function (e) {
  if (e.target.tagName === "BUTTON") {
    const id = e.target.dataset.attributeId;
    myFunction(id);
  }
});

function myFunction(id) {
  console.log("Button clicked:", id);
}


function confirmAddUnit(event, buildingName) {
    event.preventDefault();
    const url = event.currentTarget.getAttribute('href');
    Swal.fire({
        title: 'Add Single Unit?',
        text: "Do you want to add a single unit to building: " + buildingName + "?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, continue'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

function confirmAddBedsitter(event, buildingName) {
    event.preventDefault();
    const url = event.currentTarget.getAttribute('href');
    Swal.fire({
        title: 'Add Bedsitter?',
        text: "Do you want to add a bedsitter to building: " + buildingName + "?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, continue'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

function confirmAddMultiRooms(event, buildingName) {
    event.preventDefault();
    const url = event.currentTarget.getAttribute('href');
    Swal.fire({
        title: 'Add Multi Rooms?',
        text: "Do you want to add multi rooms to building: " + buildingName + "?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, continue'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}



$(document).ready(function () {
    $("#firstSectionNexttBtn").click(function () {
        $("#secondSection").show();
        $("#firstSection").hide();
    });

    $("#secondSectionBackBtn").click(function () {
        $("#secondSection").hide();
        $("#firstSection").show();
    });

    $("#secondSectionNextBtn").click(function () {
        $("#secondSection").hide();
        $("#thirdSection").show();
    });

    $("#thirdSectionNextBtn").click(function () {
        $("#fourthSection").show();
        $("#thirdSection").hide();
    });

    $("#thirdSectionBackBtn").click(function () {
        $("#thirdSection").hide();
        $("#secondSection").show();
    });

    $("#fourthSectionNextBtn").click(function () {
        $("#fourthSection").hide();
        $("#fifthSection").show();
    });

    $("#fourthSectionBackBtn").click(function () {
        $("#fourthSection").hide();
        $("#thirdSection").show();
    });

    $("#fifthSectionBackBtn").click(function () {
        $("#fourthSection").show();
        $("#fifthSection").hide();
    });
});


// Constituency Fetching basing on the Selected COunty
function FetchConstituency(name) {
    alert('You have Chosen County Number ' + name);
    $('#constituency').html('');
    $('#ward').html('<option>Select Ward</option>');
    $.ajax({
        type: 'POST',
        url: 'processes/ajax_process.php',
        data: {
            county_id: name
        },
        success: function (data) {
            $('#constituency').html(data);
        }
    });
}

function FetchWard(name) {
    $('#ward').html('');
    $.ajax({
        type: 'POST',
        url: 'processes/ajax_process.php',
        data: {
            constituency_id: name
        },
        success: function (data) {
            $('#ward').html(data);
        }
    });
}


//Close Individual Information Modal
    function closeIndividualOwnerInfo(){
        var individualOwnerModal = document.getElementById('individual-owner');
        individualOwnerModal.style.display = 'none';
    }
    function closeEntityOwnership(){
        var entityOwnerModal = document.getElementById('entity-owner');
        entityOwnerModal.style.display = 'none';
    }
    

    // add row script
    const billOptions = [
  "Water",
  "Electricity",
  "Garbage",
  "Internet",
  "Security",
  "Management Fee"
];

function addRow() {
  const tbody = document.querySelector("#expensesTable tbody");
  if (!tbody) return;

  const row = document.createElement("tr");

  // Build select dropdown with available bills
  let options = `<option value="">-- Select Bill --</option>`;
  billOptions.forEach(opt => {
    options += `<option value="${opt}">${opt}</option>`;
  });

  row.innerHTML = `<td>
                      <select class="form-control" name="bill[]" onchange="refreshBillOptions()" required>
                          ${options}
                      </select>
                  </td>
                  <td><input type="number" class="form-control" name="qty[]" value="0" min="0" step="1" oninput="updateTotals(this)" required></td>
                  <td><input type="number" class="form-control" name="unit_price[]" value="0" min="0" step="0.01" oninput="updateTotals(this)" required></td>
                  <td class="subtotal">0.00</td>
                  <td><button type="button" class="btn btn-sm shadow" style="background-color:#cc0001; color:#fff;" onclick="removeRow(this)"><i class="bi bi-trash"></i></button></td>`;

  tbody.appendChild(row);

  // Initialize subtotal for the new row (use qty input as anchor)
  const qtyInput = row.querySelector('input[name="qty[]"]');
  if (qtyInput) updateTotals(qtyInput);

  refreshBillOptions();
  recalcTotals();
}

function removeRow(button) {
  const tr = button.closest("tr");
  if (tr) tr.remove();
  refreshBillOptions();
  recalcTotals();
}

function refreshBillOptions() {
  // Collect all selected bills
  const selects = Array.from(document.querySelectorAll('select[name="bill[]"]'));
  const selectedBills = selects.map(sel => sel.value).filter(val => val !== "");

  // Update all selects
  selects.forEach(select => {
    const currentValue = select.value;

    // clear and add placeholder
    select.innerHTML = '';
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = '-- Select Bill --';
    select.appendChild(placeholder);

    billOptions.forEach(opt => {
      // Keep current value available, hide others if already selected elsewhere
      if (!selectedBills.includes(opt) || opt === currentValue) {
        const option = document.createElement("option");
        option.value = opt;
        option.textContent = opt;
        if (opt === currentValue) option.selected = true;
        select.appendChild(option);
      }
    });
  });
}

function updateTotals(input) {
  const row = input.closest("tr");
  if (!row) return;

  const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
  const price = parseFloat(row.querySelector('input[name="unit_price[]"]').value) || 0;
  const subtotal = qty * price;

  row.querySelector(".subtotal").textContent = subtotal.toFixed(2);

  recalcTotals();
}

function recalcTotals() {
  let totalQty = 0;
  let totalUnitPrice = 0;
  let totalSubtotal = 0;

  document.querySelectorAll("#expensesTable tbody tr").forEach(row => {
    const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
    const price = parseFloat(row.querySelector('input[name="unit_price[]"]').value) || 0;
    const subtotal = parseFloat(row.querySelector(".subtotal").textContent) || 0;

    totalQty += qty;
    totalUnitPrice += price;
    totalSubtotal += subtotal;
  });

  const elQty = document.getElementById("totalQty");
  const elUnit = document.getElementById("totalUnitPrice");
  const elSub = document.getElementById("totalSubtotal");

  if (elQty) elQty.textContent = totalQty;
  if (elUnit) elUnit.textContent = totalUnitPrice.toFixed(2);
  if (elSub) elSub.textContent = totalSubtotal.toFixed(2);
}

// Initialize on DOM ready in case you have pre-existing rows
document.addEventListener('DOMContentLoaded', () => {
  refreshBillOptions();
  recalcTotals();
});
