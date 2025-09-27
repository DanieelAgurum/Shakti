document.addEventListener("DOMContentLoaded", () => {
  const solicitudSidebar = document.getElementById("solicitudSidebar");
  const usuariosList = document.getElementById("usuariosGrid");
  const searchForm = document.querySelector(".search-box form");
  const searchInput = searchForm.querySelector("input[name='buscador']");
  
  // Mostrar loader en un contenedor
  function showLoader(target) {
    target.innerHTML = `
      <div class="loader-container">
        <div class="orbit"></div>
      </div>`;
  }

  // Obtener HTML desde el backend
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

  // Cargar usuarios
  async function cargarUsuarios(query = "") {
    usuariosList.classList.add("usuario-flex");

    showLoader(usuariosList);

    const url =
      "/shakti/Controlador/solicitudesCtrl.php?usuarios" +
      (query ? `&buscador=${encodeURIComponent(query)}` : "");

    const htmlUsuarios = await fetchHTML(url);

    if (htmlUsuarios) {
      usuariosList.classList.remove("usuario-flex");
      usuariosList.classList.add("usuarios-grid");

      usuariosList.innerHTML = htmlUsuarios;
    } else {
      usuariosList.innerHTML = `<div class="usuarios-vacio"><p>No se encontraron usuarios</p></div>`;
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

  // Función AJAX
  function ajaxPost(url, data, onSuccess, onError) {
    $.ajax({
      url: url,
      type: "POST",
      data: data,
      success: onSuccess,
      error: onError || function (xhr, status, error) {},
    });
  }

  // Actualizar botón del usuario
  function actualizarBotonUsuario(usuarioDiv, html) {
    if (usuarioDiv) usuarioDiv.innerHTML = html;
  }

  // Eliminar solicitud
  function eliminarSolicitud(nickname) {
    // Buscar el contenedor card completo
    const solicitudCard = document
      .querySelector(`.solicitud-card div[data-soli-nickname="${nickname}"]`)
      ?.closest(".solicitud-card");

    if (solicitudCard) solicitudCard.remove();

    // Si ya no hay solicitudes, mostrar mensaje vacío
    const sidebar = document.getElementById("solicitudSidebar");
    if (sidebar && sidebar.children.length === 0) {
      sidebar.innerHTML = `<div class="solicitud-vacia"><p>Sin solicitudes</p></div>`;
    }
  }

  // Manejar clicks
  function manejarClick(e, tipo) {
    const nickname = e.target.getAttribute("data-nickname");
    if (!nickname) return;

    const usuarioDiv = document.querySelector(
      `div[data-soli-usuario-nickname="${nickname}"]`
    );
    if (!usuarioDiv) return;

    switch (tipo) {
      case "aceptar":
        ajaxPost(
          "/shakti/Controlador/solicitudesCtrl.php?aceptarSolicitud",
          { nickname },
          function () {
            actualizarBotonUsuario(
              usuarioDiv,
              `<button type="button" class="btn btn-secondary btn-agregado" data-nickname="${nickname}">
                Agregado <i class="bi bi-person-check"></i>
              </button>`
            );
            eliminarSolicitud(nickname);
          }
        );
        break;

      case "rechazar":
        ajaxPost(
          "/shakti/Controlador/solicitudesCtrl.php?rechazarAmigo",
          { nickname },
          function (data) {
            if (data === "rechazo" || data === "no_existe") {
              actualizarBotonUsuario(
                usuarioDiv,
                `<button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
                  Agregar Amigo <i class="bi bi-person-add"></i>
                </button>`
              );
              eliminarSolicitud(nickname);
            }
          }
        );
        break;

      case "agregar":
        ajaxPost(
          "/shakti/Controlador/solicitudesCtrl.php?agregarAmigo",
          { nickname },
          function (data) {
            if (data === "enviada") {
              actualizarBotonUsuario(
                usuarioDiv,
                `<button type="button" class="btn btn-warning btn-cancelar" data-nickname="${nickname}">
                  Cancelar Solicitud <i class="bi bi-x-circle"></i>
                </button>`
              );
            }
          }
        );
        break;

      case "cancelar":
        ajaxPost(
          "/shakti/Controlador/solicitudesCtrl.php?cancelarSolicitud",
          { nickname },
          function (data) {
            if (data === "cancelado") {
              actualizarBotonUsuario(
                usuarioDiv,
                `<button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
                  Agregar Amigo <i class="bi bi-person-add"></i>
                </button>`
              );
            }
          }
        );
        break;

      case "eliminar":
        actualizarBotonUsuario(
          usuarioDiv,
          `<button type="button" class="btn btn-banner-azul btn-agregar" data-nickname="${nickname}">
            Agregar Amigo <i class="bi bi-person-add"></i>
          </button>`
        );
        eliminarSolicitud(nickname);
        break;
    }
  }

  // Eventos en sidebar
  solicitudSidebar.addEventListener("click", (e) => {
    if (
      e.target.classList.contains("btn-banner-azul") &&
      e.target.textContent.includes("Aceptar")
    ) {
      manejarClick(e, "aceptar");
    }
    if (e.target.classList.contains("btn-banner-rojo")) {
      manejarClick(e, "rechazar");
    }
  });

  // Eventos en usuarios
  usuariosList.addEventListener("click", (e) => {
    if (e.target.classList.contains("btn-agregar")) manejarClick(e, "agregar");
    if (e.target.classList.contains("btn-cancelar"))
      manejarClick(e, "cancelar");
    if (
      e.target.classList.contains("btn-banner-azul") &&
      e.target.textContent.includes("Aceptar")
    ) {
      manejarClick(e, "aceptar");
    }
    if (e.target.classList.contains("btn-agregado"))
      manejarClick(e, "eliminar");
    if (e.target.classList.contains("btn-banner-rojo"))
      manejarClick(e, "rechazar");
  });
});
