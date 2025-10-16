import { otherRequests } from "./api/otherRequestDetails.js";
import { getRequestDetails } from "./api/getRequestDetails.js";
import { assignProvider } from "./api/assignProvider.js";
import { updateAvailabilty } from "./api/updateAvailabilty.js";
import { getProviderDetails } from "./api/getProviderDetails.js";
import { setDurationBudget } from "./api/durationBudget.js";
import { toggleProposalsORotherRequests, confirmAssignBox, hideAssignBox, applyAvailabilityStyles } from "./uiControl.js";

document.addEventListener("DOMContentLoaded", () => {
  // Load data
  getRequestDetails();
  otherRequests();

  // set the availability bg color
  const btn = document.getElementById("availabilityBtn");
  if (btn) {
    applyAvailabilityStyles(btn.dataset.status);
  }

  // Tab switching
  const proposalsTab = document.getElementById("proposals");
  const otherRequestsTab = document.getElementById("otherRequests");

  proposalsTab?.addEventListener("click", () =>
    toggleProposalsORotherRequests("proposals-list")
  );

  otherRequestsTab?.addEventListener("click", () =>
    toggleProposalsORotherRequests("requestList")
  );

  // display confirm assign btn
  document.getElementById('assignBtn').addEventListener('click', confirmAssignBox);
  document.getElementById('cancelAssignBtn').addEventListener('click', hideAssignBox);

  // assign provider
  document.getElementById('actualAssignBtn').addEventListener('click', assignProvider);

  // update availability
  document.getElementById('availabilityBtn').addEventListener('click', updateAvailabilty);

  // get request details
  document.getElementById('request-provider').addEventListener('click', getProviderDetails);

  // set budget and duration
  document.getElementById('durationBudget').addEventListener('submit', setDurationBudget);
});
