export async function sendResponse(response) {
    try {
        const assignmentId = this.getAttribute('data-assignment_id');
        console.log("Assignment ID:", assignmentId);

        const res = await fetch("actions/sendResponse.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "provider_response=" + encodeURIComponent(response) +
                  "&assignment_id=" + encodeURIComponent(assignmentId)
        });

        const data = await res.text();
        console.log("Server Response:", data);
    } catch (error) {
        console.error("Error sending response:", error);
    }
}
