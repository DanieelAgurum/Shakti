document.addEventListener("DOMContentLoaded", () => {
    // Mostrar respuestas ocultas
    document.querySelectorAll(".ver-respuestas").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            const divRespuestas = document.getElementById(`respuestas-${id}`);
            const isVisible = !divRespuestas.classList.contains("d-none");

            divRespuestas.classList.toggle("d-none");

            // Cambiar texto del botón
            btn.textContent = isVisible ? `Ver respuestas` : `Ocultar respuestas`;
        });
    });
});
