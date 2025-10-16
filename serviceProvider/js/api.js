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
                ${request.request || "No Title"}
            </div>
            <div class="d-flex gap-3"> 
                <div class="text-muted mb-2">
                    ${request.residence || "Unknown residence"}
                </div>
                <div class="text-success">
                    KSH ${request.budget || "Unknown"}
                </div>
                <div>
                    ${request.duration || "Unknown"} hrs
                </div>
            </div>
            <span class="badge badge-skill">${request.category}</span>
            <div class="description collapsed mt-2 job-description">
                <p style="font-style:italic;">
                    ${request.description || "No description available."}
                </p>
                <img 
                    id="request-photo" 
                    src="../landlord/pages/maintenance/${request.photo_url}" 
                    alt="Photo" 
                    class="photo-preview w-100 rounded" 
                    height="400px"
                >
            </div>
        </div>

        <!-- Conditional Apply Section -->
        ${request.provider_id === 2
            ? html`
                <div class="text-end" style="white-space: nowrap;">
                    <span class="text-success fw-bold">Applied</span>
                </div>
              `
            : html`
                <div class="text-end" style="white-space: nowrap;">
                    <button class="btn btn-outline-warning apply-btn text-dark" 
                        data-bs-toggle="modal" 
                        data-bs-target="#applyModal"
                        data-request-id="${request.id}">
                        Apply
                    </button>
                </div>
              `
        }
    </div>

    <div class="d-flex justify-content-end">
        <button id="toggleBtn" class="more">More</button>
    </div>
</div>
`;


// Template for the list of requests
const requestsListTemplate = (requests) => html`
    <div class="requests-container" id="requests-container">
        ${requests.length
        ? requests.map(requestTemplate) // Dynamically render each request
        : html`<p class="text-danger">No requests available.</p>`}
    </div>
`;

// Function to render requests into the DOM
function fillRequests(requests) {
    const container = document.getElementById("requests-list-container"); // Container element
    if (requests.success === false) {
        container.innerHTML = `<p>Error: ${requests.error}</p>`;
    } else {
        const requestList = requests.data || []; // Use the 'data' property for the requests array
        if (requestList.length === 0) {
            container.innerHTML = `<p class="text-danger">No requests available.</p>`;
        } else {
            render(requestsListTemplate(requestList), container); // Pass only the requests array to the template
        }
    }
}
export async function fetchRequests() {
    await get_requests(); // Fetch data and render it
}


