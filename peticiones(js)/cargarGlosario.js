async function cargarInstituciones() {
    try {
        const res = await fetch(`/shakti/Controlador/glosarioCtrl.php?opcion=4`);
        const data = await res.json();

        console.log(data);

        const contenedor = document.querySelector(".row-cols-1");

        if (!data || data.sinDatos || !Array.isArray(data) || data.length === 0) {
            contenedor.innerHTML = `<p class="text-center text-muted">No hay información disponible.</p>`;
            return;
        }

        let html = "";

        data.forEach(item => {
            html += `
                <div class="col">
                    <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeInLeft">
                        <div class="card-body">
                            <h5 class="card-title">
                                ${item.icono ?? ""} 
                                ${item.registro ?? ""}
                            </h5>
                            ${item.nombre ?? ""}
                        </div>
                    </div>
                </div>
            `;
        });

        contenedor.innerHTML = html;

    } catch (error) {
        console.error("Error cargando el glosario:", error);
        document.querySelector(".row-cols-1").innerHTML =
            `<p class="text-center text-danger">Ocurrió un error al cargar la información.</p>`;
    }
}

document.addEventListener("DOMContentLoaded", cargarInstituciones);