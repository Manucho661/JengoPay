import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";

/* ===========================
   GET PROVIDER DETAILS
=========================== */
export async function getProviderDetails() {

  console.log("get provider details is being reached");
  const providerId = this.getAttribute("data-provider-id");

  try {
    const response = await fetch(
      `./actions/request_details/get_provider_details.php?provider_id=${providerId}`
    );

    const details = await response.json();
    console.log(details);
    openProviderDetailsModal(details);


  } catch (err) {
    console.error("❌ Error fetching provider details:", err);
  }
}

function openProviderDetailsModal(details) {

  // --- helper: safely set content or attribute ---
  const safeSet = (id, value, prop = "innerText") => {
    const el = document.getElementById(id);
    if (!el) {
      console.warn(`⚠️ Missing element: #${id}`);
      return;
    }

    if (prop === "src") {
      el.src = value;
    } else {
      el[prop] = value;
    }
  };

    safeSet("providerModalName", details.details.name || "Unknown Provider");

// --- show modal---
  const modalEl = document.getElementById("providerModal");
  if (!modalEl) {
    console.error("❌ Cannot open modal: #proposalModal not found in DOM");
    return;
  }

  const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
  modal.show();
}