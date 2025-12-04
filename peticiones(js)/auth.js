document.addEventListener("DOMContentLoaded", function() {
    const formLogin = document.getElementById("formLogin");

    if (formLogin) {
        formLogin.addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(this.action, { method: this.method, body: formData })
                .then(res => {
                    // Manejo de errores HTTP
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    return res.json(); // Convertir respuesta a JSON
                })
                .then(data => {
                    if (data.success) {
                        // Redirigir según rol
                        switch (data.id_rol.toString()) { // asegurar que sea string
                            case "1":
                                window.location.href = urlBase + "Vista/usuaria/perfil";
                                break;
                            case "2":
                                window.location.href = urlBase + "Vista/especialista/perfil";
                                break;
                            case "3":
                                window.location.href = urlBase + "Vista/admin/";
                                break;
                        }
                    } else {
                        // Mostrar mensaje de error con SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message || "Correo o contraseña incorrectos",
                            confirmButtonColor: '#5a2a83',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .catch(err => console.error("Error login normal:", err));
        });
    }

    // Login con Google
    const btnGoogle = document.getElementById("btnGoogleLogin");
    if (btnGoogle) {
        btnGoogle.addEventListener("click", function(e) {
            e.preventDefault();
            window.location.href = urlBase + "Controlador/loginGoogle.php";
        });
    }

    // Mostrar / Ocultar contraseña
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#contraseña');

    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', () => {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            togglePassword.innerHTML = type === 'password' 
                ? '<i class="bi bi-eye-fill"></i>' 
                : '<i class="bi bi-eye-slash-fill"></i>';
        });
    }
});
