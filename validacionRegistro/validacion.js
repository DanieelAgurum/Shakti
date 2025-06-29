function actualizarRol() {
    const checkbox = document.getElementById('especialistaCheck');
    const inputRol = document.getElementById('rol');
    inputRol.value = checkbox.checked ? "2" : "1";
    console.log("Rol actualizado a:", inputRol.value);
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
        if (!/^[a-zA-Z0-9_]{4,20}$/.test(value)) return 'Debe tener entre 4 y 20 caracteres (letras, números, guion bajo)';
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
            return 'Debe tener al menos 8 caracteres y solo puede contener letras, números, puntos y guiones bajos';
        return true;
    },
    conContraseña: (value, form) => {
        if (value.trim() === '') return 'La confirmación es obligatoria';
        if (value !== form.contraseña.value) return 'Las contraseñas no coinciden';
        return true;
    },
    fecha_nac: value => {
        if (!value) return 'La fecha es obligatoria';
        const fecha = new Date(value);
        const hoy = new Date();
        if (fecha > hoy) return 'No puede ser futura';
        const edad = hoy.getFullYear() - fecha.getFullYear();
        if (edad < 18) return 'Debes tener al menos 18 años';
        return true;
    }
};

function showError(input, message) {
    const errorElem = document.getElementById('error' + input.id.charAt(0).toUpperCase() + input.id.slice(1));
    if (errorElem) errorElem.textContent = message;
    input.classList.add('invalid');
}

function clearError(input) {
    const errorElem = document.getElementById('error' + input.id.charAt(0).toUpperCase() + input.id.slice(1));
    if (errorElem) errorElem.textContent = '';
    input.classList.remove('invalid');
}

function validateField(input) {
    const val = input.value;
    const field = input.id;
    let result;

    if (field === 'conContraseña') {
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

function validateTerminos() {
    const checkbox = document.getElementById('terminosCheck');
    const errorElem = document.getElementById('errorTerminos');

    if (!checkbox.checked) {
        errorElem.textContent = 'Debes aceptar los términos y condiciones';
        checkbox.classList.add('invalid');
        return false;
    } else {
        errorElem.textContent = '';
        checkbox.classList.remove('invalid');
        return true;
    }
}

function validateAll() {
    let valid = true;

    Object.keys(validators).forEach(field => {
        const input = form[field];
        if (input && !validateField(input)) valid = false;
    });

    if (!validateTerminos()) valid = false;

    return valid;
}

Object.keys(validators).forEach(field => {
    const input = form[field];
    if (input) {
        input.addEventListener('input', () => validateField(input));
        input.addEventListener('blur', () => validateField(input));
    }
});

// Validar términos al marcar/desmarcar
document.getElementById('terminosCheck').addEventListener('change', validateTerminos);

form.addEventListener('submit', e => {
    if (!validateAll()) {
        e.preventDefault();
    }
});