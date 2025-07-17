document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contactForm");
  const scrollLoader = document.getElementById("loaderInicioBtn");
  const btnEnviar = document.getElementById("btnEnviar");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const correo = form.correo.value.trim();
    const comentario = form.comentario.value.trim();

    if (!correo || !comentario) {
      Swal.fire({
        icon: "warning",
        title: "Campos requeridos",
        text: "Por favor completa todos los campos antes de enviar.",
        confirmButtonText: "Aceptar"
      });
      return;
    }

    const datos = $("#contactForm").serialize();

    // Mostrar el spinner y deshabilitar el botón
    scrollLoader.classList.remove("d-none");
    btnEnviar.disabled = true;

    $.ajax({
      type: "POST",
      url: "../Controlador/mandarContactCtrl.php?opcion=1",
      data: datos,
      success: function (respuesta) {
        form.reset();
        scrollLoader.classList.add("d-none");
        btnEnviar.disabled = false;

        if (respuesta.trim() === "Enviado") {
          Swal.fire({
            icon: "success",
            title: "¡Gracias!",
            text: "Tu mensaje fue enviado correctamente.",
            confirmButtonText: "Aceptar"
          });
        } else {
          Swal.fire({
            icon: "info",
            title: "Respuesta del servidor",
            text: respuesta,
            confirmButtonText: "Aceptar"
          });
        }
      },
      error: function () {
        scrollLoader.classList.add("d-none");
        btnEnviar.disabled = false;

        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Ocurrió un problema al enviar el mensaje. Inténtalo de nuevo.",
          confirmButtonText: "Aceptar"
        });
      }
    });
  });
});
