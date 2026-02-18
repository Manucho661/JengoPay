
export async function getUnits() {
    const buildingId = document.getElementById('buildingSelect').value;
    console.log('guys, its being reached');
    const unitSelect = document.getElementById("unitSelect");

    // Reset the inputs
    unitSelect.innerHTML = '<option value="">Choose unit...</option>';
    document.getElementById("tenantNameInput").value = "";
    document.getElementById("tenantIdInput").value = "";

    if (!buildingId) return;
    console.log(buildingId);

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
            unitSelect.disabled = false;
        });

        unitDiv.style.display = "block"; // Show the unitDiv when units are populated

    } catch (e) {
        console.error(e);
    }
}

export async function getTenant() {
    const unitId = document.getElementById('unitSelect').value;
    const tenantNameInput = document.getElementById("tenantNameInput");
    const tenant_id = document.getElementById("tenantIdInput");

    tenantNameInput.value = "";

    tenant_id.value = "";

    if (!unitId) return;

    try {
        const res = await fetch(`actions/getTenant.php?unit_id=${encodeURIComponent(unitId)}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();

        if (data.success && data.tenant) {
            // Populate the tenant details
            // const absentTenantMess = document.getElementById("tenantHelp");
            // absentTenantMess.textContent = "";
            // absentTenantMess.style.display = "none";

            tenantNameInput.value = data.tenant.tenant_name;
            tenant_id.value = data.tenant.tenant_user_id;

        } else {
            console.error("Tenant data not found.");
            // const absentTenantMess = document.getElementById("tenantHelp");
            // absentTenantMess.textContent = "No tenant found, please select another building or unit";
            // absentTenantMess.style.display = "block";
            // document.getElementById("startNewConvBtn").disabled = true;
        }
    } catch (e) {
        console.error(e);
    }
}