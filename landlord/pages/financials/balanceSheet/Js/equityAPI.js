import {total_liabilities} from "./liabilitiesApi.js"
import { attachCollapseHandler } from "./AssetsApi.js"

export async function getEquity() {
    try {
        const response = await fetch("actions/getEquityy.php");

        if (!response.ok) {
            console.log("Server couldn't be reached");
            return; // stop execution if response is bad
        }

        const data = await response.json();
        // console.log(data);
    
        addTbodyOwnersCapital(data.owners_capital);
        addTbodyRetainedEarnings(data.retainedEarnings, data.revenue, data.expenses, data.totalEquity);

        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}

export function addTbodyRetainedEarnings(retainedEarnings, totalRevenue, totalExpenses, totalEquity) {
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
        <!-- Main row (clickable) -->
        <tr class="main-row" data-bs-target="#${collapseId}" aria-expanded="false" style="cursor: pointer;">
            <td>
                <span class="text-warning" style="font-size: 20px;">▸</span> Retained Earnings
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
        <tr>
            <td class="totalEquityCell">
                 Total Equity
            </td>
            <td>${totalEquity}</td>
        </tr>
       <tr>
        <td class="totalLiabilitiesEquityCell">
            Total Liabilities and Equity
        </td>
        <td class="totalLiabilitiesEquityCell">${totalEquity + total_liabilities}</td>
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

export function addTbodyOwnersCapital(ownersCapital) {
    const table = document.getElementById("myTable");
    if (!table) {
        console.log("Table not found");
        return;
    }

    // Create a new tbody
    const newTbody = document.createElement("tbody");

    // Unique collapse id for the Owner's Capital section
    const collapseId = "ownersCapitalDetails";

    // Main structure for the Owner's Capital section
    newTbody.innerHTML = `
        <tr><th colspan="2">Equity</th></tr>

        <!-- Main row (clickable) -->
        <tr class="main-row" data-bs-target="#${collapseId}" aria-expanded="false" style="cursor:pointer;">
            <td>
                <span class="text-warning" style="font-size: 20px;">▸</span> Owner's Capital
            </td>
            <td>${ownersCapital}</td>
        </tr>

        <!-- Collapsible row -->
        <tr class="collapse" id="${collapseId}" account_id="owners_capital">
            <td colspan="2" class="p-0">
                <div class="p-2 text-muted">Click to load details...</div>
            </td>
        </tr>
    `;

    // Append to table
    table.appendChild(newTbody);

    // Use the attachCollapseHandler function to handle the collapse behavior for this section
    attachCollapseHandler(newTbody.querySelector(".main-row"), newTbody.querySelector(`#${collapseId}`), "owners_capital");
}


