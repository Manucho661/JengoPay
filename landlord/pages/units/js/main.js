import { getTenantUnitDetails } from "./getTenantUnitDetails.js";

document.addEventListener("click", async (e) => {
  const link = e.target.closest(".mark-vacant");
  if (!link) return;

  e.preventDefault();
  e.stopPropagation();

  const unitId = link.dataset.unitId; // string
  if (!unitId) return;

  // open the offcanvas first (optional)
  const el = document.getElementById("vacateOffcanvas");
  const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
  offcanvas.show();

  // now load details for THIS unit
  await getTenantUnitDetails(unitId);
});
