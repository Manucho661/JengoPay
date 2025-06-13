// fetch maintanence records.
fetch('actions/fetch_records.php', { cache: 'no-store' })
.then(response => response.text()) // read as plain text
.then(rawText => {
  // console.log("🔍 Raw response from PHP:", rawText);
  try {
    const data = JSON.parse(rawText);
    console.log("✅ Parsed JSON:", data);

    if (data.success) {
      populateRequestsTable(data.data);
    } else {
      console.error("⚠️ Backend error:", data.error);
    }
  } catch (e) {
    console.error("❌ JSON parsing error:", e.message);
  }
})
.catch(error => {
  console.error("❌ Network error:", error.message);
});

// populate maintenanceRequests Table
function populateRequestsTable(requests) {
  const tableBody = document.getElementById("maintenanceRequestsTableBody");
  tableBody.innerHTML = ""; // Clear any existing rows
  requests.forEach(requests => {
    const row = document.createElement("tr");

    // ✅ STEP 1: Create statusHTML
    let statusHTML = '';
    const status = (requests.status || '').toLowerCase();

    if (status === 'in progress') {
      statusHTML = `
        <td>
          <span class="status in-progress">
            <i class="fas fa-spinner fa-spin"></i> In Progress
          </span>
        </td>`;
    } else if (status === 'completed') {
      statusHTML = `
        <td>
          <span class="status completed" >
            <i class="fas fa-check-circle"></i> Completed
          </span>
        </td>`;

    }

    else if (status === 'pending') {
      statusHTML = `
        <td>
          <span class="status completed" >
            <i class="fas fa-check-circle"></i> Pending
          </span>
        </td>`;
    }
    else if (status === 'cancelled') {
      statusHTML = `
        <td>
          <span class="status cancelled" >
            <i class="fas fa-check-circle"></i> Cancelled
          </span>
        </td>`;
    }
    else if (status === 'in_progress') {
      statusHTML = `
        <td>
          <span class="status completed" >
           <i class="fas fa-spinner fa-spin"></i> In Progress
          </span>
        </td>`;
    }
    else if (status === 'incomplete') {
      statusHTML = `
        <td>
          <span class="status incomplete">
            <i class="fas fa-times-circle"></i> Incomplete
          </span>
        </td>`;
    } else {
      statusHTML = `
        <td>
          <span class="status unknown" style="color: gray;">
            <i class="fas fa-question-circle"></i> Unknown
          </span>
        </td>`;
    }
    row.innerHTML = `
      <td>${requests.request_date || ''}</td>
      <td>${requests.id || ''}</td>
      <td>
      <div>${requests.residence}</div>
      <div style="color: green;">${requests.unit}</div>
      </td>

      <td>
      <div>${requests.category }</div>
        <div style="color:green; border:none; width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; ">${requests.description }</div>

      </td>
      <td>
      <div>${requests.provider_name|| ''} </div>
      <div class="email" style="width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; ">📧 </i> ${requests.provider_email|| ''} </div>
      </td>
      <td>${requests.priority|| ''} </td>
      ${statusHTML}
      <td>
        <div class="${requests.payment_status}">
          <i class="${requests.payment_status === 'Paid' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'}"></i>
          ${requests.payment_status}
        </div>
      </td>

      <td style="vertical-align: middle;">
      <div style="display: flex; flex-direction: column; justify-content: center; height: 100%;">
      <div class="d-flex gap-15px" style="align-items: center;">
      <div>
        <button class="btn btn-sm pay-btn d-flex"
          data-building-name="${requests.building_name}"
          data-unit="${requests.unit || ''}"
          data-request-id="${requests.id}"
          style="background-color: #00192D; color:#FFC107"> <i class="fas fa-coins" style="margin-right:3px;"></i>
          Pay
        </button>
      </div>
      <div>
        <button class="btn btn-sm view-btn"
          style="background-color: #193042; margin-left:10px; color:#fff;"
          title="View"
          data-id="${requests.id}"
          data-status="${status}">
          <i class="fas fa-eye"></i>
        </button>
      </div>
      <div class="dropdown">
            <button class="btn btn-sm more-btn d-flex" data-bs-toggle="dropdown" aria-expanded="false">⋮</button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item assign-provider" href="#" style="color: #FFA000 !important;"> <i class="fas fa-tasks"></i> Assign Provider</a></li>
              <li><a class="dropdown-item mark-complete" href="#" style="color: #FFA000 !important;"> <i class="fas fa-tasks"></i> Mark Complete</a></li>
              <li><a class="dropdown-item view-payment" href="#" style="color: #FFA000 !important;"  data-request-id="${requests.id}" ><i class="fas fa-eye"></i> View Payment</a></li>
              <li>
              <a class="dropdown-item delete-request" href="#" style="color: #F87171 !important;" data-request-id="${requests.id}"><i class="fas fa-trash"></i> Delete Request</a>
            </li>
            </ul>
          </div>
    </div>
      </div>
    </td>
    `;
   // Add the event listener here AFTER the row is in memory
     const tempDiv = document.createElement('div');
     tempDiv.appendChild(row);
      const payBtn = tempDiv.querySelector('.pay-btn');
      payBtn.addEventListener('click', (e) => {
      const btn = e.currentTarget;
      // const buildingName = btn.getAttribute('data-building-name');
      //  const unit = btn.getAttribute('data-unit');
      const requestId = btn.getAttribute('data-request-id');

      // document.getElementById('modal_building_name').textContent = buildingName;
      // document.getElementById('modal_unit').textContent = unit;
      document.getElementById('modal_request_id').value = requestId;
      const modal = new bootstrap.Modal(document.getElementById('recordPaymentModal'));
      modal.show();
      });

      // View Request
      const viewBtn = tempDiv.querySelector('.view-btn');
      viewBtn.addEventListener('click', (e) =>{
        const modal = new bootstrap.Modal(document.getElementById('maintenanceRequestModal'));
        modal.show();
      });

      // Assign provider
       const assignProviderBtn = tempDiv.querySelector('.assign-provider');
        assignProviderBtn.addEventListener('click', (e) =>{
        const modal = new bootstrap.Modal(document.getElementById('assignProviderModal'));
        modal.show();
      });

      // Mark complete
      const markCompleteBtn = tempDiv.querySelector('.mark-complete');
        markCompleteBtn.addEventListener('click', (e) =>{
        markComplete(requests.id);
      });

      // Delete Request
      const deleteBtn = tempDiv.querySelector('.delete-request');
      deleteBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const confirmed = confirm("Are you sure you want to delete this request?");
        if (!confirmed) return;

        const requestId = e.currentTarget.getAttribute('data-request-id');

        fetch(`../maintenance/actions/delete_request.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            request_id: requestId
          })
        })

        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("Maintenance Request deleted successfully.");
            location.reload(); // or re-fetch data
          } else {
            alert("Error deleting request: " + data.error);
          }
        })
        .catch(err => {
          console.error("Network error:", err);
          alert("Failed to delete the request.");
        });
      });


    tableBody.appendChild(tempDiv.firstChild); // append the full row
  });

  // view payment
  document.addEventListener('click', function (e) {
    if (e.target.closest('.view-payment')) {
      e.preventDefault();
      const btn = e.target.closest('.view-payment');
      const requestId = btn.getAttribute('data-request-id');

      fetch(`../maintenance/actions/get_payment_details.php?maintenance_request_id=${requestId}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const p = data.payment;

            document.getElementById('view_amountPaid').innerText = p.amount_paid || '-';
            document.getElementById('view_paymentMethod').innerText = p.payment_method || '-';
            document.getElementById('view_datePaid').innerText = p.date_paid || '-';
            document.getElementById('view_serviceProvider').innerText = p.service_provider || '-';
            document.getElementById('view_chequeNumber').innerText = p.cheque_number || '-';
            document.getElementById('view_invoiceNumber').innerText = p.invoice_number || '-';
            document.getElementById('view_paymentNotes').innerText = p.notes || '-';

            if (p.receipt_url) {
              document.getElementById('view_receiptLink').innerHTML = `
                <a href="${p.receipt_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                  View Receipt
                </a>`;
            } else {
              document.getElementById('view_receiptLink').innerHTML = `<span class="text-muted">No receipt uploaded</span>`;
            }

            // Show modal
            const viewModal = new bootstrap.Modal(document.getElementById('viewPaymentModal'));
            viewModal.show();
          } else {
            alert('Error: ' + data.error);
          }
        })
        .catch(err => {
          console.error(err);
          alert('Failed to load payment details.');
        });
    }
  });


// add dataTable
  const table = $('#requests-table').DataTable({
        dom: 'Brtip',
        order: [],
        buttons: [
          {
            extend: 'excelHtml5',
            text: 'Excel',
            exportOptions: { columns: ':not(:last-child)' }
          },
          {
            extend: 'pdfHtml5',
            text: 'PDF',
            exportOptions: { columns: ':not(:last-child)' },
            customize: function (doc) {
              doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
              doc.styles.tableHeader.alignment = 'center';
              doc.styles.tableBodyEven.alignment = 'center';
              doc.styles.tableBodyOdd.alignment = 'center';

              const body = doc.content[1].table.body;
              for (let i = 1; i < body.length; i++) {
                if (body[i][4]) body[i][4].color = 'blue';
              }
            }
          }
        ]
      });

      table.buttons().container().appendTo('#custom-buttons');

      $('#searchInput').on('keyup', function () {
        table.search(this.value).draw();
      });
}

// Add a payment
function addRequestPayment(event){
  event.preventDefault(); // Prevent the form from submitting immediately
  const form = document.getElementById("recordPaymentForm");
  const formData = new FormData(form);

  // 🔍 Log actual contents of the FormData
   for (let [key, value] of formData.entries()) {
     console.log(`${key}: ${value}`);
   }
     fetch("actions/add_records.php", {
            method: "POST",
            body: formData
          })
          .then(res => res.text())
          .then(data => {
            alert(data); // Display success message or error from server
            location.reload(); // Reload the page to reflect changes (optional)
          })
          .catch(err => console.error(err));
};

// Mark Complete
function markComplete(itemId, status = 'completed'){
   fetch(`actions/update_records.php?type=mark_item&item_id=${itemId}&status=${status}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log("Item updated successfully.");
        // Optionally refresh part of the UI
      } else {
        console.error("Backend error:", data.error);
      }
    })
    .catch(error => {
      console.error("Network error:", error);
    });
}