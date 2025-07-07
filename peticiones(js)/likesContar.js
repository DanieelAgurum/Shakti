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
                        alert(data.error);
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