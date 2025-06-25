<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro - Shakti</title>
    <?php
    include '../components/usuaria/estilos.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .invalid {
            border-color: white;
        }

        .error {
            color: red;
            font-size: 0.85em;
            height: 1em;
            margin-bottom: 0.5em;
            display: block;
        }
    </style>
</head>

<body class="auth-body">
    <?php
    require '../components/usuaria/navbar.php';
    ?>

    <div class="auth-container">
        <div class="auth-header">
            <img src="https://source.unsplash.com/random/80x80?logo" alt="Logo" />
            <h1>Crear cuenta</h1>
        </div>

        <form class="auth-form" id="registroForm" novalidate action="../Controlador/UsuariasControlador.php" method="post" enctype="multipart/form-data">
            <label>Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ingrese su(s) nombre(s)" />
            <small class="error" id="errorNombre"></small>

            <label>Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" placeholder="Ingrese sus apellidos" />
            <small class="error" id="errorApellidos"></small>

            <label>Nombre de usuario</label>
            <input type="text" name="nickname" id="nickname" placeholder="Ingrese su nombre de usuario" />
            <small class="error" id="errorNickname"></small>

            <label>Correo electrónico</label>
            <input type="email" name="correo" id="correo" placeholder="Ingrese su correo electrónico" />
            <small class="error" id="errorCorreo"></small>

            <label>Contraseña</label>
            <input type="password" name="contraseña" id="contraseña" placeholder="Ingrese su contraseña" />
            <small class="error" id="errorContraseña"></small>

            <label>Confirmar contraseña</label>
            <input type="password" name="conContraseña" id="conContraseña" placeholder="Ingrese nuevamente su contraseña" />
            <small class="error" id="errorConContraseña"></small>

            <label>Fecha de nacimiento</label>
            <input type="date" name="fecha_nac" id="fecha_nac" />
            <small class="error" id="errorFecha_nac"></small>

            <div>
                <label>
                    <input type="checkbox" id="especialistaCheck" onchange="actualizarRol()" />
                    Registrar como especialista
                </label>
            </div>

            <div id="campo-documento" style="display: none;">
                <label>Documento que respalde tu experiencia</label>
                <input type="file" name="documento" id="documento" accept=".pdf,.doc,.docx" />
                <small class="error" id="errorDocumento"></small>
            </div>

            <input type="hidden" name="rol" id="rol" value="1" />
            <input type="hidden" name="opcion" value="1" />
            <button type="submit">Enviar</button>
        </form>

        <div class="auth-footer">
            <a href="login.html">¿Ya tienes una cuenta? Inicia sesión</a>
        </div>
    </div>

    <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
        <script>
            Swal.fire({
                icon: '<?= $_GET['status'] === 'success' ? 'success' : 'error' ?>',
                title: '<?= $_GET['status'] === 'success' ? '¡Todo listo!' : 'Ups...' ?>',
                text: '<?= htmlspecialchars(urldecode($_GET["message"]), ENT_QUOTES, "UTF-8") ?>',
                confirmButtonText: 'Aceptar'
            });
        </script>
    <?php endif; ?>

    <script>
        function actualizarRol() {
            const checkbox = document.getElementById('especialistaCheck');
            const campoDocumento = document.getElementById('campo-documento');
            const inputRol = document.getElementById('rol');

            if (checkbox.checked) {
                campoDocumento.style.display = "block";
                inputRol.value = "2"; // especialista
            } else {
                campoDocumento.style.display = "none";
                inputRol.value = "1"; // usuaria
            }
        }

        const form = document.getElementById('registroForm');

        const validators = {
            nombre: value => {
                if (value.trim() === '') return 'El nombre es obligatorio';
                if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(value.trim())) return 'El nombre solo puede contener letras y espacios';
                return true;
            },
            apellidos: value => {
                if (value.trim() === '') return 'Los apellidos son obligatorios';
                if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(value.trim())) return 'Los apellidos solo pueden contener letras y espacios';
                return true;
            },
            nickname: value => {
                if (value.trim() === '') return 'El nombre de usuario es obligatorio';
                if (!/^[a-zA-Z0-9_]{4,20}$/.test(value)) return 'El nombre de usuario debe tener entre 4 y 20 caracteres (letras, números, guion bajo)';
                return true;
            },
            correo: value => {
                if (value.trim() === '') return 'El correo es obligatorio';
                if (!/\S+@\S+\.\S+/.test(value)) return 'Correo electrónico inválido';
                return true;
            },
            contraseña: value => {
                if (value.trim() === '') return 'La contraseña es obligatoria';
                if (!/^[a-zA-Z0-9._]{8,}$/.test(value))
                    return 'La contraseña debe tener al menos 8 caracteres y solo puede contener letras, números, puntos y guiones bajos';
                return true;
            },
            conContraseña: (value, form) => {
                if (value.trim() === '') return 'La confirmación de contraseña es obligatoria';
                if (value !== form.contraseña.value) return 'Las contraseñas no coinciden';
                return true;
            },
            fecha_nac: value => {
                if (!value) return 'La fecha de nacimiento es obligatoria';
                const fecha = new Date(value);
                const hoy = new Date();
                if (fecha > hoy) return 'La fecha de nacimiento no puede ser futura';
                const edad = hoy.getFullYear() - fecha.getFullYear();
                if (edad < 18) return 'Debes ser mayor de 18 años para registrarte';
                return true;
            },
            documento: (value, form) => {
                if (form.especialistaCheck.checked) {
                    if (!form.documento.files.length) return 'Debes subir un documento';
                    const file = form.documento.files[0];
                    const allowedTypes = ['application/pdf'];
                    if (!allowedTypes.includes(file.type)) return 'Formato no permitido. Solo se permiten archivos PDF';
                    if (file.size > 5 * 1024 * 1024) return 'El archivo no debe superar los 5MB';
                }
                return true;
            }
        };

        function showError(input, message) {
            const errorElem = document.getElementById('error' + input.id.charAt(0).toUpperCase() + input.id.slice(1));
            if (errorElem) {
                errorElem.textContent = message;
            }
            input.classList.add('invalid');
        }

        function clearError(input) {
            const errorElem = document.getElementById('error' + input.id.charAt(0).toUpperCase() + input.id.slice(1));
            if (errorElem) {
                errorElem.textContent = '';
            }
            input.classList.remove('invalid');
        }

        function validateField(input) {
            const val = input.value;
            const field = input.id;
            let result;

            if (field === 'conContraseña' || field === 'documento') {
                result = validators[field](val, form);
            } else {
                result = validators[field](val);
            }

            if (result !== true) {
                showError(input, result);
                return false;
            } else {
                clearError(input);
                return true;
            }
        }

        function validateAll() {
            let valid = true;
            Object.keys(validators).forEach(field => {
                const input = form[field];
                if (input) {
                    if (!validateField(input)) valid = false;
                }
            });
            return valid;
        }

        Object.keys(validators).forEach(field => {
            const input = form[field];
            if (input) {
                input.addEventListener('input', () => validateField(input));
                input.addEventListener('blur', () => validateField(input));
            }
        });

        const docInput = document.getElementById('documento');
        if (docInput) {
            docInput.addEventListener('change', function() {
                validateField(this);
            });
        }

        form.addEventListener('submit', e => {
            if (!validateAll()) {
                e.preventDefault();
            }
        });
    </script>

</body>

</html>