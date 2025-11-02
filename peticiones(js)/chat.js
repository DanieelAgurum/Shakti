document.addEventListener("DOMContentLoaded", () => {
  const chatList = document.getElementById("chat-list");
  const chatMensajes = document.querySelector(".chat-mensajes");
  const idUsuario = Number(document.getElementById("id_usuaria").value);

  const formulario = document.getElementById("formulario");
  const mensajeInput = document.getElementById("mensaje");
  const archivoInput = document.getElementById("archivo");
  const inputReceptor = document.getElementById("id_receptor");
  const btnImg = document.querySelector(".btn-subir-imagen");
  const btnEnviar = formulario?.querySelector("button[type=submit]");
  const chatIa = document.querySelector('[data-id-amigo="0"]');

  let esperandoRespuesta = false;
  let enviando = false;

  const pusher = new Pusher("0469c2ac8ae0b818938a", {
    cluster: "us2",
    encrypted: true,
  });

  const canalesSuscritos = {};

  function suscribirCanal(id1, id2) {
    const canal = `chat-${Math.min(id1, id2)}-${Math.max(id1, id2)}`;
    if (canalesSuscritos[canal]) return canalesSuscritos[canal];

    const channel = pusher.subscribe(canal);
    channel.bind("nuevo-mensaje", function (msg) {
      if (msg.id_emisor !== idUsuario) {
        agregarMensajeAlChat(msg);
      }
    });

    canalesSuscritos[canal] = channel;
    return channel;
  }

  function agregarChatNuevo(usuario) {
    const div = document.createElement("div");
    div.className = "chat-activo";
    div.dataset.idAmigo = usuario.id;
    div.innerHTML = `
      <div class="chat-avatar">
        <img src="${usuario.foto || ""}" alt="${usuario.nickname}">
      </div>
      <div class="info-chat">
        <strong>${usuario.nickname}</strong>
      </div>
    `;
    div.addEventListener("click", () => {
      document
        .querySelectorAll(".chat-activo")
        .forEach((el) => el.classList.remove("activo"));
      div.classList.add("activo");
      seleccionarChat(usuario.id);
      chatMensajes.innerHTML = "";
      cargarMensajes(idUsuario, usuario.id);
      suscribirCanal(idUsuario, usuario.id); // 🔹 Suscripción solo al seleccionar chat
    });
    chatList.appendChild(div);
  }

  function cargarChats() {
    const params = new URLSearchParams(window.location.search);
    const idEspecialista = params.get("especialistas");

    fetch(
      "/shakti/Controlador/chatsCtrl.php?cargarChats&especialista=" +
        (idEspecialista || "")
    )
      .then((res) => res.json())
      .then((json) => {
        const data = json.data;
        chatList.innerHTML = "";

        if (!data || data.length === 0) return;

        data.forEach((chat) => agregarChatNuevo(chat));

        // 🔹 Solo selecciona automáticamente si hay especialista en la URL
        if (idEspecialista && data.length > 0) {
          const primerChat = data[0];
          const divChat = chatList.querySelector(
            `[data-id-amigo="${primerChat.id}"]`
          );

          if (divChat) {
            divChat.classList.add("activo");
            seleccionarChat(primerChat.id);
            chatMensajes.innerHTML = "";
            cargarMensajes(idUsuario, primerChat.id);
            suscribirCanal(idUsuario, primerChat.id);
          }
        }
      })
      .catch((error) => {
      });
  }

  function cargarMensajes(idEmisor, idReceptor) {
    fetch(
      `/shakti/Controlador/chatsCtrl.php?cargarMensajes=1&idEmisor=${idEmisor}&idReceptor=${idReceptor}`
    )
      .then((res) => res.json())
      .then((json) => {
        const data = json.data || [];
        chatMensajes.innerHTML = "";
        data.forEach((msg) => agregarMensajeAlChat(msg));
        chatMensajes.scrollTop = chatMensajes.scrollHeight;
      });
  }

  function seleccionarChat(idReceptor) {
    inputReceptor.value = idReceptor;
    mensajeInput.value = "";
    archivoInput.value = "";
    btnImg.style.display = "";
  }

  function agregarMensajeAlChat(msg) {
    if (!msg) return;
    const div = document.createElement("div");

    const esMio = msg.id_emisor == idUsuario;
    div.className = `mensaje ${esMio ? "mensaje-yo" : "mensaje-otro"}`;

    if (msg.mensaje) {
      const p = document.createElement("p");
      p.innerText = msg.mensaje;
      div.appendChild(p);
    }
    if (msg.contenido && msg.tipo === "imagen") {
      const img = document.createElement("img");
      img.src = msg.contenido || msg.tipo;
      img.className = "imagen-mensaje";
      div.appendChild(img);
    }

    // 🔹 Evitar duplicados
    const idUnico = `${msg.id_emisor}-${msg.id_receptor}-${
      msg.creado_en || Date.now()
    }`;
    if (!document.getElementById(idUnico)) {
      div.id = idUnico;
      chatMensajes.appendChild(div);
      chatMensajes.scrollTop = chatMensajes.scrollHeight;
    }
  }

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

  async function seleccionarChatIA() {
    try {
      inputReceptor.value = 0;
      chatMensajes.innerHTML = "";

      const res = await fetch(
        "/shakti/Controlador/chatsCtrl.php?cargarMensajesIanBot",
        {
          method: "GET",
          headers: { "Content-Type": "application/json" },
        }
      );

      const data = await res.json();

      // Si hay mensajes previos
      if (data.data && Array.isArray(data.data) && data.data.length > 0) {
        mostrarMensaje(
          "👋 Hola ¡Bienvenido!, soy Ian Bot. Empieza a chatear. \n Solo brindo apoyo y acompañamiento emocional preventivo. No soy un sustituto profesional de salud mental, pero puedo ayudarte a encontrar un especialista adecuado si así lo deseas.",
          "ia"
        );

        data.data.forEach((msg) => {
          const tipo = msg.es_mensaje_yo ? "yo" : "ia";
          const esHTML = /<\/?[a-z][\s\S]*>/i.test(msg.mensaje);
          mostrarMensaje(msg.mensaje, tipo, null, esHTML);
        });

        chatMensajes.scrollTop = chatMensajes.scrollHeight;
      } else {
        // Si no hay mensajes previos
        mostrarMensaje(
          "👋 Hola ¡Bienvenido!, soy Ian Bot. Empieza a chatear. \n Solo brindo apoyo y acompañamiento emocional preventivo. No soy un sustituto profesional de salud mental, pero puedo ayudarte a encontrar un especialista adecuado si así lo deseas.",
          "ia"
        );
      }

      // Marcar el chat como activo
      document
        .querySelectorAll(".chat-activo")
        .forEach((el) => el.classList.remove("activo"));
      chatIa.classList.add("activo");

      // Ocultar botón de imagen
      btnImg.style.display = "none";
    } catch (error) {
      mostrarMensaje("⚠️ Error al conectar con Ian Bot.", "ia");
    }
  }

  async function enviarMensaje(e) {
    e.preventDefault();
    if (enviando) return;
    enviando = true;

    const idReceptor = Number(inputReceptor.value);
    const mensaje = mensajeInput.value.trim();

    if (!mensaje && !archivoInput.value) {
      enviando = false;
      return;
    }

    if (idReceptor === 0) {
      if (esperandoRespuesta) {
        enviando = false;
        return;
      }
      mostrarMensaje(mensaje, "yo");
      mensajeInput.value = "";
      try {
        esperandoRespuesta = true;
        mensajeInput.disabled = true;
        btnEnviar.disabled = true;
        mostrarAnimacion();

        const res = await fetch(
          "/shakti/Controlador/chatsCtrl.php?enviarMensajeIanBot",
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ mensaje }),
          }
        );

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
        enviando = false;
      }
    } else {
      try {
        const formData = new FormData(formulario);
        const res = await fetch(
          "/shakti/Controlador/chatsCtrl.php?enviarMensaje",
          {
            method: "POST",
            body: formData,
          }
        );
        const data = await res.json();

        if (!data.error) {
          agregarMensajeAlChat(data);
          mensajeInput.value = "";
          archivoInput.value = "";

          suscribirCanal(idUsuario, data.id_receptor);
        }
      } catch (error) {
      } finally {
        enviando = false;
      }
    }
  }

  formulario?.removeEventListener("submit", enviarMensaje);
  formulario?.addEventListener("submit", enviarMensaje);

  mensajeInput.addEventListener("keydown", (e) => {
    // Si presiona Enter
    if (e.key === "Enter") {
      if (e.shiftKey) {
        return;
      }

      e.preventDefault();
      formulario.requestSubmit();
    }
  });

  if (chatIa) {
    chatIa.addEventListener("click", seleccionarChatIA);
  }

  cargarChats();
});
