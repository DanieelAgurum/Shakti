// Google Sign-In
function handleCredentialResponse(response) {
    console.log("Token recibido:", response.credential); 
    fetch(urlBase + "Controlador/loginGoogle.php", { // urlBase debe estar definido globalmente
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "credential=" + encodeURIComponent(response.credential)
    })
    .then(res => res.json())
    .then(data => {
        console.log("Respuesta del backend:", data); 
        if(data.success){
            var authModal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
            if(authModal) authModal.hide();
            switch(String(data.id_rol)){
                case "1": window.location.href = urlBase + "usuaria/perfil.php"; break;
                case "2": window.location.href = urlBase + "especialista/perfil.php"; break;
                case "3": window.location.href = urlBase + "admin/"; break;
            }
        } else {
            alert(data.msg || "No se pudo iniciar sesión con Google");
        }
    })
    .catch(err => console.error(err));
}

// Login con formulario
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
                    }
                } else {
                    alert("Usuario o contraseña incorrectos");
                }
            })
            .catch(err => console.error(err));
        });
    }
});
