
export async function getSummary() {
    const response = await fetch(`../actions/getSummary`);
    const summary = await response.json();
    console.log("Fetched data:", summary);
}