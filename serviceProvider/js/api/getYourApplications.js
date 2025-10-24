import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";

export async function getApplications() {
  try {
    const response = await fetch("actions/getApplications.php");
    if (!response.ok) {
      console.log("A problem occurred while sending the request");
      return;
    }

    // ✅ You forgot 'await' here
    const data = await response.json();
    console.log("Fetched applications:", data);

    // ✅ Get the container element
    const container = document.getElementById("applications");

    // ✅ Create the HTML dynamically using Lit
    const template = html`
      <div class="section-title">
        Jobs You've been assigned.
        <span class="text-muted"><i>Click Accept button to accept</i></span>
      </div>

      <ul class="list-group" id="applicationsListGroup">
        ${data.data.map(
          (job) => html`
          <div class="list-group-item p-3 rounded border-0 border-bottom">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div style="font-weight: bold; color: #00192D;">
                ${job.request}
                </div>
                <span class="badge bg-success">Active</span>
            </div>
            <!-- Job location -->
            <div class="text-muted mb-2">
                ${job.residence} · <span>${job.unit}</span>
            </div>

            <!-- Job description -->
            <div class="appliedJobDescription collapsed mb-3">
              <p> ${job.description}</p>
              <img 
                id="request-photo" 
                src="../landlord/pages/maintenance/${job.photo_url}" 
                alt="Photo" 
                class="photo-preview w-100 rounded" 
                height="400px"
              >
            </div>
            <div class="d-flex justify-content-end">
                  <button id="toggleBtn" class="more">More</button>
            </div>
            <!-- Job details row -->
            <div class="row text-muted small mb-3">
                <div class="col-md-6">
                  <strong>Applied: <i class="text-dark fw-bold"> ${(() => {
                      const d = new Date(job.submitted_at);
                      const day = d.getDate().toString().padStart(2, '0');
                      const month = d.toLocaleDateString('en-US', { month: 'short' });
                      const year = d.getFullYear().toString().slice(-2);
                      return `${day} ${month} ‘${year}`;
                    })()}</i>  
                  </strong>  
                </div>
                <div class="col-md-6 text-md-end">
                  <strong>Client's budget: <i class="text-dark fw-bold"> ${job.budget}</i></strong>
                </div>
                <div class="col-md-6">
                    <strong>Duration: <i class="text-dark fw-bold"> ${job.estimated_time}</i></strong>
                </div>
                <div class="col-md-6 text-md-end">
                  <strong>Your budget: <i class="text-dark fw-bold">${job.bid_amount}</i></i></strong> 
                </div>
            </div>
            <!-- Action buttons -->
            <div class="text-end">
                <button class="btn btn-outline-success btn-sm">Accept</button>
                <button class="btn btn-outline-danger btn-sm">Decline</button>
            </div>
        </div>
          `
        )}
      </ul>
    `;

    // ✅ Render into the element
    render(template, container);
  } catch (err) {
    console.error("❌ Failed to fetch proposals:", err);
  }
}
