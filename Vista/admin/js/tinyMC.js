document.addEventListener('DOMContentLoaded', function () {
    // Previene conflictos de foco entre modales y TinyMCE
    document.addEventListener('focusin', function (e) {
        if (
            e.target.closest('.tox-tinymce-aux') ||
            e.target.closest('.tox-tinymce') ||
            e.target.closest('.moxman-window') ||
            e.target.closest('.tam-assetmanager-root')
        ) {
            e.stopImmediatePropagation();
        }
    });

    const agregarModal = document.getElementById('exampleModal');
    const modificarModal = document.getElementById('modificarModal');

    agregarModal.addEventListener('shown.bs.modal', function () {
        // Inicializa TinyMCE para el textarea de agregar, si no existe
        if (!tinymce.get('descripcion')) {
            tinymce.init({
                selector: '#descripcion',
                height: 300,
                plugins: 'lists link autolink',
                toolbar: 'undo redo | bold italic | bullist numlist | link',
                branding: false,
                setup: function (editor) {
                    editor.on('init', function () {
                        const container = editor.getContainer();
                        if (container.closest('.modal')) {
                            container.closest('.modal').addEventListener('focusin', function (e) {
                                if (
                                    e.target.closest('.tox-tinymce-aux') ||
                                    e.target.closest('.tox-tinymce')
                                ) {
                                    e.stopImmediatePropagation();
                                }
                            });
                        }
                    });
                },
            });
        }
    });

    agregarModal.addEventListener('hidden.bs.modal', function () {
        // Destruye el editor TinyMCE para liberar recursos y limpiar el textarea
        if (tinymce.get('descripcion')) {
            tinymce.get('descripcion').remove();
        }
    });

    modificarModal.addEventListener('shown.bs.modal', function () {
        // Inicializa TinyMCE para el textarea modificar si no está ya inicializado
        if (!tinymce.get('descripcionModificado')) {
            tinymce.init({
                selector: '#descripcionModificado',
                height: 300,
                plugins: 'lists link autolink',
                toolbar: 'undo redo | bold italic | bullist numlist | link',
                branding: false,
                setup: function (editor) {
                    editor.on('init', function () {
                        const container = editor.getContainer();
                        if (container.closest('.modal')) {
                            container.closest('.modal').addEventListener('focusin', function (e) {
                                if (
                                    e.target.closest('.tox-tinymce-aux') ||
                                    e.target.closest('.tox-tinymce')
                                ) {
                                    e.stopImmediatePropagation();
                                }
                            });
                        }
                    });
                },
            });
        }
    });

    modificarModal.addEventListener('hidden.bs.modal', function () {
        // Destruye el editor TinyMCE modificar para reinicialización limpia al abrir
        if (tinymce.get('descripcionModificado')) {
            tinymce.get('descripcionModificado').remove();
        }
    });
});