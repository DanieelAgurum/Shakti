function enviarReporte(event) {
  event.preventDefault();
  var datos = $("#reportarPublicacion").serialize();

  $.ajax({
    type: "POST",
    url: "/Controlador/reportesCtrl.php?opcion=2",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      let icono = respuesta.opcion === 0 ? "success" : "error";
      let titulo = respuesta.opcion === 0 ? "¡Éxito!" : "Error";

      Swal.fire({
        icon: icono,
        title: titulo,
        text: respuesta.mensaje,
        confirmButtonText: "Aceptar",
      }).then(() => {
        $("#modalReportar").modal("hide");
        $("#reportarPublicacion")[0].reset();
      });
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
