<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo Excel</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Subir un Archivo Excel</h1>
    </header>
    <main>
        <section class="upload-form">
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <label for="file">Seleccionar un archivo Excel (.xlsx):</label>
                <input type="file" name="file" id="file" accept=".xlsx">
                <button type="submit">Subir</button>
            </form>
        </section>
    </main>
    <script src="js/validarArchivo.js"></script>
</body>
</html>
