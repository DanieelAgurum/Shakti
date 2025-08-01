import { ref, onValue, push, update, remove, query, limitToLast } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-database.js";
import { db } from "/Shakti/peticiones(js)/firebaseInit.js";

// Elementos del DOM
const mensajesContenedor = document.getElementById("mensajes");
const formChat = document.getElementById("form-chat");
const inputMensaje = document.getElementById("mensaje-input");

let chatId = null;
let idDestino = null;
let chatSeleccionadoManualmente = false;
let mensajesUnsubscribe = null; // 👈 Para limpiar el listener anterior

// Seleccionar usuaria o especialista
window.seleccionarEspecialista = function(idUsuarioDestino) {
  const nuevoChatId = generarChatId(window.usuarioActual.id, idUsuarioDestino);
  if (!idUsuarioDestino || !window.usuarioActual?.id) return console.error("ID inválido");
  if (chatId === nuevoChatId) return; // 👈 Ya estás en ese chat

  idDestino = idUsuarioDestino;
  chatId = nuevoChatId;
  chatSeleccionadoManualmente = true;

  mensajesContenedor.innerHTML = "";
  formChat.style.display = "block";
  cargarMensajes();
};

// Generar chatId determinista
function generarChatId(id1, id2) {
  if (!id1 || !id2) return null;
  return id1 < id2 ? `${id1}_${id2}` : `${id2}_${id1}`;
}

// Cargar mensajes
function cargarMensajes() {
  if (!chatId) return console.error("Chat ID no definido");

  if (mensajesUnsubscribe) mensajesUnsubscribe(); // 👈 Detener listener anterior

  const mensajesRef = query(ref(db, "chats/" + chatId), limitToLast(50)); // 👈 Solo últimos 50

  mensajesUnsubscribe = onValue(mensajesRef, (snapshot) => {
    mensajesContenedor.innerHTML = "";
    snapshot.forEach((child) => {
      const msg = child.val();
      const key = child.key;
      if (!msg || !msg.texto) return;

      const div = document.createElement("div");
      div.classList.add("mensaje", msg.remitenteId == window.usuarioActual.id ? "usuario" : "especialista");

      const p = document.createElement("p");
      p.textContent = msg.texto;
      div.appendChild(p);

      const hora = new Date(msg.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const spanHora = document.createElement("span");
      spanHora.className = "hora-msg text-muted small d-block";
      spanHora.textContent = hora;
      div.appendChild(spanHora);

      if (msg.remitenteId == window.usuarioActual.id) {
        const btnsDiv = document.createElement("div");
        btnsDiv.classList.add("msg-options");

        const btnEditar = document.createElement("button");
        btnEditar.textContent = "Editar";
        btnEditar.className = "btn btn-sm btn-link text-primary";
        btnEditar.onclick = () => editarMensaje(chatId, key, msg.texto);

        const btnEliminar = document.createElement("button");
        btnEliminar.textContent = "Eliminar";
        btnEliminar.className = "btn btn-sm btn-link text-danger";
        btnEliminar.onclick = () => eliminarMensaje(chatId, key);

        btnsDiv.appendChild(btnEditar);
        btnsDiv.appendChild(btnEliminar);
        div.appendChild(btnsDiv);
      }

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

  const mensajesRef = ref(db, "chats/" + chatId);
  push(mensajesRef, {
    texto,
    remitenteId: window.usuarioActual.id,
    remitenteRol: window.usuarioActual.rol,
    timestamp: Date.now()
  }).catch(error => console.error("Error enviando mensaje:", error));

  inputMensaje.value = "";
});

// Editar mensaje
function editarMensaje(chatId, msgKey, textoAnterior) {
  Swal.fire({
    title: 'Editar mensaje',
    input: 'text',
    inputValue: textoAnterior,
    showCancelButton: true,
    confirmButtonText: 'Guardar',
    cancelButtonText: 'Cancelar',
    inputValidator: (value) => {
      if (!value.trim()) return 'El mensaje no puede estar vacío';
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const msgRef = ref(db, `chats/${chatId}/${msgKey}`);
      update(msgRef, {
        texto: result.value.trim(),
        timestamp: Date.now()
      });
    }
  });
}

// Eliminar mensaje
function eliminarMensaje(chatId, msgKey) {
  Swal.fire({
    title: '¿Eliminar mensaje?',
    text: 'Esta acción no se puede deshacer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      const msgRef = ref(db, `chats/${chatId}/${msgKey}`);
      remove(msgRef);
    }
  });
}

// Eliminar todo el chat
window.eliminarChatCompleto = function () {
  if (!chatId) return;

  Swal.fire({
    title: '¿Eliminar toda la conversación?',
    text: 'Todos los mensajes se eliminarán de forma permanente.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar todo',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      const chatRef = ref(db, "chats/" + chatId);
      remove(chatRef);
      mensajesContenedor.innerHTML = "<p class='text-muted'>Charla eliminada.</p>";
    }
  });
};

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
  if (window.usuarioActual?.rol !== "2") return;

  const chatsRef = ref(db, "chats");
  onValue(chatsRef, async (snapshot) => {
    const chats = snapshot.val();
    const contenedor = document.getElementById('lista-especialistas');
    if (!contenedor) {
      console.error("Contenedor de lista-especialistas no encontrado");
      return;
    }
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

    const usuarias = await obtenerDatosUsuarias([...usuariasIdsSet]);
    if (!usuarias.length) {
      contenedor.innerHTML = '<p class="text-muted">No hay chats activos para mostrar.</p>';
      return;
    }

    contenedor.innerHTML = `<h5>Usuarias con chat activo</h5>`;

    usuarias.forEach(u => {
      const fotoUrl = u.foto ? `/Shakti/verFoto.php?id=${u.id}` : '/Shakti/img/usuario.jpg';

      const card = document.createElement('div');
      card.className = "card mb-3 card-chat-item";
      card.style.cursor = "pointer";
      card.onclick = () => window.seleccionarEspecialista(u.id);

      card.innerHTML = `
        <div class="row g-0 h-100 align-items-center">
          <div class="col-4 d-flex align-items-center justify-content-center">
            <img 
              src="${fotoUrl}" 
              class="img-thumbnail perfil-img rounded-circle" 
              alt="${u.nombre || 'Usuaria'}"
              onerror="this.onerror=null;this.src='/Shakti/img/usuario.jpg';"
              style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;"
            />
          </div>
          <div class="col-8">
            <div class="card-body py-2">
              <h6 class="card-title mb-1">${u.nombre || 'Sin nombre'}</h6>
              <p class="card-text mb-1 text-truncate">${u.descripcion || ''}</p>
              <small class="text-muted">Activo</small>
            </div>
          </div>
        </div>
      `;

      contenedor.appendChild(card);
    });

    if (usuarias.length > 0 && !chatSeleccionadoManualmente) {
      window.seleccionarEspecialista(usuarias[0].id);

      
    }
  });
}

if (window.usuarioActual?.rol === "2") {
  cargarChatsActivosEspecialista();
}
