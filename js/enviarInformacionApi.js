document.addEventListener('DOMContentLoaded', function () {
    var dataForm = document.getElementById('data-form');
    var saveButton = document.getElementById('btn-guardar'); 

    // Variable para rastrear si se ha enviado la solicitud
    var isRequestSent = false;

    dataForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Previene el envío del formulario por defecto

        // Si la solicitud ya se ha enviado, no hagas nada
        if (isRequestSent) {
            return;
        }

        // Muestra un mensaje de confirmación utilizando un alert
        var confirmationMessage = '¿Seguro que quieres enviar los cambios?';
        var isConfirmed = window.confirm(confirmationMessage);

        if (isConfirmed) {
            // Deshabilita el botón "Guardar cambios" para prevenir clics adicionales
            saveButton.disabled = true;

            // Cambia el texto del botón para indicar que se está enviando la solicitud
            saveButton.value = 'Enviando....';

            // Recopila los datos de la tabla y crea un objeto JSON
            var tableData = [];
            var table = document.querySelector('table');
            var rows = table.querySelectorAll('tr');

            // Recorre las filas de la tabla (excluyendo la primera fila de encabezados)
            for (var i = 1; i < rows.length; i++) {
                var rowData = {};
                var cells = rows[i].querySelectorAll('td');

                for (var j = 0; j < cells.length; j++) {
                    var headerName = headers[j]; // Obtiene el nombre del encabezado correspondiente
                    var cellValue = cells[j].querySelector('input').value;
                    rowData[headerName] = cellValue; // Utiliza el nombre del encabezado como clave
                }

                tableData.push(rowData);
            }

            var jsonData = JSON.stringify(tableData);
            console.log(jsonData);
            
            // Envía los datos JSON a una API utilizando fetch
            fetch('URL_DE_LA_API', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: jsonData
            })
                .then(function (response) {
                    if (response.ok) {
                        // El envío a la API fue exitoso
                        alert('Los cambios se han guardado correctamente.');
                    } else {
                        // Manejar errores de la API
                        alert('Error al guardar los cambios en la API.');
                        saveButton.value = 'Error al enviar';
                        saveButton.style.backgroundColor = 'red';
                    }
                })
                .catch(function (error) {
                    // Manejar errores de red u otros errores
                    alert('Ocurrió un error al intentar guardar los cambios.');
                    console.error(error);
                });
            
            // Marca que la solicitud se ha enviado
            isRequestSent = true;
        }
    });
});
