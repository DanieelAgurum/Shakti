// --- FORMULARIO DE CREAR PUBLICACIÓN ---
const formPublicacion = document.querySelector('form[action*="publicacionControlador.php"][onsubmit]');
const btnPublicar = document.getElementById('btnPublicar');

if (formPublicacion && btnPublicar) {
    formPublicacion.addEventListener('submit', () => {
        btnPublicar.disabled = true;
        btnPublicar.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Enviando...
      `;
    });
}

// --- FORMULARIOS DE EDICIÓN DE PUBLICACIONES ---
document.querySelectorAll('.edit-form').forEach(form => {
    form.addEventListener('submit', () => {
        const btnGuardar = form.querySelector('.btn-guardar');
        if (btnGuardar) {
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = `
          <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
          Guardando...
        `;
        }
    });
});

// --- FORMULARIOS DE COMENTARIOS ---
document.querySelectorAll('.comment-form').forEach(form => {
    form.addEventListener('submit', () => {
        const btnEnviar = form.querySelector('.btn-enviar-comentario');
        if (btnEnviar) {
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = `
          <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
          Enviando...
        `;
        }
    });
});

$(document).ready(function () {
    $(document).on('click', '.btn-toggle-comments', function () {
        const id = $(this).data('id');
        const $commentsSection = $('#comments-' + id);

        if ($commentsSection.is(':visible')) {
            $commentsSection.slideUp(200, function () {
                $commentsSection.addClass('d-none');
            });
        } else {
            $commentsSection.removeClass('d-none').hide().slideDown(200);
        }
    });
});

document.querySelectorAll('.btn-toggle-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        document.getElementById(`titulo-text-${id}`).style.display = 'none';
        document.getElementById(`contenido-text-${id}`).style.display = 'none';
        document.getElementById(`edit-form-${id}`).classList.remove('d-none');
    });
});

document.querySelectorAll('.btn-cancel').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        document.getElementById(`edit-form-${id}`).classList.add('d-none');
        document.getElementById(`titulo-text-${id}`).style.display = '';
        document.getElementById(`contenido-text-${id}`).style.display = '';
    });
});