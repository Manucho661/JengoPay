import { html, render } from 'https://unpkg.com/lit@3.1.4/index.js?module';

// fetch requests 
export async function fetchRequests(page) {
    const itemsPerPage = 4;
    const currentPage = page;

    const response = await fetch(`./actions/getRequests.php?page= ${page}&limit=${itemsPerPage}`);
    const requests = await response.json();
    console.log("Fetched data:", requests);
    updateInfo(requests.start, requests.end, requests.totalRecords);
    updatePagination(currentPage, requests.total_pages);
    renderRequestsTable(requests.data);
    chartRequests(requests.data);
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



// pagination
function updateInfo(start, end, totalRecords) {
    document.getElementById('showing-start').textContent = start;
    document.getElementById('showing-end').textContent = end;
    document.getElementById('total-records').textContent = totalRecords;
}

export function updatePagination(currentPage, totalPages) {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    // Previous button
    const prevBtn = document.createElement('button');
    prevBtn.textContent = '« Previous';
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => fetchRequests(currentPage - 1);
    pagination.appendChild(prevBtn);

    // Page number buttons
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);

    if (startPage > 1) {
        const firstBtn = document.createElement('button');
        firstBtn.textContent = '1';
        firstBtn.onclick = () => fetchRequests(1);
        pagination.appendChild(firstBtn);

        if (startPage > 2) {
            const dots = document.createElement('span');
            dots.textContent = '...';
            pagination.appendChild(dots);
        }
    }

    for (let i = startPage; i <= endPage; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.textContent = i;
        pageBtn.className = i === currentPage ? 'active' : '';
        pageBtn.onclick = () => fetchRequests(i);
        pagination.appendChild(pageBtn);
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            const dots = document.createElement('span');
            dots.textContent = '...';
            pagination.appendChild(dots);
        }

        const lastBtn = document.createElement('button');
        lastBtn.textContent = totalPages;
        lastBtn.onclick = () => fetchRequests(totalPages);
        pagination.appendChild(lastBtn);
    }

    // Next button
    const nextBtn = document.createElement('button');
    nextBtn.textContent = 'Next »';
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = () => fetchRequests(currentPage + 1);
    pagination.appendChild(nextBtn);
}


// CHART SECTION
function chartRequests(requests) {
    const grouped = {};

    // Group requests by month
    requests.forEach(element => {
        const month = element.created_at.slice(5, 7); // "YYYY-MM"
        if (!grouped[month]) grouped[month] = 0;
        grouped[month] += 1; // counting number of requests per month
    });

    // Convert object to arrays for Chart.js
    const labels = Object.keys(grouped);   // ["2025-01", "2025-02"]
    const values = Object.values(grouped); // [5, 3, ...]

    console.log(labels, values);

    // Draw the chart
    function drawChart(labels, values) {
        const ctx = document.getElementById('requestsGraph').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // x-axis
                datasets: [{
                    label: 'Requests',   // series name
                    data: values,        // y-axis
                    borderWidth: 2,
                    backgroundColor: "rgba(0, 25, 45, 0.2)",
                    borderColor: "#00192D",
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: "#FFC107",
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    drawChart(labels, values);
}


