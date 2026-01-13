export async function login(e) {
    e.preventDefault();

    // -----------------------------
    // Elements & state
    // -----------------------------
    const form = e.target;
    const formData = new FormData(form);

    const loginBtn = document.getElementById('loginBtn');
    const originalBtnText = loginBtn.textContent;

    const incorrectDetailsErrorMessage =
        document.getElementById('incorretDetailsErrorMessage');
    const generalErrorMessage =
        document.getElementById('generalErrorMessage');

    // -----------------------------
    // Lock UI & clear messages
    // -----------------------------
    lockButton();
    clearMessages();

    try {
        const response = await fetch('./actions/verifyLoginDetails.php', {
            method: 'POST',
            body: formData,
        });

        const data = await response.json();
        console.log(data);

        // -----------------------------
        // Handle response
        // -----------------------------
        if (data.status === 'success' && data.redirect) {
            // Server decides destination
            window.location.href = data.redirect;
            return;
        }

        // Invalid credentials or known failure
        incorrectDetailsErrorMessage.textContent =
            data.message || 'Incorrect email or password';
        unlockButton();

    } catch (err) {
        console.error(err);
        generalErrorMessage.textContent =
            'Network error. Please try again.';
        unlockButton();
    }

    // -----------------------------
    // Helper functions
    // -----------------------------
    function lockButton() {
        loginBtn.disabled = true;
        loginBtn.textContent = 'Loading...';
    }

    function unlockButton() {
        loginBtn.disabled = false;
        loginBtn.textContent = originalBtnText;
    }

    function clearMessages() {
        incorrectDetailsErrorMessage.textContent = '';
        if (generalErrorMessage) {
            generalErrorMessage.textContent = '';
        }
    }
}
