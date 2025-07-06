function iniciarSesion() {
  var datos = $("#iniciarSesion").serialize();

  $.ajax({
    type: "POST",
    url: "../Controlador/loginCtrl.php",
    data: datos,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.success) {
        switch (parseInt(respuesta.id_rol)) {
          case 1:
            window.location.href = "../Vista/usuaria/perfil.php";
            break;
          case 2:
            window.location.href = "../Vista/especialista/perfil.php";
            break;
          case 3:
            window.location.href = "../Vista/admin/";
            break;
          default:
            location.reload();
        }
      } else {
        $("#mensaje-error").text(respuesta.message).show();
      }
    },
  });
}
