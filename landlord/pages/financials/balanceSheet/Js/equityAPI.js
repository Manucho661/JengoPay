import { total_liabilities } from "./liabilitiesApi.js"
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
    if (!table) return;

    // Function to format numbers with commas and brackets for negatives
    function formatAmount(value) {
        const num = Number(value) || 0;
        let formatted = num.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        if (num < 0) formatted = `(${formatted.replace('-', '')})`; // For negative numbers
        return formatted;
    }

    const newTbody = document.createElement("tbody");
    const collapseId = "retainedEarningsDetails";

    // Use formatted numbers inside divs
    newTbody.innerHTML = `
        <tr class="main-row" data-bs-target="#${collapseId}" aria-expanded="false" style="cursor: pointer;">
            <td><span class="text-warning" style="font-size: 20px;">▸</span> Retained Earnings</td>
            <td class="amount-cell"><div class="amount-text">${formatAmount(retainedEarnings)}</div></td>
        </tr>
        <tr class="collapse" id="${collapseId}">
            <td colspan="2">
                <table class="table table-sm mb-0">
                    <tr>
                        <td>Revenue: <div class="amount-cell"><div class="amount-text">${formatAmount(totalRevenue)}</div></div></td>
                        <td>Expenses: <div class="amount-cell"><div class="amount-text">${formatAmount(totalExpenses)}</div></div></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="totalEquityCell">Total Equity</td>
            <td class="amount-cell"><div class="amount-text">${formatAmount(totalEquity)}</div></td>
        </tr>
        <tr class="equityAndLiabilities">
            <td class="totalLiabilitiesEquityCell">Total Liabilities and Equity</td>
            <td class="amount-cell"><div class="amount-text equityAndLiabilities bg-white border-0">${formatAmount(Number(totalEquity) + Number(total_liabilities))}</div></td>
        </tr>
    `;

    table.appendChild(newTbody);

    // Collapse toggle handler
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
            const span = row.querySelector("span");
            if (span) span.textContent = collapseEl.classList.contains("show") ? "▾" : "▸";
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

    // Function to format numbers with commas and brackets for negatives
    function formatAmount(value) {
        const num = Number(value) || 0;
        let formatted = num.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        if (num < 0) formatted = `(${formatted.replace('-', '')})`; // For negative numbers
        return formatted;
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
            <td class="amount-cell">
                <div class="amount-text">${formatAmount(ownersCapital)}</div>
            </td>
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


