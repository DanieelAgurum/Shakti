function iniciarSesion() {
  var datos = $("#iniciarSesion").serialize();

  $.ajax({
    type: "POST",
    url: "../Controlador/loginCtrl.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.message === "exito") {
        location.reload();
      } else {
        $("#mensaje-error").text(respuesta.message).show();
      }
    },
  });
}
