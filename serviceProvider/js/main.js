import { get_requests } from "./api.js";
import { add_request_id } from "./modal.js";
import { getAssignedRequests } from "./api/getAssignedJobs.js";
import { submitProposal } from "./api/submitProposal.js";
import { expandCollapseRequest } from "./uiControl.js";
import { getApplications } from "./api/getYourApplications.js";
document.addEventListener("DOMContentLoaded", async () => {
    // Fetch requests
    await get_requests();
    expandCollapseRequest();
    getAssignedRequests();

    // GET applied Requests
    document.getElementById('apps-tab').addEventListener('click', getApplications);
    // Check if the buttons exist
    const buttons = document.querySelectorAll('.apply-btn');
    if (buttons.length > 0) {
        buttons.forEach(button => add_request_id(button));  // Attach the listener to each button
    } else {
        console.error("No apply buttons found. Checking after 1 second...");
        setTimeout(() => {
            const buttonsRetry = document.querySelectorAll('.apply-btn');
            if (buttonsRetry.length > 0) {
                buttonsRetry.forEach(button => add_request_id(button));
            } else {
                console.error("Still no apply buttons found.");
            }
        }, 1000);  // Retry after 1 second (adjust the time if needed)
    }

    // Handle form submission
    const proposalForm = document.getElementById("applyForm");
    if (proposalForm) {
        proposalForm.addEventListener('submit', (e) => submitProposal(e, proposalForm));  // Pass the event object here
    }
});

