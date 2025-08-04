$(document).ready(function () {
    $('input[name="especialista"]').on('keyup', function () {
        var especialista = $(this).val();

        $.ajax({
            url: '../../Modelo/buscar_especialistas.php',
            type: 'GET',
            data: {
                especialista: especialista
            },
            success: function (response) {
                $('#resultados').html(response);
            }

        });
        console.log(especialista);
    });
});
if (window.innerWidth <= 768) {
    const style = document.createElement('style');
    style.textContent = `
    .card-up::before {
      content: none !important;
      animation: none !important;
      display: none !important;}`;
    document.head.appendChild(style);
}