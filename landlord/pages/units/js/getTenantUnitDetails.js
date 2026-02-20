export async function getTenantUnitDetails(unitId) {
  // Elements
  const tenantNameEl = document.getElementById("tenantName");
  const unitNumberEl = document.getElementById("unitNumber");
  const buildingNameEl = document.getElementById("buildingName");
  const moveInDateEl = document.getElementById("moveInDate");
  const hidden = document.getElementById("vacateUnitId");       // keep unit_id
  const tenancyHidden = document.getElementById("tenancyId"); // update tenancy_id

  // Reset UI first
  if (tenantNameEl) tenantNameEl.textContent = "—";
  if (unitNumberEl) unitNumberEl.textContent = "—";
  if (buildingNameEl) buildingNameEl.textContent = "—";
  if (moveInDateEl) moveInDateEl.textContent = "—";

  // Reset hidden inputs
  if (hidden) hidden.value = unitId ?? "";
  if (tenancyHidden) tenancyHidden.value = ""; // clear tenancy_id until we fetch it

  if (!unitId) return;

  try {
    const res = await fetch(
      `actions/getTenantUnitDetails.php?unit_id=${encodeURIComponent(unitId)}`
    );
    if (!res.ok) throw new Error(`HTTP ${res.status}`);

    const json = await res.json();

    // Safety: handle backend errors
    if (!json.success) {
      console.error("API returned error:", json.error || json);
      return;
    }

    const row = json.data; // single row or null

    // No active tenancy found
    if (!row) {
      if (tenantNameEl) tenantNameEl.textContent = "No active tenant";
      if (tenancyHidden) tenancyHidden.value = ""; // ensure cleared
      return;
    }

    // ✅ Set tenancy_id only (unit_id stays as-is)
    if (tenancyHidden) tenancyHidden.value = row.tenancy_id ?? "";
    console.log("yoyo");
    console.log(row.tenancy_id);

    // Build tenant full name (API has middle_name, not second_name)
    const first = row.first_name ?? "";
    const middle = row.middle_name ?? "";
    const fullName = (first + " " + middle).trim();

    // Populate fields
    if (buildingNameEl) buildingNameEl.textContent = row.building_name ?? "—";
    if (unitNumberEl) unitNumberEl.textContent = row.unit_number ?? "—";
    if (tenantNameEl) tenantNameEl.textContent = fullName || "No active tenant";

    // Move-in date (keep raw or format)
    if (moveInDateEl) {
      moveInDateEl.textContent = row.move_in_date
        ? new Date(row.move_in_date).toLocaleDateString(undefined, {
            year: "numeric",
            month: "short",
            day: "2-digit",
          })
        : "—";
    }
  } catch (err) {
    console.error("Failed to load tenant/unit details:", err);
  }
}
