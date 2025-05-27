// SafeSet Object
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

// fetch personal info
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


  // Fetch pets

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




document.getElementById("addPetForm").addEventListener("submit", function (e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  
  fetch("add_pet.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Pet added successfully!");
      const modal = bootstrap.Modal.getInstance(document.getElementById("addPetModal"));
      modal.hide();
      // Optionally refresh pet list
    } else {
      alert("Failed to add pet: " + data.message);
    }
  })
  .catch(error => {
    console.error("Error:", error);
    alert("Error occurred while adding pet.");
  });
});


document.getElementById("addFileForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("add_file.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert("File uploaded successfully!");

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById("addFileModal"));
        modal.hide();

        // Add new row to files table
        const table = document.querySelector("#files-table tbody");
        const newRow = document.createElement("tr");
        newRow.innerHTML = `
          <td>${formData.get("file_name")}</td>
          <td><a href="${data.file_url}" target="_blank" class="btn btn-sm btn-outline-primary">View</a></td>
        `;
        table.appendChild(newRow);

        this.reset();
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch(error => {
      console.error("Error uploading file:", error);
      alert("An error occurred while uploading the file.");
    });
});


document.getElementById("shiftTenantForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const tenant = "Joseph"; // fixed here
  const building = document.getElementById("buildingSelect").value;
  const unit = document.getElementById("unitSelect").value;

  if (!building || !unit) {
    alert("Please select both building and unit.");
    return;
  }

  // Example AJAX submission
  fetch("shift_tenant.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ tenant, building, unit })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("Tenant shifted successfully!");
      document.getElementById("shiftTenantForm").reset();
      var modal = bootstrap.Modal.getInstance(document.getElementById("shiftTenantModal"));
      modal.hide();
    } else {
      alert("Shift failed: " + data.message);
    }
  });
});



document.getElementById('editPenaltyForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const penaltyRate = document.getElementById('penaltyRate').value;

  // Validate input
  if (penaltyRate === "" || isNaN(penaltyRate) || penaltyRate < 0 || penaltyRate > 100) {
    alert("Please enter a valid penalty rate between 0 and 100.");
    return;
  }

  // Submit using fetch
  fetch('save_penalty_rate.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ penaltyRate: penaltyRate })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Penalty rate updated successfully.');

      // ✅ Update UI (replace `.penalty-rate-display` with your actual display element)
      const displayEl = document.querySelector('.penalty-rate-display');
      if (displayEl) {
        displayEl.textContent = `${penaltyRate}%`;
      }

      // ✅ Close modal using Bootstrap 5 API
      const modalEl = document.getElementById('editPenaltyModal');
      const modal = bootstrap.Modal.getInstance(modalEl);
      modal.hide();
    } else {
      alert('Failed to update penalty rate: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('An unexpected error occurred.');
  });
});


document.getElementById('editIncomeInfoForm').addEventListener('submit', function (e) {
  e.preventDefault(); // Prevent normal form submission

  const form = e.target;
  const formData = new FormData(form);

  fetch('update_income_info.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Optional: Update elements on the frontend
      document.getElementById('incomeSourceDisplay').textContent = formData.get('income_source');
      document.getElementById('employerDisplay').textContent = formData.get('employer');
      document.getElementById('jobTitleDisplay').textContent = formData.get('job_title');

      // Close modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('editIncomeInfoModal'));
      modal.hide();
    } else {
      alert('Failed to update: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Something went wrong while updating.');
  });
});

window.submitEditPersonalInfoModal = function (event) {
  console.log('DoNe');
  alert('done')
  event.preventDefault();


  const email = document.getElementById('editEmail').value.trim();
  const phone = document.getElementById('editPhone').value.trim();
  const idNo = document.getElementById('editIDNo').value.trim();
  const userId = document.getElementById('user_id').value;

  if (!email || !phone || !idNo) {
    alert("Please fill in all required fields.");
    return;
  }

  const formData = new FormData();
  formData.append("email", email);
  formData.append("phone", phone);
  formData.append("id_no", idNo);
  formData.append("user_id", userId);

  fetch("../people/actions/tenant_profile/update_personal_info.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert("Personal information updated successfully.");
        const editModal = bootstrap.Modal.getInstance(document.getElementById('editPersonalInfoModal'));
        editModal.hide();
        location.reload();
      } else {
        alert("Error updating info: " + (data.message || "Unknown error"));
      }
    })
    .catch(error => {
      console.error("Error:", error);
      alert("An error occurred while updating information.");
    });
}
