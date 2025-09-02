
export async function downloadBS() {
    const {
        jsPDF
    } = window.jspdf;
    const doc = new jsPDF();

    if (typeof doc.autoTable !== 'function') {
        console.error("Error: autoTable plugin is not properly loaded.");
        alert("Error: autoTable plugin is not available.");
        return;
    }

    const table = document.getElementById("myTable");
    const rows = table.querySelectorAll("tbody tr");

    const data = [];
    let boldRows = [];
    let sectionHeaders = [];
    let slightlyBoldRows = [];

    // Categories that should NOT be indented
    let nonIndentedCategories = [
        "non-current assets:",
        "current assets:",
        "non-current liabilities:",
        "current liabilities:"
    ].map(text => text.toLowerCase());

    rows.forEach((row, rowIndex) => {
        const rowData = [];
        row.querySelectorAll("td").forEach((cell) => {
            rowData.push(cell.innerText.trim()); // Trim to remove extra spaces
        });

        const firstCellText = rowData[0]?.toLowerCase() || "";

        // Mark section headers for bold
        if (["assets", "liabilities", "equity"].includes(firstCellText)) {
            sectionHeaders.push(rowIndex);
        }

        // Major total rows for bold
        if (["total assets", "total liabilities", "total liabilities & equity"].includes(firstCellText)) {
            boldRows.push(rowIndex);
        }

        // Slightly bold rows (category totals)
        if ([
            "total non-current assets",
            "total current assets",
            "total non-current liabilities",
            "total equity",
            "total current liabilities"
        ].includes(firstCellText)) {
            slightlyBoldRows.push(rowIndex);
        }

        data.push(rowData);
    });

    doc.setFontSize(14); // Adjust font size
    doc.setFont("helvetica", "bold"); // Set font style
    doc.text("Balance Sheet", 105, 10, {
        align: "center"
    }); // Center the title

    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text("Ebenezer Apartment,", 105, 6, {
        align: "center"
    });

    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text("As of December 31, 2024", 105, 14, {
        align: "center"
    });


    doc.autoTable({
        startY: 15, // Moves the table down to create space for headers
        head: [
            ['Description', 'Amount (KSH)']
        ],
        body: data,

        headStyles: {
            fillColor: [0, 25, 45], // Dark Blue (#00192D)
            textColor: [255, 255, 255], // White text
            fontStyle: 'bold'
        },


        didParseCell: function (data) {
            if (data.section === 'body') {
                const rowIndex = data.row.index;
                const colIndex = data.column.index;
                const text = data.cell.raw.trim().toLowerCase();

                // Apply bold to section headers
                if (sectionHeaders.includes(rowIndex)) {
                    data.cell.styles.fontSize = 12;
                    data.cell.styles.fontStyle = 'bold';
                }

                // Apply bold to major total rows
                if (boldRows.includes(rowIndex)) {
                    data.cell.styles.fontStyle = 'bold';
                    data.cell.styles.fontSize = 12;
                }

                // Apply semi-bold style to slightly bold rows
                if (slightlyBoldRows.includes(rowIndex)) {
                    data.cell.styles.fontStyle = 'bold';
                    data.cell.styles.fontSize = 10;
                }

                // Indent sub-items but NOT Non-current Assets, Current Assets, Non-current Liabilities, Current Liabilities
                if (
                    colIndex === 0 &&
                    !sectionHeaders.includes(rowIndex) &&
                    !boldRows.includes(rowIndex) &&
                    !nonIndentedCategories.includes(text)
                ) {
                    data.cell.styles.cellPadding = {
                        left: 10,
                        right: 2,
                        top: 2,
                        bottom: 2
                    };
                }
            }
        }
    });

    doc.save('balancesheet.pdf');

};