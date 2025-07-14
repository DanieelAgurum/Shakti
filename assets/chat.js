import { ref, onValue, push } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-database.js";

const mensajesContenedor = document.getElementById("mensajes");
const formChat = document.getElementById("form-chat");
const inputMensaje = document.getElementById("mensaje-input");

let chatId = null;
let idDestino = null;

// Función para seleccionar un usuario
window.seleccionarEspecialista = function(idUsuarioDestino) {
  idDestino = idUsuarioDestino;
  chatId = generarChatId(window.usuarioActual.id, idDestino);
  mensajesContenedor.innerHTML = "";
  formChat.style.display = "block";
  cargarMensajes();
};

// Generar chatId determinista
function generarChatId(id1, id2) {
  return id1 < id2 ? `${id1}_${id2}` : `${id2}_${id1}`;
}

// Cargar mensajes en tiempo real
function cargarMensajes() {
  if (!chatId) return;
  const mensajesRef = ref(window.firebaseDB, "chats/" + chatId);

  onValue(mensajesRef, (snapshot) => {
    mensajesContenedor.innerHTML = "";
    snapshot.forEach((child) => {
      const msg = child.val();
      if (!msg || !msg.texto) return;
      const div = document.createElement("div");
      div.classList.add("mensaje");
      div.classList.add(msg.remitenteId == window.usuarioActual.id ? "usuario" : "especialista");
      div.textContent = msg.texto;
      mensajesContenedor.appendChild(div);
    });
    mensajesContenedor.scrollTop = mensajesContenedor.scrollHeight;
  });
}

// Enviar mensaje
formChat.addEventListener("submit", (e) => {
  e.preventDefault();
  const texto = inputMensaje.value.trim();
  if (!texto || !chatId) return;

  const mensajesRef = ref(window.firebaseDB, "chats/" + chatId);

  push(mensajesRef, {
    texto,
    remitenteId: window.usuarioActual.id,
    remitenteRol: window.usuarioActual.rol,
    timestamp: Date.now()
  }).catch(error => console.error("Error enviando mensaje:", error));

  inputMensaje.value = "";
});

// Obtener datos de usuarias desde PHP
async function obtenerDatosUsuarias(ids) {
  if (!ids || ids.length === 0) return [];
  try {
    const res = await fetch('/Shakti/api/usuariasPorIds.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ ids })
    });
    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

    const text = await res.text();
    if (!text) return [];

    return text.trim().split('\n').map(line => {
      const partes = line.split('|');
      if (partes.length < 4) return null;
      const [id, nombre, descripcion, foto] = partes.map(p => p.trim());
      return { id, nombre, descripcion, foto };
    }).filter(Boolean);
  } catch (error) {
    console.error("Error al obtener usuarias:", error);
    return [];
  }
}

// Cargar chats activos si es especialista
function cargarChatsActivosEspecialista() {
  if (window.usuarioActual.rol !== "2") return;

  const chatsRef = ref(window.firebaseDB, "chats");
  onValue(chatsRef, async (snapshot) => {
    const chats = snapshot.val();
    const contenedor = document.getElementById('lista-especialistas');
    contenedor.innerHTML = "";

    if (!chats) {
      contenedor.innerHTML = '<p class="text-muted">No hay chats activos para mostrar.</p>';
      return;
    }

    const usuariasIdsSet = new Set();

    for (const chatKey in chats) {
      if (chatKey.includes(window.usuarioActual.id)) {
        const ids = chatKey.split("_");
        const usuariaId = ids[0] === window.usuarioActual.id ? ids[1] : ids[0];
        usuariasIdsSet.add(usuariaId);
      }
    }

    const usuariasIds = Array.from(usuariasIdsSet);

    if (usuariasIds.length === 0) {
      contenedor.innerHTML = '<p class="text-muted">No hay chats activos para mostrar.</p>';
      return;
    }

    const usuarias = await obtenerDatosUsuarias(usuariasIds);

    if (!usuarias.length) {
      contenedor.innerHTML = '<p class="text-muted">No hay chats activos para mostrar.</p>';
      return;
    }

    contenedor.innerHTML = `<h5>Usuarias con chat activo</h5>`;

    usuarias.forEach(u => {
      const fotoUrl = u.foto ? `/Shakti/verFoto.php?id=${u.id}` : '/Shakti/assets/img/default.png';

      const card = document.createElement('div');
      card.className = "card mb-3";
      card.style = "max-width: 540px; cursor: pointer;";
      card.onclick = () => window.seleccionarEspecialista(u.id);

      card.innerHTML = `
        <div class="row g-0 align-items-center">
          <div class="col-md-4">
            <img src="${fotoUrl}" class="img-fluid rounded-start img-thumbnail" alt="${u.nombre || 'Usuaria'}" onerror="this.src='/Shakti/assets/img/default.png'" />
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h6 class="card-title mb-1">${u.nombre || 'Sin nombre'}</h6>
              <p class="card-text mb-1">${u.descripcion || ''}</p>
              <small class="text-muted">Activo</small>
            </div>
          </div>
        </div>
      `;
      contenedor.appendChild(card);
    });

    // Seleccionar automáticamente el primero
    window.seleccionarEspecialista(usuarias[0].id);
  });
}

if (window.usuarioActual.rol === "2") {
  cargarChatsActivosEspecialista();
}
