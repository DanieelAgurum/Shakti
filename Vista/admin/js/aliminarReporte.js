document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".btnEliminar").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      const nombre = this.dataset.nombre;
      const contenido = this.dataset.contenido;

      // Mostrar nombre y contenido en el modal
      document.getElementById("nombreUsuariaModal").textContent = nombre;
      document.getElementById("contenidoUsuariaModal").textContent = contenido;

      // Generar link con los parámetros que quieras
      const link = `../../Controlador/reportesCtrl.php?opcion=2&id=${id}&tipo=${contenido}`;
      document.getElementById("btnEliminarLink").setAttribute("href", link);
    });
  });
});
