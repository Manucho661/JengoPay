import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";
import { openProposalModal, openProviderDetailsModal } from "../modals.js";
import { applyAvailabilityStyles } from "../uiControl.js";

/* ===========================
   FETCH PROPOSALS
=========================== */
export async function fetchProposals() {
  const id = new URLSearchParams(window.location.search).get("id");
  if (!id) return;

  try {
    const response = await fetch(
      `./actions/requestDetails/getProposals.php?id=${id}`
    );
    const data = await response.json();
    console.log("✅ Proposals fetched:", data);

    // Uncomment if you want to render directly:
  } catch (err) {
    console.error("❌ Failed to fetch proposals:", err);
  }
}