
import { getConversationMessages } from "./getConversationsMessages.js";
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


    document.addEventListener("click", async (e) => {

        const btn = e.target.closest(".open-chat");
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        const conversationId = btn.dataset.conversationId;
        if (!conversationId) return;

        // Open offcanvas first
        const el = document.getElementById("chatOffcanvas");
        const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(el);
        offcanvas.show();

        getConversationMessages(conversationId);

        console.log('js being reached');

    });

});

