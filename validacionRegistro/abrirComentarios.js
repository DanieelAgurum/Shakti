$(document).ready(function () {
    // Validar y enviar comentario raíz
    $(document).on("submit", ".comment-form", function (e) {
        e.preventDefault();
        const form = $(this);
        const comentario = form.find("input[name='comentario']").val().trim();
        const idPub = form.data("id-publicacion");

        if (comentario.length < 4) {
            Swal.fire("Error", "El comentario debe tener al menos 4 caracteres.", "warning");
            return;
        }

        $.ajax({
            url: "../../Controlador/comentariosCtrl.php",
            type: "POST",
            dataType: "json",
            data: form.serialize(),
            success: function (data) {
                if (data.status === "ok") {
                    Swal.fire({
                        icon: "success",
                        title: "Comentario enviado",
                        timer: 1200,
                        showConfirmButton: false,
                    });

                    // Recarga TODOS los comentarios para mostrar el botón "Ver respuestas" sólo si hay respuestas
                    cargarComentarios();

                    form[0].reset();
                } else if (data.message === "malas_palabras") {
                    Swal.fire({
                        icon: "warning",
                        title: "Lenguaje inapropiado",
                        text: "Evitemos palabras ofensivas. Gracias.",
                        timer: 4000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                } else if (data.message && data.message.includes("Únete a la comunidad")) {
                    Swal.fire({
                        icon: "info",
                        title: "Ups...",
                        text: data.message,
                        confirmButtonText: "Iniciar sesión",
                        confirmButtonColor: "#3085d6",
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = "/Shakti/Vista/login.php";
                    });
                } else {
                    Swal.fire("Error", data.message || "Error al enviar comentario.", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la comunicación con el servidor.", "error");
            },
        });
    });

    // ✅ Mostrar/Ocultar respuestas
    $(document).on("click", ".ver-respuestas", function () {
        const btn = $(this);
        const idComentario = btn.data("id");
        const respuestasCount = btn.data('count');
        const container = $("#respuestas-" + idComentario);

        if (container.hasClass("d-none")) {
            $.ajax({
                url: "../../Controlador/comentariosCtrl.php",
                method: "POST",
                dataType: "json",
                data: { opcion: 5, id_padre: idComentario },
                success: function (data) {
                    if (data.status === "ok") {
                        // Insertar el HTML ya generado desde el backend
                        container.html(data.html).removeClass("d-none");
                        btn.html("Ocultar respuestas");
                    } else {
                        Swal.fire("Error", "No se pudieron cargar las respuestas.", "error");
                    }
                },
                error: function () {
                    Swal.fire("Error", "Error al cargar respuestas.", "error");
                },
            });
        } else {
            container.addClass("d-none").empty();
            btn.html(`Ver respuestas (${respuestasCount || 0})`);
        }
    });

    // Mostrar/ocultar formulario de respuesta
    $(document).on("click", ".btn-responder", function () {
        const id = $(this).data("id");
        const contenedor = $(`#form-responder-${id}`);

        if (contenedor.children().length > 0) {
            contenedor.empty(); // Cerrar si ya existe
        } else {
            const formHtml = `
        <form class="form-responder" data-id-padre="${id}">
          <div class="input-group mb-2">
            <input type="text" name="comentario" class="form-control form-control-sm" placeholder="Escribe tu respuesta..." required>
            <button type="submit" class="btn btn-sm btn-outline-primary">Enviar</button>
          </div>
        </form>`;
            contenedor.html(formHtml);
        }
    });

    // Enviar respuesta
    $(document).on("submit", ".form-responder", function (e) {
        e.preventDefault();
        const form = $(this);
        const idPadre = form.data("id-padre");
        const comentario = form.find("input[name='comentario']").val().trim();
        const idPublicacion = form.closest(".card").find(".comment-form").data("id-publicacion");

        if (comentario.length < 4) {
            Swal.fire("Error", "La respuesta debe tener al menos 4 caracteres.", "warning");
            return;
        }

        $.ajax({
            url: "../../Controlador/comentariosCtrl.php",
            type: "POST",
            dataType: "json",
            data: {
                opcion: 1,
                comentario: comentario,
                id_padre: idPadre,
                id_publicacion: idPublicacion,
            },

            success: function (data) {
                if (data.status === "ok") {
                    Swal.fire({
                        icon: "success",
                        title: "Respuesta enviada",
                        timer: 1200,
                        showConfirmButton: false,
                    });

                    form.remove();
                    $(`#respuestas-${idPadre}`).addClass("d-none").empty();

                    // 🔁 Recargar todos los comentarios para reflejar el nuevo estado
                    cargarComentarios(idPublicacion);
                } else if (data.message === "malas_palabras") {
                    Swal.fire({
                        icon: "warning",
                        title: "Lenguaje inapropiado",
                        text: "Evitemos palabras ofensivas. Gracias.",
                        timer: 4000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire("Error", "No se pudo guardar la respuesta.", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la comunicación con el servidor.", "error");
            },
        });
    });

    // Editar comentario
    $(document).on("click", ".btn-edit-comentario", function () {
        const id = $(this).data("id");
        $(`#edit-form-${id}`).toggleClass("d-none");
    });

    $(document).on("submit", ".edit-comentario-form", function (e) {
        e.preventDefault();
        const form = $(this);
        const id = form.find("input[name='id_comentario']").val();
        const nuevoComentario = form.find("input[name='nuevo_comentario']").val().trim();

        $.ajax({
            url: "../../Controlador/comentariosCtrl.php",
            type: "POST",
            dataType: "json",
            data: {
                opcion: 2,
                id_comentario: id,
                comentario: nuevoComentario,
            },
            success: function (data) {
                if (data.status === "ok") {
                    Swal.fire({
                        icon: "success",
                        title: "Comentario actualizado",
                        timer: 1200,
                        showConfirmButton: false,
                    });
                    form.addClass("d-none");
                    cargarComentarios();
                } else if (data.message === "malas_palabras") {
                    Swal.fire({
                        icon: "warning",
                        title: "Lenguaje inapropiado",
                        text: "Evitemos palabras ofensivas. Gracias.",
                        timer: 4000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire("Error", "No se pudo editar el comentario.", "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Error en la comunicación con el servidor.", "error");
            },
        });
    });

    // Eliminar comentario
    $(document).on("click", ".btn-eliminar-comentario", function () {
        const id = $(this).data("id");

        Swal.fire({
            title: "¿Eliminar comentario?",
            text: "Esta acción no se puede deshacer.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../../Controlador/comentariosCtrl.php",
                    type: "POST",
                    dataType: "json",
                    data: { opcion: 3, id_comentario: id },
                    success: function (data) {
                        if (data.status === "ok") {
                            Swal.fire({
                                icon: "success",
                                title: "Comentario eliminado",
                                timer: 1200,
                                showConfirmButton: false,
                            });
                            cargarComentarios();
                        } else {
                            Swal.fire("Error", "No se pudo eliminar el comentario.", "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Error en la comunicación con el servidor.", "error");
                    },
                });
            }
        });
    });

    // Recargar todos los comentarios
    function cargarComentarios() {
        $(".comments-section").each(function () {
            const section = $(this);
            const idPublicacion = section.attr("id").split("-")[1];

            $.ajax({
                url: "../../Controlador/comentariosCtrl.php",
                type: "POST",
                dataType: "json",
                data: {
                    opcion: 4,
                    id_publicacion: idPublicacion,
                },
                success: function (data) {
                    const contenedor = section.find(".existing-comments");
                    contenedor.empty();

                    if (data.status === "ok") {
                        contenedor.html(data.html);
                    } else {
                        contenedor.html("<p class='text-muted'>Aún no hay comentarios.</p>");
                    }
                },
                error: function () {
                    console.error("Error al recargar comentarios");
                },
            });
        });
    }

});
