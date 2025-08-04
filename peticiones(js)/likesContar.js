// Delegación para botones de like
document.addEventListener('click', e => {
    const btn = e.target.closest('.btn-like');
    if (!btn) return;

    const idPublicacion = btn.dataset.id;

    fetch('../../Controlador/likeControlador.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_publicacion=' + encodeURIComponent(idPublicacion)
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            Swal.fire({
                icon: 'info',
                title: 'Ups...',
                text: 'Únete a la comunidad para darle like y comentar',
                confirmButtonText: 'Iniciar sesión',
                confirmButtonColor: '#3085d6'
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = '/Vista/login.php';
                }
            });
            return;
        }

        // Actualizar contador
        const contador = btn.querySelector('.likes-count');
        contador.textContent = data.likes;

        // Cambiar clase del botón (estilo)
        btn.classList.toggle('btn-outline-danger');
        btn.classList.toggle('btn-danger');

        // Cambiar ícono
        const icon = btn.querySelector('.heart-icon');
        icon.classList.toggle('bi-suit-heart');
        icon.classList.toggle('bi-suit-heart-fill');

        // Animación
        icon.classList.add('animate-heart');
        setTimeout(() => icon.classList.remove('animate-heart'), 300);
    });
});
