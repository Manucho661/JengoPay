export async function getCurrentAssets() {
    try {
        const response = await fetch("actions/getAssetss.php");

        if (!response.ok) {
            console.log("Server couldn't be reached");
            return; // stop execution if response is bad
        }

        const data = await response.json();
        console.log(data);
        addTbody(data.currentAssets, data.totalCurrent);
        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}

export function addTbody(assets, total) {
    const table = document.getElementById("myTable");

    if (!table) {
        console.log("Table not found");
        return;
    }

    // Create a new tbody
    const newTbody = document.createElement("tbody");

    // Add the header row
    const headerRow = document.createElement("tr");
    const headerCell = document.createElement("td");
    headerCell.colSpan = 2; // span across two columns
    headerCell.classList.add("section-header"); // ✅ different style if you want
    headerCell.textContent = "Current Assets";
    headerRow.appendChild(headerCell);
    newTbody.appendChild(headerRow);

    // Add rows for each asset
    assets.forEach((asset, index) => {
        const collapseId = `collapse-${index}`;

        // Create the main row
        const row = document.createElement("tr");
        row.classList.add("main-row");
        row.style.cursor = "pointer"; // Make cursor a pointer on hover
        row.setAttribute("data-bs-target", `#${collapseId}`);
        row.setAttribute("aria-expanded", "false");
        row.setAttribute("aria-controls", collapseId);

        const nameCell = document.createElement("td");
        nameCell.innerHTML = `<span class="text-warning" style="font-size: 20px;">▸</span> ${asset.name}`;
        row.appendChild(nameCell);

        const amountCell = document.createElement("td");
        amountCell.textContent = asset.amount;
        row.appendChild(amountCell);

        newTbody.appendChild(row);

        // Add the collapsible row (hidden by default)
        const collapseRow = document.createElement("tr");
        const collapseCell = document.createElement("td");
        collapseCell.colSpan = 2;
        const collapseDiv = document.createElement("div");
        collapseDiv.id = collapseId;
        collapseDiv.classList.add("collapse");
        collapseDiv.setAttribute("account_id", asset.account_id);
        collapseCell.appendChild(collapseDiv);
        collapseRow.appendChild(collapseCell);
        newTbody.appendChild(collapseRow);
    });

    // Add the total row at the end with custom styling
    const totalRow = document.createElement("tr");
    totalRow.classList.add("total-row");

    const totalLabelCell = document.createElement("td");
    totalLabelCell.textContent = "Total";
    totalRow.appendChild(totalLabelCell);

    const totalValueCell = document.createElement("td");
    totalValueCell.textContent = total;
    totalRow.appendChild(totalValueCell);

    newTbody.appendChild(totalRow);

    // Append the new tbody to the table
    table.appendChild(newTbody);

    // --- Attach event handlers to the table body only once ---
    if (!table.dataset.collapseHandlers) {
        table.addEventListener("click", (e) => {
            // Allow interactive elements to behave normally
            if (e.target.closest('a, button, input, select, textarea')) return;

            const row = e.target.closest('.main-row');
            if (!row) return;

            const targetSelector = row.getAttribute('data-bs-target');
            if (!targetSelector) return;

            const collapseEl = document.querySelector(targetSelector);
            if (!collapseEl) return;

            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
            bsCollapse.toggle();

            // Update the arrow (▸ / ▾)
            const span = row.querySelector('span');
            if (span) {
                span.textContent = collapseEl.classList.contains('show') ? '▾' : '▸';
            }

            // Update aria-expanded attribute
            row.setAttribute('aria-expanded', collapseEl.classList.contains('show').toString());
        });

        // Lazy-load details when the collapse is about to be shown
        table.addEventListener('show.bs.collapse', async (e) => {
            const collapseDiv = e.target;
            if (!collapseDiv.classList || !collapseDiv.classList.contains('collapse')) return;

            const account_id = collapseDiv.getAttribute('account_id');
            if (!account_id) return;
            if (collapseDiv.dataset.loaded) return;

            try {
                const res = await fetch(`actions/getAccountDetails.php?account=${encodeURIComponent(account_id)}`);
                const json = await res.json();
                console.log(json.data);

                const detailsTable = `
                    <table class="details table table-sm mb-0">
                        <tbody>
                                ${json.data.map(d => {
                    // Check the value of source_table and replace accordingly
                    let sourceTableText = d.source_table;
                    if (sourceTableText === 'expense_payments') {
                        sourceTableText = 'Expense Payment';
                    } else if (sourceTableText === 'invoice_payment') {
                        sourceTableText = 'Invoice Payment';
                    }

                    // Calculate total by comparing debit and credit, and apply negative for credit
                    let total = 0;
                    if (d.debit && d.credit) {
                        total = d.debit - d.credit;  // Debit - Credit
                    } else if (d.debit) {
                        total = d.debit;  // If only debit exists, display debit
                    } else if (d.credit) {
                        total = -d.credit;  // If only credit exists, display negative credit
                    }

                    return `
                            <tr>
                                <td class="text-dark text-muted">${d.created_at}</td>
                                <td>${sourceTableText}</td>
                                <td class="text-danger">KSH&nbsp;${total.toFixed(2)}</td>  <!-- Display the total with 2 decimal places -->
                            </tr>
                        `;
                }).join('')}
                </tbody>

                    </table>
                `;
                collapseDiv.innerHTML = detailsTable;
                collapseDiv.dataset.loaded = "true";
            } catch (err) {
                collapseDiv.innerHTML = `<div class="text-danger p-2">Failed to load details</div>`;
            }
        });

        table.dataset.collapseHandlers = 'true';
    }
}