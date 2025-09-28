// Define la base URL de tu proyecto
const urlBase = "http://localhost/Shakti/";

// Google Sign-In
function handleCredentialResponse(response) {
    if (!response || !response.credential) {
        console.error("No se recibió credential de Google");
        return;
    }

    console.log("Token recibido:", response.credential); 

    fetch(urlBase + "Controlador/loginGoogle.php", { 
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "credential=" + encodeURIComponent(response.credential)
    })
    .then(res => res.json())
    .then(data => {
        console.log("Respuesta del backend:", data); 
        if(data.success){
            const modalEl = document.getElementById('authModal');
            if(modalEl){
                const authModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                authModal.hide();
            }
            switch(String(data.id_rol)){
                case "1": window.location.href = urlBase + "usuaria/perfil.php"; break;
                case "2": window.location.href = urlBase + "especialista/perfil.php"; break;
                case "3": window.location.href = urlBase + "admin/"; break;
                default: console.warn("Rol no reconocido:", data.id_rol);
            }
        } else {
            alert(data.msg || "No se pudo iniciar sesión con Google");
        }
    })
    .catch(err => console.error("Error en fetch loginGoogle:", err));
}

// Login con formulario tradicional
document.addEventListener("DOMContentLoaded", function() {
    const formLogin = document.getElementById("formLogin");
    if(formLogin){
        formLogin.addEventListener("submit", function(e){
            e.preventDefault(); 
            const formData = new FormData(formLogin);

            fetch(formLogin.action, {
                method: formLogin.method,
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    switch(String(data.id_rol)){
                        case "1":
                            window.location.href = urlBase + "usuaria/perfil.php";
                            break;
                        case "2":
                            window.location.href = urlBase + "especialista/perfil.php";
                            break;
                        case "3":
                            window.location.href = urlBase + "admin/";
                            break;
                        default:
                            alert("Rol no reconocido");
                    }
                } else {
                    alert("Usuario o contraseña incorrectos");
                }
            })
            .catch(err => console.error("Error en fetch login formulario:", err));
        });
    }
});
