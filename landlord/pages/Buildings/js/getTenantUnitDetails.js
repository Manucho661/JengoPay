
export async function getTenantUnitDetails() {
    console.log("getTenantUnit function is being reached");
    //  const buildingSelect = document.getElementById("building");
    //  const unitSelect = document.getElementById("unit");

    //  const resetUnits = (msg = "Select Unit") => {
    //      unitSelect.innerHTML = `<option value="">${msg}</option>`;
    //      unitSelect.disabled = true;
    //  };

    //  resetUnits("Select Building first");

    //  buildingSelect.addEventListener("change", async () => {
    //      const buildingId = buildingSelect.value;
    //      resetUnits("Loading units...");

    //      if (!buildingId) {
    //          resetUnits("Select Building first");
    //          return;
    //      }

    //      try {
    //          const res = await fetch(`actions/getUnits.php?building_id=${encodeURIComponent(buildingId)}`);
    //          if (!res.ok) throw new Error(`HTTP ${res.status}`);
    //         const data = await res.json();

    //         unitSelect.innerHTML = `<option value="">Select Unit</option>`;
    //          data.units.forEach(u => {
    //              const opt = document.createElement("option");
    //              opt.value = u.id;              // unit id
    //             opt.textContent = u.unit_number; // display
    //              unitSelect.appendChild(opt);
    //          });

    //          unitSelect.disabled = false;
    //      } catch (e) {
    //          console.error(e);
    //          resetUnits("Failed to load units");
    //      }
    //  });
}

