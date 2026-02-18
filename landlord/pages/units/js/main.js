import { getTenantUnitDetails } from "./getTenantUnitDetails.js";
// document.getElementById("buttonContainer").addEventListener("click", function (e) {
//   if (e.target.tagName === "BUTTON") {
//     const id = e.target.dataset.attributeId;
//     myFunction(id);
//   }
// });

function myFunction(id) {
  console.log("Button clicked:", id);
}

document.addEventListener("click", (e) => {
  const btn = e.target.closest("#vacateBtn");
  if (!btn) return;
  getTenantUnitDetails();
  const offcanvas = new bootstrap.Offcanvas(document.getElementById("vacateOffcanvas"));
  offcanvas.show();
});
