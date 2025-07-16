function contactForm() {
  var datos = $("#contactForm").serialize();

  $.ajax({
    type: "POST",
    url: "../Controlador/mandarContactCtrl.php?opcion=1",
    data: datos,
    success:function (respuesta){
        console.log(respuesta);
    }
  });
}
