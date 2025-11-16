// JS no necesita cambios para el hover, solo asegura que las cards con link tengan overlay y clic
let offset = 0;
const limit = 6;
let cargando = false;
let finDatos = false;

async function cargarInstituciones() {
  if (cargando || finDatos) return;
  cargando = true;

  try {
    const res = await fetch(`/shakti/Controlador/institucionesCtrl.php?opcion=4&offset=${offset}&limit=${limit}`);
    const data = await res.json();

    if (!data || data.sinDatos || !Array.isArray(data.datos) || data.datos.length === 0) {
      if (offset === 0) {
        document.querySelector(".recurso-tarjetas-fila").innerHTML = `
          <p class="text-center text-muted mt-4 w-100">No hay instituciones registradas.</p>`;
      } else {
        finDatos = true;
      }
      return;
    }

    const contenedorTarjetas = document.querySelector(".recurso-tarjetas-fila");
    let cardsHTML = "";

    data.datos.forEach((inst) => {
      const hasLink = inst.link && inst.link.trim() !== "";

      cardsHTML += `
<div class="card-list">
  <article class="card">
    <figure class="card-image" ${hasLink ? `data-link="${inst.link}"` : ""}>
      <img src="${inst.imagen}" alt="Imagen de ${inst.nombre}">
      ${
        hasLink
          ? `<div class="card-visit-overlay" onclick="window.open('${inst.link}', '_blank')">Visitar sitio web</div>`
          : ""
      }
    </figure>

    <div class="card-header">
      ${
        hasLink
          ? `<a class="card-title enlace" href="${inst.link}" target="_blank" rel="noopener noreferrer">${inst.nombre}</a>`
          : `<span class="card-title">${inst.nombre}</span>`
      }
      <div class="descripcion-alta">${inst.descripcion}</div>
    </div>

    <div class="card-footer">
      ${
        inst.domicilio && inst.domicilio.trim() !== ""
          ? `<div class="card-meta direccion">
               <a class="enlace" href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(inst.domicilio)}" target="_blank">📍 ${inst.domicilio}</a>
             </div>`
          : ""
      }
      ${
        inst.telefono && inst.telefono.trim() !== ""
          ? `<div class="card-meta telefono">
               <a class="enlace" href="tel:${inst.telefono}">📞 ${inst.telefono}</a>
             </div>`
          : ""
      }
    </div>
  </article>
</div>`;
    });

    contenedorTarjetas.insertAdjacentHTML("beforeend", cardsHTML);

    // Configurar overlay y clic en la imagen
    contenedorTarjetas.querySelectorAll(".card-image[data-link]").forEach((imagen) => {
      const link = imagen.dataset.link;
      const overlay = imagen.querySelector(".card-visit-overlay");
      if (link) {
        imagen.style.cursor = "pointer";
        imagen.addEventListener("click", () => window.open(link, "_blank"));
        if (overlay) overlay.style.display = "flex";
      }
    });

    offset += limit;
    if (data.datos.length > 0) setTimeout(() => cargarInstituciones(), 300);
    else finDatos = true;

  } catch (error) {
    console.error("Error al cargar instituciones:", error);
  } finally {
    cargando = false;
  }
}

document.addEventListener("DOMContentLoaded", () => cargarInstituciones());
