// Al cargar el DOM, asignar eventos a botones eliminar
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btnEliminar").forEach((button) => {
        button.addEventListener("click", function () {
            const id = this.dataset.id;
            const titulo = this.dataset.titulo;

            // Mostrar el título en el modal eliminar
            document.getElementById("nombreGlosarioModal").textContent = titulo;

            // Actualizar href o dataset del botón confirmar eliminar
            const link = `../../Controlador/glosarioCtrl.php?opcion=3&id_glosario=${encodeURIComponent(id)}`;
            document.getElementById("btnEliminarLink").setAttribute("href", link);
        });
    });
});

// Función para enviar datos de nuevo glosario
function enviarDatos(event) {
    event.preventDefault()

    if (tinymce.get("descripcion")) {
        tinymce.get("descripcion").save();
    }

    var datos = $("#agregarGlosario").serialize();
    var mensajeContenedor = document.getElementById("mensaje");

    $.ajax({
        type: "POST",
        url: "../../Controlador/glosarioCtrl.php",
        data: datos,
        dataType: "json",
        success: function (respuesta) {
            console.log(respuesta);
            if (respuesta.opcion === 1) {
                // Recarga o redirige tras agregar
                window.location.href = "glosario.php?estado=agregado";
            } else {
                mensajeContenedor.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                      <span class="mensaje-texto">${respuesta.mensaje}</span>
                    </div>`;
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", status, error);
            mensajeContenedor.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                  <span class="mensaje-texto">Error de comunicación con el servidor.</span>
                </div>`;
        },
    });
}
// Función para llenar datos en modal modificar
function modificarDatos(id, icono, titulo, contenido) {
    document.getElementById("idGlosario").value = id;
    document.getElementById("iconoModificado").value = icono;
    document.getElementById("tituloModificado").value = titulo;

    if (tinymce.get("descripcionModificado")) {
        tinymce.get("descripcionModificado").setContent(contenido);
    } else {
        document.getElementById("descripcionModificado").value = contenido;
    }
}

// Función para enviar datos modificados
function enviarDatosModificados(event) {
    event.preventDefault();

    if (tinymce.get("descripcionModificado")) {
        tinymce.get("descripcionModificado").save();
    }

    var datos = $("#formModificarGlosario").serialize();

    var mensajeContenedor = document.getElementById("mensajeModificar");

    $.ajax({
        type: "POST",
        url: "../../Controlador/glosarioCtrl.php",
        data: datos,
        dataType: "json",
        success: function (respuesta) {
            if (respuesta.opcion == 1) {
                window.location.href = "glosario.php?estado=modificado";
            } else {
                mensajeContenedor.innerHTML = '<div class="alert alert-danger">' + respuesta.mensaje + '</div>';
            }
        },
        error: function () {
            mensajeContenedor.innerHTML = '<div class="alert alert-danger">Error al modificar los datos.</div>';
        }
    });
}