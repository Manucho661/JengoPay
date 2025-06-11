// IIFE to contain everything
(() => {
  // Modal toggles
  const inspectionModal = document.getElementById("add-inspection");

  function toggleModal(visible) {
    if (!inspectionModal) return;
    inspectionModal.style.display = visible ? "flex" : "none";
  }

  window.openAddInspection = () => toggleModal(true);
  window.closeAddInspection = () => toggleModal(false);




  // Custom select logic
  function setupCustomSelects() {
    document.querySelectorAll('.select-option-container').forEach(container => {
      const select = container.querySelector('.custom-select');
      const optionsContainer = container.querySelector('.select-options');
      const options = optionsContainer.querySelectorAll('div');

      select.addEventListener('click', () => {
        const isOpen = optionsContainer.style.display === "block";
        optionsContainer.style.display = isOpen ? "none" : "block";
        select.style.borderRadius = isOpen ? "5px" : "5px 5px 0 0";
        select.classList.toggle("open", !isOpen);
      });

      options.forEach(option => {
        option.addEventListener('click', () => {
          select.textContent = option.textContent;
          select.setAttribute("data-value", option.getAttribute("data-value"));
          options.forEach(opt => opt.classList.remove("selected"));
          option.classList.add("selected");
          optionsContainer.style.display = "none";
          select.style.borderRadius = "5px";
          select.classList.remove("open");
        });
      });

      // Close select dropdown when clicking outside
      document.addEventListener('click', (e) => {
        if (!e.target.closest('.select-option-container')) {
          optionsContainer.style.display = "none";
          select.style.borderRadius = "5px";
          select.classList.remove("open");
        }
      });
    });
  }

  // Generic form submission handler
  function handleFormSubmit(formId, url, extraFields = {}) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener("submit", function (event) {
      event.preventDefault();
      const formData = new FormData(form);
      Object.entries(extraFields).forEach(([key, value]) => formData.append(key, value));

      fetch(url, {
        method: "POST",
        body: new URLSearchParams(formData)
      })
        .then(res => res.text())
        .then(data => {
          alert(data); // Replace with custom notification
          location.reload();
        })
        .catch(err => console.error("Form submission failed:", err));
    });
  }

  // fetch scheduled schedules.
  function fetchScheduledInspections(){
     fetch('actions/fetch_records.php?table=inspections')
      .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Inspections:", data.data);
                // you can now use data.data to display in your UI
                populateInspectionTable(data.data);
            } else {
                console.error("Backend error:", data.error);
            }
        })
        .catch(error => {
            console.error("Network or parsing error:", error);
        });
  }

// Submit inspection form
function handleFormSubmit_inspect(formId, url, extraFields = {}) {
  const form = document.getElementById(formId);
  if (!form) return;

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(form);

    // Include any additional key-value pairs
    Object.entries(extraFields).forEach(([key, value]) => {
      formData.append(key, value);
    });

    fetch(url, {
      method: "POST",
      body: formData // ✅ leave this as raw FormData
    })
    .then(res => res.text())
    .then(data => {
      alert(data);
      location.reload();
    })
    .catch(err => console.error("Form submission failed:", err));
  });
}


  // Populate table function
  function populateInspectionTable(inspections) {
  const tableBody = document.getElementById("scheduledInspectionsTableBody");
  tableBody.innerHTML = ""; // Clear any existing rows

  inspections.forEach(inspection => {
    const row = document.createElement("tr");

    // ✅ STEP 1: Create statusHTML
    let statusHTML = '';
    const status = (inspection.status || '').toLowerCase();

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
    } else if (status === 'incomplete') {
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
      <td>${inspection.date || ''}</td>
      <td>${inspection.id || ''}</td>
      <td>
        <div>${inspection.building_name }</div>
        <div style="color: green;">${inspection.unit_name }</div>
      </td>
      <td>${inspection.inspection_type|| ''}</td>
      ${statusHTML}
      <td class="d-flex gap-15px">   
        <button class="btn inspect_btn"
          data-building-name="${inspection.building_name}"
          data-unit="${inspection.unit || ''}"
          data-inspection-id="${inspection.id}"
         style="background-color: #00192D; color:#FFC107">
         Inspect</button>

        <button class="btn btn-sm view-btn"
          style="background-color: #193042; margin-left:10px; color:#fff;"
          title="View"
          data-id="${inspection.id}"
          data-status="${status}">
          <i class="fas fa-eye"></i>
        </button>

        <!-- Edit Button -->
        <button class="btn btn-sm" style="background-color: #1e6f5c; margin-left: 2px; margin-right: 2px; color: #fff;" title="Edit">
          <i class="fas fa-edit"></i>
        </button>

        <!-- Delete Button -->
        <button class="btn btn-sm" style="background-color: #b02a37; margin-left: 2px; margin-right: 2px; color: #fff;" title="Delete">
          <i class="fas fa-trash"></i>
        </button>

      </td>
    `;

   
    // Add the event listener here AFTER the row is in memory
    const tempDiv = document.createElement('div');
    tempDiv.appendChild(row);
    
    // select all inspectBTNS
    const inspectBtn = tempDiv.querySelector('.inspect_btn');
    inspectBtn.addEventListener('click', (e) => {
      const btn = e.currentTarget;
      const buildingName = btn.getAttribute('data-building-name');
      const unit = btn.getAttribute('data-unit');
      const inspectionId = btn.getAttribute('data-inspection-id');

      document.getElementById('modal_building_name').textContent = buildingName;
      document.getElementById('modal_unit').textContent = unit;
      document.getElementById('modal_inspection_id').value = inspectionId;

      
      const prfm_Ins_mdl = document.getElementById('perform_inspection_modal');
      prfm_Ins_mdl.style.display =  "block";
    });

   const viewBtn = tempDiv.querySelector('.view-btn');
   viewBtn.addEventListener('click', () => {
    const inspectionId = viewBtn.getAttribute('data-id');

    // Get the status text from the row
    const statusSpan = tempDiv.querySelector('td span');
    const statusText = statusSpan?.textContent?.trim().toLowerCase();

    const status = viewBtn.getAttribute('data-status');
    if (status === 'completed') {
      viewDetails(inspectionId);
    } else {
      alert('You can only view details for completed inspections.');
    }
  });

    tableBody.appendChild(tempDiv.firstChild); // append the full row

  });
}

function viewDetails(inspectionId) {
  
fetch(`actions/fetch_records.php?table=inspection_items&inspection_id=${inspectionId}`)
      .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Inspections:", data.data);
                // you can now use data.data to display in your UI
                populateItemsTable(data.data);
            } else {
                console.error("Backend error:", data.error);
            }
        })
        .catch(error => {
            console.error("Network or parsing error:", error);
        });
        const modal = new bootstrap.Modal(document.getElementById('inspectionModal'));
        modal.show();
}

// populateItemsTable
 
  function populateItemsTable(inspection_items) {
  const tableBody = document.getElementById("inspectionModalTableBody");
  tableBody.innerHTML = ""; // Clear existing rows

  inspection_items.forEach(item => {
    const row = document.createElement("tr");

    // Example columns: item name, status, comments
    row.innerHTML = `
      <td>${item.category || '—'}</td>
      <td> <div class="${item.status || '—'}"> ${item.status || '—'}</div>  </td>
      <td>${item.description || '—'}</td>
      <td>${item.photos || '—'}</td>
    `;
    tableBody.appendChild(row);
  });
}

  // Initialize everything after DOM loads
  document.addEventListener("DOMContentLoaded", () => {
    
    setupCustomSelects();
    handleFormSubmit("form_new_inspection", "../actions/inspections/add_record.php", { type: "inspections" });
    handleFormSubmit_inspect("perform_inspection", "actions/add_record.php");
    fetchScheduledInspections();
  });

})();
