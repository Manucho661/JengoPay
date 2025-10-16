import { attachCollapseHandler } from "./AssetsApi.js";
import { attachCollapseHandlerAccPayables } from "./AssetsApi.js";

let total_liabilities;
export { total_liabilities };

export async function getNonCurrentLiabilities() {
    try {
        const response = await fetch("actions/getLiabilitiess.php");

        if (!response.ok) {
            console.log("Server couldn't be reached");
            return; // stop execution if response is bad
        }

        const data = await response.json();
        console.log(data);
        addTbodyNonCurrentLiabilities(data.nonCurrentLiabilities, data.totalNonCurrent, data.totalLiabilities);

        total_liabilities = data.totalLiabilities;
        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}

export function addTbodyNonCurrentLiabilities(nonCrtliabilities, total, totalLiabilities) {
    const table = document.getElementById("myTable");

    if (!table) {
        console.log("Table not found");
        return;
    }

    // Create a new tbody
    const newTbody = document.createElement("tbody");


    // --- Sub-header row ("liabilities") ---
    const subHeaderRow = document.createElement("tr");
    const subHeaderCell = document.createElement("td");
    subHeaderCell.colSpan = 2;
    subHeaderCell.textContent = "Non-Current liabilties";
    subHeaderCell.classList.add("section-header");
    subHeaderRow.appendChild(subHeaderCell);
    newTbody.appendChild(subHeaderRow);
    // Add rows for each liability
    nonCrtliabilities.forEach((liability, index) => {
        const collapseId = `collapse-${index}`;

        // Create the main row
        const row = document.createElement("tr");
        row.classList.add("main-row");
        row.style.cursor = "pointer"; // Make cursor a pointer on hover
        row.setAttribute("data-bs-target", `#${collapseId}`);
        row.setAttribute("aria-expanded", "false");
        row.setAttribute("aria-controls", collapseId);

        // Name cell
        const nameCell = document.createElement("td");
        nameCell.innerHTML = `<span class="text-warning" style="font-size: 20px;">▸</span> ${liability.liability_name}`;
        row.appendChild(nameCell);

        // Amount cell with a div inside
        const amountCell = document.createElement("td");
        amountCell.classList.add("amount-cell"); // class for td styling

        const amountDiv = document.createElement("div");
        amountDiv.classList.add("amount-text"); // class for internal div styling

        // Format liability amount
        let amount = Number(liability.amount) || 0;
        let formattedAmount = amount.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Wrap negative numbers in brackets
        if (amount < 0) {
            formattedAmount = `(${formattedAmount.replace('-', '')})`;
            amountDiv.classList.add("text-danger"); // red for negative
        } else {
            amountDiv.classList.add("text-dark"); // dark for positive
        }

        amountDiv.textContent = formattedAmount;
        amountCell.appendChild(amountDiv);
        row.appendChild(amountCell);

        newTbody.appendChild(row);


        // Add the collapsible row (hidden by default)
        const collapseRow = document.createElement("tr");
        const collapseCell = document.createElement("td");
        collapseCell.colSpan = 2;
        const collapseDiv = document.createElement("div");
        collapseDiv.id = collapseId;
        collapseDiv.classList.add("collapse");
        collapseDiv.setAttribute("account_id", liability.account_id);
        collapseCell.appendChild(collapseDiv);
        collapseRow.appendChild(collapseCell);
        newTbody.appendChild(collapseRow);

        // Use attachCollapseHandler to handle click and collapse for this row
        attachCollapseHandler(row, collapseDiv, liability.account_id);
    });

    // Add the total row for non-current liabilities
    const totalRow = document.createElement("tr");
    totalRow.classList.add("total-row");

    // Label cell
    const totalLabelCell = document.createElement("td");
    totalLabelCell.textContent = "Total";
    totalRow.appendChild(totalLabelCell);

    // Value cell with a div inside
    const totalValueCell = document.createElement("td");
    totalValueCell.classList.add("amount-cell"); // class for td styling

    const totalDiv = document.createElement("div");
    totalDiv.classList.add("amount-text"); // class for internal div styling

    // Format total with commas and 2 decimals
    let nonCrtamount = Number(total) || 0;
    let nonCrtformattedTotal = nonCrtamount.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Wrap negative numbers in brackets
    if (nonCrtamount < 0) {
        nonCrtformattedTotal = `(${nonCrtformattedTotal.replace('-', '')})`;
        totalDiv.classList.add("text-danger"); // red for negative
    } else {
        totalDiv.classList.add("text-dark"); // dark for positive
    }

    totalDiv.textContent = nonCrtformattedTotal;
    totalValueCell.appendChild(totalDiv);
    totalRow.appendChild(totalValueCell);

    newTbody.appendChild(totalRow);

    // Add the total liabilities row
    const totalLiabilitiesRow = document.createElement("tr");
    totalLiabilitiesRow.classList.add("totalLiabilities-row");

    // Label cell
    const totalLiabilitiesLabelCell = document.createElement("td");
    totalLiabilitiesLabelCell.textContent = "Total Liabilities";
    totalLiabilitiesRow.appendChild(totalLiabilitiesLabelCell);

    // Value cell with a div inside
    const totalLiabilitiesValueCell = document.createElement("td");
    totalLiabilitiesValueCell.classList.add("amount-cell"); // class for td styling

    const totalLiabilitiesDiv = document.createElement("div");
    totalLiabilitiesDiv.classList.add("amount-text"); // class for internal div styling

    // Format total liabilities with commas and 2 decimals
    let amount = Number(totalLiabilities) || 0;
    let formattedTotal = amount.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Wrap negative numbers in brackets
    if (amount < 0) {
        formattedTotal = `(${formattedTotal.replace('-', '')})`;
        totalLiabilitiesDiv.classList.add("text-danger"); // red for negative
    } else {
        totalLiabilitiesDiv.classList.add("text-dark"); // dark for positive
    }

    totalLiabilitiesDiv.textContent = formattedTotal;
    totalLiabilitiesValueCell.appendChild(totalLiabilitiesDiv);
    totalLiabilitiesRow.appendChild(totalLiabilitiesValueCell);

    newTbody.appendChild(totalLiabilitiesRow); // Append row to tbody


    // Append the new tbody to the table
    table.appendChild(newTbody);
}


// Handle current liabilities
export async function getCurrentLiabilities() {
    try {
        const response = await fetch("actions/getLiabilitiess.php");

        if (!response.ok) {
            console.log("Server couldn't be reached");
            return; // stop execution if response is bad
        }

        const data = await response.json();
        console.log(data);
        addTbodyCurrentLiabilities(data.currentLiabilities, data.totalCurrent);
        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}

export function addTbodyCurrentLiabilities(currentLiabilities, total) {
    const table = document.getElementById("myTable");

    if (!table) {
        console.log("Table not found");
        return;
    }

    // Create a new tbody
    const newTbody = document.createElement("tbody");

    // --- First main header row ("Liabilities") ---
    const mainHeaderRow = document.createElement("tr");
    const mainHeaderCell = document.createElement("td");
    mainHeaderCell.colSpan = 2;
    mainHeaderCell.textContent = "Liabilities";
    mainHeaderCell.classList.add("main-section-header");
    mainHeaderRow.appendChild(mainHeaderCell);
    newTbody.appendChild(mainHeaderRow);

    // --- Sub-header row ("Non-Current Liabilities") ---
    const subHeaderRow = document.createElement("tr");
    const subHeaderCell = document.createElement("td");
    subHeaderCell.colSpan = 2;
    subHeaderCell.textContent = "Current Liabilities";
    subHeaderCell.classList.add("section-header");
    subHeaderRow.appendChild(subHeaderCell);
    newTbody.appendChild(subHeaderRow);

    // Loop through the liabilities and create rows
    currentLiabilities.forEach((currentLiability, index) => {
        const collapseId = `collapse-currentliability-${index}`;

        // Create the main row (clickable)
        const row = document.createElement("tr");
        row.classList.add("main-row");
        row.style.cursor = "pointer"; // Make cursor a pointer on hover
        row.setAttribute("data-bs-target", `#${collapseId}`);
        row.setAttribute("aria-expanded", "false");
        row.setAttribute("aria-controls", collapseId);

        // Name cell
        const nameCell = document.createElement("td");
        nameCell.innerHTML = `<span class="text-warning" style="font-size: 20px;">▸</span> ${currentLiability.liability_name}`;
        row.appendChild(nameCell);

        // Amount cell with a div inside
        const amountCell = document.createElement("td");
        amountCell.classList.add("amount-cell"); // class for td styling

        const amountDiv = document.createElement("div");
        amountDiv.classList.add("amount-text"); // class for internal div styling

        // Format current liability amount
        let amount = Number(currentLiability.amount) || 0;
        let formattedAmount = amount.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Wrap negative numbers in brackets
        if (amount < 0) {
            formattedAmount = `(${formattedAmount.replace('-', '')})`;
            amountDiv.classList.add("text-danger"); // red for negative
        } else {
            amountDiv.classList.add("text-dark"); // dark for positive
        }

        amountDiv.textContent = formattedAmount;
        amountCell.appendChild(amountDiv);
        row.appendChild(amountCell);

        newTbody.appendChild(row);


        // Add the collapsible row (hidden by default)
        const collapseRow = document.createElement("tr");
        const collapseCell = document.createElement("td");
        collapseCell.colSpan = 2;
        const collapseDiv = document.createElement("div");
        collapseDiv.id = collapseId;
        collapseDiv.classList.add("collapse");
        collapseDiv.setAttribute("data-section", "liability");
        collapseDiv.setAttribute("account_id", currentLiability.account_id);
        collapseCell.appendChild(collapseDiv);
        collapseRow.appendChild(collapseCell);
        newTbody.appendChild(collapseRow);

        // Attach the collapse handler for each row
        // Attach the appropriate collapse handler based on account_id
        if (currentLiability.account_id === 300) {
            attachCollapseHandlerAccPayables(row, collapseDiv, currentLiability.account_id);
        } else {
            attachCollapseHandler(row, collapseDiv, currentLiability.account_id);
        }
    });

    // Add the total row for liabilities
    const totalRow = document.createElement("tr");
    totalRow.classList.add("total-row");

    // Label cell
    const totalLabelCell = document.createElement("td");
    totalLabelCell.textContent = "Total";
    totalRow.appendChild(totalLabelCell);

    // Value cell with a div inside
    const totalValueCell = document.createElement("td");
    totalValueCell.classList.add("amount-cell"); // class for td styling

    const totalDiv = document.createElement("div");
    totalDiv.classList.add("amount-text"); // class for internal div styling

    // Format total with commas and 2 decimals
    let amount = Number(total) || 0;
    let formattedTotal = amount.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Wrap negative numbers in brackets
    if (amount < 0) {
        formattedTotal = `(${formattedTotal.replace('-', '')})`;
        totalDiv.classList.add("text-danger"); // red for negative
    } else {
        totalDiv.classList.add("text-dark"); // dark for positive
    }

    totalDiv.textContent = formattedTotal;
    totalValueCell.appendChild(totalDiv);
    totalRow.appendChild(totalValueCell);

    newTbody.appendChild(totalRow);

    // Append the new tbody to the table
    table.appendChild(newTbody);
}
