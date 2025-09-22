document.addEventListener('DOMContentLoaded', () => {
    const editBtn = document.getElementById('editFotoBtn');
    const fotoInput = document.getElementById('fotoInput');
    const imagenPreview = document.getElementById('imagenPreview');
    const editarFotoModalEl = document.getElementById('editarFotoModal');
    const fotoForm = document.getElementById('fotoForm');

    if (!editarFotoModalEl) return;
    const editarFotoModal = new bootstrap.Modal(editarFotoModalEl);

    editBtn.addEventListener('click', () => {
        fotoInput.click();
    });

    fotoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            imagenPreview.src = event.target.result;
        }
        reader.readAsDataURL(file);

        editarFotoModal.show();
    });
});