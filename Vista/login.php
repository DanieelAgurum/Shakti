<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_SESSION['id_rol'])) {
    switch ($_SESSION['id_rol']) {
        case 1: header("Location: " . $urlBase . "usuaria/perfil.php"); exit;
        case 2: header("Location: " . $urlBase . "especialista/perfil.php"); exit;
        case 3: header("Location: " . $urlBase . "admin/"); exit;
    }
}
?>


<div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content p-4">

      <!-- Tabs -->
      <ul class="nav nav-pills nav-justified mb-3">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-login">Iniciar sesión</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-register">Registrarse</button>
        </li>
      </ul>

      <div class="tab-content">
        <!-- LOGIN -->
        <div class="tab-pane fade show active" id="pills-login">
          <form id="formLogin" action="<?= $urlBase ?>Controlador/loginCtrl.php" method="POST">
            <input type="hidden" name="opcion" value="1">

            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="correo" class="form-control" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="contraseña" class="form-control" required>
              </div>
            </div>

            <button type="submit" class="btn btn-purple w-100">Ingresar</button>
          </form>
        </div>

        <!-- REGISTRO -->
        <div class="tab-pane fade" id="pills-register">
          <form id="formRegistro" action="<?= $urlBase ?>Controlador/UsuariasControlador.php" method="POST">
            <input type="hidden" name="opcion" value="1">

            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Apellidos</label>
              <input type="text" name="apellidos" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Usuario</label>
              <input type="text" name="nickname" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
              <input type="email" name="correo" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input type="password" name="contraseña" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Confirmar contraseña</label>
              <input type="password" name="conContraseña" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Fecha de nacimiento</label>
              <input type="date" name="fecha_nac" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Rol</label>
              <select name="rol" class="form-select" required>
                <option value="1">Usuaria</option>
                <option value="2">Especialista</option>
              </select>
            </div>

            <button type="submit" class="btn btn-purple w-100">Registrarse</button>
          </form>
        </div>
      </div>
      <!-- Google Sign-In -->
    <div id="g_id_onload"
             data-client_id="149987150313-s4k1pr3ggbi12phgsfspsbf6mnukocc8.apps.googleusercontent.com"
             data-callback="handleCredentialResponse"
             data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
             data-type="standard"
             data-shape="pill"
             data-theme="outline"
             data-text="sign_in_with"
             data-size="large"
             data-logo_alignment="left">
        </div>
      </div>

    </div>
  </div>
</div>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
  const urlBase = "<?= $urlBase ?>"; // define urlBase globalmente para auth.js
</script>
<script src="peticiones(js)/auth.js"></script>



<style>
  /*STYLYSSSSSSSS*/
.modal-content {
    border-radius: 1.5rem;
    background: #f9f9f9;
    box-shadow: 8px 8px 20px rgba(0,0,0,0.05), -8px -8px 20px rgba(255,255,255,0.8);
    padding: 2rem;
    border: none;
}

/* Tabs estilo suave */
.nav-pills .nav-link {
    border-radius: 50px;
    background: #e0e0e0;
    color: #5a2a83;
    font-weight: 500;
    transition: all 0.3s ease;
}
.nav-pills .nav-link.active {
    background: #5a2a83;
    color: #fff;
    box-shadow: inset 2px 2px 5px rgba(0,0,0,0.2);
}

/* Inputs y selects */
input.form-control, select.form-select {
    border-radius: 50px;
    border: none;
    padding: 0.7rem 1rem;
    background: #f0f0f0;
    box-shadow: inset 5px 5px 10px rgba(0,0,0,0.05), inset -5px -5px 10px rgba(255,255,255,0.7);
    transition: all 0.3s ease;
}
input.form-control:focus, select.form-select:focus {
    outline: none;
    box-shadow: inset 5px 5px 12px rgba(0,0,0,0.1), inset -5px -5px 12px rgba(255,255,255,0.8);
}

/* Botones */
.btn-purple {
    border-radius: 50px;
    background: #5a2a83;
    color: #fff;
    font-weight: 600;
    padding: 0.7rem 1.5rem;
    box-shadow: 5px 5px 15px rgba(0,0,0,0.1), -5px -5px 15px rgba(255,255,255,0.2);
    transition: all 0.3s ease;
}
.btn-purple:hover {
    transform: translateY(-2px);
    box-shadow: 5px 5px 20px rgba(0,0,0,0.15), -5px -5px 20px rgba(255,255,255,0.25);
}

/* Iconos en input */
.input-group-text {
    background: #f0f0f0;
    border-radius: 50px 0 0 50px;
    border: none;
    color: #5a2a83;
}

/* Labels */
.form-label {
    font-weight: 500;
    color: #5a2a83;
}

/* Modal tamaño máximo */
.modal-dialog {
    max-width: 460px;
}
</style>