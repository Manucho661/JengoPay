// uiControl.js

export function toggleProposalsORotherRequests(sectionId) {
  const sections = {
    "proposals-list": {
      list: document.getElementById("proposals-list"),
      btn: document.getElementById("proposals"),
    },
    "requestList": {
      list: document.getElementById("requestList"),
      btn: document.getElementById("otherRequests"),
    },
  };

  // Guard: if invalid sectionId passed
  if (!sections[sectionId]) {
    console.error(`❌ Unknown sectionId: ${sectionId}`);
    return;
  }

  // Hide all sections and reset buttons
  Object.values(sections).forEach(({ list, btn }) => {
    if (list) list.classList.remove("visible");
    if (btn) btn.classList.remove("active-btn");
  });

  const { list, btn } = sections[sectionId];
  if (!list || !btn) {
    console.warn(`⚠️ Missing DOM elements for section: ${sectionId}`);
    return;
  }

  // Show target section and activate its button
  list.classList.add("visible");
  btn.classList.add("active-btn");
}


// the assign buttons
export function confirmAssignBox() {
  const assignBox = document.getElementById("assignBox");
  const confirmAssignBox = document.getElementById("confirmAssign");

  assignBox.style.display = "none";
  confirmAssignBox.style.display = "flex";
}

// cancel confirm button box
export function hideAssignBox() {
  const assignBox = document.getElementById("assignBox");
  const confirmAssignBox = document.getElementById("confirmAssign");

  assignBox.style.display = "block";
  confirmAssignBox.style.display = "none";

}

// Availability background color
export function applyAvailabilityStyles(status) {
  const btn = document.getElementById("availabilityBtn");

  if (status === "available") {
    btn.style.backgroundColor = "#fff"; // always white
    btn.style.color = "#00192D";        // text color consistent
  } else {
    btn.style.backgroundColor = "#E6EAF0"; // your default grey
    btn.style.color = "#00192D";
  }
}
