import { otherRequests, get_request_details } from "./api.js";
import { toggleProposalsORotherRequests, confirmAssignBox, hideAssignBox } from "./uiControl.js";

document.addEventListener("DOMContentLoaded", () => {
  // Load data
  get_request_details();
  otherRequests();

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
  document.getElementById('assignBtn').addEventListener('click', confirmAssignBox );
    document.getElementById('cancelAssignBtn').addEventListener('click', hideAssignBox );

});
