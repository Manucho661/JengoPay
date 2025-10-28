export async function sendResponse(response) {
    try {
        const res = await fetch("action.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "provider_response=" + encodeURIComponent(response)
        });

        if (!res.ok) {
            throw new Error("Network response was not OK");
        }

        const data = await res.text();
        console.log("Server Response:", data);
    } catch (error) {
        console.error("Error sending response:", error);
    }
}

