import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";

/* ===========================
   OTHER REQUESTS
=========================== */
export async function otherRequests() {
  try {
    const response = await fetch(
      `./actions/requestDetails/otherRequests.php`
    );
    const data = await response.json();
    // console.log("✅ Other requests fetched:", data);
    renderRequestsList(data.data || data);
  } catch (err) {
    console.error("❌ Error fetching other requests:", err);
  }
}

function renderRequestsList(requests) {
  const container = document.getElementById("requestList");
  container.innerHTML = "";

  if (!requests?.length) {
    render(
      html`
        <li class="request-item">
          <div class="request-content">
            <div class="request-desc">No other requests found</div>
          </div>
        </li>
      `,
      container
    );
    return;
  }

  requests.forEach((req) => {
    const li = document.createElement("li");
    li.classList.add("request-item");

    render(
      html`
      <div class="request-icon bg-warning text-white"><i class="fas fa-tools"></i></div>
      <div class="request-content">
        <div class="request-desc">${req.description}</div>
        <div class="request-meta">
          <div class="request-date">
            <i class="far fa-calendar-alt"></i> ${req.request_date}
          </div>
          <div class="request-status">
            <i class="fas fa-circle"></i> ${req.status}
          </div>
          <div class="request-priority">
            <i class="fas fa-circle"></i> ${req.priority}
          </div>
        </div>
      </div>
    `,
      li
    );

    // ✅ Load details dynamically instead of reloading
    li.addEventListener("click", () => {
      // Optionally update URL (no reload)
      window.history.replaceState(null, "", `?id=${req.id}`);

      // Fetch and display request details dynamically
    
    });

    container.appendChild(li);
  });

}