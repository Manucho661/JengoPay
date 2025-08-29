import { downloadBS } from "./DownloadPDF.js";
import {exportToExcel} from "./downloadExcel.js"
    console.log("main issa working");

document.addEventListener("DOMContentLoaded", () => {
    console.log("main issa working");

    document.getElementById('downloadBtn').addEventListener('click', downloadBS);
    document.getElementById('exportToExcel').addEventListener('click', exportToExcel);

});