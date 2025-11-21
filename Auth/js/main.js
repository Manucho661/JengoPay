import { login } from "./api/login.js";


document.addEventListener("DOMContentLoaded", async () =>{
    document.getElementById('loginForm').addEventListener('submit', login);
})