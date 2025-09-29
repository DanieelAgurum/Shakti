document.addEventListener("DOMContentLoaded", () => {
  $(function () {
    let index = 0;

    // Abrir chatbot
    $("#shakti-chatbot-circle").on("click", function () {
      $(this).hide();
      $("#shakti-chatbot-box").addClass("active");
    });

    // Cerrar chatbot
    $(".shakti-chatbot-box-toggle").on("click", function () {
      $("#shakti-chatbot-box").removeClass("active");
      $("#shakti-chatbot-circle").fadeIn();
    });

    // Enviar mensaje al backend
    $("#shakti-chatbot-form").on("submit", async function (e) {
      e.preventDefault();
      let msg = $("#shakti-chatbot-input").val().trim();
      if (!msg) return;

      // Mostrar mensaje propio
      generateMsg(msg, "self");

      // Mostrar "escribiendo..."
      showTyping();

      try {
        const res = await fetch("/shakti/chat/ianbot.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ mensaje: msg })
        });

        const data = await res.json();

        removeTyping();

        if (data.respuesta) {
          generateMsg(data.respuesta, "user");
        } else {
          generateMsg("⚠️ No se recibió respuesta del servidor.", "user");
        }
      } catch (error) {
        console.error(error);
        removeTyping();
        generateMsg("❌ Error al conectar con el servidor.", "user");
      }
    });

    function generateMsg(msg, type) {
      index++;
      let html = `
        <div id="shakti-cm-msg-${index}" class="shakti-chatbot-msg ${type}">
          <div class="shakti-cm-msg-text">${msg}</div>
        </div>
      `;
      $(".shakti-chatbot-logs").append(html);
      $("#shakti-chatbot-input").val("");
      $(".shakti-chatbot-logs").scrollTop(
        $(".shakti-chatbot-logs")[0].scrollHeight
      );
    }

    function showTyping() {
      index++;
      let html = `
        <div id="typing-indicator" class="shakti-chatbot-msg typing">
          <div class="shakti-cm-msg-text">
            escribiendo
            <span class="typing-dots">
              <span></span><span></span><span></span>
            </span>
          </div>
        </div>
      `;
      $(".shakti-chatbot-logs").append(html);
      $(".shakti-chatbot-logs").scrollTop(
        $(".shakti-chatbot-logs")[0].scrollHeight
      );
    }

    function removeTyping() {
      $("#typing-indicator").remove();
    }
  });
});