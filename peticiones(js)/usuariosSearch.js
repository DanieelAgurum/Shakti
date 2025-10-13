document.addEventListener("DOMContentLoaded", () => {
  const usuariosList = document.getElementById("usuariosGrid");
  const searchForm = document.querySelector(".search-box form");
  const searchInput = searchForm.querySelector("input[name='buscador']");
  const limite = 6;
  let paginaActual = 0;
  let cargando = false;
  let noHayMas = false;

  function showLoader(target) {
    // Quitar row si existe y mantener flex
    target.classList.remove("row");
    target.classList.add("usuario-flex");

    target.innerHTML = `
      <div class="loader-container">
        <div class="orbit"></div>
      </div>`;
  }

  async function fetchHTML(url) {
    try {
      const response = await fetch(url);
      if (!response.ok) throw new Error(`Error: ${response.status}`);
      return await response.text();
    } catch (error) {
      return null;
    }
  }

  async function cargarUsuarios(query = "", reset = true) {
    if (cargando) return;
    cargando = true;

    if (reset) {
      paginaActual = 0;
      noHayMas = false;
      showLoader(usuariosList); // animación de carga
    }

    const offset = paginaActual * limite;
    const url = `/shakti/Controlador/solicitudesCtrl.php?especialistas&limit=${limite}&offset=${offset}&buscador=${encodeURIComponent(
      query
    )}`;
    const htmlUsuarios = await fetchHTML(url);

    if (htmlUsuarios) {
      if (!htmlUsuarios.includes("No se encontraron especialistas")) {
        if (reset) {
          usuariosList.innerHTML = htmlUsuarios;
        } else {
          usuariosList.insertAdjacentHTML("beforeend", htmlUsuarios);
        }

        // Animación de los cards
        const cards = usuariosList.querySelectorAll(".testimonial-card");
        cards.forEach((card, index) => {
          card.classList.remove("animate__animated", "animate__backInUp");
          void card.offsetWidth; // reinicia animación
          card.classList.add("animate__animated", "animate__backInUp");
          card.style.animationDelay = `${index * 0.1}s`;
        });

        // Después de animar, aplicar layout final
        usuariosList.classList.remove("usuario-flex");
        usuariosList.classList.add("row");

        paginaActual++;
      } else {
        if (reset) {
          usuariosList.innerHTML = `<div class="col-12 text-center">No se encontraron especialistas</div>`;
        }
        noHayMas = true;
      }
    } else {
      usuariosList.innerHTML = `<div class="col-12 text-center">No se encontraron especialistas</div>`;
      noHayMas = true;
    }

    cargando = false;
  }

  // Inicial: cargar todos los usuarios
  cargarUsuarios("", true);

  // Buscar usuarios
  searchForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const query = searchInput.value.trim();
    cargarUsuarios(query, true); // animación de carga también
  });

  // Scroll infinito
  window.addEventListener("scroll", () => {
    if (
      window.innerHeight + window.scrollY >=
      document.body.offsetHeight - 200
    ) {
      cargarUsuarios(searchInput.value.trim(), false);
    }
  });
});
