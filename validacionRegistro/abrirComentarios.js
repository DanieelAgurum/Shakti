function validarFormulario() {
  const titulo = document.querySelector('input[name="titulo"]').value.trim();
  const contenido = document
    .querySelector('textarea[name="contenido"]')
    .value.trim();

  if (titulo.length < 3) {
    alert("El título debe tener al menos 3 caracteres.");
    return false;
  }

  if (contenido.length < 5) {
    alert("El contenido debe tener al menos 5 caracteres.");
    return false;
  }

  return true;
}

function validarEdicion(id) {
  const titulo = document.getElementById("titulo-" + id).value.trim();
  const contenido = document.getElementById("contenido-" + id).value.trim();

  if (titulo.length < 3) {
    alert("El título debe tener al menos 3 caracteres.");
    return false;
  }

  if (contenido.length < 5) {
    alert("El contenido debe tener al menos 5 caracteres.");
    return false;
  }

  return true;
}

// Delegación para mostrar/ocultar sección de comentarios
document.addEventListener("click", (e) => {
  if (e.target.closest(".btn-toggle-comments")) {
    const btn = e.target.closest(".btn-toggle-comments");
    const pubId = btn.getAttribute("data-id");
    const commentsSection = document.getElementById("comments-" + pubId);
    if (commentsSection) {
      commentsSection.classList.toggle("d-none");
    }
  }

  // Delegación para botón "Responder"
  if (e.target.classList.contains("btn-responder")) {
    const idPadre = e.target.dataset.id;
    const form = e.target
      .closest(".comments-section")
      .querySelector(".comment-form");
    form.querySelector("input[name='id_padre']").value = idPadre;
    form.querySelector("input[name='comentario']").focus();
  }

  // Mostrar formulario de edición
  if (e.target.classList.contains("btn-toggle-edit")) {
    const id = e.target.getAttribute("data-id");
    document.getElementById("edit-form-" + id).classList.remove("d-none");
    document.getElementById("titulo-text-" + id).style.display = "none";
    document.getElementById("contenido-text-" + id).style.display = "none";
    e.target.style.display = "none";
  }

  // Cancelar edición
  if (e.target.classList.contains("btn-cancel")) {
    const id = e.target.getAttribute("data-id");
    document.getElementById("edit-form-" + id).classList.add("d-none");
    document.getElementById("titulo-text-" + id).style.display = "block";
    document.getElementById("contenido-text-" + id).style.display = "block";
    const btnEdit = document.querySelector(
      '.btn-toggle-edit[data-id="' + id + '"]'
    );
    if (btnEdit) btnEdit.style.display = "inline-block";
  }
});

// Enviar comentario (formulario AJAX)
document.addEventListener("submit", async (e) => {
  const form = e.target.closest(".comment-form");
  if (!form) return;

  e.preventDefault();
  const formData = new FormData(form);
  const idPub = form.getAttribute("data-id-publicacion");
  const contDiv = form.closest(".comments-section");
  const existing = contDiv.querySelector(".existing-comments");

  try {
    const res = await fetch("../../controlador/comentariosCtrl.php", {
      method: "POST",
      body: formData,
    });
    const data = await res.json();

    if (data.status === "ok") {
      Swal.fire({
        icon: "success",
        title: "Comentario enviado",
        timer: 1200,
        showConfirmButton: false,
      });

      const div = document.createElement("div");
      div.classList.add("mb-2", "p-2", "bg-light", "rounded");
      div.innerHTML = `<strong>${data.nombre}:</strong> ${data.comentario}<br><small class="text-muted">${data.fecha}</small> <button class="btn btn-sm btn-link btn-responder" data-id="${data.id_comentario}">Responder</button>`;

      if (data.id_padre) {
        const padreBtn = contDiv.querySelector(
          `.btn-responder[data-id="${data.id_padre}"]`
        );
        if (padreBtn) {
          const wrapper = document.createElement("div");
          wrapper.classList.add("ms-4");
          padreBtn.after(wrapper);
          wrapper.append(div);
        }
      } else {
        existing.append(div);
      }

      // Actualizar contador
      const countSpan = document.getElementById(`comentarios-count-${idPub}`);
      if (countSpan) {
        const currentCount = parseInt(countSpan.textContent) || 0;
        countSpan.textContent = currentCount + 1;
      }

      form.reset();
      form.querySelector("input[name='id_padre']").value = "";
    } else if (data.status === "error" && data.message === "malas_palabras") {
      const input = form.querySelector("input[name='comentario']");
      input.classList.add("input-error");

      Swal.fire({
        icon: "warning",
        title: "Cuida tus palabras",
        text: "Te invitamos a expresarte con respeto.",
        confirmButtonColor: "#f27474",
      });

      setTimeout(() => {
        input.classList.remove("input-error");
      }, 3000);
      return;
    } else if (data.message && data.message.includes("Únete a la comunidad")) {
      Swal.fire({
        icon: "info",
        title: "Ups...",
        text: data.message,
        confirmButtonText: "Iniciar sesión",
        confirmButtonColor: "#3085d6",
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "/Shakti/Vista/login.php";
        }
      });
      return;
    } else {
      Swal.fire({
        icon: "info",
        title: "Error",
        text: data.message || "Ocurrió un error inesperado",
      });
    }
  } catch (err) {
    Swal.fire({
      icon: "error",
      title: "Error AJAX",
      text: "No se pudo enviar el comentario",
    });
    console.error(err);
  }
});
