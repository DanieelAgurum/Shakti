document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btnEliminar').forEach(button => {
    button.addEventListener('click', function () {
      const id = this.dataset.id;
      const nombre = this.dataset.nombre;

      // Mostrar nombre en el modal
      document.getElementById('nombreUsuariaModal').textContent = nombre;

      // Generar link con los parámetros que quieras
      const link = `../../Controlador/UsuariasControlador.php?opcion=3&id=${id}`;
      document.getElementById('btnEliminarLink').setAttribute('href', link);
    });
  });
});
