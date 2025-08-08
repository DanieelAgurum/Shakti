document.getElementById("formCalificacion").addEventListener("submit", function (e) {
    e.preventDefault();

    const calificacion = document.querySelector('input[name="calificacion"]:checked');
    const opinion = document.getElementById("opinion").value.trim();
    const btn = this.querySelector("button[type=submit]");

    if (!calificacion) {
        Swal.fire({
            icon: "warning",
            title: "Calificación requerida",
            text: "Por favor selecciona una calificación antes de enviar.",
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
        return;
    }
    if (opinion === "") {
        Swal.fire({
            icon: "warning",
            title: "Opinión requerida",
            text: "Por favor escribe tu experiencia.",
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
        return;
    }

    btn.disabled = true;
    const formData = new FormData();
    formData.append("opcion", 1);
    formData.append("calificacion", calificacion.value);
    formData.append("opinion", opinion);

    fetch("Controlador/testimoniosCtrl.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
                .then(data => {
            if (data.status === "no-session") {
                Swal.fire({
                    icon: 'info',
                    title: 'Ups...',
                    text: data.message,
                    confirmButtonText: 'Iniciar sesión',
                    confirmButtonColor: '#3085d6'
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = '/Vista/login.php';
                    }
                });
                return;
            }

            if (data.status === "success") {
                Swal.fire({
                    icon: "success",
                    title: "¡Gracias!",
                    text: data.message,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                document.getElementById("formCalificacion").reset();
            } else if (data.message.includes("ofensivo")) {
                Swal.fire({
                    icon: "warning",
                    title: "Lenguaje inapropiado",
                    text: data.message,
                    timer: 4000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: "warning",
                    title: "Lenguaje inapropiado",
                    text: data.message,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: "error",
                title: "Error de red",
                text: "No se pudo enviar el testimonio.",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        })
        .finally(() => {
            btn.disabled = false;
        });
});
