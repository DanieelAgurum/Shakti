function iniciarSesion() {
  var datos = $("#iniciarSesion").serialize();

  $.ajax({
    type: "POST",
    url: "../Controlador/loginCtrl.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.success) {
        switch (respuesta.id_rol) {
          case "1":
            window.location.href = "../vista/usuaria/perfil.php";
            break;
          case "2":
            window.location.href = "../vista/tutor/panel.php";
            break;
          case "3":
            window.location.href = "../vista/admin/panel.php";
            break;
          default:
            break;
        }
      } else {
        $("#mensaje-error").text(respuesta.message).show();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la petición AJAX:", error);
      $("#mensaje-error")
        .text("Error en el servidor, intenta más tarde.")
        .show();
    },
  });
}
