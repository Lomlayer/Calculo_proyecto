<?php
session_start();
require 'conexion.php'; // Conexión a la base de datos

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit;
}

// Agregar tema
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_tema'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    // Insertar el nuevo tema en la base de datos
    $stmt = $conn->prepare("INSERT INTO temas (titulo, descripcion) VALUES (?, ?)");
    $stmt->bind_param("ss", $titulo, $descripcion);
    $stmt->execute();
    $stmt->close();

    // Redirigir al listado de temas después de agregar
    header("Location: temario.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Tema</title>
    <link rel="stylesheet" href="css/temario.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2c3e50;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding-left: 20px;
        }

        .sidebar a {
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            margin: 5px 0;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #476ea1;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }

        .navbar {
            background-color: #333;
            padding: 10px;
            color: #fff;
            text-align: center;
        }

        .add-tema-form {
            background-color: #34495e;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            margin: 20px auto; /* Separar del borde */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .add-tema-form input,
        .add-tema-form textarea {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            background-color: #1c2833;
            color: #fff;
        }

        .add-tema-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .add-tema-form button:hover {
            background-color: #45a049;
        }

        .regresar-btn {
            display: inline-block;
            background-color: #444;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            margin-top: 20px;
            border-radius: 5px;
        }

        .regresar-btn:hover {
            background-color: #666;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="index.php">Inicio</a>
        <a href="temario.php">Temario</a>
        <a href="cerrar s.php">Cerrar Sesión</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="navbar">
            <span>Agregar Tema</span>
        </div>

        <div class="add-tema-form">
            <h3>Agregar un nuevo tema</h3>
            <form method="POST" action="">
                <input type="text" name="titulo" placeholder="Título del tema" required>
                <textarea name="descripcion" placeholder="Descripción del tema" rows="4" required></textarea>
                <button type="submit" name="add_tema">Agregar Tema</button>
            </form>
        </div>

        <a href="temario.php" class="regresar-btn">Regresar al Temario</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>
