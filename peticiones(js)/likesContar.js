document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-like').forEach(btn => {
        btn.addEventListener('click', () => {
            const idPublicacion = btn.dataset.id;

            fetch('../../controlador/likeControlador.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id_publicacion=' + encodeURIComponent(idPublicacion)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Ups...',
                            text: 'Únete a la comunidad para darle like y comentar.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ir a iniciar sesión'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/Shakti/Vista/login.php';
                            }
                        });
                        return;
                    }

                    const contador = btn.querySelector('.likes-count');
                    contador.textContent = data.likes;
                    btn.classList.toggle('btn-outline-danger');
                    btn.classList.toggle('btn-danger');
                });
        });
    });
});