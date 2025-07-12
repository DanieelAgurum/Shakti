// Eliminar Tipo reporte
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".btnEliminar").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      const pregunta = this.dataset.pregunta;

      document.getElementById("nombreUsuariaModal").textContent = pregunta;

      const link = `../../Controlador/preguntasFrecuentesCtrl.php?opcion=3&id=${encodeURIComponent(
        id
      )}`;
      document.getElementById("btnEliminarLink").setAttribute("href", link);
    });
  });
});

event.preventDefault();

function enviarDatos() {
  var datos = $("#agregarPreguntaFrecuente").serialize();
  var mensajeContenedor = document.getElementById("mensaje");

  $.ajax({
    type: "POST",
    url: "../../Controlador/preguntasFrecuentesCtrl.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.opcion === 1) {
        window.location.href = "preguntas_frecuentes.php?estado=agregado";
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

function modificarDatos(id, pregunta, respuesta) {
  document.getElementById("id").value = id;
  document.getElementById("nombrePreguntaModificar").value = pregunta;
  document.getElementById("textoPreguntaModificar").value = respuesta;
}

function enviarDatosModificados() {
  var datos = $("#formModificarTipoReporte").serialize();
  var mensajeContenedor = document.getElementById("mensajeModificados");

  $.ajax({
    type: "POST",
    url: "../../Controlador/preguntasFrecuentesCtrl.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.opcion === 1) {
        window.location.href = "preguntas_frecuentes.php?estado=modificado";
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
