document.addEventListener("DOMContentLoaded", () => {
  const solicitudSidebar = document.getElementById("solicitudSidebar");
  const usuariosList = document.getElementById("usuariosGrid");
  const searchForm = document.querySelector(".search-box form");
  const searchInput = searchForm.querySelector("input[name='buscador']");

  // Loader animación
  function showLoader(target) {
    target.innerHTML = `
            <div id="loaderInicio" class="loader-container">
            <div class="orbit">
            </div>
        </div>`;
  }

  async function fetchHTML(url) {
    try {
      const response = await fetch(url, { method: "GET" });
      if (!response.ok) throw new Error(`Error: ${response.status}`);
      return await response.text();
    } catch (error) {
      console.error("Fetch fallido:", error);
      return "<p>Error cargando contenido</p>";
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

    // Si no hay query y hay cache -> mostrar cache inmediatamente
    if (!query) {
      const cache = localStorage.getItem("usuariosCache");
      if (cache) {
        usuariosList.innerHTML = cache;
      }
    }

    const url =
      "/shakti/Controlador/solicitudesCtrl.php?usuarios" +
      (query ? `&buscador=${encodeURIComponent(query)}` : "");

    const htmlUsuarios = await fetchHTML(url);

    if (htmlUsuarios) {
      usuariosList.innerHTML = htmlUsuarios;
      if (!query) {
        // Guardar cache solo si no es búsqueda
        localStorage.setItem("usuariosCache", htmlUsuarios);
      }
    } else {
      usuariosList.innerHTML = `<p>No hay usuarios disponibles</p>`;
    }
  }

  // Al cargar la página: solicitudes + usuarios
  Promise.all([cargarSolicitudes(), cargarUsuarios()]);

  searchForm.addEventListener("submit", async (e) => {
    e.preventDefault(); // evitar recarga
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

    // 1. Aceptar solicitud desde slider
    if (e.target.classList.contains("btn-banner-azul")) {
      const solicitudDiv = document.querySelector(
        `div[data-soli-nickname="${nickname}"]`
      );
      if (solicitudDiv) solicitudDiv.remove();

      $.ajax({
        url: "/shakti/Controlador/solicitudesCtrl.php?aceptarSolicitud",
        type: "POST",
        data: { nickname: nickname },
        success: function (data) {
          if (data) {
            if (usuarioDiv) {
              usuarioDiv.innerHTML = `
              <button type="button" class="btn btn-secondary btn-agregado" data-nickname="${nickname}">
                Agregado <i class="bi bi-person-check"></i>
              </button>`;
            }
          }
          console.log("✅ Respuesta del back:", data);
        },
        error: function (xhr, status, error) {
          console.error("❌ Error en la petición:", status, error);
        },
      });
      return;
    }

    // 2. Rechazar solicitud desde slider
    if (e.target.classList.contains("btn-banner-rojo")) {
      const solicitudDiv = document.querySelector(
        `div[data-soli-nickname="${nickname}"]`
      );
      if (solicitudDiv) solicitudDiv.remove();

      if (usuarioDiv) {
        usuarioDiv.innerHTML = `
        <button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
          Agregar Amigo <i class="bi bi-person-add"></i>
        </button>
      `;
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

    // 1. Agregar amigo → pasa a Cancelar
    if (e.target.classList.contains("btn-agregar")) {
      usuarioDiv.innerHTML = `
      <button type="button" class="btn btn-warning btn-cancelar" data-nickname="${nickname}">
        Cancelar Solicitud <i class="bi bi-x-circle"></i>
      </button>
    `;
      console.log("✅ Solicitud enviada:", nickname);
      return;
    }

    // 2. Cancelar solicitud → vuelve a Agregar amigo
    if (e.target.classList.contains("btn-cancelar")) {
      usuarioDiv.innerHTML = `
      <button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
        Agregar Amigo <i class="bi bi-person-add"></i>
      </button>
    `;
      console.log("❌ Solicitud cancelada:", nickname);
      return;
    }

    // 3. Aceptar solicitud desde usuarios → cambia a Agregado y quita del slider
    if (
      e.target.classList.contains("btn-banner-azul") &&
      e.target.textContent.includes("Aceptar")
    ) {
      usuarioDiv.innerHTML = `
      <button type="button" class="btn btn-secondary btn-agregado" data-nickname="${nickname}">
        Agregado <i class="bi bi-person-check"></i>
      </button>
    `;
      if (solicitudDiv) solicitudDiv.remove();

      console.log("✅ Solicitud aceptada desde usuarios:", nickname);
      return;
    }

    // 4. Rechazar solicitud desde usuarios → cambia a Agregar amigo y quita del slider
    if (e.target.classList.contains("btn-banner-rojo")) {
      usuarioDiv.innerHTML = `
      <button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
        Agregar Amigo <i class="bi bi-person-add"></i>
      </button>
    `;
      if (solicitudDiv) solicitudDiv.remove();

      console.log("❌ Solicitud rechazada desde usuarios:", nickname);
      return;
    }

    // 5. Agregado (ya aceptado) → vuelve a Agregar amigo (elimina)
    if (e.target.classList.contains("btn-agregado")) {
      usuarioDiv.innerHTML = `
      <button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
        Agregar Amigo <i class="bi bi-person-add"></i>
      </button>
    `;
      console.log("🔄 Amigo eliminado:", nickname);
      return;
    }
  });
});
