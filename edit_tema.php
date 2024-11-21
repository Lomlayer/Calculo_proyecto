<?php
session_start();
require 'conexion.php'; // Conexión a la base de datos

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit;
}

// Verificar si se pasó un ID de tema para editar
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: temario.php");
    exit;
}

$id = $_GET['id'];

// Obtener los datos del tema desde la base de datos
$sql = "SELECT * FROM temas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el tema existe
if ($result->num_rows == 0) {
    echo "<p>El tema no existe.</p>";
    exit;
}

$tema = $result->fetch_assoc();

// Actualizar los datos del tema si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_tema'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    // Actualizar el tema en la base de datos
    $update_sql = "UPDATE temas SET titulo = ?, descripcion = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $titulo, $descripcion, $id);
    $update_stmt->execute();

    // Redirigir al listado de temas después de la actualización
    header("Location: temario.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tema</title>
    <link rel="stylesheet" href="css/temario.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
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
            margin-bottom: 30px; /* Separar del formulario */
        }

        .navbar a {
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            margin-left: 10px;
            border-radius: 5px;
        }

        .navbar a:hover {
            background-color: #476ea1;
        }

        .edit-tema-form {
            background-color: #34495e;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            margin: 20px auto; /* Separar del borde */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-tema-form h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .edit-tema-form input,
        .edit-tema-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            background-color: #1c2833;
            color: #fff;
        }

        .edit-tema-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .edit-tema-form button:hover {
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
            <span>Editar Tema</span>
            <a href="cerrar.php">Cerrar Sesión</a>
        </div>

        <!-- Formulario para editar tema -->
        <div class="edit-tema-form">
            <h3>Editar Tema: <?php echo htmlspecialchars($tema['titulo']); ?></h3>
            <form method="POST" action="">
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($tema['titulo']); ?>" required>
                <textarea name="descripcion" placeholder="Descripción del tema" rows="4" required><?php echo htmlspecialchars($tema['descripcion']); ?></textarea>
                <button type="submit" name="update_tema">Actualizar Tema</button>
            </form>
        </div>

        <!-- Botón para regresar -->
        <a href="temario.php" class="regresar-btn">Regresar al Temario</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>
