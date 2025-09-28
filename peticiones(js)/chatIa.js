document.addEventListener("DOMContentLoaded", () => {
  const chatList = document.getElementById("chat-list");
  const chatMensajes = document.querySelector(".chat-mensajes");
  const idUsuario = Number(document.getElementById("id_usuaria").value);

  const formulario = document.getElementById("formulario");
  const mensajeInput = document.getElementById("mensaje");
  const archivoInput = document.getElementById("archivo");
  const inputReceptor = document.getElementById("id_receptor");
  const btnEnviar = formulario?.querySelector("button[type=submit]");
  const chatIa = document.querySelector('[data-id-amigo="0"]');

  let canalPusher = null;
  let idAmigoActual = null;
  let esperandoRespuesta = false;

  // ========================
  //  CHAT CON USUARIOS (Pusher)
  // ========================
  const pusher = new Pusher("0469c2ac8ae0b818938a", {
    cluster: "us2",
    encrypted: true,
  });

  function agregarChatNuevo(usuario) {
    const div = document.createElement("div");
    div.className = "chat-activo";
    div.dataset.idAmigo = usuario.id;
    div.innerHTML = `
            <img src="${usuario.foto || ""}" alt="${usuario.nickname}">
            <div class="info-chat">
                <strong>${usuario.nickname}</strong>
                <small></small>
            </div>
        `;
    div.addEventListener("click", () => {
      document
        .querySelectorAll(".chat-activo")
        .forEach((el) => el.classList.remove("activo"));
      div.classList.add("activo");
      seleccionarChat(usuario.id);
      cargarMensajes(idUsuario, usuario.id);
      suscribirseAlChat(usuario.id);
    });
    chatList.prepend(div);
  }

  function suscribirseAlChat(idAmigo) {
    if (canalPusher) pusher.unsubscribe(canalPusher.name);
    idAmigoActual = Number(idAmigo);
    const canalNombre =
      "chat-" +
      Math.min(idUsuario, idAmigoActual) +
      "-" +
      Math.max(idUsuario, idAmigoActual);
    canalPusher = pusher.subscribe(canalNombre);

    canalPusher.bind("nuevo-mensaje", async (data) => {
      const idEmisor = Number(data.id_emisor);
      const idReceptor = Number(data.id_receptor);
      if (idEmisor === idUsuario) return; // evitar duplicar mis propios mensajes

      const esParaChatActivo =
        (idEmisor === idAmigoActual && idReceptor === idUsuario) ||
        (idEmisor === idUsuario && idReceptor === idAmigoActual);

      if (esParaChatActivo) {
        fetch(`/chat/obtener_mensaje.php?id=${data.id_mensaje}`)
          .then((res) => res.json())
          .then((msg) => {
            if (!msg.error) agregarMensajeAlChat(msg);
            console.log(msg);
          });
        return;
      }

      // si es para mí pero no es el chat activo
      if (idReceptor === idUsuario) {
        let chatDiv = document.querySelector(
          `.chat-activo[data-id-amigo="${idEmisor}"]`
        );
        if (!chatDiv) {
          const res = await fetch(`/chat/obtener_usuario.php?id=${idEmisor}`);
          const usuario = await res.json();
          if (!usuario.error) agregarChatNuevo(usuario);
        }
      }
    });
  }

  function cargarChats() {
    fetch(`/chat/cargar_chats.php`)
      .then((res) => res.text())
      .then((data) => {
        console.log(data);
        chatList.innerHTML = "";
        data.forEach((chat) => {
          const div = document.createElement("div");
          div.className = "chat-activo";
          div.dataset.idAmigo = chat.id_amigo;
          div.innerHTML = `
          <img src="${chat.foto || ""}" alt="${chat.nickname}">
          <div class="info-chat">
            <strong>${chat.nickname}</strong>
          </div>
        `;
          div.addEventListener("click", () => {
            document
              .querySelectorAll(".chat-activo")
              .forEach((el) => el.classList.remove("activo"));
            div.classList.add("activo");
            seleccionarChat(chat.id_amigo);
            cargarMensajes(idUsuario, chat.id_amigo);
            suscribirseAlChat(chat.id_amigo);
          });
          chatList.appendChild(div);
        });
      });
  }

  function seleccionarChat(idReceptor) {
    inputReceptor.value = idReceptor;
    mensajeInput.value = "";
    archivoInput.value = "";
  }

  function cargarMensajes(idEmisor, idReceptor) {
    fetch(
      `/chat/cargar_mensajes.php?id_emisor=${idEmisor}&id_receptor=${idReceptor}`
    )
      .then((res) => res.json())
      .then((data) => {
        chatMensajes.innerHTML = "";
        data.forEach((msg) => agregarMensajeAlChat(msg));
        console.log(msg);
        chatMensajes.scrollTop = chatMensajes.scrollHeight;
      });
  }

  function agregarMensajeAlChat(msg) {
    console.log(msg);
    if (!msg) return;
    const div = document.createElement("div");
    div.className = `mensaje ${
      msg.es_mensaje_yo ? "mensaje-yo" : "mensaje-otro"
    }`;
    if (msg.mensaje) {
      const p = document.createElement("p");
      p.innerText = msg.mensaje;
      div.appendChild(p);
    }
    if (msg.contenido && msg.tipo === "imagen") {
      const img = document.createElement("img");
      img.src = msg.contenido;
      img.className = "imagen-mensaje";
      div.appendChild(img);
    }
    chatMensajes.appendChild(div);
    chatMensajes.scrollTop = chatMensajes.scrollHeight;
  }

  // ========================
  //  CHAT CON IAN BOT (IA)
  // ========================
  function mostrarMensaje(texto, tipo, id = null, esHTML = false) {
    const div = document.createElement("div");
    div.classList.add("mensaje", tipo === "yo" ? "mensaje-yo" : "mensaje-otro");
    if (esHTML) div.innerHTML = texto;
    else div.textContent = texto;
    if (id) div.id = id;
    chatMensajes.appendChild(div);
    chatMensajes.scrollTop = chatMensajes.scrollHeight;
    return div;
  }

  function mostrarAnimacion() {
    const loader = `<div class="loader" id="typing"><span class="emoji">⏳</span> Ian Bot está pensando...</div>`;
    return mostrarMensaje(loader, "ia", "typing", true);
  }

  function quitarAnimacion() {
    const typing = document.getElementById("typing");
    if (typing) typing.remove();
  }

  function seleccionarChatIA() {
    inputReceptor.value = 0;
    chatMensajes.innerHTML = "";
    mostrarMensaje(
      "👋 Hola ¡Bienvenido!, soy Ian Bot. Empieza a chatear...",
      "ia"
    );
    chatIa.classList.add("activo");
  }

  if (chatIa) {
    document
      .querySelectorAll(".chat-activo")
      .forEach((el) => el.classList.remove("activo"));
    seleccionarChatIA();

    chatIa.addEventListener("click", () => {
      document
        .querySelectorAll(".chat-activo")
        .forEach((el) => el.classList.remove("activo"));
      seleccionarChatIA();
    });
  }

  // ========================
  //  ENVÍO MENSAJES
  // ========================
  formulario?.addEventListener("submit", async (e) => {
    e.preventDefault();
    const idReceptor = Number(inputReceptor.value);
    const mensaje = mensajeInput.value.trim();
    if (!mensaje && !archivoInput.value) return;

    if (idReceptor === 0) {
      // CHAT CON IAN BOT
      if (esperandoRespuesta) return;
      mostrarMensaje(mensaje, "yo");
      mensajeInput.value = "";
      try {
        esperandoRespuesta = true;
        mensajeInput.disabled = true;
        btnEnviar.disabled = true;
        mostrarAnimacion();
        const res = await fetch(`/shakti/chat/ianbot.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ mensaje }),
        });
        quitarAnimacion();
        if (!res.ok) {
          mostrarMensaje("⚠️ Error en la comunicación con el bot.", "ia");
          return;
        }
        const data = await res.json();
        const esHTML = /<\/?[a-z][\s\S]*>/i.test(data.respuesta);
        mostrarMensaje(
          data.respuesta || "⚠️ No recibí respuesta del bot.",
          "ia",
          null,
          esHTML
        );
      } catch {
        quitarAnimacion();
        mostrarMensaje("⚠️ Ocurrió un error al conectar con el bot.", "ia");
      } finally {
        esperandoRespuesta = false;
        mensajeInput.disabled = false;
        btnEnviar.disabled = false;
        mensajeInput.focus();
      }
    } else {
      // CHAT CON USUARIO NORMAL
      const formData = new FormData(formulario);
      fetch("/chat/enviar_mensaje.php", { method: "POST", body: formData })
        .then((res) => res.json())
        .then((data) => {
          if (!data.error) {
            console.log(data);
            agregarMensajeAlChat({
              id_mensaje: data.id_mensaje,
              id_emisor: data.id_emisor,
              id_receptor: data.id_receptor,
              es_mensaje_yo: true,
              mensaje,
            });
            mensajeInput.value = "";
            archivoInput.value = "";
          }
        });
    }
  });

  // ========================
  //  INICIAR
  // ========================
  cargarChats();
});
