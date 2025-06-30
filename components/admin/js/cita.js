document.addEventListener('DOMContentLoaded', function () {
    const generarCitaButton = document.getElementById('generar-cita');

    generarCitaButton.addEventListener('click', function (event) {
        event.preventDefault(); // Evita la recarga de la página

        const cliente = "Juan Pérez";
        const tecnico = "Pedro Quiroz Aristegui";
        const fecha = "2023-09-28";
        const hora = "01:00 PM";
        const lugar = "Roma#52 col.San Angel";

        const citaCliente = document.querySelector('.cita p:nth-child(2)');
        const citaTecnico = document.querySelector('.cita p:nth-child(3)');
        const citaFecha = document.querySelector('.cita p:nth-child(4)');
        const citaHora = document.querySelector('.cita p:nth-child(5)');
        const citaLugar = document.querySelector('.cita p:nth-child(6)');

        citaCliente.textContent = `Cliente:  ${cliente}`;
        citaTecnico.textContent = `Técnico: ${tecnico}`;
        citaFecha.textContent = `Fecha: ${fecha}`;
        citaHora.textContent = `Hora: ${hora}`;
        citaLugar.textContent = `Lugar: ${lugar}`;
    });
});
