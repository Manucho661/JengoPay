import { otherRequests } from "./api/otherRequestDetails.js";
import { assignProvider } from "./api/assignProvider.js";
import { updateAvailabilty } from "./api/updateAvailabilty.js";
import { getProviderDetails } from "./api/getProviderDetails.js";
import { setDurationBudget } from "./setDurationBudget.js";
import { sendText } from "./api/sendText.js";
import { getTextMessages } from "./api/getTextMessages.js";
import { toggleProposalsORotherRequests, confirmAssignBox, hideAssignBox } from "./uiControl.js";
import { terminateContract, terminateContractBox, hideTerminateBox } from "./api/terminateProvider.js";
import { startLoadingAnimation, stopLoadingAnimation } from "./preLoader.js";


// 🔥 Clean event listeners grouped in one place
function setupEvents() {
  document.getElementById("proposals")?.addEventListener("click", () =>
    toggleProposalsORotherRequests("proposals-list")
  );

  document.getElementById("otherRequests")?.addEventListener("click", () =>
    toggleProposalsORotherRequests("requestList")
  );

  document.getElementById("assignBtn").addEventListener("click", confirmAssignBox);
  document.getElementById("cancelAssignBtn").addEventListener("click", hideAssignBox);
  document.getElementById("actualAssignBtn").addEventListener("click", assignProvider);

  document.getElementById("availabilityBtn").addEventListener("click", updateAvailabilty);
  document.getElementById("request-provider").addEventListener("click", getProviderDetails);

  document.getElementById("durationBudget").addEventListener("submit", setDurationBudget);

  document.getElementById("terminateBtn").addEventListener("click", terminateContractBox);
  document.getElementById("cancelTerminateBtn").addEventListener("click", hideTerminateBox);
  document.getElementById("actualTerminateBtn").addEventListener("click", terminateContract);

  document.getElementById("chatForm").addEventListener("submit", sendText);

  // Chat refresh
  // getTextMessages();
  // setInterval(getTextMessages, 1000);
}


// 🔥 Main logic wrapped in async function
document.addEventListener("DOMContentLoaded", async () => {
  const main = document.getElementById("appMain");
  // show preloader + hide main
  main.style.display = "none";
  startLoadingAnimation();

  try {
    await getRequestDetails();
    await otherRequests();
  } catch (err) {
    console.error(err);
    // show error message perhaps inside the preloader
    const pre = document.getElementById("preloader");
    if (pre) pre.innerText = "Failed to load data.";
    return;
  }

  // stop animation (waits for fade-out to finish)
  await stopLoadingAnimation();
  // reveal main
  main.style.display = "block";


  setupEvents();
});


