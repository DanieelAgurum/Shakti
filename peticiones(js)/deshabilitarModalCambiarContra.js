document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formRecuperar");
  const emailInput = document.getElementById("recuperarEmail");
  const btnEnviar = document.getElementById("btnEnviarRecuperacion");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const correo = emailInput.value.trim();
    if (!correo) return;

    btnEnviar.disabled = true;

    fetch("../Controlador/cambiarContraCorreo.php?opcion=1", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({ correo }),
    })
      .then((response) => {
        if (!response.ok) throw new Error("Error al enviar el correo.");
        return response.text();
      })
      .then(() => {
        form.reset();

        Swal.fire({
          icon: "success",
          title: "Correo enviado",
          text: "Revisa tu bandeja de entrada o correo no deseado.",
          confirmButtonColor: "#6a1b9a",
        }).then(() => {
          const modal = bootstrap.Modal.getInstance(document.getElementById("exampleModal"));
          if (modal) modal.hide();

          btnEnviar.disabled = false;
        });
      })
      .catch((error) => {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo enviar el correo. Intenta más tarde.",
          confirmButtonColor: "#d33",
        });
        btnEnviar.disabled = false;
      });
  });
});