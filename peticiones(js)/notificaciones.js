document.addEventListener('DOMContentLoaded', () => {
    const toastDuration = 6000; // ms
    const contador = document.getElementById('contadorNotificaciones');
    const modal = document.getElementById('modalNotificaciones');
    let idsMostrados = new Set();

    async function cargarNotificaciones() {
        try {
            const res = await fetch('/shakti/Controlador/notificacionesCtrl.php');
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();

            const config = data.config || { notificar_publicaciones: 1, notificar_comentarios: 1 };
            const notificaciones = data.notificaciones || [];

            if (Array.isArray(notificaciones) && notificaciones.length > 0) {
                let nuevas = [];
                let totalNuevas = 0;

                notificaciones.forEach(noti => {
                    if (!idsMostrados.has(noti.id)) {
                        idsMostrados.add(noti.id);

                        const esPublicacion = noti.tipo_notificacion === "publicacion";
                        const esComentario = noti.tipo_notificacion === "comentario";

                        if (
                            (esPublicacion && config.notificar_publicaciones == 1) ||
                            (esComentario && config.notificar_comentarios == 1)
                        ) {
                            nuevas.push(noti.mensaje);
                            totalNuevas++;
                        }
                    }
                });

                if (totalNuevas > 0) {
                    contador.style.display = "inline-block";
                    contador.textContent = notificaciones.length;

                    if (totalNuevas === 1) {
                        mostrarToast(`🔔 ${nuevas[0]}`);
                    } else {
                        mostrarToast(`🔔 Tienes ${totalNuevas} nuevas notificaciones`);
                    }
                }
            } else {
                contador.style.display = "none";
            }
        } catch (error) {
            console.error("Error cargando notificaciones:", error);
        }
    }

    // Mostrar Toast
    function mostrarToast(mensaje) {
        const fondo = mensaje.includes("Tienes")
            ? "linear-gradient(135deg, #27ae60, #5ee6b5)"
            : "linear-gradient(135deg, #f39c12, #f5c542)";

        const toast = Toastify({
            text: mensaje,
            duration: toastDuration,
            close: true,
            gravity: "bottom",
            position: "right",
            offset: { x: 10, y: 460 },
            style: {
                background: fondo,
                borderRadius: "12px",
                fontSize: "15px",
                color: "#fff",
                padding: "12px 16px",
                boxShadow: "0 3px 12px rgba(0, 0, 0, 0.25)",
                fontWeight: "500"
            },
            stopOnFocus: true,
            onClick: () => {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(modal);
                modalInstance.show();
            }
        });

        toast.showToast();
        window.toastActivo = toast;
    }

    modal.addEventListener('show.bs.modal', async () => {
        if (window.toastActivo) {
            const toastEl = document.querySelector('.toastify.on');
            if (toastEl) toastEl.remove();
            window.toastActivo = null;
        }

        try {
            const res = await fetch('/shakti/Controlador/notificacionesCtrl.php?marcarLeidas=1');
            if (res.ok) {
                contador.style.display = "none";
                console.log("✅ Notificaciones marcadas como leídas");
            }
        } catch (error) {
            console.error("Error al marcar como leídas:", error);
        }
    });

    cargarNotificaciones();
    setInterval(cargarNotificaciones, 10000);
});
