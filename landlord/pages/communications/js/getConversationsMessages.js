export async function getConversationMessages(conversationId) {

    console.log("it's being called");''
  const res = await fetch(
    `actions/getConversationMessages.php?conversation_id=${encodeURIComponent(conversationId)}`,
    { headers: { "Accept": "application/json" } }
  );

  if (!res.ok) {
    throw new Error(`HTTP ${res.status}`);
  }

  const data = await res.json();
  if (!data.ok) throw new Error(data.error || "Failed to load conversation");

  // Title
  const titleParts = [];
  if (data.contact?.name) titleParts.push(data.contact.name);
  if (data.context?.building_name) titleParts.push(data.context.building_name);
  if (data.context?.unit_number) titleParts.push(`Unit ${data.context.unit_number}`);

  document.getElementById("chatTitle").textContent =
    titleParts.length ? titleParts.join(" • ") : `Chat #${conversationId}`;

  // Contact info
  const contactInfo = document.getElementById("contactInfo");
  contactInfo.innerHTML = `
    <div class="fw-semibold">${escapeHtml(data.contact?.name || "Unknown")}</div>
    <div class="text-muted small">${escapeHtml(data.contact?.role || "")}${data.contact?.email ? " • " + escapeHtml(data.contact.email) : ""}</div>
    ${
      (data.context?.building_name || data.context?.unit_number)
        ? `<div class="text-muted small mt-1">
             ${escapeHtml(data.context?.building_name || "")}
             ${data.context?.unit_number ? " • Unit " + escapeHtml(data.context.unit_number) : ""}
           </div>`
        : ""
    }
  `;

  // Messages
  renderMessages(data.messages || [], data.me_id);
}

function renderMessages(messages, meId) {
  const el = document.getElementById("chatMessages");

  if (!messages.length) {
    el.innerHTML = `<div class="p-3 text-muted">No messages yet.</div>`;
    return;
  }

  el.innerHTML = messages.map(m => {
    const isMe = Number(m.sender_id) === Number(meId);
    return `
      <div class="p-3 border-bottom ${isMe ? "text-end" : ""}">
        <div class="small text-muted">${escapeHtml(m.created_at || "")}</div>
        <div class="${isMe ? "d-inline-block" : ""}">${escapeHtml(m.body || "")}</div>
      </div>
    `;
  }).join("");

  el.scrollTop = el.scrollHeight;
}

function escapeHtml(str) {
  return String(str ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}