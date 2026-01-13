export async function registerUser(e) {
    e.preventDefault();

    // -----------------------------
    // Elements & state
    // -----------------------------
    const form = e.target;
    const formData = new FormData(form);

    const submitBtn = document.getElementById('registerBtn');
    const originalBtnText = submitBtn.textContent;

    const passErrorMessage = document.getElementById('passErrorMessage');
    const emailErrorMessage = document.getElementById('emailErrorMessage');
    const generalErrorMessage = document.getElementById('generalErrorMessage');

    const password = formData.get('password');

    // -----------------------------
    // Lock UI
    // -----------------------------
    lockButton();

    // -----------------------------
    // Clear previous messages
    // -----------------------------
    clearMessages();

    // -----------------------------
    // Password validation
    // -----------------------------
    const passwordError = validatePassword(password);
    if (passwordError) {
        passErrorMessage.textContent = passwordError;
        unlockButton();
        return;
    }

    // -----------------------------
    // Send data to server
    // -----------------------------
    try {
        const response = await fetch('./actions/registerUser.php', {
            method: 'POST',
            body: formData,
        });

        const data = await response.json();
        console.log(data);

        handleServerResponse(data);

    } catch (err) {
        console.error(err);
        generalErrorMessage.textContent = 'Network error. Please try again.';
        unlockButton();
        console.log(errorMessage)
    }

    // -----------------------------
    // Helper functions
    // -----------------------------

    function lockButton() {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Loading...';
    }

    function unlockButton() {
        submitBtn.disabled = false;
        submitBtn.textContent = originalBtnText;
    }

    function clearMessages() {
        passErrorMessage.textContent = '';
        emailErrorMessage.textContent = '';
        generalErrorMessage.textContent = '';
    }

    function validatePassword(pass) {
        if (pass.length < 6) return 'Password must be at least 6 characters.';
        if (!/\d/.test(pass)) return 'Password must contain at least 1 number.';
        if (!/[a-zA-Z]/.test(pass)) return 'Password must contain at least 1 letter.';
        if (!/[^a-zA-Z0-9]/.test(pass)) return 'Password must contain at least 1 special character.';
        return null; // no error
    }

    function handleServerResponse(data) {
        if (data.status === 'success') {
            showSuccessMessage();
        } else if (data.status === 'Email_exists') {
            emailErrorMessage.textContent = 'This email already exists. Please try another or reset password.';
            unlockButton();
        } else {
            generalErrorMessage.textContent = 'We can’t register you now. JengoPay is experiencing technical issues. Please try again later.';
            unlockButton();
        }
    }

    function showSuccessMessage() {
        const section = document.getElementById('FormSection');

        section.innerHTML = `
            <div style="text-align:center;">
                <h3>Registration successful 🎉</h3>
                <p id="redirectText">Redirecting to login</p>
            </div>
        `;

        const redirectText = document.getElementById('redirectText');
        let dots = 0;

        const interval = setInterval(() => {
            dots = (dots + 1) % 4;
            redirectText.textContent = 'Redirecting to login' + '.'.repeat(dots);
        }, 500);

        setTimeout(() => {
            clearInterval(interval);
            window.location.href = '/Jengopay/auth/login.php';
        }, 7000); // 2 seconds delay is sufficient
    }
}
