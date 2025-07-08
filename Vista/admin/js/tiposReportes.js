event.preventDefault();

function enviarDatos() {
  var datos = $("#agregarTipoReporte").serialize();
  var mensajeContenedor = document.getElementById("mensaje");

  $.ajax({
    type: "POST",
    url: "../../Controlador/tipo_reporteCtrl.php?estado=eliminado",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.opcion === 1) {
        window.location.href = "tipos_reportes.php?estado=agregado";
      } else if (respuesta.opcion === 0) {
        // Mostrar mensaje error
        mensajeContenedor.innerHTML = `
          <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 20px;">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <span class="mensaje-texto">${respuesta.mensaje}</span>
          </div>`;
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud:", status, error);
    },
  });
}

function modificarDatos(id, nombre, tipo) {
  document.getElementById("id_tipo_reporte").value = id;
  document.getElementById("nombreModificado").value = nombre;
  document.getElementById("tipoModificado").value = tipo;
}

function enviarDatosModificados() {
  var datos = $("#formModificarTipoReporte").serialize();
  var mensajeContenedor = document.getElementById("mensajeModificados");

  $.ajax({
    type: "POST",
    url: "../../Controlador/tipo_reporteCtrl.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.opcion === 0) {
        window.location.href = "tipos_reportes.php?estado=modificado";
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

// Eliminar Tipo reporte
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".btnEliminar").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      const nombre = this.dataset.nombre;

      // Mostrar nombre en el modal
      document.getElementById("nombreUsuariaModal").textContent = nombre;

      // Generar link con los parámetros que quieras
      const link = `../../Controlador/tipo_reporteCtrl.php?opcion=3&id=${id}`;
      document.getElementById("btnEliminarLink").setAttribute("href", link);
    });
  });
});
