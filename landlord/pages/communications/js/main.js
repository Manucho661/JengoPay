
import { updateRecipientOptions, getUnits, getTenant } from "./updateReciepientOptions.js";
document.addEventListener("DOMContentLoaded", () => {

    // get unit
    const buildingSelect = document.getElementById("buildingSelect");
    buildingSelect.addEventListener("change", getUnits);

    // //   get tenant
    const unitSelect = document.getElementById("unitSelect");
    unitSelect.addEventListener("change", getTenant);

    const updateReciepientOptions = document.getElementById("recipientType");
    updateReciepientOptions.addEventListener("change", updateRecipientOptions);
});

