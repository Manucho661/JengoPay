import { otherRequests, get_request_details, assignProvider, updateAvailabilty, getProviderDetails } from "./api.js";
import { toggleProposalsORotherRequests, confirmAssignBox, hideAssignBox, applyAvailabilityStyles } from "./uiControl.js";

document.addEventListener("DOMContentLoaded", () => {
  // Load data
  get_request_details();
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

});
