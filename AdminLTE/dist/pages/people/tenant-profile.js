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
        

//populate editing tenant field
        document.getElementById("editIncomeSource").value = tenant.income_source;
        document.getElementById("editEmployer").value = tenant.employer_name;
        document.getElementById("editJobTitle").value = tenant.job_title;

        //populate tenant id on files modal
         document.getElementById("tenantIdFile").value = tenant.tenant_id;
        //populate tenant id on pets modal
        document.getElementById("tenantIdaddPetForm").value = tenant.tenant_id;

         console.log(tenant.tenant_id);
        

        
        const tenantIdInput = document.getElementById("tenant_id");
if (tenantIdInput) {
  tenantIdInput.value = tenant.tenant_id;
  console.log(`✅ tenant_id set to ${tenant.tenant_id}`);
} else {
  console.warn("❌ tenant_id input field not found.");
}


        


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
          <td> <b>${pet.type} </b>  </td>
          <td>${pet.weight}</td>
          <td>${pet.license}</td>
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




 window.submitEditIncomeInfo = function (event) {
  console.log('DoNe');
  alert('hi!');
  event.preventDefault();

  const incomeSource = document.getElementById('editIncomeSource').value.trim();
  const employer = document.getElementById('editEmployer').value.trim();
  const jobTitle = document.getElementById('editJobTitle').value.trim();
  const userId = document.getElementById('user_id')?.value;
  const tenantId = document.getElementById('tenant_id')?.value;

  if (!incomeSource || !employer || !jobTitle || !userId || !tenantId) {
    alert("⚠️ Please fill in all required fields.");
    return;
  }

  const formData = new FormData();
  formData.append('income_source', incomeSource);
  formData.append('employer', employer);
  formData.append('job_title', jobTitle);
  formData.append('user_id', userId);
  formData.append('tenant_id', tenantId);

  fetch("../people/actions/tenant_profile/update_income_info.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      console.log('Server response:', data);

      if (data.success) {
         alert("✅ Income information updated successfully.");
        // ✅ Hide the modal
        const modalElement = document.getElementById('editIncomeInfoModal');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) modalInstance.hide();

        // ✅ Refresh the page to reflect changes
        setTimeout(() => {
          location.reload();
        }, 300); // optional small delay to allow modal to close smoothly
      } else {
        alert("❌ Failed to update income info: " + (data.message || "Unknown error."));
      }
    })
    .catch(error => {
      console.error("Fetch error:", error);
      alert("❌ An error occurred while updating income information.");
    });
}


// Set tenant_id when modal opens
document.querySelectorAll('.open-add-file-modal, .add-file-btn').forEach(button => {
  button.addEventListener('click', function () {
    const tenantId = this.getAttribute('data-tenant-id');
    document.getElementById('tenantId').value = tenantId;
  });
});

// Submit form
document.getElementById("addFileForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const form = document.getElementById("addFileForm");
  const formData = new FormData(form);
  const tenantId = formData.get("tenant_id");

  // Optional: Log fields
  for (const [key, value] of formData.entries()) {
    console.log(`${key}:`, value);
  }

  fetch("../people/actions/tenant_profile/add_records.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("File uploaded successfully!");

      // Close modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('addFileModal'));
      modal.hide();

      // Clear form
      form.reset();

      // Refresh file list
      refreshFileList(tenantId);
    } else {
      alert("Error uploading file: " + data.message);
    }
  })
  .catch(error => {
    console.error("Upload error:", error);
    alert("Something went wrong while uploading.");
  });
});

// Load updated file list for a tenant
function refreshFileList(tenantId) {
  fetch(`../people/actions/tenant_profile/get_files.php?tenant_id=${tenantId}`)
    .then(response => response.json())
    .then(data => {
      const fileList = document.getElementById("fileList");
      fileList.innerHTML = ""; // Clear old list

      if (data.success && Array.isArray(data.files)) {
        data.files.forEach(file => {
          const li = document.createElement("li");
          li.innerHTML = `<strong>${file.file_name}</strong> - <a href="${file.file_path}" target="_blank">View File</a>`;
          fileList.appendChild(li);
        });
      } else {
        fileList.innerHTML = "<li>No files found.</li>";
      }
    })
    .catch(error => {
      console.error("Failed to fetch file list:", error);
    });
}



document.getElementById('addPetForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('actions/pets/add_pet.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Hide modal
            const modalEl = document.getElementById('addPetModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            // Reset form
            this.reset();

            // Append new pet to table
            const tbody = document.querySelector('#pets-table tbody');
            const pet = data.pet;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${pet.type}</td>
                <td>${pet.weight} kg</td>
                <td>${pet.license}</td>
            `;
            tbody.appendChild(row);
            location.reload();
        } else {
            alert(data.message || "Something went wrong.");
        }
    })
    .catch(err => {
        console.error("Fetch/network error:", err);
        alert("Network error or server unreachable.");
    });
});


