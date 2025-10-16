import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";

export async function getRequests() {
  try {
    const response = await fetch("actions/getRequests.php");
    if (!response.ok) {
      console.log("A problem occurred while sending the request");
      return;
    }

    // ✅ You forgot 'await' here
    const data = await response.json();
    console.log("Fetched applications:", data);

    // ✅ Get the container element
    const container = document.getElementById("find");

    // ✅ Create the HTML dynamically using Lit
    const template = html`
      <div class="section-title text-mute">
        Available Jobs.
        <span class="text-muted"><i>Click Apply button to apply</i></span>
      </div>
    `;

    // ✅ Render into the element
    render(template, container);
  } catch (err) {
    console.error("❌ Failed to fetch proposals:", err);
  }
}
