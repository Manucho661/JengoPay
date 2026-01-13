import { login } from "./login.js";
import { registerUser } from "./registerUser.js";
import { resetPassword } from "./resetPassword.js"
import { typingEffect } from "./typingEffect.js";

document.addEventListener("DOMContentLoaded", async () => {
    typingEffect();
    document.getElementById('loginForm')?.addEventListener('submit', login);
    document.getElementById('registerProviderForm')?.addEventListener('submit', registerUser);
    document.getElementById('registerLandlordForm')?.addEventListener('submit', registerUser);
    document.getElementById('resetPasswordForm')?.addEventListener('submit', resetPassword);

})