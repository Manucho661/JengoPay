function fetchPersonalInfo(){
  fetch(`../actions/tenant_profile/pets/fetch_records.php?tenant_id=${tenantId}`)

      .then(response => response.json())
    .then(data => {
      console.log('Personal info:', data);

      // Example: populate fields
      document.getElementById('fullName').textContent = `${data.first_name} ${data.middle_name}`;
      document.getElementById('email').textContent = data.email;
      document.getElementById('id_no').textContent = data.id_no;
      document.getElementById('unit').textContent = data.unit;
    })
    .catch(error => {
      console.error('Error fetching personal info:', error);
    });

}

function fetchPets() {
   
  const tableBody = document.querySelector('#pets-table tbody');
  tableBody.innerHTML = '<tr><td colspan="4"><div class="loader"></div></td></tr>';
  fetch(`../actions/tenant_profile/pets/fetch_records.php?tenant_id=${tenantId}`)
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

