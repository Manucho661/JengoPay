

export async function getTenant() {
    console.log("getTenant function is being reached");

    const el = (id) => document.getElementById(id);

    const unitId = el("unitSelect")?.value;

    function safeHide(id) {
        const node = el(id);
        if (node) node.style.display = "none";
    }

    function safeShow(id) {
        const node = el(id);
        if (node) node.style.display = "block";
    }
    safeHide("tenantNameDiv");
    if (el("tenantNameInput")) el("tenantNameInput").value = "";
    if (el("tenantIdInput")) el("tenantIdInput").value = "";
    if (el("targetId")) el("targetId").value = "";

    if (!unitId) return;

    console.log(unitId);
    console.log("5");
    // safeHide("unitDiv");

    // const resetUnits = (msg = "Select Unit") => {
    //     unitSelect.innerHTML = `<option value="">${msg}</option>`;
    //     unitSelect.disabled = true;
    // };

    // resetUnits("Select Building first");

    // buildingSelect.addEventListener("change", async () => {
    //     const buildingId = buildingSelect.value;
    //     resetUnits("Loading units...");

    //     if (!buildingId) {
    //         resetUnits("Select Building first");
    //         return;
    //     }

    //     try {
    //         const res = await fetch(`actions/getUnits.php?building_id=${encodeURIComponent(buildingId)}`);
    //         if (!res.ok) throw new Error(`HTTP ${res.status}`);
    //         const data = await res.json();

    //         unitSelect.innerHTML = `<option value="">Select Unit</option>`;
    //         data.units.forEach(u => {
    //             const opt = document.createElement("option");
    //             opt.value = u.id;              // unit id
    //             opt.textContent = u.unit_number; // display
    //             unitSelect.appendChild(opt);
    //         });

    //         unitSelect.disabled = false;
    //         safeShow("unitDiv");

    //     } catch (e) {
    //         console.error(e);
    //         resetUnits("Failed to load units");
    //     }
    // });
}

