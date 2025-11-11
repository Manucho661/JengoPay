export async function submitProposal(e, proposalForm) {
    e.preventDefault();
    console.log("Proposal Form submitted");

    // Log the form data before submitting
    const formData = new FormData(proposalForm);
    formData.forEach((value, key) => {
        console.log(`${key}: ${value}`);
    });

    try {
        // Correctly await fetch
        const response = await fetch("actions/submitApplication.php", {
            method: "POST",
            body: formData, // Use formData, not the whole proposalForm
        });

        // Check if the response is OK
        if (!response.ok) {
            throw new Error(`Failed to submit: ${response.statusText}`);
        }

        // Assuming the response is JSON, parse it
        const data = await response.json();
        console.log("✅ Proposals fetched:", data);

        // Reload the page after successful submission
        // window.location.reload();
    } catch (err) {
        console.error("❌ Failed to fetch proposals:", err);
    }
}
