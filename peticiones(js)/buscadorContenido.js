document.getElementById("buscadorContenido").addEventListener("input", function () {
    const busqueda = this.value.toLowerCase().trim().split(" ");
    const cards = document.querySelectorAll(".contenido-card");
    let hayResultados = false; 
    
    cards.forEach(card => {
        const titulo = card.dataset.titulo.toLowerCase();
        const categoria = card.dataset.categoria.toLowerCase();
        const tipo = card.dataset.tipo.toLowerCase();

        let coincide = true;

        busqueda.forEach(palabra => {
            if (
                !titulo.includes(palabra) &&
                !categoria.includes(palabra) &&
                !tipo.includes(palabra)
            ) {
                coincide = false;
            }
        });

        card.parentElement.style.display = coincide ? "" : "none";

        if (coincide) hayResultados = true; 
    });

    const mensaje = document.getElementById("noResultados");
    if (!hayResultados) {
        mensaje.style.display = "block";
    } else {
        mensaje.style.display = "none";
    }
});
