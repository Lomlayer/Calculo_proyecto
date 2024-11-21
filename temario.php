<?php
session_start();
require 'conexion.php'; // Conexión a la base de datos

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Obtener todos los temas con sus datos
$sql = "SELECT * FROM temas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temario</title>
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
        }

        .temario-container {
            margin-top: 20px;
        }

        .tema {
            background-color: #34495e;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .tema h3 {
            color: #fff;
        }

        .tema p {
            color: #d5d8dc;
        }

        .tema hr {
            border: 0;
            border-top: 1px solid #555;
            margin: 15px 0;
        }

        .admin-actions a {
            color: #f39c12;
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
            padding: 5px 10px;
            border-radius: 4px;
            background-color: #34495e;
            transition: background-color 0.3s;
        }

        .admin-actions a:hover {
            background-color: #476ea1;
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
        <?php if ($_SESSION['role'] == 1): ?>
            <a href="agregar_tema.php">Agregar Tema</a>
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="content">
    <div class="navbar">
        <span>Temario</span>
        <a href="cerrar.php">Cerrar Sesión</a>
    </div>

        <div class="temario-container">
            <h2>Listado de Temas</h2>

            <?php
            if ($result->num_rows > 0) {
                // Recorrer los temas y mostrar los datos
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='tema'>";
                    echo "<h3>" . htmlspecialchars($row['titulo']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['descripcion']) . "</p>";
                    echo "<hr>";

                    // Botones de editar y eliminar solo para administradores
                    if ($_SESSION['role'] == 1) {
                        echo "<div class='admin-actions'>
                                <a href='edit_tema.php?id=" . $row['id'] . "'>Editar</a>
                                <a href='delete_tema.php?id=" . $row['id'] . "'>Eliminar</a>
                              </div>";
                    }

                    echo "</div>";  // Cierre del div 'tema'
                }
            } else {
                echo "<p>No hay temas disponibles.</p>";
            }
            ?>

            <a href="index.php" class="regresar-btn">Regresar</a>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>
