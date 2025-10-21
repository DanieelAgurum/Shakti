document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const hash = params.get("publicacion");

    if (hash) {
        const observer = new MutationObserver(() => {
            const post = document.querySelector(`[data-hash="${hash}"]`);
            if (post) {
                post.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });
                post.classList.add("resaltado-post");
                observer.disconnect();
            }
        });

        observer.observe(document.getElementById("contenedorPublicaciones"), {
            childList: true,
            subtree: true
        });
    }
});