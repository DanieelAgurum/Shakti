import { ref, onValue } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-database.js";
import { db } from "/Shakti/peticiones(js)/firebaseInit.js";

document.addEventListener("DOMContentLoaded", () => {
  const usuario = window.usuarioActual;

  if (!usuario?.id) {
    console.error("Usuario no definido o sin ID");
    return;
  }
  if (!db) {
    console.error("Firebase DB no inicializado");
    return;
  }

  const notiContenedor = document.getElementById("listaNotificaciones");
  const contador = document.getElementById("contadorNotificaciones");

  if (!notiContenedor || !contador) {
    console.error("No se encontró el contenedor o contador de notificaciones");
    return;
  }

  let mensajesNuevos = 0;
  let toastActual = null;  // Referencia al toast actual para ocultarlo antes de mostrar otro

  const chatsRef = ref(db, 'chats');
  onValue(chatsRef, (snapshot) => {
    notiContenedor.innerHTML = "";
    mensajesNuevos = 0;
    let ultimaNotificacion = null;

    snapshot.forEach((chatSnap) => {
      const chatId = chatSnap.key;
      if (!chatId.includes(usuario.id)) return;

      chatSnap.forEach((msgSnap) => {
        const mensaje = msgSnap.val();

        if (mensaje.remitenteId !== usuario.id) {
          mensajesNuevos++;
          ultimaNotificacion = mensaje;

          // Crear la notificación en el modal
          const li = document.createElement("li");
          li.className = "dropdown-item";
          li.textContent = `📩 Nuevo mensaje: "${mensaje.texto}"`;
          notiContenedor.appendChild(li);

          // Quitar esta notificación del modal después de 5 minutos
          setTimeout(() => {
            if (li.parentNode) {
              li.parentNode.removeChild(li);

              // Si no quedan notificaciones, mostrar mensaje por defecto
              if (notiContenedor.children.length === 0) {
                notiContenedor.innerHTML = '<li class="dropdown-item text-muted">No tienes notificaciones.</li>';
                contador.style.display = "none";
              }
            }
          }, 5 * 60 * 1000); // 5 minutos
        }
      });
    });

    // Mostrar u ocultar contador
    if (mensajesNuevos > 0) {
      contador.style.display = "inline-block";
      contador.textContent = mensajesNuevos;
    } else {
      contador.style.display = "none";
      notiContenedor.innerHTML = '<li class="dropdown-item text-muted">No tienes notificaciones.</li>';
    }

    // Mostrar solo un toast con la última notificación
    if (ultimaNotificacion) {
      if (toastActual) {
        toastActual.hideToast();
        toastActual = null;
      }

      toastActual = Toastify({
        text: `📩 Nuevo mensaje: "${ultimaNotificacion.texto}"`,
        duration: 300000, // 5 minutos
        close: true,
        gravity: "top",
        position: "right",
        style: { background: "#4caf50" },
        stopOnFocus: true,
      });
      toastActual.showToast();
    }
  });
});
