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
      ${requests.provider_name
          ? `<div>${requests.provider_name}</div>
            <div class="email" style="width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">📧 ${requests.provider_email || ''}</div>`
          : `<div>Not assigned</div>`
        }
      </td>
      <td>${requests.priority|| ''} </td>
      ${statusHTML}
      <td>
        <div class="${requests.payment_status}">
          <i class="${requests.payment_status === 'Paid' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'}"></i>
          ${requests.payment_status}
        </div>
      </td>

      <td class="align-middle">
        <div style="display: flex; align-items: center; gap: 10px;">
          <!-- View button -->
          <button class="btn btn-sm view-btn"
            style="background-color: #193042; color:#fff;"
            title="View"
            data-id="${requests.id}"
            data-status="${status}">
            <i class="fas fa-eye"></i>
          </button>

          <!-- Dropdown -->
          <div class="dropdown">
            <button class="btn btn-sm more-btn d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">⋮</button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item assign-provider" href="#" style="color: #FFA000;"><i class="fas fa-tasks"></i> Assign Provider</a></li>
              <li><a class="dropdown-item mark-complete" href="#" style="color: #FFA000;"><i class="fas fa-tasks"></i> Mark Complete</a></li>
              <li><a class="dropdown-item view-payment" href="#" style="color: #FFA000;" data-request-id="${requests.id}"><i class="fas fa-eye"></i> View Payment</a></li>
              <li><a class="dropdown-item delete-request" href="#" style="color: #F87171;" data-request-id="${requests.id}"><i class="fas fa-trash"></i> Delete Request</a></li>
            </ul>
          </div>
        </div>
      </td>
    `;
   // Add the event listener here AFTER the row is in memory
     const tempDiv = document.createElement('div');
     tempDiv.appendChild(row);
      // View Request
      const viewBtn = tempDiv.querySelector('.view-btn');
      viewBtn.addEventListener('click', (e) =>{
        document.getElementById('request-id').innerText= requests.id;
        document.getElementById('request-date').innerText= requests.request_date;
        document.getElementById('property-name').innerText= requests.residence;
        document.getElementById('unit-number').innerText= requests.unit;
        document.getElementById('request-category').innerText= requests.category;
        document.getElementById('request-status').innerText= requests.status;
        document.getElementById('payment-status').innerText= requests.payment_status;
        document.getElementById('request-description').innerText= requests.description;
        document.getElementById('request-imag').src = '/originaltwo/AdminLTE/dist/pages/' + requests.photo_url;
        const viewProposal= document.getElementById('showProposal');
        viewProposal.setAttribute("data-request-id", requests.id); 
        const modal = new bootstrap.Modal(document.getElementById('maintenanceRequestModal'));
        modal.show();
      });
      // Assign provider
       const assignProviderBtn = tempDiv.querySelector('.assign-provider');
        assignProviderBtn.addEventListener('click', (e) =>{
        selectProviders(requests.id);
      });
      // Mark complete
      const markCompleteBtn = tempDiv.querySelector('.mark-complete');
        markCompleteBtn.addEventListener('click', (e) =>{
        markComplete(requests.id);
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
function markComplete(requestsID){
   fetch(`actions/update_records.php?type=mark_item&request_id=${requestsID}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log("Item updated successfully.");
        location.reload(); // Reload the page to reflect changes
        // Optionally refresh part of the UI
      } else {
        console.error("Backend error:", data.error);
      }
    })
    .catch(error => {
      console.error("Network error:", error);
    });
}


//Delete Request
function deleteRequest(requestsID){
   fetch(`actions/delete_records.php?type=mark_item&request_id=${requestsID}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log("Item updated successfully.");
        location.reload(); // Reload the page to reflect changes
        // Optionally refresh part of the UI
      } else {
        console.error("Backend error:", data.error);
      }
    })
    .catch(error => {
      console.error("Network error:", error);
    });
}


// Select Providers
function selectProviders(requestsID){
  fetch('actions/fetch_service_providers.php') // Adjust your endpoint
  .then(res => res.json())
  .then(data => {
    const select = document.getElementById('service_provider_id');
    data.forEach(provider => {
      const option = document.createElement('option');
      option.value = provider.id;
      option.textContent = `${provider.name} - ${provider.category}`;
      select.appendChild(option);
      
    });
    document.getElementById('maintenance_request_id').value= requestsID;
    const modal = new bootstrap.Modal(document.getElementById('assignProviderModal'));
      modal.show();
  })
  .catch(error => console.error('Error fetching providers:', error));
}


// Assign the Provider
function assignProvider(event){
  event.preventDefault(); // Prevent the form from submitting immediately
  const form = document.getElementById("assignProviderForm");
  const formData = new FormData(form);

  fetch("actions/assign_service_provider.php", {
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


// make payment
function makePayment() {
  const maintenanceRequestModalEl = document.getElementById('maintenanceRequestModal');
  const maintenanceRequestModalInstance = bootstrap.Modal.getInstance(maintenanceRequestModalEl);
  if (maintenanceRequestModalInstance) {
    maintenanceRequestModalInstance.hide();
  }

  // Open the "Pay Provider"
  const payProviderModalEl = document.getElementById('payProviderModal');
  let payProviderModalInstance = bootstrap.Modal.getInstance(payProviderModalEl);

  if (!payProviderModalInstance) {
    payProviderModalInstance = new bootstrap.Modal(payProviderModalEl);
  }
  payProviderModalInstance.show();

 function handleOpenRecordPaymentModal() {
    const payProviderInstance = bootstrap.Modal.getInstance(payProviderModalEl);
    payProviderInstance.hide();

    const recordPaymentModalEl = document.getElementById('recordPaymentModal');
    let recordPaymentModalInstance = bootstrap.Modal.getInstance(recordPaymentModalEl);
    recordPaymentModalInstance = new bootstrap.Modal(recordPaymentModalEl);
    recordPaymentModalInstance.show();
    nextStepBtn
}

// in system payment
function inSystemPayment(){
  const step1Div = document.getElementById('step-1');
  step1Div.style.display = 'none';

  const step2Div = document.getElementById('step-2');
  step2Div.style.display = 'block';
}
  const nextStepBtn = document.getElementById('nextStepBtn');
  nextStepBtn.addEventListener('click', inSystemPayment);
  const openRecordPaymentModalBtn = document.getElementById('openRecordPaymentModalBtn');
  openRecordPaymentModalBtn.removeEventListener('click', handleOpenRecordPaymentModal);
  openRecordPaymentModalBtn.addEventListener('click', handleOpenRecordPaymentModal);

 // Mpesa/bank payment
  const paymentMethod = document.getElementById('paymentMethod'); // ✅ The <select>
  const mpesaPhoneSection = document.getElementById('mpesaPhoneSection');
  const bankSection= document.getElementById('bankTransferSection');
  paymentMethod.addEventListener('change', function () {
    if (this.value === 'mpesa') {
      mpesaPhoneSection.style.display = 'block';
    } else if(this.value === 'bank'){
      mpesaPhoneSection.style.display = 'none';
      bankSection.style.display = 'block';
    }
    else{
      mpesaPhoneSection.style.display = 'none';
      bankSection.style.display = 'block';
    }
  });
}


// stk push
document.getElementById("paymentForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent normal form submission

    // Get values from form inputs
    const phone = document.getElementById("phoneNumber").value;
    const amount = document.getElementById("mpesaAmount").value;

    // Send data to PHP via fetch
    fetch("actions/stk.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        phone: phone,
        amount: amount,
      }),
    })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      alert(data.message);
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Something went wrong");
    });
  });

  //Show Job proposals.
  function showProposals(){
      const maintenanceRequestModalEl = document.getElementById('maintenanceRequestModal');
      const maintenanceRequestModalInstance = bootstrap.Modal.getInstance(maintenanceRequestModalEl);
      if (maintenanceRequestModalInstance) {
        maintenanceRequestModalInstance.hide(); // 👈 call it properly
      }
      const viewProposal = document.getElementById("showProposal");
      const requestId = viewProposal.getAttribute("data-request-id");
      
       fetch(`actions/get_request_proposals.php?request_id=${requestId}`)
       .then(response => response.json())
      .then(data => {
        console.log("Fetched Proposals:", data);
        // Do something with the data...y
        const container = document.getElementById("job_proposals_container");
        container.classList.remove("d-none"); // Make sure it's visible
        container.innerHTML = ""; // Clear previous content if needed
        data.forEach(p => {
          container.innerHTML += `
            <div class="proposal-card mt-2">
              <div class="proposal-header d-flex align-items-start">
                <img src="${p.profilePic || 'https://i.pravatar.cc/70'}" alt="Profile Picture" class="profile-pic me-3">
                <div>
                  <h5>${p.name} <span class="badge bg-warning">5 <i class="fa fa-star " id="star-rating"></i>${p.ratings}</span></h5>
                  <p>${p.category}</p>
                </div>
                <div class="ms-auto proposal-meta text-end">
                  <h6>$${p.bid_amount}/hr</h6>
                  <small>${p.estimated_time}</small><br>
                  <small class="text-success">✅ ${p.jobs_completed} jobs completed</small>
                </div>
              </div>
              <hr>
              <p><strong>Cover Letter:</strong> ${p.cover_letter}</p>
              <p><strong>Location:</strong> ${p.location}</p>
              <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-outline-secondary btn-sm btn-action">Message</button>
                <button class="btn btn-outline-primary btn-sm btn-action" style="margin-right:6px;">Shortlist</button>
                <button class="btn btn-outline-danger btn-sm">Decline</button>
              </div>
            </div>
          `;
        });
      })
       .catch(err=>{
         console.error("Error fetching proposals:", err);
       })

        const modal = document.getElementById("proposalContainer");
        const backdrop = document.getElementById("customBackdrop");

        backdrop.classList.remove("d-none");
        modal.classList.remove("d-none");

        setTimeout(() => {
          backdrop.classList.add("show");
          modal.classList.add("show", "d-block");
        }, 10);

  }