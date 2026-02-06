import { html, render } from "https://unpkg.com/lit@3.1.4/index.js?module";

/* ===========================
   GET PROVIDER DETAILS
=========================== */
export async function getProviderDetails() {

  console.log('yoyi');

  const providerId = this.getAttribute("data-provider-id");
  console.log(providerId);
  try {
    const response = await fetch(
      `./actions/requestDetails/getProviderDetails.php?provider_id=${providerId}`
    );


    const res = await response.json();
    const provider = res.details;

 //  populate provider details on offCanvas
    document.getElementById("providerName").textContent = provider.name;

  } catch (err) {
    console.error("❌ Error fetching provider details:", err);
  }
}