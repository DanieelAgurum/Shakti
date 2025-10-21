document.addEventListener('DOMContentLoaded', () => {
    const toastDuration = 6000; // milisegundos
    const contador = document.getElementById('contadorNotificaciones');
    const modal = document.getElementById('modalNotificaciones');
    let idsMostrados = new Set();

    async function cargarNotificaciones() {
        try {
            const res = await fetch('/shakti/Controlador/notificacionesCtrl.php');
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(noti => {
                    if (!idsMostrados.has(noti.id)) {
                        idsMostrados.add(noti.id);
                        mostrarToast(noti.mensaje);
                    }
                });

                // Actualiza el contador
                contador.style.display = "inline-block";
                contador.textContent = data.length;
            } else {
                contador.style.display = "none";
            }
        } catch (error) {
            console.error("Error cargando notificaciones:", error);
        }
    }

    // Mostrar Toast con animación y acción al hacer clic
    function mostrarToast(mensaje) {
        Toastify({
            text: `🔔 ${mensaje}`,
            duration: toastDuration,
            close: true,
            gravity: "bottom",
            position: "right",
            offset: {
                x: 10,
                y: 480
            },
            style: {
                background: "#f5c542",
                borderRadius: "10px",
                fontSize: "15px",
                color: "#fff",
                padding: "12px 16px",
                boxShadow: "0 3px 10px rgba(0, 0, 0, 0.2)"
            },
            stopOnFocus: true,
            onClick: () => {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(modal);
                modalInstance.show(); // abre el modal
            }
        }).showToast();
    }


    // Marcar todas como leídas al abrir el modal
    modal.addEventListener('show.bs.modal', async () => {
        try {
            const res = await fetch('/shakti/Controlador/notificacionesCtrl.php?marcarLeidas=1');
            if (res.ok) {
                contador.style.display = "none";
                console.log("Notificaciones marcadas como leídas");
            }
        } catch (error) {
            console.error("Error al marcar como leídas:", error);
        }
    });

    // Carga inicial
    cargarNotificaciones();
    // Revisión automática cada 10 segundos
    setInterval(cargarNotificaciones, 10000);
});
