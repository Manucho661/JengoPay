function safeSet(id, value) {
  const el = document.getElementById(id);
  if (el) {
    el.textContent = value;
    console.log(`✅ Set #${id} = ${value}`);
  } else {
    console.warn(`❌ Element with ID '${id}' not found`);
  }
}


document.addEventListener("DOMContentLoaded", function () {
  if (typeof user_id !== 'undefined') {
    fetchPersonalInfo(user_id);
  } else {
    console.warn("user_id is not defined");
  }
// 


  function fetchPersonalInfo(user_id) {
    fetch(`actions/tenant_profile/fetch_records.php?user_id=${user_id}`)
      .then(response => response.json())
      .then(data => {
        console.log('Personal info:', data);
        console.log('Files:', data.files);


            const tenant = data.tenant;
            const files = data.files;
            
        // Populate fields
        safeSet("first_name", tenant.first_name);
        safeSet("middle_name", tenant.middle_name);
        safeSet("status", tenant.status);
        safeSet("email", tenant.email);
        safeSet("phone", tenant.phone_number);
        safeSet("id_no", tenant.id_no);
        safeSet("income_source", tenant.income_source);
        safeSet("work_place", tenant.work_place);
        safeSet("job_title", tenant.job_title);
        safeSet("unit", tenant.unit);
        


        const statusElement = document.getElementById('status');
        statusElement.textContent = tenant.status;

        if (tenant.status.toLowerCase() === 'inactive') {
          statusElement.className = 'inactive'; // sets class to "inactive"
        } else {
          statusElement.className = 'active'; // clear or reset class if needed
        }


        // populate files table
        const tableBody = document.querySelector('#files-table tbody');
        tableBody.innerHTML = '';
        if (!Array.isArray(files) || files.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4">No files found.</td></tr>';
        return;
       }
        files.forEach(files => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td> <b>${files.file_name} </b>  </td>
          <td>
          <a href="${files.file_path}" target="_blank"
            class="btn btn-sm"
            style="background-color: #193042; color:#fff; margin-right: 2px;">
            <i class="fas fa-eye"></i> 
          </a>

          <button class="btn btn-sm" style="background-color: #0C5662; color:#FFCCCC; margin-right: 2px;" data-toggle="modal" data-target="#plumbingIssueModal" title="Get Full Report about this Repair Work"><i class="fa fa-trash"></i></button>
          </td>
        `;
        tableBody.appendChild(row);
      });

      })
      .catch(error => {
        console.error('Error fetching personal info:', error);
      });
  }


function fetchPets(user_id) {

  const tableBody = document.querySelector('#pets-table tbody');
  tableBody.innerHTML = '<tr><td colspan="4"><div class="loader"></div></td></tr>';
  fetch(`actions/pets/fetch_records.php?user_id=${user_id}`)
    .then(response => {
      console.log('HTTP status:', response.status);
      return response.text(); // ⬅ read as text first
    })
    .then(text => {
      console.log('Raw response text:', text);
      const data = JSON.parse(text); // ⬅ then manually parse
      console.log('Parsed data:', data);

      tableBody.innerHTML = '';

      if (!Array.isArray(data) || data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4">No pets found.</td></tr>';
        return;
      }

      data.forEach(pet => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td> <b>${pet.pet_name} </b>  </td>
          <td>Mad DOG</td>
          <td>${pet.weight}</td>
          <td>${pet.license_number}</td>
        `;
        tableBody.appendChild(row);
      });
    })
    .catch(error => {
      console.error('Fetch or parse error:', error);
      tableBody.innerHTML = `<tr><td colspan="4" style="color:red;">Error loading pets</td></tr>`;
    });
}

fetchPets(user_id)

});





