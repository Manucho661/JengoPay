
export async function getUnits() {
    const buildingId = document.getElementById("buildingSelect").value;
    const noUnitMessage = document.getElementById("noUnitMessage");
    const unitSelect = document.getElementById("unitSelect");
    const sendBtn = document.getElementById("confirmAndSendBtn");

    // Reset UI
    unitSelect.innerHTML = '<option value="">Choose unit...</option>';
    unitSelect.disabled = true;
    sendBtn.disabled = true; // disable submit until tenant confirmed

    document.getElementById("tenantNameInput").value = "";
    document.getElementById("tenantIdInput").value = "";

    if (noUnitMessage) noUnitMessage.style.display = "none";

    if (!buildingId) return;

    try {
        const res = await fetch(
            `actions/getUnits.php?building_id=${encodeURIComponent(buildingId)}`
        );

        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const data = await res.json();
        const units = Array.isArray(data.units) ? data.units : [];

        // ❌ No units found
        if (units.length === 0) {
            if (noUnitMessage) {
                noUnitMessage.textContent =
                    "No units found for this building. Please choose another building.";
                noUnitMessage.style.display = "block";
            }

            unitSelect.innerHTML = '<option value="">No units available</option>';
            unitSelect.disabled = true;
            sendBtn.disabled = true;
            return;
        }

        // ✅ Units exist
        unitSelect.innerHTML = '<option value="">Select Unit</option>';
        units.forEach((u) => {
            const opt = document.createElement("option");
            opt.value = u.id;
            opt.textContent = u.unit_number;
            unitSelect.appendChild(opt);
        });

        unitSelect.disabled = false;

        // ⚠️ Keep send button disabled until tenant confirmed
        sendBtn.disabled = true;

        if (noUnitMessage) noUnitMessage.style.display = "none";

    } catch (e) {
        console.error(e);

        if (noUnitMessage) {
            noUnitMessage.textContent =
                "Failed to load units. Please refresh or try again.";
            noUnitMessage.style.display = "block";
        }

        unitSelect.innerHTML = '<option value="">Error loading units</option>';
        unitSelect.disabled = true;
        sendBtn.disabled = true;
    }
}



export async function getTenant() {
    const unitId = document.getElementById("unitSelect").value;
    const noTenantMessage = document.getElementById("noTenantMessage");
    const tenantNameInput = document.getElementById("tenantNameInput");
    const tenant_id = document.getElementById("tenantIdInput");
    const sendBtn = document.getElementById("confirmAndSendBtn");

    // Reset first
    tenantNameInput.value = "";
    tenant_id.value = "";
    tenantNameInput.disabled = true;
    sendBtn.disabled = true;

    if (noTenantMessage) {
        noTenantMessage.style.display = "none";
    }

    if (!unitId) return;

    try {
        const res = await fetch(
            `actions/getTenant.php?unit_id=${encodeURIComponent(unitId)}`
        );

        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const data = await res.json();

        // ✅ Tenant found
        if (data.success && data.tenant) {
            tenantNameInput.value = data.tenant.tenant_name;
            tenant_id.value = data.tenant.tenant_id;

            tenantNameInput.disabled = false;
            sendBtn.disabled = false;

            if (noTenantMessage) {
                noTenantMessage.style.display = "none";
            }

        } else {
            // ❌ No tenant
            if (noTenantMessage) {
                noTenantMessage.textContent =
                    "No tenant found for this unit. Please choose another unit.";
                noTenantMessage.style.display = "block";
            }

            tenantNameInput.disabled = true;
            sendBtn.disabled = true;
        }

    } catch (e) {
        console.error(e);

        if (noTenantMessage) {
            noTenantMessage.textContent =
                "Failed to load tenant. Please try again.";
            noTenantMessage.style.display = "block";
        }

        tenantNameInput.disabled = true;
        sendBtn.disabled = true;
    }
}

