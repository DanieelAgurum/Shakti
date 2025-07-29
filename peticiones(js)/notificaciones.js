import { ref, onValue, set } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-database.js";
import { db } from "/Shakti/peticiones(js)/firebaseInit.js";

document.addEventListener("DOMContentLoaded", () => {
  const usuario = window.usuarioActual;
  if (!usuario?.id || !db) {
    console.error("Usuario no definido o Firebase no inicializado");
    return;
  }

  const notiContenedor = document.getElementById("listaNotificaciones");
  const contador = document.getElementById("contadorNotificaciones");
  const modal = document.getElementById("modalNotificaciones");

  if (!notiContenedor || !contador || !modal) {
    console.error("Faltan elementos del DOM");
    return;
  }

  let toastActual = null;
  const mensajesMostrados = new Set();
  const mensajesNoLeidos = [];
  let modalAbierto = false;
  let yaInicializado = false;

  modal.addEventListener('show.bs.modal', () => {
    modalAbierto = true;

    mensajesNoLeidos.forEach(({ chatId, msgId }) => {
      const leidoRef = ref(db, `chats/${chatId}/${msgId}/leidoPor/${usuario.id}`);
      set(leidoRef, true);
    });

    mensajesNoLeidos.length = 0;
    contador.style.display = "none";

    if (notiContenedor.children.length === 0) {
      notiContenedor.innerHTML = '<p class="text-muted text-center m-0">No tienes notificaciones.</p>';
    }
  });

  modal.addEventListener('hidden.bs.modal', () => {
    modalAbierto = false;
  });

  // Función para obtener datos de usuarios desde PHP (similar a la que tienes en chat)
  async function obtenerDatosUsuarios(ids) {
    if (!ids || ids.length === 0) return {};
    try {
      const res = await fetch('/Shakti/api/usuariasPorIds.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ids })
      });
      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
      const text = await res.text();
      if (!text) return {};

      // El endpoint devuelve líneas con: id|nombre|descripcion|foto
      const mapUsuarios = {};
      text.trim().split('\n').forEach(line => {
        const partes = line.split('|');
        if (partes.length >= 2) {
          const id = partes[0].trim();
          const nombre = partes[1].trim();
          mapUsuarios[id] = nombre;
        }
      });
      return mapUsuarios;
    } catch (error) {
      console.error("Error al obtener datos de usuarios:", error);
      return {};
    }
  }

  const chatsRef = ref(db, 'chats');
  onValue(chatsRef, async (snapshot) => {
    if (!snapshot.exists()) return;

    let mensajesNuevos = 0;
    const nuevosMensajes = [];
    const remitentesIdsSet = new Set();

    snapshot.forEach(chatSnap => {
      const chatId = chatSnap.key;
      if (!chatId.includes(usuario.id)) return;

      chatSnap.forEach(msgSnap => {
        const mensaje = msgSnap.val();
        const msgId = msgSnap.key;

        const yaLeido = mensaje.leidoPor && mensaje.leidoPor[usuario.id];
        const esParaUsuario = mensaje.remitenteId !== usuario.id;

        if (esParaUsuario && !yaLeido && !mensajesMostrados.has(msgId)) {
          mensajesMostrados.add(msgId);
          mensajesNuevos++;
          mensajesNoLeidos.push({ chatId, msgId });
          remitentesIdsSet.add(mensaje.remitenteId);

          // Guardamos datos básicos para luego agregar nombre real
          nuevosMensajes.push({
            texto: mensaje.texto,
            remitenteId: mensaje.remitenteId,
            nombre: null // Aquí pondremos el nombre luego
          });
        }
      });
    });

    if (mensajesNuevos > 0) {
      contador.style.display = "inline-block";
      contador.textContent = mensajesNuevos;

      // Pedimos nombres reales de los remitentes
      const mapaNombres = await obtenerDatosUsuarios(Array.from(remitentesIdsSet));

      notiContenedor.innerHTML = "";
      nuevosMensajes.forEach(msg => {
        const nombreReal = mapaNombres[msg.remitenteId] || "Desconocido";
        msg.nombre = nombreReal;

        const div = document.createElement("div");
        div.className = "alert alert-warning py-1 my-1";
        div.textContent = `📩 ${nombreReal} te escribió: "${msg.texto}"`;
        notiContenedor.appendChild(div);

        setTimeout(() => {
          if (div.parentNode) div.remove();
          if (notiContenedor.children.length === 0) {
            notiContenedor.innerHTML = '<p class="text-muted text-center m-0">No tienes notificaciones.</p>';
            contador.style.display = "none";
          }
        }, 60 * 1000);
      });

      if (yaInicializado && !modalAbierto) {
        const { texto, nombre } = nuevosMensajes[nuevosMensajes.length - 1];
        if (toastActual) toastActual.hideToast();

        toastActual = Toastify({
          text: `📩 ${nombre} te escribió: "${texto}"`,
          duration: 60 * 1000,
          close: true,
          gravity: "top",
          position: "right",
          style: { background: "#a442b2" },
          stopOnFocus: true,
        });
        toastActual.showToast();
      }
    }

    if (notiContenedor.children.length === 0 && mensajesNuevos === 0) {
      contador.style.display = "none";
      notiContenedor.innerHTML = '<p class="text-muted text-center m-0">No tienes notificaciones.</p>';
    }

    yaInicializado = true;
  });
});
