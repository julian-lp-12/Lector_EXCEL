function validarInput(input) {
    const cellType = input.getAttribute('data-cell-type');
    const inputValue = input.value.trim(); // Elimina espacios en blanco alrededor del valor

    const errorContainer = input.nextElementSibling; // Elemento hermano para mostrar mensajes de error

    if (cellType === 'number') {
        if (!isNumeric(inputValue)) {
            console.log(inputValue);
            showError(errorContainer, 'Este campo debe ser numérico.');
        } else {
            clearError(errorContainer);
        }
    } else if (cellType === 'text') {
        if (!isAlphabetic(inputValue)) {
            console.log(inputValue);
            showError(errorContainer, 'Este campo debe contener solo letras.');
        } else {
            clearError(errorContainer);
        }
        
    }
}

// Función auxiliar para verificar si un valor es numérico
function isNumeric(value) {
    
    return !isNaN(parseFloat(value)) && isFinite(value);
}

// Función auxiliar para verificar si un valor contiene solo letras
function isAlphabetic(value) {
    return /^[A-Za-z]+$/.test(value);
}

// Función para mostrar un mensaje de error
function showError(container, message) {
    if (!container) {
        container = document.createElement('div');
        container.className = 'error-message';
        input.parentNode.appendChild(container);
    }
    container.textContent = message;
}

// Función para borrar un mensaje de error
function clearError(container) {
    if (container) {
        container.parentNode.removeChild(container);
    }
}

// Agrega un evento 'input' para validación en tiempo real
const editableInputs = document.querySelectorAll('.editable-input');
editableInputs.forEach(function(input) {
    input.addEventListener('input', function() {
        validarInput(input);
    });
});
