import { html, render } from 'https://unpkg.com/lit@3.1.4/index.js?module';


// Handle none Current assets
export async function getNonCurrentAssets() {
    try {
        const response = await fetch("actions/getAssetss.php");

        if (!response.ok) {
            console.log("Server couldn't be reached");
            return; // stop execution if response is bad
        }

        const data = await response.json();
        console.log(data);
        addTbodyNonCurrentAssets(data.nonCurrentAssets, data.totalNonCurrent);
        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}

export function addTbodyNonCurrentAssets(assets, total) {
    const table = document.getElementById("myTable");

    if (!table) {
        console.log("Table not found");
        return;
    }

    // Create a new tbody
    const newTbody = document.createElement("tbody");

    // --- First main header row ("Assets") ---
    const mainHeaderRow = document.createElement("tr");
    const mainHeaderCell = document.createElement("td");
    mainHeaderCell.colSpan = 2;
    mainHeaderCell.textContent = "Assets";
    mainHeaderCell.classList.add("main-section-header");
    mainHeaderRow.appendChild(mainHeaderCell);
    newTbody.appendChild(mainHeaderRow);

    // --- Sub-header row ("Non-Current Assets") ---
    const subHeaderRow = document.createElement("tr");
    const subHeaderCell = document.createElement("td");
    subHeaderCell.colSpan = 2;
    subHeaderCell.textContent = "Non-Current Assets";
    subHeaderCell.classList.add("section-header");
    subHeaderRow.appendChild(subHeaderCell);
    newTbody.appendChild(subHeaderRow);

    // Add rows for each asset
    assets.forEach((asset, index) => {
        const collapseId = `collapse-nonCurrentAsset${index}`;

        // Create the main row
        const row = document.createElement("tr");
        row.classList.add("main-row");
        row.style.cursor = "pointer";
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
        collapseDiv.setAttribute("data-section", "nonCurrentAssets");
        collapseDiv.classList.add("collapse");
        collapseDiv.setAttribute("account_id", asset.account_id);
        collapseCell.appendChild(collapseDiv);
        collapseRow.appendChild(collapseCell);
        newTbody.appendChild(collapseRow);

        // Attach individual click handler for each block
        attachCollapseHandler(row, collapseDiv, asset.account_id);
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
}

// Function to handle the collapsible behavior for each block
export function attachCollapseHandler(row, collapseDiv, accountId) {
    console.log(accountId);
    // Handle clicks (toggle only)
    row.addEventListener("click", (e) => {
        if (e.target.closest('a, button, input, select, textarea')) return;
        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseDiv, { toggle: false });
        bsCollapse.toggle();
    });

    // Update arrow when collapse finishes expanding
    collapseDiv.addEventListener("shown.bs.collapse", (e) => {
        const span = row.querySelector("span");
        if (span) span.textContent = "▾"; // ▼ down arrow
        row.setAttribute("aria-expanded", "true");
    });

    // Update arrow when collapse finishes collapsing
    collapseDiv.addEventListener("hidden.bs.collapse", (e) => {
        const span = row.querySelector("span");
        if (span) span.textContent = "▸"; // ► right arrow
        row.setAttribute("aria-expanded", "false");
    });

    // Lazy-load details when the collapse is about to be shown
    collapseDiv.addEventListener('show.bs.collapse', async (e) => {
        if (collapseDiv.dataset.loaded) return;

        try {
            const res = await fetch(`actions/getAccountDetails.php?account=${encodeURIComponent(accountId)}`);
            const json = await res.json();

            const detailsTable = `
                <table class="details table table-sm mb-0">
                    <tbody>
                        ${json.data.map(d => {
                let sourceTableText = d.source_table;
                if (sourceTableText === 'expense_payments') {
                    sourceTableText = 'Expense Payment';
                } else if (sourceTableText === 'invoice_payment') {
                    sourceTableText = 'Invoice Payment';
                }

                let total = 0;
                if (d.debit && d.credit) {
                    total = d.debit - d.credit;
                } else if (d.debit) {
                    total = d.debit;
                } else if (d.credit) {
                    total = -d.credit;
                }

                return `
                                <tr>
                                    <td class="text-dark text-muted">${d.created_at}</td>
                                    <td>${sourceTableText}</td>
                                    <td class="text-danger">KSH&nbsp;${total.toFixed(2)}</td>
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
}


export async function getCurrentAssets() {
    try {
        const response = await fetch("actions/getAssetss.php");

        if (!response.ok) {
            console.log("Server couldn't be reached");
            return; // stop execution if response is bad
        }

        const data = await response.json();
        console.log(data);
        addTbodyCurrentAssets(data.currentAssets, data.totalCurrent, data.totalAssets);
        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}

// Main function to add Current Assets section to the table
export function addTbodyCurrentAssets(assets, total, totalAssets) {
    const table = document.getElementById("myTable");

    if (!table) {
        console.log("Table not found");
        return;
    }

    // Create a new tbody
    const newTbody = document.createElement("tbody");

    // Add the header row for Current Assets
    const headerRow = document.createElement("tr");
    const headerCell = document.createElement("td");
    headerCell.colSpan = 2;
    headerCell.classList.add("section-header");
    headerCell.textContent = "Current Assets";
    headerRow.appendChild(headerCell);
    newTbody.appendChild(headerRow);

    // Add rows for each asset
    assets.forEach((asset, index) => {
        const collapseId = `collapse-current-${index}`;

        // Create the main row (clickable)
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

        // Attach the collapse handler for each row
        attachCollapseHandler(row, collapseDiv, asset.account_id);
    });

    // Add the total row for assets
    const totalRow = document.createElement("tr");
    totalRow.classList.add("total-row");

    const totalLabelCell = document.createElement("td");
    totalLabelCell.textContent = "Total";
    totalRow.appendChild(totalLabelCell);

    const totalValueCell = document.createElement("td");
    totalValueCell.textContent = total;
    totalRow.appendChild(totalValueCell);

    newTbody.appendChild(totalRow);

    // Add the total assets row
    const totalAssetsRow = document.createElement("tr");
    totalAssetsRow.classList.add("totalAssets-row");

    const totalAssetsLabelCell = document.createElement("td");
    totalAssetsLabelCell.textContent = "Total Assets"; // Corrected cell
    totalAssetsRow.appendChild(totalAssetsLabelCell);

    const totalAssetsValueCell = document.createElement("td");
    totalAssetsValueCell.textContent = totalAssets; // Corrected cell
    totalAssetsRow.appendChild(totalAssetsValueCell);

    newTbody.appendChild(totalAssetsRow); // Append to the tbody


    // Append the new tbody to the table
    table.appendChild(newTbody);
}
