// ─────────────────────────────────────────────
// 📦 Imports
// ─────────────────────────────────────────────
import { getAssignedRequests } from "./api/getAssignedJobs.js";
import { getApplications } from "./api/getYourApplications.js";
import { sendResponse } from "./api/assignmentResponse.js";
import { getTextMessages } from "./api/getTextMessages.js";
import { sendText } from "./api/sendText.js";


// ─────────────────────────────────────────────
// 🚀 Main Initialization
// ─────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", async () => {
  try {
    // ── 1️⃣ Initial Data Fetching ─────────────────────────
    // await get_requests();
    expandCollapseRequest();

    await getAssignedRequests();
    await getApplications();
    expandCollapseApplication();

    // ── 2️⃣ Apply Buttons Setup ───────────────────────────
    initializeApplyButtons();

    // ── 3️⃣ Chat Setup ───────────────────────────────────
    initializeChat();

    // ── 4️⃣ Proposal Form Setup ──────────────────────────
    const proposalForm = document.getElementById("applyForm");
    if (proposalForm) {
      proposalForm.addEventListener("submit", (e) => submitProposal(e, proposalForm));
    }

    // ── 5️⃣ Assignment Response Setup ────────────────────
    const acceptBtn = document.getElementById("acceptBtn");
    const declineBtn = document.getElementById("declineBtn");

    if (acceptBtn) {
      acceptBtn.onclick = function () {
        sendResponse.call(this, "Accepted");
      };
    }

    if (declineBtn) {
      declineBtn.onclick = function () {
        sendResponse.call(this, "Declined");
      };
    }

    // ── 6️⃣ Applications Tab Refresh ─────────────────────
    const appsTab = document.getElementById("apps-tab");
    if (appsTab) {
      appsTab.addEventListener("click", getApplications);
    }

  } catch (err) {
    console.error("❌ Initialization error:", err);
  }
});


// ─────────────────────────────────────────────
// 💬 Chat System Setup
// ─────────────────────────────────────────────
function initializeChat() {
  const chatForm = document.getElementById("chatForm");
  if (!chatForm) return;

  // Handle form submission
  chatForm.addEventListener("submit", sendText);

  // Initial load of chat messages
  getTextMessages();

  // Refresh every 3 seconds safely
  let isFetching = false;
  setInterval(async () => {
    if (isFetching) return;
    isFetching = true;
    await getTextMessages();
    isFetching = false;
  }, 3000);
}


// ─────────────────────────────────────────────
// 🧩 Apply Button Initialization
// ─────────────────────────────────────────────
function initializeApplyButtons() {
  const buttons = document.querySelectorAll(".apply-btn");

  if (buttons.length > 0) {
    buttons.forEach((button) => add_request_id(button));
  } else {
    console.warn("⚠️ No apply buttons found. Retrying in 1 second...");
    setTimeout(() => {
      const buttonsRetry = document.querySelectorAll(".apply-btn");
      if (buttonsRetry.length > 0) {
        buttonsRetry.forEach((button) => add_request_id(button));
      } else {
        console.error("❌ Still no apply buttons found.");
      }
    }, 1000);
  }
}
