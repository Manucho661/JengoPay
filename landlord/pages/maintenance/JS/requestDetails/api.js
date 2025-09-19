import { html, render } from 'https://unpkg.com/lit@3.1.4/index.js?module';
import { openProposalModal } from "./modals.js";

/* ===========================
   FETCH REQUEST DETAILS
=========================== */
export async function get_request_details() {
  const id = new URLSearchParams(window.location.search).get("id");
  if (!id) return console.warn("No request ID in URL.");

  try {
    const response = await fetch(`./actions/request_details/get_request_details.php?id=${id}`);
    if (!response.ok) {
      console.error("Server error:", await response.text());
      return;
    }

    const data = await response.json();
    fillRequestDetails(data.request, data.photos);
    fillProposals(data.proposals);
    console.log("Fetched request details:", data);
  } catch (err) {
    console.error("Fetch failed:", err);
  }
}

/* ===========================
   RENDER REQUEST DETAILS
=========================== */
function fillRequestDetails(request, photos) {
  document.getElementById("request-property").textContent = request?.residence || "N/A";
  document.getElementById("request-unit").textContent = request?.unit || "N/A";
  document.getElementById("request-provider").textContent = request?.provider_name || "Unassigned";
  document.getElementById("request-status").textContent = request?.status || "Unassigned";
  document.getElementById("request-description").textContent = request?.description || "No description provided";

  const photoEl = document.getElementById("request-photo");
  if (photos?.length > 0) {
    photoEl.src = photos[0].photo_url;
  } else {
    photoEl.src = "";
    console.log("No photos available.");
  }
}

/* ===========================
   PROPOSALS
=========================== */
// Single Proposal Template
const proposalTemplate = (p) => html`
  <li class="proposal-item">
    <img src="${p.provider_photo_url || 'https://i.pravatar.cc/70'}"
         alt="Profile Picture"
         class="profile-pic me-3">
    <div class="proposal-content">
      <div class="d-flex mb-0">
        <p class="text-dark my-0"><b>${p.name || 'Unknown Provider'}</b>&nbsp;</p>
        <p class="text-muted my-0">${formatDate(p.submitted_at)}</p>
      </div>
      <div class="request-meta">
        <div class="request-status"><i class="fas fa-circle"></i> ${p.ratings || 'N/A'}</div>
        <div class="request-priority text-success"><i class="fas fa-circle"></i> $${p.bid_amount || 'N/A'}/hr</div>
        <div><p>${p.description || 'No description provided'}</p></div>
      </div>
    </div>
  </li>
`;

// Proposal List Template
const proposalsListTemplate = (proposals) => html`
  <ul id="proposals-list" class="proposals-list visible">
    ${proposals.length
    ? proposals.map(proposalTemplate)
    : html`<li class="no-proposals">No proposals available.</li>`}
  </ul>
`;

// Render + Attach Events
function fillProposals(proposals) {
  const container = document.getElementById("proposals-list");
  render(proposalsListTemplate(proposals), container);

  container.querySelectorAll(".proposal-item").forEach((item, i) => {
    item.addEventListener("click", () => {
      console.log("Clicked proposal:", proposals[i]);
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
  return `${d.getMonth() + 1}-${d.getDate()}-${d.getFullYear().toString().slice(2)}`;
}

/* ===========================
   FETCH PROPOSALS
=========================== */
export async function fetchProposals() {
  const id = new URLSearchParams(window.location.search).get("id");
  if (!id) return;

  try {
    const response = await fetch(`./actions/request_details/get_proposals.php?id=${id}`);
    const data = await response.json();
    console.log("Fetched proposals:", data);
    // fillProposals(data.data); // uncomment if needed
  } catch (err) {
    console.error("Failed to fetch proposals:", err);
  }
}

/* ===========================
   OTHER REQUESTS
=========================== */
export async function otherRequests() {
  try {
    const response = await fetch(`./actions/request_details/other_requests.php`);
    const data = await response.json();
    console.log("Fetched other requests:", data);

    renderRequestsList(data.data || data); // flexible depending on backend
  } catch (err) {
    console.error("Error fetching other requests:", err);
  }
}

function renderRequestsList(requests) {
  const container = document.getElementById("requestList");
  container.innerHTML = "";

  if (!requests?.length) {
    render(html`
      <li class="request-item">
        <div class="request-content">
          <div class="request-desc">No other requests found</div>
        </div>
      </li>
    `, container);
    return;
  }

  requests.forEach(req => {
    const li = document.createElement("li");
    li.classList.add("request-item");

    render(html`
      <div class="request-icon"><i class="fas fa-tools"></i></div>
      <div class="request-content">
        <div class="request-desc">${req.description}</div>
        <div class="request-meta">
          <div class="request-date"><i class="far fa-calendar-alt"></i> ${req.request_date}</div>
          <div class="request-status"><i class="fas fa-circle"></i> ${req.status}</div>
          <div class="request-priority"><i class="fas fa-circle"></i> ${req.priority}</div>
        </div>
      </div>
    `, li);

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
  // Get provider_id from button attribute
  let providerId = this.getAttribute("data-provider-id");

  // Get request_id from page URL
  let urlParamss = new URLSearchParams(window.location.search);
  let requestId = urlParamss.get("id");

  try {
    // Make fetch call to your PHP action
    let response = await fetch(
      `./actions/request_details/assign_provider.php?request_id=${requestId}&provider_id=${providerId}`
    );

    // Parse JSON response
    let data = await response.json();

    if (data.status === "success") {
      console.log("✅ Provider assigned successfully:", data);

      // Re-fetch and update the UI after assigning the provider
      get_request_details();  // This will fetch and update the page automatically
      const proposalModalEl = document.getElementById('proposalModal');
      const proposalModal = bootstrap.Modal.getInstance(proposalModalEl) || new bootstrap.Modal(proposalModalEl);
      proposalModal.hide();


    } else {
      console.log("⚠️ Assignment failed:", data.message);
    }
  } catch (err) {
    console.error("❌ Error assigning provider:", err);
  }
}
