const form = document.getElementById('actualizarForm');

const validators = {
    nombreN: value => {
        if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(value.trim())) return 'El nombre solo puede contener letras y espacios';
        return true;
    },
    apellidosN: value => {
        if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(value.trim())) return 'Los apellidos solo pueden contener letras y espacios';
        return true;
    },
    nicknameN: value => {
        if (!/^[a-zA-Z0-9_]{4,20}$/.test(value)) return 'Debe tener entre 4 y 20 caracteres (letras, números, guion bajo)';
        return true;
    },
    contraseñaN: value => {
        if (value.trim() !== '' && !/^[a-zA-Z0-9._]{8,}$/.test(value))
            return 'Debe tener al menos 8 caracteres y solo puede contener letras, números, puntos y guiones bajos';
        return true;
    },
    telefono: value => {
        if (value.trim() !== '' && !/^\d{7,15}$/.test(value.trim()))
            return 'Solo se permiten números (mínimo 7 dígitos)';
        return true;
    },
    fecha_nac: value => {
        if (!value.trim()) return true;
        const fecha = new Date(value);
        const hoy = new Date();
        if (fecha > hoy) return 'No puede ser futura';
        const edad = hoy.getFullYear() - fecha.getFullYear();
        if (edad < 18) return 'Debes tener al menos 18 años';
        return true;
    },
    direccion: value => {
        if (value.trim() !== '' && value.length > 150)
            return 'La dirección no debe exceder los 150 caracteres';
        return true;
    },
    descripcion: value => {
        if (value.trim() !== '' && value.length > 300)
            return 'La descripción no debe exceder los 300 caracteres';
        return true;
    }
};

function validateField(input) {
    const validator = validators[input.name];
    if (!validator) {
        console.warn(`No hay validador para ${input.name}`);
        return true;
    }
    const result = validator(input.value);
    if (result === true) {
        clearError(input);
        return true;
    } else {
        showError(input, result);
        return false;
    }
}

function showError(input, message) {
    const errorElem = document.getElementById('error' + capitalizeFirstLetter(input.id));
    if (errorElem) {
        errorElem.textContent = message;
    }
    input.classList.add('invalid');
}

function clearError(input) {
    const errorElem = document.getElementById('error' + capitalizeFirstLetter(input.id));
    if (errorElem) {
        errorElem.textContent = '';
    }
    input.classList.remove('invalid');
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

Object.keys(validators).forEach(field => {
    const input = form.querySelector(`[name="${field}"]`);
    if (input) {
        input.addEventListener('input', () => validateField(input));
        input.addEventListener('blur', () => validateField(input));
    } else {
        console.warn(`No se encontró input con name="${field}"`);
    }
});

form.addEventListener('submit', function (e) {
    let isValid = true;

    Object.keys(validators).forEach(field => {
        const input = form.querySelector(`[name="${field}"]`);
        if (input) {
            if (!validateField(input)) {
                isValid = false;
            }
        }
    });

    const alertBox = document.getElementById('formErrorAlert');

    if (!isValid) {
        e.preventDefault();
        alertBox.textContent = 'Asegúrate de que toda la información sea válida';
        alertBox.classList.remove('d-none');

        alertBox.scrollIntoView({
            behavior: 'smooth'
        });
    } else {
        alertBox.classList.add('d-none');
    }
});