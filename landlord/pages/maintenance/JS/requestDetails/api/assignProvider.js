
/* ===========================
   ASSIGN PROVIDER
=========================== */
export async function assignProvider() {
  const providerId = this.getAttribute("data-provider-id");
  const requestId = new URLSearchParams(window.location.search).get("id");

  try {
    const response = await fetch(
      `./actions/requestDetails/assignProvider.php?request_id=${requestId}&provider_id=${providerId}`
    );

    const data = await response.json();

    if (data.status === "success") {
      console.log("✅ Provider assigned successfully:", data);
     
    } else {
      console.warn("⚠️ Assignment failed:", data.message);
    }

    // hide the Provider details Modal
    const modalElement = document.getElementById('proposalModal');
    const modal = bootstrap.Modal.getInstance(modalElement);

    if (modal) {
      modal.hide(); // actually hides it
      console.log('Modal closed');
      const confirmAssignBox = document.getElementById("confirmAssign");
      confirmAssignBox.style.display = "none";
    } else {
      console.warn('No existing modal instance found');
    }
  } catch (err) {
    console.error("❌ Error assigning provider:", err);
  }
}