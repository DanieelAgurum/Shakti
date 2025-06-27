function iniciarSesion() {
  var datos = $("#iniciarSesion").serialize();

  $.ajax({
    type: "POST",
    url: "../Controlador/loginCtrl.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      // console.log(respuesta);

      if (respuesta.success) {
        switch (String(respuesta.id_rol)) {
          case "1":
            location.reload();
            break;
          case "2":
            location.reload();
            break;
          case "3":
            location.reload();
            break;
          default:
            location.reload();
            break;
        }
      } else {
        $("#mensaje-error").text(respuesta.message).show();
      }
    },
  });
}