<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Función para escapar HTML de manera segura
function safeHTML($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Validar el tipo MIME del archivo
function validateMimeType($file)
{
    $allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    return in_array($file['type'], $allowedTypes);
}

// Configurar límite de tamaño de archivo
$maxFileSize = 6 * 1024 * 1024; // 5 MB

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file = $_FILES["file"];

    if ($file["error"] === UPLOAD_ERR_OK && $file["size"] <= $maxFileSize && validateMimeType($file)) {
        $filename = "uploads/" . basename($file["name"]);

        if (move_uploaded_file($file["tmp_name"], $filename)) {
            // Utiliza PhpSpreadsheet para abrir y leer el archivo Excel
            $spreadsheet = IOFactory::load($filename);
            $worksheet = $spreadsheet->getActiveSheet();

            // Obtiene el rango de celdas utilizadas en la hoja de cálculo
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            // Obtiene los encabezados de la primera fila
            $headers = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = safeHTML($worksheet->getCellByColumnAndRow($col, 1)->getValue());
                $headers[] = $cellValue;
            }

            // generación del archivo HTML completo
            echo "<!DOCTYPE html>";
            echo "<html lang='es'>";
            echo "<head>";
            echo "<meta charset='UTF-8'>";
            echo "<title>Tabla de Excel Editable</title>";
            echo "<link rel='stylesheet' type='text/css' href='./css/styles.css'>";
            echo "</head>";
            echo "<body>";
            echo "<div class='container'>";
            echo "<h2>Contenido del Archivo Excel</h2>";

            // Generar la tabla HTML con encabezados dinámicos y campos de entrada editables
            echo "<form method='post' id='data-form'  action='guardar.php'>"; // Agrega un formulario para guardar los cambios
            echo "<table class='table' border='1'>";
            echo "<tr>";
            foreach ($headers as $header) {
                echo "<th>{$header}</th>";
            }
            echo "</tr>";

            for ($row = 2; $row <= $highestRow; $row++) {
                echo "<tr>";
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $cellValue = safeHTML($worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue());
                    $fieldName = "cell_{$row}_{$col}";
                    $cellType = $worksheet->getCellByColumnAndRow($col, $row)->getDataType();

                    // Establece el atributo 'type' del campo de entrada
                    if ($cellType == 's') {
                        $inputType = 'text';
                    } else if ($cellType == 'n') {
                        $inputType = 'number';
                    }

                    echo "<td><input type='{$inputType}' name='{$fieldName}' value='{$cellValue}' class='editable-input'></td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "<div class='button-container'><input type='submit' id='btn-guardar' class='btn-guardar' value='Guardar cambios'></div>"; // Agregar un botón para guardar los cambios
            echo "</form>";
            echo "</div>";
            echo "<script>";
            echo "var headers = " . json_encode($headers) . ";";
            echo "</script>";
            echo "<script src='js/enviarInformacionApi.js'></script>";
            echo "<script src='js/validarInput.js'></script>";
            echo "</body>";
            echo "</html>";
            
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        echo "Error en la carga del archivo.";
    }
} else {
    header("Location: index.php");
    exit;
}
