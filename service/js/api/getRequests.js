import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";

// Function to fetch requests and call the rendering function
export async function get_requests() {
    try {
        const response = await fetch('./actions/get_requests.php');

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json(); // parse JSON body
        console.log(data);
        fillRequests(data);
        return data;
    } catch (err) {
        console.error("Error fetching requests:", err);
        fillRequests({ success: false, error: err.message });
        return { success: false, error: err.message };
    }
}

// Template for each request, injecting dynamic data
const requestsTemplate = (data) => html`
  <div class="requests-container" id="requests-container">
    ${
      Array.isArray(data)
        ? data.length
          ? data.map(request => html`
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
                      <div class="text-muted">
                         ${request.unit || "Unknown"}
                      </div>
                      <div class="text-muted">
                      <span class="text-muted">Posted:</span> 
                        <i class="text-danger fw-semibold">
                          ${(() => {
                            const d = new Date(request.created_at);
                            const day = d.getDate().toString().padStart(2, '0');
                            const month = d.toLocaleDateString('en-US', { month: 'short' });
                            const year = d.getFullYear().toString().slice(-2);
                            return `${day} ${month} ‘${year}`;
                          })()}
                        </i>
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

                  ${
                    request.provider_id === 2
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
                <div class="row text-muted small mb-3">
                  <div class="col-md-6">
                    <strong>Duration:</strong>
                    <i class="text-dark fw-bold">
                      ${request.duration === 'Not set' ? 'Not set' : request.duration + ' days'}
                    </i>
                  </div>
                  <div class="col-md-6 text-md-end">
                    <strong>Budget:</strong>
                    <i class="text-dark fw-bold">
                      ${request.budget === 'Not set' ? 'Not set' : 'KSH ' + request.budget}
                    </i>
                  </div>
                </div>
              </div>
            `)
          : html`<div class="bg-white rounded p-4 text-center border">
    <div class="mb-3">
        <svg
            class="mx-auto" 
            style="height: 60px; width: 60px; color: #6c757d;"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
            />
        </svg>
    </div>

    <h3 class="h5 fw-semibold text-dark mb-2">
        No Jobs Available
    </h3>

    <p class="text-muted mb-3">
        There are currently no such job postings. Check back later for new opportunities!
    </p>

    <button class="btn btn-warning px-4 py-2">
        Get Notified
    </button>
</div>
`
        : html`
            <div class="job-card mb-3 p-3 bg-white shadow-sm rounded">
              <div class="d-flex justify-content-between">
                <div style="width: 100%;">
                  <div class="request" style="font-weight:bold; color: #00192D;">
                    ${data.request || "No Title"}
                  </div>
                  <div class="d-flex gap-3"> 
                    <div class="text-muted mb-2">
                      ${data.residence || "Unknown residence"}
                    </div>
                    <div class="text-success">
                       ${data.unit || "Unknown"}
                    </div>
                    <div class="text-muted small">
                      <strong>Posted on:</strong>
                      <i class="text-dark fw-bold">
                        ${(() => {
                          const d = new Date(data.created_at);
                          const day = d.getDate().toString().padStart(2, '0');
                          const month = d.toLocaleDateString('en-US', { month: 'short' });
                          const year = d.getFullYear().toString().slice(-2);
                          return `${day} ${month} ‘${year}`;
                        })()}
                      </i>
                    </div>
                  </div>
                  <span class="badge badge-skill">${data.category}</span>
                  <div class="description collapsed mt-2 job-description">
                    <p style="font-style:italic;">
                      ${data.description || "No description available."}
                    </p>
                    <img 
                      id="request-photo" 
                      src="../landlord/pages/maintenance/${data.photo_url}" 
                      alt="Photo" 
                      class="photo-preview w-100 rounded" 
                      height="400px"
                    >
                  </div>
                </div>

                ${
                  data.provider_id === 2
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
                            data-request-id="${data.id}">
                            Apply
                          </button>
                        </div>
                      `
                }
              </div>

              <div class="d-flex justify-content-end">
                <button id="toggleBtn" class="more">More</button>
              </div>
              <div class="row text-muted small mb-3">
                <div class="col-md-6">
                  <strong>Duration:</strong>
                  <i class="text-dark fw-bold">
                    ${data.duration === 'Not set' ? 'Not set' : data.duration + ' days'}
                  </i>
                </div>
                <div class="col-md-6 text-md-end">
                  <strong>Budget:</strong>
                  <i class="text-dark fw-bold">
                    ${data.budget === 'Not set' ? 'Not set' : 'KSH ' + data.budget}
                  </i>
                </div>
              </div>
            </div>
          `
    }
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
            container.innerHTML = `<div class="bg-white rounded p-4 text-center border">

    <div class="mb-3">
        <svg
            class="mx-auto d-block"
            style="height: 60px; width: 60px; color: #6c757d;"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
            />
        </svg>
    </div>

    <h3 class="h5 fw-semibold text-dark mb-2">
        No Jobs Available
    </h3>

    <p class="text-muted mb-3">
        Stay tuned for more job opportunities coming soon!
    </p>

    <button class="btn btn-warning px-4 py-2">
        Get Notified
    </button>

</div>


`;
        } else {
            render(requestsTemplate(requestList), container); // Pass only the requests array to the template
        }
    }
}
export async function fetchRequests() {
    await get_requests(); // Fetch data and render it
}


