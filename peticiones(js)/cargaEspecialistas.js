function cargarEspecialistas(pagina = 1) {
    $("#loaderInicio").show();
    $.post("../../Controlador/cargarEspecialistasPaginado.php", {
        pagina: pagina
    }, function (data) {
        $("#resultados").html(data.cards);
        $("#paginacion").html(data.paginacion);
        $("#loaderInicio").hide();
    }, 'json').fail(function () {
        console.error('Error al cargar especialistas');
        $("#loaderInicio").hide();
    });
}


$(document).ready(function () {
    cargarEspecialistas();

    // Delegar evento para paginación
    $(document).on('click', '.pag-btn', function () {
        let pagina = $(this).data('page');
        cargarEspecialistas(pagina);
    });
});