export async function getTextMessages() {
    try {
        const res = await fetch('./actions/getTextMessages.php');
        const data = await res.json();

        console.log('💬 Chat history:', data);

        // ✅ Ensure we have a successful response
        if (data.status !== 'success' || !Array.isArray(data.messages)) {
            console.warn('⚠️ No messages found or invalid response');
            return;
        }

        // ✅ Get the chat body container
        const chatBody = document.getElementById('chatBody');
        if (!chatBody) return;

        // ✅ Clear old messages
        chatBody.innerHTML = '';

        // ✅ Loop through messages and render them
        data.messages.forEach(msg => {
            const messageDiv = document.createElement('div');

            // Use 'me' or 'client' depending on sender_id
            messageDiv.classList.add('message');
            messageDiv.classList.add(msg.sender_id === 2 ? 'me' : 'client');

            // Add message bubble
            messageDiv.innerHTML = `
        <div class="bubble">${msg.message}</div>
      `;

            chatBody.appendChild(messageDiv);
        });

        // ✅ Auto-scroll to the bottom
        chatBody.scrollTop = chatBody.scrollHeight;

    } catch (err) {
        console.error('❌ Error fetching messages:', err);
    }
}

