
function validarArchivo() {
    var archivoInput = document.querySelector('input[type="file"]');
    var archivo = archivoInput.files[0];
    
    if (!archivo) {
        alert('Por favor, selecciona un archivo.');
        return false;
    }
    
    var nombreArchivo = archivo.name;
    var extension = nombreArchivo.split('.').pop().toLowerCase();
    
    if (extension !== 'xlsx') {
        alert('El archivo debe tener extensión .xlsx');
        return false;
    }
    
    return true;
}

// Asociar la función de validación al evento submit del formulario
document.querySelector('form').addEventListener('submit', function(e) {
    if (!validarArchivo()) {
        e.preventDefault(); // Evitar que el formulario se envíe si la validación falla
    }
});


