function dividirHTMLporBloques(html) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, "text/html");

    const bloques = Array.from(doc.body.children);
    const total = bloques.length;

    if (total === 0) return {
        parte1: "",
        parte2: "",
        parte3: ""
    };
    if (total === 1) return {
        parte1: bloques[0].outerHTML,
        parte2: "",
        parte3: ""
    };
    if (total === 2) return {
        parte1: bloques[0].outerHTML,
        parte2: bloques[1].outerHTML,
        parte3: ""
    };
    if (total === 3)
        return {
            parte1: bloques[0].outerHTML,
            parte2: bloques[1].outerHTML,
            parte3: bloques[2].outerHTML
        };

    // Más de 3 → dividir
    const t = Math.ceil(total / 3);
    let parte1 = "",
        parte2 = "",
        parte3 = "";

    bloques.forEach((b, i) => {
        if (i < t) parte1 += b.outerHTML;
        else if (i < t * 2) parte2 += b.outerHTML;
        else parte3 += b.outerHTML;
    });

    return {
        parte1,
        parte2,
        parte3
    };
}

function cargarArticulo(titulo, cuerpoHtml, img1, img2, img3) {
    document.getElementById("modalContenidoTitulo").innerHTML = titulo;

    const {
        parte1,
        parte2,
        parte3
    } = dividirHTMLporBloques(cuerpoHtml);

    const imagenes = [img1, img2, img3].filter(i => i && i !== "data:;base64,");
    let html = `<div class="container-fluid">`;

    if (imagenes.length === 0) {
        html += `
            <div class="row"><div class="col-12">${parte1}${parte2}${parte3}</div></div>
        `;
    } else if (imagenes.length === 1) {
        html += `
            <div class="row g-4">
                <div class="col-12">${parte1}${parte2}${parte3}</div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <img class="img-fluid rounded shadow" src="${imagenes[0]}">
                </div>
            </div>
        `;
    } else if (imagenes.length === 2) {
        html += `
            <div class="row g-4 bloque-contenido">
                <div class="col-lg-8">${parte1}</div>
                <div class="col-lg-4 text-center"><img class="img-fluid rounded shadow" src="${imagenes[0]}"></div>
            </div>

            <div class="row g-4 bloque-contenido mt-3">
                <div class="col-lg-4 text-center"><img class="img-fluid rounded shadow" src="${imagenes[1]}"></div>
                <div class="col-lg-8">${parte2}${parte3}</div>
            </div>
        `;
    } else if (imagenes.length === 3) {
        html += `
            <div class="row g-4 bloque-contenido">
                <div class="col-lg-8">${parte1}</div>
                <div class="col-lg-4 text-center"><img class="img-fluid rounded shadow" src="${imagenes[0]}"></div>
            </div>

            <div class="row g-4 bloque-contenido mt-3">
                <div class="col-lg-4 text-center"><img class="img-fluid rounded shadow" src="${imagenes[1]}"></div>
                <div class="col-lg-8">${parte2}</div>
            </div>

            <div class="row bloque-contenido mt-4">
                <div class="col-lg-8">${parte3}</div>
                <div class="col-lg-4 text-center"><img class="img-fluid rounded shadow" src="${imagenes[2]}"></div>
            </div>
        `;
    }

    html += `</div>`;

    document.getElementById("modalContenidoBody").innerHTML = html;
    new bootstrap.Modal(document.getElementById("modalContenido")).show();
}

function cargarVideo(titulo, url) {

    let embedUrl = url;

    // Convertir un link normal de YouTube a formato embed
    if (url.includes("youtube.com/watch?v=")) {
        const id = url.split("v=")[1];
        embedUrl = "https://www.youtube.com/embed/" + id;
    }

    document.getElementById("modalContenidoTitulo").innerHTML = titulo;
    document.getElementById("modalContenidoBody").innerHTML = `
            <div class="d-flex justify-content-center">
            <div style="width: 80%; max-width: 800px;">
                <div class="ratio ratio-16x9">
                    <iframe src="${embedUrl}" class="rounded shadow" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        `;
    new bootstrap.Modal(document.getElementById("modalContenido")).show();
}

function cargarInfografia(titulo, archivoBase64) {

    document.getElementById("modalContenidoTitulo").innerHTML = titulo;

    if (!archivoBase64 || archivoBase64 === "null") {
        document.getElementById("modalContenidoBody").innerHTML =
            `<p class="text-danger fw-bold">No hay archivo PDF disponible.</p>`;
    } else {
        document.getElementById("modalContenidoBody").innerHTML = `
            <iframe
                src="${archivoBase64}"
                style="width:100%; height:100vh;"
                class="rounded shadow">
            </iframe>
        `;
    }

    new bootstrap.Modal(document.getElementById("modalContenido")).show();
}

document.querySelectorAll(".contenido-link").forEach(btn => {
    btn.addEventListener("click", function (e) {
        e.preventDefault();

        const tipo = this.dataset.tipo;

        if (tipo === "articulo") {
            cargarArticulo(
                this.dataset.titulo,
                this.dataset.cuerpo,
                this.dataset.img1,
                this.dataset.img2,
                this.dataset.img3
            );
        }

        if (tipo === "video") {
            cargarVideo(this.dataset.titulo, this.dataset.url);
        }

        if (tipo === "infografia") {
            cargarInfografia(this.dataset.titulo, this.dataset.archivo);
        }
    });
});

document.getElementById("modalContenido").addEventListener("hidden.bs.modal", function () {
    document.getElementById("modalContenidoBody").innerHTML = "";
    document.getElementById("modalContenidoTitulo").innerHTML = "";
});