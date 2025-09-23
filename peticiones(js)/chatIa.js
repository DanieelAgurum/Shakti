document.addEventListener("DOMContentLoaded", () => {
  const chatIa = document.querySelector('[data-id-amigo="0"]');
  const chatBox = document.querySelector(".chat-mensajes");
  const form = document.getElementById("formulario");
  const inputMensaje = document.getElementById("mensaje");
  const inputReceptor = document.getElementById("id_receptor");
  const btnEnviar = form.querySelector("button[type=submit]");

  let esperandoRespuesta = false;

  // Función para renderizar mensajes
  function mostrarMensaje(texto, tipo, id = null, esHTML = false) {
    const div = document.createElement("div");
    div.classList.add("mensaje");
    div.classList.add(tipo === "yo" ? "mensaje-yo" : "mensaje-otro");

    if (esHTML) div.innerHTML = texto;
    else div.textContent = texto;

    if (id) div.id = id;
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
    return div;
  }

  // Animación de “pensando”
  function mostrarAnimacion() {
    const loader = `<div class="loader" id="typing"><span class="emoji">⏳</span> Ian Bot está pensando...</div>`;
    return mostrarMensaje(loader, "ia", "typing", true);
  }

  function quitarAnimacion() {
    const typing = document.getElementById("typing");
    if (typing) typing.remove();
  }

  // Función para seleccionar el chat de Ian Bot
  function seleccionarChatIA() {
    inputReceptor.value = 0;
    chatBox.innerHTML = "";
    mostrarMensaje("👋 Hola ¡Bienvenido!, soy Ian Bot. Empieza a chatear...", "ia");
    chatIa.classList.add("activo");
  }

  // Seleccionar automáticamente Ian Bot al cargar
  if (chatIa) {
    document.querySelectorAll(".chat-activo").forEach(el => el.classList.remove("activo"));
    seleccionarChatIA();
  }

  // Enviar mensaje al bot
  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      if (esperandoRespuesta) return;

      const mensaje = inputMensaje.value.trim();
      if (!mensaje) return;

      // Mostrar mensaje propio
      mostrarMensaje(mensaje, "yo");
      inputMensaje.value = "";

      try {
        esperandoRespuesta = true;
        inputMensaje.disabled = true;
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

        mostrarMensaje(data.respuesta || "⚠️ No recibí respuesta del bot.", "ia", null, esHTML);

      } catch (error) {
        quitarAnimacion();
        mostrarMensaje("⚠️ Ocurrió un error al conectar con el bot.", "ia");
      } finally {
        esperandoRespuesta = false;
        inputMensaje.disabled = false;
        btnEnviar.disabled = false;
        inputMensaje.focus();
      }
    });
  }
});