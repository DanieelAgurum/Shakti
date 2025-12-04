document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registroForm");
  const pwd = document.getElementById("contraseña");
  const errEl = document.getElementById("errorContraseña");

  const regPermitidos = /^[A-Za-z0-9._!@#$%^&*()\-+=\[\]{};:'",.<>?/\\|`~]{8,12}$/;
  const regEspeciales = /[!@#$%^&*()\-+=\[\]{};:'",.<>?/\\|`~]/;

  const validar = () => {
    const val = pwd.value.trim();
    if (!regPermitidos.test(val)) return "Debe tener entre 8 y 12 caracteres válidos.";
    if (!regEspeciales.test(val)) return "Incluye al menos un carácter especial (!@#$...)";
    return true;
  };

  const mostrarError = (msg) => {
    errEl.textContent = msg;
    pwd.classList.add("is-invalid");
  };

  const limpiarError = () => {
    errEl.textContent = "";
    pwd.classList.remove("is-invalid");
  };

  const manejarValidacion = () => {
    const res = validar();
    if (res === true) {
      limpiarError();
      return true;
    } else {
      mostrarError(res);
      return false;
    }
  };

  pwd.addEventListener("input", manejarValidacion);
  pwd.addEventListener("blur", manejarValidacion);

  form.addEventListener("submit", (e) => {
    if (!manejarValidacion()) e.preventDefault();
  });
});