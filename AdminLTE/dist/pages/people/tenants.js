
document.addEventListener('DOMContentLoaded', function (){
  fetchTenants('all');

  document.querySelectorAll('.select-option-container').forEach(container => {
    const select = container.querySelector('.custom-select');
    const optionsContainer = container.querySelector('.select-options');
    const options = optionsContainer.querySelectorAll('div');

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
  fetch('../actions/fetch_records.php?building=' + encodeURIComponent(building))
      .then(response => response.json())
      .then(data => {
          const tableBody = document.querySelector('#users-table tbody');

          // If DataTable already exists, destroy it
          if ( $.fn.dataTable.isDataTable('#users-table') ) {
              $('#users-table').DataTable().destroy();
          }

          tableBody.innerHTML = '';

          data.forEach(user => {
              const row = document.createElement('tr');
              row.innerHTML = `
                    <td>${user.name}</td>
                    <td>${user.id_no}</td>
                    <td> <div> ${user.residence}</div>
                    <div style="color: green;" > ${user.unit}</div>

                     </td>
                    <td>
                    <div class="phone" >  <i class="fas fa-phone icon"></i> ${user.phone_number} </div>
                     <div class="email" > <i class="fa fa-envelope icon"></i> ${user.email} </div>
                   </td>
                    <td> <button class="status completed"><i class="fa fa-check-circle"></i> Active </button> </td>
                  <td>
                      <button onclick="handleDelete(event, ${user.user_id}, 'users');"
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
          $('#users-table').DataTable();
      })
      .catch(error => {
          console.error('Error fetching data:', error);
      });
}
});
