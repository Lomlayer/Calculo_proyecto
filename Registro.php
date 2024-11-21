<?php
// Incluir el archivo de conexión
include 'conexion.php'; // Asegúrate de que el archivo de conexión 'conexion.php' esté configurado correctamente.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $video_ayuda_1 = $_POST['video_ayuda_1'];
    $video_ayuda_2 = $_POST['video_ayuda_2'];

    // Directorios de destino para los archivos
    $dir_imagen = "imagenes/";
    $dir_pdf = "pdf/";

    // Verificar si las carpetas existen, si no, crearlas
    if (!is_dir($dir_imagen)) {
        mkdir($dir_imagen, 0777, true);  // Crear la carpeta de imágenes si no existe
    }

    if (!is_dir($dir_pdf)) {
        mkdir($dir_pdf, 0777, true);  // Crear la carpeta de PDFs si no existe
    }

    // Manejar la imagen cargada
    $imagen = $_FILES['imagen'];
    $imagen_nombre = $imagen['name'];
    $imagen_tmp = $imagen['tmp_name'];
    $imagen_error = $imagen['error'];

    // Manejar el archivo PDF cargado
    $pdf = $_FILES['pdf'];
    $pdf_nombre = $pdf['name'];
    $pdf_tmp = $pdf['tmp_name'];
    $pdf_error = $pdf['error'];

    // Verificar si hubo errores en la carga de los archivos
    if ($imagen_error === 0 && $pdf_error === 0) {
        // Generar un nombre único para los archivos
        $imagen_destino = $dir_imagen . uniqid('', true) . "." . pathinfo($imagen_nombre, PATHINFO_EXTENSION);
        $pdf_destino = $dir_pdf . uniqid('', true) . ".pdf";

        // Asegurarse de que el archivo PDF sea válido
        if ($pdf['type'] != 'application/pdf') {
            echo "<p style='color: red; text-align: center;'>El archivo PDF no es válido. Asegúrate de que sea un archivo PDF.</p>";
        } else {
            // Mover los archivos a las carpetas correspondientes
            if (move_uploaded_file($imagen_tmp, $imagen_destino) && move_uploaded_file($pdf_tmp, $pdf_destino)) {
                // Insertar datos en la base de datos (actualizar con la tabla 'tarjetas')
                $sql = "INSERT INTO tarjetas (titulo, descripcion, enlace_video1, enlace_video2, imagen_url, pdf_url)
                        VALUES ('$titulo', '$descripcion', '$video_ayuda_1', '$video_ayuda_2', '$imagen_destino', '$pdf_destino')";

                if ($conn->query($sql) === TRUE) {
                    echo "<p style='color: green; text-align: center;'>Tarjeta registrada exitosamente.</p>";
                    header("Location: index.php");
                } else {
                    echo "<p style='color: red; text-align: center;'>Error: " . $conn->error . "</p>";
                }
            } else {
                echo "<p style='color: red; text-align: center;'>Error al cargar los archivos. Intente de nuevo.</p>";
            }
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Hubo un error con los archivos cargados.</p>";
    }
}

// Cerrar la conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Tarjetas</title>
   <link rel="stylesheet" href="css/registro.css">
</head>
<body>

<div class="container">
    <h2>Registro de Tarjeta</h2>
    <form action="registro.php" method="post" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="Título" required>
        <label for="">Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>
        <textarea name="descripcion" placeholder="Descripción" required></textarea>
        <input type="text" name="video_ayuda_1" placeholder="Enlace Video de Ayuda 1" required>
        <input type="text" name="video_ayuda_2" placeholder="Enlace Video de Ayuda 2">
        <label for="">Archivo:</label>
        <input type="file" name="pdf" accept="application/pdf" required>
        <button type="submit">Registrar Tarjeta</button> <br>
        <!-- Botón de regresar -->
        <a href="index.php" class="back-button">Volver</a>
    </form>
</div>

</body>
</html>
