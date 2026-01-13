export async function resetPassword(e) {
    e.preventDefault();

    // -----------------------------
    // Elements & state
    // -----------------------------
    const form = e.target;
    const formData = new FormData(form);

    const resetBtn = document.getElementById('resetBtn');
    const originalBtnText = resetBtn.textContent;

    const passErrorMessage = document.getElementById('passErrorMessage');
    const emailErrorMessage = document.getElementById('emailErrorMessage');
    const generalErrorMessage = document.getElementById('generalErrorMessage');

    const password = formData.get('newPassword');
    const confirmPassword = formData.get('confirmPassword');

    // -----------------------------
    // Lock UI & clear messages
    // -----------------------------
    lockButton();
    clearMessages();

    // -----------------------------
    // Password strength validation
    // -----------------------------
    const passwordError = validatePassword(password);
    if (passwordError) {
        passErrorMessage.textContent = passwordError;
        unlockButton();
        return;
    }

    // -----------------------------
    // Password match validation ✅
    // -----------------------------
    if (password !== confirmPassword) {
        passErrorMessage.textContent = 'Passwords do not match.';
        unlockButton();
        return;
    }

    // -----------------------------
    // Send data to server
    // -----------------------------
    try {
        const response = await fetch('./actions/resetPassword.php', {
            method: 'POST',
            body: formData,
        });

        const data = await response.json();
        console.log(data);

        handleServerResponse(data);

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
        resetBtn.disabled = true;
        resetBtn.textContent = 'Loading...';
    }

    function unlockButton() {
        resetBtn.disabled = false;
        resetBtn.textContent = originalBtnText;
    }

    function clearMessages() {
        passErrorMessage.textContent = '';
        emailErrorMessage.textContent = '';
        generalErrorMessage.textContent = '';
    }

    function validatePassword(pass) {
        if (pass.length < 6)
            return 'Password must be at least 6 characters.';
        if (!/\d/.test(pass))
            return 'Password must contain at least 1 number.';
        if (!/[a-zA-Z]/.test(pass))
            return 'Password must contain at least 1 letter.';
        if (!/[^a-zA-Z0-9]/.test(pass))
            return 'Password must contain at least 1 special character.';
        return null;
    }

    function handleServerResponse(data) {
        if (data.status === 'success') {
            showSuccessMessage();
        } else if (data.status === 'Email_does_not_exist') {
            emailErrorMessage.textContent =
                'This email doesn’t exist. Please try another or create an account.';
            unlockButton();
        } else {
            generalErrorMessage.textContent =
                'We can’t reset your password right now. Please try again later.';
            unlockButton();
        }
    }

    function showSuccessMessage() {
        const section = document.getElementById('FormSection');

        section.innerHTML = `
            <div style="text-align:center;">
                <h3>Password reset successful 🎉</h3>
                <p id="redirectText">Redirecting to login</p>
            </div>
        `;

        const redirectText = document.getElementById('redirectText');
        let dots = 0;

        const interval = setInterval(() => {
            dots = (dots + 1) % 4;
            redirectText.textContent =
                'Redirecting to login' + '.'.repeat(dots);
        }, 500);

        setTimeout(() => {
            clearInterval(interval);
            window.location.href = '/Jengopay/auth/login.php';
        }, 2000);
    }
}
