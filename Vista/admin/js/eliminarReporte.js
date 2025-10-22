document.addEventListener("DOMContentLoaded", function () {
  let offset = 0;
  let cargando = false; // Evita llamadas múltiples simultáneas
  let finDeReportes = false; // Controla si ya se cargaron todos

  const tablaReportes = document.querySelector("#tablaReportes");

  function cargarReportes() {
    if (cargando || finDeReportes) return;
    cargando = true;

    fetch(`/shakti/Controlador/reportesCtrl.php?opcion=1&offset=${offset}`)
      .then((res) => res.json())
      .then((data) => {
        console.log("Reportes cargados:", data);

        if (!data.sinDatos && data.html.trim() !== "") {
          tablaReportes.insertAdjacentHTML("beforeend", data.html);
          offset += 10;
        } else {
          if (offset === 0) {
            tablaReportes.insertAdjacentHTML(
              "beforeend",
              `<tr><td colspan="6" class="text-center text-muted p-3">No hay reportes disponibles.</td></tr>`
            );
          } else {
            console.log("✅ Ya se cargaron todos los reportes.");
          }
          finDeReportes = true;
        }
      })
      .catch((err) => console.error("Error al cargar reportes:", err))
      .finally(() => {
        cargando = false;
      });
  }

  cargarReportes();

  window.addEventListener("scroll", () => {
    if (
      window.innerHeight + window.scrollY >= document.body.offsetHeight - 50 &&
      !cargando &&
      !finDeReportes
    ) {
      cargarReportes();
    }
  });

  document.addEventListener("click", function (e) {
    if (e.target.closest(".btnEliminar")) {
      const button = e.target.closest(".btnEliminar");
      const id = button.dataset.id;
      const nombre = button.dataset.nombre;
      const contenido = button.dataset.contenido;

      document.getElementById("nombreUsuariaModal").textContent = nombre;
      document.getElementById("contenidoUsuariaModal").textContent = contenido;

      const link = `../../Controlador/reportesCtrl.php?opcion=3&id=${id}&tipo=${contenido}`;
      document.getElementById("btnEliminarLink").setAttribute("href", link);
    }
  });
});
