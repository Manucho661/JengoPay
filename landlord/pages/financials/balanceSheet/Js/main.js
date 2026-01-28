import { downloadBS } from "./DownloadPDF.js";
import { exportToExcel } from "./downloadExcel.js";


document.addEventListener("DOMContentLoaded", async () => {

    document.getElementById('downloadBtn').addEventListener('click', downloadBS);
    document.getElementById('exportToExcel').addEventListener('click', exportToExcel);
});
