
// document.addEventListener("DOMContentLoaded", () => {
//     const buildingSelect = document.getElementById("buildingSelect");
//     const unitSelect = document.getElementById("unitSelect");


//     const resetUnits = (msg = "Select Unit") => {
//         unitSelect.innerHTML = `<option value="">${msg}</option>`;
//         unitSelect.disabled = true;
//     };

//     resetUnits("Select Building first");

//     buildingSelect.addEventListener("change", async () => {
//         const buildingId = buildingSelect.value;
//         resetUnits("Loading units...");

//         if (!buildingId) {
//             resetUnits("Select Building first");
//             return;
//         }

//         try {
//             const res = await fetch(`actions/getUnits.php?building_id=${encodeURIComponent(buildingId)}`);
//             if (!res.ok) throw new Error(`HTTP ${res.status}`);
//             const data = await res.json();


//             unitSelect.innerHTML = `<option value="">Select Unit</option>`;
//             data.units.forEach(u => {
//                 const opt = document.createElement("option");
//                 opt.value = u.id;              // unit id
//                 opt.textContent = u.unit_number; // display
//                 unitSelect.appendChild(opt);
//             });

//             unitSelect.disabled = false;
//         } catch (e) {
//             console.error(e);
//             resetUnits("Failed to load units");
//         }
//     });
// });


// export async function getUnits() {
//     console.log("getUnits function is being reached");
//     const buildingSelect = document.getElementById("buildingSelect");
//     const unitSelect = document.getElementById("unitSelect");

//     // helper functions
//     function safeHide(id) {
//         const node = document.getElementById(id);
//         if (node) node.style.display = "none";
//     }

//     function safeShow(id) {
//         const node = document.getElementById(id);
//         if (node) node.style.display = "block";
//     }

//     safeHide("unitDiv"); // Hide the unit div initially
//     unitSelect.innerHTML = '<option value="">Choose unit...</option>'; // Reset the unit select

//     const resetUnits = (msg = "Select Unit") => {
//         unitSelect.innerHTML = `<option value="">${msg}</option>`;
//         unitSelect.disabled = true;
//     };

//     resetUnits("Select Building first");

//     buildingSelect.addEventListener("change", async () => {
//         const buildingId = buildingSelect.value;
//         console.log("Selected building ID:", buildingId); // Debugging output

//         resetUnits("Loading units...");

//         if (!buildingId) {
//             resetUnits("Select Building first");
//             return;
//         }

//         try {
//             const res = await fetch(`actions/getUnits.php?building_id=${encodeURIComponent(buildingId)}`);
//             if (!res.ok) throw new Error(`HTTP ${res.status}`);
//             const data = await res.json();
//             console.log("Fetched data:", data); // Debugging output

//             unitSelect.innerHTML = `<option value="">Select Unit</option>`;
//             if (data.units && data.units.length > 0) {
//                 data.units.forEach(u => {
//                     const opt = document.createElement("option");
//                     opt.value = u.id;              // unit id
//                     opt.textContent = u.unit_number; // display
//                     unitSelect.appendChild(opt);
//                 });

//                 unitSelect.disabled = false;
//                 safeShow("unitDiv"); // Show the unit div
//             } else {
//                 unitSelect.innerHTML = '<option value="">No units available</option>';
//                 safeHide("unitDiv");
//             }

//         } catch (e) {
//             console.error(e);
//             resetUnits("Failed to load units");
//             safeHide("unitDiv");
//         }
//     });
// }

function resetTenantFlowUI() {
    document.getElementById("unitDiv").style.display = "none";
    document.getElementById("unitSelect").innerHTML = '<option value="">Choose unit...</option>';
    document.getElementById("tenantNameInput").value = "";
    document.getElementById("tenantIdInput").value = "";
    document.getElementById("targetId").value = "";
};

export function updateRecipientOptions() {
    const type = document.getElementById('recipientType').value;
    const tenantFlow = document.getElementById('tenantFlow');

    if (type === "tenant") {
        if (tenantFlow) tenantFlow.style.display = "block";
        resetTenantFlowUI();
        document.getElementById('targetType').value = 'tenant';
        return;
    }
    if (type === "admin") {
        // System Admin is auto-selected (no dropdown
        document.getElementById('targetType').value = 'admin';
        document.getElementById('targetId').value = 'admin';
        return;
    }
}

export async function getUnits() {
    const buildingId = document.getElementById('buildingSelect').value;

    const unitDiv = document.getElementById("unitDiv");
    const unitSelect = document.getElementById("unitSelect");

    // Reset the inputs
    unitDiv.style.display = "none";
    unitSelect.innerHTML = '<option value="">Choose unit...</option>';
    document.getElementById("tenantNameInput").value = "";
    document.getElementById("tenantIdInput").value = "";
    document.getElementById("targetId").value = "";

    if (!buildingId) return;

    try {
        const res = await fetch(`actions/getUnits.php?building_id=${encodeURIComponent(buildingId)}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();

        // Reset and populate the unit select options
        unitSelect.innerHTML = '<option value="">Select Unit</option>'; // Add default option

        data.units.forEach(u => {
            const opt = document.createElement("option");
            opt.value = u.id;              // unit id
            opt.textContent = u.unit_number; // display name
            unitSelect.appendChild(opt);
        });

        unitDiv.style.display = "block"; // Show the unitDiv when units are populated

    } catch (e) {
        console.error(e);
    }
}

export async function getTenant() {
    const unitId = document.getElementById('unitSelect').value;
    const tenantNameDiv = document.getElementById("tenantNameDiv");
    const tenantNameInput = document.getElementById("tenantNameInput");
    const tenant_user_id = document.getElementById("tenantIdInput");
    const targetId = document.getElementById("targetId");

    tenantNameDiv.style.display = "none";
    tenantNameInput.value = "";
    targetId.value = "";
    tenant_user_id.value= "";

    if (!unitId) return;

    try {
        const res = await fetch(`actions/getTenant.php?unit_id=${encodeURIComponent(unitId)}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();

        if (data.success && data.tenant) {
            // Populate the tenant details
            tenantNameInput.value = data.tenant.tenant_name;
            targetId.value = data.tenant.tenant_user_id;
            tenant_user_id.value = data.tenant.tenant_user_id;

            // Show the tenant name div after populating the fields
            tenantNameDiv.style.display = "block";
        } else {
            console.error("Tenant data not found.");
        }
    } catch (e) {
        console.error(e);
    }
}

