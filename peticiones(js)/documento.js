document.addEventListener("DOMContentLoaded", function () {
    const botones = document.querySelectorAll(".ver-documento");
    const modalEl = document.getElementById("modalDocumento");
    const iframe = document.getElementById("iframeDocumento");
    const modal = new bootstrap.Modal(modalEl);
    const modalTitle = document.getElementById("modalDocumentoLabel");

    botones.forEach(boton => {
        boton.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            const title = this.getAttribute("data-title");

            iframe.src = '/shakti/Modelo/ver_contenido.php?id_legal=' + id;

            modalTitle.textContent = title;

            modal.show();
        });
    });

    modalEl.addEventListener("hidden.bs.modal", () => {
        iframe.src = "";
    });
});