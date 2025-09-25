$(document).on("click", ".btn-eliminar-publicacion", function () {
    const id = $(this).data("id");

    Swal.fire({
        title: "¿Eliminar publicación?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../../Controlador/publicacionControlador.php",
                type: "POST",
                dataType: "json",
                data: { borrar_id: id },
                success: function (data) {
                    if (data.status === "ok") {
                        Swal.fire({
                            icon: "success",
                            title: "Publicación eliminada",
                            timer: 1200,
                            showConfirmButton: false,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error", data.message, "error");
                    }
                },
                error: function () {
                    Swal.fire("Error", "Error en la comunicación con el servidor.", "error");
                },
            });
        }
    });
});
