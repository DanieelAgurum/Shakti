let offset = 0;
const limit = 6;
let cargando = false;
let finDatos = false;

async function cargarInstituciones() {
  if (cargando || finDatos) return;
  cargando = true;

  try {
    const res = await fetch(
      `/shakti/Controlador/institucionesCtrl.php?opcion=4&offset=${offset}&limit=${limit}`
    );
    const data = await res.json();

    if (
      !data ||
      data.sinDatos ||
      !Array.isArray(data.datos) ||
      data.datos.length === 0
    ) {
      if (offset === 0) {
        document.querySelector(".recurso-tarjetas-fila").innerHTML = `
          <p class="text-center text-muted mt-4 w-100">No hay instituciones registradas.</p>`;
      } else {
        finDatos = true;
      }
      return;
    }

    const contenedorTarjetas = document.querySelector(".recurso-tarjetas-fila");
    const contenedorModales =
      document.querySelector("#modalesContainer") || crearContenedorModales();

    let cardsHTML = "";
    let modalesHTML = "";

    data.datos.forEach((inst) => {
      const token = inst.token;

      cardsHTML += `
        <div class="col">
          <div class="recurso-card-base card h-100 shadow-sm">
            <div class="recurso-card-img-container">
              <img src="${inst.imagen}" class="recurso-card-img-top card-img-top" alt="Imagen representativa de ${inst.nombre}" style="max-width: auto; height: 180px;">
            </div>
            <div class="recurso-card-body card-body d-flex flex-column">
              <h5 class="recurso-card-titulo card-title">${inst.nombre}</h5>
              <p class="recurso-card-texto-breve card-text text-muted">${inst.descripcion}</p>
              <button type="button" class="recurso-card-boton btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#${token}">
                Ver más...
              </button>
            </div>
          </div>
        </div>
      `;

      modalesHTML += `
        <div class="modal fade custom-config-modal" id="${token}" tabindex="-1" aria-labelledby="${token}Label" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content recurso-modal-contenido">
              <div class="modal-header recurso-modal-header">
                <h5 class="modal-title recurso-modal-titulo" id="${token}Label">${inst.nombre}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body recurso-modal-body">
                <div class="row">
                  <div class="col-md-4 text-center mb-3">
                    <img src="${inst.imagen}" class="recurso-modal-img img-fluid rounded" alt="Imagen ampliada de ${inst.nombre}">
                    <span class="badge bg-success recurso-badge-costo">${inst.costo || "Costo Accesible"}</span>
                    <span class="badge bg-primary recurso-badge-servicio">${inst.servicio || "Atención General"}</span>
                  </div>
                  <div class="col-md-8 recurso-modal-texto-contenido">
                    <p class="recurso-modal-subtitulo fw-bold">${inst.descripcion || inst.descripcion}</p>
                    <p>${inst.descripcion || "Información detallada no disponible."}</p>

                    <h6 class="mt-4 recurso-modal-seccion-titulo">Domicilio:</h6>
                <p>
                <a href="https://www.google.com/maps?q=${encodeURIComponent(inst.domicilio)}" target="_blank" class="text-decoration-none text-primary">
                ${inst.domicilio || "No especificado"}
                </a>                
                </p>
                    <h6 class="mt-4 recurso-modal-seccion-titulo">Teléfonos:</h6>
                    <ul>
                      <li><a href="tel:${inst.telefono}" class="fw-bold text-success">${inst.telefono || "No disponible"}</a></li>
                    </ul>

                    ${
                      inst.link
                        ? `<p class="mt-3">
                            <a href="${inst.link}" target="_blank" class="recurso-modal-boton-web btn btn-sm btn-outline-secondary">
                              Visitar Sitio Web
                            </a>
                          </p>`
                        : ""
                    }
                  </div>
                </div>
              </div>
              <div class="modal-footer recurso-modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>`;
    });

    contenedorTarjetas.insertAdjacentHTML("beforeend", cardsHTML);
    contenedorModales.insertAdjacentHTML("beforeend", modalesHTML);

    offset += limit;    if (data.datos.length > 0) {
      setTimeout(() => cargarInstituciones(), 300);
    } else {
      finDatos = true;
    }
  } catch (error) {
    console.error("Error al cargar instituciones:", error);
  } finally {
    cargando = false;
  }
}

function crearContenedorModales() {
  const div = document.createElement("div");
  div.id = "modalesContainer";
  document.body.appendChild(div);
  return div;
}

document.addEventListener("DOMContentLoaded", () => {
  cargarInstituciones();
});