import { html, render } from 'https://unpkg.com/lit@3.1.4/index.js?module';

// fetch requests 
export async function fetchRequests() {
    const response = await fetch('./actions/get_requests.php');
    const currentData = await response.json();
    console.log("Fetched data:", currentData);
    renderRequestsTable(currentData.data);
}

function renderRequestsTable(requests) {
    const tableBody = document.querySelector("#requests-table tbody");

    // If no data
    if (!requests || requests.length === 0) {
        const emptyTemplate = html`
      <tr>
        <td colspan="5">No requests found</td>
      </tr>
    `;
        render(emptyTemplate, tableBody);
        return;
    }

    // Render rows
    const rowsTemplate = html`
    ${requests.map(req => html`
      <tr
          @click=${() => window.location.href = `request_details.php?id=${req.id}`}
      >
            <td>${req.request_date}</td>
            <td>
                <div>${req.residence}</div>
            <div style='color: green;'>${req.unit}</div>
            </td>
            <td>
                <div>${req.category}</div>
                <div style='color:green; border:none; width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>${req.description}</div>
            </td>
            <td>
                ${req.provider_name && req.provider_name.trim() !== ""
            ? html`<div>${req.provider_name}</div>`
            : html`<div>Not assigned</div>`}
            </td>
            <td>${req.priority}</td>
            <td style="color: ${req.status ? 'green' : '#b93232ff'}">
                ${req.status || 'Not assigned'}
            </td>

            <td>
                ${req.payment_status === "Paid"
            ? html`
                    <div class="Paid">
                    <i class="bi bi-check-circle-fill"></i> Paid
                    </div>
                `
            : html`
                    <div class="Pending">
                    <i class="bi bi-hourglass-split"></i> Pending
                    </div>
                `}
            </td>
            <td style="vertical-align: middle;">
                <div style="display: flex; gap: 8px; align-items: center; height: 100%;">
                    <button
                    class="btn btn-sm d-flex align-items-center gap-1 px-3 py-2"
                    style="background-color: #00192D; color: white; border: none; border-radius: 8px; 
                            box-shadow: 0 2px 6px rgba(0,0,0,0.1); font-weight: 500;">
                    <i class="bi bi-eye-fill"></i> View
                    </button>

                    <button
                    class="btn btn-sm d-flex align-items-center gap-1 px-3 py-2"
                    style="background-color: #ec5b53; color: white; border: none; border-radius: 8px; 
                            box-shadow: 0 2px 6px rgba(0,0,0,0.1); font-weight: 500;">
                    <i class="bi bi-trash-fill"></i> Delete
                    </button>
                </div>
            </td>
      </tr>
    `)}
  `;

    render(rowsTemplate, tableBody);
}
