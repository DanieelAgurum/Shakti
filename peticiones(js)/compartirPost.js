
let idPublicacionCompartir = null;

function setIdCompartir(id) {
    idPublicacionCompartir = id;
}

async function generarLinkSeguro(idPublicacion) {
    const datos = `${idPublicacion}`;
    const hash = await sha256(datos);
    return `${window.location.origin}/Vista/usuaria/foro.php?publicacion=${hash}`;
}

async function sha256(mensaje) {
    const encoder = new TextEncoder();
    const data = encoder.encode(mensaje);
    const hashBuffer = await crypto.subtle.digest('SHA-256', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
}

async function compartirWhatsapp() {
    if (!idPublicacionCompartir) return;
    const url = await generarLinkSeguro(idPublicacionCompartir);
    const texto = encodeURIComponent(
        "¡Mira esta publicación!\n\n " + url
    );
    window.open(`https://wa.me/?text=${texto}`, '_blank');

}

async function compartirFacebook() {
    if (!idPublicacionCompartir) return;
    const url = await generarLinkSeguro(idPublicacionCompartir);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

async function compartirTwitter() {
    if (!idPublicacionCompartir) return;
    const url = await generarLinkSeguro(idPublicacionCompartir);
    const texto = encodeURIComponent("Revisa esta publicación que encontré en Shakti:");
    window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${texto}`, '_blank');
}
