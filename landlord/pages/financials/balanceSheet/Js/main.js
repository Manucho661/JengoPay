import { downloadBS } from "./DownloadPDF.js";
import { exportToExcel } from "./downloadExcel.js";
import { getNonCurrentLiabilities, getCurrentLiabilities  } from "./liabilitiesApi.js";
import { getEquity } from "./equityAPI.js";

console.log("main issa working");

document.addEventListener("DOMContentLoaded", async () => {
    // Execute in sequence
    await getCurrentLiabilities();
    await getNonCurrentLiabilities();
    await getEquity();

    document.getElementById('downloadBtn').addEventListener('click', downloadBS);
    document.getElementById('exportToExcel').addEventListener('click', exportToExcel);
});
