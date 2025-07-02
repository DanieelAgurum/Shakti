function esperar() {
  const formRecuperar = document.getElementById("formRecuperar");
  const modalContent = document.getElementById("exampleModal");
  const inputCorreo = document.getElementById("recuperarEmail");
  const mensaje = document.getElementById("mostrarMensaje");
  const btnSubmit = formRecuperar.querySelector("button[type='submit']");

  if (!formRecuperar || !inputCorreo || !modalContent) return;

  formRecuperar.addEventListener("submit", () => {
    // Ocultar campos
    inputCorreo.style.display = "none";
    btnSubmit.style.display = "none";

    // Mostrar mensaje temporal
    mensaje.textContent = "Enviando..........";
    formRecuperar.appendChild(mensaje);
  });
}

document.addEventListener("DOMContentLoaded", esperar);
