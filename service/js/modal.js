// Add the request id to the submit proposal form in apply Modal on click Apply
export function add_request_id(button) {
    // Check if the button exists
    if (!button) {
        console.error("Button not found!");
        return;
    }

    // Attach the click event listener
    button.addEventListener('click', function(event) {
        // Get the request ID from the data-request-id attribute
        const requestId = event.target.getAttribute('data-request-id');
        
        // If no ID is found, log an error and return
        if (!requestId) {
            console.error("Request ID not found!");
            return;
        }

        // Log the requestId for debugging purposes
        console.log("Request ID:", requestId);

        // Set the value of the hidden input field with the request ID
        const hiddenInput = document.getElementById('requestId');
        if (hiddenInput) {
            hiddenInput.value = requestId;
            console.log('Request ID set in form:', hiddenInput.value);
        } else {
            console.error("Hidden input for request ID not found.");
        }
    });
}

