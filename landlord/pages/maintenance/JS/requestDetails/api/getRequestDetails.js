import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";
import { openProposalModal, openProviderDetailsModal } from "../modals.js";
import { applyAvailabilityStyles } from "../uiControl.js";

/* ===========================
   FETCH REQUEST DETAILS
=========================== */
export async function getRequestDetails(customId = null) {
  // Use customId if provided, else use URL parameter
  const id = customId || new URLSearchParams(window.location.search).get("id");

  if (!id) {
    console.warn("⚠️ No request ID provided or found in URL.");
    return;
  }

  try {
    const response = await fetch(`./actions/request_details/getRequestDetails.php?id=${id}`);

    if (!response.ok) {
      console.error("❌ Server error:", await response.text());
      return;
    }

    const data = await response.json();
    fillRequestDetails(data.request, data.photos);
    fillProposals(data.proposals);
    console.log("✅ Request details fetched:", data);
  } catch (err) {
    console.error("❌ Fetch failed:", err);
  }
}


/* ===========================
   RENDER REQUEST DETAILS
=========================== */
function fillRequestDetails(request, photos) {
  document.getElementById("request-name").textContent =
    request?.request || "N/A";
  document.getElementById("request-property").textContent =
    request?.residence || "N/A";

  // Budget and Duration
  // budget
  const budgetEl = document.getElementById("budget");
  const durationEl = document.getElementById("duration");

  const budgetValue = request?.budget;
  const durationValue = request?.duration;

  if (!isNaN(budgetValue) && budgetValue !== null && budgetValue !== "") {
    budgetEl.textContent = `KSH ${budgetValue}`;
  } else {
    budgetEl.textContent = "Not set";
  }

  // Check if duration is numeric
  if (!isNaN(durationValue) && durationValue !== null && durationValue !== "") {
    durationEl.textContent = `${durationValue} hrs`;
  } else {
    durationEl.textContent = "Not set";
  }

  document.getElementById("request-unit").textContent = request?.unit || "N/A";
  document.getElementById("request-provider").textContent =
    request?.provider_name || "Unassigned";
  document.getElementById("request-status").textContent =
    request?.status || "Unassigned";
  document.getElementById("request-description").textContent =
    request?.description || "No description provided";

  // add provider id to provider name
  const requestProvider = document.getElementById("request-provider");
  requestProvider.setAttribute("data-provider-id", request.provider_id);

  // Photo
  const photoEl = document.getElementById("request-photo");
  if (photos?.length > 0) {
    photoEl.src = photos[0].photo_url;
  } else {
    photoEl.src = "";
    console.log("ℹ️ No photos available.");
  }

  // ✅ Availability Button
  const availabilityBtn = document.getElementById("availabilityBtn");
  if (availabilityBtn) {
    const availability = request?.availability || "unavailable";
    const requestId = request?.id || "";

    availabilityBtn.dataset.requestId = requestId;
    availabilityBtn.dataset.status = availability;
    availabilityBtn.textContent =
      availability === "available" ? "Set Unavailable" : "Set Available";
  }
}


/* ===========================
   PROPOSALS
=========================== */
const proposalTemplate = (p) => html`
  <li class="proposal-item">
    <img
      src="${p.provider_photo_url || "https://i.pravatar.cc/70"}"
      alt="Profile Picture"
      class="profile-pic me-3"
    />
    <div class="proposal-content">
      <div class="d-flex mb-0">
        <p class="text-dark my-0"><b>${p.name || "Unknown Provider"}</b>&nbsp;</p>
        <p class="text-muted my-0">${formatDate(p.submitted_at)}</p>
      </div>
      <div class="request-meta">
        <div class="request-status">
          <i class="fas fa-circle"></i> ${p.ratings || "N/A"}
        </div>
        <div class="request-priority text-success">
          <i class="fas fa-circle"></i> $${p.bid_amount || "N/A"}/hr
        </div>
        <div><p>${p.description || "No description provided"}</p></div>
      </div>
    </div>
  </li>
`;

const proposalsListTemplate = (proposals) => html`
  <ul id="proposals-list" class="proposals-list visible">
    ${proposals.length
    ? proposals.map(proposalTemplate)
    : html`<li class="no-proposals">No proposals available.</li>`}
  </ul>
`;

function fillProposals(proposals) {
  const container = document.getElementById("proposals-list");
  render(proposalsListTemplate(proposals), container);

  container.querySelectorAll(".proposal-item").forEach((item, i) => {
    item.addEventListener("click", () => {
      console.log("👆 Proposal clicked:", proposals[i]);
      openProposalModal(proposals[i]);
    });
  });
}

/* ===========================
   HELPERS
=========================== */
function formatDate(dateString) {
  if (!dateString) return "N/A";
  const d = new Date(dateString);
  return `${d.getMonth() + 1}-${d.getDate()}-${d
    .getFullYear()
    .toString()
    .slice(2)}`;
}



