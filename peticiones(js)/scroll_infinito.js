const limite = 6;
let paginaActual = 1;
let cargando = false;
let noHayMas = false;

function debounce(fn, delay) {
  let timer;
  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), delay);
  };
}

async function cargarMasPublicaciones(pagina) {
  if (cargando || noHayMas) return;
  cargando = true;

  const scrollLoader = document.getElementById("scrollLoader");
  if (pagina !== 1) {
    scrollLoader.classList.remove("d-none");
  }

  const offset = (pagina - 1) * limite;
  const terminoBusqueda = document
    .querySelector('input[name="buscador"]')
    .value.trim();

  const url = new URL(
    "/shakti/controlador/buscadorForoCtrl.php",
    window.location.origin
  );
  url.searchParams.append("limit", limite);
  url.searchParams.append("offset", offset);

  if (terminoBusqueda.length > 0) {
    url.searchParams.append("buscador", terminoBusqueda);
    url.searchParams.append("opcion", "1");
  }

  try {
    const res = await fetch(url.toString());
    if (!res.ok) throw new Error("Error en la petición");

    const data = await res.text();

    if (data.trim().length === 0) {
      noHayMas = true;
      window.removeEventListener("scroll", onScrollDebounced);
      return;
    }

    if (pagina === 1) {
      document.querySelector("#contenedorPublicaciones").innerHTML = data;
    } else {
      document
        .querySelector("#contenedorPublicaciones")
        .insertAdjacentHTML("beforeend", data);
    }

    paginaActual = pagina + 1;
    cargando = false;
  } catch (error) {
    cargando = false;
  } finally {
    if (pagina !== 1) {
      scrollLoader.classList.add("d-none");
    }
  }
}

function onScroll() {
  const distanciaAlFondo =
    document.body.offsetHeight - (window.innerHeight + window.scrollY);
  if (distanciaAlFondo < 10000) {
    cargarMasPublicaciones(paginaActual);
  }
}

const onScrollDebounced = debounce(onScroll, 100);

window.addEventListener("load", async () => {
  await cargarMasPublicaciones(1);
  const loader = document.getElementById("loaderInicio");
  if (loader) {
    loader.classList.add("fade-out");
    setTimeout(() => loader.remove(), 100);
  }
  window.addEventListener("scroll", onScrollDebounced);
});
