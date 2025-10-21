document.querySelectorAll(".noti-item").forEach(item => {
    item.addEventListener("click", async () => {
        const idPublicacion = item.dataset.id;
        if (!idPublicacion) return;

        const encoder = new TextEncoder();
        const data = encoder.encode(idPublicacion);
        const hashBuffer = await crypto.subtle.digest("SHA-256", data);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        const hash = hashArray.map(b => b.toString(16).padStart(2, "0")).join("");

        // Redirige al foro con el hash
        window.location.href = `/shakti/Vista/usuaria/foro.php?publicacion=${hash}`;
    });
});