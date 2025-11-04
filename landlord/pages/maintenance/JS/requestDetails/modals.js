export function openProposalModal(proposal) {
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
  safeSet("modalPhoto", proposal.provider_photo_url || "https://i.pravatar.cc/70", "src");
  safeSet("modalName", proposal.name || "Unknown Provider");
  safeSet("modalBadge", proposal.topRated ? "Top Rated" : "");
  safeSet("modalTitle", proposal.title || "Service Provider");
  safeSet("modalRate", `$${proposal.bid_amount || "N/A"}/hr`);
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