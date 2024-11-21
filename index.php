<?php
session_start();
include 'conexion.php';

// Verificación de sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: inicio s.php");
    exit;
}

$role = $_SESSION['role'];
$user_name = $_SESSION['user_name']; // Obtener el nombre del usuario

// Verificar si el admin intenta eliminar una tarjeta
if (isset($_GET['delete_id']) && $role == 1) {
    $delete_id = $_GET['delete_id'];

    // Eliminar la tarjeta de la base de datos
    $delete_sql = "DELETE FROM tarjetas WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirigir después de la eliminación
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto de Funciones</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

    <!-- Barra lateral -->
    <div class="sidebar">
        <a href="index.php">Inicio</a>
        <?php if ($role == 1) { ?>
            <a href="registro.php">Agregar Tarjeta</a>
            <a href="lista_admins.php">Lista de Admins</a>
        <?php } ?>
        <a href="Buscar.php">Buscar</a>
        <!-- Opción Temario -->
        <a href="temario.php" class="temario">Temario</a>
    </div>

    <!-- Botón hamburguesa (para móviles) -->
    <span class="hamburger" onclick="toggleSidebar()">&#9776;</span>

    <!-- Contenido principal -->
    <div class="content">
        <div class="navbar">
            <div class="left">
                <span>Hola, <?php echo htmlspecialchars($user_name); ?>!</span>
            </div>
            <div class="right">
                <a href="cerrar s.php">Cerrar Sesión</a>
            </div>
        </div>

        <!-- Botón de Agregar nueva tarjeta (solo visible para Admin) -->
        <?php if ($role == 1) { ?>
            <div class="add-button">
                <a href="registro.php">Agregar Nueva Tarjeta</a>
            </div>
        <?php } ?>

        <!-- Tarjetas -->
        <div class="cards-container">
            <?php
            $sql = "SELECT * FROM tarjetas";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card'>";
                    if ($role == 1) {
                        echo "<form action='index.php' method='get' style='position: absolute; top: 10px; right: 10px;'>";
                        echo "<button type='submit' name='delete_id' value='" . $row['id'] . "' class='delete-btn'>";
                        echo "<img src='https://img.icons8.com/ios-filled/50/000000/trash.png' alt='Eliminar'>";
                        echo "</button>";
                        echo "</form>";
                    }
                    echo "<h3>" . htmlspecialchars($row['titulo']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['descripcion']) . "</p>";

                    if (!empty($row['imagen_url'])) {
                        echo "<img src='" . htmlspecialchars($row['imagen_url']) . "' alt='Imagen' style='width: 100%; height: auto; border-radius: 5px; margin-bottom: 10px;'>";
                    }

                    echo "<p><strong>Videos de ayuda:</strong></p>";
                    if (!empty($row['enlace_video1'])) {
                        echo "<a href='" . htmlspecialchars($row['enlace_video1']) . "' target='_blank'>1. Video Ayuda 1</a><br>";
                    }
                    if (!empty($row['enlace_video2'])) {
                        echo "<a href='" . htmlspecialchars($row['enlace_video2']) . "' target='_blank'>2. Video Ayuda 2</a><br>";
                    }

                    if (!empty($row['pdf_url'])) {
                        echo "<br><a href='" . htmlspecialchars($row['pdf_url']) . "' target='_blank'>Nota Cornell</a>";
                    }

                    echo "</div>"; // Cerrar tarjeta
                }
            } else {
                echo "<p>No hay tarjetas disponibles.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>

</body>
</html>
