import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";

// Function to fetch requests and call the rendering function
export async function get_requests() {
    try {
        const response = await fetch('./actions/get_requests.php');

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json(); // parse JSON body
        console.log(data); // logs the parsed JSON from PHP

        // Call the fillRequests function with the fetched data
        fillRequests(data); // Passing the data to the fillRequests function

        return data; // Return the data so other code can use it (if needed)
    } catch (err) {
        console.error("Error fetching requests:", err);
        // If there's an error, pass an error message to fillRequests
        fillRequests({ success: false, error: err.message });
        return { success: false, error: err.message };
    }
}

// Template for each request, injecting dynamic data
const requestTemplate = (request) => html`
<div class="job-card mb-3 p-3 bg-white shadow-sm rounded">
    <div class="d-flex justify-content-between">
        <div style="width: 100%;">
            <div class="request" style="font-weight:bold; color: #00192D;">
                ${request.request || "No Title"} <!-- Dynamic title -->
            </div>
            <div class="text-muted mb-2">
                ${request.residence || "Unknown Client"} <!-- Dynamic client name -->
            </div>
            <span class="badge badge-skill">${request.category}</span> <!-- Dynamic skills -->
            <p class="mt-2 job-description" style="font-style:italic;">
                ${request.description || "No description available."} <!-- Dynamic description -->
            </p>
        </div>
        <!-- Apply Button -->
        <div class="text-end" style="white-space: nowrap;">
            <button class="btn btn-outline-warning apply-btn text-dark" 
                data-bs-toggle="modal" 
                data-bs-target="#applyModal"
                data-request-id="${request.id}">
                Apply
            </button>
        </div>

    </div>
</div>
`;

// Template for the list of requests
const requestsListTemplate = (requests) => html`
    <div class="requests-container">
        ${requests.length
        ? requests.map(requestTemplate) // Dynamically render each request
        : html`<p class="text-danger">No requests available.</p>`}
    </div>
`;

// Function to render requests into the DOM
// Function to render requests into the DOM
function fillRequests(requests) {
    const container = document.getElementById("requests-list-container"); // Container element

    // Check if 'requests' is successful and contains data
    if (requests.success === false) {
        // Handle error if any
        container.innerHTML = `<p>Error: ${requests.error}</p>`;
    } else {
        // Check if there are requests in the 'data' property
        const requestList = requests.data || []; // Use the 'data' property for the requests array

        if (requestList.length === 0) {
            // If there are no requests, display a "No requests available" message
            container.innerHTML = `<p class="text-danger">No requests available.</p>`;
        } else {
            // Render the list of requests
            render(requestsListTemplate(requestList), container); // Pass only the requests array to the template
        }
    }
}

// Call the get_requests function and pass fillRequests to render the data
export async function fetchRequests() {
    await get_requests(); // Fetch data and render it
}


// submit proposals

export async function submit_proposal(e, proposalForm) {
    e.preventDefault();
    console.log("Proposal Form submitted");

    // Log the form data before submitting
    const formData = new FormData(proposalForm);
    formData.forEach((value, key) => {
        console.log(`${key}: ${value}`);
    });

    try {
        // Correctly await fetch
        const response = await fetch("actions/submit_application.php", {
            method: "POST",
            body: formData, // Use formData, not the whole proposalForm
        });

        // Check if the response is OK
        if (!response.ok) {
            throw new Error(`Failed to submit: ${response.statusText}`);
        }

        // Assuming the response is JSON, parse it
        const data = await response.json();
        console.log("✅ Proposals fetched:", data);

        // Reload the page after successful submission
        window.location.reload();
    } catch (err) {
        console.error("❌ Failed to fetch proposals:", err);
    }
}

