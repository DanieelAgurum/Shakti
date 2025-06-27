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
            window.location.href = "../vista/especialista/perfil.php";
            break;
          case "3":
            window.location.href = "../vista/admin/";
            break;
          default:
            break;
        }
      } else {
        $("#mensaje-error").text(respuesta.message).show();
      }
    },
  });
}
