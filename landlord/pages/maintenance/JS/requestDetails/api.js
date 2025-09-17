// Get the proposals
export async function fetchProposals() {

    const params = new URLSearchParams(window.location.search);
    const id = params.get("id"); // e.g. 7

    const response = await fetch(`./actions/request_details/get_proposals.php?id=${id}`);
    const currentData = await response.json();
    console.log("Fetched data:", currentData);
    // renderRequestsTable(currentData.data);
}