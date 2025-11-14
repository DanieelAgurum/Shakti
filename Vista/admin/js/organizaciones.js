$.fn.dataTable.ext.errMode = "none";

let offset = 0;
const limit = 6;
let cargando = false;
let finDatos = false;
let dataTable = null;

// === CARGAR INSTITUCIONES ===
async function cargarInstituciones(reset = false) {
  if (cargando || finDatos) return;
  cargando = true;

  try {
    const res = await fetch(
      `/shakti/Controlador/institucionesCtrl.php?opcion=4&offset=${offset}&limit=${limit}`
    );
    const data = await res.json();

    if (!data || !Array.isArray(data.datos) || data.datos.length === 0) {
      finDatos = true;
      return;
    }

    // Construimos los datos como arrays de columnas para DataTable
    const newRows = data.datos.map((inst, i) => {
      const index = offset + i + 1;
      return [
        index,
        inst.nombre,
        inst.descripcion,
        inst.telefono || "No disponible",
        `<a href="https://www.google.com/maps?q=${encodeURIComponent(
          inst.domicilio || ""
        )}" 
        target="_blank" class="text-decoration-none text-primary">
        ${inst.domicilio || "No especificado"}
    </a>`,

        // NUEVO: Link visible y clickeable
        `<a href="${inst.link || "#"}" target="_blank">
        ${inst.link ? "Visitar página" : "Sin link"}
     </a>`,

        `<img src="${inst.imagen}" alt="${inst.nombre}" class="img-fluid rounded shadow-sm" style="max-width: 90px; height: auto;">`,

        `<div class="text-center">
      <button class="btn btn-primary btn-sm btnEditar" 
        data-nombre="${inst.nombre}" 
        data-descripcion="${inst.descripcion}" 
        data-telefono="${inst.telefono || ""}" 
        data-domicilio="${inst.domicilio || ""}"
        data-link="${inst.link || ""}"
        data-registro_modificar="${inst.registro || ""}"
        >
        <i class="fa-solid fa-pen"></i> Editar
      </button>
      <button class="btn btn-danger btn-sm btnEliminar" 
        data-registro_modificar="${inst.registro || ""}"
        data-nombre="${inst.nombre}">
        <i class="fa-solid fa-trash"></i> Eliminar
      </button>
    </div>`,
      ];
    });

    if (reset && dataTable) {
      dataTable.clear().destroy();
      dataTable = null;
      offset = 0;
      finDatos = false;
    }

    if (!dataTable) {
      dataTable = $("#MiAgenda").DataTable({
        data: newRows,
        pageLength: 10,
        lengthChange: false,
        language: {
          search: "Buscar:",
          info: "Mostrando _START_ a _END_ de _TOTAL_ instituciones",
          infoEmpty: "Sin resultados",
          zeroRecords: "No se encontraron coincidencias",
          paginate: { next: "Siguiente", previous: "Anterior" },
        },
      });
    } else {
      dataTable.rows.add(newRows).draw();
    }

    offset += limit;
    setTimeout(() => cargarInstituciones(), 300);
  } catch (error) {
  } finally {
    cargando = false;
  }
}

// === AUTOCOMPLETADO OSM ===
function initAutocompleteOSM() {
  let timeout = null;
  let abortController = null;

  document.addEventListener("input", async (e) => {
    const input = e.target;
    if (!input.matches("input[id^='domicilio_'], #domicilio_agregar")) return;

    let suggestionBox = input.parentNode.querySelector(".suggestion-box");
    if (!suggestionBox) {
      suggestionBox = document.createElement("div");
      suggestionBox.classList.add("suggestion-box");
      Object.assign(suggestionBox.style, {
        position: "absolute",
        zIndex: "1000",
        background: "#fff",
        border: "1px solid #ccc",
        width: "100%",
        maxHeight: "140px",
        overflowY: "auto",
        borderRadius: "5px",
        boxShadow: "0 2px 6px rgba(0,0,0,0.1)",
      });
      input.parentNode.appendChild(suggestionBox);
    }

    clearTimeout(timeout);
    const query = input.value.trim();
    suggestionBox.innerHTML = "";
    if (query.length < 3) return;

    timeout = setTimeout(async () => {
      if (abortController) abortController.abort();
      abortController = new AbortController();

      suggestionBox.innerHTML = `<div class="text-center text-muted py-2">Buscando...</div>`;

      try {
        const res = await fetch(
          `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(
            query
          )}&format=json&addressdetails=1&limit=3`,
          {
            signal: abortController.signal,
            headers: { "Accept-Language": "es" },
          }
        );

        if (!res.ok) throw new Error("Error de red");
        const data = await res.json();

        if (!data.length) {
          suggestionBox.innerHTML = `<div class="text-muted px-2 py-1">Sin resultados</div>`;
          return;
        }

        suggestionBox.innerHTML = data
          .map(
            (item, i) => `
            <div class="suggestion-item py-1 px-2" data-index="${i}" style="cursor:pointer;">
              ${item.display_name}
            </div>`
          )
          .join("");

        const items = suggestionBox.querySelectorAll(".suggestion-item");
        let selectedIndex = -1;

        // Clic en una sugerencia
        items.forEach((el) => {
          el.addEventListener("click", () => {
            input.value = el.textContent.trim();
            suggestionBox.innerHTML = "";
          });
        });

        // Navegación con teclado
        input.addEventListener("keydown", (e) => {
          if (items.length === 0) return;

          if (e.key === "ArrowDown") {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % items.length;
            items.forEach((el, idx) =>
              el.classList.toggle("bg-light", idx === selectedIndex)
            );
            items[selectedIndex].scrollIntoView({ block: "nearest" });
          } else if (e.key === "ArrowUp") {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            items.forEach((el, idx) =>
              el.classList.toggle("bg-light", idx === selectedIndex)
            );
            items[selectedIndex].scrollIntoView({ block: "nearest" });
          } else if (e.key === "Enter" && selectedIndex >= 0) {
            e.preventDefault();
            input.value = items[selectedIndex].textContent.trim();
            suggestionBox.innerHTML = "";
          }
        });
      } catch (error) {
        if (error.name !== "AbortError") {
          suggestionBox.innerHTML = `<div class="text-danger px-2 py-1">Error al buscar</div>`;
        }
      }
    }, 300);
  });

  // Ocultar al perder foco
  document.addEventListener(
    "blur",
    (e) => {
      if (e.target.matches("input[id^='domicilio_'], #domicilio_agregar")) {
        const box = e.target.parentNode.querySelector(".suggestion-box");
        if (box) setTimeout(() => (box.innerHTML = ""), 150);
      }
    },
    true
  );
}

// === MODALES ===
document.addEventListener("click", (e) => {
  // Eliminar
  if (e.target.matches(".btnEliminar, .btnEliminar *")) {
    const btn = e.target.closest(".btnEliminar");
    const id = btn.dataset.registro_modificar;
    const nombre = btn.dataset.nombre;

    document.getElementById("nombreEliminar").textContent = nombre;

    const btnModal = document.getElementById("btnEliminarLink");
    btnModal.dataset.id = id;

    new bootstrap.Modal(document.getElementById("miModal")).show();
  }

  // Editar
  if (e.target.matches(".btnEditar, .btnEditar *")) {
    const btn = e.target.closest(".btnEditar");

    document.getElementById("nombre_modificar").value = btn.dataset.nombre;
    document.getElementById("descripcion_modificar").value =
      btn.dataset.descripcion;
    document.getElementById("numero_modificar").value =
      btn.dataset.telefono || "";
    document.getElementById("domicilio_modificar").value =
      btn.dataset.domicilio || "";
    document.getElementById("id_modificar").value =
      btn.dataset.registro_modificar;

    new bootstrap.Modal(document.getElementById("modificarModal")).show();
  }
});

// === ENVÍO FORMULARIOS ===
function enviarDatosModificados() {
  const form = document.getElementById("modificarOrganizacion");
  const datos = new FormData(form);

  $.ajax({
    type: "POST",
    url: "../../Controlador/institucionesCtrl.php?opcion=2",
    data: datos,
    processData: false,
    contentType: false,
    dataType: "json",
    success: (respuesta) => {
      let icono = "info";

      if (respuesta.opcion === "modifico") icono = "success";
      else if (respuesta.opcion === "error") icono = "warning";
      else if (respuesta.opcion === 1) icono = "success";
      else if (respuesta.opcion === 0) icono = "error";

      Swal.fire({
        icon: icono,
        title: "Aviso",
        text: respuesta.mensaje,
        confirmButtonText: "Aceptar",
      }).then(() => {
        if (respuesta.opcion === "modifico") {
          location.reload();
        }
      });
    },
    error: (xhr, status, error) => {},
  });
}

function enviarDatos(e) {
  e.preventDefault();
  const form = document.getElementById("agregarOrganizacion");
  const datos = new FormData(form);

  $.ajax({
    type: "POST",
    url: "../../Controlador/institucionesCtrl.php?opcion=1",
    data: datos,
    processData: false,
    contentType: false,
    dataType: "json",
    success: (respuesta) => {
      let icono = "info";

      if (respuesta.opcion === 1) icono = "success";
      else if (respuesta.opcion === 0) icono = "warning";

      Swal.fire({
        icon: icono,
        title: "Aviso",
        text: respuesta.mensaje,
        confirmButtonText: "Aceptar",
      }).then(() => {
        if (respuesta.opcion === 1) {
          location.reload();
        }
      });
    },
    error: (xhr, status, error) => {
      console.error("Error al agregar:", status, error, xhr.responseText);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo agregar la organización. Intente más tarde.",
      });
    },
  });
}

function eliminarDatos(id) {
  $.ajax({
    type: "POST",
    url: "../../Controlador/institucionesCtrl.php?opcion=3",
    data: { id },
    dataType: "json",
    success: (respuesta) => {
      let icono = "info";

      if (respuesta.opcion === "eliminado") icono = "success";
      else if (respuesta.opcion === "error") icono = "warning";
      else if (respuesta.opcion === 0) icono = "error";

      Swal.fire({
        icon: icono,
        title: "Aviso",
        text: respuesta.mensaje,
        confirmButtonText: "Aceptar",
      }).then(() => {
        if (respuesta.opcion === "eliminado") {
          location.reload();
        }
      });
    },
    error: (xhr, status, error) => {
      console.error("Error al eliminar:", status, error, xhr.responseText);
    },
  });
}

// === INICIALIZACIÓN ===
document.addEventListener("DOMContentLoaded", () => {
  const btnEliminar = document.getElementById("btnEliminarLink");
  initAutocompleteOSM();
  cargarInstituciones();

  btnEliminar.addEventListener("click", () => {
    const id = btnEliminar.dataset.id;
    eliminarDatos(id);
  });
});
