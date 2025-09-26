document.addEventListener("DOMContentLoaded", () => {
  const solicitudSidebar = document.getElementById("solicitudSidebar");
  const usuariosList = document.getElementById("usuariosGrid");
  const searchForm = document.querySelector(".search-box form");
  const searchInput = searchForm.querySelector("input[name='buscador']");

  // Función para mostrar loader en un contenedor específico
  function showLoader(target) {
    target.innerHTML = `
      <div class="loader-container">
        <div class="orbit"></div>
      </div>`;
  }

  // Función para ocultar loader (puede dejar contenido vacío o conservar contenido previo)
  function hideLoader(target) {
    target.innerHTML = "";
  }

  // Función para obtener HTML desde el backend
  async function fetchHTML(url) {
    try {
      const response = await fetch(url, { method: "GET" });
      if (!response.ok) throw new Error(`Error: ${response.status}`);
      return await response.text();
    } catch (error) {
      return null;
    }
  }

  // Cargar solicitudes
  async function cargarSolicitudes() {
    showLoader(solicitudSidebar);

    const htmlSolicitudes = await fetchHTML(
      "/shakti/Controlador/solicitudesCtrl.php?solicitudes"
    );

    solicitudSidebar.innerHTML =
      htmlSolicitudes ||
      `<div class="solicitud-vacia"><p>Sin solicitudes</p></div>`;
  }

  // Cargar usuarios con buscador opcional
  async function cargarUsuarios(query = "") {
    showLoader(usuariosList);

    const url =
      "/shakti/Controlador/solicitudesCtrl.php?usuarios" +
      (query ? `&buscador=${encodeURIComponent(query)}` : "");

    const htmlUsuarios = await fetchHTML(url);

    if (htmlUsuarios && htmlUsuarios.trim().length > 0) {
      usuariosList.innerHTML = htmlUsuarios;
    } else {
      usuariosList.innerHTML = `<p>No hay usuarios disponibles</p>`;
    }
  }

  // Cargar ambos al inicio
  Promise.all([cargarSolicitudes(), cargarUsuarios()]);

  // Buscar usuarios
  searchForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const query = searchInput.value.trim();
    await cargarUsuarios(query);
  });

  // Manejo en el sidebar de solicitudes
  solicitudSidebar.addEventListener("click", (e) => {
    const nickname = e.target.getAttribute("data-nickname");
    if (!nickname) return;

    const usuarioDiv = document.querySelector(
      `div[data-soli-usuario-nickname="${nickname}"]`
    );

    if (e.target.classList.contains("btn-banner-azul")) {
      const solicitudDiv = document.querySelector(
        `div[data-soli-nickname="${nickname}"]`
      );
      if (solicitudDiv) solicitudDiv.remove();

      $.ajax({
        url: "/shakti/Controlador/solicitudesCtrl.php?aceptarSolicitud",
        type: "POST",
        data: { nickname },
        success: function (data) {
          if (data && usuarioDiv) {
            usuarioDiv.innerHTML = `
              <button type="button" class="btn btn-secondary btn-agregado" data-nickname="${nickname}">
                Agregado <i class="bi bi-person-check"></i>
              </button>`;
          }
        },
        error: function (xhr, status, error) {
          console.error("❌ Error en la petición:", status, error);
        },
      });
      return;
    }

    if (e.target.classList.contains("btn-banner-rojo")) {
      const solicitudDiv = document.querySelector(
        `div[data-soli-nickname="${nickname}"]`
      );
      if (solicitudDiv) solicitudDiv.remove();

      if (usuarioDiv) {
        usuarioDiv.innerHTML = `
          <button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
            Agregar Amigo <i class="bi bi-person-add"></i>
          </button>`;
      }

      console.log("❌ Solicitud rechazada desde slider:", nickname);
      return;
    }
  });

  // Manejo de botones en lista de usuarios
  usuariosList.addEventListener("click", (e) => {
    const nickname = e.target.getAttribute("data-nickname");
    if (!nickname) return;

    const usuarioDiv = document.querySelector(
      `div[data-soli-usuario-nickname="${nickname}"]`
    );
    if (!usuarioDiv) return;

    const solicitudDiv = document.querySelector(
      `div[data-soli-nickname="${nickname}"]`
    );

    if (e.target.classList.contains("btn-agregar")) {
      $.ajax({
        url: "/shakti/Controlador/solicitudesCtrl.php?agregarAmigo",
        type: "POST",
        data: { nickname },
        success: function (data) {
          try {
            if (data == "enviada" && usuarioDiv) {
              usuarioDiv.innerHTML = `
              <button type="button" class="btn btn-warning btn-cancelar" data-nickname="${nickname}">
                Cancelar Solicitud <i class="bi bi-x-circle"></i>
              </button>`;
            }
          } catch (error) {}
        },
      });
      return;
    }

    if (e.target.classList.contains("btn-cancelar")) {
      $.ajax({
        url: "/shakti/Controlador/solicitudesCtrl.php?cancelarSolicitud",
        type: "POST",
        data: { nickname },
        success: function (data) {
          try {
            if (data == "cancelado" && usuarioDiv) {
              usuarioDiv.innerHTML = `
      <button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
        Agregar Amigo <i class="bi bi-person-add"></i>
      </button>`;
            }
          } catch (error) {}
        },
      });
      return;
    }

    if (
      e.target.classList.contains("btn-banner-azul") &&
      e.target.textContent.includes("Aceptar")
    ) {
      usuarioDiv.innerHTML = `
      <button type="button" class="btn btn-secondary btn-agregado" data-nickname="${nickname}">
        Agregado <i class="bi bi-person-check"></i>
      </button>`;
      if (solicitudDiv) solicitudDiv.remove();
      console.log("✅ Solicitud aceptada desde usuarios:", nickname);
      return;
    }

    if (e.target.classList.contains("btn-banner-rojo")) {
      usuarioDiv.innerHTML = `
      <button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
        Agregar Amigo <i class="bi bi-person-add"></i>
      </button>`;
      if (solicitudDiv) solicitudDiv.remove();
      console.log("❌ Solicitud rechazada desde usuarios:", nickname);
      return;
    }

    if (e.target.classList.contains("btn-agregado")) {
      usuarioDiv.innerHTML = `
      <button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
        Agregar Amigo <i class="bi bi-person-add"></i>
      </button>`;
      console.log("🔄 Amigo eliminado:", nickname);
      return;
    }
  });
});
