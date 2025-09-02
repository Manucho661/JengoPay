export function exportToExcel() {
    let table = document.getElementById("myTable");
    let workbook = XLSX.utils.table_to_book(table, {
        sheet: "Sheet1"
    });
    let excelFile = XLSX.write(workbook, {
        bookType: 'xlsx',
        type: 'array'
    });
    let blob = new Blob([excelFile], {
        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    });
    saveAs(blob, "table_data.xlsx");
}