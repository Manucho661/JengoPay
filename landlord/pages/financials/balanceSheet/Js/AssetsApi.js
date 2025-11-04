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
        nameCell.innerHTML = `${asset.name} &nbsp;&nbsp;<span class="text-warning" style=""><i class="fa fa-ellipsis-v fs-8"></i></span>`;
        row.appendChild(nameCell);

        const amountCell = document.createElement("td");

        let amount = Number(asset.amount) || 0;
        let parts = asset.amount.toString().split(".");
        let integerPart = Number(parts[0]).toLocaleString(); // adds commas
        let decimalPart = parts[1]; // already 2 decimals
        let formattedAmount = `${integerPart}.${decimalPart}`;

        // If negative, wrap in brackets
        if (amount < 0) {
            formattedAmount = `(${formattedAmount.replace('-', '')})`;
        }

        // Create a div inside the td
        const amountDiv = document.createElement("div");
        amountDiv.classList.add("amount-text"); // class for styling
        amountDiv.textContent = formattedAmount;

        // Optional: color based on negative or positive
        if (amount < 0) {
            amountDiv.classList.add("text-danger");
        } else {
            amountDiv.classList.add("text-success");
        }

        // Append the div to the cell
        amountCell.appendChild(amountDiv);

        // Add a class to the td for cell styling
        amountCell.classList.add("amount-cell");

        row.appendChild(amountCell);
        newTbody.appendChild(row);

    });

    // Add the total row at the end with custom styling
    const totalRow = document.createElement("tr");
    totalRow.classList.add("total-row");

    // Label cell
    const totalLabelCell = document.createElement("td");
    totalLabelCell.textContent = "Total";
    totalRow.appendChild(totalLabelCell);

    // Value cell with a div inside
    const totalValueCell = document.createElement("td");
    totalValueCell.classList.add("amount-cell"); // class for cell styling

    const totalDiv = document.createElement("div");
    totalDiv.classList.add("amount-text"); // class for internal div styling

    // Format total with commas and brackets if negative
    let formattedTotal = Number(total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (Number(total) < 0) {
        formattedTotal = `(${formattedTotal.replace('-', '')})`;
        totalDiv.classList.add("text-danger"); // red for negative
    } else {
        totalDiv.classList.add("text-dark"); // green for positive
    }

    totalDiv.textContent = formattedTotal;
    totalValueCell.appendChild(totalDiv);
    totalRow.appendChild(totalValueCell);

    newTbody.appendChild(totalRow);


    // Append the new tbody to the table
    table.appendChild(newTbody);
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
export function addTbodyCurrentAssets(assets, totalCurrentAssets, totalAssets) {
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
        nameCell.innerHTML = `${asset.name} &nbsp;&nbsp;<span class="text-warning" style=""><i class="fa fa-ellipsis-v fs-8"></i></span>`;
        row.appendChild(nameCell);

        const amountCell = document.createElement("td");

        let amount = Number(asset.amount) || 0;
        let parts = asset.amount.toString().split(".");
        let integerPart = Number(parts[0]).toLocaleString(); // adds commas
        let decimalPart = parts[1]; // already 2 decimals
        let formattedAmount = `${integerPart}.${decimalPart}`;

        // If negative, wrap in brackets
        if (amount < 0) {
            formattedAmount = `(${formattedAmount.replace('-', '')})`;
        }

        // Create a div inside the td
        const amountDiv = document.createElement("div");
        amountDiv.classList.add("amount-text"); // class for styling
        amountDiv.textContent = formattedAmount;

        // Optional: color based on negative or positive
        if (amount < 0) {
            amountDiv.classList.add("text-danger");
        } else {
            amountDiv.classList.add("text-success");
        }

        // Append the div to the cell
        amountCell.appendChild(amountDiv);

        // Add a class to the td for cell styling
        amountCell.classList.add("amount-cell");

        row.appendChild(amountCell);
        newTbody.appendChild(row);

    });

    // Add the total row for current assets
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
    let amount = Number(totalCurrentAssets) || 0;
    let formattedTotalCurrentAssets = amount.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Wrap negative numbers in brackets
    if (amount < 0) {
        formattedTotalCurrentAssets = `(${formattedTotalCurrentAssets.replace('-', '')})`;
        totalDiv.classList.add("text-danger"); // red for negative
    } else {
        totalDiv.classList.add("text-dark"); // dark for positive
    }

    totalDiv.textContent = formattedTotalCurrentAssets;
    totalValueCell.appendChild(totalDiv);
    totalRow.appendChild(totalValueCell);

    newTbody.appendChild(totalRow);


    // Add the total assets row (ALL)
    // Create Total Assets row
    const totalAssetsRow = document.createElement("tr");
    totalAssetsRow.classList.add("totalAssets-row");

    // Label cell
    const totalAssetsLabelCell = document.createElement("td");
    totalAssetsLabelCell.textContent = "Total Assets";
    totalAssetsRow.appendChild(totalAssetsLabelCell);

    // Value cell with a div inside
    const totalAssetsValueCell = document.createElement("td");
    totalAssetsValueCell.classList.add("amount-cell"); // class for td styling

    const totalAssetsDiv = document.createElement("div");
    totalAssetsDiv.classList.add("amount-text"); // class for internal div styling

    // Format totalAssets with commas and brackets if negative
    let formattedTotalAssets = Number(totalAssets).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (Number(totalAssets) < 0) {
        formattedTotalAssets = `(${formattedTotalAssets.replace('-', '')})`;
        totalAssetsDiv.classList.add("text-danger"); // red for negative
    } else {
        totalAssetsDiv.classList.add("text-dark"); // green for positive
    }

    totalAssetsDiv.textContent = formattedTotalAssets;
    console.log(formattedTotalAssets);
    totalAssetsValueCell.appendChild(totalAssetsDiv);
    totalAssetsRow.appendChild(totalAssetsValueCell);

    newTbody.appendChild(totalAssetsRow); // Append row to tbody



    // Append the new tbody to the table
    table.appendChild(newTbody);
}
