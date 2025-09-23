document.addEventListener("DOMContentLoaded", () => {
  const chatIa = document.querySelector('[data-id-amigo="0"]');
  const chatBox = document.querySelector(".chat-mensajes");
  const form = document.getElementById("formulario");
  const inputMensaje = document.getElementById("mensaje");
  const inputReceptor = document.getElementById("id_receptor");

  // Función para renderizar mensajes
  function mostrarMensaje(texto, tipo) {
    const div = document.createElement("div");
    div.classList.add("mensaje");

    if (tipo === "yo") {
      div.classList.add("mensaje-yo");
    } else {
      div.classList.add("mensaje-otro");
    }

    div.textContent = texto;
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  // Al hacer clic en Ian Bot
  if (chatIa) {
    chatIa.addEventListener("click", () => {
      inputReceptor.value = 0;
      chatBox.innerHTML = ""; // limpiar mensajes previos
      mostrarMensaje(
        "👋 Hola ¡Bienvenido!, soy Ian Bot.  Empieza a chatear...",
        "ia"
      );
    });
  }

  // Enviar mensaje
  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const mensaje = inputMensaje.value.trim();
      const idReceptor = inputReceptor.value;

      if (!mensaje) return;

      // Mostrar mi mensaje
      mostrarMensaje(mensaje, "yo");
      inputMensaje.value = "";

      if (idReceptor === "0") {
        try {

          // Enviar al backend (ianbot.php)
          const res = await fetch(`/shakti/chat/ianbot.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ mensaje }),
          });

          if (!res.ok) {
            mostrarMensaje("⚠️ Error en la comunicación con el bot.", "ia");
            return;
          }

          const data = await res.json();

          mostrarMensaje(
            data.respuesta || "⚠️ No recibí respuesta del bot.",
            "ia"
          );
        } catch (error) {
          mostrarMensaje("⚠️ Ocurrió un error al conectar con el bot.", "ia");
        }
      }
    });
  }
});
