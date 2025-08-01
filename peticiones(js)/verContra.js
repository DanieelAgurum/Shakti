document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("contraseña") ?? document.getElementById("password");
    const toggleBtn = document.getElementById("togglePassword");
    const icon = document.getElementById("iconPassword");

    toggleBtn.addEventListener("click", function () {
        const type = passwordInput.type === "password" ? "text" : "password";
        passwordInput.type = type;
        icon.classList.toggle("bi-eye-fill");
        icon.classList.toggle("bi-eye-slash-fill");
    });
});