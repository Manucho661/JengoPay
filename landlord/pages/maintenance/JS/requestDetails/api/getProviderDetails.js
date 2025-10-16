import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";
import { openProposalModal, openProviderDetailsModal } from "../modals.js";
import { applyAvailabilityStyles } from "../uiControl.js";

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
