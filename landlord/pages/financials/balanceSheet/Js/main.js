import { downloadBS } from "./DownloadPDF.js";
import { exportToExcel } from "./downloadExcel.js";
import { getNonCurrentAssets, getCurrentLiabilities } from "./api.js";
import { getCurrentAssets } from "./currentAssetsApi.js";
import { getNonCurrentLiabilities } from "./nonCurrentLiabilitiesApi.js";
import { getEquity } from "./equityAPI.js";

console.log("main issa working");

document.addEventListener("DOMContentLoaded", async () => {
    // Execute in sequence
    await getNonCurrentAssets();
    await getCurrentAssets();
    await getCurrentLiabilities();
    await getNonCurrentLiabilities();
    await getEquity();

    document.getElementById('downloadBtn').addEventListener('click', downloadBS);
    document.getElementById('exportToExcel').addEventListener('click', exportToExcel);
});
