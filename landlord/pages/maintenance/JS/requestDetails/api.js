import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";
import { openProposalModal } from "./modals.js";
import { applyAvailabilityStyles } from "./uiControl.js";

/* ===========================
   FETCH REQUEST DETAILS
=========================== */
export async function get_request_details() {
  const id = new URLSearchParams(window.location.search).get("id");
  if (!id) {
    console.warn("⚠️ No request ID in URL.");
    return;
  }

  try {
    const response = await fetch(
      `./actions/request_details/get_request_details.php?id=${id}`
    );

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
  document.getElementById("request-property").textContent =
    request?.residence || "N/A";
  document.getElementById("request-unit").textContent = request?.unit || "N/A";
  document.getElementById("request-provider").textContent =
    request?.provider_name || "Unassigned";
  document.getElementById("request-status").textContent =
    request?.status || "Unassigned";
  document.getElementById("request-description").textContent =
    request?.description || "No description provided";

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

/* ===========================
   FETCH PROPOSALS
=========================== */
export async function fetchProposals() {
  const id = new URLSearchParams(window.location.search).get("id");
  if (!id) return;

  try {
    const response = await fetch(
      `./actions/request_details/get_proposals.php?id=${id}`
    );
    const data = await response.json();
    console.log("✅ Proposals fetched:", data);

    // Uncomment if you want to render directly:
    // fillProposals(data.data);
  } catch (err) {
    console.error("❌ Failed to fetch proposals:", err);
  }
}

/* ===========================
   OTHER REQUESTS
=========================== */
export async function otherRequests() {
  try {
    const response = await fetch(
      `./actions/request_details/other_requests.php`
    );
    const data = await response.json();
    console.log("✅ Other requests fetched:", data);

    renderRequestsList(data.data || data);
  } catch (err) {
    console.error("❌ Error fetching other requests:", err);
  }
}

function renderRequestsList(requests) {
  const container = document.getElementById("requestList");
  container.innerHTML = "";

  if (!requests?.length) {
    render(
      html`
        <li class="request-item">
          <div class="request-content">
            <div class="request-desc">No other requests found</div>
          </div>
        </li>
      `,
      container
    );
    return;
  }

  requests.forEach((req) => {
    const li = document.createElement("li");
    li.classList.add("request-item");

    render(
      html`
        <div class="request-icon"><i class="fas fa-tools"></i></div>
        <div class="request-content">
          <div class="request-desc">${req.description}</div>
          <div class="request-meta">
            <div class="request-date">
              <i class="far fa-calendar-alt"></i> ${req.request_date}
            </div>
            <div class="request-status">
              <i class="fas fa-circle"></i> ${req.status}
            </div>
            <div class="request-priority">
              <i class="fas fa-circle"></i> ${req.priority}
            </div>
          </div>
        </div>
      `,
      li
    );

    li.addEventListener("click", () => {
      window.location.href = `request_details.php?id=${req.id}`;
    });

    container.appendChild(li);
  });
}

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

/* ===========================
   UPDATE AVAILABILITY
=========================== */
export async function updateAvailabilty() {
  const btn = document.getElementById("availabilityBtn");
  const requestId = btn.dataset.requestId;
  const currentStatus = btn.dataset.status;

  // Toggle status
  const newStatus = currentStatus === "available" ? "unavailable" : "available";

  try {
    // Disable button temporarily (avoid double clicks)
    btn.disabled = true;
    btn.textContent = "Updating...";

    // Send update to backend
    const response = await fetch(
      `./actions/request_details/update_availabilty.php?id=${requestId}&status=${newStatus}`
    );
    const data = await response.json();

    if (data.status === "success") {
      console.log("✅ Availability updated:", data);

      // Update button instantly
      btn.dataset.status = newStatus;
      btn.textContent =
        newStatus === "available" ? "Set Unavailable" : "Set Available";

          applyAvailabilityStyles(newStatus);
    } else {
      console.warn("⚠️ Failed to update availability:", data.message);
      btn.textContent =
        currentStatus === "available" ? "Set Unavailable" : "Set Available";
    }
  } catch (err) {
    console.error("❌ Error updating availability:", err);
    btn.textContent =
      currentStatus === "available" ? "Set Unavailable" : "Set Available";
  } finally {
    btn.disabled = false;
  }
}

/* ===========================
   GET PROVIDER DETAILS
=========================== */
export async function getProviderDetails() {

  console.log("get provider details is being reached");
  // const providerId = this.getAttribute("data-provider-id");

  // try {
  //   const response = await fetch(
  //     `./actions/request_details/get_provider_details.php?provider_id=${providerId}`
  //   );

  //   const data = await response.json();

    
  // } catch (err) {
  //   console.error("❌ Error assigning provider:", err);
  // }
}
