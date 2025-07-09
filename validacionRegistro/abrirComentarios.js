function validarFormulario() {
  const titulo = document.querySelector('input[name="titulo"]').value.trim();
  const contenido = document.querySelector('textarea[name="contenido"]').value.trim();

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
  const titulo = document.getElementById('titulo-' + id).value.trim();
  const contenido = document.getElementById('contenido-' + id).value.trim();

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

// Mostrar/ocultar sección comentarios
document.querySelectorAll('.btn-toggle-comments').forEach(btn => {
  btn.addEventListener('click', () => {
    const pubId = btn.getAttribute('data-id');
    const commentsSection = document.getElementById('comments-' + pubId);
    commentsSection.classList.toggle('d-none');
  });
});

// Mostrar formulario edición y ocultar texto
document.querySelectorAll('.btn-toggle-edit').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.getAttribute('data-id');
    document.getElementById('edit-form-' + id).classList.remove('d-none');
    document.getElementById('titulo-text-' + id).style.display = 'none';
    document.getElementById('contenido-text-' + id).style.display = 'none';
    btn.style.display = 'none'; // Oculta botón editar mientras editas
  });
});

// Cancelar edición
document.querySelectorAll('.btn-cancel').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.getAttribute('data-id');
    document.getElementById('edit-form-' + id).classList.add('d-none');
    document.getElementById('titulo-text-' + id).style.display = 'block';
    document.getElementById('contenido-text-' + id).style.display = 'block';
    document.querySelector('.btn-toggle-edit[data-id="' + id + '"]').style.display =
      'inline-block';
  });
});

// Enviar comentario con AJAX y actualizar la lista de comentarios
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('.btn-responder').forEach(btn => {
    btn.addEventListener('click', e => {
      const idPadre = e.target.dataset.id;
      const form = e.target.closest('.comments-section').querySelector('.comment-form');
      form.querySelector("input[name='id_padre']").value = idPadre;
      form.querySelector("input[name='comentario']").focus();
    });
  });

  document.querySelectorAll(".comment-form").forEach(form => {
    form.addEventListener("submit", async e => {
      e.preventDefault();
      const formData = new FormData(form);
      const idPub = form.getAttribute("data-id-publicacion");
      const contDiv = form.closest(".comments-section");
      const existing = contDiv.querySelector('.existing-comments');

      try {
        const res = await fetch("../../controlador/comentariosCtrl.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();

        if (data.status === "ok") {
          Swal.fire({
            icon: "success",
            title: "Comentario enviado",
            timer: 1200,
            showConfirmButton: false
          });

          const div = document.createElement("div");
          div.classList.add("mb-2", "p-2", "bg-light", "rounded");
          div.innerHTML = `<strong>${data.nombre}:</strong> ${data.comentario}<br><small class="text-muted">${data.fecha}</small><button class="btn btn-sm btn-link btn-responder" data-id="${data.id_comentario}">Responder</button>`;

          if (data.id_padre) {
            // encuentra el div del padre
            const padreBtn = contDiv.querySelector(`.btn-responder[data-id="${data.id_padre}"]`);
            const wrapper = document.createElement("div");
            wrapper.classList.add("ms-4");
            padreBtn.after(wrapper);
            wrapper.append(div);
          } else {
            existing.append(div);
          }

          form.reset();
          form.querySelector("input[name='id_padre']").value = "";

        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: data.message
          });
        }
      } catch (err) {
        Swal.fire({
          icon: "error",
          title: "Error AJAX"
        });
        console.error(err);
      }
    });
  });
});