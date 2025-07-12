document.addEventListener("DOMContentLoaded", function () {
    const tituloInput = document.getElementById("recipient-name");
    const notaTextarea = document.getElementById("nota");
    const submitBtn = document.querySelector("#exampleModal button[type='submit']");

    const errorTitulo = document.createElement("div");
    errorTitulo.className = "text-danger small";
    tituloInput.parentElement.appendChild(errorTitulo);

    const errorNota = document.createElement("div");
    errorNota.className = "text-danger small";
    notaTextarea.parentElement.appendChild(errorNota);

    function validarCampos() {
        let valido = true;

        if (tituloInput.value.trim().length === 0) {
            errorTitulo.textContent = "El título no puede estar vacío.";
            valido = false;
        } else {
            errorTitulo.textContent = "";
        }

        if (notaTextarea.value.trim().length === 0) {
            errorNota.textContent = "El mensaje no puede estar vacío.";
            valido = false;
        } else {
            errorNota.textContent = "";
        }
        submitBtn.disabled = !valido;
    }

    tituloInput.addEventListener("input", validarCampos);
    notaTextarea.addEventListener("input", validarCampos);

    validarCampos();

    const modal = document.getElementById("exampleModal");
    modal.addEventListener("hidden.bs.modal", () => {
        tituloInput.value = "";
        notaTextarea.value = "";
        errorTitulo.textContent = "";
        errorNota.textContent = "";
        submitBtn.disabled = true;
    });
});