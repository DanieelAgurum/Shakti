// Eliminar Organización
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".btnEliminar").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      const nombre = this.dataset.nombre;

      // CORRECCIÓN: Se cambió el id del span para evitar duplicados
      document.getElementById("nombreEliminar").textContent = nombre;

      const link = `../../Controlador/organizacionesCtrl.php?opcion=3&id=${encodeURIComponent(
        id
      )}`;
      document.getElementById("btnEliminarLink").setAttribute("href", link);
    });
  });

  // AÑADIDO: Limpia el formulario de agregar cuando el modal se cierra.
  // Esto evita que los datos de "modificar" aparezcan en "agregar".
  $('#exampleModal').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
    document.getElementById("mensaje").innerHTML = "";
  });
});


// CORRECCIÓN: Función reescrita para manejar FormData (para el archivo de imagen) y AJAX
function enviarDatos(event) {
  event.preventDefault(); // Prevenir el envío tradicional del formulario

  var form = document.getElementById("agregarOrganizacion");
  var datos = new FormData(form);
  var mensajeContenedor = document.getElementById("mensaje");

  $.ajax({
    type: "POST",
    url: "../../Controlador/organizacionesCtrl.php",
    data: datos,
    dataType: "json",
    contentType: false, // Necesario para FormData
    processData: false, // Necesario para FormData
    success: function (respuesta) {
      if (respuesta.opcion === 1) {
        window.location.href = "organizaciones.php?estado=agregado";
      } else if (respuesta.opcion === 0) {
        mensajeContenedor.innerHTML = `
             <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 20px;">
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               <span class="mensaje-texto">${respuesta.mensaje}</span>
             </div>`;
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud:", status, error);
      mensajeContenedor.innerHTML = `
             <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 20px;">
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               <span class="mensaje-texto">Ocurrió un error en el servidor. Inténtalo de nuevo.</span>
             </div>`;
    },
  });
}

// CORRECCIÓN: Se actualizó esta función para apuntar a los nuevos IDs del modal de modificar
function modificarDatos(id, nombre, descripcion, numero) {
  document.getElementById("id_modificar").value = id;
  document.getElementById("nombre_modificar").value = nombre;
  document.getElementById("descripcion_modificar").value = descripcion;
  document.getElementById("numero_modificar").value = numero;
}

function enviarDatosModificados() {
  var datos = $("#modificarOrganizacion").serialize();
  var mensajeContenedor = document.getElementById("mensajeModificados");

  $.ajax({
    type: "POST",
    url: "../../Controlador/organizacionesCtrl.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.opcion === 1) {
        window.location.href = "organizaciones.php?estado=modificado";
      } else {
        mensajeContenedor.innerHTML = `
          <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 20px;">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <span class="mensaje-texto">${respuesta.mensaje}</span>
          </div>`;
      }
    },
  });
}