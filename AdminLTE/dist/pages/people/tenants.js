
// FETCH TENANTS SECTION
document.addEventListener('DOMContentLoaded', function (){
  fetchTenants('all');

  document.querySelectorAll('.select-option-container').forEach(container => {

    const select = container.querySelector('.custom-select');
    const optionsContainer = container.querySelector('.select-options');
    const options = optionsContainer.querySelectorAll('div');
    const displayed_building = document.getElementById('displayed_building');

    // Toggle dropdown on select click
    select.addEventListener('click', () => {
      const isOpen = optionsContainer.style.display === 'block';

      // Close all other dropdowns before opening a new one
      document.querySelectorAll('.select-options').forEach(opt => opt.style.display = 'none');
      document.querySelectorAll('.custom-select').forEach(sel => {
        sel.classList.remove('open');

      });

      // Toggle current dropdown
      optionsContainer.style.display = isOpen ? 'none' : 'block';
      select.classList.toggle('open', !isOpen);
    });

    // Option click handler
    options.forEach(option => {
      option.addEventListener('click', () => {
        select.textContent = option.textContent;
        displayed_building.textContent= option.textContent;
        select.setAttribute('data-value', option.getAttribute('data-value'));

        options.forEach(opt => opt.classList.remove('selected'));
        option.classList.add('selected');

        optionsContainer.style.display = 'none';
        select.classList.remove('open');

         // 🚀 Fetch tenants for the selected building
        const selectedBuilding = option.getAttribute('data-value');
        fetchTenants(selectedBuilding); // << Add this line

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

// fetch tenants function

function fetchTenants(building) {

  const tableBody = document.querySelector('#users-table tbody');
  tableBody.innerHTML = '<tr><td colspan="6"><div class="loader"></div></td></tr>'; // 🟡 Show loading
  fetch('../actions/fetch_records.php?building=' + encodeURIComponent(building))

      .then(response => response.json())
      .then(data => {
          const tableBody = document.querySelector('#users-table tbody');

          // ✅ Save the number of rows (tenants)
          const enteries = document.getElementById('enteries');
          const tenantCount = data.length;
          enteries.textContent = tenantCount;


          // If DataTable already exists, destroy it
          if ( $.fn.dataTable.isDataTable('#users-table') ) {
              $('#users-table').DataTable().destroy();
          }
          tableBody.innerHTML = '';

          data.forEach(user => {
              const row = document.createElement('tr');
              row.setAttribute('onclick', `goToDetails(${user.user_id})`);

              row.innerHTML = `
                    <td>${user.first_name} &nbsp; ${user.middle_name}  </td>
                    <td>${user.id_no}</td>
                    <td> <div> ${user.residence}</div>
                    <div style="color: green;" > ${user.unit}</div>

                     </td>
                    <td>
                    <div class="phone" >  <i class="fas fa-phone icon"></i> ${user.phone_number} </div>
                     <div class="email" > <i class="fa fa-envelope icon"></i> ${user.email} </div>
                   </td>
                    <td> <button class="status ${user.status}"><i class="fa fa-check-circle"></i>&nbsp; ${user.status} </button> </td>
                  <td>
                    

                      <button onclick="handleDeactivate(event, ${user.user_id}, 'tenants');"
                        class="btn btn-sm" style="background-color: #00192D; color:white">
                        <i class="fa fa-arrow-right"></i>
                      </button>
                      <button class="btn btn-sm" style="background-color: #AF2A28; color:white;">
                        <i class="fa fa-comment"></i>
                      </button>
                      <button class="btn btn-sm" style="background-color: #F74B00; color:white;">
                        <i class="fa fa-envelope"></i>
                      </button>
                  </td>
              `;
              tableBody.appendChild(row);
          });

          // After inserting rows, reinitialize DataTable
          $(document).ready(function () {
            const table = $('#users-table').DataTable({
              dom: 'Brtip', // ⬅ Changed to include Buttons in DOM
              buttons: [
                {
                  extend: 'excelHtml5',
                  text: 'Excel',
                  exportOptions: {
                    columns: ':not(:last-child)' // ⬅ Exclude last column
                  }
                },
                {
                  extend: 'pdfHtml5',
                  text: 'PDF',
                  exportOptions: {
                    columns: ':not(:last-child)' // ⬅ Exclude last column
                  },
                  customize: function (doc) {
                    // Center table
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                    // Optional: center-align the entire table
                    doc.styles.tableHeader.alignment = 'center';
                    doc.styles.tableBodyEven.alignment = 'center';
                    doc.styles.tableBodyOdd.alignment = 'center';

                    const body = doc.content[1].table.body;
                        for (let i = 1; i < body.length; i++) { // start from 1 to skip header
                          if (body[i][4]) {
                            body[i][4].color = 'blue'; // set email column to blue
                          }
                        }

                  }

                }
              ]
            });

            // Append buttons to your div
            table.buttons().container().appendTo('#custom-buttons');

            // Custom search
            $('#searchInput').on('keyup', function () {
              table.search(this.value).draw();
            });

          });
      })
      .catch(error => {
          console.error('Error fetching data:', error);
      });
}
});


// DELETE TENANT

      function handleDeactivate(event, id, type) {
        event.stopPropagation(); // Stop the row or parent element click
        if (confirm("Are you sure?")) {
        fetch('../actions/update_record.php', {
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
          event.preventDefault(); // Prevent the form from submitting normally

          // Create FormData object from the form
          const formData = new FormData(document.getElementById("form_for_tenant"));
          formData.append("type", "tenant"); // Add the type for tenant

          // Send data via fetch
          fetch("../actions/add_record.php", {
            method: "POST",
            body: new URLSearchParams(formData)
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


      