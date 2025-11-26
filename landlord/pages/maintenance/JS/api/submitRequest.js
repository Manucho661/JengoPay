import { fetchRequests } from "./getRequests.js";

export async function submitRequest(e, modal) {
    e.preventDefault();

    const form = document.getElementById('requestForm');
    const otherField = document.getElementById('otherRequestField');
    const specifyRequest = document.getElementById('specifyRequest');

    // Validate other field if visible
    if (otherField.style.display === 'block' && !specifyRequest.value.trim()) {
        specifyRequest.setCustomValidity('Please specify your request');
        specifyRequest.reportValidity();
        return;
    } else {
        specifyRequest.setCustomValidity('');
    }

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Build FormData (important — this sends files + fields properly)
    const fd = new FormData();
    fd.append('category', document.getElementById('category').value);
    const requestValue = otherField.style.display === 'block' ? specifyRequest.value : document.getElementById('request').value;
    fd.append('request', requestValue);
    fd.append('description', document.getElementById('description').value);

    // If your file input has id "photoUpload" and allows multiple, append all files with name 'photos[]'
    const fileInput = document.getElementById('photoUpload');
    if (fileInput && fileInput.files.length) {
        for (let i = 0; i < fileInput.files.length; i++) {
            fd.append('photos[]', fileInput.files[i]); // name must be photos[] to match PHP expectation
        }
    }

    try {
        const response = await fetch("./actions/submitRequest.php", {
            method: "POST",
            body: fd,
            // DO NOT set 'Content-Type' header — the browser will add the correct multipart boundary
        });

        const submitResult = await response.json();
        console.log('Server response:', submitResult);

        if (submitResult.success) {
            // alert('Request submitted successfully!');
            // Reset UI
            form.reset();
            otherField.style.display = 'none';
            document.getElementById('otherRequestBtn').textContent = 'Other';

            // modal
            modal.hide();
            fetchRequests();
        } else {
            // show helpful message
            alert('Submission failed: ' + (submitResult.error || submitResult.message || 'Unknown error'));
        }

    } catch (err) {
        console.error(err);
        alert('An error occurred while submitting the request.');
    }
}
