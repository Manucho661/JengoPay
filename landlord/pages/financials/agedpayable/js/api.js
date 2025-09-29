import { html, render } from 'https://unpkg.com/lit@3.1.4/index.js?module';

export async function get_aged_payables() {
    try {
        const response = await fetch("actions/getAgedPayables.php");

        if (!response.ok) {
            console.log("Couldn't query data");
            return;
        }

        const data = await response.json();
        console.log(data);
        populateAccountsTable(data);  // Populate the table with the data
    } catch (err) {
        console.log('Server couldn\'t be reached');
    }
}

function populateAccountsTable(accounts) {
    const tableBody = document.querySelector('#accountsTable tbody');
    if (!tableBody) return;
    tableBody.innerHTML = '';

    const tableRows = accounts.data.map((account, i) => {
        const collapseId = `collapse-${i}`;
        return html`
            <!-- Main row (manual toggle via JS) -->
            <tr class="main-row"
                data-bs-target="#${collapseId}"
                aria-expanded="false"
                aria-controls="${collapseId}"
                style="cursor: pointer;">
                <td><span class="text-warning" style="font-size: 20px;">▸</span> ${account.supplier}</td>
                <td>${account['0-30 Days']}</td>
                <td>${account['31-60 Days']}</td>
                <td>${account['61-90 Days']}</td>
                <td>${account['90+ Days']}</td>
                <td>KSH&nbsp; ${account['Total Payable']}</td>
            </tr>

            <!-- Collapsible row -->
            <tr>
                <td colspan="6" class="p-0">
                    <div id="${collapseId}" class="collapse" data-supplier="${account.supplier}">
                        <div class="details-loading text-muted p-2">Loading...</div>
                    </div>
                </td>
            </tr>
        `;
    });

    render(html`${tableRows}`, tableBody);

    // --- Attach delegated handlers only once per tbody ---
    if (!tableBody.dataset.collapseHandlers) {
        // Click handler: manually toggle the collapse associated with the clicked .main-row
        tableBody.addEventListener('click', (e) => {
            // allow interactive elements inside the row to behave normally
            if (e.target.closest('a, button, input, select, textarea')) return;

            const row = e.target.closest('.main-row');
            if (!row) return;

            const targetSelector = row.getAttribute('data-bs-target');
            if (!targetSelector) return;

            const collapseEl = document.querySelector(targetSelector);
            if (!collapseEl) return;

            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
            bsCollapse.toggle();
        });

        // Lazy-load details when collapse is about to be shown
        tableBody.addEventListener('show.bs.collapse', async (e) => {
            const collapseDiv = e.target;
            if (!collapseDiv.classList || !collapseDiv.classList.contains('collapse')) return;

            const supplier = collapseDiv.getAttribute('data-supplier');
            if (!supplier) return;
            if (collapseDiv.dataset.loaded) return;

            try {
                const res = await fetch(`actions/getAccountDetails.php?supplier=${encodeURIComponent(supplier)}`);
                const json = await res.json();

                const detailsTable = `
                    <table class="details table table-sm mb-0">
                        <thead>
                            <tr><th>Date</th><th>Expense No</th><th>Total</th><th>Age Bucket</th></tr>
                        </thead>
                        <tbody>
                            ${json.data.map(d => `
                                <tr>
                                    <td class="text-dark text-muted">${d.created_at}</td>
                                    <td>${d.expense_no}</td>
                                    <td class="text-danger">KSH&nbsp;${d.total}</td>
                                    <td>${d.age_bucket}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
                collapseDiv.innerHTML = detailsTable;
                collapseDiv.dataset.loaded = "true";
            } catch (err) {
                collapseDiv.innerHTML = `<div class="text-danger p-2">Failed to load details</div>`;
            }
        });

        // Update arrow and aria-expanded when shown/hidden
        tableBody.addEventListener('shown.bs.collapse', (e) => {
            const toggleRow = tableBody.querySelector(`[data-bs-target="#${e.target.id}"]`);
            if (toggleRow) {
                const span = toggleRow.querySelector('span');
                if (span) span.textContent = '▾';
                toggleRow.setAttribute('aria-expanded', 'true');
            }
        });
        tableBody.addEventListener('hidden.bs.collapse', (e) => {
            const toggleRow = tableBody.querySelector(`[data-bs-target="#${e.target.id}"]`);
            if (toggleRow) {
                const span = toggleRow.querySelector('span');
                if (span) span.textContent = '▸';
                toggleRow.setAttribute('aria-expanded', 'false');
            }
        });

        tableBody.dataset.collapseHandlers = 'true';
    }
}





