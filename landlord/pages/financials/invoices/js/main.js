
import { getUnits, getTenant } from "./updateRecipient.js";
document.addEventListener("DOMContentLoaded", () => {

    // get unit
    const buildingSelect = document.getElementById("buildingSelect");
    buildingSelect.addEventListener("change", getUnits);

    // //   get tenant
    const unitSelect = document.getElementById("unitSelect");
    unitSelect.addEventListener("change", getTenant);

});

