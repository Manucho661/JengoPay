import { applyAvailabilityStyles } from "../uiControl.js";

/* ===========================
   UPDATE AVAILABILITY
=========================== */
export async function updateAvailabilty() {
  const btn = document.getElementById("availabilityBtn");
  const requestId = btn.dataset.requestId;
  const currentStatus = btn.dataset.status;

  // Toggle status
  const newStatus = currentStatus === "available" ? "unavailable" : "available";

  try {
    // Disable button temporarily (avoid double clicks)
    btn.disabled = true;
    btn.textContent = "Updating...";

    // Send update to backend
    const response = await fetch(
      `./actions/requestDetails/update_availabilty.php?id=${requestId}&status=${newStatus}`
    );
    const data = await response.json();

    if (data.status === "success") {
      console.log("✅ Availability updated:", data);

      // Update button instantly
      btn.dataset.status = newStatus;
      btn.textContent =
        newStatus === "available" ? "Set Unavailable" : "Set Available";

      applyAvailabilityStyles(newStatus);
    } else {
      console.warn("⚠️ Failed to update availability:", data.message);
      btn.textContent =
        currentStatus === "available" ? "Set Unavailable" : "Set Available";
    }
  } catch (err) {
    console.error("❌ Error updating availability:", err);
    btn.textContent =
      currentStatus === "available" ? "Set Unavailable" : "Set Available";
  } finally {
    btn.disabled = false;
  }
}