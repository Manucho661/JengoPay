import { otherRequests } from "./api/otherRequestDetails.js";
import { getRequestDetails } from "./api/getRequestDetails.js";
import { assignProvider } from "./api/assignProvider.js";
import { updateAvailabilty } from "./api/updateAvailabilty.js";
import { getProviderDetails } from "./api/getProviderDetails.js";
import { setDurationBudget } from "./api/durationBudget.js";
import { sendText } from "./api/sendText.js";
import { getTextMessages } from "./api/getTextMessages.js";
import { toggleProposalsORotherRequests, confirmAssignBox, hideAssignBox, applyAvailabilityStyles } from "./uiControl.js";

import { terminateContract, terminateContractBox, hideTerminateBox } from "./api/terminateProvider.js";

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

  // Terminate contract
  document.getElementById('terminateBtn').addEventListener('click', terminateContractBox);
  document.getElementById('cancelTerminateBtn').addEventListener('click', hideTerminateBox);

  document.getElementById('actualTerminateBtn').addEventListener('click', terminateContract);

  // send text
  document.getElementById('chatForm').addEventListener('submit', sendText);

  // get text Messages
  getTextMessages();

  // 🔁 refresh messages every 3 seconds
  setInterval(getTextMessages, 3000);

});
