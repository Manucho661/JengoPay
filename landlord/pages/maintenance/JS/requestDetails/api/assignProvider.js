import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";
import { openProposalModal, openProviderDetailsModal } from "../modals.js";
import { applyAvailabilityStyles } from "../uiControl.js";

/* ===========================
   ASSIGN PROVIDER
=========================== */
export async function assignProvider() {
  const providerId = this.getAttribute("data-provider-id");
  const requestId = new URLSearchParams(window.location.search).get("id");

  try {
    const response = await fetch(
      `./actions/request_details/assign_provider.php?request_id=${requestId}&provider_id=${providerId}`
    );

    const data = await response.json();

    if (data.status === "success") {
      console.log("✅ Provider assigned successfully:", data);

      // Refresh request details
      get_request_details();

      // Close modal
      const modalEl = document.getElementById("proposalModal");
      const modal =
        bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modal.hide();
    } else {
      console.warn("⚠️ Assignment failed:", data.message);
    }
  } catch (err) {
    console.error("❌ Error assigning provider:", err);
  }
}