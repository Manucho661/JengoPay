import { total_liabilities } from "./liabilitiesApi.js"

export async function getEquity() {
    try {
        const response = await fetch("actions/getEquity.php");

        if (!response.ok) {
            console.log("Server couldn't be reached");
            return; // stop execution if response is bad
        }

        const data = await response.json();
        // console.log(data);

        addTbodyOwnersCapital(data.owners_capital);
        addTbodyRetainedEarnings(data.retainedEarnings, data.totalEquity);

        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}

export function addTbodyRetainedEarnings(retainedEarnings, totalEquity) {
    const table = document.getElementById("myTable");
    const retainedEarningsAccountCode = 410;
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

    // Use formatted numbers inside divs
    newTbody.innerHTML = `
        <tr class="main-row" style="cursor: pointer;">
            <td> Retained Earnings &nbsp;&nbsp;<a href="/jengopay/landlord/pages/financials/generalledger/general_ledger.php?account_id=${retainedEarningsAccountCode}" style="text-decoration: none;"> <i class="fa fa-ellipsis-v fs-8 more text-warning"></i> </a></td>
            <td class="amount-cell"><div class="amount-text">  ${formatAmount(retainedEarnings)} </div></td>
        </tr>
        <tr>
            <td class="totalEquityCell">Total Equity </i></td>
            <td class="amount-cell"><div class="amount-text">${formatAmount(totalEquity)}</div></td>
        </tr>
        <tr class="equityAndLiabilities">
            <td class="totalLiabilitiesEquityCell">Total Liabilities and Equity</td>
            <td class="amount-cell"><div class="amount-text equityAndLiabilities bg-white border-0">${formatAmount(Number(totalEquity) + Number(total_liabilities))}</div></td>
        </tr>
    `;

    table.appendChild(newTbody);
}

export function addTbodyOwnersCapital(ownersCapital) {
    const owners_capitalAccount_code=400;
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


    // Main structure for the Owner's Capital section
    newTbody.innerHTML = `
        <tr><th colspan="2">Equity</th></tr>
        <tr class="main-row" style="cursor:pointer;">
            <td>
                 Owner's Capital &nbsp;&nbsp; <a href="/jengopay/landlord/pages/financials/generalledger/general_ledger.php?account_id=${owners_capitalAccount_code}" style="text-decoration: none;"> <i class="fa fa-ellipsis-v fs-8 more text-warning"></i> </a>
            </td>
            <td class="amount-cell">
                <div class="amount-text">${formatAmount(ownersCapital)} </div>
            </td>
        </tr>
    `;

    // Append to table
    table.appendChild(newTbody);
}


