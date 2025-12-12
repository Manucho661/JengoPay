import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";

/* ===========================
   FETCH REQUEST DETAILS
=========================== */
export async function getRequestDetails(customId = null) {
  // Helper: get request ID from custom param or URL
  const getRequestId = () =>
    customId || new URLSearchParams(window.location.search).get("id");

  const id = getRequestId();
  if (!id) {
    console.warn("⚠️ No request ID provided or found in URL.");
    return;
  }

  const url = `./actions/requestDetails/getRequestDetails.php?id=${id}`;

  try {
    const response = await fetch(url);

    if (!response.ok) {
      const errorText = await response.text();
      console.error("❌ Server error:", errorText);
      return;
    }

    const data = await response.json();

    // UI updates
    if (data?.request || data?.photos) fillRequestDetails(data.request, data.photos);
    if (data?.proposals) fillProposals(data.proposals);

    console.log("✅ Request details fetched successfully:", data);
  } catch (err) {
    console.error("❌ Fetch failed:", err);
  }
}

/* ===========================
   RENDER REQUEST DETAILS
=========================== */
function fillRequestDetails(request, photos) {
  const getEl = (id) => document.getElementById(id);

  // Helper for setting text with default
  const setText = (el, value, defaultText = "N/A") => {
    if (!el) return;
    el.textContent =
      value !== null && value !== undefined && value !== "" ? value : defaultText;
  };

  // Helper for setting color based on condition
  const setColor = (el, condition, trueColor = "green", falseColor = "#b93232ff") => {
    if (!el) return;
    el.style.color = condition ? trueColor : falseColor;
  };

  // Basic request details
  setText(getEl("request-name"), request?.title);
  setText(getEl("request-category"), request?.category);
  setText(getEl("request-id"), request?.id);
  setText(getEl("request-property"), request?.residence);
  setText(getEl("created-at"), request?.created_at);
  
  setText(getEl("request-unit"), request?.unit);
  setText(getEl("request-provider"), request?.provider_name, "Unassigned");
  setText(getEl("request-description"), request?.description, "No description provided");

  // Budget & Duration
  const budgetEl = getEl("budget");
  const durationEl = getEl("duration");
  setText(budgetEl, !isNaN(request?.budget) ? `KSH ${request.budget}` : null, "Not set");
  setText(durationEl, !isNaN(request?.duration) ? `${request.duration} days` : null, "Not set");

  // Status & Provider response
  const statusEl = getEl("request-status");
  const providerResponseEl = getEl("provider_response");

  const status = request?.status || "Not assigned";
  const response = request?.provider_response || "Not assigned";

  setText(statusEl, status);
  setColor(statusEl, status === "In progress");

  setText(providerResponseEl, response);
  setColor(providerResponseEl, response === "Accepted");

  // Dataset for buttons
  const terminateBtn = getEl("actualTerminateBtn");
  if (terminateBtn) terminateBtn.dataset.assignmentId = request?.assignment_id;

  const requestProvider = getEl("request-provider");
  if (requestProvider && request?.provider_id)
    requestProvider.dataset.providerId = request.provider_id;

  const availabilityBtn = getEl("availabilityBtn");
  if (availabilityBtn) {
    const availability = request?.availability || "unavailable";
    const requestId = request?.id || "";
    availabilityBtn.dataset.requestId = requestId;
    availabilityBtn.dataset.status = availability;
    availabilityBtn.textContent =
      availability === "available" ? "Set Unavailable" : "Set Available";
  }

  // Photo
  const photoEl = getEl("request-photo");
  if (photoEl) {
    photoEl.src = photos?.[0]?.photo_url || "";
    if (!photos?.length) console.info("ℹ️ No photos available.");
  }
}



/* ===========================
  Request PROPOSALS
=========================== */

function fillProposals(proposals) {
  const container = document.getElementById("proposals-list");
  render(renderProposals(proposals), container);

  // proposal modal
  container.querySelectorAll(".proposal-item").forEach((item, i) => {
    item.addEventListener("click", () => {
      console.log("👆 Proposal clicked:", proposals[i]);
      openProposalModal(proposals[i]);
    });
  });
}
const renderProposals = (proposals) => html`
  <ul id="proposals-list" class="proposals-list visible">
    ${proposals.length
    ? proposals.map(
      (p) => html`
            <li class="proposal-item">
              <img
                src="/Jengopay/landlord/pages/maintenance/images/download.webp"
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
                    <i class="fas fa-circle"></i> KSH ${p.bid_amount || "N/A"}/hr
                  </div>
                  <div><p>${p.description || "No description provided"}</p></div>
                </div>
              </div>
            </li>
          `
    )
    : html`<li class="no-proposals">No proposals available.</li>`}
  </ul>
`;


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

// proposalModal
function openProposalModal(proposal) {
  console.log("Opening proposal modal with:", proposal);

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

  // --- populate modal content ---
  safeSet(
  "modalPhoto",
  "/Jengopay/landlord/pages/maintenance/images/download.webp",
  "src"
);

  safeSet("modalName", proposal.name || "Unknown Provider");
  safeSet("modalBadge", proposal.topRated ? "Top Rated" : "");
  safeSet("modalTitle", proposal.title || "Service Provider");
  safeSet("modalRate", `KSH ${proposal.bid_amount || "N/A"}/hr`);
  safeSet("modalDelivery", proposal.estimated_time ? `${proposal.estimated_time} delivery` : "N/A");
  safeSet("modalJobs", `✅ ${proposal.jobs_completed || 0} jobs completed`);
  safeSet("modalDescription", proposal.cover_letter || "No description provided");
  safeSet("modalLocation", proposal.location || "Unknown");

  const actualAssignBtn = document.getElementById("actualAssignBtn");
  actualAssignBtn.setAttribute("data-provider-id", proposal.provider_id);

  // Check if the request is already assigned
  const el = document.getElementById("request-provider");
  const assignedProvider = el.dataset.providerId?.trim().toLowerCase();

  if (assignedProvider && assignedProvider !== "null") {
    document.getElementById('assignBox').style.display = "none";

    const footer = document.getElementById('proposalModalFooter');
    const providerName = el.textContent.trim();

    // Check if the message already exists
    const existingMsg = footer.querySelector('p[data-assigned="true"]');
    if (!existingMsg) {
      const msg = document.createElement('p');
      msg.innerHTML = `This request is already assigned to <span style="color: green; font-weight: bold;">${providerName}</span>. To reassign, terminate the contract.`;

      // Add a custom attribute to track that this message was created
      msg.setAttribute('data-assigned', 'true');

      footer.appendChild(msg);
    }
  }

  // --- show modal with Bootstrap ---
  const modalEl = document.getElementById("proposalModal");
  if (!modalEl) {
    console.error("❌ Cannot open modal: #proposalModal not found in DOM");
    return;
  }

  const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
  modal.show();
}

