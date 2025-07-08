function enviarReporte(event) {
  event.preventDefault();
  var datos = $("#reportarPublicacion").serialize();

  $.ajax({
    type: "POST",
    url: "../../Controlador/reportesCtrl.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.opcion === 0) {
        // Éxito
        Swal.fire({
          icon: "success",
          title: "¡Éxito!",
          text: respuesta.mensaje,
          confirmButtonText: "Aceptar",
        }).then(() => {
          $("#modalReportar").modal("hide"); // Cerrar modal
          $("#reportarPublicacion")[0].reset(); // Resetear formulario
        });
      } else if (respuesta.opcion === 1) {
        // Error
        Swal.fire({
          icon: "error",
          title: "Error",
          text: respuesta.mensaje,
          confirmButtonText: "Aceptar",
        }).then(() => {
          $("#modalReportar").modal("hide"); // Cerrar modal
          $("#reportarPublicacion")[0].reset(); // Resetear formulario
        });
      }
    },
    error: function (xhr, status, error) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Ocurrió un error en la solicitud. Inténtalo de nuevo.",
        confirmButtonText: "Aceptar",
      });
    },
  });
}

function rellenarDatosReporte(nick, id_publi) {
  document.getElementById("nickname").value = nick;
  document.getElementById("publicacion").value = id_publi;
}
