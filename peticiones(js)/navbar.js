document.addEventListener('DOMContentLoaded', function () {
  const searchWrapper = document.querySelector('.custom-search-wrapper');
  const searchIcon = searchWrapper.querySelector('.custom-search-icon');
  const searchInput = searchWrapper.querySelector('.custom-search-input');

  // Al clickear la lupa
  searchIcon.addEventListener('click', function () {
    searchWrapper.classList.add('active');
    searchInput.focus();
  });

  // Al hacer click fuera, se contrae
  document.addEventListener('click', function (e) {
    if (!searchWrapper.contains(e.target)) {
      searchWrapper.classList.remove('active');
      searchInput.value = '';
    }
  });
});

// Puedes agregar funcionalidad adicional aquí, como manejar la búsqueda en tiempo real
// o enviar la consulta al servidor cuando el usuario presione Enter.
// Por ejemplo, para manejar la búsqueda en tiempo real:
searchInput.addEventListener('input', function () {
  const query = searchInput.value.trim();
  // Aquí puedes agregar la lógica para filtrar resultados en tiempo real
  console.log('Búsqueda en tiempo real:', query);
});

// O para manejar el envío de la búsqueda al presionar Enter:
searchInput.addEventListener('keydown', function (e) {
  if (e.key === 'Enter') {
    e.preventDefault(); // Evita el envío del formulario si está dentro de uno
    const query = searchInput.value.trim();
    // Aquí puedes agregar la lógica para enviar la consulta al servidor
    console.log('Enviar búsqueda:', query);
  }
});
