<?php
if (session_status() === PHP_SESSION_NONE) session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
$urlBase = getBaseUrl();

if (!(isset($_SESSION['id_rol'])) || $_SESSION['id_rol'] == 2) {
    header("Location: {$urlBase}Vista/especialista/perfil");
    exit;
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/TestModelo.php';
$model = new TestIanMdl();
$idUsuario = $_SESSION['id_usuaria'];
$puedeHacerTest = $model->puedeHacerTest($idUsuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Test - NexoH</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= $urlBase ?>css/test.css">
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/navbar.php'; ?>
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="modalBienvenidaTest" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Bienvenido al Test de Salud Mental</h5>
      </div>
      <div class="modal-body" id="modalBodyTest">
        <?php if($puedeHacerTest): ?>
          <p>Este test no es un diagnóstico médico. Sirve para conocerte mejor antes de hablar con un profesional.</p>
        <?php else: ?>
          <p>Ya realizaste este test recientemente. Debes esperar 7 días para volver a hacerlo.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <?php if($puedeHacerTest): ?>
        <button type="button" id="startTest" class="btn btn-primary" data-bs-dismiss="modal">Empezar Test</button>
        <?php else: ?>
        <a href="<?= $urlBase ?>index.php" class="btn btn-secondary">Volver</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Contenedor del test -->
<div class="container mt-3 mb-3 d-flex justify-content-center align-items-center min-vh-100">
    <div id="testContainerTest" class="card shadow p-4 w-100" style="max-width: 650px; display:none;">
        <h1 id="tituloTest" class="text-center mb-4 fw-bold">Test IAn – Salud Mental</h1>
        <form id="testIanTest">
            <div id="preguntaActualTest"></div>
            <div class="d-flex justify-content-between mt-3">
                <button type="button" id="prevPreguntaTest" class="btn btn-secondary">Anterior</button>
                <button type="submit" id="nextPreguntaTest" class="btn btn-primary">Siguiente</button>
            </div>
        </form>
        <div id="respuestaIATest"  class="mt-4"></div>
    </div>
</div>

<script>
const preguntas = [
    "¿Sueles guardar lo que sientes para no parecer débil?",
    "¿Te cuesta hablar de tus emociones con amigos o familia?",
    "¿Últimamente te has sentido cansado mentalmente o sin motivación?",
    "¿Te enojas con facilidad, incluso por cosas pequeñas?",
    "¿Sientes que no puedes fallar o mostrarte vulnerable?",
    "¿Has sentido presión por cumplir con expectativas de los demás?",
    "¿Duermes bien y descansas lo suficiente?",
    "¿Tienes alguien con quien puedas hablar sin sentirte juzgado?"
];

const opciones = ['No, nunca', 'A veces', 'Con frecuencia', 'Casi siempre'];
let respuestas = {};
let i = 0;

function mostrarPregunta() {
    const qContainer = document.getElementById('preguntaActualTest');
    qContainer.innerHTML = `
        <label class="form-label fw-semibold">${preguntas[i]}</label>
        <select class="form-select" id="respuestaSelectTest" required>
            <option value="">Selecciona</option>
            ${opciones.map(op => `<option value="${op}">${op}</option>`).join('')}
        </select>
    `;
}

document.getElementById('startTest')?.addEventListener('click', function() {
    document.getElementById('testContainerTest').style.display = 'block';
    mostrarPregunta();
});

document.getElementById('nextPreguntaTest').addEventListener('click', async function(e) {
    e.preventDefault();
    const valor = document.getElementById('respuestaSelectTest').value;
    if(!valor) return alert("Selecciona una opción");
    
    respuestas[`p${i+1}`] = valor;
    i++;

    if(i < preguntas.length) {
        mostrarPregunta();
    } else {
        // BLOQUEAR botón para evitar múltiples envíos
        const btnNext = document.getElementById('nextPreguntaTest');
        btnNext.disabled = true;
        btnNext.textContent = 'Enviando...';
        await enviarTest();
    }
});

document.getElementById('prevPreguntaTest').addEventListener('click', function(e) {
    e.preventDefault();
    if(i>0){ i--; mostrarPregunta(); }
});

async function enviarTest() {
    const formData = new FormData();
    Object.keys(respuestas).forEach(k => formData.append(`respuestas[${k}]`, respuestas[k]));

    try {
        const resp = await fetch('<?= $urlBase ?>Controlador/testControl.php', { method:'POST', body: formData });
        const data = await resp.json();
        document.getElementById('respuestaIATest').innerHTML = `<div class="alert alert-info mt-3">${data.mensaje}</div>`;
        document.getElementById('testIanTest').style.display = 'none';
    } catch(err) {
        alert("Ocurrió un error al enviar el test. Intenta nuevamente.");
        const btnNext = document.getElementById('nextPreguntaTest');
        btnNext.disabled = false;
        btnNext.textContent = 'Siguiente';
    }
}

var myModal = new bootstrap.Modal(document.getElementById('modalBienvenidaTest'));
myModal.show();
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Shakti/components/usuaria/footer.php'; ?>
</body>
</html>
