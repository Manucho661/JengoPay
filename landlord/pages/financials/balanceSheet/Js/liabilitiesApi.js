import { attachCollapseHandler } from "./api.js";

export async function getNonCurrentLiabilities() {
    try {
        const response = await fetch("actions/getLiabilitiess.php");

        if (!response.ok) {
            console.log("Server couldn't be reached");
            return; // stop execution if response is bad
        }

        const data = await response.json();
        console.log(data);
        addTbodyNonCurrentLiabilities(data.nonCurrentLiabilities, data.totalNonCurrent);
        return data; // good practice to return the result
    } catch (err) {
        console.log('Runtime error encountered:', err);
    }
}

export function addTbodyNonCurrentLiabilities(liabilities, total) {
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
    headerCell.textContent = "NonCurrent Liabilities";
    headerRow.appendChild(headerCell);
    newTbody.appendChild(headerRow);

    // Add rows for each liability
    liabilities.forEach((liability, index) => {
        const collapseId = `collapse-${index}`;

        // Create the main row
        const row = document.createElement("tr");
        row.classList.add("main-row");
        row.style.cursor = "pointer"; // Make cursor a pointer on hover
        row.setAttribute("data-bs-target", `#${collapseId}`);
        row.setAttribute("aria-expanded", "false");
        row.setAttribute("aria-controls", collapseId);

        const nameCell = document.createElement("td");
        nameCell.innerHTML = `<span class="text-warning" style="font-size: 20px;">▸</span> ${liability.name}`;
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

    // Add the header row for the liabilities section
    const headerRow = document.createElement("tr");
    const headerCell = document.createElement("td");
    headerCell.colSpan = 2;
    headerCell.classList.add("section-header");
    headerCell.textContent = "Current Liabilities";
    headerRow.appendChild(headerCell);
    newTbody.appendChild(headerRow);

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
        attachCollapseHandler(row, collapseDiv, currentLiability.account_id);
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
