document.addEventListener('DOMContentLoaded', function () {
  // Initial fetch
          fetchTenants('all');

          // Custom Dropdown Handler
          document.querySelectorAll('.select-option-container').forEach(container => {
            const select = container.querySelector('.custom-select');
            const optionsContainer = container.querySelector('.select-options');
            const options = optionsContainer.querySelectorAll('div');
            const displayed_building = document.getElementById('displayed_building');

            select.addEventListener('click', () => {
              const isOpen = optionsContainer.style.display === 'block';
              document.querySelectorAll('.select-options').forEach(opt => opt.style.display = 'none');
              document.querySelectorAll('.custom-select').forEach(sel => sel.classList.remove('open'));

              optionsContainer.style.display = isOpen ? 'none' : 'block';
              select.classList.toggle('open', !isOpen);
            });

            options.forEach(option => {
              option.addEventListener('click', () => {
                const selectedValue = option.getAttribute('data-value');
                select.textContent = option.textContent;
                displayed_building.textContent = option.textContent;
                select.setAttribute('data-value', selectedValue);

                options.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');

                optionsContainer.style.display = 'none';
                select.classList.remove('open');

                fetchTenants(selectedValue); // Fetch filtered tenants
              });

              option.addEventListener('mouseenter', () => {
                options.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
              });
            });
          });

          // Close dropdowns on outside click
          document.addEventListener('click', (e) => {
            if (!e.target.closest('.select-option-container')) {
              document.querySelectorAll('.select-options').forEach(opt => opt.style.display = 'none');
              document.querySelectorAll('.custom-select').forEach(sel => {
                sel.classList.remove('open');
                sel.style.borderRadius = '5px';
              });
            }
          });

        // Modal Prefill on Edit
        const editBtn = document.querySelector('.edit-btn');
        if (editBtn) {
          editBtn.addEventListener('click', () => {
            document.getElementById('editEmail').value = document.getElementById('email').textContent.trim();
            document.getElementById('editPhone').value = document.getElementById('phone').textContent.trim();
            document.getElementById('editIDNo').value = document.getElementById('id_no').textContent.trim();
          });
        }

 document.getElementById('editPersonalInfoForm').addEventListener('submit', function (e) {
  e.preventDefault();

  // Get values from form
  const updatedEmail = document.getElementById('editEmail').value.trim();
  const updatedPhone = document.getElementById('editPhone').value.trim();
  const updatedID = document.getElementById('editIDNo').value.trim();
  const updatedIncomeSource = document.getElementById('editIncomeSource').value.trim();
  const updatedEmployer = document.getElementById('editEmployer').value.trim();
  const updatedJobTitle = document.getElementById('editJobTitle').value.trim();

  // Construct form data
  const formData = new FormData();
  formData.append('email', updatedEmail);
  formData.append('phone', updatedPhone);
  formData.append('id_no', updatedID);
  formData.append('income_source', updatedIncomeSource);
  formData.append('employer', updatedEmployer);
  formData.append('job_title', updatedJobTitle);
  formData.append('type', 'update_personal_info');

  // Replace this URL with your actual PHP update endpoint
  fetch('../actions/tenants/update_personal_info.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.text())
    .then(response => {
      console.log(response);
      if (response === 'success') {
        // Update the values on the UI without reload
        document.getElementById('email').textContent = updatedEmail;
        document.getElementById('phone').textContent = updatedPhone;
        document.getElementById('id_no').textContent = updatedID;

        // Optional: Update other fields if displayed elsewhere
        if (document.getElementById('income_source'))
          document.getElementById('income_source').textContent = updatedIncomeSource;
        if (document.getElementById('employer'))
          document.getElementById('employer').textContent = updatedEmployer;
        if (document.getElementById('job_title'))
          document.getElementById('job_title').textContent = updatedJobTitle;

        // Hide the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editPersonalInfoModal'));
        modal.hide();

        // Show confirmation (optional)
        alert('Changes saved successfully.');
      } else {
        alert('Update failed: ' + response);
      }
    })
    .catch(err => {
      console.error(err);
      alert('An error occurred while saving changes.');
    });
});


  // Tenant Submit Form
  window.submitTenantForm = function (event) {
     console.log('add fired');
            alert('Function triggered!');
    event.preventDefault();
    const formData = new FormData(document.getElementById("form_for_tenant"));
    formData.append("type", "tenant");

    fetch("actions/tenants/add_record.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.text())
      .then(data => {
        alert(data);
        location.reload();
      })
      .catch(err => console.error(err));
  };

  // Smooth navigation
  const mainElement = document.getElementById("mainElement");
  if (mainElement) {
    mainElement.classList.remove("fade-out");
  }

  function navigateWithTransition(url) {
    NProgress.start();
    mainElement.classList.add("fade-out");
    setTimeout(() => {
      window.location.href = url;
    }, 500);
  }

  document.querySelectorAll("a").forEach(link => {
    link.addEventListener("click", function (e) {
      const target = this.getAttribute("target");
      const href = this.getAttribute("href");
      if (!target || target === "_self") {
        e.preventDefault();
        navigateWithTransition(href);
      }
    });
  });
});

// Fetch tenants by building
function fetchTenants(building) {
  const tableBody = document.querySelector('#users-table tbody');
  tableBody.innerHTML = '<tr><td colspan="6"><div class="loader"></div></td></tr>';
  fetch('../actions/fetch_records.php?building=' + encodeURIComponent(building))
    .then(response => response.json())
    .then(data => {
      const enteries = document.getElementById('enteries');
      enteries.textContent = data.length;
      if ($.fn.dataTable.isDataTable('#users-table')) {
        $('#users-table').DataTable().destroy();
      }

      tableBody.innerHTML = '';

      data.forEach(user => {
        const row = document.createElement('tr');
        row.setAttribute('onclick', `goToDetails(${user.user_id})`);
        row.innerHTML = `
          <td>${user.first_name} ${user.middle_name}</td>
          <td>${user.id_no}</td>
          <td>
            <div>${user.residence}</div>
            <div style="color: green;">${user.unit}</div>
          </td>
          <td>
            <div class="phone"><i class="fas fa-phone icon"></i> ${user.phone_number}</div>
            <div class="email"><i class="fa fa-envelope icon"></i> ${user.email}</div>
          </td>
          <td>
            <button class="status ${user.status}">
              <i class="fa fa-check-circle"></i>&nbsp; ${user.status}
            </button>
          </td>
          <td>
            ${
              user.status === 'active'
                ? `<button onclick="handleDeactivate(event, ${user.user_id}, 'deactivate');"
                      class="btn btn-sm" style="background-color: #F87171; color:white;">
                      <i class="fa fa-arrow-right"></i> Deactivate
                    </button>`
                : `<button onclick="handleReactivate(event, ${user.user_id}, 'activate');"
                      class="btn btn-sm" style="background-color: #2e7d32 ; color:white;">
                      <i class="fa fa-arrow-left"></i> Reactivate
                    </button>`
            }
            <button class="btn btn-sm" style="background-color: #AF2A28; color:white;">
              <i class="fa fa-comment"></i>
            </button>
            <button class="btn btn-sm" style="background-color: #F74B00; color:white;">
              <i class="fa fa-envelope"></i>
            </button>
          </td>`;
        tableBody.appendChild(row);
      });

      const table = $('#users-table').DataTable({
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
    })
    .catch(error => {
      console.error('Error fetching data:', error);
    });
}

// Deactivate / Reactivate handlers
function handleDeactivate(event, id, type) {
  event.stopPropagation();
  if (confirm("Are you sure?")) {
    fetch('../actions/tenants/update_record.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${encodeURIComponent(id)}&type=${encodeURIComponent(type)}`
    })
      .then(res => res.text())
      .then(data => {
        alert(data);
        location.reload();
      });
  }
}

         r6
  // Activate Tenants
          function handleReactivate(event, id, type) {
            event.stopPropagation(); // Stop the row or parent element click
            if (confirm("Are you sure?")) {
            fetch('../actions/tenants/update_record.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: 'id=' + encodeURIComponent(id) + '&type=' + encodeURIComponent(type)
            })
            .then(res => res.text())
            .then(data => {
              alert(data);
              location.reload();
            })
            .catch(err => console.error(err));
          }

          }


      //End Tenant status

      // ADD TENANT TO DB
        function submitTenantForm(event) {
          console.log('YOYO');
          event.preventDefault(); // Prevent the form from submitting normally

          // Create FormData object from the form
          const formData = new FormData(document.getElementById("form_for_tenant"));
          formData.append("type", "tenant"); // Add the type for tenant

          // Send data via fetch
          fetch("actions/tenants/add_record.php", {
            method: "POST",
            body: formData
          })
          .then(res => res.text())
          .then(data => {
            alert(data); // Display success message or error from server
            location.reload(); // Reload the page to reflect changes (optional)
          })
          .catch(err => console.error(err));
        }


    // POPUPS
    // Function to open the complaint popup
    function openshiftPopup() {
      document.getElementById("shiftPopup").style.display = "flex";
    }

    // Function to close the complaint popup
    function closeshiftPopup() {
      document.getElementById("shiftPopup").style.display = "none";
    }

    // Function to open the complaint popup
    function opennotificationPopup() {
      document.getElementById("notificationPopup").style.display = "flex";
    }

    // Function to close the complaint popup
    function closenotificationPopup() {
      document.getElementById("notificationPopup").style.display = "none";
    }



    // Function to open the complaint popup
    function openPopup() {
      document.getElementById("addTenantModal").style.display = "flex";
    }

    // Function to open the tenant popup
    function tenant_form() {
      document.getElementById("tenant-form").style.display = "flex";
    }
    // Function to close the complaint popup
    function closePopup() {
      document.getElementById("addTenantModal").style.display = "none";
    }

    //  SMOOTH LOADING IN AND OUT
      document.addEventListener("DOMContentLoaded", () => {
        // Fade in effect on page load
        const mainElement = document.getElementById("mainElement");

        if (mainElement) {
          mainElement.classList.remove("fade-out");
        }

        function navigateWithTransition(url) {
        NProgress.start();                             // Start progress bar
        mainElement.classList.add("fade-out");         // Fade out the main content

        setTimeout(() => {
          window.location.href = url;                  // Navigate after fade
        }, 500); // Matches the CSS transition time
      }


        // Intercept link clicks
        document.querySelectorAll("a").forEach(link => {
          link.addEventListener("click", function (e) {
            const target = this.getAttribute("target");
            const href = this.getAttribute("href");

            if (!target || target === "_self") {
              e.preventDefault();
              navigateWithTransition(href);
            }
          });
        });
      });

      document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.editTenantBtn').forEach(button => {
    button.addEventListener('click', function () {
      const tenantId = this.getAttribute('data-id');

      fetch('fetch-tenant.php?id=' + tenantId)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Populate form
            document.getElementById('editTenantId').value = data.tenant.id;
            document.getElementById('editFullName').value = data.tenant.full_name;
            document.getElementById('editPhone').value = data.tenant.phone;
            // ...more fields...

            // Show modal
            const editModal = new bootstrap.Modal(document.getElementById('editTenantModal'));
            editModal.show();
          } else {
            alert('Tenant not found.');
          }
        })
        .catch(err => console.error('Error:', err));
    });
  });
});


document.addEventListener('DOMContentLoaded', () => {
  fetch('fetch_options.php')
    .then(res => res.json())
    .then(data => {
      const tenantSelect = document.getElementById('tenantSelect');
      const buildingSelect = document.getElementById('buildingSelect');
      const unitSelect = document.getElementById('unitSelect');

      // Populate Tenants
      data.tenants.forEach(tenant => {
        tenantSelect.innerHTML += `<option value="${tenant.id}">${tenant.full_name}</option>`;
      });

      // Populate Buildings
      data.buildings.forEach(building => {
        buildingSelect.innerHTML += `<option value="${building.id}">${building.name}</option>`;
      });

      // Event: Filter Units by Building
      buildingSelect.addEventListener('change', () => {
        const selectedBuildingId = buildingSelect.value;
        unitSelect.innerHTML = '<option disabled selected>Select Unit</option>';

        data.units
          .filter(unit => unit.building_id === selectedBuildingId)
          .forEach(unit => {
            unitSelect.innerHTML += `<option value="${unit.id}">${unit.unit_name}</option>`;
          });
      });
    })
    .catch(err => console.error('Error loading data:', err));
});



