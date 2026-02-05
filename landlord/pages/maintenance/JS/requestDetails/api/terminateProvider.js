

export async function terminateContract() {
    try {
        const assignmentId = this.getAttribute('data-assignment-id');
        console.log("Assignment ID:", assignmentId);

        const res = await fetch("actions/requestDetails/terminateContract.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "&assignment_id=" + encodeURIComponent(assignmentId)
        });

        const data = await res.text();
        console.log("Server Response:", data);

        // re-render the request details
        

        const modalElement = document.getElementById('providerModal');
        const modal = bootstrap.Modal.getInstance(modalElement); // get existing instance

        if (modal) {
            modal.hide(); // actually hides it
            console.log('Modal closed');
        } else {
            console.warn('No existing modal instance found');
        }


    } catch (error) {
        console.error("Error sending response:", error);
    }
}



export function terminateContractBox() {
    const terminateBox = document.getElementById("terminateBox");
    const confirmTerminateBox = document.getElementById("confirmTerminateBox");

    terminateBox.style.display = "none";
    confirmTerminateBox.style.display = "flex";
}

// cancel confirm button box
export function hideTerminateBox() {
    const terminateBox = document.getElementById("terminateBox");
    const confirmTerminateBox = document.getElementById("confirmTerminateBox");

    terminateBox.style.display = "block";
    confirmTerminateBox.style.display = "none";

}