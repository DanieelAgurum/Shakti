document.addEventListener("DOMContentLoaded", () => {
const selectFont = document.getElementById("fontSize");
const checkboxDarkMode = document.getElementById("darkMode");
const checkboxHighContrast = document.getElementById("highContrast");
const inputPassword = document.getElementById("newPassword");
const btnGenerarToken = document.getElementById("btnGenerarToken");
const tokenContainer = document.getElementById("tokenContainer");
const formConfig = document.getElementById("formConfig");
const passwordMessage = document.getElementById("passwordMessage");

window.usuarioActual = <?= json_encode($usuario) ?>;

const regexPassword = /^(?=.*[0-9])(?=.*[!@#$%^&*?()_+\-=\[\]{};:'",.<>\/\\|~])[A-Za-z0-9!@#$%^&*?()_+\-=\[\]{};:'",.<>\/\\|~]{8,}$/;

    // ─────────────── Funciones Reutilizables ───────────────
    const toggleClass = (el, className, condition) => {
    el.classList.toggle(className, condition);
    };

    const aplicarTamanoFuente = (tamano) => {
    document.documentElement.classList.remove("font-small", "font-medium", "font-large");
    if (["small", "medium", "large"].includes(tamano)) {
    document.documentElement.classList.add(`font-${tamano}`);
    }
    };

    const aplicarModoOscuro = (estado) => {
    toggleClass(document.documentElement, "dark-mode", estado);
    };

    const aplicarAltoContraste = (estado) => {
    toggleClass(document.documentElement, "high-contrast", estado);
    };

    const mostrarAlerta = (icon, title, text, timer = 2000) => {
    Swal.fire({
    icon,
    title,
    text,
    timer,
    timerProgressBar: true,
    showConfirmButton: false
    });
    };

    // ─────────────── Inicialización ───────────────
    aplicarTamanoFuente("<?= $configActual['tamano_fuente'] ?? 'medium' ?>");
    aplicarModoOscuro(<?= !empty($configActual['modo_oscuro']) ? 'true' : 'false' ?>);
    aplicarAltoContraste(<?= !empty($configActual['alto_contraste']) ? 'true' : 'false' ?>);

    // ─────────────── Eventos de configuración ───────────────
    if (selectFont) selectFont.addEventListener("change", () => aplicarTamanoFuente(selectFont.value));
    if (checkboxDarkMode) checkboxDarkMode.addEventListener("change", () => aplicarModoOscuro(checkboxDarkMode.checked));
    if (checkboxHighContrast) checkboxHighContrast.addEventListener("change", () => aplicarAltoContraste(checkboxHighContrast.checked));

    // ─────────────── Validación de contraseña ───────────────
    if (inputPassword && btnGenerarToken && passwordMessage) {
    btnGenerarToken.disabled = true;

    inputPassword.addEventListener("input", () => {
    const password = inputPassword.value.trim();
    if (!password) {
    inputPassword.classList.remove("is-valid", "is-invalid");
    passwordMessage.textContent = "";
    btnGenerarToken.disabled = true;
    return;
    }

    const valido = regexPassword.test(password);
    toggleClass(inputPassword, "is-valid", valido);
    toggleClass(inputPassword, "is-invalid", !valido);
    passwordMessage.textContent = valido ? "" : "Mínimo 8 caracteres, un número y un carácter especial.";
    btnGenerarToken.disabled = !valido;
    });
    }

    // ─────────────── Generar Token ───────────────
    if (btnGenerarToken && inputPassword) {
    btnGenerarToken.addEventListener("click", () => {
    const password = inputPassword.value.trim();
    if (!regexPassword.test(password)) return;

    btnGenerarToken.disabled = true;
    btnGenerarToken.textContent = "Enviando...";

    fetch("<?= $urlBase ?>Controlador/configuracionCtrl.php", {
    method: "POST",
    headers: {
    "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "accion=generar_token&newPassword=" + encodeURIComponent(password)
    })
    .then(res => res.json())
    .then(data => {
    if (data.status === "ok") {
    tokenContainer?.classList.remove("d-none");
    mostrarAlerta("success", "Token enviado", data.msg, 2500);

    let tiempo = 60;
    btnGenerarToken.textContent = `Reintentar en ${tiempo}s`;
    const interval = setInterval(() => {
    tiempo--;
    btnGenerarToken.textContent = `Reintentar en ${tiempo}s`;
    if (tiempo <= 0) {
      clearInterval(interval);
      btnGenerarToken.textContent="Generar / Enviar token" ;
      btnGenerarToken.disabled=false;
      }
      }, 1000);
      } else {
      mostrarAlerta("error", "Error" , data.msg);
      btnGenerarToken.disabled=false;
      btnGenerarToken.textContent="Generar / Enviar token" ;
      }
      })
      .catch(err=> {
      console.error(err);
      mostrarAlerta("error", "Error", "Ocurrió un error inesperado al generar el token");
      btnGenerarToken.disabled = false;
      btnGenerarToken.textContent = "Generar / Enviar token";
      });
      });
      }

      // ─────────────── Guardar Configuración ───────────────
      if (formConfig) {
      formConfig.addEventListener("submit", (e) => {
      e.preventDefault();
      const formData = new FormData(formConfig);
      formData.append("accion", "guardar_configuracion");

      fetch("/Controlador/configuracionCtrl.php", {
      method: "POST",
      body: formData
      })
      .then(res => res.json())
      .then(data => {
      if (data.status === "ok") {
      mostrarAlerta("success", "Configuración guardada", data.msg);
      aplicarTamanoFuente(formData.get("tamano_fuente") || "medium");
      aplicarModoOscuro(formData.get("modo_oscuro") === "on");
      aplicarAltoContraste(formData.get("alto_contraste") === "on");

      bootstrap.Modal.getInstance(document.getElementById('configModal'))?.hide();
      } else {
      mostrarAlerta("error", "Error", data.msg);
      }
      })
      .catch(err => {
      console.error(err);
      mostrarAlerta("error", "Error", "Ocurrió un error al guardar la configuración");
      });
      });
      }
      });