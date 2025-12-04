document.addEventListener('DOMContentLoaded', () => {
    
    let baseUrl = window.location.origin;
    if (window.location.hostname === "localhost") baseUrl += "/shakti";

    const toastDuration = 6000;
    const contador = document.getElementById('contadorNotificaciones');
    const modal = document.getElementById('modalNotificaciones');
    const lista = document.getElementById('listaNotificaciones');
    let idsMostrados = new Set();

    let notiIntervalId = null;
    const POLL_INTERVAL_MS = 5000;

    function estaLogeado() {
        if (typeof window.sesionIniciada !== 'undefined') {
            return Boolean(window.sesionIniciada);
        }

        const bodyLogged = document.body?.dataset?.loggedIn;
        if (bodyLogged === "1" || bodyLogged === "true") return true;

        const usuarioEl = document.getElementById('usuarioLogged');
        if (usuarioEl && usuarioEl.dataset && (usuarioEl.dataset.id || usuarioEl.dataset.loggedIn)) {
            return true;
        }

        return false;
    }

    async function cargarNotificaciones() {
        try {
            const res = await fetch(`${baseUrl}/Controlador/notificacionesCtrl.php`, {
                credentials: 'include'
            });

            if (!res.ok) {
                if (res.status === 403 && notiIntervalId) {
                    clearInterval(notiIntervalId);
                    notiIntervalId = null;
                }
                return; 
            }

            const data = await res.json();

            const config = data.config || { notificar_publicaciones: 1, notificar_comentarios: 1 };
            const notificaciones = data.notificaciones || [];

            if (Array.isArray(notificaciones) && notificaciones.length > 0) {
                let nuevas = [];
                let totalNuevas = 0;
                let html = "";

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

                    html += `
                        <li class="list-group-item noti-item d-flex justify-content-between align-items-start ${noti.leida == 0 ? 'fw-bold bg-light' : ''}"
                            data-id="${noti.id_publicacion}" data-bs-placement="top" title="Ver publicación">
                            <div class="ms-2 me-auto">
                                ${noti.mensaje}<br>
                                <small class="text-dark noti">${new Date(noti.fecha_creacion).toLocaleString()}</small>
                            </div>
                            ${noti.leida == 0 ? '<span class="badge bg-danger rounded-pill">Nuevo</span>' : ''}
                        </li>`;
                });

                if (lista) lista.innerHTML = html;

                if (contador) {
                    if (totalNuevas > 0) {
                        contador.style.display = "inline-block";
                        contador.textContent = totalNuevas;
                        if (totalNuevas === 1) mostrarToast(`🔔 ${nuevas[0]}`);
                        else mostrarToast(`🔔 Tienes ${totalNuevas} nuevas notificaciones`);
                    } else {
                        contador.style.display = "none";
                    }
                }
            } else {
                if (contador) contador.style.display = "none";
                if (lista) lista.innerHTML = "<li class='list-group-item text-center noti'>No tienes notificaciones</li>";
            }
        } catch (error) {
        }
    }

    function mostrarToast(mensaje) {
        if (!modal) return;

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

    function configurarModal() {
        if (!modal) return;

        modal.addEventListener('show.bs.modal', async () => {
            if (window.toastActivo) {
                const toastEl = document.querySelector('.toastify.on');
                if (toastEl) toastEl.remove();
                window.toastActivo = null;
            }

            try {
                const res = await fetch(`${baseUrl}/Controlador/notificacionesCtrl.php?marcarLeidas=1`, {
                    credentials: 'include'
                });

                if (!res.ok) return;

                if (contador) contador.style.display = "none";

            } catch (error) {
            }
        });
    }

    if (estaLogeado()) {
        configurarModal();
        cargarNotificaciones();
        notiIntervalId = setInterval(cargarNotificaciones, POLL_INTERVAL_MS);
    }
});
