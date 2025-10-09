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

export function addTbodyNonCurrentLiabilities(nonCrtliabilities, total, $totalLiabilities) {
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

        const nameCell = document.createElement("td");
        nameCell.innerHTML = `<span class="text-warning" style="font-size: 20px;">▸</span> ${liability.liability_name}`;
        row.appendChild(nameCell);

        const amountCell = document.createElement("td");
        amountCell.textContent = liability.amount;
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

    // Add the total row for non-current-liabilities
    const totalRow = document.createElement("tr");
    totalRow.classList.add("total-row");

    const totalLabelCell = document.createElement("td");
    totalLabelCell.textContent = "Total";
    totalRow.appendChild(totalLabelCell);

    const totalValueCell = document.createElement("td");
    totalValueCell.textContent = total;
    totalRow.appendChild(totalValueCell);

    newTbody.appendChild(totalRow);

    // Add the total liabilities row
    const totalLiabilitiesRow = document.createElement("tr");
    totalLiabilitiesRow.classList.add("totalLiabilities-row");

    const totalLiabilitiesLabelCell = document.createElement("td");
    totalLiabilitiesLabelCell.textContent = "Total Liabilities"; // Corrected cell
    totalLiabilitiesRow.appendChild(totalLiabilitiesLabelCell);

    const totalLiabilitiesValueCell = document.createElement("td");
    totalLiabilitiesValueCell.textContent = $totalLiabilities; // Corrected cell
    totalLiabilitiesRow.appendChild(totalLiabilitiesValueCell);

    newTbody.appendChild(totalLiabilitiesRow); // Append to the tbody

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

        const nameCell = document.createElement("td");
        nameCell.innerHTML = `<span class="text-warning" style="font-size: 20px;">▸</span> ${currentLiability.liability_name}`;
        row.appendChild(nameCell);

        const amountCell = document.createElement("td");
        amountCell.textContent = currentLiability.amount;
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
