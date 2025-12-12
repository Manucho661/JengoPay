import { registerUser } from "./api/registerUser.js";

document.addEventListener("DOMContentLoaded", async () => {
    document.getElementById('registerForm')?.addEventListener('submit', registerUser);
    document.getElementById('registerLandlordForm')?.addEventListener('submit', registerUser);
});
