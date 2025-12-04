document.addEventListener('DOMContentLoaded', () => {
    const editBtn = document.getElementById('editFotoBtn'); // Botón que abre el modal
    const fotoInput = document.getElementById('fotoInput');
    const imagenPreview = document.getElementById('imagenPreview');
    const editarFotoModalEl = document.getElementById('editarFotoModal');
    const fotoForm = document.getElementById('fotoForm');

    if (!editarFotoModalEl) return;
    const editarFotoModal = new bootstrap.Modal(editarFotoModalEl);

    // Abrir modal al hacer click en el botón "editar foto"
    if (editBtn) {
        editBtn.addEventListener('click', () => {
            editarFotoModal.show();
        });
    }

    // Previsualizar imagen al seleccionar archivo
    fotoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            imagenPreview.src = event.target.result;
        }
        reader.readAsDataURL(file);
    });
});
