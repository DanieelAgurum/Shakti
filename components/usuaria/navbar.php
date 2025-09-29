<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/notificacionesModelo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/obtenerLink/obtenerLink.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/shakti/Modelo/configuracionMdl.php';
$urlBase = getBaseUrl();

$usuario = [
  'id' => $_SESSION['id'] ?? 0,
  'rol' => $_SESSION['id_rol'] ?? 0,
  'nickname' => $_SESSION['nickname'] ?? 'Invitado',
  'correo' => $_SESSION['correo'] ?? null
];

$config = null;
$configActual = null;
if (isset($_SESSION['id_usuaria'])) {
  $idUsuaria = $_SESSION['id_usuaria'];
  $config = new ConfiguracionMdl();
  $configActual = $config->obtenerConfiguracion($idUsuaria);
}

// Notificaciones
$notificaciones = [];
$notificacionesNoLeidas = 0;
if ($usuario['id'] && $usuario['rol'] == 1) {
  $notificaciones = Notificacion::obtenerParaUsuaria($usuario['id']);
  $notificacionesNoLeidas = count(array_filter($notificaciones, fn($n) => $n['leida'] == 0));
}

// Función para generar rutas según rol
function rutaSegura(array $mapa, int $rol, string $default = 'login.php')
{
  return $mapa[$rol] ?? $default;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Favicon -->
  <link rel="icon" href="<?= $urlBase ?>img/4carr.ico">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <!-- Bootstrap Bundle JS (incluye Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- CSS personalizados -->
  <link rel="stylesheet" href="<?= $urlBase ?>css/navbar.css" />
  <link rel="stylesheet" href="<?= $urlBase ?>css/estilos.css" />
  <link rel="stylesheet" href="<?= $urlBase ?>css/config.css">

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const selectFont = document.getElementById("fontSize");
      const checkboxDarkMode = document.getElementById("darkMode");
      const checkboxHighContrast = document.getElementById("highContrast");

      function aplicarTamanoFuente(tamano) {
        document.documentElement.classList.remove("font-small", "font-medium", "font-large");
        switch (tamano) {
          case "small":
            document.documentElement.classList.add("font-small");
            break;
          case "medium":
            document.documentElement.classList.add("font-medium");
            break;
          case "large":
            document.documentElement.classList.add("font-large");
            break;
        }
      }

      function aplicarModoOscuro(estado) {
        document.documentElement.classList.toggle("dark-mode", estado);
      }

      function aplicarAltoContraste(estado) {
        document.documentElement.classList.toggle("high-contrast", estado);
      }

      aplicarTamanoFuente("<?= $configActual['tamano_fuente'] ?? 'medium' ?>");
      aplicarModoOscuro(<?= !empty($configActual['modo_oscuro']) ? 'true' : 'false' ?>);
      aplicarAltoContraste(<?= !empty($configActual['alto_contraste']) ? 'true' : 'false' ?>);

      if (selectFont) {
        selectFont.addEventListener("change", function() {
          aplicarTamanoFuente(this.value);
        });
      }

      if (checkboxDarkMode) {
        checkboxDarkMode.addEventListener("change", function() {
          aplicarModoOscuro(this.checked);
        });
      }

      if (checkboxHighContrast) {
        checkboxHighContrast.addEventListener("change", function() {
          aplicarAltoContraste(this.checked);
        });
      }
    });
  </script>
</head>


<nav class="navbar navbar-expand-lg custom-navbar fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="<?= $urlBase ?>index.php">SHAKTI</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEspecialista"
      aria-controls="navbarEspecialista" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarEspecialista">
      <ul class="navbar-nav ms-auto align-items-center">
        <?php
        // Mapa de rutas
        $rutas = [
          'libreYSegura' => [1 => 'usuaria/libreYSegura.php', 2 => 'usuaria/libreYSegura.php', 3 => 'admin/'],
          'alzalaVoz' => [1 => 'usuaria/alzalaVoz.php', 3 => 'admin/'],
          'publicaciones' => [1 => 'usuaria/publicaciones.php', 2 => 'usuaria/publicaciones.php', 3 => 'admin/']
        ];
        ?>
        <li class="nav-item"><a class="nav-link" href="<?= $urlBase ?>Vista/<?= rutaSegura($rutas['libreYSegura'], $usuario['rol']) ?>">Libre y Segura</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= $urlBase ?>Vista/usuaria/foro.php">Foro</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= $urlBase ?>Vista/contacto.php">Contáctanos</a></li>

        <?php if ($usuario['rol'] <= 1): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $urlBase ?>Vista/<?= rutaSegura($rutas['alzalaVoz'], $usuario['rol']) ?>">Test</a></li>
        <?php endif; ?>

        <li class="nav-item"><a class="nav-link" href="<?= $urlBase ?>Vista/<?= rutaSegura($rutas['publicaciones'], $usuario['rol']) ?>">Publicaciones</a></li>

        <li class="nav-item ms-3 d-flex align-items-center custom-search-wrapper">
          <i class="bi bi-search custom-search-icon"></i>
          <input type="text" class="custom-search-input" placeholder="Buscar..." />
        </li>


        <!-- Menú usuario -->
        <li class="dropdown ms-3">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle me-1"></i> <?= ucwords(strtolower($usuario['nickname'])) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <?php if ($usuario['correo']): ?>
              <li>
                <a class="dropdown-item" href="<?= $urlBase ?>Vista/<?= rutaSegura([1 => 'usuaria/perfil.php', 2 => 'especialista/perfil.php'], $usuario['rol']) ?>">Mi perfil <i class="bi bi-person-circle me-1"></i></a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item" href="<?= $urlBase ?>Vista/usuaria/usuarios.php">
                  Solicitudes <i class="bi bi-person-fill-add"></i>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalNotificaciones">
                  Notificaciones <i class="bi bi-bell-fill"></i>
                  <?php if ($notificacionesNoLeidas): ?>
                    <span id="contadorNotificaciones" class="badge bg-danger rounded-pill ms-2"><?= $notificacionesNoLeidas ?></span>
                  <?php endif; ?>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#configModal">
                  Configuración <i class="bi bi-gear-fill"></i>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <form action="<?= $urlBase ?>Controlador/loginCtrl.php" method="post" class="m-0 p-0">
                  <input type="hidden" name="opcion" value="2" />
                  <button type="submit" class="dropdown-item cerrar">Cerrar sesión <i class="bi bi-door-open-fill"></i></button>
                </form>
              </li>
            <?php else: ?>
              <li>
                <a class="dropdown-item" href="<?= $urlBase ?>Vista/login.php">
                  Iniciar sesión <i class="bi bi-box-arrow-in-right"></i>
                </a>
              </li>
            <?php endif; ?>

          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<button id="btn-top" title="Ir al inicio" class="btn-top">
  <i class="fas fa-arrow-up"></i>
</button>

<!-- Modal de configuración -->
<div class="modal fade custom-config-modal" id="configModal" tabindex="-1" aria-labelledby="configModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="configModalLabel">Configuración</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Body -->
      <form id="formConfig" method="post" action="">
        <div class="modal-body">

          <!-- Tabs -->
          <ul class="nav nav-tabs" id="configTabs" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" id="cuenta-tab" data-bs-toggle="tab" data-bs-target="#cuenta" type="button" role="tab">Cuenta</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="privacidad-tab" data-bs-toggle="tab" data-bs-target="#privacidad" type="button" role="tab">Privacidad</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="notificaciones-tab" data-bs-toggle="tab" data-bs-target="#notificaciones" type="button" role="tab">Notificaciones</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="accesibilidad-tab" data-bs-toggle="tab" data-bs-target="#accesibilidad" type="button" role="tab">Accesibilidad</button>
            </li>
          </ul>

          <!-- Contenido Tabs -->
          <div class="tab-content mt-3" id="configTabsContent">

            <!-- Cuenta -->
            <div class="tab-pane fade show active" id="cuenta" role="tabpanel">
              <div class="form-floating mt-4">
                <input type="password" class="form-control custom-input" name="newPassword" id="newPassword" placeholder="Nueva contraseña">
                <label for="newPassword">Nueva contraseña</label>
              </div>
              <small id="passwordMessage" class="text-danger"></small>

              <!-- Botón para generar/enviar token -->
              <div class="mt-4">
                <button type="button" id="btnGenerarToken" class="btn btn-outline-light w-100">
                  Generar / Enviar token
                </button>
              </div>

              <!-- Input para ingresar token (oculto al inicio) -->
              <div class="form-floating mt-4 d-none" id="tokenContainer">
                <input type="text" class="form-control custom-input" name="token" id="token" placeholder="Ingresa el token">
                <label for="token">Token</label>
              </div>
            </div>

            <!-- Privacidad -->
            <div class="tab-pane fade" id="privacidad" role="tabpanel">
              <div class="form-check form-switch mt-2">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="permitir_amigos"
                  id="addFriendOption"
                  <?= !empty($configActual['permitir_amigos']) && $configActual['permitir_amigos'] == 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="addFriendOption">Permitir que me agreguen como amigo</label>
              </div>
              <div class="form-check form-switch mt-2">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="perfil_privado"
                  id="privateProfile"
                  <?= !empty($configActual['perfil_privado']) && $configActual['perfil_privado'] == 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="privateProfile">Perfil privado</label>
              </div>
            </div>

            <!-- Notificaciones -->
            <div class="tab-pane fade" id="notificaciones" role="tabpanel">
              <div class="form-check mt-2">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="notificar_mensajes"
                  id="notifyMessages"
                  <?= !empty($configActual['notificar_mensajes']) && $configActual['notificar_mensajes'] == 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="notifyMessages">Notificarme de nuevos mensajes</label>
              </div>
              <div class="form-check mt-2">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="notificar_comentarios"
                  id="notifyComments"
                  <?= !empty($configActual['notificar_comentarios']) && $configActual['notificar_comentarios'] == 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="notifyComments">Notificarme de comentarios y respuestas</label>
              </div>
            </div>

            <!-- Accesibilidad -->
            <div class="tab-pane fade" id="accesibilidad" role="tabpanel">
              <div class="mb-3">
                <label for="fontSize" class="form-label">Tamaño de fuente</label>
                <select class="form-select" name="tamano_fuente" id="fontSize">
                  <option value="small" <?= isset($configActual['tamano_fuente']) && $configActual['tamano_fuente'] == 'small' ? 'selected' : '' ?>>Pequeño</option>
                  <option value="medium" <?= isset($configActual['tamano_fuente']) && $configActual['tamano_fuente'] == 'medium' ? 'selected' : '' ?>>Medio</option>
                  <option value="large" <?= isset($configActual['tamano_fuente']) && $configActual['tamano_fuente'] == 'large' ? 'selected' : '' ?>>Grande</option>
                </select>
              </div>
              <div class="form-check form-switch mt-2">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="modo_oscuro"
                  id="darkMode"
                  <?= !empty($configActual['modo_oscuro']) && $configActual['modo_oscuro'] == 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="darkMode">Activar modo oscuro</label>
              </div>
              <div class="form-check form-switch mt-2">
                <input
                  class="form-check-input"
                  name="alto_contraste"
                  type="checkbox"
                  id="highContrast"
                  <?= !empty($configActual['alto_contraste']) && $configActual['alto_contraste'] == 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="highContrast">Activar alto contraste</label>
              </div>
            </div>

          </div><!-- Fin tab-content -->
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <input type="hidden" name="accion" id="accion" value="guardar_configuracion">
          <button type="button" class="btn btn-banner btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-banner">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Notificaciones -->
<div class="modal fade" id="modalNotificaciones">
  <div class="modal-dialog modal-dialog-scrollable modal-md">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title"><i class="bi bi-bell"></i> Notificaciones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if ($notificaciones): ?>
          <ul class="list-group">
            <?php foreach ($notificaciones as $n): ?>
              <li class="list-group-item d-flex justify-content-between align-items-start <?= $n['leida'] == 0 ? 'fw-bold bg-light' : '' ?>">
                <div class="ms-2 me-auto"><?= htmlspecialchars($n['mensaje']) ?><br><small class="text-muted"><?= date('d/m/Y H:i', strtotime($n['fecha_creacion'])) ?></small></div>
                <?= $n['leida'] == 0 ? '<span class="badge bg-danger rounded-pill">Nuevo</span>' : '' ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-muted">No tienes notificaciones.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></div>
    </div>
  </div>
</div>

<script>
  window.usuarioActual = <?= json_encode($usuario) ?>;
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const inputPassword = document.getElementById("newPassword");
    const btnGenerarToken = document.getElementById("btnGenerarToken");
    const tokenContainer = document.getElementById("tokenContainer");
    const formConfig = document.getElementById("formConfig");
    const passwordMessage = document.getElementById("passwordMessage");

    btnGenerarToken.disabled = true;

    const regexPassword = /^(?=.*[0-9])(?=.*[!@#$%^&*?()_+\-=\[\]{};:'",.<>\/\\|~])[A-Za-z0-9!@#$%^&*?()_+\-=\[\]{};:'",.<>\/\\|~]{8,}$/;

    inputPassword.addEventListener("input", function() {
      const password = inputPassword.value.trim();

      if (password === "") {
        inputPassword.classList.remove("is-valid", "is-invalid");
        passwordMessage.textContent = "";
        btnGenerarToken.disabled = true;
        return;
      }

      if (regexPassword.test(password)) {
        inputPassword.classList.add("is-valid");
        inputPassword.classList.remove("is-invalid");
        passwordMessage.textContent = "";
        btnGenerarToken.disabled = false;
      } else {
        inputPassword.classList.add("is-invalid");
        inputPassword.classList.remove("is-valid");
        passwordMessage.textContent = "Mínimo 8 caracteres, un número y un carácter especial.";
        btnGenerarToken.disabled = true;
      }
    });

    btnGenerarToken.addEventListener("click", function() {
      const password = inputPassword.value.trim();
      if (!regexPassword.test(password)) return;

      btnGenerarToken.disabled = true;
      btnGenerarToken.textContent = "Enviando...";

      fetch("<?= $urlBase ?>Controlador/configuracionCtrl.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: "accion=generar_token&newPassword=" + encodeURIComponent(password)
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === "ok") {
            tokenContainer.classList.remove("d-none");

            Swal.fire({
              icon: "success",
              title: "Token enviado",
              text: data.msg,
              timer: 2500,
              timerProgressBar: true,
              showConfirmButton: false,
            });

            let tiempo = 60;
            btnGenerarToken.textContent = `Reintentar en ${tiempo}s`;
            const interval = setInterval(() => {
              tiempo--;
              btnGenerarToken.textContent = `Reintentar en ${tiempo}s`;
              if (tiempo <= 0) {
                clearInterval(interval);
                btnGenerarToken.textContent = "Generar / Enviar token";
                btnGenerarToken.disabled = false;
              }
            }, 1000);

          } else {
            btnGenerarToken.disabled = false;
            btnGenerarToken.textContent = "Generar / Enviar token";
            Swal.fire({
              icon: "error",
              title: "Error",
              text: data.msg,
              timer: 2500,
              timerProgressBar: true,
              showConfirmButton: false
            });
          }
        })
        .catch(err => {
          console.error(err);
          btnGenerarToken.disabled = false;
          btnGenerarToken.textContent = "Generar / Enviar token";
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Ocurrió un error inesperado al generar el token",
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false
          });
        });
    });

    formConfig.addEventListener("submit", function(e) {
      e.preventDefault();

      const formData = new FormData(formConfig);
      formData.append("accion", "guardar_configuracion");

      fetch("<?= $urlBase ?>Controlador/configuracionCtrl.php", {
          method: "POST",
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === "ok") {
            Swal.fire({
              icon: "success",
              title: "Configuración guardada",
              text: data.msg,
              timer: 2000,
              timerProgressBar: true,
              showConfirmButton: false
            });

            const tamano = formData.get("tamano_fuente") || "medium";
            const modoOscuro = formData.get("modo_oscuro") === "on";
            const altoContraste = formData.get("alto_contraste") === "on";

            document.documentElement.classList.remove("font-small", "font-medium", "font-large");
            document.documentElement.classList.add(`font-${tamano}`);
            document.documentElement.classList.toggle("dark-mode", modoOscuro);
            document.documentElement.classList.toggle("high-contrast", altoContraste);

            const modal = bootstrap.Modal.getInstance(document.getElementById('configModal'));
            modal.hide();

          } else {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: data.msg,
              timer: 2000,
              timerProgressBar: true,
              showConfirmButton: false
            });
          }
        })
        .catch(err => {
          console.error(err);
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Ocurrió un error al guardar la configuración",
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false
          });
        });
    });
  });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $urlBase ?>peticiones(js)/navbar.js"></script>
<script type="module" src="<?= $urlBase ?>peticiones(js)/notificaciones.js"></script>