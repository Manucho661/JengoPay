
/* ===========================
   SEND TEXT
=========================== */
export async function sendText(e) {
    e.preventDefault(); // stop form reload

    try {
        console.log("chat function is working");

        const input = document.getElementById("chatInput");
        // const chatBody = document.getElementById("chatBody");
        const message = input.value.trim();
        if (!message) return;

        const res = await fetch("./actions/sendText.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ text: message })
        });

        // Check if response is ok (status 200–299)
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }

        // Parse and log the JSON response
        const data = await res.json();
        console.log("✅ Server response:", data);

        // Optionally clear the input after sending
        input.value = "";

    } catch (err) {
        console.error("❌ Error sending message:", err);
    }
}
