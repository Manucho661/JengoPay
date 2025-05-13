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

        // Populate fields
        document.getElementById('first_name').textContent = data.first_name;
        document.getElementById('middle_name').textContent = data.middle_name;
        document.getElementById('status').textContent = data.status;
        document.getElementById('email').textContent = data.email;
        document.getElementById('phone').textContent = data.phone_number;
        document.getElementById('id_no').textContent = data.id_no;
        document.getElementById('income_source').textContent = data.income_source;
        document.getElementById('work_place').textContent = data.work_place;
        document.getElementById('job_title').textContent = data.job_title;
        document.getElementById('unit').textContent = data.unit;


        const statusElement = document.getElementById('status');
        statusElement.textContent = data.status;

        if (data.status.toLowerCase() === 'inactive') {
          statusElement.className = 'inactive'; // sets class to "inactive"
        } else {
          statusElement.className = ''; // clear or reset class if needed
        }


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





