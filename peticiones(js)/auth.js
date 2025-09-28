// LOGIN NORMAL
document.addEventListener("DOMContentLoaded", function() {
    const formLogin = document.getElementById("formLogin");
    if(formLogin){
        formLogin.addEventListener("submit", function(e){
            e.preventDefault();
            const formData = new FormData(this);

            fetch(this.action, { method: this.method, body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        switch(data.id_rol){
                            case "1": window.location.href = urlBase + "Vista/usuaria/perfil.php"; break;
                            case "2": window.location.href = urlBase + "especialista/perfil.php"; break;
                            case "3": window.location.href = urlBase + "admin/"; break;
                        }
                    } else {
                        alert("Usuario o contraseña incorrectos");
                    }
                })
                .catch(err => console.error("Error login normal:", err));
        });
    }
});

document.getElementById("btnGoogleLogin")?.addEventListener("click", function(e){
    e.preventDefault();
    window.location.href = urlBase + "Controlador/loginGoogle.php"; // misma ventana
});

document.addEventListener("DOMContentLoaded", function() {
    const btnGoogle = document.getElementById("btnGoogleLogin");
    if(btnGoogle){
        btnGoogle.addEventListener("click", function(e){
            e.preventDefault();

            
           
         
            window.addEventListener("message", function(event){
                if(event.origin !== urlBase) return;
                const data = event.data;
                if(data.success){
                    const modalEl = document.getElementById('authModal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();

                    switch(data.id_rol){
                        case "1": window.location.href = urlBase + "Vista/usuaria/perfil.php"; break;
                        case "2": window.location.href = urlBase + "Vista/especialista/perfil.php"; break;
                        case "3": window.location.href = urlBase + "admin/"; break;
                    }
                } else alert(data.msg || "Error login Google");
            });
        });
    }
});
