export async function getEquity() {
    try {
        const response = await fetch("actions/getEquityy.php");

        if (!response.ok) {
            console.log("Server couldn't be reached");
            return; // stop execution if response is bad
        }

        const data = await response.json();
        // console.log(data);
    
        addTbodyOwnersCapital(data.totalEquity, data.totalExpenses);
        addTbodyRetainedEarnings(data.retainedEarnings, data.totalRevenue, data.totalExpenses);

        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}

export function addTbodyRetainedEarnings(retainedEarnings, totalRevenue, totalExpenses) {
    const table = document.getElementById("myTable");

    if (!table) {
        console.log("Table not found");
        return;
    }

    // Create a new tbody
    const newTbody = document.createElement("tbody");

    // Unique collapse id
    const collapseId = "retainedEarningsDetails";

    // Add rows with proper structure
    newTbody.innerHTML = `
        <tr><th colspan="2">Equity</th></tr>

        <!-- Main row (clickable) -->
        <tr class="main-row" data-bs-target="#${collapseId}" aria-expanded="false" style="cursor: pointer;">
            <td>
                <span style="margin-right:5px;">▸</span> Retained Earnings
            </td>
            <td>${retainedEarnings}</td>
        </tr>

        <!-- Collapsible row -->
        <tr class="collapse" id="${collapseId}">
            <td colspan="2">
                <table class="table table-sm mb-0">
                    <tr>
                        <td>Revenue: ${totalRevenue}</td>
                        <td>Expenses: ${totalExpenses}</td>
                    </tr>
                </table>
            </td>
        </tr>
    `;

    // Append the new tbody to the table
    table.appendChild(newTbody);

    // Attach event handlers once
    if (!table.dataset.collapseHandlers) {
        table.dataset.collapseHandlers = "true";

        table.addEventListener("click", (e) => {
            const row = e.target.closest(".main-row");
            if (!row) return;

            const targetSelector = row.getAttribute("data-bs-target");
            const collapseEl = document.querySelector(targetSelector);
            if (!collapseEl) return;

            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
            bsCollapse.toggle();

            // Update arrow
            const span = row.querySelector("span");
            if (span) {
                span.textContent = collapseEl.classList.contains("show") ? "▾" : "▸";
            }

            // Update aria-expanded
            row.setAttribute("aria-expanded", collapseEl.classList.contains("show").toString());
        });
    }
}

export function addTbodyOwnersCapital(ownersCapital, total) {
    const table = document.getElementById("myTable");
    if (!table) {
        console.log("Table not found");
        return;
    }

    // Create a new tbody
    const newTbody = document.createElement("tbody");

    // Unique collapse id
    const collapseId = "ownersCapitalDetails";

    // Main structure
    newTbody.innerHTML = `
        <tr><th colspan="2">Equity</th></tr>

        <!-- Main row (clickable) -->
        <tr class="main-row" data-bs-target="#${collapseId}" aria-expanded="false" style="cursor:pointer;">
            <td>
                <span style="margin-right:5px;">▸</span> Owner's Capital
            </td>
            <td>${ownersCapital}</td>
        </tr>

        <!-- Collapsible row -->
        <tr class="collapse" id="${collapseId}" account_id="owners_capital">
            <td colspan="2" class="p-0">
                <div class="p-2 text-muted">Click to load details...</div>
            </td>
        </tr>

        <!-- Total row -->
        <tr class="total-row fw-bold">
            <td>Total</td>
            <td>${total}</td>
        </tr>
    `;

    // Append to table
    table.appendChild(newTbody);

    // Attach handlers once
    if (!table.dataset.collapseHandlers) {
        table.dataset.collapseHandlers = "true";

        // Toggle on click
        table.addEventListener("click", (e) => {
            const row = e.target.closest(".main-row");
            if (!row) return;

            const targetSelector = row.getAttribute("data-bs-target");
            const collapseEl = document.querySelector(targetSelector);
            if (!collapseEl) return;

            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
            bsCollapse.toggle();

            // Update arrow
            const span = row.querySelector("span");
            if (span) {
                span.textContent = collapseEl.classList.contains("show") ? "▾" : "▸";
            }

            row.setAttribute("aria-expanded", collapseEl.classList.contains("show").toString());
        });

        // Lazy-load details when expanding
        table.addEventListener("show.bs.collapse", async (e) => {
            const collapseRow = e.target; // the <tr class="collapse">
            if (!collapseRow.classList.contains("collapse")) return;

            const account_id = collapseRow.getAttribute("account_id");
            if (!account_id || collapseRow.dataset.loaded) return;

            const td = collapseRow.querySelector("td"); // the container cell

            try {
                const res = await fetch(`actions/getOwnersCapitalDetails.php?account=${encodeURIComponent(account_id)}`);
                const json = await res.json();

                const detailsTable = `
                    <table class="details table table-sm mb-0">
                        <tbody>
                            ${json.data.map(d => {
                    // Replace source table names
                    let sourceTableText = d.source_table;
                    if (sourceTableText === "expense_payments") sourceTableText = "Expense Payment";
                    else if (sourceTableText === "invoice_payment") sourceTableText = "Invoice Payment";

                    // Calculate total
                    let total = 0;
                    if (d.debit && d.credit) total = d.debit - d.credit;
                    else if (d.debit) total = d.debit;
                    else if (d.credit) total = -d.credit;

                    return `
                                    <tr>
                                        <td class="text-muted">${d.created_at}</td>
                                        <td>${sourceTableText}</td>
                                        <td class="text-danger">KSH&nbsp;${total.toFixed(2)}</td>
                                    </tr>
                                `;
                }).join("")}
                        </tbody>
                    </table>
                `;

                td.innerHTML = detailsTable;
                collapseRow.dataset.loaded = "true";
            } catch (err) {
                td.innerHTML = `<div class="text-danger p-2">Failed to load details</div>`;
            }
        });
    }
}
